<?php

namespace app\admin\model;

use think\Model;

class Producer extends Model
{
    // 表名
    protected $name = 'producer';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    
    // 追加属性
    protected $append = [
        'is_pro_text',
        'type_text',
        'status_text'
    ];
    

    
    public function getIsProList()
    {
        return ['1' => __('Is_pro 1'),'2' => __('Is_pro 2')];
    }     

    public function getTypeList()
    {
        return ['1' => __('Type 1'),'2' => __('Type 2')];
    }     

    public function getStatusList()
    {
        return ['normal' => __('Status 1'),'hidden' => __('Status 0')];
    }     


    public function getIsProTextAttr($value, $data)
    {        
        $value = $value ? $value : $data['is_pro'];
        $list = $this->getIsProList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getTypeTextAttr($value, $data)
    {        
        $value = $value ? $value : $data['type'];
        $list = $this->getTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStatusTextAttr($value, $data)
    {        
        $value = $value ? $value : $data['status'];
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
