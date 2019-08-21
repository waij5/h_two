<?php

namespace app\admin\command;

use app\admin\model\DailyFee;
use app\admin\model\OrderItems;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use think\Db;

/**
 * 默认统计前一天的， 一般第二天 凌晨处理
 */
class DailyFeeSummary extends Command
{
    protected $batchLimit = 2000;

    protected function configure()
    {
        $this
            ->setName('dailyfeesummary')
            ->addOption('force', 'f', Option::VALUE_OPTIONAL, 'force override', false)
            ->addOption('date', 'd', Option::VALUE_OPTIONAL, 'date', date('Y-m-d', strtotime('-1 day')))
            ->setDescription('Generate daily order items summary by fee type');
    }

    /**
     * 分批查询记录
     * 按记录数划分为不同EXCEL文件
     * 打包文件，删除EXCEL
     * 记录压缩文件位置
     * 注意中文路径的转换，存储文件时转换，存入DB时未转换
     */
    protected function execute(Input $input, Output $output)
    {
        //覆盖
        $force = $input->getOption('force');
        $date  = $input->getOption('date');

        $payTimeStart = strtotime($date);
        $payTimeEnd   = strtotime($date . ' 23:59:59');

        if ($payTimeStart === false) {
            $output->info('Invalid format for date');
            return false;
        }

        $doesRecExist = (bool) DailyFee::where(['pay_date' => $date, 'status' => 1])->count();
        if ($force) {
            DailyFee::where(['pay_date' => $date, 'status' => 1])->update(['status' => 0]);
        } else {
            if ($doesRecExist) {
                $output->info('Record exists, you can rebuild it with \'--force 1\'');
                return false;
            }
        }

        //开始毫秒数
        $startMTime = microtime(true);

        $feeSummary = OrderItems::generateDailyFeeSummary($payTimeStart, $payTimeEnd);
        $saveTime   = time();
        try {
            foreach ($feeSummary as $feeType => $row) {
                $dailyFee                     = new DailyFee;
                $dailyFee->pay_date           = $date;
                $dailyFee->fee_type           = $feeType;
                $dailyFee->original_pay_total = $row['original_pay_total'];
                $dailyFee->pay_total          = $row['pay_total'];
                $dailyFee->status             = 1;
                $dailyFee->createtime         = $saveTime;
                $dailyFee->save();
            }
        } catch (\think\PdoException $e) {
            $output->info("Dailyfeesummary exception: " . $e->getMessage());
        }

        $endTime = microtime(true);
        $output->info("Generate Successed!" . PHP_EOL . "Spend time: " . ($endTime - $startMTime) . ' s');
    }

}
