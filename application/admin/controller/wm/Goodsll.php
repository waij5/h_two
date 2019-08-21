<?php

namespace app\admin\controller\wm;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use think\DB;

/**
 * 进货单
 *
 * @icon fa fa-circle-o
 */
class Goodsll extends Backend
{
    
    public function _initialize()
    {
    	parent::_initialize();
    	$this->model = model('Manifest');

    	$supplierList = db('wm_supplier')->where('sup_type' ,'<>','1')->where('sup_status','1')->select();
    	$this->view->assign('supplierList', $supplierList);

    	$depotList = db('depot')->where(['type'=>'2', 'status'=>'normal'])->select();
    	$this->view->assign('depotList', $depotList);

    	$deptList = db('deptment')->where(['dept_status'=>'1'])->order('dept_id', 'asc')->select();
    	$this->view->assign('deptList', $deptList);

    	$deptEditList = db('deptment')->where(['dept_status'=>'1'])->order('dept_id', 'asc')->select();
    	$this->view->assign('deptEditList', $deptEditList);

    	$userList = model('Admin')->order('username', 'asc')->select();
    	$this->view->assign('userList', $userList);
    }

    public function index(){
    	if ($this->request->isAjax())
        {	
			//如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name'))
            {
                return $this->selectpage();
            }
			$search = $this->request->request("search");

            $filter = $this->request->get("filter", '');
            $filter = json_decode($filter, TRUE);
            if (!empty($filter['stime'])) {
                $startr= strtotime($filter['stime'].'00:00:00');       //今天的0点
                $endr= strtotime($filter['etime'].'23:59:59');   //  今天的24点
                $map['mf.mcreatetime'] = array("between",array($startr,$endr));
                $mapTotal['m.mcreatetime'] = array("between",array($startr,$endr));
            }else{
                $map= [];
                $mapTotal= [];
            }
            if(!empty($filter['member_name'])){
                $map['c.ctm_name'] = ['like', '%'.$filter['member_name'].'%'];
                $mapTotal['c.ctm_name'] = ['like', '%'.$filter['member_name'].'%'];
            }
            
			list($where, $sort, $order, $offset, $limit) = $this->buildparams();
			$tDefaultWhere = ['m.mprimary_type'=> '5', 'm.mbelong_type'=> '2'];		//主类型5：领料；所属类型2：物品
			$lDefaultWhere = ['mf.mprimary_type'=> '5', 'mf.mbelong_type'=> '2'];
            $total = $this->model->alias('m')->join('yjy_customer c', 'm.member_id = c.ctm_id', 'LEFT')
                    ->where($tDefaultWhere)
					->where($mapTotal)->where($where)
                    ->count();

			$lists = $this->model->alias('mf')
					->field('mf.*, dpt.name as dpt_name, a.nickname, dep.dept_name, c.ctm_name')
					->join('yjy_depot dpt', 'mf.mdepot_id = dpt.id', 'LEFT')
					->join('yjy_admin a', 'mf.mout_id = a.id', 'LEFT')
					->join('yjy_deptment dep', 'mf.mdepart_id = dep.dept_id', 'LEFT')
					->join('yjy_customer c','mf.member_id = c.ctm_id','LEFT')
					->where($lDefaultWhere)
					->where($map)->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
					->select();
			

            $result = array("total" => $total, "rows" => $lists);

            return json($result);
        }
    	return $this->view->fetch();
    }


    public function add(){
    	if($this->model->select()){
            $num=$this->model->field('max(man_id) as id')->find();
			$nums = $num['id']+1;
			$man_num = 'll'.$nums;
			$this->view->assign("man_num", $man_num);
        }else{
			$this->view->assign("man_num", 'll1');
        }

        if($this->request->isPost()){
        	$params = $this->request->post("row/a");			//货单表数据
        	$params['mcreatetime'] = time();
        	if($params){
        		// var_dump($params);die();
        		$man_nums = $params['man_num'];
        		$searchMan_num = $this->model->where('man_num',$man_nums)->find();
        		if($searchMan_num){
        			$this->error('本次领料单号已存在，请重新获取！');
        		}else{
        			$lot_id = $this->request->post("lot_id/a");
					$mpro_num = $this->request->post('mpro_num/a');
					$mcost = $this->request->post('mcost/a');
					$mallcost = $this->request->post('mallcost/a');
					$mprice = $this->request->post('mprice/a');
					$mallprice = $this->request->post('mallprice/a');
					$mstime = $this->request->post("mstime/a");
					$metime = $this->request->post("metime/a");


					$manlistData = array();		//货单明细表数据
					$lotnumData = array();		//产品批号表数据
					$stocklogData = array();		//产品变动明细表数据
					$overStock = [];			//当前库存
					// $proData = array();		// project产品同步数据，如库存数
					$stocklogData['sletc'] = $params['man_num'];
					$stocklogData['slexplain'] = '领料单'.$params['man_num'];
					$stocklogData['slremark'] = $params['mremark'];
					$stocklogData['sldepartment'] = $params['mdepart_id'];
					$stocklogData['sltype'] = '5';		//变动状态=主类型：5领料
					$stocklogData['smalltype'] = '5';	//次类型

					if(!$lot_id){		//产品必选
						$this->error('请选择物品！');
					}
					foreach ($lot_id as $k => $v) {
						$lotnumData[$k]['lot_id'] = $v;
						$manlistData[$k]['lotid'] = $v;
						$allstock[$k] = db('wm_lotnum')->alias('l')			//查询批号的当前库存和关联的产品的当前总库存
											->field('l.lstock,p.pro_stock,p.pro_id')
											->join('yjy_project p', 'l.lpro_id = p.pro_id')
											->where('l.lot_id', $v)
											->select();
					}
					foreach ($allstock as $key => $value) {
						foreach ($value as $ke => $va) {
							$overStock[$key]['lstock'] = $va['lstock'];
							$overStock[$key]['pro_stock'] = $va['pro_stock'];
							$overStock[$key]['pro_id'] = $va['pro_id'];
						}
						
					}
					// var_dump($overStock);die();
					
					
					
					foreach ($mpro_num as $k => $v) {
						if($v ==''){
							$this->error('领料数量必填！');
						}
						$intNum = intval($v);
						$floatNum = floatval($v);
						if($floatNum <1 || $intNum != $floatNum){
							$this->error('请输入正确的领料数量！');
						}
						if(intval($v)>$overStock[$k]['lstock']){
							$this->error('领料数量不能大于当前库存数！');
						}
						$manlistData[$k]['mpro_num'] = $v;
						$lotnumData[$k]['mpro_num'] = $v;
						$overStock[$k]['mpro_num'] = $v;
					}
					
					foreach($mcost as $k => $v){
						$floatNum = floatval($v);
						if($v ==''){
							$this->error('进价必填！');
						}
						if($floatNum <0){
							$this->error('请输入正确的进价！');
						}
						$manlistData[$k]['mcost'] = $v;
					}
					foreach($mallcost as $k => $v){
						$manlistData[$k]['mallcost'] = $v;
					}

					
					foreach($mprice as $k => $v){
						$floatNum = floatval($v);
						if($v ==''){
							$this->error('售价必填！');
						}
						if($floatNum <0){
							$this->error('请输入正确的售价！');
						}
						$manlistData[$k]['mprice'] = $v;
					}
					foreach($mallprice as $k => $v){
						$manlistData[$k]['mallprice'] = $v;
					}

					foreach ($mstime as $k => &$v){
						$manlistData[$k]['mstime'] = strtotime($v);
					}
					foreach ($metime as $k => &$v){
						$manlistData[$k]['metime'] = strtotime($v);
					}
					


					if($manlistData && $lotnumData && $stocklogData){

						\think\Db::startTrans();				//开启db回滚;
						$res = ['error' => false, 'msg' => ''];		//设置回滚状态及信息

						$manifestRes = $this->model->save($params);
						if($manifestRes == false){

							$res = ['error' => true, 'msg' => '保存失败！manifestRes'];
							break;

						}elseif($manifestRes !== false){
							$man_id = $this->model->man_id;			//取得货单表id
							if(!$man_id){
								$res = ['error' => true, 'msg' => '保存失败！man_id'];
								break;
							}else{
								foreach ($manlistData as $k => $v) {
									$manlistData[$k]['manid'] = $man_id;		//货单id
								}

								if($overStock){
									// $newOverStock = $this->model->judgeRepeatArray($overStock);
									$newOverStock = [];
									$proStockArr = [];
									foreach ($overStock as $k => $v) {
										$newOverStock[$v['pro_id']][] = $v['mpro_num'];
										$proStockArr[$v['pro_id']]['changeStock'] = '';
										$proStockArr[$v['pro_id']]['pro_stock'] = $v['pro_stock'];

										$stocklogData[$k]['slrest'] = $v['lstock']-$lotnumData[$k]['mpro_num'];

										$changLotnumStock = db('wm_lotnum')->where('lot_id',$lotnumData[$k]['lot_id'])->update(['lstock' => $v['lstock']-$lotnumData[$k]['mpro_num']]);
										if($changLotnumStock==''){
										// var_dump($changLotnumStock.'---');die();
											$res = ['error' => true, 'msg' => '保存失败！changLotnumStock'];
											break;
										}
									}
									
									foreach ($newOverStock as $key => $value) {
										foreach ($value as $k => $v) {
											$proStockArr[$key]['changeStock'] += $v;
										}
									}

									foreach ($proStockArr as $key => $v) {

										$changProStock = db('project')->where('pro_id',$key)->update(['pro_stock' => $v['pro_stock']-$v['changeStock']]);
										if($changProStock==''){
											// var_dump($changLotnumStock.'---');die();
											$res = ['error' => true, 'msg' => '保存失败！changProStock'];
											break;
										}
									}
									// var_dump($changProStock);die();
								}
								/*foreach ($overStock as $k => $v) {
									$changLotnumStock = db('wm_lotnum')->where('lot_id',$lotnumData[$k]['lot_id'])->update(['lstock' => $v['lstock']-$lotnumData[$k]['mpro_num']]);
									$changProStock = db('project')->where('pro_id',$v['pro_id'])->update(['pro_stock' => $v['pro_stock']-$manlistData[$k]['mpro_num']]);
									if(!$changLotnumStock || !$changProStock){
										$res = ['error' => true, 'msg' => '保存失败！overStock'];
										break;
									}
								}*/

								$manlistRes = db('wm_manlist')->insertAll($manlistData);
								if($manlistRes){
									$stockLogRes = model('StockLog')->add_stocklog($manlistData,$stocklogData);
									if($stockLogRes == '2'){
										$res = ['error' => true, 'msg' => '保存失败！stockLogRes'];
										break;
									}
								}else{
									$res = ['error' => true, 'msg' => '保存失败！manlistRes'];
										break;
								}

							}
							
						}

						if($res['error'] == false){
							\think\Db::commit();
							$this->success();
						}else{
							\think\Db::rollback();
							$this->error($res['msg']);
						}
					}
        		}
        	}

        }


    	return $this->view->fetch();
    }


    public function edits($ids = NULL){
    	$row = db('wm_manifest')->alias('m')->field('m.*,c.ctm_name,c.ctm_id')->join('yjy_customer c','m.member_id = c.ctm_id','LEFT')
    	->where('m.man_id',$ids)->find();
		if (!$row){
			$this->error(__('No Results were found'));
		}
    	// var_dump($row);die();
    	$list = db('wm_manlist')->alias('ml')
    			->field('ml.*,lotnum.lot_id,lotnum.lstock,lotnum.lotnum,lotnum.lproducer,lotnum.laddr,lotnum.lapprov_num,lotnum.lregist_num,pro.pro_name,pro.pro_unit,pro.pro_spec,u.name as uname')
    			->join('yjy_wm_lotnum lotnum', 'ml.lotid = lotnum.lot_id', 'LEFT')
    			->join('yjy_project pro', 'lotnum.lpro_id = pro.pro_id', 'LEFT')
    			->join('yjy_unit u', 'pro.pro_unit = u.id', 'LEFT')
    			->where('manid', $ids)
    			->select();
    			// var_dump($row);die();
		$totalCost = '';
		foreach ($list as $key => $value) {
			$totalCost += $value['mallcost'];
		}
		$this->view->assign('row',$row);
		$this->view->assign('totalCost',$totalCost);

		if($list){
			$this->view->assign('list',$list);
		}
		
		return $this->view->fetch();
    }

    
    public function editRemark(){
    	if($this->request->isAjax()){
            $manid = $this->request->post('manid');
            $mremark = $this->request->post('mremark');
            $msupplier_id = $this->request->post('msupplier_id');
            $mout_id = $this->request->post('mout_id');
            $mdepart_id = $this->request->post('mdepart_id');
            $member_id = $this->request->post('member_id');
            $manNum = db('wm_manifest')
            			->field('man_num')
            			->where('man_id',$manid)
            			->find();
            // return json($manNum['man_num']);
            if($manNum){
                $editManRemarkRes=db('wm_manifest')->where('man_id',$manid)->update(['mremark'=>$mremark,'msupplier_id'=>$msupplier_id,'mout_id'=>$mout_id,'mdepart_id'=>$mdepart_id,'member_id'=>$member_id]);
                
                $editLogRemarkRes[]=db('wm_stocklog')->where(['sletc'=>$manNum['man_num']])->update(['slremark'=>$mremark,'sldepartment'=>$mdepart_id,'slcustomer_id'=>$member_id,]);

                if($editManRemarkRes && $editLogRemarkRes){
                	return json(1);
                }else{
                	return json(2);
                }
            }else{
            	return json(3);
            }

        }
    }


    public function getNum(){
        if($this->request->isAjax()){
            $num = $this->request->post('num');
            if($num){
                $nums=$this->model->field('max(man_id) as id')->find();
                $numss = $nums['id']+1;
                $ll_num = 'll'.$numss;
                // var_dump($yp_num);
                return json($ll_num);
            }

        }
    }



    public function backGoods(){
        if($this->request->isAjax()){
        	$datas = [];
            $ml_id = $this->request->post('ml_id');
            $backnum= $this->request->post('backnum');
            if($ml_id && $backnum){
            	$datas = db('wm_manlist')->alias('ml')
	                     ->field('ml.manid,ml.lotid as lot_ids,ml.mpro_num,ml.mcost,ml.mprice,ml.backtype,mf.man_num,lot.lpro_id as pro_id')
	                     ->join('wm_manifest mf', 'ml.manid = mf.man_id', 'LEFT')
	                     ->join('wm_lotnum lot', 'ml.lotid = lot.lot_id', 'LEFT')
	                     ->where('ml.ml_id','=',$ml_id)
	                     ->find();
	            
	            // $datas['type'] = 4;
	            // return json($datas);
	            $issetNum = $datas['mpro_num']-$backnum;
	           
	            if($datas['lot_ids'] && $issetNum>=0 && $datas['backtype']==''){

	            	$datas['type'] = 5;
	            	$datas['ml_id'] = $ml_id;
	            	$datas['backnum'] = $backnum;
	            	// return json($datas);
	                \think\Db::startTrans();				//开启db回滚;datas['']
					$res = ['error' => false, 'msg' => ''];
					
					$backNumRes = model('StockLog')->backStock($datas);
					// return json($backNumRes);

					
					if($backNumRes == '1'){
						$res = ['error' => false, 'msg' => '1'];
						// break;
					}elseif($backNumRes == '2'){
						$res = ['error' => true, 'msg' => '2'];
						// break;
					}

					if($res['error'] == false){
						\think\Db::commit();
						return json($res['msg']);
					}else{
						\think\Db::rollback();
						return json($res['msg']);
					}
	            }else{
	            	return json(3);
	            }
        	}else{
        		return json(3);
        	}
            

        }
    }

}