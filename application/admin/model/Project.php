<?php

namespace app\admin\model;

use think\Model;

class Project extends Model
{
    // 表名
    protected $name = 'project';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    
    // 追加属性
    protected $append = [

    ];

    const TYPE_MEDICINE = 1;
    const TYPE_PRODUCT = 2;
    const TYPE_PROJECT = 9;
    // static $TYPE_TITLES = [
    //     static::TYPE_MEDICINE = '药品',
    //     static::TYPE_PRODUCT = '物资',
    //     static::TYPE_PROJECT = '项目',
    // ];

    static $fee = [
        '1' => '手术费',
        '2' => '耗材费',
        '3' => '美容费',
        '4' => '药费',
        '5' => '检验费',
        // '1' => '西药',
        // '2' => '中成药',
        // '3' => '中草药',
        // '4' => '检查费',
        // '5' => '治疗费',

        // '6' => '放射费',
        // '7' => '手术费',
        // '8' => '化验费',
        // '9' => '输血费',
        // '10' => '输氧费',

        // '11' => '材料费',
        // '12' => '家庭病床',
        // '13' => '诊察费',
        // '14' => '钼钯费',
        // '15' => '抢救费',

        // '16' => '病理费',
        // '17' => '输液费',
        // '18' => '彩超费',
        // '19' => '其它',
    ];

    public static function getTitleById($id)
    {
        $title = '';
        if (isset(self::$fee[$id])) {
            $title = self::$fee[$id];
        }

        return $title;
    }

    public static function getList()
    {
        return self::$fee;
    }

    public static function getTypeList()
    {
        return [
                static::TYPE_PROJECT => __('Pro_type_' . static::TYPE_PROJECT),
                static::TYPE_MEDICINE => __('Pro_type_' . static::TYPE_MEDICINE),
                static::TYPE_PRODUCT => __('Pro_type_' . static::TYPE_PRODUCT),
        ];
    }

    public static function getTypeTitle($type)
    {
        $titleList = static::getTypeList();
        return isset($titleList[$type]) ? $titleList[$type] : '';
    }
}
