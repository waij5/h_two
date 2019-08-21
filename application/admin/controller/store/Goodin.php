<?php

namespace app\admin\controller\store;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use think\DB;

/**
 * 进货单
 *
 * @icon fa fa-circle-o
 */
class Goodin extends Backend
{
    
    /**
     * PurchaseOrder模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('PurchaseOrder');
		$producerList = [];
		$depotList = [];
		$producerLists = model('Producer')->where('status','normal')->order('id', 'asc')->select();
		foreach ($producerLists as $k => $v)
        {
            $producerList[$v['id']] = $v;
        }
		$depotLists = model('Depot')->where('type','2')->where('status','normal')->order('id', 'asc')->select();
		foreach ($depotLists as $k => $v)
        {
            $depotList[$v['id']] = $v;
        }
        $this->view->assign("typeList", $this->model->getTypeList());
        $this->view->assign("rkTypeList", $this->model->getRkTypeList());
        $this->view->assign("isDrugList", $this->model->getIsDrugList());
        $this->view->assign("isJzList", $this->model->getIsJzList());
        $this->view->assign("isCbList", $this->model->getIsCbList());
        $this->view->assign("cjTypeList", $this->model->getCjTypeList());
        $this->view->assign("statusList", $this->model->getStatusList());
        $this->view->assign("producerList", $producerList);
        $this->view->assign("depotList", $depotList);
    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个方法
     * 因此在当前控制器中可不用编写增删改查的代码,如果需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个方法
     * 因此在当前控制器中可不用编写增删改查的代码,如果需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    /**
     * 查看
     */
    public function index()
    {
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
                $map['orderlist.createtime'] = array("between",array($startr,$endr));
                $mapTotal['createtime'] = array("between",array($startr,$endr));
            }else{
                $map= [];
                $mapTotal= [];
            }
            
			list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->where('type','1')
					->where('is_drug','2')
					->where($mapTotal)->where($where)
                    ->count();
			$lists = $this->model->alias('orderlist')
					->field('orderlist.*, producer.proname,  depot.name as depname')
					->join(DB::getTable('Producer') . ' producer', 'orderlist.producer_id = producer.id', 'LEFT')
					->join(DB::getTable('Depot') . ' depot', 'orderlist.depot_id = depot.id', 'LEFT')
					->where('orderlist.type','1')
					->where('orderlist.is_drug','2')
					->where($map)->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
					->select();
			if ($search)
            {
                foreach ($lists as $k => $v)
                {
                    if (stripos($v['order_num'], $search) !== false || stripos($v['depot_id'], $search) !== false)
                    {
                        $list[] = $v;
                    }
                }
            }
            else
            {
                $list = $lists;
            }

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }






    /**
     * Selectpage搜索
     * 
     * @internal
     */
    public function selectpage()
    {
        return parent::selectpage();
    }
    
     
    
    
	public function getNum(){
        if($this->request->isAjax()){
            $num = $this->request->post('num');
            if($num){
                $nums=$this->model->field('max(id) as id')->find();
                $numss = $nums['id']+1;
                $wprk_num = 'wprk'.$numss;
                // var_dump($yp_num);
                return json($wprk_num);
            }

        }
    }

    /**
     * 添加
     */
    public function add(){
		if($this->model->select()){
            $num=$this->model->field('max(id) as id')->find();
			$nums = $num['id']+1;
			$order_num = 'wprk'.$nums;
			$this->view->assign("order_num", $order_num);
        }else{
			$this->view->assign("order_num", 'wprk1');
        }
		if ($this->request->isPost()){
            $params = $this->request->post("row/a");
			$drugs_id = $this->request->post("drugs_id/a");
			$storage_num = $this->request->post("storage_num/a");
			$cost = $this->request->post("cost/a");
			$price = $this->request->post("price/a");
			$producttime = $this->request->post("producttime/a");
			$expirestime = $this->request->post("expirestime/a");
            if(!$drugs_id)
                $this->error('请选择产品！');
			$drugRows = array();
			foreach ($drugs_id as $k => &$v){
				$drugRows[$k]['drugs_id'] = $v;
			}
			foreach ($storage_num as $k => &$v){
                $intnum = intval($v);
                $floatnum = floatval($v);
                // var_dump($floatnum);die();
                if($floatnum <1 || $intnum != $floatnum){
                    $this->error('请输入正确的入库数量！');
                }

				$drugRows[$k]['storage_num'] = $v;
			}
			foreach ($cost as $k => &$v){
				$drugRows[$k]['cost'] = $v;
			}
			foreach ($price as $k => &$v){
				$drugRows[$k]['price'] = $v;
			}
			foreach ($producttime as $k => &$v){
				$drugRows[$k]['producttime'] = strtotime($v);
			}
			foreach ($expirestime as $k => &$v){
				$drugRows[$k]['expirestime'] = strtotime($v);
			}
			
            if ($params){
                $remark = $params['remark'];  //stock_add_log
                $order_num = $params['order_num'];
                $onum_res = model('PurchaseOrder')->where('order_num',$order_num)->find();
                foreach ($params as $k => $v){
                    $v = is_array($v) ? implode(',', $v) : $v;
                }
                try{
                    if(empty($onum_res)){
                        //是否采用模型验证
                        if ($this->modelValidate){
                            $name = basename(str_replace('\\', '/', get_class($this->model)));
                            $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : true) : $this->modelValidate;
                            $this->model->validate($validate);
                        }
                        $result = $this->model->save($params);
    					if ($result !== false){
                            $purchase_id = $this->model->id;
    						$data = [];
    						foreach ($drugRows as $k => $v){	
    							$promodel = model('Product')->get(['id' => $v['drugs_id']]);
    							if ($promodel){
    								$stock = $promodel['stock'];
                                    $drugRows[$k]['stock'] = $stock + $v['storage_num'];  //stock_add_log
    								model('Product')->where('id', $v['drugs_id'])->update([
    										'stock' => $stock + $v['storage_num'],
    								]);
    							}
    							$goodsData[] = ['id' => $v['drugs_id'], 'prtime' => $v['producttime'], 'extime' => $v['expirestime']];
    							$data[] = ['goods_id' => $v['drugs_id'], 'purchase_id' => $purchase_id, 'good_num' => $v['storage_num'], 'storage_num' => $v['storage_num'], 'cost' => $v['cost'], 'totalcost' => $v['cost'] * $v['storage_num'], 'price' => $v['price'], 'totalprice' => $v['price'] * $v['storage_num'], 'producttime' => $v['producttime'], 'expirestime' => $v['expirestime']];
    						}
                            $goodsRes = model('Product')->saveAll($goodsData);//保存生产、到期日期到登记表
    						$dataresult = model('PurchaseFlow')->saveAll($data);
    						if ($dataresult !== false){
                                $exp = '物品入库单:';  //stock_add_log
                                switch ($params['rk_type']) {
                                    case '0':
                                        $type = '1';    //入库
                                        break;
                                    case '1':
                                        $type = '11';   //调拨入库
                                        break;
                                    case '2':
                                        $type = '12';   //盘盈入库
                                        break;
                                    case '3':
                                        $type = '13';   //退货入库
                                        break;
                                    case '4':
                                        $type = '14';   //报增入库
                                        break;
                                    case '5':
                                        $type = '15';   //其他入库
                                        break;
                                    
                                }
                                model('Stock')->stock_add_log($drugRows,$params,$exp,$remark,$type);//记录数据到产品库存变动明细表
    							$this->success();
    						}else{
    							$this->error(model('PurchaseFlow')->getError());
    						}
                        }elseif($result == false){
                            $this->error($this->model->getError());
                        }
                    }else{
                        $this->error('本次入库单号已存在，请重新打开本页面！');
                    }
                }
                catch (\think\exception\PDOException $e){
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }



    public function proSearch(){
        $list = [];
        if($this->request->isAjax()){
            $keywords = $this->request->post('keywords');
            $depot = $this->request->post('depot');
            $where['depot_id'] = $depot;
            $where['code'] = ['like', '%'.$keywords.'%'];
            $where['status'] = 'normal';
            if($keywords && $depot){
                $list = model('Product')
                        ->where($where)
                        ->order('code','asc')
                        ->column('id,name,code,lotnum,sizes,unit,stock,prtime,extime,cost,price');
                // var_dump($list);

                foreach ($list as $key => $v) {
                    if($v['prtime']){
                        $list[$key]['prtime'] = date("Y-m-d",$v['prtime']);
                    }else{
                        $list[$key]['prtime'] = '0000-00-00';
                    }
                    if($v['extime']){
                        $list[$key]['extime'] = date("Y-m-d",$v['extime']);
                    }else{
                        $list[$key]['extime'] = '0000-00-00';
                    }
                    
                }
            }
        }
        return json($list);
    }







    /**
     * 编辑
     */
    public function edit($ids = NULL)
    {
        $row = $this->model->find($ids);
		$flowLists = model('PurchaseFlow')->where('purchase_id',$ids)->order('id', 'asc')->select();
		foreach ($flowLists as $k => $v)
        {	
			$lists[$k]['storage_num'] = $v['storage_num'];
            $lists[$k]['totalcost'] = $v['totalcost'];
			$lists[$k]['cost'] = $v['cost'];
			$lists[$k]['price'] = $v['price'];
			$lists[$k]['producttime'] = empty($v['producttime'])?'0000-00-00':date("Y-m-d",$v['producttime']);
			$lists[$k]['expirestime'] = empty($v['expirestime'])?'0000-00-00':date("Y-m-d",$v['expirestime']);
			$lists[$k]['goods_id'] = $v['goods_id'];
			$goods_id = $v['goods_id'];
			$proLists = model('Product')->where('id',$goods_id)->select();
            if($proLists){
                $lists[$k]['code'] = $proLists[0]['code'];
                $lists[$k]['name'] = $proLists[0]['name'];
                $lists[$k]['stock'] = $proLists[0]['stock'];
                $lists[$k]['sizes'] = $proLists[0]['sizes'];
                $lists[$k]['unit'] = $proLists[0]['unit'];
                $lists[$k]['lotnum'] = $proLists[0]['lotnum'];
            }else{
                $lists[$k]['code'] = '';
                $lists[$k]['name'] = '';
                $lists[$k]['stock'] = '';
                $lists[$k]['sizes'] = '';
                $lists[$k]['unit'] = '';
                $lists[$k]['lotnum'] = '';
            }
			
        }

        //4.10  打印
        $totalAll['storage_num'] ='';
        $totalAll['totalcost'] ='';
        // $totalAll['createtime'] ='';
        foreach ($lists as $key => $val) {
            $totalAll['storage_num'] += $val['storage_num'];
            $totalAll['totalcost'] += $val['totalcost'];
            // $totalAll['createtime'] = $val['createtime'];
        }
        $this->view->assign("totalAll", $totalAll);
        //4.10  打印

		//var_dump($lists);exit;
        if (!$row)
            $this->error(__('No Results were found'));
        
        $this->view->assign("row", $row);
		if(!empty($lists)){
			$this->view->assign("lists", $lists);
		}
        return $this->view->fetch();
    }


    /**
     * 删除
     */
    public function del($ids = "")
    {
        if ($ids)
        {   
            $exp = '删除物品入库单:';      //保存到stock_log的说明信息
            $params = db('purchase_order')->field('rk_type')->where('id', $ids)->find();
            switch ($params['rk_type']) {
                case '0':
                    $type = '111';    //删除入库
                    break;
                case '1':
                    $type = '1111';   //删除调拨入库
                    break;
                case '2':
                    $type = '1211';   //删除盘盈入库
                    break;
                case '3':
                    $type = '1311';   //删除退货入库
                    break;
                case '4':
                    $type = '1411';   //删除报增入库
                    break;
                case '5':
                    $type = '1511';   //删除其他入库
                    break;
                
            }
            model('Stock')->stock_del_log($ids,$exp,$type);   //删除记录的产品数据保存到表;同时扣除对应的产品的数量
            db('purchase_flow')->where('purchase_id', $ids)->delete();      //删除单号关联的产品记录
            $count = $this->model->destroy($ids);
            if ($count)
            {
                $this->success();
            }
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }

}
