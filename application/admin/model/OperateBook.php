<?php

namespace app\admin\model;

use think\Model;

class OperateBook extends Model
{
    // 表名
    protected $name = 'operate_book';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    
    // 追加属性
    protected $append = [

    ];
    

    const STATUS_BOOKED  = 2;
    const STATUS_CANCELED   = 1;



    public function operatePros()
    {
        // obk_id
        return $this->hasMany('\app\admin\model\OperatePro', 'book_id');
    }



}
