<?php

namespace app\admin\model;

use think\Model;

class Itemchange extends Model
{
    // 表名
    protected $name = 'order_admin_change';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [

    ];
    

    







}
