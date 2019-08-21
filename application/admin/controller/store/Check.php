<?php

namespace app\admin\controller\store;

use app\common\controller\Backend;

use think\Controller;
use think\Request;

/**
 * 仓库盘点
 *
 * @icon fa fa-circle-o
 */
class Check extends Backend
{
    
    /**
     * StockChecks模型对象
     */
    protected $model = null;

    public function _initialize()
    {   
        $depotLists = [];
        $userList = [];
        parent::_initialize();
        $this->model = model('StockChecks');
		
		$depotLists = model('Depot')->where('status','normal')->order('id', 'asc')->select();
		foreach ($depotLists as $k => $v)
        {
            $depotList[$v['id']] = $v;
        }
        $userLists = model('Admin')->order('id', 'asc')->select();
        foreach ($userLists as $k => $v)
        {
            $userList[$v['id']] = $v;
        }
        $this->view->assign("userList", $userList);
        $this->view->assign("depotList", $depotList);

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个方法
     * 因此在当前控制器中可不用编写增删改查的代码,如果需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */


    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            foreach ($list as $key => $value){
                $dName = model('Check')->getDepotName($list[$key]['depot_id']);   //获取盘点仓库中文名称
                if($dName){
                    foreach ($dName as $keys => $va) {
                        $list[$key]['depot_id'] = $va['name'];
                    }
                 }
            }
            $result = array("total" => $total, "rows" => $list);

            return json($result);
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
                        ->column('id,name,code,lotnum,sizes,unit,stock,prtime,extime,cost');
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
     * 添加
     */
    public function add(){
		if($this->model->select()){
            $num=$this->model->field('max(id) as id')->find();
			$nums = $num['id']+1;
			if(strlen($nums)<6){
				$num=str_pad($nums,6,"0",STR_PAD_LEFT);
			}else{
				$num=$nums;
			}
			$order_num = 'pd'.$num;
			//var_dump($order_num);exit;
			$this->view->assign("order_num", $order_num);
        }else{
			$this->view->assign("order_num", 'pd000001');
        }
		if ($this->request->isPost()){
            $params = $this->request->post("row/a");
			$goods_id = $this->request->post("goods_id/a");
			$storage_num = $this->request->post("storage_num/a");
			$stock = $this->request->post("stock/a");
            if(!$goods_id)
                $this->error('请选择产品！');
			$drugRows = array();
			foreach ($goods_id as $k => &$v){
				$drugRows[$k]['goods_id'] = $v;
			}
			foreach ($storage_num as $k => &$v){
                $intnum = intval($v);
                $floatnum = floatval($v);
                // var_dump($floatnum);die();
                if($floatnum <1 || $intnum != $floatnum){
                    $this->error('请输入正确的盘点数量！');
                }

				$drugRows[$k]['storage_num'] = $v;
			}
			foreach ($stock as $k => $v){
				$drugRows[$k]['stock'] = $v;
			}
			
            if ($params){
                foreach ($params as $k => $v){
                    $v = is_array($v) ? implode(',', $v) : $v;
                }
                try{
                    //是否采用模型验证
                    if ($this->modelValidate){
                        $name = basename(str_replace('\\', '/', get_class($this->model)));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : true) : $this->modelValidate;
                        $this->model->validate($validate);
                    }
                    $result = $this->model->save($params);
					if ($result !== false){
                        $detail_id = $this->model->id;//var_dump($drugRows);exit;
						$data = [];
						foreach ($drugRows as $k => $v){
							$differ_num = $v['storage_num'] - $v['stock'];
							if($differ_num == 0){
								$status = 0;
							}elseif($differ_num > 0){
								$status = 1;
							}elseif($differ_num < 0){
								$status = -1;
							}
							$data[] = ['goods_id' => $v['goods_id'], 'detail_id' => $detail_id, 'goods_num' => $v['storage_num'], 'differ_num' => $differ_num, 'status' => $status];
						}
						$dataresult = model('StockDetail')->saveAll($data);
						if ($dataresult !== false){
							$this->success();
						}else{
							$this->error(model('StockDetail')->getError());
						}
                    }elseif($result == false){
                        $this->error($this->model->getError());
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

    /**
     * 编辑
     */
    public function edit($ids = NULL)
    {
        $row = $this->model->find($ids);
		$flowLists = model('StockDetail')->where('detail_id',$ids)->order('id', 'asc')->select();
        if($flowLists){
            foreach ($flowLists as $k => $v)
            {   
                $lists[$k]['storage_num'] = $v['goods_num'];
                $lists[$k]['goods_id'] = $v['goods_id'];
                $lists[$k]['differ_num'] = $v['differ_num'];
                $lists[$k]['status'] = $v['status'];
                $goods_id = $v['goods_id'];
                $proLists = model('Product')->where('id',$goods_id)->find();
                $lists[$k]['code'] = $proLists['code'];
                $lists[$k]['name'] = $proLists['name'];
                $lists[$k]['stock'] = $proLists['stock'];
                $lists[$k]['unit'] = $proLists['unit'];
            }
        }else{
            $this->error(__('No Results were found'));
        }
		
		
       if (!$row)
            $this->error(__('No Results were found'));
        
        $this->view->assign("row", $row);
		if(!empty($lists)){
			$this->view->assign("lists", $lists);
		}
        return $this->view->fetch();
    }
    

}
