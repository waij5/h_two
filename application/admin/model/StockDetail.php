<?php

namespace app\admin\model;

use think\Model;

class StockDetail extends Model
{
    // 表名
    protected $name = 'stock_detail';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;

    public function getTypeList()
    {
        return ['0' => __('Type 0'),'1' => __('Type 1'),'2' => __('Type 2')];
    }   
}
