<?php

namespace app\admin\model;

use think\Model;

class PointsClearBatch extends Model
{
    // 表名
    protected $name = 'points_clear_batch';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
}