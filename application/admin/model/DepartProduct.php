<?php

namespace app\admin\model;

use think\Model;

class DepartProduct extends Model
{
    // 表名
    protected $name = 'depart_product';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
}
