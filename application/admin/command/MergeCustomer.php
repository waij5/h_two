<?php

namespace app\admin\command;

use app\admin\model\AccountLog;
use app\admin\model\Customer;
use app\admin\model\CustomerMap;
use app\admin\model\CustomerMapLog;
use app\admin\model\MallSync;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use think\Db;
use think\Log;
use yjy\exception\TransException;

class MergeCustomer extends Command
{
    protected function configure()
    {
        $this
            ->setName('mergecustomer')
            ->addOption('record', 'r', option::VALUE_OPTIONAL, 'command record id')
            ->addOption('type', 't', option::VALUE_REQUIRED, 'type check all unsynced users or unsynced users which last logined in three months(ALL / PART)', 'PART')
            ->setDescription('Merge customer from HIS AND MALL');
    }

    /**
     * 分批查询记录
     * 按记录数划分为不同EXCEL文件
     * 打包文件，删除EXCEL
     * 记录压缩文件位置
     * 注意中文路径的转换，存储文件时转换，存入DB时未转换
     * -- 由于记录条数较少，不做划分EXCEL处理 --
     */
    protected function execute(Input $input, Output $output)
    {
        ini_set('memory_limit', '512M');
        $startMTime = microtime(true);

        $extraParams = array();
        $type        = $input->getOption('type');
        $output->info('type : ' . $type);

        if (strtoupper($type) != 'ALL') {
            $lastLoginTime             = strtotime('-3 months', strtotime(date('Y-m-d'))) - date('Z');
            $extraParams['last_login'] = $lastLoginTime;
        }

        $currentPage = 0;
        $hasMore     = true;

        $mallSync = MallSync::instance();

        //清除LOG表数据
        Db::execute('truncate ' . CustomerMapLog::getTable());
        do {
            $currentPage++;
            $output->info('Starting dealing data for page ' . $currentPage);
            $response = $mallSync->getUnSyncedUsers($currentPage, $extraParams);

            if (empty($response)) {
                $hasMore = false;
            } else {
                $data = json_decode(MallSync::prepareJSON($response), true);
                if (empty($data)) {
                    $hasMore = false;
                } else {
                    $list    = $data['list'];
                    $hasMore = $data['hasMore'];
                    unset($response);
                    unset($data);
                    // $useIds = array_column($list, 'user_id');
                    $mobiles = array_column($list, 'mobile_phone');

                    $syncData = array();

                    $yjyCustomers = Customer::where(['ctm_mobile' => ['in', $mobiles]])->field('ctm_id, ctm_mobile, ctm_tel, ctm_name,ctm_rank_points, ctm_pay_points, ctm_remark')->select();

                    foreach ($yjyCustomers as $yjyCustomer) {
                        $listKey = array_search($yjyCustomer->ctm_mobile, $mobiles);
                        if ($listKey === false) {
                            continue;
                        }

                        $ecsUser = $list[$listKey];

                        try {
                            Db::startTrans();
                            if ($ecsUser['real_name'] == $yjyCustomer->ctm_name) {
                                // 完全匹配
                                $customerMap = new CustomerMap;
                                $validStatus = 1;
                            } else {
                                //结构完全一致，每天重置数据，分表是为了MAP表内数据尽量保存有效数据
                                $customerMap = new CustomerMapLog;
                                $validStatus = 0;
                            }

                            $currentSyncTime = time();

                            $customerMap->user_id     = $ecsUser['user_id'];
                            $customerMap->customer_id = $yjyCustomer->ctm_id;

                            $customerMap->mobile    = $ecsUser['mobile_phone'];
                            $customerMap->phone     = $yjyCustomer->ctm_tel;
                            $customerMap->ctm_name  = $yjyCustomer->ctm_name;
                            $customerMap->user_name = $ecsUser['user_name'];
                            $customerMap->real_name = $ecsUser['real_name'];
                            $customerMap->nick_name = $ecsUser['nick_name'];

                            $customerMap->rank_points = $ecsUser['rank_points'] + $yjyCustomer->ctm_rank_points;
                            $customerMap->pay_points  = $ecsUser['pay_points'] + $yjyCustomer->ctm_pay_points;
                            // 暂时设为0
                            $customerMap->deposit_amt = 0;
                            // 默认未完全匹配
                            $customerMap->status    = $validStatus;
                            $customerMap->sync_time = $validStatus ? $currentSyncTime : 0;
                            if ($customerMap->save()) {
                                //因为唯一索引(customer_map uniq_user_id) 及 其它可能的数据库异常，回馈到商城的顾客同步信息延迟在保存成功后写入
                                if ($validStatus) {
                                    array_push($syncData, array('user_id' => $ecsUser['user_id'], 'yjy_customer_id' => $yjyCustomer->ctm_id, 'rank_points' => $yjyCustomer->ctm_rank_points, 'pay_points' => $yjyCustomer->ctm_pay_points));

                                    //===========================
                                    //积分合并处理
                                    if ($ecsUser['rank_points'] != 0 || $ecsUser['pay_points'] != 0) {
                                        if ($yjyCustomer->log_account_change(0, 0, $ecsUser['rank_points'], $ecsUser['pay_points'], 0, $currentSyncTime, AccountLog::TYPE_MALL_MERGE, $changeDesc = '', 'SYSTEM', 'MALL', $currentSyncTime) === false) {
                                            throw new TransException('Merge customer: failed to change customer info!');
                                        }
                                    }
                                    //更新原变动日志为已同步
                                    AccountLog::update(['sync_time' => $currentSyncTime], ['customer_id' => $yjyCustomer->ctm_id, 'sync_time' => 0]);
                                }
                            }

                            Db::commit();
                        } catch (\think\exception\PDOException $e) {
                            Db::rollback();
                            Log::record('MergeCustomer PDOException: ' . $e->getMessage());
                        } catch (TransException $e) {
                            Db::rollback();
                            Log::record('MergeCustomer TransException: ' . $e->getMessage());
                        }
                    }
                    unset($yjyCustomers);
                    unset($list);

                    // 将匹配到的顾客信息回传至 商城系统
                    // 并协调积分(保证总额一致), 并至MALL原积分变动为已更新
                    $mallSync->postSyncUsersToMall($syncData);
                }
            }
            sleep(1);
        } while ($hasMore == true);

        $endTime = microtime(true);

        $output->info('Spent time: ' . ($endTime - $startMTime) . 's    page: ' . $currentPage);

    }

    public function prepareJSON($input)
    {
        if (substr($input, 0, 3) == pack("CCC", 0xEF, 0xBB, 0xBF)) {
            $input = substr($input, 3);
        }
        return $input;
    }
}
