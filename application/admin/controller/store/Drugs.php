<?php

namespace app\admin\controller\store;

use app\common\controller\Backend;
use app\admin\model\Pdutype;

use think\Controller;
use think\Request;
use think\DB;

/**
 * 产品登记
 *
 * @icon fa fa-circle-o
 */
class Drugs extends Backend
{
    
    /**
     * Product模型对象
     */
    protected $model = null;
    protected $noNeedRight = ['ajaxsubject', ];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Product');
		$pdutypeList = [];
		$unitList = [];
		$producerList = [];
		$depotList = [];
		// 查询数据集
		$pdutypeLists = model('Protype')->where('pid','0')->where('status','normal')->order('id', 'desc')->select();
		foreach ($pdutypeLists as $k => $v)
        {
            $pdutypeList[$v['name']] = $v;
        }
		$unitLists = model('Unit')->where('status','normal')->order('id', 'asc')->select();
		foreach ($unitLists as $k => $v)
        {
            $unitList[$v['name']] = $v;
        }
		$producerLists = model('Producer')->where('status','normal')->order('id', 'asc')->select();
		foreach ($producerLists as $k => $v)
        {
            $producerList[$v['proname']] = $v;
        }
		$depotLists = model('Depot')->where('type','1')->where('status','normal')->order('id', 'asc')->select();
		foreach ($depotLists as $k => $v)
        {
            $depotList[$v['name']] = $v;
        }
		$deptmentLists = model('Deptment')->where('dept_status','1')->order('dept_id', 'asc')->select();
		foreach ($deptmentLists as $k => $v)
        {
            $deptmentList[$v['dept_name']] = $v;
        }
        $data = model('Protype')->where('status','normal')->where('pid', '0')->column('id,pid,name');
        
        $proFeeType = model('Fee')->getList();
        $this->view->assign('proFeeType', $proFeeType);
        
        $this->view->assign('subject', $data);
        $this->view->assign("isJkList", $this->model->getIsJkList());
        $this->view->assign("drugTypeList", $this->model->getDrugTypeList());
        $this->view->assign("statusList", $this->model->getStatusList());
        $this->view->assign("isTcList", $this->model->getIsTcList());
        $this->view->assign("isJfList", $this->model->getIsJfList());
        $this->view->assign("isCgList", $this->model->getIsCgList());
        $this->view->assign("typeList", $this->model->getTypeList());
        $this->view->assign("pdutypeList", $pdutypeList);
        $this->view->assign("unitList", $unitList);
        $this->view->assign("producerList", $producerList);
        $this->view->assign("depotList", $depotList);
        $this->view->assign("deptmentList", $deptmentList);
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
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name'))
            {
                return $this->selectpage();
            }
            
            $filter = $this->request->get("filter", '');
            $filter = json_decode($filter, TRUE);
            if (!empty($filter['stime'])) {
                $startr= strtotime($filter['stime'].'00:00:00');       //今天的0点
                $endr= strtotime($filter['etime'].'23:59:59');   //  今天的24点
                $map['createtime'] = array("between",array($startr,$endr));
            }else{
                $map= [];
            }

            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            
            $total = $this->model
                    ->where($map)->where($where)
					->where('type','1')
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->where($map)->where($where)
					->where('type','1')
                    ->order(['id' => 'desc', 'status' => 'desc'])
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

                    // <!-- 2017-09-28  子非魚 -->
                    foreach ($list as $key => $value) {
                         $pName = model('Product')->getpName($list[$key]['pdutype_id']);   //获取所属科目、类别名称
                         $pName2 = model('Product')->getpName($list[$key]['pdutype2_id']);
                         $dept_name = model('Check')->getDepotName($list[$key]['depot_id']);
                         $producer_id = db('Producer')->field('proname')->where(['id' => $list[$key]['producer_id']])->select();
                         if($pName){
                            foreach ($pName as $keys => $va) {
                                $list[$key]['pdutype_id'] = $va['name'];
                            }
                         }
                         if($pName2){
                            foreach ($pName2 as $keys => $va) {
                                $list[$key]['pdutype2_id'] = $va['name'];
                            }
                         }else{
                            $list[$key]['pdutype2_id'] = '-';
                         }
                         if($dept_name){
                            foreach ($dept_name as $keys => $va) {
                                $list[$key]['dept_name'] = $va['name'];
                            }
                         }else{
                             $list[$key]['dept_name'] = '-';
                         }

                         if($producer_id){
                            foreach ($producer_id as $keys => $va) {
                                $list[$key]['producer_id'] = $va['proname'];
                            }
                         }else{
                             $list[$key]['producer_id'] = '-';
                         }

                         
                    }
                    // <!-- 2017-09-28  子非魚 -->
                    
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }


    // <!-- 2017-09-28  子非魚 -->
    public function add(){
        if($this->model->select()){
            $num=$this->model->field('max(id) as id')->find();
            $nums = $num['id']+1;
            $yp_num = 'yp'.$nums;
            $this->view->assign("yp_num", $yp_num);
        }else{
            $this->view->assign("yp_num", 'yp1');
        }

        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                // dump($params);die();
                $num = $params['num'];
                $cost = $params['cost'];
                $price = $params['price'];
                if($cost<0 || $price<0){
                    $this->error('成本或售价不得小于0！');
                }
                $onum_res = db('Product')->where('num',$num)->find();
                foreach ($params as $k => $v)
                {
                    $v = is_array($v) ? implode(',', $v) : $v;
                }
                try
                {
                if(empty($onum_res)){
                    //是否采用模型验证
                    if ($this->modelValidate)
                    {
                        $name = basename(str_replace('\\', '/', get_class($this->model)));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : true) : $this->modelValidate;
                        $this->model->validate($validate);
                    }
                    $result = $this->model->save($params);
                    if ($result !== false)
                    {
                        $this->success();
                    }
                    else
                    {
                        $this->error($this->model->getError());
                    }

                }else{
                        $this->error('编号已存在，请更换编号！');
                    }

                }
                catch (\think\exception\PDOException $e)
                {
                    $this->error($e->getMessage());
                }


            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        // $data = model('Protype')->where('pid = 0')->find();
        // $this->model = model('Protype');
        // $datas = model('Protype')->where(['pid' => 0])->select()->toArray();
        // $datas = model('Protype')->where(['pid' => 0])->field('id,name')->select();
        // $datas = model('Protype')->where(['pid' => 0])->select();
        

        // $datas = collection($datas)->toArray();
        // $datas = $datas->toArray();
        
        return $this->view->fetch();
    }

    public function getNum(){
        if($this->request->isAjax()){
            $num = $this->request->post('num');
            if($num){
                $nums=$this->model->field('max(id) as id')->find();
                $numss = $nums['id']+1;
                $yp_num = 'yp'.$numss;
                // var_dump($yp_num);
                return json($yp_num);
            }

        }
    }


    public function ajaxSubject(){
        if ($this->request->isAjax()){
            $sid = $this->request->post('subject_id');
            $str = "<option value=''>请选择类别</option>";
            if(!empty($sid) && $sid != 0){
                $data = model('Protype')->where('pid', $sid)->where('status','normal')->column('id,pid,name');
                if($data){
                    foreach ($data as $key => $v) {
                        $str.= "<option value=".$v['id'].">".$v['name']."</option>";
                    }
                }else{
                    $str = '<option value="">无二级类别</option>';
                }

            }else{
                $str = '<option value=""></option>';
            }
            return $str;
        }
    }


    public function edit($ids = NULL)
    {
        $row = $this->model->find($ids);
        if (!$row)
            $this->error(__('No Results were found'));
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {   
                $cost = $params['cost'];
                $price = $params['price'];
                if($cost<0 || $price<0){
                    $this->error('成本或售价不得小于0！');
                }

                foreach ($params as $k => $v)
                {
                    $v = is_array($v) ? implode(',', $v) : $v;
                }
                try
                {
                    //是否采用模型验证
                    if ($this->modelValidate)
                    {
                        $name = basename(str_replace('\\', '/', get_class($this->model)));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : true) : $this->modelValidate;
                        $row->validate($validate);
                    }
                    $result = $row->save($params);
                    if ($result !== false)
                    {
                        $this->success();
                    }
                    else
                    {
                        $this->error($row->getError());
                    }
                }
                catch (think\exception\PDOException $e)
                {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $data = model('Protype')->where('pid', '0')->column('id,pid,name');
        $aa = model('Product')->where('id', $ids)->column('pdutype_id,pdutype2_id');
        $pdutype2_id = '';
        if(!empty($aa)){
            foreach ($aa as $key => $value) {
                $pData = model('Protype')->where('pid', $key)->column('id,pid,name');
                foreach ($pData as $key => $v) {
                    if($value == $v['id']){
                        $select = '  selected';
                    }else{
                        $select = '';
                    }
                    $pdutype2_id.= "<option value='".$v['id']."'".$select.">".$v['name']."</option>";
                }
            }
            
        }else{
            $pdutype2_id = '<option value=""></option>';
        }
        
        // var_dump($pdutype2_id);
        $this->view->assign('pdutype2_id',$pdutype2_id);
        $this->view->assign('goods_id',$ids);
        $this->view->assign('subject', $data);
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }



    // <!-- 2017-09-28  子非魚 -->

}
