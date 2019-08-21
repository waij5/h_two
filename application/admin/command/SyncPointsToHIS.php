<?php

namespace app\admin\command;

use app\admin\model\AccountLog;
use app\admin\model\Customer;
use app\admin\model\CustomerMap;
use app\admin\model\MallSync;
use app\admin\model\Msg;
use app\admin\model\Msgtype;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;
use think\Log;

class SyncPointsToHIS extends Command
{

    protected $batchLimit = 200;

    protected function configure()
    {
        $this
            ->setName('syncpointstoHIS')
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
        $startMTime  = microtime(true);
        $currentTime = $startMTime;

        $output->info('Starting to sync points to HIS...' . date('Y-m-d H:i:s'));
        $mallSync = MallSync::instance();
        //获取 已映射好的 顾客列表
        $mappedUsersTotal = CustomerMap::where(['status' => 1])->count();
        $batchMax         = ceil($mappedUsersTotal / $this->batchLimit);
        $output->info('mapped users total: ' . $mappedUsersTotal . '; batch total: ' . $batchMax);

        // $mappedUserIds = CustomerMap::where(['status' => 1])->order('id', 'ASC')->column('user_id', 'customer_id');
        //顾客 ID 分块处理
        // $mappedUserIdsBlocks = array_chunk($mappedUserIds, 500, true);

        // foreach ($mappedUserIdsBlocks as $blockIndex => $blockUserIds) {
        for ($currentBatch = 1; $currentBatch <= $batchMax; $currentBatch++) {
            $batchOffset = ($currentBatch - 1) * $this->batchLimit;
            $mappedUsers = CustomerMap::where(['status' => 1])
                ->order('id', 'ASC')
                ->limit($batchOffset, $this->batchLimit)
                ->column('user_id', 'customer_id');

            if (empty($mappedUsers)) {
                continue;
            }

            $currentPage = 0;
            $hasMore     = false;
            do {
                $successLogIds = array();
                $currentPage++;

                $tmpCurrentTime = microtime(true);
                $output->info('Starting to deal data for batch ' . $currentBatch . ' page ' . $currentPage . ' time ' . date('Y-m-d H:i:s') . ' Spent ' . ($tmpCurrentTime - $currentTime));
                $currentTime = $tmpCurrentTime;
                $response    = $mallSync->getMallPointsChange($currentPage, $mappedUsers);
                $data        = json_decode(MallSync::prepareJSON($response), true);
                if (empty($data)) {
                    $hasMore = false;
                } else {
                    $list    = $data['list'];
                    $hasMore = $data['hasMore'];
                    unset($response);
                    unset($data);

                    $syncTime = time();
                    foreach ($list as $key => $changeLog) {
                        $customerId = array_search($changeLog['user_id'], $mappedUsers);
                        if (empty($customerId)) {
                            Log::record('Warning: sync points to HIS failed, customer related does not exist! (log_id: ' . $changeLog['log_id'] . ' * user_id: ' . $changeLog['user_id'] . ')');
                            continue;
                        }

                        $changeTime = MallSync::gm2LocalTime($changeLog['change_time']);
                        if (Customer::logAccountChange($customerId, 0, 0, $changeLog['rank_points'], $changeLog['pay_points'], 0, 0, $changeTime, AccountLog::TYPE_MALL_IMPORT, $changeLog['change_desc'], $changeLog['ip'], 'MALL', $syncTime)) {
                            array_push($successLogIds, $changeLog['log_id']);
                        } else {
                            $output->info('fail to log_account_change.' . PHP_EOL . 'log_info: ' . var_export($changeLog, true));
                        }
                    }

                    //同步成功回传
                    if (!empty($successLogIds) && $mallSync->getMallPointsChangeBack($successLogIds) === false) {
                        Log::record('Sync points to HIS warning: getMallPointsChangeBack failed');
                        $msg              = new Msg;
                        $msg->msg_type    = Msgtype::TYPE_SYSTEM;
                        $msg->msg_from    = 0;
                        $msg->msg_to      = 1;
                        $msg->msg_title   = '积分同步警告';
                        $msg->msg_content = '积分同步至HIS系统成功，回传数据至MALL失败，请检查相关日志后处理！  原MALL变动日志ID(' . implode(',', $successLogIds) . ')';
                        $msg->save();
                    }
                }
            } while ($hasMore == true);
        }

        $endTime = microtime(true);
        $output->info('Spent time: ' . ($endTime - $startMTime) . 's');
    }
}
