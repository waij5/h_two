<?php

namespace app\admin\model;

use think\Model;

class FProSets extends Model
{
    // 表名
    protected $name = 'fpro_sets';
    protected $pk   = 'id';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;

    // 追加属性
    protected $append = [

    ];

}
