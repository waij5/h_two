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
class Cmcj extends Backend
{
    
    public function _initialize()
    {
    	parent::_initialize();
    	$this->model = model('Manifest');

    	$supplierList = db('wm_supplier')->where('sup_type' ,'<>','1')->where('sup_status','1')->select();
    	$this->view->assign('supplierList', $supplierList);

    	$depotList = db('depot')->where(['type'=>'4', 'status'=>'normal'])->select();
    	$this->view->assign('depotList', $depotList);

    	$this->view->assign('cjType',$this->model->getCjType());
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
                $mapTotal['mcreatetime'] = array("between",array($startr,$endr));
            }else{
                $map= [];
                $mapTotal= [];
            }
            
			list($where, $sort, $order, $offset, $limit) = $this->buildparams();
			$tDefaultWhere = ['mprimary_type'=> '3', 'mbelong_type'=> '4'];		//主类型3：冲减；所属类型4：耗材
			$lDefaultWhere = ['mf.mprimary_type'=> '3', 'mf.mbelong_type'=> '4'];
            $total = $this->model
                    ->where($tDefaultWhere)
					->where($mapTotal)->where($where)
                    ->count();

			$lists = $this->model->alias('mf')
					->field('mf.*, sup.sup_name, dpt.name as dpt_name')
					->join('yjy_wm_supplier sup', 'mf.msupplier_id = sup.sup_id', 'LEFT')
					->join('yjy_depot dpt', 'mf.mdepot_id = dpt.id', 'LEFT')
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
			$man_num = 'hccj'.$nums;
			$this->view->assign("man_num", $man_num);
        }else{
			$this->view->assign("man_num", 'hccj1');
        }

        if($this->request->isPost()){
        	$params = $this->request->post("row/a");			//货单表数据
        	$params['mcreatetime'] = time();
        	if($params){
        		// var_dump($params);die();
        		$man_nums = $params['man_num'];
        		$searchMan_num = $this->model->where('man_num',$man_nums)->find();
        		if($searchMan_num){
        			$this->error('本次冲减单号已存在，请重新获取！');
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
					$stocklogData['slexplain'] = '耗材冲减单'.$params['man_num'];
					$stocklogData['slremark'] = $params['mremark'];
					$stocklogData['sltype'] = '3';		//变动状态=主类型：3冲减
					$stocklogData['smalltype'] = $params['msecond_type'];	//次类型

					if(!$lot_id){		//产品必选
						$this->error('请选择耗材！');
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
							$this->error('冲减数量必填！');
						}
						$intNum = intval($v);
						$floatNum = floatval($v);
						if($floatNum <1 || $intNum != $floatNum){
							$this->error('请输入正确的冲减数量！');
						}
						if(intval($v)>$overStock[$k]['lstock']){
							$this->error('冲减数量不能大于当前库存数！');
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
    	$row = $this->model->find($ids);
		if (!$row){
			$this->error(__('No Results were found'));
		}
    	$list = db('wm_manlist')->alias('ml')
    			->field('ml.*,lotnum.lstock,lotnum.lotnum,lotnum.lproducer,lotnum.laddr,lotnum.lapprov_num,lotnum.lregist_num,pro.pro_name,pro.pro_unit,pro.pro_spec,u.name as uname')
    			->join('yjy_wm_lotnum lotnum', 'ml.lotid = lotnum.lot_id', 'LEFT')
    			->join('yjy_project pro', 'lotnum.lpro_id = pro.pro_id', 'LEFT')
    			->join('yjy_unit u', 'pro.pro_unit = u.id', 'LEFT')
    			->where('manid', $ids)
    			->select();
    			// var_dump($list);die();
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

    public function proSearch(){
    	$lists = [];
        if($this->request->isAjax()){
            $keywords = $this->request->post('keywords');
            $depot = $this->request->post('depot');
            $where['p.depot_id'] = $depot;
            
            if(preg_match('/[\x{4e00}-\x{9fa5}]/u', $keywords)>0){// 搜索中文关键词
				$where['p.pro_name'] = ['like', '%'.$keywords.'%'];

			}/*elseif(preg_match('/^[1-9][0-9]*$/',$keywords)){// 搜索产品id关键词
				$where['p.pro_id'] = $keywords;
			}*/else{												// 搜索产品拼音码关键词
				$where['p.pro_spell'] = ['like', '%'.$keywords.'%'];
			}
			
            $where['p.pro_status'] = '1';
            $where['p.pro_type'] = '4';
            if($keywords && $depot){
                $list = db('wm_lotnum')->alias('lotnum')
                		->field('lotnum.lot_id,lotnum.lotnum,lotnum.lstock,lotnum.lcost,lotnum.lprice,lotnum.lstime,lotnum.letime,lotnum.lproducer,lotnum.laddr,lotnum.lapprov_num,lotnum.lregist_num,p.pro_code,p.pro_spell,p.pro_name,p.pro_unit,p.pro_spec,p.pro_id,u.name as uname')
                		->join('yjy_project p', 'lotnum.lpro_id = p.pro_id', 'LEFT')
                		->join('yjy_unit u','p.pro_unit = u.id', 'LEFT')
                		->where('lotnum.lstock','>','0')
                		->where($where)
                		->order('p.pro_id asc, lotnum.letime asc')
                		->select();
        		foreach ($list as $key => $value) {
        			$list[$key]['letime'] = $value['letime']>0?date('Y-m-d',$value['letime']):'';
        			$list[$key]['lstime'] = $value['lstime']>0?date('Y-m-d',$value['lstime']):'';
        		}
        		// $lists =[];
        		foreach ($list as $key => $value) {
        			$lists[$key+1]= $value;
        		}
                // var_dump($list);

            }
        }

        return json($lists);
    }



    public function getNum(){
        if($this->request->isAjax()){
            $num = $this->request->post('num');
            if($num){
                $nums=$this->model->field('max(man_id) as id')->find();
                $numss = $nums['id']+1;
                $wpcj_num = 'hccj'.$numss;
                // var_dump($yp_num);
                return json($wpcj_num);
            }

        }
    }





}