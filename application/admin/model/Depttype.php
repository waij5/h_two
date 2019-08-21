<?php

namespace app\admin\model;

class Depttype
{
    static $data = [
                    'other' => '其他',
                    'customerosconsult' => '现场客服',
                    'customerconsult' => '营销部门',
                    'deduct' => '划扣科室',
                    'Administration' => '行政',
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
}