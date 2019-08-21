<?php

namespace app\admin\model;

use think\Model;

class Changedetail extends Model
{
    // 表名
    protected $name = 'stock_log';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;

    public function getTypeC()
    {
        return ['0' => __('Type 0'),
        // '1' => __('Type 1'),
        '11' => __('Type 11'),
        '12' => __('Type 12'),
        '13' => __('Type 13'),
        '15' => __('Type 15'),


        // '2' => __('Type 2'),
        '21' => __('Type 21'),
        '23' => __('Type 23'),
        '24' => __('Type 24'),
        '25' => __('Type 25'),

        '5' => __('Type 5'),
        '8' => __('Type 8'),
        '6' => __('Type 6'),'7' => __('Type 7'),'10' => __('Type 10'),
        
        '1111' => __('Type 1111'),
        '1211' => __('Type 1211'),
        '1311' => __('Type 1311'),
        '1511' => __('Type 1511'),
        '2111' => __('Type 2111'),
        '2311' => __('Type 2311'),
        '2411' => __('Type 2411'),
        '2511' => __('Type 2511'),



        // '3' => __('Type 3'),'4' => __('Type 4'),
        
        // '111' => __('Type 111'),'211' => __('Type 211'),
        '511' => __('Type 511'),'811' => __('Type 811')];
    }   
}
