<?php

namespace app\admin\model;

class Gender
{
    static $data = [
                    // '0' => '-',
                    '1' => '女',
                    '2' => '男',
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