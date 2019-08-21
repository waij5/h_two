<?php

namespace app\admin\model;

class Fee
{
     static $fee = [
        '1' => '西药',
        '2' => '中成药',
        '3' => '中草药',
        '4' => '检查费',
        '5' => '治疗费',

        '6' => '放射费',
        '7' => '手术费',
        '8' => '化验费',
        '9' => '输血费',
        '10' => '输氧费',

        '11' => '材料费',
        '12' => '家庭病床',
        '13' => '诊察费',
        '14' => '钼钯费',
        '15' => '抢救费',

        '16' => '病理费',
        '17' => '输液费',
        '18' => '彩超费',

        '19' => '注射费',
        '20' => '诊查费',
        '21' => '急诊留观床位费',
        '22' => '特需服务费',
        '23' => '检验费',
        self::TYPE_OTHER => '其它',
    ];

    const TYPE_OTHER = 999;


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
}