<?php

namespace app\admin\command;

use app\admin\model\CustomerOsconsult;
use app\admin\model\DailyStat;
use app\admin\model\OrderApplyRecords;
use app\admin\model\OrderItems;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;

class Stat extends Command
{

    /**
     * 路径和文件名配置
     */
    protected $options = [

    ];

    protected function configure()
    {
        $this
            ->setName('stat')
            ->addOption('type', 't', Option::VALUE_REQUIRED, 'type,use \'all\' when build all types', 'ALL')
            ->addOption('date', 'd', Option::VALUE_OPTIONAL, 'date,use yesterday when is is not specified', date('Y-m-d', strtotime('-1 day')))
            ->setDescription('daily stat');
    }

    protected function execute(Input $input, Output $output)
    {
        $type     = $input->getOption('type') ?: 'ALL';
        $calcDate = $input->getOption('date') ?: '';

        $type = strtoupper($type);

        //结束今日之前的现场客服 未成功和未失败的
        // STATUS_PENDING STATUS_CONSULTING STATUS_REFUSED STATUS_FAIL STATUS_SUCCESS_PAYED
        ;
        CustomerOsconsult::update(['osc_status' => CustomerOsconsult::STATUS_FAIL], ['osc_status' => ['notin', [CustomerOsconsult::STATUS_FAIL, CustomerOsconsult::STATUS_SUCCESS_PAYED]], 'createtime' => ['<', strtotime(date('Y-m-d'))]]);

        $checkTime = strtotime('-3 days', time());
        //撤销 三日前未付款的订单
        OrderItems::update(
            ['item_status' => OrderItems::STATUS_CANCELED],
            [
                'item_status'     => ['in', [OrderItems::STATUS_PENDING, OrderItems::STATUS_APPLYING]],
                'item_createtime' => ['lt', $checkTime],
            ],
            ['item_status']
        );
        //撤销三日前提交的审核记录
        OrderApplyRecords::update(
            [
                'reply_status'   => OrderApplyRecords::STATUS_CANCELED,
                'reply_admin_id' => 0,
                'reply_info'     => '审核超过三天，系统自动取消',
                'updatetime'     => time(),
            ],
            [
                'reply_status' => OrderApplyRecords::STATUS_PENDING,
                'createtime'   => ['lt', $checkTime],
            ],
            ['reply_status', 'reply_admin_id', 'reply_info']
        );

        if ($type == 'NOTOUT') {
            // $info1 = DailyStat::generateCustomerNotOutAmout($calcDate);
            // $this->showMsg($info1, $output);
        } elseif ($type == 'DEPT') {
            // $info2 = DailyStat::generateDeptDailyStat($calcDate, 1, true);
            // $this->showMsg($info2, $output);
        } elseif ($type == 'DAILY') {
            $info3 = DailyStat::generateDailyStat($calcDate, 1, $force = true);
            $this->showMsg($info3, $output);
        } elseif ($type == 'ALL') {
            // $info1 = DailyStat::generateCustomerNotOutAmout($calcDate);
            // $this->showMsg($info1, $output);
            // $info2 = DailyStat::generateDeptDailyStat($calcDate, 1, true);
            // $this->showMsg($info2, $output);
            $info3 = DailyStat::generateDailyStat($calcDate, 1, $force = true);
            $this->showMsg($info3, $output);
        }

        $output->info("Build completed!");
    }

    private function showMsg($result, &$output)
    {
        if ($result['error']) {
            $output->error($result['msg']);
        } else {
            $output->info($result['msg']);
        }
    }

}
