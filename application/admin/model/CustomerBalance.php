<?php

namespace app\admin\model;

use app\admin\model\BalanceType;
use think\Db;
use think\Model;

class CustomerBalance extends Model
{
    // 表名
    protected $name = 'customer_balance';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;

    // 追加属性
    protected $append = [

    ];

    /**
     * 获取列表总数
     * @param mixed $where 基本查询条件
     * @param string $sort
     * @param string $order
     * @param array $secCondition
     */
    public function getListCount($where, $secCondition = [])
    {
        $total = $this->where($where)
            ->where($secCondition)
            ->count();

        return $total;
    }

    public static function getListCount2($mainWhere, $extraWhere = [], $includeSummary = false)
    {

        if ($includeSummary) {
            $total = static::alias('balance')
                ->join(Db::getTable('customer') . ' customer', 'balance.customer_id=customer.ctm_id', 'LEFT')
                ->join(Db::getTable('admin') . ' admin', 'customer.admin_id=admin.id', 'LEFT')
                ->join(Db::getTable('deptment') . ' deptment', 'balance.deptment_id=deptment.dept_id', 'LEFT')
                ->where($mainWhere)
                ->where($extraWhere)
                ->limit(0, 1)
                ->column('count(*) as count, SUM(CASE WHEN balance_type = \'' . BalanceType::TYPE_PROJECT_PAY . '\' THEN 1 ELSE 0 END) AS order_count, SUM(pay_total) AS pay_total, SUM(CASE WHEN balance_type > 0 THEN deposit_total ELSE 0 END) AS in_deposit_total, SUM(CASE WHEN balance_type > 0 THEN coupon_cost ELSE 0 END) AS in_coupon_cost, SUM(CASE WHEN balance_type > 0 THEN coupon_total ELSE 0 END) AS in_coupon_total, SUM(CASE WHEN balance_type > 0 THEN cash_pay_total ELSE 0 END) AS in_cash_pay_total, SUM(CASE WHEN balance_type > 0 THEN card_pay_total ELSE 0 END) AS in_card_pay_total, SUM(CASE WHEN balance_type > 0 THEN wechatpay_pay_total ELSE 0 END) AS in_wechatpay_pay_total, SUM(CASE WHEN balance_type > 0 THEN alipay_pay_total ELSE 0 END) AS in_alipay_pay_total, SUM(CASE WHEN balance_type > 0 THEN other_pay_total ELSE 0 END) AS in_other_pay_total, SUM(CASE WHEN balance_type > 0 THEN pay_total ELSE 0 END) AS in_pay_total, SUM(deposit_total) AS deposit_total, SUM(coupon_cost) AS coupon_cost, SUM(coupon_total) AS coupon_total');
            $total = current($total);
            foreach ($total as $key => $value) {
                if (is_null($value) or $value = '') {
                    $total[$key] = 0.00;
                }
            }
            $total['out_pay_total'] = number_format(($total['pay_total'] - $total['in_pay_total']), 2);
        } else {
            $total = static::alias('balance')
                ->join(Db::getTable('customer') . ' customer', 'balance.customer_id=customer.ctm_id', 'LEFT')
                ->join(Db::getTable('admin') . ' admin', 'customer.admin_id=admin.id', 'LEFT')
                ->join(Db::getTable('deptment') . ' deptment', 'balance.deptment_id=deptment.dept_id', 'LEFT')
                ->join(Db::getTable('tooltype') . ' tooltype', 'customer.ctm_first_tool_id=tooltype.tool_id', 'LEFT')
                ->where($mainWhere)
                ->where($extraWhere)
                ->count();
        }

        return $total;
    }
    public static function getList2($mainWhere, $sort, $order, $offset, $limit, $extraWhere = [])
    {
        $list = static::alias('balance')
            ->field('balance.*, customer.ctm_name, customer.ctm_source, customer.ctm_explore, customer.admin_id as customer_admin_id, customer.ctm_last_osc_admin, admin.nickname, admin.id, admin.dept_id as develop_dept_id, deptment.dept_name, source.sce_name as source_name, channels.chn_name as explore_name, tooltype.tool_name')
            ->join(Db::getTable('customer') . ' customer', 'balance.customer_id=customer.ctm_id', 'LEFT')
            ->join(Db::getTable('admin') . ' admin', 'customer.admin_id=admin.id', 'LEFT')
            ->join(Db::getTable('deptment') . ' deptment', 'balance.deptment_id=deptment.dept_id', 'LEFT')
            ->join(Db::getTable('ctmchannels') . ' channels', 'customer.ctm_explore=channels.chn_id', 'LEFT')
            ->join(Db::getTable('ctmsource') . ' source', 'customer.ctm_source=source.sce_id', 'LEFT')
            ->join(Db::getTable('tooltype') . ' tooltype', 'customer.ctm_first_tool_id=tooltype.tool_id', 'LEFT')
            ->where($mainWhere)
            ->where($extraWhere)
            ->order($sort, $order)
            ->limit($offset, $limit)
            ->select();

        $admin          = new \app\admin\model\Admin;
        $adminList      = $admin->getBriefAdminList();
        $deptCacheList  = \app\admin\model\Deptment::getDeptListCache();
        $refundTypeList = BalanceType::getRefundList();
        foreach ($list as $key => $row) {
            if (isset($adminList[$row['admin_id']])) {
                $list[$key]['admin_id'] = $adminList[$row['admin_id']];
            }
            if (isset($adminList[$row['rec_admin_id']])) {
                $list[$key]['rec_admin_id'] = $adminList[$row['rec_admin_id']];
            }
            $list[$key]['develop_admin_name'] = '';
            if (isset($adminList[$row['develop_admin_id']])) {
                $list[$key]['develop_admin_name'] = $adminList[$row['develop_admin_id']];
            }
            //顾客营销人员
            $list[$key]['customer_admin_name'] = '';
            if (isset($adminList[$row['customer_admin_id']])) {
                $list[$key]['customer_admin_name'] = $adminList[$row['customer_admin_id']];
            }
            //开发科室
            $list[$key]['customer_admin_dept'] = '';
            if (isset($deptCacheList[$row['develop_dept_id']])) {
                $list[$key]['customer_admin_dept'] = $deptCacheList[$row['develop_dept_id']]['dept_name'];
            }

            $list[$key]['osc_admin_name'] = '';
            if (isset($adminList[$row['osc_admin_id']])) {
                $list[$key]['osc_admin_name'] = $adminList[$row['osc_admin_id']];
            }
            //最近现场客服
            $list[$key]['last_osc_admin'] = '';
            if (isset($adminList[$row['ctm_last_osc_admin']])) {
                $list[$key]['last_osc_admin'] = $adminList[$row['ctm_last_osc_admin']];
            }

            $list[$key]['refund_type_name'] = '';
            if (isset($refundTypeList[$row['refund_type']])) {
                $list[$key]['refund_type_name'] = $refundTypeList[$row['refund_type']];
            }

            if (empty($list[$key]['tool_name'])) {
                $list[$key]['tool_name'] = '【自然到诊】';
            }
            //初复诊
            $list[$key]['b_osc_type_name'] = \app\admin\model\Osctype::getTypeById($list[$key]['b_osc_type']);

            $list[$key]['balance_type'] = BalanceType::getTitleById($list[$key]['balance_type']);
        }

        return $list;
    }

    /**
     * 获取列表
     */
    public function getList($where, $sort, $order, $offset, $limit, $secCondition = [])
    {
        //Customerosconsult表子查询
        $subQuery = $this->where($where)
            ->where($secCondition)
            ->buildSql();

        $list = $this->table($subQuery . ' balance')
            ->field('balance.*, customer.ctm_name, deptment.dept_name')
            ->join(Db::getTable('customer') . ' customer', 'balance.customer_id=customer.ctm_id', 'LEFT')
            ->join(Db::getTable('deptment') . ' deptment', 'balance.deptment_id=deptment.dept_id', 'LEFT')
            ->order($sort, $order)
            ->limit($offset, $limit)
            ->select();

        $adminList = model('Admin')->getAdminList();
        foreach ($list as $key => $row) {
            if (isset($adminList[$row['admin_id']])) {
                $list[$key]['admin_id'] = $adminList[$row['admin_id']]['nickname'];
            }
            if (isset($adminList[$row['rec_admin_id']])) {
                $list[$key]['rec_admin_id'] = $adminList[$row['rec_admin_id']]['nickname'];
            }

            $list[$key]['balance_type'] = BalanceType::getTitleById($list[$key]['balance_type']);
        }

        return $list;
    }

    /**
     * 保存流水信息
     */
    public function saveBalance($data)
    {
        $result = [
            'error' => true,
            'msg'   => __('Error occurs'),
        ];
        if (isset($this->balance_id)) {
            return $result;
        }
        if (empty($data['balance_type']) || !in_array($data['balance_type'], array_keys(BalanceType::getList()))) {
            $result['msg'] = __('Parameter %s is empty or Invalid!', __('balance_type'));
            return $result;
        }
        // $data  total pay_total coupon_cost coupon_total deposit_total
        // 'deptment_id', 'rec_admin_id',
        // $allowFields = ['customer_id', 'balance_type', 'pay_type', 'total', 'pay_total', 'balance_remark', 'admin_id'];

        $allowFields = ['customer_id', 'balance_type',
            'cash_pay_total', 'card_pay_total', 'wechatpay_pay_total', 'alipay_pay_total', 'other_pay_total',
            'total', 'pay_total', 'balance_remark', 'admin_id', 'osconsult_id', 'b_osc_type'];
        if ($data['balance_type'] == BalanceType::TYPE_PROJECT_CHARGEBACK) {
            // array_push($allowFields, 'order_id');
        } elseif ($data['balance_type'] == BalanceType::TYPE_PRESTORE_CHARGEBACK) {
            array_push($allowFields, 'refund_type');
        } elseif ($data['balance_type'] == BalanceType::TYPE_PROJECT_PAY) {
            // array_push($allowFields, 'order_id');
            array_push($allowFields, 'coupon_cost');
            array_push($allowFields, 'coupon_total');
            array_push($allowFields, 'deposit_total');
        } elseif ($data['balance_type'] == BalanceType::TYPE_COUPON) {
            array_push($allowFields, 'deposit_total');
        }
        // BalanceType::TYPE_ADJUST_INCOME BalanceType::TYPE_ADJUST_OUTPAY
        $insertData = [];
        foreach ($allowFields as $key => $allowField) {
            if (!isset($data[$allowField])) {
                $result['msg'] = __('Parameter %s is empty or Invalid!', __($allowField));
                return $result;
            } else {
                //任意金额不小于0
                // if (strpos($allowField, 'total') !== false && $data[$allowField] < 0) {
                //     $result['msg'] = __('Parameter %s is empty or Invalid!', __($allowField));
                //     return $result;
                // }
            }
            $insertData[$allowField] = $data[$allowField];
        }
        // develop_admin_id
        // osc_admin_id
        $optionalFields = ['develop_admin_id', 'osc_admin_id'];
        foreach ($optionalFields as $key => $optionalField) {
            if (isset($data[$optionalField])) {
                $insertData[$optionalField] = $data[$optionalField];
            }
        }

        $insertData['create_date'] = date('Y-m-d');

        if ($this->save($insertData) !== false) {
            $result['error'] = false;
            $result['msg']   = __('');
        }

        return $result;
    }

    /**
     * 项目退款  --  退到定金
     */
    public function chargeback($data)
    {

    }

    /**
     * 现金退款  --  定金->现金
     */
    public function refund()
    {

    }

    public static function generateReceipt1($balances, $balanceIds, $adminId)
    {
        $offsetX = floatval(\think\Config::get('site.receiptOffsetX'));
        $offsetY = floatval(\think\Config::get('site.receiptOffsetY'));

        $customer     = model('Customer')->field('ctm_id, ctm_name')->find(current($balances)['customer_id']);
        $customerName = '--';
        if (!empty($customer)) {
            $customerName = $customer->ctm_name . "[$customer->ctm_id]";
        }
        $printTime = date('y/m/d H:i');

        $printList       = array();
        $defaultFontSize = 10;
        $itemFontSize    = 9;
        $cusFontSize1    = 9;
        //[str, font, color, posX, posY]

        $balanceTotal        = 0.00;
        $balanceTotalAbs     = 0.00;
        $balanceCouponTotal  = 0.00;
        $balanceDepositTotal = 0.00;
        $extraBalanceTotal   = 0.00;
        foreach ($balances as $key => $balance) {
            $balanceTotal += $balance['pay_total'];
            $balanceCouponTotal += $balance['coupon_total'];
            $balanceDepositTotal += $balance['deposit_total'];

            //非项目付款，记入额外营收
            if ($balance['balance_type'] != BalanceType::TYPE_PROJECT_PAY) {
                $extraBalanceTotal += $balance['pay_total'];
                continue;
            }
        }

        //初始化费用列表--按费用类型
        $feeList     = array();
        $feeTypeList = \app\admin\model\Fee::getList();
        foreach ($feeTypeList as $key => $feeType) {
            $feeList[$key] = 0.00;
        }

        //项目，费用清单--按项目部门
        $deptFeeList = array();
        $deptList    = \app\admin\model\Deptment::getDeptListCache();

        $projectItems = model('OrderItems')->alias('order_items')
            ->join(model('Project')->getTable() . ' pro', 'order_items.pro_id = pro.pro_id', 'LEFT')
            ->where(['order_items.balance_id' => ['in', $balanceIds]])
            ->column('order_items.pro_id, order_items.pro_name, order_items.item_total, order_items.pro_spec, order_items.dept_id, pro.pro_fee_type', 'order_items.pro_id');
        static::receiptDeal($projectItems, $feeList, $deptFeeList, $deptList);

        $balanceTotalAbs    = abs($balanceTotal);
        $balanceTotalChnStr = ($balanceTotal < 0 ? '负数 ' : '   ') . number2chinese($balanceTotalAbs, true, false);

        $hosName = \think\Config::get('site.hospital');
        $hosName = $hosName ? $hosName : '';
        array_push($printList, ['str' => $hosName, 'posX' => 34, 'posY' => 17.5, 'fontSize' => $defaultFontSize]);

        array_push($printList, ['str' => $customerName, 'posX' => 34, 'posY' => 21, 'fontSize' => $defaultFontSize]);

        //实际显示费用类型
        $showFeeList = [
            1               => ['x' => 36, 'y' => 30.8, 'total' => 0.00],
            2               => ['x' => 36, 'y' => 35.5, 'total' => 0.00],
            3               => ['x' => 36, 'y' => 40.2, 'total' => 0.00],
            4               => ['x' => 36, 'y' => 44.9, 'total' => 0.00],
            5               => ['x' => 36, 'y' => 49.6, 'total' => 0.00],
            6               => ['x' => 36, 'y' => 54.3, 'total' => 0.00],
            7               => ['x' => 36, 'y' => 59, 'total' => 0.00],
            8               => ['x' => 36, 'y' => 63.7, 'total' => 0.00],
            9               => ['x' => 36, 'y' => 68.4, 'total' => 0.00],
            10              => ['x' => 36, 'y' => 73.1, 'total' => 0.00],

            11               => ['x' => 72, 'y' => 30.8, 'total' => 0.00],
            12               => ['x' => 72, 'y' => 35.5, 'total' => 0.00],
            13               => ['x' => 72, 'y' => 40.2, 'total' => 0.00],
            14               => ['x' => 72, 'y' => 44.9, 'total' => 0.00],
            15               => ['x' => 72, 'y' => 49.6, 'total' => 0.00],
            16               => ['x' => 72, 'y' => 54.3, 'total' => 0.00],
            17               => ['x' => 72, 'y' => 59, 'total' => 0.00],
            18               => ['x' => 72, 'y' => 63.7, 'total' => 0.00],

            Fee::TYPE_OTHER => ['x' => 72, 'y' => 68.4, 'total' => 0.00],
        ];

        $curFeeNum = 1;
        $feeOtherTotal = 0;
        foreach ($feeList as $key => $value) {
            // $feeMod     = $curFeeNum % 10;
            // $feeDivided = $curFeeNum / 10;

            // if ($feeDivided <= 1) {
            //     $posX = 36;
            // } else {
            //     $posX = 72;
            // }
            // $curFeeNum++;

            // $posY = 26.1 + ($feeMod == 0 ? 10 : $feeMod) * 4.7;
            // if ($key == \app\admin\model\Fee::TYPE_OTHER) {
            //     $value += $extraBalanceTotal;
            // }
            // $value = \str_pad($value, 12, " ", STR_PAD_LEFT);
            //文字居右
            // imagettftext($image, $fontSize, 0, ($posX + 120) - $fontBox[2], $posY, $textColor, $font, $value);
            //不在显示列表内的费用加在其它里
            if (!in_array($key, array_keys($showFeeList))) {
                $feeOtherTotal += $value;
                continue;
            } elseif ($key == Fee::TYPE_OTHER) {
                $value = $feeOtherTotal + $value;
            }
            $posX = $showFeeList[$key]['x'];
            $posY = $showFeeList[$key]['y'];

            array_push($printList, ['str' => (string) $value, 'posX' => $posX, 'posY' => $posY, 'fontSize' => $defaultFontSize]);
        }

        //定金优惠券
        array_push($printList, ['str' => "定金和券", 'posX' => 51.5, 'posY' => 26.1 + 10 * 4.7, 'fontSize' => $defaultFontSize]);
        array_push($printList, ['str' => (string) ($balanceCouponTotal + $balanceDepositTotal), 'posX' => 72, 'posY' => 26.1 + 10 * 4.7, 'fontSize' => $defaultFontSize]);

        $deptPositions = [
            'x_distance'         => -33,
            'dept_item_distance' => 40,
            'dept_item_d_y'      => 4,
            'dept_name'          => [206, 29.2],
            'customer_name'      => [206, 34.2],
            'dept_item_base'     => [198, 44.5],
            'dept_total'         => [206, 71.8],
            'timestamp'          => [206, 77],
        ];
        $deptCount = 0;

        foreach ($deptFeeList as $key => $deptFee) {
            if ($deptCount >= 4) {
                break;
            }
            $deptNamePosX = $deptPositions['dept_name'][0] + $deptPositions['x_distance'] * $deptCount;
            array_push($printList, ['str' => $deptFee['dept_name'], 'posX' => $deptNamePosX, 'posY' => $deptPositions['dept_name'][1], 'fontSize' => $defaultFontSize]);

            $customerNamePosX = $deptPositions['customer_name'][0] + $deptPositions['x_distance'] * $deptCount;
            array_push($printList, ['str' => $customerName, 'posX' => $customerNamePosX, 'posY' => $deptPositions['customer_name'][1], 'fontSize' => $defaultFontSize]);

            $deptItemCount = 0;
            $deptItemPosX  = $deptPositions['dept_item_base'][0] + $deptPositions['x_distance'] * $deptCount;
            foreach ($deptFee['dept_pros'] as $key => $deptPro) {
                $deptItemPosY = $deptPositions['dept_item_base'][1] + $deptItemCount * $deptPositions['dept_item_d_y'];
                array_push($printList, ['str' => $deptPro, 'posX' => $deptItemPosX, 'posY' => $deptItemPosY, 'fontSize' => $itemFontSize]);
                $deptItemCount++;
            }

            $deptTotalPosX = $deptPositions['dept_total'][0] + $deptPositions['x_distance'] * $deptCount;
            array_push($printList, ['str' => $deptFee['dept_total'], 'posX' => $deptTotalPosX, 'posY' => $deptPositions['dept_total'][1], 'fontSize' => $defaultFontSize]);
            $timestampPosX = $deptPositions['timestamp'][0] + $deptPositions['x_distance'] * $deptCount;
            array_push($printList, ['str' => $printTime, 'posX' => $timestampPosX, 'posY' => $deptPositions['timestamp'][1], 'fontSize' => $cusFontSize1]);

            $deptCount++;
        }
        // $admin = \think\session::get('admin');
        $admin = model('Admin')->field('nickname')->find($adminId);
        if (!empty($admin)) {
            $adminNickName = $admin['nickname'];
        } else {
            $adminNickName = '--';
        }

        array_push($printList, ['str' => $balanceTotal, 'posX' => 37, 'posY' => 77.3, 'fontSize' => $defaultFontSize]);
        array_push($printList, ['str' => $balanceTotalChnStr, 'posX' => 37, 'posY' => 81.9
            , 'fontSize' => $defaultFontSize]);

        array_push($printList, ['str' => $printTime, 'posX' => 23.5, 'posY' => 87.5, 'fontSize' => $defaultFontSize]);
        array_push($printList, ['str' => $adminNickName, 'posX' => 67, 'posY' => 87.5, 'fontSize' => $defaultFontSize]);

        if (!empty($offsetX) || !empty($offsetY)) {
            foreach ($printList as $key => $row) {
                $printList[$key]['posX'] += $offsetX;
                $printList[$key]['posY'] += $offsetY;
            }
        }

        return $printList;
    }

    public static function generateReceipt2($balances, $balanceIds, $adminId)
    {
        $offsetX      = floatval(\think\Config::get('site.receiptOffsetX'));
        $offsetY      = floatval(\think\Config::get('site.receiptOffsetY'));
        $customer     = model('Customer')->field('ctm_id, ctm_name')->find(current($balances)['customer_id']);
        $customerName = '--';
        if (!empty($customer)) {
            $customerName = $customer->ctm_name . "[$customer->ctm_id]";
        }

        $printList        = array();
        $defaultFontSize  = 10;
        $itemFontSize     = 9;
        $cusFontSize1     = 9;
        $feeFontSize      = 10;
        $feeLabelFontSize = 13;
        //[str, font, color, posX, posY]

        array_push($printList, ['str' => $customerName, 'posX' => 34, 'posY' => 26.5, 'fontSize' => $defaultFontSize]);

        $balanceTotal        = 0.00;
        $balanceTotalAbs     = 0.00;
        $balanceCouponTotal  = 0.00;
        $balanceDepositTotal = 0.00;
        $extraBalanceTotal   = 0.00;
        foreach ($balances as $key => $balance) {
            $balanceTotal += $balance['pay_total'];
            $balanceCouponTotal += $balance['coupon_total'];
            $balanceDepositTotal += $balance['deposit_total'];

            //非项目付款，记入额外营收
            if ($balance['balance_type'] != BalanceType::TYPE_PROJECT_PAY) {
                $extraBalanceTotal += $balance['pay_total'];
                continue;
            }
        }

        //初始化费用列表--按费用类型
        $feeList = array();
        //实际显示费用类型
        $feeList = [
            1               => ['x' => 35.2, 'y' => 36.9, 'total' => 0.00],
            2               => ['x' => 35.2, 'y' => 42.4, 'total' => 0.00],
            3               => ['x' => 35.2, 'y' => 47.9, 'total' => 0.00],
            19              => ['x' => 35.2, 'y' => 53.4, 'total' => 0.00],
            11              => ['x' => 35.2, 'y' => 58.9, 'total' => 0.00],

            20              => ['x' => 90.0, 'y' => 36.9, 'total' => 0.00],
            21              => ['x' => 90.0, 'y' => 42.4, 'total' => 0.00],
            4               => ['x' => 90.0, 'y' => 47.9, 'total' => 0.00],
            23              => ['x' => 90.0, 'y' => 75.4, 'total' => 0.00],

            5               => ['x' => 143.0, 'y' => 36.9, 'total' => 0.00],
            7               => ['x' => 143.0, 'y' => 58.9, 'total' => 0.00],
            Fee::TYPE_OTHER => ['x' => 143.0, 'y' => 64.49, 'total' => 0.00],
            22              => ['x' => 143.0, 'y' => 69.9, 'total' => 0.00],
        ];

        //项目，费用清单--按项目部门
        $deptFeeList = array();
        $deptList    = \app\admin\model\Deptment::getDeptListCache();

        $projectItems = model('OrderItems')->alias('order_items')
            ->join(model('Project')->getTable() . ' pro', 'order_items.pro_id = pro.pro_id', 'LEFT')
            ->where(['order_items.balance_id' => ['in', $balanceIds]])
            ->column('order_items.pro_id, order_items.pro_name, order_items.item_total, order_items.pro_spec, order_items.dept_id, pro.pro_fee_type', 'order_items.pro_id');
        // static::receiptDeal($projectItems, $feeList, $deptFeeList, $deptList);

        array_map(function ($proItem) use (&$feeList) {
            if (isset($feeList[$proItem['pro_fee_type']])) {
                $feeList[$proItem['pro_fee_type']]['total'] += $proItem['item_total'];
            } else {
                $feeList[\app\admin\model\Fee::TYPE_OTHER]['total'] += $proItem['item_total'];
            }
        }, $projectItems);

        foreach ($feeList as $key => $feeSet) {
            array_push($printList, ['str' => $feeSet['total'], 'posX' => $feeSet['x'], 'posY' => $feeSet['y'], 'fontSize' => $feeFontSize]);
        }

        $maxPos        = 1;
        $bArr          = [];
        $numChineseArr = ['零', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖'];

        $bLast = $balanceTotalAbs = abs($balanceTotal);
        for ($i = 10000; $i >= 1; $i = $i / 10) {
            $bArr[$i] = (int) ($bLast / $i);
            $bLast    = $bLast - $i * $bArr[$i];

            if ($bArr[$i] != 0 && $maxPos < $i) {
                $maxPos = $i;
            }

            if ($i > 1 && $i > $maxPos && $bArr[$i] == 0) {
                $bArr[$i] = '';
            } else {
                if ($bArr[$i] >= 10) {
                    $bArr[$i] = rtrim(number2chinese($bArr[$i], true, false), '元');
                } else {
                    $bArr[$i] = $numChineseArr[($bArr[$i])];
                }
            }
        }
        $bArr['jiao'] = $numChineseArr[(string) substr($balanceTotalAbs * 10, -1, 1)];
        $bArr['fen']  = $numChineseArr[(string) substr($balanceTotalAbs * 100, -1, 1)];

        // 金额
        //正负数
        array_push($printList, ['str' => ($balanceTotal < 0 ? '负数' : ''), 'posX' => 35, 'posY' => 80.9, 'fontSize' => $defaultFontSize]);
        array_push($printList, ['str' => $bArr[10000], 'posX' => 44, 'posY' => 80.9, 'fontSize' => $defaultFontSize]);
        array_push($printList, ['str' => $bArr[1000], 'posX' => 58, 'posY' => 80.9, 'fontSize' => $defaultFontSize]);
        array_push($printList, ['str' => $bArr[100], 'posX' => 70, 'posY' => 80.9, 'fontSize' => $defaultFontSize]);
        array_push($printList, ['str' => $bArr[10], 'posX' => 82, 'posY' => 80.9, 'fontSize' => $defaultFontSize]);
        array_push($printList, ['str' => $bArr[1], 'posX' => 94, 'posY' => 80.9, 'fontSize' => $defaultFontSize]);
        array_push($printList, ['str' => $bArr['jiao'], 'posX' => 105, 'posY' => 80.9, 'fontSize' => $defaultFontSize]);
        array_push($printList, ['str' => $bArr['fen'], 'posX' => 116, 'posY' => 80.9, 'fontSize' => $defaultFontSize]);
        array_push($printList, ['str' => $balanceTotal, 'posX' => 134, 'posY' => 80.9, 'fontSize' => $defaultFontSize]);

        //定金优惠券
        // 143 754
        array_push($printList, ['str' => "定金和券", 'posX' => 119, 'posY' => 75.4, 'fontSize' => $feeLabelFontSize]);
        array_push($printList, ['str' => (string) ($balanceCouponTotal + $balanceDepositTotal), 'posX' => 143, 'posY' => 75.4, 'fontSize' => $feeFontSize]);

        $admin = model('Admin')->field('nickname')->find($adminId);
        if (!empty($admin)) {
            $adminNickName = $admin['nickname'];
        } else {
            $adminNickName = '--';
        }

        //打印时间
        array_push($printList, ['str' => date('Y'), 'posX' => 62, 'posY' => 19.8, 'fontSize' => $defaultFontSize]);
        array_push($printList, ['str' => date('m'), 'posX' => 83, 'posY' => 19.8, 'fontSize' => $defaultFontSize]);
        array_push($printList, ['str' => date('d'), 'posX' => 98, 'posY' => 19.8, 'fontSize' => $defaultFontSize]);
        array_push($printList, ['str' => $adminNickName, 'posX' => 125, 'posY' => 92.0, 'fontSize' => $defaultFontSize]);

        if (!empty($offsetX) || !empty($offsetY)) {
            foreach ($printList as $key => $row) {
                $printList[$key]['posX'] += $offsetX;
                $printList[$key]['posY'] += $offsetY;
            }
        }

        return $printList;
    }

    public static function generateReceipt3($balances, $balanceIds, $adminId)
    {
        $offsetX      = floatval(\think\Config::get('site.receiptOffsetX'));
        $offsetY      = floatval(\think\Config::get('site.receiptOffsetY'));
        $customer     = model('Customer')->field('ctm_id, ctm_name')->find(current($balances)['customer_id']);
        $customerName = '--';
        if (!empty($customer)) {
            $customerName = $customer->ctm_name . "[$customer->ctm_id]";
        }

        $printList        = array();
        $defaultFontSize  = 10;
        $itemFontSize     = 9;
        $cusFontSize1     = 9;
        $feeFontSize      = 10;
        $feeLabelFontSize = 12;
        //[str, font, color, posX, posY]

        array_push($printList, ['str' => $customerName, 'posX' => 31.0, 'posY' => 34.5, 'fontSize' => $defaultFontSize]);

        $balanceTotal        = 0.00;
        $balanceCouponTotal  = 0.00;
        $balanceDepositTotal = 0.00;
        $extraBalanceTotal   = 0.00;
        foreach ($balances as $key => $balance) {
            $balanceTotal += $balance['pay_total'];
            $balanceCouponTotal += $balance['coupon_total'];
            $balanceDepositTotal += $balance['deposit_total'];

            //非项目付款，记入额外营收
            if ($balance['balance_type'] != BalanceType::TYPE_PROJECT_PAY) {
                $extraBalanceTotal += $balance['pay_total'];
                continue;
            }
        }

        //初始化费用列表--按费用类型
        $feeList = array();
        //实际显示费用类型
        $feeList = [
            20              => ['x' => 97.0, 'y' => 45.0, 'total' => 0.00],
            21              => ['x' => 97.0, 'y' => 51.0, 'total' => 0.00],
            4               => ['x' => 97.0, 'y' => 56.2, 'total' => 0.00],

            5               => ['x' => 150.0, 'y' => 45.0, 'total' => 0.00],
            9               => ['x' => 150.0, 'y' => 51.0, 'total' => 0.00],
            10              => ['x' => 150.0, 'y' => 56.2, 'total' => 0.00],

            7               => ['x' => 150.0, 'y' => 66.8, 'total' => 0.00],
            Fee::TYPE_OTHER => ['x' => 150.0, 'y' => 72.1, 'total' => 0.00],
            22              => ['x' => 150.0, 'y' => 77.2, 'total' => 0.00],
        ];

        //项目，费用清单--按项目部门
        $deptFeeList = array();
        $deptList    = \app\admin\model\Deptment::getDeptListCache();

        $projectItems = model('OrderItems')->alias('order_items')
            ->join(model('Project')->getTable() . ' pro', 'order_items.pro_id = pro.pro_id', 'LEFT')
            ->where(['order_items.balance_id' => ['in', $balanceIds]])
            ->column('order_items.pro_id, order_items.pro_name, order_items.item_total, order_items.pro_spec, order_items.dept_id, pro.pro_fee_type', 'order_items.pro_id');
        // static::receiptDeal($projectItems, $feeList, $deptFeeList, $deptList);

        array_map(function ($proItem) use (&$feeList) {
            if (isset($feeList[$proItem['pro_fee_type']])) {
                $feeList[$proItem['pro_fee_type']]['total'] += $proItem['item_total'];
            } else {
                $feeList[\app\admin\model\Fee::TYPE_OTHER]['total'] += $proItem['item_total'];
            }
        }, $projectItems);

        foreach ($feeList as $key => $feeSet) {
            array_push($printList, ['str' => $feeSet['total'], 'posX' => $feeSet['x'], 'posY' => $feeSet['y'], 'fontSize' => $feeFontSize]);
        }

        $maxPos        = 1;
        $bArr          = [];
        $numChineseArr = ['零', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖'];

        $bLast = $balanceTotalAbs = abs($balanceTotal);
        for ($i = 10000; $i >= 1; $i = $i / 10) {
            $bArr[$i] = (int) ($bLast / $i);
            $bLast    = $bLast - $i * $bArr[$i];

            if ($bArr[$i] != 0 && $maxPos < $i) {
                $maxPos = $i;
            }

            if ($i > 1 && $i > $maxPos && $bArr[$i] == 0) {
                $bArr[$i] = '';
            } else {
                if ($bArr[$i] >= 10) {
                    $bArr[$i] = rtrim(number2chinese($bArr[$i], true, false), '元');
                } else {
                    $bArr[$i] = $numChineseArr[($bArr[$i])];
                }
            }
        }
        $bArr['jiao'] = $numChineseArr[(string) substr($balanceTotalAbs * 10, -1, 1)];
        $bArr['fen']  = $numChineseArr[(string) substr($balanceTotalAbs * 100, -1, 1)];

        // 金额
        array_push($printList, ['str' => $bArr[10000], 'posX' => 45.0, 'posY' => 88.5, 'fontSize' => $defaultFontSize]);
        array_push($printList, ['str' => $bArr[1000], 'posX' => 59.0, 'posY' => 88.5, 'fontSize' => $defaultFontSize]);
        array_push($printList, ['str' => $bArr[100], 'posX' => 71.0, 'posY' => 88.5, 'fontSize' => $defaultFontSize]);
        array_push($printList, ['str' => $bArr[10], 'posX' => 84, 'posY' => 88.5, 'fontSize' => $defaultFontSize]);
        array_push($printList, ['str' => $bArr[1], 'posX' => 94, 'posY' => 88.5, 'fontSize' => $defaultFontSize]);
        array_push($printList, ['str' => $bArr['jiao'], 'posX' => 105, 'posY' => 88.5, 'fontSize' => $defaultFontSize]);
        array_push($printList, ['str' => $bArr['fen'], 'posX' => 116, 'posY' => 88.5, 'fontSize' => $defaultFontSize]);
        array_push($printList, ['str' => $balanceTotal, 'posX' => 134, 'posY' => 88.5, 'fontSize' => $defaultFontSize]);

        //定金优惠券
        // 143 754
        array_push($printList, ['str' => "定金和券", 'posX' => 116.0, 'posY' => 82.5, 'fontSize' => $feeLabelFontSize]);
        array_push($printList, ['str' => (string) ($balanceCouponTotal + $balanceDepositTotal), 'posX' => 150, 'posY' => 82.5, 'fontSize' => $feeFontSize]);

        $admin = model('Admin')->field('nickname')->find($adminId);
        if (!empty($admin)) {
            $adminNickName = $admin['nickname'];
        } else {
            $adminNickName = '--';
        }

        //打印时间
        array_push($printList, ['str' => date('Y'), 'posX' => 62, 'posY' => 28.0, 'fontSize' => $defaultFontSize]);
        array_push($printList, ['str' => date('m'), 'posX' => 83, 'posY' => 28.0, 'fontSize' => $defaultFontSize]);
        array_push($printList, ['str' => date('d'), 'posX' => 100.0, 'posY' => 28.0, 'fontSize' => $defaultFontSize]);
        array_push($printList, ['str' => $adminNickName, 'posX' => 131.0, 'posY' => 99.5, 'fontSize' => $defaultFontSize]);

        if (!empty($offsetX) || !empty($offsetY)) {
            foreach ($printList as $key => $row) {
                $printList[$key]['posX'] += $offsetX;
                $printList[$key]['posY'] += $offsetY;
            }
        }

        return $printList;
    }

    public static function receiptDeal($proItems, &$feeList, &$deptFeeList, $deptList)
    {
        foreach ($proItems as $key => $proItem) {
            if (isset($feeList[$proItem['pro_fee_type']])) {
                $feeList[$proItem['pro_fee_type']] += $proItem['item_total'];
            } else {
                $feeList[\app\admin\model\Fee::TYPE_OTHER] += $proItem['item_total'];
            }

            if (!isset($deptFeeList[$proItem['dept_id']])) {
                $deptFeeList[$proItem['dept_id']] = array(
                    'dept_name'  => isset($deptList[$proItem['dept_id']]) ? $deptList[$proItem['dept_id']]['dept_name'] : '--',
                    'dept_total' => 0.00,
                    'dept_pros'  => array(),
                );
            }

            array_push($deptFeeList[$proItem['dept_id']]['dept_pros'], $proItem['pro_name']);
            $deptFeeList[$proItem['dept_id']]['dept_total'] += $proItem['item_total'];
        }
    }

}
