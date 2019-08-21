<?php

namespace app\admin\model;

use think\Model;

class AccountLog extends Model
{
    // 表名
    protected $name = 'account_log';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;

    // 追加属性
    protected $append = [
        'change_time_text',
        'sync_time_text',
    ];

    //项目退款到定金
    const TYPE_CHARGE_BACK = 'CHARGE BACK';
    //更换项目
    const TYPE_SWITCH_ITEM = 'SWITCH ITEM';
    //预存定金
    const TYPE_PRESTORE = 'PRESTORE';
    //购买优惠券
    const TYPE_COUPON = 'COUPON';
    //订单付款
    const TYPE_PAY_ORDER = 'PAY ORDER';
    //定金退款
    const TYPE_PRESTORE_CHARGEBACK = 'PRESTORE CHARGEBACK';
    //分销
    const TYPE_DISTRIBUTE = 'DISTRIBUTE';
    //调整
    const TYPE_ADJUST = 'ADJUST';
    const TYPE_OTHER  = 'OTHER';
    //兑换
    const TYPE_EXCHANGE = 'EXCHANGE';
    //合并用户
    const TYPE_MALL_MERGE = 'MALL MERGE';
    const TYPE_MALL_IMPORT = 'MALL IMPORT';

    static $typeDate = [
        self::TYPE_CHARGE_BACK         => '项目退款到定金',
        self::TYPE_SWITCH_ITEM         => '更换项目',
        self::TYPE_PRESTORE            => '预存定金',
        self::TYPE_COUPON              => '购买优惠券',
        self::TYPE_PAY_ORDER           => '订单付款',
        self::TYPE_PRESTORE_CHARGEBACK => '定金退款',
        self::TYPE_DISTRIBUTE          => '分销',
        self::TYPE_ADJUST              => '调整',
        self::TYPE_OTHER               => '其他',
        self::TYPE_EXCHANGE            => '兑换',
        self::TYPE_MALL_MERGE          => '合并商城用户',
        self::TYPE_MALL_IMPORT          => '商城导入',

    ];

    public function getList()
    {
        return self::$typeDate;
    }

    public function getChangeTimeTextAttr($value, $data)
    {
        $value = $value ? $value : $data['change_time'];
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    public function getSyncTimeTextAttr($value, $data)
    {
        $value = $value ? $value : $data['sync_time'];
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setChangeTimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }

    protected function setSyncTimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }

}
