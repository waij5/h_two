<?php

namespace app\admin\model;

use think\Model;

class Drugs extends Model
{
	 // 表名
    protected $name = 'project';
    protected $autoWriteTimestamp = 'int';
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    public function getDrugTypeList()
    {
        return ['1' => __('ptype 1'),'2' => __('ptype 2'),'3' => __('ptype 3'),'4' => __('ptype 4')];
    }  


    public function getStatusList()
    {
        return ['1' => __('Pro_status 1'),'0' => __('Pro_status 0')];
    } 


}