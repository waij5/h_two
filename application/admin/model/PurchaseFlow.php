<?php

namespace app\admin\model;

use think\Model;

class PurchaseFlow extends Model
{
    // 表名
    protected $name = 'purchase_flow';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [
        'producttime_text',
        'expirestime_text'
    ];
    

    



    public function getProducttimeTextAttr($value, $data)
    {
        $value = $value ? $value : $data['producttime'];
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getExpirestimeTextAttr($value, $data)
    {
        $value = $value ? $value : $data['expirestime'];
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setProducttimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }

    protected function setExpirestimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }


}
