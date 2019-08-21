<?php

namespace app\admin\model;

use think\Model;

class Purchase extends Model
{
	 // 表名
    protected $name = 'wm_purchase';
    protected $autoWriteTimestamp = 'int';
    protected $createTime = 'mcreatetime';
    protected $updateTime = '';

    public function dbStartTrans($res){
        if($res['error'] == false){
            \think\Db::commit();
            return 1;
        }else{
            \think\Db::rollback();
            return 2;
        }
    }
}