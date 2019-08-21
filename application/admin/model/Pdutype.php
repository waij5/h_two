<?php

namespace app\admin\model;

use think\Model;

class Pdutype extends Model
{
    // 表名
    protected $name = 'pdutype';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    
    // 追加属性
    protected $append = [
    ];
    
    public function getTypeList()
    {
        return ['1' => __('Type 1'),'2' => __('Type 2')];
    }     

    public function getStatusList()
    {
        return ['normal' => __('Status normal'),'hidden' => __('Status hidden')];
    }

}
