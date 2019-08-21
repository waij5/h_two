<?php

namespace app\admin\model;

class Osctype
{
    //请勿轻动，可能会影响业务成功率统计
    static $typeData = [
        '1' => '初次',
        '2' => '复次',
        '3' => '再消费',
        '4' => '复查',
        // '9' => '定金',
        '5' => '其它',
/*
'1' => '初诊',
'2' => '复诊',
'3' => '再消费',
'4' => '复查',
// '9' => '定金',
'5' => '其它',
 */
    ];

    public static function getTypeById($id)
    {
        $type = '';
        if (isset(self::$typeData[$id])) {
            $type = self::$typeData[$id];
        }

        return $type;
    }

    public static function getList()
    {
        return self::$typeData;
    }
}
