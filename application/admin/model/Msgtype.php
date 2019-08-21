<?php

namespace app\admin\model;

class Msgtype
{
    const TYPE_SYSTEM = 1;
    const TYPE_DEPT = 2;
    const TYPE_REVISIT = 3;
    const TYPE_OTHER = 99;
    static $data = [
                    self::TYPE_SYSTEM => '系统消息',
                    self::TYPE_DEPT => '院内消息',
                    self::TYPE_REVISIT => '回访消息',
                    self::TYPE_SYSTEM => '其他',
                    
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