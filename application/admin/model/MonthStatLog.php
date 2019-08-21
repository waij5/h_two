<?php

namespace app\admin\model;

use think\Model;

class MonthStatLog extends Model
{
    // 表名
    protected $name = 'month_stat_log';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'endtime';
}