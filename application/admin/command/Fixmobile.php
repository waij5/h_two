<?php

namespace app\admin\command;

use think\Log;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use app\admin\model\Customer;

class Fixmobile extends Command
{

    protected $model         = null;
    protected $linesPerExcel = 20000;
    protected $batchLimit    = 2000;

    protected function configure()
    {
        $this
            ->setName('fixmobile');
    }

    protected function execute(Input $input, Output $output)
    {
        //开始毫秒数
        $startMTime = microtime(true);

        $total = Customer::where('ctm_mobile', 'null')->where('ctm_tel', 'not null')->count();
        $maxBatchCount  = ceil($total / $this->batchLimit);
        $currentCount   = 0;
        $currentBatchNo = 1;

        $successCount = 0;
        $failedCount = 0;

        $currentMaxId = 0;


        //由于更改本表内容，
        for (; $currentBatchNo <= $maxBatchCount; $currentBatchNo++) {
            $output->info('Processing :batch ' . $currentBatchNo . ' / ' . $maxBatchCount);
            $list = Customer::where('ctm_mobile', 'null')->where('ctm_tel', 'not null')->where('ctm_id', 'gt', $currentMaxId)->order('ctm_id', 'ASC')->limit(0, $this->batchLimit)->select();
            
            foreach ($list as $key => $customer) {
                if ($currentCount % 150 == 0) {
                    $output->info('Processing ' . $currentCount . ' / ' . $total . '    ' . floor(10000 * $currentCount / $total) / 100 . '%' . '   failed ' . $failedCount);
                }
                $currentCount ++;
                $currentMaxId = $customer['ctm_id'];

                try {
                    $customer->save(['ctm_mobile' => trim($customer->ctm_tel)]);
                } catch(\think\exception\PDOException $e) {
                    $failedCount ++;
                    \think\Log::record('failed: ctm_id ' . $customer->ctm_id . '    ctm_tel: ' . $customer->ctm_tel);
                    continue;
                }

                $successCount ++;
            }
        }
        $endMTime = microtime(true);

        $output->info('completed: ' . "total $total | success $successCount | failed $failedCount" . PHP_EOL . 'time spent: ' . floor(10000 * ($endMTime - $startMTime)) / 10000 . 's');
    }

}
