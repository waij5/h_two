<?php

namespace app\admin\model;

use think\Model;

class Goods extends Model
{
	 // 表名
    protected $name = 'project';
    protected $autoWriteTimestamp = 'int';
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    


    public function getStatusList()
    {
        return ['1' => __('Pro_status 1'),'0' => __('Pro_status 0')];
    } 


}