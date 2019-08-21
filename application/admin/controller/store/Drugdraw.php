<?php

namespace app\admin\controller\store;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use think\DB;

/**
 * 科室领药
 *
 * @icon fa fa-circle-o
 */
class Drugdraw extends Backend
{
    
    /**
     * DepotOutks模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('DepotOutks');
        $producerList = [];
        $depotList = [];
        $userList = [];
        $deptList = [];
        $customList = [];
        $producerLists = model('Producer')->where('status','normal')->order('id', 'asc')->select();
        foreach ($producerLists as $k => $v)
        {
            $producerList[$v['id']] = $v;
        }
        $depotLists = model('Depot')->where('type','1')->where('status','normal')->order('id', 'asc')->select();
        foreach ($depotLists as $k => $v)
        {
            $depotList[$v['id']] = $v;
        }
        $userLists = model('Admin')->order('username', 'asc')->select();
        foreach ($userLists as $k => $v)
        {
            $userList[$v['id']] = $v;
        }
        $deptLists = model('Deptment')->where('dept_status','1')->order('dept_id', 'asc')->select();
        foreach ($deptLists as $k => $v)
        {
            $deptList[$v['dept_id']] = $v;
        }
        // $customLists = model('Customer')->order('ctm_id', 'asc')->select();
        // foreach ($customLists as $k => $v)
        // {
        //     $customList[$v['ctm_id']] = $v;
        // }
        $this->view->assign("typeList", $this->model->getTypeList());
        $this->view->assign("statusList", $this->model->getStatusList());
        $this->view->assign("producerList", $producerList);
        $this->view->assign("depotList", $depotList);
        $this->view->assign("userList", $userList);
        $this->view->assign("deptList", $deptList);
        // $this->view->assign("customList", $customList);
    }
    
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
            if (!empty($filter['stime']) && !empty($filter['etime'])) {
                $startr= strtotime($filter['stime'].'00:00:00');       //今天的0点
                $endr= strtotime($filter['etime'].'23:59:59');   //  今天的24点
                $map['kslist.createtime'] = array("between",array($startr,$endr));
                $mapTotal['createtime'] = array("between",array($startr,$endr));
            }else{
                $map= [];
                $mapTotal= [];
            }
            if(!empty($filter['member_name'])){
                $map['customer.ctm_name'] = ['like', '%'.$filter['member_name'].'%'];
                $mapTotal['customer.ctm_name'] = ['like', '%'.$filter['member_name'].'%'];
            }

            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $total = $this->model
                    ->where('type','0')
                    ->where($mapTotal)->where($where)
                    ->count();
            $lists = $this->model->alias('kslist')
                    ->field('kslist.*, producer.proname, depot.name as depname, deptment.dept_name, admin.nickname, customer.ctm_name')
                    ->join(DB::getTable('Producer') . ' producer', 'kslist.producer_id = producer.id', 'LEFT')
                    ->join(DB::getTable('Depot') . ' depot', 'kslist.depot_id = depot.id', 'LEFT')
                    ->join(DB::getTable('Deptment') . ' deptment', 'kslist.depart_id = deptment.dept_id', 'LEFT')
                    ->join(DB::getTable('Admin') . ' admin', 'kslist.out_id = admin.id', 'LEFT')
                    ->join(DB::getTable('Customer') . ' customer', 'kslist.member_id = customer.ctm_id', 'LEFT')
                    ->where('kslist.type','0')
                    ->where($map)->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            // $total = count($lists);
            

            $result = array("total" => $total, "rows" => $lists);

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



    /*public function getLeader(){
        
        if($this->request->isAjax()){

            $leaderList = '';
            $depart = $this->request->post('depart');
            if($depart){
                $data = model('Admin')->alias('a')->field('a.nickname,a.id')
                        ->join(DB::getTable('Deptment') . ' dt', 'a.dept_id = dt.dept_id', 'LEFT')
                        ->where('dt.dept_id',$depart)
                        ->select();
                if($data){
                    $leaderList = '<option value="">---请选择---</option>';
                    foreach ($data as $key => $v) {
                        
                        $leaderList .= "<option value=".$v['id'].">".$v['nickname']."</option>";
                    }
                }else{
                    $leaderList = '<option value="">此科室无人员</option>';
                }

            }else{
                $leaderList = '<option value=""></option>';
            }

            return $leaderList;
        }
    }*/
    
	

    /**
     * 添加
     */
    public function add(){
		if($this->model->select()){
            $num=$this->model->field('max(id) as id')->find();
			$nums = $num['id']+1;
			$order_num = 'ly'.$nums;
			$this->view->assign("order_num", $order_num);
        }else{
			$this->view->assign("order_num", 'ly1');
        }
		if ($this->request->isPost()){
            $params = $this->request->post("row/a");
			$drugs_id = $this->request->post("drugs_id/a");
			$storage_num = $this->request->post("storage_num/a");
            $cost = $this->request->post("cost/a");
            $price = $this->request->post("price/a");
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
                    $this->error('请输入正确的领取数量！');
                }

				$drugRows[$k]['storage_num'] = $v;
			}
			foreach ($cost as $k => &$v){
                $drugRows[$k]['cost'] = $v;
            }
            foreach ($price as $k => &$v){
                $drugRows[$k]['price'] = $v;
            }
            if ($params){
                $remark = $params['remark'];  //stock_add_log
                $order_num = $params['order_num'];
                $onum_res = model('DepotOutks')->where('order_num',$order_num)->find();
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
                        $data = [];
                        \think\Db::startTrans();
                        $res = ['error' => false, 'msg' => ''];
                        foreach ($drugRows as $k => $v){    
                            $promodel = model('Product')->get(['id' => $v['drugs_id']]);
                            if ($promodel){
                                $stock = $promodel['stock'];
                                if($v['storage_num'] > $stock){
                                    // $this->error('冲减数量不能大于当前库存数！');exit();
                                    $res['error'] = true;
                                    $res['msg'] = '领药数量不能大于当前库存数！';

                                    break;
                                }else{
                                    $drugRows[$k]['stock'] = $stock - $v['storage_num'];  //stock_add_log
                                    model('Product')->where('id', $v['drugs_id'])->update([
                                            'stock' => $stock - $v['storage_num'],
                                    ]);

                                    $result = $this->model->save($params);
                                    if ($result !== false){
                                        $depotout_id = $this->model->id;//var_dump($drugRows);exit;
                                        $data[] = ['goods_id' => $v['drugs_id'], 'depotout_id' => $depotout_id, 'goods_num' => $v['storage_num'],'cost' => $v['cost'], 'price' => $v['price'], 'totalcost' => $v['cost'] * $v['storage_num']];
                                    }elseif($result == false){
                                        $this->error($this->model->getError());
                                    }
                                }
                                
                            }
                            
                        }
                        if ($res['error'] == false) {
                            \think\Db::commit();
                            if($data){
                                $dataresult = model('DepartProduct')->saveAll($data);
                                if ($dataresult !== false){
                                    $exp = '科室领药单:';  //stock_add_log
                                    $type = '8';  // 类型8 是领药
                                    model('Stock')->stock_lladd_log($drugRows,$params,$exp,$remark,$type);//记录数据到产品库存变动明细表
                                    $this->success();
                                }else{
                                    $this->error(model('DepartProduct')->getError());
                                }
                            }
                        } else {
                            \think\Db::rollback();
                            $this->error($res['msg']);
                        }              
                    }else{
                        $this->error('本次领药单号已存在，请重新打开本页面！');
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

    public function getNum(){
        if($this->request->isAjax()){
            $num = $this->request->post('num');
            if($num){
                $nums=$this->model->field('max(id) as id')->find();
                $numss = $nums['id']+1;
                $ly_num = 'ly'.$numss;
                // var_dump($yp_num);
                return json($ly_num);
            }

        }
    }
    /**
     * 编辑
     */
    public function edit($ids = NULL)
    {
        $row = $this->model->find($ids);
		$flowLists = model('DepartProduct')->where('depotout_id',$ids)->order('id', 'asc')->select();
        $customerS = db('depot_outks')->alias('do')->field('do.member_id,c.ctm_name')
                        ->join(DB::getTable('Customer') . ' c', 'do.member_id = c.ctm_id', 'LEFT')
                        ->where('do.id',$ids)->find();
        $this->view->assign("customerS", $customerS);
                        // var_dump($customer_name);die();
                        
		foreach ($flowLists as $k => $v)
        {	
			$lists[$k]['storage_num'] = $v['goods_num'];
            $lists[$k]['totalcost'] = $v['totalcost'];
			$lists[$k]['goods_id'] = $v['goods_id'];
            $lists[$k]['price'] = $v['price'];
            $lists[$k]['cost'] = $v['cost'];
			$goods_id = $v['goods_id'];
			$proLists = model('Product')->where('id',$goods_id)->select();
            if($proLists){
    			$lists[$k]['code'] = $proLists[0]['code'];
    			$lists[$k]['name'] = $proLists[0]['name'];
    			$lists[$k]['stock'] = $proLists[0]['stock'];
                $lists[$k]['lotnum'] = $proLists[0]['lotnum'];
                $lists[$k]['sizes'] = $proLists[0]['sizes'];
    			$lists[$k]['unit'] = $proLists[0]['unit'];
                $lists[$k]['prtime'] = date('Y-m-d', $proLists[0]['prtime']);
                $lists[$k]['extime'] = date('Y-m-d', $proLists[0]['extime']);
            }else{
                $lists[$k]['code'] = '';
                $lists[$k]['name'] = '';
                $lists[$k]['stock'] = '';
                $lists[$k]['lotnum'] = '';
                $lists[$k]['sizes'] = '';
                $lists[$k]['unit'] = '';
                $lists[$k]['prtime'] = '';
                $lists[$k]['extime'] = '';
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
            $exp = '删除科室领药单:';      //保存到stock_log的说明信息
            $type = '811';
            model('Stock')->stock_lldel_log($ids,$exp,$type);   //删除记录的产品数据保存到表;同时扣除对应的产品的数量
            db('depart_product')->where('depotout_id', $ids)->delete();      //删除单号关联的产品记录
            $count = $this->model->destroy($ids);
            if ($count)
            {
                $this->success();
            }
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }

}
