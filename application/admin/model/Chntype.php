<?php

namespace app\admin\model;

class Chntype
{
    static $data = [
        ''   => '--',
        '1'  => '网络客服',
        '2'  => '商务部',
        '3'  => '经营部',
        '4'  => '自然导诊',
        '5'  => '新媒体',

        '6'  => '电商平台',
        '7'  => '投诉',
        '8'  => '电话咨询',

        '99' => '其他',

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
