<?php

namespace app\admin\model;

use think\Model;

class Product extends Model
{
    // 表名
    protected $name = 'product';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    
    // 追加属性
    protected $append = [
        'is_jk_text',
        'drug_type_text',
        'status_text',
        'is_tc_text',
        'is_jf_text',
        'is_cg_text',
        'type_text'
    ];
    

    
    public function getIsJkList()
    {
        return ['1' => __('Is_jk 1'),'0' => __('Is_jk 0')];
    }     

    public function getDrugTypeList()
    {
        return ['1' => __('Drug_type 1'),'2' => __('Drug_type 2'),'3' => __('Drug_type 3'),'4' => __('Drug_type 4')];
    }     

    public function getStatusList()
    {
        return ['normal' => __('Status normal'),'hidden' => __('Status hidden')];
    }     

    public function getIsTcList()
    {
        return ['1' => __('Is_tc 1'),'0' => __('Is_tc 0')];
    }     

    public function getIsJfList()
    {
        return ['1' => __('Is_jf 1'),'0' => __('Is_jf 0')];
    }     

    public function getIsCgList()
    {
        return ['1' => __('Is_cg 1'),'0' => __('Is_cg 0')];
    }     

    public function getTypeList()
    {
        return ['1' => __('Type 1'),'2' => __('Type 2')];
    }     


    public function getIsJkTextAttr($value, $data)
    {        
        $value = $value ? $value : $data['is_jk'];
        $list = $this->getIsJkList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getDrugTypeTextAttr($value, $data)
    {        
        $value = $value ? $value : $data['drug_type'];
        $list = $this->getDrugTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStatusTextAttr($value, $data)
    {        
        $value = $value ? $value : $data['status'];
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getIsTcTextAttr($value, $data)
    {        
        $value = $value ? $value : $data['is_tc'];
        $list = $this->getIsTcList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getIsJfTextAttr($value, $data)
    {        
        $value = $value ? $value : $data['is_jf'];
        $list = $this->getIsJfList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getIsCgTextAttr($value, $data)
    {        
        $value = $value ? $value : $data['is_cg'];
        $list = $this->getIsCgList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getTypeTextAttr($value, $data)
    {        
        $value = $value ? $value : $data['type'];
        $list = $this->getTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


// <!-- 2017-09-28  子非魚 -->
//获取所属科目、类别名称
    public function getpName($p_id = ''){
        if($p_id){
            $pName = model('Protype')->field('name')->where(['id' => $p_id])->select();
            return $pName;
        }else{
            return '';
        }
    }

    public function getchineseName($data){
        if($data){
            foreach ($data as $key => $v) {
                 $pName = model('Product')->getpName($data[$key]['pdutype_id']);   //获取所属科目、类别名称
                 $pName2 = model('Product')->getpName($data[$key]['pdutype2_id']); 
                 if($pName){
                    foreach ($pName as $keys => $va) {
                        $data[$key]['pdutype_id'] = $va['name'];
                    }
                 }else{
                    $data[$key]['pdutype_id'] = '-';
                 }
                 
                 if($pName2){
                    foreach ($pName2 as $keys => $va) {
                        $data[$key]['pdutype2_id'] = $va['name'];
                    }
                 }else{
                    $data[$key]['pdutype2_id'] = '-';
                 }
                 // $v['onum'] = $key+1;          // 取得key值作为列表数据序号 
            }
        }
        return $data;
    }

    // 取得key值作为列表数据序号
    public function getOrderNumber($data){
        if($data){
            foreach ($data as $key => $v) {
                $v['onum'] = $key+1;
            }
        }
        return $data;
    }

// <!-- 2017-09-28  子非魚 -->
}
