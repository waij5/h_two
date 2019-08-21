<?php

namespace app\admin\model;

use think\Model;
// use think\DB;


class StockLog extends Model
{
	protected $name = 'wm_stocklog';


	/**        
	 * 			进货、入库等新增单号产品
	 * @param array $drugRows  新增的单号的产品信息
	 * @param array $params    新增的单号的非产品类信息
	 * @param string $exp      新增的单号说明信息（进货、入库等）
	 * @param string $remark   新增的单号的非产品类信息中的 备注（首先获取该备注数据，以保证获取$params的备注为正常值。）
	 * @param  每个产品的变动都要保存到产品变动明细表中
	 */

	public function add_stocklog($manlistData,$stocklogData){
		if($stocklogData['sltype']=='1' ||$stocklogData['sltype']=='2' ||$stocklogData['sltype']=='3'){		//变动状态，进货、入库、冲减

			foreach ($manlistData as $k => $v) {
				$logData[] = 
				[
					'slotid' => $v['lotid'],
					'slcost' => $v['mcost'],
					'slallcost' => $v['mallcost'],
					'slprice' => $v['mprice'],
					'slallprice' => $v['mallprice'],
					'slnum' => $v['mpro_num'],
					'slrest' => $stocklogData[$k]['slrest'],
					'sletc' => $stocklogData['sletc'],
					'slexplain' => $stocklogData['slexplain'],
					'slremark' => $stocklogData['slremark'],
					'sltype' => $stocklogData['sltype'],
					'smalltype' => $stocklogData['smalltype'],
					'sltime' => time(),
				];
			}
			$res = db('wm_stocklog')->insertAll($logData);
			if($res){
				return '1';		//保存stock_log成功
			}else{
				return '2';
			}

		}elseif($stocklogData['sltype']=='4' ||$stocklogData['sltype']=='5'){			//领药
			foreach ($manlistData as $k => $v) {
				$logData[] = 
				[
					'slotid' => $v['lotid'],
					'slcost' => $v['mcost'],
					'slallcost' => $v['mallcost'],
					'slprice' => $v['mprice'],
					'slallprice' => $v['mallprice'],
					'slnum' => $v['mpro_num'],
					'slrest' => $stocklogData[$k]['slrest'],
					'sldepartment' => $stocklogData['sldepartment'],
					'sletc' => $stocklogData['sletc'],
					'slexplain' => $stocklogData['slexplain'],
					'slremark' => $stocklogData['slremark'],
					'sltype' => $stocklogData['sltype'],
					'smalltype' => $stocklogData['smalltype'],
					'sltime' => time(),
				];
			}
			$res = db('wm_stocklog')->insertAll($logData);
			if($res){
				return '1';		//保存stock_log成功
			}else{
				return '2';
			}
		}elseif($stocklogData['sltype']=='6'){

			//
			foreach ($manlistData as $k => $v) {
				$logData[] = 
				[
					'slotid' => $v['lot_id'],
					'slcost' => $v['mcost'],
					'slprice' => $v['mprice'],
					'slallcost' => $v['mallcost'],
					'slallprice' => $v['mallprice'],
					'slrest' => $v['slrest'],
					'slnum' => $v['mpro_num'],
					'sltype' => $stocklogData['sltype'],
					'smalltype' => $stocklogData['sltype'],
					'sldr_id' => $stocklogData['dr_id'],
					'slcustomer_id' => $stocklogData['customer_id'],
					'slexplain' => $stocklogData['slexplain'],
					'sltime' => time(),
				];
			}
			
			foreach ($logData as $k => $v) {
				db('wm_stocklog')->insert($v);
				$slIdRes[$k]['sl_id'] = db('wm_stocklog')->getLastInsID();
			}
			// var_dump($slIdRes);die();
			foreach ($slIdRes as $key => $val) {
				$reData[] = [
					'rdr_id' => $stocklogData['dr_id'],
					'rsl_id' => $val['sl_id'],
				];
				
			}
			$reRes = db('wm_recipe')->insertAll($reData);
			$drRes = db('deduct_records')->where('id', $stocklogData['dr_id'])->update(['status' => $stocklogData['dr_status']]);
			if($reRes && $drRes){
				return '1';		//保存stock_log成功
			}else{
				return '2';
			}

		}elseif($stocklogData['sltype']=='7'){

			//
			foreach ($manlistData as $k => $v) {
				$logData[] = 
				[
					'slotid' => $v['lot_id'],
					'slcost' => $v['mcost'],
					'slprice' => $v['mprice'],
					'slallcost' => $v['mallcost'],
					'slallprice' => $v['mallprice'],
					'slrest' => $v['slrest'],
					'slnum' => $v['mpro_num'],
					'sltype' => $stocklogData['sltype'],
					'smalltype' => $stocklogData['sltype'],
					'sldr_id' => $stocklogData['dr_id'],
					'slcustomer_id' => $stocklogData['customer_id'],
					'slexplain' => $stocklogData['slexplain'],
					'sltime' => time(),
				];

				$reRes = db('wm_recipe')->where('re_id',$v['re_id'])->delete();
			}
			
			$slRes = db('wm_stocklog')->insertAll($logData);
			$drRes = db('deduct_records')->where('id', $stocklogData['dr_id'])->update(['status' => $stocklogData['dr_status']]);
			if($reRes && $drRes && $slRes){
				return '1';		//保存stock_log成功
			}else{
				return '2';
			}

		}

	}

	/*
	**科室领取的退回操作
	*/
	public function backStock($datas){			

		if($datas){
			$mlData = [];
            $slData = [];

			$mlData['lotid'] = $slData['slotid'] = $datas['lot_ids'];
			$mlData['manid'] = $datas['manid'];

			$mlData['mpro_num'] = $slData['slnum'] = -$datas['backnum'];
			$mlData['mcost'] = $slData['slcost'] = -$datas['mcost'];
			$mlData['mallcost'] = $slData['slallcost'] = -$datas['mcost']*$datas['backnum'];
			$mlData['mprice'] = $slData['slprice'] = -$datas['mprice'];
			$mlData['mallprice'] = $slData['slallprice'] = -$datas['mprice']*$datas['backnum'];

			$slData['sletc'] = $datas['man_num'];
			$slData['slrest'] = '';
			$mlData['mstime'] =$mlData['metime'] = $slData['sltime'] = time();
			$mlData['backtype'] = 2;

			if($datas['type'] == '4'){
				$slData['slexplain'] = '领药单'.$datas['man_num'].'--退药';
				$slData['sltype'] = '4';		//变动状态=主类型：4领药
				$slData['smalltype'] = '4';	//次类型

			}elseif ($datas['type'] == '5'){	//领料单退料
				$slData['slexplain'] = '领料单'.$datas['man_num'].'--退料';
				$slData['sltype'] = '5';		//变动状态=主类型：4领料
				$slData['smalltype'] = '5';	//次类型
			}
			
			$mlRes = db('wm_manlist')->insert($mlData);
			$oldmlRes = db('wm_manlist')->where('ml_id',$datas['ml_id'])->update(['backtype' => 1]);
			$slRes = db('wm_stocklog')->insert($slData);
			$changeProStock = db('project')->where('pro_id',$datas['pro_id'])->setInc('pro_stock',$datas['backnum']);
			$changeLotStock = db('wm_lotnum')->where('lot_id',$datas['lot_ids'])->setInc('lstock',$datas['backnum']);
			// return $mlRes;
			if($mlRes && $oldmlRes && $slRes && $changeProStock && $changeLotStock){
				return '1';		//保存stock_log成功
			}else{
				return '2';
			}


		}
		

	}

	/**
     * @param int $type   操作类型
     * 获取类型中文名称
     */
    function getType($type){
    	switch ($type) {
    		case '1':
    			return '进货';
    			break;

			case '2':
				return '入库';
				break;
			case '21':
				return '其他入库';
				break;
			case '22':
				return '调拨入库';
				break;
			case '23':
				return '盘盈入库';
				break;
			case '24':
				return '退货入库';
				break;

			case '3':
				return '冲减';
				break;
			case '31':
				return '入库冲减';
				break;
			case '32':
				return '盘亏冲减';
				break;
			case '33':
				return '过期冲减';
				break;
			case '34':
				return '其他冲减';
				break;

			case '4':
				return '科室领药';
				break;

			case '5':
				return '科室领料';
				break;

			case '6':
				return '出库';
				break;

			case '7':
				return '撤销';
				break;

			default:
    			# code...
    			break;
		}
    }


}