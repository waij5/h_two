<?php

namespace app\admin\model;

class DeductType
{
    static $data = [
                    'project' => '项目',
                    'product_1' => '药品',
                    'product_2' => '物品',
                    // 'opeation' => '手术',
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