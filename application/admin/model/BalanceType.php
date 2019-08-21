<?php

namespace app\admin\model;

class BalanceType
{
    const TYPE_PRESTORE = 1;
    const TYPE_COUPON = 2;
    const TYPE_PROJECT_PAY = 3;
    const TYPE_ADJUST_INCOME = 9;
    const TYPE_PRESTORE_CHARGEBACK = -1;
    const TYPE_RETURN_COUPON = -2;
    const TYPE_PROJECT_CHARGEBACK = -3;
    const TYPE_ADJUST_OUTPAY = -9;

    static $data = [
                    self::TYPE_PRESTORE => '定金',
                    self::TYPE_COUPON => '购券',
                    self::TYPE_PROJECT_PAY => '项目收款',
                    self::TYPE_ADJUST_INCOME => '账外业务收入',
                    self::TYPE_PRESTORE_CHARGEBACK => '定金退款',
                    self::TYPE_RETURN_COUPON => '退券',
                    self::TYPE_PROJECT_CHARGEBACK => '项目退款',
                    self::TYPE_ADJUST_OUTPAY => '账外业务支出',

    ];

    //退费类型
    static $refundType = [
                    '2' => '开单错误取消',
                    '1' => '正常退款',
                    '9' => '其它',
    ];

    public static function getRefundList()
    {
        return self::$refundType;
    }

    public static function getTitleById($id)
    {
        $title = '';
        if (isset(self::$data[$id])) {
            $title = self::$data[$id];
        }

        return $title;
    }

    public static function getList()
    {
        return self::$data;
    }

    public static function getAdjustList() {

        $list = array();
        $list[self::TYPE_ADJUST_INCOME] = self::$data[self::TYPE_ADJUST_INCOME];
        $list[self::TYPE_ADJUST_OUTPAY] = self::$data[self::TYPE_ADJUST_OUTPAY];


        return $list;
    }
}