<?php

namespace app\admin\behavior;

use app\admin\model\Customer;
use app\admin\model\CustomerOsconsult;
use app\admin\model\OrderChangeLog;
use app\admin\model\AccountLog;
use think\Config;
use think\Log;

class Order
{
    protected $siteConfig = null;

    public function __construct()
    {
        $this->siteConfig = Config::get('site');
    }
    /**
     * 划扣成功执行动作
     * @param array  ['orderItem' => $orderItem, 'deductRecord' => model('DeductRecord')]
     */
    public function deductNdReverse(&$params)
    {
        if ($this->siteConfig['points_mode'] == 2) {
            $baseAmount = $params['deductRecord']->deduct_amount;
            $customer = Customer::find($params['orderItem']->customer_id);
            if (empty($customer)) {
                return false;
            }

            $this->dealAccountChange($baseAmount, array($params['orderItem']['item_id']), $customer);
        }
    }

    /**
     * 批量划扣动作
     *  $customer, ['batchDeductTotal' => $batchDeductTotal, 'orderItemIdArr' => $orderItemIdArr]
     */
    public function batchDeduct(&$customer, $extrInfo)
    {
        \think\Log::record('====batchDeduct====');
        if ($this->siteConfig['points_mode'] == 2) {
            $this->dealAccountChange($extrInfo['batchDeductTotal'], $extrInfo['orderItemIdArr'], $customer);
        }
    }

    /**
     * 批量反划扣动作
     * @param array $hookParams[$customerId] = [
                'customerId'   => $customerId,
                'orderIds'     => [],
                'reverseTotal' => 0.00,
            ];
     */
    public function batchReverse(&$params)
    {
        foreach ($params as $customerId => $row) {
            $customer = Customer::find($customerId);

            if (!empty($customer)) {
                $this->dealAccountChange($row['reverseTotal'], $row['orderItemIdArr'], $customer);
            }
            unset($customer);
        }
    }

    /**
     * @params $balance $balance
     * @params ['osconsultIdArr' => $osconsultIdArr, 'customer' => $customer)
     * CustomerBalance $balance
     */

    public function payOrder($balance, $extraInfo)
    {
        $osconsultIdArr = $extraInfo['osconsultIdArr'];
        $customer   = $extraInfo['customer'];
        $orderItemIdArr = $extraInfo['orderItemIdArr'];

        $updateOscRes = CustomerOsconsult::where(['osc_id' => ['in', $osconsultIdArr]])->update(['osc_status' => CustomerOsconsult::STATUS_SUCCESS_PAYED]);

        //付款模式
        if ($this->siteConfig['points_mode'] == 1) {
            $baseAmount = $balance->total - $balance->coupon_total;
            $this->dealAccountChange($baseAmount, $orderItemIdArr, $customer);
        }
    }

    /**
     * 项目退款到定金
     * @param float $depositChangeAmt 退款金额，顾客定金增加，扣减积分等
     */
    public function chargeback(&$params)
    {
        return $this->switchItem($params);
    }

    /**
     * 取消/更换订单项目
     * @param Array $params 
         $hookParams = [
                        'oldOrderItems'    => array($oldOrderItem),
                        'newOrderItems'    => $newOrderItems,
                        'admin_id'         => Session::get('admin')->id,
                        'depositChangeAmt' => $depositChangeAmt,
                        'customer'         => $customer,
                    ];
     */
    public function switchItem(&$params)
    {
        //付款模式
        if ($this->siteConfig['points_mode'] == 1) {
            //基础金额为消费金额， 参数定金额为顾客定金增加额， 取负值
            $baseAmount = - $params['depositChangeAmt'];
            $this->dealAccountChange($baseAmount, array_column($params['newOrderItems'], 'item_id'), $params['customer']);
        }
        // 传入的ITEM 为 纯数组
        //超出64字符，数据库自动截取
        $oldName = $params['oldOrderItems']['pro_name'];
        $newName = implode(' ', array_column($params['newOrderItems'], 'pro_name'));

        $changeLog           = new OrderChangeLog;
        $changeLog->customer_id = $params['customer']['ctm_id'];
        $changeLog->old_name = $oldName;
        $changeLog->new_name = $newName;

        $changeLog->deposit_change = $params['depositChangeAmt'];
        $changeLog->original_item_id = $params['oldOrderItems']['item_id'];
        $changeLog->original_item_data = json_encode($params['oldOrderItems']);
        $changeLog->new_item_data      = json_encode($params['newOrderItems']);
        $changeLog->admin_id = $params['admin_id'];

        $changeLog->type = $params['oldOrderItems']['item_type'];
        //退 新项目至少 两个， 原项目变动后的， 有一个退款抵扣的单
        $changeLog->change_type = (2 == count($params['newOrderItems'])) ? OrderChangeLog::TYPE_RETURN : OrderChangeLog::TYPE_SWITCH;

        $changeLog->stat_date = date('Y-m-d H:i:s');
        $changeLog->createtime = time();

        $changeLog->save();
    }


    /**
     * @param $baseAmount 消费的金额，如是退还，请用负值
     */
    private function dealAccountChange($baseAmount, $orderItemIdArr, &$customer)
    {
        if (empty($customer)) {
            return false;
        }
        if ($baseAmount == 0) {
            return true;
        }

        $rankPoints = floor($baseAmount * $this->siteConfig['rank_point_rate']);
        $payPoints  = floor($baseAmount * $this->siteConfig['pay_points_rate']);

        if (!is_array($orderItemIdArr)) {
            $orderItemIdArr = array($orderItemIdArr);
        }

        $fixedOrderNos = array();
        foreach ($orderItemIdArr as $key => $orderItemId) {
            $fixedOrderNos[]= str_pad($orderItemId, 11, 0, STR_PAD_LEFT);
        }
        $fixedOrderStr = implode(',', $fixedOrderNos);

        $chgDescAddon = '获得';
        if ($baseAmount < 0) {
            $chgDescAddon = '扣减';
        }

        $customer->log_account_change(0, 0, $rankPoints, $payPoints, 0, $changeTime = '', AccountLog::TYPE_PAY_ORDER, $chgDescAddon. '积分，订单号: ' . $fixedOrderStr);

        //分销佣金 ctm_affiliate
        if ($this->siteConfig['lv1_percent'] && $customer->sales1_id) {
            $lv1Affiliate = floor($baseAmount * $this->siteConfig['lv1_percent'] / 100);
            if ($lv1Affiliate != 0) {
                $sale1        = new Customer;
                if (!empty($sale1 = $sale1->find($customer->sales1_id))) {
                    $lv1Desc = '一级分销' . $chgDescAddon . '佣金，订单号: ' . $fixedOrderStr;
                    $sale1->log_account_change(0, 0, 0, 0, $lv1Affiliate, time(), AccountLog::TYPE_DISTRIBUTE, $lv1Desc, $ip = 'SYSTEM', 'HIS', 0);
                }
            }
            
        }
        if ($this->siteConfig['lv2_percent'] && $customer->sales2_id) {
            $lv2Affiliate = floor($baseAmount * $this->siteConfig['lv2_percent'] / 100);
            if ($lv2Affiliate != 0) {
                $lv2Desc = '二级分销' . $chgDescAddon . '佣金，订单号: ' . $fixedOrderStr;
                $sale2        = new Customer;
                if (!empty($sale2 = $sale2->find($customer->sales2_id))) {
                    $sale2->log_account_change(0, 0, 0, 0, $lv2Affiliate, time(), AccountLog::TYPE_DISTRIBUTE, $lv2Desc, $ip = 'SYSTEM', 'HIS', 0);
                }
            }
        }
    }
}
