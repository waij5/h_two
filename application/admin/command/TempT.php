<?php

namespace app\admin\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use fast\Http;
use app\admin\model\MallSync;
use app\admin\model\Customer;
use app\admin\model\CustomerMap;
use app\admin\model\CustomerMapLog;
use app\admin\model\AccountLog;
use yjy\exception\TransException;
use think\Db;
use think\Log;

class TempT extends Command
{
    protected function configure()
    {
        $this
            ->setName('tempt')
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
        
        $mallSync = MallSync::instance();
        $syncData = array();
        array_push($syncData, array('user_id' => 2411, 'yjy_customer_id' => 10000, 'rank_points' => 20, 'pay_points' => 40));
        // print_r($syncData);
        $output->info(print_r($mallSync->postSyncUsersToMall($syncData), true));
    }

    function prepareJSON($input){
        if(substr($input,0,3) == pack("CCC",0xEF,0xBB,0xBF)) {
            $input = substr($input,3);
        }
        return $input;
    }
}