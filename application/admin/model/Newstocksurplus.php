<?php

namespace app\admin\model;

use think\Model;

class Newstocksurplus extends Model
{
    // 表名   货单表
    protected $name = 'project';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    // protected $createTime = 'mcreatetime';
    // protected $updateTime = '';

//处理本期数据dateList,所有产品数据allProData,处理类型dealType=1为etime>time()状况;
    public function dealAllData($dateList,$limitProData,$dealType){

    	if($dealType==1){
    		$nowDateAllData =[];
	        foreach ($dateList as $k => $v) {
	            $nowDateAllData[$v['pro_id']]['jh_num'] = 0;
	            $nowDateAllData[$v['pro_id']]['jh_cost'] = 0;

	            $nowDateAllData[$v['pro_id']]['rk_num'] = 0;
	            $nowDateAllData[$v['pro_id']]['rk_cost'] = 0;

	            $nowDateAllData[$v['pro_id']]['cj_num'] = 0;
	            $nowDateAllData[$v['pro_id']]['cj_cost'] = 0;

	            $nowDateAllData[$v['pro_id']]['ly_num'] = 0;
	            $nowDateAllData[$v['pro_id']]['ly_cost'] = 0;

	            $nowDateAllData[$v['pro_id']]['ll_num'] = 0;
	            $nowDateAllData[$v['pro_id']]['ll_cost'] = 0;

	            $nowDateAllData[$v['pro_id']]['fcf_num'] = 0;
	            $nowDateAllData[$v['pro_id']]['fcf_cost'] = 0;

	            $nowDateAllData[$v['pro_id']]['ccf_num'] = 0;
	            $nowDateAllData[$v['pro_id']]['ccf_cost'] = 0;
	            // $nowDateAllData[$v['pro_id']][$v['sltype']]['slnum'] = 0;
	        }

	        foreach ($dateList as $k => $v) {  
	            if($v['sltype']==1){
	                $nowDateAllData[$v['pro_id']]['jh_num'] += $v['slnum'];
	                $nowDateAllData[$v['pro_id']]['jh_cost'] += $v['slallcost'];
	            }elseif($v['sltype']==2){
	                $nowDateAllData[$v['pro_id']]['rk_num'] += $v['slnum'];
	                $nowDateAllData[$v['pro_id']]['rk_cost'] += $v['slallcost'];
	            }elseif($v['sltype']==3){
	                $nowDateAllData[$v['pro_id']]['cj_num'] += $v['slnum'];
	                $nowDateAllData[$v['pro_id']]['cj_cost'] += $v['slallcost'];
	            }elseif($v['sltype']==4){
	                $nowDateAllData[$v['pro_id']]['ly_num'] += $v['slnum'];
	                $nowDateAllData[$v['pro_id']]['ly_cost'] += $v['slallcost'];
	            }elseif($v['sltype']==5){
	                $nowDateAllData[$v['pro_id']]['ll_num'] += $v['slnum'];
	                $nowDateAllData[$v['pro_id']]['ll_cost'] += $v['slallcost'];
	            }elseif($v['sltype']==6){
	                $nowDateAllData[$v['pro_id']]['fcf_num'] += $v['slnum'];
	                $nowDateAllData[$v['pro_id']]['fcf_cost'] += $v['slallcost'];
	            }elseif($v['sltype']==7){
	                $nowDateAllData[$v['pro_id']]['ccf_num'] += $v['slnum'];
	                $nowDateAllData[$v['pro_id']]['ccf_cost'] += $v['slallcost'];
	            }
	        }

	        // $dealData = [];//本期计算后数据:本期单个产品总进、出库存及成本 和 本期所有产品总和        
	        $dealData = [];
	        // $dealData['totalNowEnterStock']=
	        // $dealData['totalNowOutStock']=
	        $dealData['totalNowEnterCost']=
	        $dealData['totalNowOutCost'] = 0;

	        foreach ($nowDateAllData as $k => $v) {
	        	if(!empty($limitProData[$k])){
	        		$limitProData[$k]['nowEnterStock'] =  intval($v['jh_num']+$v['rk_num']-$v['cj_num']);
	                $limitProData[$k]['nowEnterCost'] =  bcadd(bcadd($v['jh_cost'], $v['rk_cost'],4),  -$v['cj_cost'],4);

	                $limitProData[$k]['nowOutStock'] =  intval($v['ly_num']+$v['ll_num']+$v['fcf_num']-$v['ccf_num']);
	            	$limitProData[$k]['nowOutCost'] = bcadd(bcadd(bcadd($v['ly_cost'],$v['ll_cost'],4),  $v['fcf_cost'],4),  -$v['ccf_cost'],4);
	        	}
	        	


	            // $allProData[$k]['nowEnterStock'] =  intval($v['jh_num']+$v['rk_num']-$v['cj_num']);
	            // $allProData[$k]['nowEnterCost'] =  bcadd(bcadd($v['jh_cost'], $v['rk_cost'],4),  -$v['cj_cost'],4);

	            // $dealData['totalNowEnterStock'] += intval($v['jh_num']+$v['rk_num']-$v['cj_num']);
	            $dealData['totalNowEnterCost'] += bcadd(bcadd($v['jh_cost'], $v['rk_cost'],4),  -$v['cj_cost'],4);

	            // $allProData[$k]['nowOutStock'] =  intval($v['ly_num']+$v['ll_num']+$v['fcf_num']-$v['ccf_num']);
	            // $allProData[$k]['nowOutCost'] = bcadd(bcadd(bcadd($v['ly_cost'],$v['ll_cost'],4),  $v['fcf_cost'],4),  -$v['ccf_cost'],4);

	            // $dealData['totalNowOutStock'] += intval($v['ly_num']+$v['ll_num']+$v['fcf_num']-$v['ccf_num']);
	            $dealData['totalNowOutCost'] += bcadd(bcadd(bcadd($v['ly_cost'],$v['ll_cost'],4),  $v['fcf_cost'],4),  -$v['ccf_cost'],4);

	            // $dealData['totalFinalCost'] = += bcadd($v[''],4);
	            
	        }

	        $dealData['limitProData']= $limitProData;

    	}
    	
    	
        return $dealData;
    }

}