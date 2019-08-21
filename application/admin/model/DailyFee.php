<?php
namespace app\admin\model;

use think\Model;

class DailyFee extends Model
{
// 表名
    protected $name = 'daily_fee';

    protected $pk = 'id';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;

}
