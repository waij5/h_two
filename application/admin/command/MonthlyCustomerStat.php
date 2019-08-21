<?php

namespace app\admin\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use app\admin\model\DailyStat as MDailyStat;
use app\admin\model\MonthStatLog;

class MonthlyCustomerStat extends Command
{
    protected $model         = null;
    protected $linesPerExcel = 5000;
    protected $batchLimit    = 2000;

    protected function configure()
    {
        $this
            ->setName('monthlycustomerstat')
            ->addOption('force', 'f', Option::VALUE_OPTIONAL, 'force override', false)
            ->addOption('record', 'r', option::VALUE_REQUIRED, 'command record id')
            ->setDescription('Generate all business consult statistic report');
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
        //覆盖安装
        $force    = $input->getOption('force');
        //开始毫秒数
        $startMTime = microtime(true);
        
        // $today = date('Y-m-d')
        $last = MonthStatLog::order('check_time_end', 'DESC')->limit(0, 1)->column('check_time_end');
        $lastCheckTime = intval(current($last));

        MDailyStat::generateMonthlyCustomerStat($lastCheckTime + 1);

        $endTime = microtime(true);
        $output->info("Generate Successed!Spend time: " . ($endTime - $startMTime) . ' s');
    }
}
