<?php

namespace app\admin\command;

use app\admin\model\AccountLog;
use app\admin\model\CmdRecords;
use app\admin\model\Customer;
use app\admin\model\CustomerBalance;
use app\admin\model\CustomerConsult;
use app\admin\model\CustomerOsconsult;
use app\admin\model\MonthCustomerStat;
use app\admin\model\OperateBook;
use app\admin\model\OrderItems;
use app\admin\model\Rvinfo;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use think\Db;
use think\Log;
use yjy\exception\TransException;

class MergeHisCustomer extends Command
{
    /**
     * 主顾客ID, 合并顾客ID   可能有其它参数
     * params 序列化后[serialized]的其它参数
     */
    protected function configure()
    {
        $this
            ->setName('mergehiscustomer')
            ->addOption('force', 'f', Option::VALUE_OPTIONAL, 'force override', false)
            ->addOption('record', 'r', option::VALUE_REQUIRED, 'command record id')
            // ->addOption('main', 'm', option::VALUE_REQUIRED, 'main customer id')
            // ->addOption('second', 's', option::VALUE_REQUIRED, 'second customer id')
            // ->addOption('params', 'p', option::VALUE_OPTIONAL, 'other params(serialized string)')
            ->setDescription('Merge customer from HIS AND MALL');

    }

    protected function execute(Input $input, Output $output)
    {
        //覆盖安装
        $force    = $input->getOption('force');
        $recordId = $input->getOption('record');

        $cmdRecord = CmdRecords::find($recordId);
        if ($cmdRecord == null) {
            return;
        }
        //开始毫秒数
        $startMTime            = microtime(true);
        $cmdRecord->createtime = time();
        $cmdRecord->save();

        $params = json_decode($cmdRecord->params, true);
        //参数异常，取消生成
        if (!isset($params['where']) || !isset($params['extraWhere']) || !isset($params['extra'])) {
            $processJson = array(
                'completedCount' => 0,
                'total'          => 0,
                'status'         => CmdRecords::STATUS_FAILED,
                'statusText'     => CmdRecords::STATUS_FAILED,
            );
            $cmdRecord->process = json_encode($processJson);
            $cmdRecord->status  = CmdRecords::STATUS_FAILED;
            $cmdRecord->save();
            return false;
        } else {
            $mainCustomerId   = $params['extra']['main'];
            $secondCustomerId = $params['extra']['second'];
            $params           = $params['extra']['param'];
        }

        // $mainCustomerId   = $input->getOption('main');
        // $secondCustomerId = $input->getOption('second');
        // $params           = unserialize($input->getOption('params'));

        try {
            //卡号相同直接返回
            if ($mainCustomerId == $secondCustomerId) {
                $processJson = array(
                    'completedCount' => 1,
                    'total'          => 1,
                    'status'         => CmdRecords::STATUS_COMPLETED,
                    'statusText'     => CmdRecords::STATUS_COMPLETED,
                );
                $cmdRecord->process = json_encode($processJson);
                $cmdRecord->status  = CmdRecords::STATUS_COMPLETED;
                $cmdRecord->save();

                return true;
            }

            //顾客基本信息获取 判断
            $mainCustomer   = Customer::find($mainCustomerId);
            $secondCustomer = Customer::find($secondCustomerId);

            if (empty($mainCustomer) || empty($secondCustomer)) {
                $output->warning('One or more customer could not be found[ ' . $mainCustomerId . ' | ' . $secondCustomerId . ' ]');
                return false;
            }

            $processJson = array(
                'completedCount' => 0,
                'total'          => 11,
                'status'         => CmdRecords::STATUS_PROCESSING,
                'statusText'     => CmdRecords::STATUS_PROCESSING,
            );
            $cmdRecord->process = json_encode($processJson);
            $cmdRecord->status  = CmdRecords::STATUS_PROCESSING;
            $cmdRecord->save();

            $currentCount = 0;
            Db::startTrans();

            //back second customer
            // $backSettings = [table_name => [conditions]];
            // file_put_contents('fsdf/', 'back customer_id : ');
            // foreach ($backSettings as $tableName => $setting) {
            //     $pp = Db::table($tableName)->where($setting)->column();
            //     file_put_contents('fsdf/', '\r\n' . json_encode($pp, JSON_UNESCAPED_UNICODE), FILE_APPEND);
            // }
            // file_put_contents('fsdf/', '\r\n' . 'back end');

            //顾客基本信息处理 如 定金，优惠券，消费金额，积分 等相加
            $mainCustomer->ctm_rank_points = $mainCustomer->ctm_rank_points + $secondCustomer->ctm_rank_points;
            $mainCustomer->ctm_pay_points  = $mainCustomer->ctm_pay_points + $secondCustomer->ctm_pay_points;
            $mainCustomer->ctm_depositamt  = $mainCustomer->ctm_depositamt + $secondCustomer->ctm_depositamt;
            $mainCustomer->ctm_affiliate   = $mainCustomer->ctm_affiliate + $secondCustomer->ctm_affiliate;
            $mainCustomer->ctm_coupamt     = $mainCustomer->ctm_coupamt + $secondCustomer->ctm_coupamt;
            $mainCustomer->ctm_salamt      = $mainCustomer->ctm_salamt + $secondCustomer->ctm_salamt;
            $mainCustomer->save();

            //进度信息
            $processJson = array(
                'completedCount' => ++$currentCount,
                'total'          => 11,
                'status'         => CmdRecords::STATUS_PROCESSING,
                'statusText'     => '合并基本信息记录中',
            );
            $cmdRecord->delayUpdateProcessInfo($currentCount, $processJson, false);

            //客服记录合并 CustomerConsult
            //update cst set customer_id = mainCustomerId where customer_id = secondCustomerId;
            if (($cstUpdateRes = Db::table((new CustomerConsult)->getTable() . ' cst')->where('customer_id', '=', $secondCustomerId)->update(['customer_id' => $mainCustomerId])) === false) {
                throw new TransException("error occured when merge consult", 1);
            }
            //进度信息
            $processJson = array(
                'completedCount' => ++$currentCount,
                'total'          => 11,
                'status'         => CmdRecords::STATUS_PROCESSING,
                'statusText'     => '合并客服记录中',
            );
            $cmdRecord->delayUpdateProcessInfo($currentCount, $processJson, false);

            //现场记录合并 CustomerOsconsult
            //update osc set customer_id = mainCustomerId where customer_id = secondCustomerId;
            if (($cocUpdateRes = Db::table((new CustomerOsconsult)->getTable() . ' coc')->where('customer_id', '=', $secondCustomerId)->update(['customer_id' => $mainCustomerId])) === false) {
                throw new TransException("error occured when merge osconsult", 1);
            }
            //进度信息
            $processJson = array(
                'completedCount' => ++$currentCount,
                'total'          => 11,
                'status'         => CmdRecords::STATUS_PROCESSING,
                'statusText'     => '合并现场记录中',
            );
            $cmdRecord->delayUpdateProcessInfo($currentCount, $processJson, false);

            //回访记录合并 Rvinfo
            if (($rvinfoUpdateRes = Db::table((new Rvinfo)->getTable() . ' rvinfo')->where('customer_id', '=', $secondCustomerId)->update(['customer_id' => $mainCustomerId])) === false) {
                throw new TransException("error occured when merge rvinfo", 1);
            }
            //进度信息
            $processJson = array(
                'completedCount' => ++$currentCount,
                'total'          => 11,
                'status'         => CmdRecords::STATUS_PROCESSING,
                'statusText'     => '合并回访记录中',
            );
            $cmdRecord->delayUpdateProcessInfo($currentCount, $processJson, false);
            //收款记录合并 CustomerBalance
            if (($balanceUpdateRes = Db::table((new CustomerBalance)->getTable() . ' balance')->where('customer_id', '=', $secondCustomerId)->update(['customer_id' => $mainCustomerId])) === false) {
                throw new TransException("error occured when merge balance", 1);
            }
            //进度信息
            $processJson = array(
                'completedCount' => ++$currentCount,
                'total'          => 11,
                'status'         => CmdRecords::STATUS_PROCESSING,
                'statusText'     => '合并收款记录中',
            );
            $cmdRecord->delayUpdateProcessInfo($currentCount, $processJson, false);
            //订单合并 OrderItems  | 划扣相关 无需操作
            if (($itemsUpdateRes = Db::table((new OrderItems)->getTable() . ' order_items')->where('customer_id', '=', $secondCustomerId)->update(['customer_id' => $mainCustomerId])) === false) {
                throw new TransException("error occured when merge orderitems", 1);
            }
            //进度信息
            $processJson = array(
                'completedCount' => ++$currentCount,
                'total'          => 11,
                'status'         => CmdRecords::STATUS_PROCESSING,
                'statusText'     => '合并订单记录中',
            );
            $cmdRecord->delayUpdateProcessInfo($currentCount, $processJson, false);
            //帐户变动合并 AccountLog
            if (($accountlogUpdateRes = Db::table((new AccountLog)->getTable() . ' accountlog')->where('customer_id', '=', $secondCustomerId)->update(['customer_id' => $mainCustomerId])) === false) {
                throw new TransException("error occured when merge accountlog", 1);
            }
            //进度信息
            $processJson = array(
                'completedCount' => ++$currentCount,
                'total'          => 11,
                'status'         => CmdRecords::STATUS_PROCESSING,
                'statusText'     => '合并账户变动记录中',
            );
            $cmdRecord->delayUpdateProcessInfo($currentCount, $processJson, false);
            //手术排班 OperateBook
            if (($operaUpdateRes = Db::table((new OperateBook)->getTable() . ' operatebook')->where('customer_id', '=', $secondCustomerId)->update(['customer_id' => $mainCustomerId])) === false) {
                throw new TransException("error occured when merge operatebook", 1);
            }
            //进度信息
            $processJson = array(
                'completedCount' => ++$currentCount,
                'total'          => 11,
                'status'         => CmdRecords::STATUS_PROCESSING,
                'statusText'     => '合并手术排班记录中',
            );
            $cmdRecord->delayUpdateProcessInfo($currentCount, $processJson, false);

            //月度信息合并 MonthCustomerStat 同月份 相加
            //每个月所有客户不管有无变动都会有一条记录

            $mainCustomerstat = Db::table((new MonthCustomerStat)->getTable() . ' monthstat')->where('customer_id', '=', $mainCustomerId)->where('status', '=', 1)->order('stat_date', 'asc')->select();

            $secondCustomerstat = Db::table((new MonthCustomerStat)->getTable() . ' monthstat')->where('customer_id', '=', $secondCustomerId)->where('status', '=', 1)->order('stat_date', 'asc')->select();

            $mainStatDates   = array_column($mainCustomerstat, 'stat_date');
            $secondStatDates = array_column($secondCustomerstat, 'stat_date');

            //MAIN - SECOND | NO OPERATE

            //SECOND - MAIN 差集 | UPDATE
            $diffStatDates = array_diff($secondStatDates, $mainStatDates);
            MonthCustomerStat::update(['customer_id' => $mainCustomerId], ['customer_id' => $secondCustomerId, 'stat_date' => ['in', $diffStatDates]], ['customer_id']);

            //两个都有 交集 | UPDATE && DELETE
            $interStatDates = array_intersect($mainStatDates, $secondStatDates);
            $preHandleUpdateStats = MonthCustomerStat::where(function ($query) use ($mainCustomerId, $secondCustomerId) {
                $query->where('customer_id', '=', $mainCustomerId)->whereOr('customer_id', '=', $secondCustomerId);
            })
                ->where('stat_date', 'in', $interStatDates)
                ->where('status', '=', 1)
                ->order('stat_date', 'asc')
                ->select();

            // Log::record(print_r($preUpdateStats, true));
            // Db::rollback();
            // return;

            $allowFields = [
                // 'rec_id',
                // 'customer_id',
                // 'customer_name',
                'depositamt',
                'deposit_inc',
                'deposit_dec',
                'deposit_change',
                'undeducted_total',
                'not_out_total',
                'deducted_total',
                'deducted_benefit_total',
                'rank_points',
                'pay_points',
                'rank_points_change',
                'pay_points_change',
                'affiliate',
                'affiliate_change',
                'item_original_pay_total',
                'item_pay_total',
                'item_real_pay_total',
                'item_switch_total',
                'balance_total',
                // 'stat_date',
                // 'status',
                // 'createtime',

            ];

            $preUpdateStats = array();
            foreach ($preHandleUpdateStats as $key => $row) {
                if (!isset($preUpdateStats[$row->stat_date])) {
                    $preUpdateStats[$row->stat_date] = $row;
                } else {
                    foreach ($row->getData() as $rowKey => $rowValue) {
                        if (in_array($rowKey, $allowFields)) {
                            $preUpdateStats[$row->stat_date][$rowKey] = $preUpdateStats[$row->stat_date][$rowKey] + $rowValue;
                        }
                    }
                    $preUpdateStats[$row->stat_date]['rec_id'] = '';
                    MonthCustomerStat::update($preUpdateStats[$row->stat_date]->getData(), ['stat_date' => $row->stat_date, 'customer_id' => $mainCustomerId, 'status' => 1], $allowFields);
                }
            }
            MonthCustomerStat::where(['customer_id' => $secondCustomerId])->delete();
            //进度信息
            $processJson = array(
                'completedCount' => ++$currentCount,
                'total'          => 11,
                'status'         => CmdRecords::STATUS_PROCESSING,
                'statusText'     => '合并月度信息记录中',
            );
            $cmdRecord->delayUpdateProcessInfo($currentCount, $processJson, false);

            //首次受理工具等 可能需要根据两个客户的相关信息进行更新，如最早，最近 的客服，现场记录
            $mainConsult    = Db::table((new CustomerConsult)->getTable() . ' cst')->where('customer_id', '=', $mainCustomerId)->order('createtime', 'asc')->find();
            $firstOsconsult = Db::table((new CustomerOsconsult)->getTable() . ' coc')->where('customer_id', '=', $mainCustomerId)->order('createtime', 'asc')->find();
            $lastOsconsult  = Db::table((new CustomerOsconsult)->getTable() . ' coc')->where('customer_id', '=', $mainCustomerId)->order('createtime', 'desc')->find();
            $lastRvinfo     = Db::table((new Rvinfo)->getTable() . ' rvinfo')->where('customer_id', '=', $mainCustomerId)->order('rv_time', 'desc')->find();

            $mainCustomer->ctm_last_rv_time = $lastRvinfo && $lastRvinfo['rv_time'] ? $lastRvinfo['rv_time'] : 0;

            $mainCustomer->ctm_first_tool_id = 0;
            $mainCustomer->ctm_first_dept_id = 0;
            $mainCustomer->ctm_first_cpdt_id = 0;
            if ($mainCustomer) {
                $mainCustomer->ctm_first_tool_id = $mainConsult['tool_id'];
                $mainCustomer->ctm_first_dept_id = $mainConsult['dept_id'];
                $mainCustomer->ctm_first_cpdt_id = $mainConsult['cpdt_id'];
            }

            //首次/最近  网电/现场   ID，客服科室，时间，项目
            $mainCustomer->ctm_first_osc_id      = 0;
            $mainCustomer->ctm_first_recept_time = 0;
            $mainCustomer->ctm_first_osc_cpdt_id = 0;
            $mainCustomer->ctm_first_osc_dept_id = 0;
            if ($firstOsconsult) {
                $mainCustomer->ctm_first_osc_id      = $firstOsconsult['osc_id'];
                $mainCustomer->ctm_first_recept_time = $firstOsconsult['createtime'];
                $mainCustomer->ctm_first_osc_cpdt_id = $firstOsconsult['cpdt_id'];
                $mainCustomer->ctm_first_osc_dept_id = $firstOsconsult['dept_id'];
            }
            $mainCustomer->ctm_last_osc_id      = 0;
            $mainCustomer->ctm_last_recept_time = 0;
            $mainCustomer->ctm_last_osc_cpdt_id = 0;
            $mainCustomer->ctm_last_osc_dept_id = 0;
            if ($lastOsconsult) {
                $mainCustomer->ctm_last_osc_id      = $lastOsconsult['osc_id'];
                $mainCustomer->ctm_last_recept_time = $lastOsconsult['createtime'];
                $mainCustomer->ctm_last_osc_cpdt_id = $lastOsconsult['cpdt_id'];
                $mainCustomer->ctm_last_osc_dept_id = $lastOsconsult['dept_id'];
            }

            //额外参数处理 客户来源 营销渠道 营销人员 电话
            if ($params && is_array($params)) {
                $mainCustomer->ctm_tel     = $params['ctm_tel'];
                $mainCustomer->ctm_mobile  = $params['ctm_mobile'];
                $mainCustomer->ctm_source  = $params['ctm_source'];
                $mainCustomer->ctm_explore = $params['ctm_explore'];
                $mainCustomer->admin_id    = $params['admin_id'];
                $mainCustomer->save();
            }
            $mainCustomer->save();

            Customer::where(['ctm_id' => $secondCustomerId])->delete();
            //进度信息
            $processJson = array(
                'completedCount' => ++$currentCount,
                'total'          => 10,
                'status'         => CmdRecords::STATUS_COMPLETED,
                'statusText'     => '合并客户完成',
            );
            $cmdRecord->delayUpdateProcessInfo($currentCount, $processJson, false);

            Db::commit();
        } catch (\think\exception\PDOException $e) {
            Db::rollback();
            Log::record('MergeHisCustomer exception: : ' . $e->getMessage());
        } catch (TransException $e) {
            Db::rollback();
            Log::record('MergeHisCustomer exception: : ' . $e->getMessage());
        }

        $endTime = microtime(true);

        //A与B合并到A， 客服更新条数 $cstUpdateRes, 现场更新条数 $cstUpdateRes
        // $output->info('Spent time: ');
    }
}
