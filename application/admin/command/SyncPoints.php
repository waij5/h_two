<?php

namespace app\admin\command;

use app\admin\model\AccountLog;
use app\admin\model\CustomerMap;
use app\admin\model\MallSync;
use app\admin\model\SyncFailLog;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Log;

class SyncPointsToMall extends Command
{

    protected $batchLimit = 200;

    protected function configure()
    {
        $this
            ->setName('syncpoints')
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
        $type = $input->getOption('type') ?: 'ALL';
        $startMTime = microtime(true);

        $mallSync = MallSync::instance();

        //因为暂时只同步 积分， 只获取积分变动不为0的
        $total = AccountLog::alias('log')
            ->join(CustomerMap::getTable() . ' map', 'log.customer_id = map.customer_id')
            ->where(['log.sync_time' => ['eq', 0], 'source' => 'HIS'])
            ->where(function ($query) {
                $query->where('log.rank_points', 'neq', 0)->whereOr('log.pay_points', 'neq', 0);
            })
            ->count();
        $maxBatchCount  = ceil($total / $this->batchLimit);
        $currentCount   = 0;
        $currentBatchNo = 1;
        //因为会改动原表加一个最大ID处理保证数据获取正确 / 也可以 循环获取 batchlimit 条，直至获取条数 < batchlimit
        $currentMaxLogId = 0;

        //分批处理
        for (; $currentBatchNo <= $maxBatchCount; $currentBatchNo++) {
            $batchOffset = ($currentBatchNo - 1) * $this->batchLimit;

            //显示进度，按批
            $output->info('Processing...batchLimit: ' . $this->batchLimit . ' --- ' . str_pad((string) $currentBatchNo, 6, ' ', STR_PAD_LEFT) . ' / ' . str_pad((string) $maxBatchCount, 6, ' ', STR_PAD_LEFT));

            $logs = AccountLog::alias('log')
                ->join(CustomerMap::getTable() . ' map', 'log.customer_id = map.customer_id')
                ->where(['log.sync_time' => ['eq', 0], 'source' => 'HIS', 'log.log_id' => ['gt', $currentMaxLogId]])
                ->where(function ($query) {
                    $query->where('log.rank_points', 'neq', 0)->whereOr('log.pay_points', 'neq', 0);
                })
                ->order('log.log_id', 'ASC')
                ->column('log.log_id, map.user_id, log.rank_points, log.pay_points, log.change_desc');
            //原始 log_id 数组
            $postLogIs = array_column($logs, 'log_id');

            $response = MallSync::prepareJSON($mallSync->syncPoints($logs));
            unset($logs);
            $data = json_decode($response, true);
            unset($response);
            if (empty($data)) {
                Log::record('Warning: SyncPoints exception -- ' . date('Y-m-d'));
                unset($data);
                sleep(1);
                continue;
            }

            AccountLog::update(['sync_time' => time()], ['log_id' => ['in', $data['successLogIds']]]);
            //保存 失败的log_id
            $failLogIds = isset($data['failLogIds']) ? $data['failLogIds'] : array_diff($postLogIs, $data['successLogIds']);
            if (!empty($failLogIds)) {
                $syncFailLog               = new SyncFailLog;
                $syncFailLog->fail_log_ids = implode(',', $failLogIds);
                $syncFailLog->save();
            }

            unset($data);
            sleep(1);
        }

        $endTime = microtime(true);

        $output->info('Total : ' . $total . PHP_EOL . 'Spent time: ' . ($endTime - $startMTime) . 's');

    }
}
