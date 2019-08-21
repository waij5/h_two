<?php

namespace app\admin\model;

use think\Model;

class FirstToolId extends Model
{
    // 表名
    protected $name = 'first_tool_apply_records';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
}