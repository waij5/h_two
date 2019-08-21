<?php

namespace app\admin\model;

use think\Model;

class OperateRota extends Model
{
    // 表名
    protected $name = 'operate_rota';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;

    // 追加属性
    protected $append = [

    ];

    const STATUS_BOOKED  = 2;
    const STATUS_READY   = 1;
    const STATUS_INVALID = 0;

}
