<?php

namespace app\admin\model;

use think\Model;

class Depot extends Model
{
    // 表名
    protected $name = 'depot';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    
    // 追加属性
    protected $append = [
        'type_text',
        'status_text'
    ];
    

    
    public function getTypeList()
    {
        return ['1' => __('Type 1'),'2' => __('Type 2'),'3' => __('器械库(器械管理使用)'),'4' => __('耗材基库(耗材管理使用)')];
    }     

    //   获取默认仓库的中文名称
    public function getDepotName($type){       
        switch ($type) {
            case '1':
                return '药品库';
                break;
            case '2':
                return '物资库';
                break;
            case '3':
                return '器械库';
                break;
            case '4':
                return '耗材基库';
                break;
            default:
                # code...
                break;
        }
    }

    public function getStatusList()
    {
        return ['normal' => __('Status normal'),'hidden' => __('Status hidden')];
    }     

    public function getAdminList()
    {	
		$adminlists = model('admin')->field('nickname,id')->where(['status' => 'normal'])->select();
		$adminlist = [];
        foreach ($adminlists as $k => $v)
        {
            $adminlist[$v['nickname']] = $v;
        }
		return $adminlist;
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
