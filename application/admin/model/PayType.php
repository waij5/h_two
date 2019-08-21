<?php

namespace app\admin\model;

class PayType
{

    const TYPE_CASH = 1;
    const TYPE_CARD = 2;
    const TYPE_ALIPAY = 3;
    const TYPE_WECHATPAY = 4;
    const TYPE_OTHERPAY = 5;

    static $newData = [
                    self::TYPE_CASH => '现金',
                    self::TYPE_CARD => '银行卡',
                    self::TYPE_ALIPAY => '支付宝',
                    self::TYPE_WECHATPAY => '微信',
    ];

    static $data = [
                        self::TYPE_CASH => ['name' => 'cash_pay_total', 'title' => '现金', 'class' => 'payIcon payIcon-cash', 'icon' => 'payIcon/cashpay.png'],
                        self::TYPE_CARD => ['name' => 'card_pay_total', 'title' => '银行卡', 'class' => 'payIcon payIcon-card', 'icon' => 'payIcon/cardpay.png'],
                        self::TYPE_ALIPAY => ['name' => 'alipay_pay_total', 'title' => '支付宝', 'class' => 'payIcon payIcon-alipay', 'icon' => 'payIcon/alipay.png'],
                        self::TYPE_WECHATPAY => ['name' => 'wechatpay_pay_total', 'title' => '微信', 'class' => 'payIcon payIcon-wechatpay', 'icon' => 'payIcon/wechatpay.png'],
                        self::TYPE_OTHERPAY => ['name' => 'other_pay_total', 'title' => '其它', 'class' => 'payIcon payIcon-other', 'icon' => 'payIcon/other.png'],
    ];


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

    public static function getList2()
    {
        return self::$newData;
    }
}