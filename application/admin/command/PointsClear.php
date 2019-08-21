<?php

namespace app\admin\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\console\input\Option;
use think\Log;
use app\admin\model\AccountLog;
use app\admin\model\Customer;
use app\admin\model\CmdRecords;
use app\admin\model\PointsClearBatch;
use app\admin\model\PointsClearRecords;

class PointsClear extends Command
{
    private $batchLimit = 1000;

    protected function configure()
    {
        $this
            ->setName('pointsclear')
            // 记录ID
            ->addOption('record', 'r', option::VALUE_OPTIONAL, 'command record id')
            // 清理类型---- TRUNCATE / CLEAR 清空，清理
            ->addOption('type', 't', option::VALUE_OPTIONAL, 'TRUNCATE / CLEAR', 'CLEAR')
            // 检查时间点
            ->addOption('checktime', 'ct', Option::VALUE_OPTIONAL, 'check date')
            // 清除积分描述
            ->addOption('desc', 'd', Option::VALUE_OPTIONAL, 'batch remark')
            ->setDescription('Clear points');
    }

    /*
     * 清除指定时间前的积分
     * 逻辑: 指定时间后 增加的积分 为 现存积分上限
     * 超出部分 清除
     */
    protected function execute(Input $input, Output $output)
    {
        $startMTime = microtime(true);

        $recordId = $input->getOption('record');
        $type = $input->getOption('type');
        $checktime = $input->getOption('checktime');
        $desc = $input->getOption('desc');

        if (!empty($recordId)) {
            $record = CmdRecords::find($recordId);
            if (empty($record)) {
                $this->output('Error: command record(' . $recordId . ') does not exist!');

                return false;
            }
            $params = json_decode($cmdRecord->params, true);
            $type = $params['extra']['type'];
            $checktime = $params['extra']['checktime'];
            $desc = $params['extra']['desc'];
        }
        // 对每个顾客进行处理， 计数精简
        $total = Customer::count();
        $maxBatchCount  = ceil($total / $this->batchLimit);
        $currentCount   = 0;
        $currentBatchNo = 1;
        //因为会对查询源表做操作，记录当前查询到最大的顾客ID
        $currentMaxCustomerId = 0;

        //分批次处理 审核 通过 匹配的顾客数据
        $pointsClearBatch = new PointsClearBatch;
        $pointsClearBatch->checktime = $checktime;
        // $pointsClearBatch->admin_id = 0;
        // $pointsClearBatch->admin_id = 0;
        $pointsClearBatch->remark = is_null($desc) ? '' : $desc;
        $pointsClearBatch->createtime = time();
        $pointsClearBatch->costtime = -1;
        $pointsClearBatch->save();

        for ($currentBatch = 1; $currentBatch <= $maxBatchCount; $currentBatch++) {
            $batchOffset      = ($currentBatch - 1) * $this->batchLimit;

            // $list = Customer::alias('customer')
            //         ->join(AccountLog::getTable() . ' accountlog', 'customer.ctm_id = accountlog.customer_id', 'LEFT')
            //         ->group('customer_id')
            //         ->where([
            //                     'accountlog.change_time' => ['gt', $checktime],
            //                     'customer.ctm_id' => ['gt', $currentMaxCustomerId],
            //         ])
            //         ->field('customer.ctm_id, customer.ctm_name, customer.ctm_rank_points, customer.ctm_pay_points, SUM(accountlog.rank_points) AS rank_points_change, SUM(pay_points) AS pay_points_change', 'ctm_id')
            //         ->order('ctm_id', 'ASC')
            //         ->limit($batchOffset, $this->batchLimit)
            //         ->select();
            $customers = Customer::order('ctm_id', 'ASC')
                            ->limit($batchOffset, $this->batchLimit)
                            ->field('ctm_id, ctm_name, ctm_rank_points, ctm_pay_points')
                            ->select();
            $customerIds = collection($customers)->column('ctm_id');
            $pointsLogSummary = AccountLog::where([
                                        'customer_id' => ['in', $customerIds],
                                        'change_time' => ['gt', $checktime],
                                    ])
                                    ->group('customer_id')
                                    ->column('SUM(rank_points) AS rank_points_change, SUM(pay_points) AS pay_points_change', 'customer_id');

            foreach ($customers as $customer) {
                $beforeRank = $customer->ctm_rank_points;
                $beforePay = $customer->ctm_pay_points;
                //当期增加的积分即为实际清理后最大拥有积分数
                $rankInc = 0;
                $payInc = 0;
                $rankChange = 0;
                $payChange = 0;
                if (isset($pointsLogSummary[$customer->ctm_id])) {
                    $rankInc = $pointsLogSummary[$customer->ctm_id]['rank_points_change'];
                    $payInc = $pointsLogSummary[$customer->ctm_id]['pay_points_change'];
                }
                // if ($rankInc < $customer->ctm_rank_points) {
                //     $rankChange = $rankInc - $customer->ctm_rank_points;
                // }
                if ($payInc < $customer->ctm_pay_points) {
                    $payChange = $payInc - $customer->ctm_pay_points;
                }
                // 积分超出了检查点 后 增加的积分，需要予以 调整
                if ($rankChange <> 0 || $payChange <> 0) {
                    $clearRecord = new PointsClearRecords;
                    $clearRecord->customer_id = $customer->ctm_id;
                    $clearRecord->customer_name = $customer->ctm_name;
                    $clearRecord->before_rank_points = $beforeRank;
                    $clearRecord->before_pay_points = $beforePay;
                    $clearRecord->rank_points_change = $rankChange;
                    $clearRecord->pay_points_change = $payChange;
                    $clearRecord->batch_id = $pointsClearBatch->batch_id;
                    $clearRecord->createtime = time();

                    unset($customer->rank_points_inc);
                    unset($customer->pay_points_inc);

                    if ($customer->log_account_change(0, 0, $rankChange, $payChange, 0, time(), AccountLog::TYPE_ADJUST, $changeDesc = '年度消费积分清理')) {
                        //清理前后的数据
                        $clearRecord->status = 1;
                        $clearRecord->save();
                    } else {
                        $clearRecord->status = 0;
                        $clearRecord->save();
                    }
                }
            }
            $output->info('Spent time: ' . (microtime(true) - $startMTime) . 's    currentBatch: ' . $currentBatch);
        }

        $pointsClearBatch->costtime = microtime(true) - $startMTime;
        $pointsClearBatch->save();
        $output->info('Compleated!Spent time: ' . (microtime(true) - $startMTime) . 's');
    }

    public function prepareJSON($input)
    {
        if (substr($input, 0, 3) == pack("CCC", 0xEF, 0xBB, 0xBF)) {
            $input = substr($input, 3);
        }
        return $input;
    }
}
