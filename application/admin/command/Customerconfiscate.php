<?php

namespace app\admin\command;

use think\Config;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use think\Exception;
use think\Db;

class Customerconfiscate extends Command
{

    protected $model = null;

    protected function configure()
    {
        $this
                ->setName('customerconfiscate')
                ->setDescription('Auto confiscate customer');
    }

    protected function execute(Input $input, Output $output)
    {
        $confiscateDays = @(intval(Config::get('site.confiscate_days', 0)));

        if ($confiscateDays) {
            $todayTime = strtotime(date('Y-m-d'));
            $currentTime = time();
            $targetTime = strtotime("-$confiscateDays day", $todayTime);

            //' . \think\Db::getTable('customer') . ' 
            $sql = 'UPDATE yjy_customer SET ctm_is_public = 1, ctm_public_time = ? WHERE arrive_status = 1 AND ctm_is_public = 0 AND ctm_last_recept_time <= ? AND ctm_id NOT IN (SELECT DISTINCT (customer_id) FROM yjy_rvinfo WHERE rv_time > ?) ';
            $result = Db::execute($sql, [$currentTime, $targetTime, $targetTime]);
        }


        //顾客数据 充公--网电版
        $cstConfiscateDays = @(intval(Config::get('site.cst_confiscate_days', 0)));
        if ($cstConfiscateDays) {
            $todayTime = strtotime(date('Y-m-d'));
            $targetTime = strtotime("-$cstConfiscateDays day", $todayTime);
            $sql2 = 'UPDATE yjy_customer SET ctm_is_cst_public = 1 WHERE arrive_status = 0 AND ctm_is_cst_public = 0 AND createtime <= ? AND ctm_id NOT IN (SELECT DISTINCT (customer_id) FROM yjy_rvinfo WHERE rv_time > ?)';
            $result2 = Db::execute($sql2, [$targetTime, $targetTime]);
        }
    }
}
