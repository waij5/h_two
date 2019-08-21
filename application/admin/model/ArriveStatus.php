<?php

namespace app\admin\model;

class ArriveStatus
{
    static $typeData = [
                    '0' => '未上门',
                    '1' => '已上门',
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