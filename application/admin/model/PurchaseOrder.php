<?php

namespace app\admin\model;

use think\Model;

class PurchaseOrder extends Model
{
    // 表名
    protected $name = 'purchase_order';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    
    // 追加属性
    protected $append = [
        'type_text',
        'rk_type_text',
        'is_drug_text',
        'is_jz_text',
        'is_cb_text',
        'cj_type_text',
        'status_text'
    ];
    

    
    public function getTypeList()
    {
        return ['0' => __('Type 0'),'1' => __('Type 1'),'2' => __('Type 2'),'3' => __('Type 3'),'4' => __('Type 4')];
    }     

    public function getRkTypeList()
    {
        return [
        // '0' => __('Rk_type 0'),
        '5' => __('Rk_type 5'),
        '1' => __('Rk_type 1'),'2' => __('Rk_type 2'),'3' => __('Rk_type 3'),
        // '4' => __('Rk_type 4'),
        ];
    }     

    public function getIsDrugList()
    {
        return ['1' => __('Is_drug 1'),'2' => __('Is_drug 2')];
    }     

    public function getIsJzList()
    {
        return ['0' => __('Is_jz 0'),'1' => __('Is_jz 1')];
    }     

    public function getIsCbList()
    {
        return ['0' => __('Is_cb 0'),'1' => __('Is_cb 1')];
    }     

    public function getCjTypeList()
    {
        return [
        // '0' => __('Cj_type 0'),
        '1' => __('Cj_type 1'),
        // '2' => __('Cj_type 2'),
        '3' => __('Cj_type 3'),'4' => __('Cj_type 4'),'5' => __('Cj_type 5')];
    }     

    public function getStatusList()
    {
        return ['normal' => __('Status normal'),'hidden' => __('Status hidden')];
    }     


    public function getTypeTextAttr($value, $data)
    {        
        $value = $value ? $value : $data['type'];
        $list = $this->getTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getRkTypeTextAttr($value, $data)
    {        
        $value = $value ? $value : $data['rk_type'];
        $list = $this->getRkTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getIsDrugTextAttr($value, $data)
    {        
        $value = $value ? $value : $data['is_drug'];
        $list = $this->getIsDrugList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getIsJzTextAttr($value, $data)
    {        
        $value = $value ? $value : $data['is_jz'];
        $list = $this->getIsJzList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getIsCbTextAttr($value, $data)
    {        
        $value = $value ? $value : $data['is_cb'];
        $list = $this->getIsCbList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getCjTypeTextAttr($value, $data)
    {        
        $value = $value ? $value : $data['cj_type'];
        $list = $this->getCjTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStatusTextAttr($value, $data)
    {        
        $value = $value ? $value : $data['status'];
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
