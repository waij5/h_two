<?php

namespace app\admin\model;

use think\Model;

class CustomerMapLog extends Model
{
    // 表名
    protected $name = 'customer_map_log';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
}