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
class Cmjh extends Backend
{
    
    public function _initialize()
    {
    	parent::_initialize();
    	$this->model = model('Manifest');

    	$supplierList = db('wm_supplier')->where('sup_type' ,'<>','1')->where('sup_status','1')->select();
    	$this->view->assign('supplierList', $supplierList);

    	$depotList = db('depot')->where(['type'=>'4', 'status'=>'normal'])->select();
    	$this->view->assign('depotList', $depotList);
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
			$tDefaultWhere = ['mprimary_type'=> '1', 'mbelong_type'=> '4'];		//主类型1：进货；所属类型4：耗材
			$lDefaultWhere = ['mf.mprimary_type'=> '1', 'mf.mbelong_type'=> '4'];
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
			$man_num = 'hcjh'.$nums;
			$this->view->assign("man_num", $man_num);
        }else{
			$this->view->assign("man_num", 'hcjh1');
        }

        if($this->request->isPost()){
        	$params = $this->request->post("row/a");			//货单表数据
        	$params['mcreatetime'] = time();
        	if($params){
        		// var_dump($params);die();
        		$man_nums = $params['man_num'];
        		$searchMan_num = $this->model->where('man_num',$man_nums)->find();
        		if($searchMan_num){
        			$this->error('本次进货单号已存在，请重新获取！');
        		}else{
        			$pro_id = $this->request->post("lpro_id/a");
					$lotnum = $this->request->post('lotnum/a');
					$mpro_num = $this->request->post('mpro_num/a');
					$mcost = $this->request->post('mcost/a');
					$mallcost = $this->request->post('mallcost/a');
					$mprice = $this->request->post('mprice/a');
					$mallprice = $this->request->post('mallprice/a');

					$lstime = $this->request->post("lstime/a");
					$letime = $this->request->post("letime/a");
					$lproducer = $this->request->post("lproducer/a");
					$laddr = $this->request->post("laddr/a");
					$lapprov_num = $this->request->post("lapprov_num/a");
					$lregist_num = $this->request->post("lregist_num/a");


					$manlistData = array();		//货单明细表数据
					$lotnumData = array();		//产品批号表数据
					$stocklogData = array();		//产品变动明细表数据
					$proData = array();		// project产品同步数据，如进货单批号产品的售价同步到产品词典售价
					$stocklogData['sletc'] = $params['man_num'];
					$stocklogData['slexplain'] = '耗材进货单'.$params['man_num'];
					$stocklogData['slremark'] = $params['mremark'];
					$stocklogData['sltype'] = '1';		//变动状态
					$stocklogData['smalltype'] = '1';	//次类型

					if(!$pro_id){		//产品必选
						$this->error('请选择耗材！');
					}
					foreach ($pro_id as $k => $v) {
						$lotnumData[$k]['lpro_id'] = $v;
						$proData[$k]['pro_id'] = $v;
					}
					
					foreach ($lotnum as $k => $v) {
						// if($v ==''){
						// 	$this->error('批号必填！');
						// }
						$lotnumData[$k]['lotnum'] = $v;
					}
					
					
					foreach ($mpro_num as $k => $v) {
						if($v ==''){
							$this->error('进货数量必填！');
						}
						$intNum = intval($v);
						$floatNum = floatval($v);
						if($floatNum <1 || $intNum != $floatNum){
							$this->error('请输入正确的进货数量！');
						}
						$manlistData[$k]['mpro_num'] = $v;
						$lotnumData[$k]['lstock'] = $v;
						$proData[$k]['pro_stock'] = $v;
					}
					
					foreach($mcost as $k => $v){
						$floatmcost = floatval($v);
						if($v ==''){
							$this->error('进价必填！');
						}
						if (preg_match('/^[0-9]+(\.[0-9]{1,4})?$/', $floatmcost) ==0) {
							$this->error('请输入正确的进价！');
						}
						$manlistData[$k]['mcost'] = $v;
						$lotnumData[$k]['lcost'] = $v;
						$proData[$k]['pro_cost'] = $v;
					}
					foreach($mallcost as $k => $v){
						$manlistData[$k]['mallcost'] = $v;
					}

					
					foreach($mprice as $k => $v){
						$floatmprice = floatval($v);
						if($v ==''){
							$this->error('售价必填！');
						}
						if (preg_match('/^[0-9]+(\.[0-9]{1,2})?$/', $floatmprice) ==0) {
							$this->error('请输入正确的售价！');
						}
						$manlistData[$k]['mprice'] = $v;
						$lotnumData[$k]['lprice'] = $v;
						$proData[$k]['pro_amount'] = $v;
					}
					foreach($mallprice as $k => $v){
						$manlistData[$k]['mallprice'] = $v;
					}

					foreach ($lstime as $k => &$v){
						$manlistData[$k]['mstime'] = strtotime($v);
						$lotnumData[$k]['lstime'] = strtotime($v);
					}
					foreach ($letime as $k => &$v){
						$manlistData[$k]['metime'] = strtotime($v);
						$lotnumData[$k]['letime'] = strtotime($v);
					}
					foreach ($lproducer as $k => &$v){
						$lotnumData[$k]['lproducer'] = $v;
					}
					foreach ($laddr as $k => &$v){
						$lotnumData[$k]['laddr'] = $v;
					}
					foreach ($lapprov_num as $k => &$v){
						$lotnumData[$k]['lapprov_num'] = $v;
					}
					foreach ($lregist_num as $k => &$v){
						$lotnumData[$k]['lregist_num'] = $v;
						$lotnumData[$k]['lsupplier_id'] = $params['msupplier_id'];
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

								$lotnumRes = [];
								foreach ($lotnumData as $k => $v) {
									db('wm_lotnum')->insert($v);			//返回保存的产品批号的id
									$lotnumRes[$k]['lotid'] = db('wm_lotnum')->getLastInsID();
								}
								
								if(!$lotnumRes){
									$res = ['error' => true, 'msg' => '保存失败！lotnumRes'];
									break;
								}else{
									foreach ($proData as $k => $v) {
										$pro_stock = db('project')->where('pro_id',$v['pro_id'])->find();
										if($pro_stock){
											$stock = $pro_stock['pro_stock'];
											
											$stocklogData[$k]['slrest'] =  $stock+$v['pro_stock'];		//stocklog的库存结余
											$proRes = db('project')->where('pro_id',$v['pro_id'])->update(['pro_stock' => $stock+$v['pro_stock'],'pro_cost' => $v['pro_cost'],'pro_amount' => $v['pro_amount'],]);
											if(!$proRes){
												$res = ['error' => true, 'msg' => '保存失败！proRes'];
												break;
											}
										}else{
											$res = ['error' => true, 'msg' => '保存失败！pro_stock'];
											break;
										}
									}
									
									foreach ($lotnumRes as $k => $v) {
										if($v ==''){
											$res = ['error' => true, 'msg' => '保存失败！lotnumRes->v'];
											break;
										}else{
											$manlistData[$k]['lotid'] = $v['lotid'];			//产品批号id
										}
									}
									// dump($lotnumData);die();
									$manlistRes = db('wm_manlist')->insertAll($manlistData);	//保存数据到货单明细表
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
    			->field('ml.*,lotnum.lotnum,lotnum.lproducer,lotnum.laddr,lotnum.lapprov_num,lotnum.lregist_num,pro.pro_name,pro.pro_unit,pro.pro_spec,u.name as uname')
    			->join('yjy_wm_lotnum lotnum', 'ml.lotid = lotnum.lot_id', 'LEFT')
    			->join('yjy_project pro', 'lotnum.lpro_id = pro.pro_id', 'LEFT')
    			->join('yjy_unit u', 'pro.pro_unit = u.id', 'LEFT')
    			->where('manid', $ids)
    			->select();
    			// var_dump($list);die();
		$totalCost = '';
		foreach ($list as $key => $value) {
			$totalCost += $value['mallcost'];
			$CjRecord[$value['lotid']] = db('wm_manlist')->alias('ml')
								->field('mf.man_num')
								->join('yjy_wm_manifest mf', 'ml.manid = mf.man_id', 'LEFT')
								->where(['ml.lotid'=>$value['lotid'],'mf.mprimary_type'=>3])
								->find();
								// ->column('mf.man_num','ml.lotid');
		}
		$cjData =0;
		foreach($list as $k => $v){
			if(empty($CjRecord[$v['lotid']])){
				$list[$k]['cjtype'] = '';
			}else{
				$list[$k]['cjtype'] = 1;
				$cjData++;
			}
		}
		// var_dump($cjData);var_dump($CjRecord);die();
		$this->view->assign('cjData',$cjData);
		
		$this->view->assign('row',$row);
		$this->view->assign('totalCost',$totalCost);

		if($list){
			$this->view->assign('list',$list);
		}
		
		return $this->view->fetch();
    }

    public function proSearch(){
    	$list = [];
        if($this->request->isAjax()){
            $keywords = $this->request->post('keywords');
            $depot = $this->request->post('depot');
            $where['p.depot_id'] = $depot;

            if(preg_match('/[\x{4e00}-\x{9fa5}]/u', $keywords)>0){// 搜索中文关键词
				$where['p.pro_name'] = ['like', '%'.$keywords.'%'];

			}/*elseif(preg_match('/^[1-9][0-9]*$/',$keywords)){// 搜索产品id关键词
				$where['p.pro_id'] = $keywords;
			}*/
			else{												// 搜索产品拼音码关键词
				$where['p.pro_spell'] = ['like', '%'.$keywords.'%'];
			}

            $where['p.pro_status'] = '1';
            $where['p.pro_type'] = '4';
            if($keywords && $depot){
                $list = db('project')->alias('p')
                		->join('yjy_unit u','p.pro_unit = u.id', 'LEFT')
                        ->where($where)
                        ->order('pro_spell','asc')
                        ->column('p.pro_id,p.pro_name,p.pro_spell,u.name,p.pro_spec,p.pro_amount,p.pro_cost,p.addr,p.producer,p.regist_num,p.approv_num');
                // var_dump($list);

            }
        }

        return json($list);
    }



    public function getNum(){
        if($this->request->isAjax()){
            $num = $this->request->post('num');
            if($num){
                $nums=$this->model->field('max(man_id) as id')->find();
                $numss = $nums['id']+1;
                $wpjh_num = 'wpjh'.$numss;
                // var_dump($yp_num);
                return json($wpjh_num);
            }

        }
    }

    public function editRemark(){
        if($this->request->isAjax()){
            $manid = $this->request->post('manid');
            $mremark = $this->request->post('mremark');
            $msupplier_id = $this->request->post('msupplier_id');
            $manlist = db('wm_manlist')->alias('ml')
            			->field('lotid,man_num')
            			->join('yjy_wm_manifest mf','ml.manid =mf.man_id','LEFT')
            			->where('ml.manid',$manid)
            			->select();
            // return json($manlist);
            if($manlist){
                $editManRemarkRes=db('wm_manifest')->where('man_id',$manid)->update(['mremark'=>$mremark,'msupplier_id'=>$msupplier_id]);
                
                foreach ($manlist as $key => $v) {
                	$editLogRemarkRes[]=db('wm_stocklog')->where(['slotid'=>$v['lotid'],'sletc'=>$v['man_num']])->update(['slremark'=>$mremark]);
                }
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





}