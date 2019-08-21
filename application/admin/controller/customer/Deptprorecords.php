<?php

namespace app\admin\controller\customer;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use think\Db;

/**
 * 物品数量
 *
 * @icon fa fa-circle-o
 */
class Deptprorecords extends Backend
{
    
    /**
     * DeptProRecords模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('DeptProRecords');

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个方法
     * 因此在当前控制器中可不用编写增删改查的代码,如果需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    public function index()
    {         
        //物品
        $proList[''] = __('ALL');
        $proLists = model('dept_pro')->column('pro_name','id');
        foreach ($proLists as $key => $value) {
            $proList[$key] = $value;
        }
        $this->view->assign("proList",$proList);
        //科室
        // $deptList[''] = __('ALL');
        // $deptLists = model('deptment')->column('dept_name','dept_id');
        // foreach ($deptLists as $key => $value) {
        //     $deptList[$key] = $value;
        // }
        $deptList = (new \app\admin\model\Deptment)->getVariousTree();
        $this->view->assign("deptList",$deptList);
        //操作人
        $adminList[''] = __('ALL');
        $adminLists = model('admin')->column('nickname','id');
        foreach ($adminLists as $key => $value) {
            $adminList[$key] = $value;
        }
        $this->view->assign("adminList",$adminList);

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


            $subSql = model('DeptProRecords')
                        ->where($where)
                        ->order($sort, $order)
                        ->limit($offset, $limit)
                        ->buildSql();


            $total = $this->model
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

            $list = model('DeptProRecords')
                    ->table($subSql . ' DPRecords')
                    ->field('DPRecords.*,ctm.ctm_name,admin.nickname,dept.dept_name')
                    ->join(DB::getTable('customer').' ctm','DPRecords.customer_id = ctm.ctm_id', 'LEFT')
                    ->join(DB::getTable('admin').' admin','DPRecords.admin_id = admin.id', 'LEFT')
                    // ->join(DB::getTable('DeptPro').' deptpro','DPRecords.pro_id = deptpro.id', 'LEFT')
                    ->join(DB::getTable('deptment').' dept','DPRecords.dept_id = dept.dept_id', 'LEFT')
                    ->select();

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }


    public function add()
    {
        $proList = model('dept_pro')->column('pro_name','id');
        $this->view->assign("proList",$proList);

        $deptList = model('deptment')->column('dept_name','dept_id');
        $this->view->assign("deptList",$deptList);

        //领料单号
        if($this->model->select()){
            $num=$this->model->field('max(id) as id')->find();
            $nums = $num['id']+1;
            if(strlen($nums)<6){
                $num=str_pad($nums,6,"0",STR_PAD_LEFT);
            }else{
                $num=$nums;
            }
            $order_num = 'tz'.$num;
            $this->view->assign("order_num", $order_num);
        }else{
            $this->view->assign("order_num", 'tz000001');
        }

        if ($this->request->isAjax()) {
            $params = $this->request->post("row/a");
            $drugs_id = $this->request->post("drugs_id/a");//物品id
            $storage_num = $this->request->post("storage_num/a");//数量

            if(!$drugs_id)
                $this->error('请选择产品！');
            $drugRows = array();
            foreach ($drugs_id as $k => &$v){
                $drugRows[$k]['pro_id'] = $v;
            }
            foreach ($storage_num as $k => &$v){
                $intnum = intval($v);
                $floatnum = floatval($v);
                if($floatnum <1 || $intnum != $floatnum){
                    $this->error('请输入正确的领取数量！');
                }

                $drugRows[$k]['pro_num'] = $v;
            }
            //保存领料单号中的详细信息
            $data = [];
            foreach ($drugRows as $key => $value) {
                $data[] = ['order_num' => $params['order_num'], 'pro_id' => $value['pro_id'], 'pro_num' => $value['pro_num']];
            }

            $params['admin_id'] = \think\Session::get('admin')['id'];

            $result = $this->model->create($params);
            if($result)
            {
                $dataresult = model('dept_pro_info')->saveAll($data);
                if($dataresult){
                    $this->success();
                }
            }else
            {
                $this->error();
            } 
        }
        return $this->view->fetch();
    }

    //选取物品
    public function selectgoods()
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
            $total = model('dept_pro')
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

            $list = model('dept_pro')
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }


    public function edit($ids = null)
    {
        $row = $this->model->find($ids);
        $this->view->assign("row",$row);
        //科室
        $dept = model('deptment')->field('dept_name')->where(['dept_id' => $row['dept_id']])->find();
        $this->view->assign("dept",$dept);
        //客户
        $customer = model('customer')->field('ctm_name')->where(['ctm_id' => $row['customer_id']])->find();
        $this->view->assign("customer",$customer);
        //操作人
        $admin = model('admin')->field('nickname')->where(['id' =>$row['admin_id']])->find();
        $this->view->assign("admin",$admin);
        
        //领料单号
        $order = model('DeptProRecords')->field('order_num')->where(['id' => $ids])->find();
        //物品信息
        $infoList = model('DeptProInfo')->where(['order_num' => $order['order_num']])->order('Id', 'asc')->select();
        $lists = [];
        foreach ($infoList as $k => $v)
        {   
            $lists[$k]['id'] = $v['Id'];
            $lists[$k]['pro_num'] = $v['pro_num'];
            $pro_id = $v['pro_id'];
            $deptList = model('dept_pro')->where('id',$pro_id)->select();
            $lists[$k]['pro_unit'] = $deptList[0]['pro_unit'];
            $lists[$k]['pro_name'] = $deptList[0]['pro_name'];
        }
        $this->view->assign("lists",$lists);
        return $this->view->fetch();

    }

    public function del($ids = "")
    {
        $this->error(__('Records can not be delete!'));
    }

    public function multi($ids = "")
    {
        $this->error(__('Access denied!'));
    }

}
