<?php

namespace app\admin\model;

use think\Model;

class Check extends Model
{
	// <!-- 2017-10-14  子非魚 -->
	//获取盘点仓库名称
	public function getDepotName($d_id = ''){
        if($d_id){
            $dName = model('Depot')->field('name')->where(['id' => $d_id])->select();
            return $dName;
        }else{
            return '';
        }
    }

    // <!-- 2017-10-14  子非魚 -->

}