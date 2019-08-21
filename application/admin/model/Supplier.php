<?php

namespace app\admin\model;

use think\Model;

class Supplier extends Model
{
    // 表名
    protected $name = 'wm_supplier';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'sup_stime';
    protected $updateTime = 'sup_etime';
    

    

    
 
    public function getTypeList()
    {
        return ['1' => __('Sup_type 1'),'2' => __('Sup_type 2'),'3' => __('Sup_type 3')];
    }     

    public function getStatusList()
    {
        return ['1' => __('Sup_status 1'),'0' => __('Sup_status 0')];
    }     


    


}
