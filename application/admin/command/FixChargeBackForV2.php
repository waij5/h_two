<?php

namespace app\admin\command;

use app\admin\model\OrderChangeLog;
use app\admin\model\OrderItems;
use think\Log;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class FixChargeBackForV2 extends Command
{
    private $batchLimit = 1000;

    protected function configure()
    {
        $this
            ->setName('fixchargeback');
    }

        /*
     * 根据订单变动记录生成新订单，更新原订单等
     */
    protected function execute(Input $input, Output $output)
    {
        $startMTime = microtime(true);

        $total = OrderChangeLog::alias('change_log')
            ->join(OrderItems::getTable() . ' order_items', 'change_log.original_item_id = order_items.item_id', 'INNER')
            ->where(['change_log.change_type' => OrderChangeLog::TYPE_RETURN])
            ->count();

        $changeLogs = OrderChangeLog::alias('change_log')
            ->join(OrderItems::getTable() . ' order_items', 'change_log.original_item_id = order_items.item_id', 'INNER')
            ->where(['change_log.change_type' => OrderChangeLog::TYPE_RETURN])
            ->order('change_log.createtime', 'ASC')
            ->column('*');

        $i = 0;
        foreach ($changeLogs as $key => $changeLog) {
            $oldOrderItem = json_decode($changeLog['original_item_data'], true);
            $newItemData = json_decode($changeLog['new_item_data'], true);
            $newItem = current($newItemData);

            //额外退款单生成
            $cancelItem              = new OrderItems;
            $cancelItem->item_type   = $changeLog['item_type'];
            $cancelItem->customer_id = $changeLog['customer_id'];

            $cancelItem->pro_id           = $changeLog['pro_id'];
            $cancelItem->pro_name         = $changeLog['pro_name'];
            $cancelItem->item_qty         = $changeLog['item_qty'];

            $cancelItem->pro_use_times    = $changeLog['pro_use_times'];
            $cancelItem->item_total_times = $newItem['item_used_times'] - $oldOrderItem['item_total_times'];
            $cancelItem->item_used_times  = 0;
            $cancelItem->pro_local_amount = $changeLog['pro_local_amount'];
            $cancelItem->pro_amount       = $changeLog['pro_amount'];
            $cancelItem->pro_min_amount   = $changeLog['pro_min_amount'];

            $cancelItem->item_amount           = $changeLog['item_amount'];
            $cancelItem->item_cost             = $changeLog['item_cost'];
            $cancelItem->item_discount_percent = $changeLog['item_discount_percent'];
            $cancelItem->item_discount_total   =  $newItem['item_discount_total'] - $oldOrderItem['item_discount_total'];
            $cancelItem->item_local_total      = $newItem['item_local_total'] - $oldOrderItem['item_local_total'];
            $cancelItem->item_ori_total        = $newItem['item_ori_total'] - $oldOrderItem['item_ori_total'];
            $cancelItem->item_min_total        = $newItem['item_min_total'] - $oldOrderItem['item_min_total'];
            $cancelItem->item_total            = $newItem['item_total'] - $oldOrderItem['item_total'];

            if (isset($newItem['item_coupon_total']) && isset($oldOrderItem['item_coupon_total'])) {
                $cancelItem->item_coupon_total     = $newItem['item_coupon_total'] - $oldOrderItem['item_coupon_total'];
            } else {
                $cancelItem->item_coupon_total = 0.00;
                Log::record('== NONE item_coupon_total ==' . ' log_id ' . $changeLog['log_id'], Log::NOTICE);
            }
            
            $cancelItem->item_pay_total        = 0;

            $cancelItem->pro_unit    = $changeLog['pro_unit'];
            $cancelItem->pro_spec    = $changeLog['pro_spec'];
            $cancelItem->dept_id     = $changeLog['dept_id'];
            $cancelItem->deduct_addr = $changeLog['deduct_addr'];

            $cancelItem->item_pay_amount_per_time = $changeLog['item_pay_amount_per_time'];
            $cancelItem->item_amount_per_time     = $changeLog['item_amount_per_time'];
            $cancelItem->item_original_total      = $newItem['item_total'] - $oldOrderItem['item_total'];
            $cancelItem->item_original_pay_total  = - $changeLog['deposit_change'];
            $cancelItem->item_createtime          = $changeLog['createtime'];
            $cancelItem->item_paytime             = $changeLog['createtime'];
            $cancelItem->balance_id               = $changeLog['balance_id'];
            $cancelItem->item_undeducted_total    = 0;

            $cancelItem->item_status      = OrderItems::STATUS_CHARGEBACK;
            $cancelItem->item_old_id      = $changeLog['original_item_id'];
            $cancelItem->osconsult_id     = $changeLog['osconsult_id'];
            $cancelItem->consult_admin_id = $changeLog['consult_admin_id'];
            $cancelItem->recept_admin_id  = $changeLog['recept_admin_id'];
            $cancelItem->admin_id         = $changeLog['admin_id'];

            /*
            $cancelItem->item_id = ;
            $cancelItem->order_id = ;
            $cancelItem->item_type = ;
            $cancelItem->customer_id = ;
            $cancelItem->pro_id = ;
            $cancelItem->pro_name = ;
            $cancelItem->item_qty = ;
            $cancelItem->pro_use_times = ;
            $cancelItem->item_total_times = ;
            $cancelItem->item_used_times = ;
            $cancelItem->pro_local_amount = ;
            $cancelItem->pro_amount = ;
            $cancelItem->pro_min_amount = ;
            $cancelItem->item_amount = ;
            $cancelItem->item_cost = ;
            $cancelItem->item_discount_percent = ;
            $cancelItem->item_discount_total = ;
            $cancelItem->item_local_total = ;
            $cancelItem->item_ori_total = ;
            $cancelItem->item_min_total = ;
            $cancelItem->item_total = ;
            $cancelItem->item_coupon_total = ;
            $cancelItem->item_pay_total = ;
            $cancelItem->pro_unit = ;
            $cancelItem->pro_spec = ;
            $cancelItem->dept_id = ;
            $cancelItem->deduct_addr = ;
            $cancelItem->item_pay_amount_per_time = ;
            $cancelItem->item_amount_per_time = ;
            $cancelItem->item_original_total = ;
            $cancelItem->item_original_pay_total = ;
            $cancelItem->item_createtime = ;
            $cancelItem->item_paytime = ;
            $cancelItem->balance_id = ;
            $cancelItem->item_undeducted_total = ;
            $cancelItem->item_status = ;
            $cancelItem->item_old_id = ;
            $cancelItem->osconsult_id = ;
            $cancelItem->consult_admin_id = ;
            $cancelItem->recept_admin_id = ;
            $cancelItem->admin_id = ;
             */
            if ($cancelItem->save() == false) {
                // throw new TransException(__('Operation failed'));
                Log::record('==FixChargeBackForV2==  failed  ' . $changeLog['log_id']);
            }

        }

        $endMTime = microtime(true);

        $output->info('FixChargeBackForV2 has been completed!Spend: ' . ($endMTime - $startMTime) . 's.');
    }
}
