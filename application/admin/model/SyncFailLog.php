<?php

namespace app\admin\model;

use think\Model;

class SyncFailLog extends Model
{
    // 表名
    protected $name = 'sync_fail_log';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
}