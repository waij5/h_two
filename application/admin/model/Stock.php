<?php

namespace app\admin\model;

use think\Model;

class Stock extends Model
{
	protected $name = 'stock_log';

	/**        
	 * 			进货、入库等新增单号产品
	 * @param array $drugRows  新增的单号的产品信息
	 * @param array $params    新增的单号的非产品类信息
	 * @param string $exp      新增的单号说明信息（进货、入库等）
	 * @param string $remark   新增的单号的非产品类信息中的 备注（首先获取该备注数据，以保证获取$params的备注为正常值。）
	 * @param  每个产品的变动都要保存到产品变动明细表中
	 */
    function stock_add_log($drugRows,$params,$exp,$remark,$type = '0'){
		if($drugRows){
			foreach ($drugRows as $key => $v) {
                //记录数据到产品库存变动明细表
                $log_data[] =
                  [
                   'l_cost' => $v['cost'],
                   'l_price' => $v['price'],
                   'l_num' => $v['storage_num'],
                   'l_rest' => $v['stock'],
                   'l_money' => $v['cost'] * $v['storage_num'],
                   'l_pid' => $v['drugs_id']
                  ];
            }
            foreach ($log_data as $ke => $va) {
                $log_data[$ke]['l_time'] = time();
                $log_data[$ke]['l_type'] = $type;
                $log_data[$ke]['l_etc'] = $params['order_num'];
                $log_data[$ke]['l_producer'] = $params['producer_id'];
                $log_data[$ke]['l_explain'] = $exp.$params['order_num'];
                $log_data[$ke]['l_remark'] = $remark;
                
            }
			db('stock_log')->insertAll($log_data);
		}
    }

    /**        
	 * 			领料新增单号产品
	 * @param array $drugRows  新增的单号的产品信息
	 * @param array $params    新增的单号的非产品类信息
	 * @param string $exp      新增的单号说明信息（科室领料）
	 * @param string $remark   新增的单号的非产品类信息中的 备注（首先获取该备注数据，以保证获取$params的备注为正常值。）
	 * @param  每个产品的变动都要保存到产品变动明细表中
	 */
    function stock_lladd_log($drugRows,$params,$exp,$remark,$type='5'){
		if($drugRows){
			foreach ($drugRows as $key => $v) {
                //记录数据到产品库存变动明细表
                $log_data[] =
                  [
                   'l_cost' => $v['cost'],
                   'l_price' => $v['price'],
                   'l_num' => $v['storage_num'],
                   'l_rest' => $v['stock'],
                   'l_money' => $v['cost'] * $v['storage_num'],
                   'l_pid' => $v['drugs_id']
                  ];
            }
            foreach ($log_data as $ke => $va) {
                $log_data[$ke]['l_time'] = time();
                $log_data[$ke]['l_type'] = $type;        //类型5是领料
                $log_data[$ke]['l_etc'] = $params['order_num'];
                $log_data[$ke]['l_producer'] = $params['producer_id'];
                $log_data[$ke]['l_department'] = $params['depart_id'];
                $log_data[$ke]['l_explain'] = $exp.$params['order_num'];
                $log_data[$ke]['l_remark'] = $remark;
                
            }
			db('stock_log')->insertAll($log_data);
		}
    }



    /**        
	 * 		进货、入库等删除单号产品;删除单号同时扣除对应产品的数量
	 * @param int $ids  删除的单号的id
	 * @param string $exp      删除的单号说明信息（删除进货、入库、冲减等单号信息）
	 * @param  每个产品的变动都要保存到产品变动明细表中
	 */
    function stock_del_log($ids,$exp,$type){
    	if($ids){
    		//根据单号id 查找单号下产品信息
			$delId = db('purchase_flow')
		                 ->alias('pf')
		                 ->field('pf.goods_id, pf.good_num,pf.cost, po.order_num')
		                 ->join('purchase_order po', 'pf.purchase_id = po.id')
		                 ->where('pf.purchase_id',$ids)
		                 ->select();
         	$delData = [];
		    foreach ($delId as $k => $v) {
		        //删除单号，扣除对应产品的数量
		        $promodel = model('Product')->get(['id' => $v['goods_id']]);
		        if ($promodel){
		            //获取产品当前库存数量
		            $stock = $promodel['stock'];
		            //当前库存数量 - 本次删除产品的数量     更新到产品表
		            model('Product')->where('id', $v['goods_id'])->update(['stock' => $stock - $v['good_num']]);
		        }

		        //保存到库存变动明细表数据

		        $delData[]=
		        	[
		        		'l_time'=> time(),
		        		'l_cost' => $v['cost'],
	                    'l_num' => $v['good_num'],
	                    'l_money' => $v['cost'] * $v['good_num'],
	                    'l_rest' => $stock - $v['good_num'],
	                    'l_pid' => $v['goods_id'],
	                    'l_type' => $type,
	                    'l_explain' => $exp.$v['order_num']
                    ];
		    }
		    db('stock_log')->insertAll($delData);
	    }
    }

    /**        
	 * 		冲减删除单号产品;删除单号同时增加对应产品的数量
	 * @param int $ids  删除的单号的id
	 * @param string $exp      删除的单号说明信息（删除冲减等单号信息）
	 * @param  每个产品的变动都要保存到产品变动明细表中
	 */
    function stock_cjdel_log($ids,$exp,$type){
    	if($ids){
    		//根据单号id 查找单号下产品信息
			$delId = db('purchase_flow')
		                 ->alias('pf')
		                 ->field('pf.goods_id, pf.good_num, pf.cost, po.order_num')
		                 ->join('purchase_order po', 'pf.purchase_id = po.id')
		                 ->where('pf.purchase_id',$ids)
		                 ->select();
         	$delData = [];
		    foreach ($delId as $k => $v) {
		        //删除单号，扣除对应产品的数量
		        $promodel = model('Product')->get(['id' => $v['goods_id']]);
		        if ($promodel){
		            //获取产品当前库存数量
		            $stock = $promodel['stock'];
		            //当前库存数量 - 本次删除产品的数量     更新到产品表
		            model('Product')->where('id', $v['goods_id'])->update(['stock' => $stock + $v['good_num']]);
		        }

		        //保存到库存变动明细表数据

		        $delData[]=
		        	[
		        		'l_time'=> time(),
	                    'l_num' => $v['good_num'],
	                    'l_rest' => $stock + $v['good_num'],
	                    'l_pid' => $v['goods_id'],
	                    'l_cost' => $v['cost'],
	                    'l_money' => $v['cost'] * $v['good_num'],
	                    'l_type' => $type,
	                    'l_explain' => $exp.$v['order_num']
                    ];
		    }
		    db('stock_log')->insertAll($delData);
	    }
    }


    /**        
	 * 		科室领料删除单号产品;删除单号同时增加对应产品的数量
	 * @param int $ids  删除的单号的id
	 * @param string $exp      删除的单号说明信息（删除领料单号信息）
	 * @param  每个产品的变动都要保存到产品变动明细表中
	 */
    function stock_lldel_log($ids,$exp,$type){
    	if($ids){
    		//根据单号id 查找单号下产品信息
			$delId = db('depart_product')
		                 ->alias('dp')
		                 ->field('dp.goods_id, dp.goods_num, dp.price, dp.cost, do.order_num')
		                 ->join('depot_outks do', 'dp.depotout_id = do.id')
		                 ->where('dp.depotout_id',$ids)
		                 ->select();
         	$delData = [];
		    foreach ($delId as $k => $v) {
		        //删除单号，扣除对应产品的数量
		        $promodel = model('Product')->get(['id' => $v['goods_id']]);
		        if ($promodel){
		            //获取产品当前库存数量
		            $stock = $promodel['stock'];
		            //当前库存数量 + 本次删除产品的数量     更新到产品表
		            model('Product')->where('id', $v['goods_id'])->update(['stock' => $stock + $v['goods_num']]);
		        }

		        //保存到库存变动明细表数据

		        $delData[]=
		        	[
		        		'l_time'=> time(),
	                    'l_num' => $v['goods_num'],
	                    'l_rest' => $stock + $v['goods_num'],
	                    'l_pid' => $v['goods_id'],
	                    'l_price' => $v['price'],
	                    'l_cost' => $v['cost'],
	                    'l_money' => $v['cost'] * $v['goods_num'],
	                    'l_type' => $type,
	                    'l_explain' => $exp.$v['order_num']
                    ];
		    }
		    db('stock_log')->insertAll($delData);
	    }
    }

	/**        
	 * 		药房处方单 发药 及 撤销发药
	 * @param  划扣记录id; 产品ids; 划扣记录状态status; 划扣产品个数qty; 产品成本单价money; 处方单编号order_id;变动明细类型type;说明信息exp
	 * @param 
	 * @param  每个产品的变动都要保存到产品变动明细表中
	 */
    function stock_drugks_log($id,$ids,$status,$qty,$money,$price,$order_id,$type,$exp){
    	if($ids){
    		
    		$promodel = model('Product')->get(['id' => $ids]);
	    	if($promodel){
	    		//  产品当前库存数量 - 发药数量
	    		$stock_num = $promodel['stock'] +  $qty;

	    		if($stock_num >= 0){

		    		model('Product')->where('id', $ids)->update(['stock' => $stock_num]);
		    		model('DeductRecords')->where('id', $id)->update(['status' => $status]);
		    		//保存到库存变动明细表数据

			        $dData[]=
			        	[
			        		'l_time'=> time(),
			                'l_num' => abs($qty),		#abs绝对值 负数转正数
			                'l_rest' => $stock_num,
			                'l_pid' => $ids,
			                'l_etc' => $order_id,
			                'l_type' => $type,
			                'l_price' => $price,
			                'l_cost' => $money,
			                'l_money' => abs($qty) * $money,
			                'l_explain' => $exp.$order_id
			            ];
		            $res = db('stock_log')->insertAll($dData);
		            if($res){
		            	return '1';
		            }else{
		            	return '2';
		            }
	            }else{
	    			return '3';
	    		}
	    	}
	    	
    	}
    	
    	

    }

    /**
     * @param int $type   操作类型
     * 获取类型中文名称
     */
    function getType($type){
    	switch ($type) {
    		case '0':
    			return '进货';
    			break;
			case '1':
				return '入库';
				break;



			case '11':
                return '调拨入库';
                break;
            case '12':
                return '盘盈入库';
                break;
            case '13':
                return '退货入库';
                break;
            case '14':
                return '报增入库';	//暂时停用
                break;
            case '15':
                return '其他入库';
                break;
            case '1111':
                return '删除调拨入库';
                break;
            case '1211':
                return '删除盘盈入库';
                break;
            case '1311':
                return '删除退货入库';
                break;
            case '1411':
                return '删除报增入库';	//暂时停用
                break;
            case '1511':
                return '删除其他入库';
                break;

            case '21':
                return '入库冲减';
                break;
            case '22':
                return '报损冲减';	//暂时停用
                break;
            case '23':
                return '盘亏冲减';
                break;
            case '24':
                return '过期冲减';
                break;
            case '25':
                return '其他冲减';
                break;
            case '2111':
                return '删除入库冲减';
                break;
            case '2211':
                return '删除报损冲减';	//暂时停用
                break;
            case '2311':
                return '删除盘亏冲减';
                break;
            case '2411':
                return '删除过期冲减';
                break;
            case '2511':
                return '删除其他冲减';
                break;



			case '2':
				return '冲减';
				break;

			case '3':
				return '退货';
				break;

			case '4':
				return '调拨';
				break;

			case '5':
				return '领料';
				break;

			case '6':
				return '药房发药';
				break;

			case '7':
				return '药房撤销发药';
				break;

			case '8':
				return '领药';
				break;

			case '10':
				return '删除进货';
				break;

			case '111':
				return '删除入库';
				break;

			case '211':
				return '删除冲减';
				break;

			case '511':
				return '删除领料';
				break;

			case '811':
				return '删除领药';
				break;

			
    		
    		default:
    			# code...
    			break;
    	}
    }



/**
* @param int $type   操作类型
* 添加调拨记录
*/
    // function allot_log(){
    	
    // }
    
    
}
