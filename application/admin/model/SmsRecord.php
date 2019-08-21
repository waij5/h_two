<?php

namespace app\admin\model;

use think\Model;

class SmsRecord extends Model
{
    // 表名
    protected $name = 'sms_records';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;

}