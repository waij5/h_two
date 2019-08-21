<?php

namespace app\admin\model;
use app\admin\model\Tooltype;

class CocAcceptTool
{
    static $data = [
                    '' => '--',
                    '1' => '商务通',
                    '2' => '微信',
                    '3' => 'QQ',
                    '4' => '微博',
                    '5' => '陌陌',
                    '6' => '微信公众平台',
                    '7' => '网站合作',
                    '8' => 'PC',
                    '9' => '获取',
                    '10' => '介绍',
                    
                    '11' => '快商通',
                    '12' => '第三方',
                    '13' => '留言',
                    '14' => '神马',
                    '15' => '搜狗',
                    '16' => '120ASK',
                    '17' => '网电数据',
                    '18' => '抓取',
                    '19' => '劫持',
                    '20' => '快商通微信',

                    '21' => '百度糯米',
                    '22' => '寻医问药',
                    '23' => '大众点评',
                    '24' => '百度',
                    '25' => '360',
                    '26' => '电话',

                    '99' => '其它',
                    // 快商通 第三方 留言 微信 朋友介绍 陌陌 神马 搜狗 120ASK 网电数据 抓取 劫持  快商通微信 百度糯米 寻医问药 大众点评 百度 360
                    
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
        return Tooltype::getToolListCache();
        // return self::$data;
    }
}