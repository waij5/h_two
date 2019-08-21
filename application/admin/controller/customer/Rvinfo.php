<?php

namespace app\admin\controller\customer;

use app\common\controller\Backend;
use think\Controller;
use think\Db;
use think\Request;
use \app\admin\model\RevisitFilter;
use app\admin\model\Gender;
use app\admin\model\Job;
use app\admin\model\ArriveStatus;



/**
 * 客户回访
 *
 * @icon fa fa-circle-o
 */
class Rvinfo extends Backend
{
    protected $noNeedLogin = ['todayrevisitnotices', 'addplaninfos', 'quickedit', ];
    /**
     * Rvinfo模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Rvinfo');

        $this->view->assign('rvStatusList', ['' => __('rv_status_none'), '0' => __('rv_status_0'), '1' => __('rv_status_1')]);
    }

    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个方法
     * 因此在当前控制器中可不用编写增删改查的代码,如果需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    public function index()
    {
        $admin = \think\Session::get('admin');
        //顾虑点
        $fatLists = model('filter')->field('fat_id,fat_name')->select();
        $fatList = ['' => __('NONE')];
        foreach ($fatLists as $value) {
            $fatList[$value['fat_id']] = $value['fat_name'];
        }
        //回访类型
        $typeList = model('rvtype')->column('rvt_name', 'rvt_id');
        $this->view->assign('fatList', $fatList);
        $this->view->assign('typeList', $typeList);
        
        $briefAdminList = model('Admin')->getBriefAdminList2();
        $this->view->assign("briefAdminList", $briefAdminList);

        if ($this->request->isAjax()) {

            list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null);

            //如果不是超管进行筛选
            $isSuperAdmin = $this->auth->isSuperAdmin();
            $bwhere = [];
            //不是超管就只能看到自己的回访记录
            // if(!$isSuperAdmin){
            //     $bwhere = ['a.admin_id' => $admin['id']];
            // }
            
            // 如果不是超管，但是主任，部门权限有全部则可以看到下属员工的回访记录
            if (!$isSuperAdmin) {
                $bwhere['a.admin_id'] = [
                    'exp',
                    'in ' . $this->deptAuth->getAdminCondition($fields = 'id', $admin['id']),
                ];
            }

            $filter = $this->request->get("filter", '');
            $filter = json_decode($filter, TRUE);
            
            if (empty($filter) && empty($bwhere)) {
                $total = model('rvinfo')->alias('a')->count();
            } else {
                $total = model('rvinfo')->alias('a')
                    ->join(DB::getTable('customer') . ' b', 'a.customer_id = b.ctm_id', 'LEFT')
                    ->join(DB::getTable('admin') . ' c', 'a.admin_id = c.id', 'LEFT')
                    ->join(DB::getTable('fat') . ' d', 'a.fat_id = d.fat_id', 'LEFT')
                    ->where($where)
                    ->where($bwhere)
                    ->count();
            }
            
                    
            $list  = model('rvinfo')->alias('a')
                ->field('a.*, b.ctm_name, b.ctm_mobile, b.ctm_id, c.nickname, d.fat_name')
                ->join(DB::getTable('customer') . ' b', 'a.customer_id = b.ctm_id', 'LEFT')
                ->join(DB::getTable('admin') . ' c', 'a.admin_id = c.id', 'LEFT')
                ->join(DB::getTable('fat') . ' d', 'a.fat_id = d.fat_id', 'LEFT')
                ->where($where)
                ->where($bwhere)
                // ->order('rvi_id', 'DESC')
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            if (!$this->auth->isSuperAdmin()) {
                foreach ($list as $key => $row) {
                    $list[$key]['rvi_tel'] = getMaskString($list[$key]['rvi_tel']);
                }
            }
            

            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }

        return $this->view->fetch();
    }

    public function add($id = null)
    {
        $id = input('ctm_id');
        if (empty($id)) {
            $id = 1;
        }
        // $info = model('customer')->where(['ctm_id' => $id])->column('ctm_name', 'ctm_mobile');
        $info = model('customer')->field('ctm_name,ctm_mobile,ctm_tel')->where(['ctm_id' => $id])->find();

        $ctm_mobile = $info['ctm_mobile'];
        if(empty($info['ctm_mobile'])){
            $ctm_mobile = $info['ctm_tel'];
        }
        $ctm_name = $info['ctm_name'];
        // foreach ($info as $k => $v) {
        //     $ctm_mobile  = $k;
        //     $ctm_name = $v;
        // }
        $typeList = model('rvtype')->column('rvt_name', 'rvt_id');

        //顾虑点
        $fatLists = model('filter')->field('fat_id,fat_name')->select();
        $fatList = ['' => __('NONE')];
        foreach ($fatLists as $value) {
            $fatList[$value['fat_id']] = $value['fat_name'];
        }

        $briefAdminList = model('Admin')->getBriefAdminList2();
        $this->view->assign("briefAdminList", $briefAdminList);

        $this->view->assign('fatList', $fatList);
        $this->view->assign('ctm_mobile', $ctm_mobile);
        $this->view->assign('info', $info);
        $this->view->assign('ctm_id', $id);
        $this->view->assign('ctm_name', $ctm_name);
        $this->view->assign('typeList', $typeList);

        if ($this->request->post()) {
            $params = $this->request->post("row/a");
            if (empty($params['rvi_tel'])) {
                $this->error(__('Parameter %s can not be empty', '电话号码'));
            }
            //回访人员
            $admin = \think\Session::get('admin');
            if(empty($params['admin_id'])){
                $params['admin_id'] = $admin->id;
            }

            $rvt_id   = $params['rvt_name'];
            $rvt_type = model('rvtype')->where(['rvt_id' => $rvt_id])->column('rvt_name');

            $dataset                = [];
            $dataset['rvi_tel']     = $params['rvi_tel'];
            $dataset['customer_id'] = $id;
            $dataset['rvt_type']    = $rvt_type['0'];
            $dataset['rvi_content'] = $params['rvi_content'];
            $dataset['admin_id']    = $params['admin_id'];
            $dataset['fat_id']      = $params['fat_id'];
            $dataset['rv_date']     = date('Y-m-d');
            $dataset['rv_time']     = time();
            $dataset['rv_is_valid'] = RevisitFilter::isContentValid($dataset['rvi_content']);

            $result = $this->model->save($dataset);

            //下次回访时间
            //unix_timestamp()在mysql中将date转为时间戳
            $time = strtotime(date('Y-m-d 23:59:59'));
            // $nextRvinfo = model('rvinfo')->where('unix_timestamp(rv_date) > '.$time)->where(['customer_id' => $id])->where('rv_time IS NULL')->order('rv_date', 'ASC')->find();
            $nextRvinfo = model('rvinfo')->where('rv_date', '>=', date('Y-m-d'))->where(['customer_id' => $id])->where('rv_time IS NULL')->order('rv_date', 'ASC')->find();
            if($nextRvinfo) {
                $customerModel = model('customer');
                $customerModel->save([
                    'ctm_next_rvinfo' => $nextRvinfo->rv_date,
                    ],
                    ['ctm_id' => $id]
                );
            }
                
            if ($result) {
                \think\Hook::listen('rvinfo_save', $this->model);

                $this->success();
            } else {
                $this->error();
            }
        }

        return $this->view->fetch();
    }

    public function edit($ids = null)
    {
        $row = $this->model->get(['rvi_id' => $ids]);
        if (!$row) {
            $this->error(__('No Results were found'));
        }

        $customer = model('Customer')->get(['ctm_id' => $row['customer_id']]);
        $genderList = Gender::getList();
        $ctmSource = model('Ctmsource')->field('sce_id, sce_name')->where(['sce_status' => 1])->order('sce_sort', 'desc')->select();
        $ctmSrcList = ['' => __('NONE')];
        foreach ($ctmSource as $source) {
            $ctmSrcList[$source['sce_id']] = $source['sce_name'];
        }
        $channelLists = model('Ctmchannels')->field('chn_id, chn_name')->where(['chn_status' => 1])->order('chn_sort', 'desc')->select();
        $channelList = ['' => __('NONE')];
        foreach ($channelLists as $source) {
            $channelList[$source['chn_id']] = $source['chn_name'];
        }
        $ctmtypeLists = model('customertype')->field('type_id,type_name')->select();
        $ctmtypeList  = ['' => __('NONE')];
        foreach ($ctmtypeLists as $ctmtype) {
            $ctmtypeList[$ctmtype['type_id']] = $ctmtype['type_name'];
        }
        
        $rec_id   = $customer['rec_customer_id'];
        // $rec_name = model('customer')->field('ctm_name')->where(['ctm_id' => $rec_id])->find();
        // if (empty($rec_name)) {
        //     $rec_name['ctm_name'] = '无';
        // }
        //推荐人
        $recCustomer     = model('customer')->field('ctm_name,ctm_id')->where(['ctm_id' => $rec_id])->find();
        $recCustomerName = __('None');
        if (!empty($recCustomer)) {
            $recCustomerName = $recCustomer->ctm_id.'--'.$recCustomer->ctm_name;
        }


        $definedRvPlans = model('Rvplan')->where(['rvp_status' => 1])->column('rvp_name', 'rvp_id');
        $this->view->assign('definedRvPlans', $definedRvPlans);
        $this->view->assign('jobList', Job::getList());
        $this->view->assign("recCustomerName", $recCustomerName);
        $this->view->assign("ctmtypeList", $ctmtypeList);
        $this->view->assign('channelList', $channelList);
        $this->view->assign('ctmSrcList', $ctmSrcList);
        $this->view->assign('genderList', $genderList);
        $this->view->assign('customer', $customer);

        $adminid = \think\Session::get('admin')['id'];

        //判断是否是当前回访人员的上级
        $is_true = $this->deptAuth->checkAuth($adminid);
        if(!$is_true){
            if($adminid != $row['admin_id']){
                $this->error(__('该条回访计划不是你添加的'));
            }
        }
        
        //回访记录当天的可以修改
        // $startDate = strtotime(date('Y-m-d'));
        // $endDate   = strtotime(date('Y-m-d 23:59:59'));

        // if(!empty($row['rv_time'])){
        //     if($row['rv_time'] < $startDate || $row['rv_time'] > $endDate){
        //         $this->error(__('回访记录不能更改'));
        //     }
        // }
        //回访记录仅仅超管可以编辑修改
        if(!$this->auth->isSuperAdmin() && $row->rv_time > 0){
            $this->error(__('回访记录不能更改'));
        }

        $this->view->assign("row", $row);

        $info = model('customer')->field('ctm_name,ctm_mobile,ctm_tel')->where(['ctm_id' =>  $row['customer_id']])->find();
        $ctm_mobile = $info['ctm_mobile'];
        if(empty($info['ctm_mobile'])){
            $ctm_mobile = $info['ctm_tel'];
        }
        $ctm_name = $info['ctm_name'];

        $typeList = model('rvtype')->column('rvt_name', 'rvt_id');
        //顾虑点
        $fatLists = model('filter')->field('fat_id,fat_name')->select();
        $fatList = ['' => __('NONE')];
        foreach ($fatLists as $value) {
            $fatList[$value['fat_id']] = $value['fat_name'];
        }

        // $this->view->assign('ctm_tel', $ctm_tel);
        $this->view->assign('ctm_name', $ctm_name);
        $this->view->assign('typeList', $typeList);
        $this->view->assign('fatList', $fatList);

        if ($this->request->post()) {
            $params = $this->request->post("row/a");
            if (empty($params['rvi_tel'])) {
                $this->error(__('Parameter %s can not be empty', '电话号码'));
            }

            $dataset                = [];
            $dataset['rvi_content'] = $params['rvi_content'];
            $dataset['fat_id']      = $params['fat_id'];
            $dataset['rv_time']     = time();
            $dataset['rv_is_valid'] = RevisitFilter::isContentValid($dataset['rvi_content']);

            $result = $row->save($dataset);
            if ($result) {
                \think\Hook::listen('rvinfo_save', $row);
                $this->success();
            } else {
                $this->error();
            }
        }

        $mode = input('mode', 0);
        if ($mode) {
            return $this->view->fetch('customer/rvinfo/editrvinfo');
        } else {
            return $this->view->fetch();
        }
        
    }

    public function search($ids = null)
    {
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null);

            $total = model('rvinfo')->where(['customer_id' => $ids])->where($where)->count();
            $list = model('Rvinfo')->getList([], $sort, $order, $offset, $limit, ['rvinfo.customer_id' => $ids], $this->auth->isSuperAdmin());
            // $list  = model('rvinfo')->alias('a')
            // // b.ctm_name,
            //     ->field('a.*,c.nickname,d.fat_name')
            //     // ->join(DB::getTable('customer') . ' b', 'a.customer_id = b.ctm_id', 'LEFT')
            //     ->join(DB::getTable('admin') . ' c', 'a.admin_id = c.id', 'LEFT')
            //     ->join(DB::getTable('fat') . ' d', 'a.fat_id = d.fat_id', 'LEFT')
            //     ->where(['a.customer_id' => $ids])
            //     ->where($where)
            //     ->order($sort, $order)
            //     ->limit($offset, $limit)
            //     ->select();
            // select a.*,b.ctm_name,c.nickname from yjy_rvinfo as a left join yjy_customer as b on a.customer_id = b.ctm_id left join yjy_admin as c on a.admin_id = c.id where a.customer_id = $ids

            $fatList = model('Fat')->where(['status' => 1])->order('sort DESC, fat_id ', 'ASC')->cache("__fat_list__")->column('fat_name, fat_code, remark', 'fat_id');

            $result = array("total" => $total, "rows" => $list, 'fatList' => $fatList);

            return json($result);
        }
    }

    public function quickedit($ids = '')
    {
        $rvinfo = $this->model->where(['rvi_id' => $ids])->find();
        if (!$rvinfo) {
            $this->error(__('No results were found'));
        }
        $customer_id = $rvinfo->customer_id;

        $admin = \think\Session::get('admin');
        if (empty($admin) || ((!$this->auth->isSuperAdmin() && ($rvinfo->admin_id != $admin->id || $rvinfo->rv_time > 0)))) {
            $this->error(__('无法保存回访，没有权限或已完成的回访'));
        } else {
            $rviContent = strip_tags(input('rvi_content', ''));
            $rvData = [
                'rvi_content' => $rviContent,
                'fat_id' => input('fat_id', null),
                'rv_time' => time(),
                'rv_is_valid' => RevisitFilter::isContentValid($rviContent),
            ];
            if ($rvinfo->save($rvData)) {
                \think\Hook::listen('rvinfo_save', $rvinfo);


            //下次回访时间
            //unix_timestamp()在mysql中将date转为时间戳
            $time = strtotime(date('Y-m-d 23:59:59'));
            // $nextRvinfo = model('rvinfo')->where('unix_timestamp(rv_date) > '.$time)->where(['customer_id' => $customer_id])->where('rv_time IS NULL')->order('rv_date', 'ASC')->find();
            $nextRvinfo = model('rvinfo')->where('rv_date', '>=', date('Y-m-d'))->where(['customer_id' => $customer_id])->where('rv_time IS NULL')->order('rv_date', 'ASC')->find();
            if($nextRvinfo) {
                $customerModel = model('customer');
                $customerModel->save([
                    'ctm_next_rvinfo' => $nextRvinfo->rv_date,
                    ],
                    ['ctm_id' => $customer_id]
                );
            }
            

                 $this->success(__('Operation completed'), null, ['rvinfo' => ($rvinfo), 'canEdit' => $this->auth->isSuperAdmin() ? 1 : 0]);
            } else {
                $this->error(__('Error occurs'));
            }
        }

        $this->error(__('Error occurs'));
    }

    /**
     * 通过预置回访记录 添加 回访
     */
    public function addplaninfos($planId = null, $customerId = null)
    {
        $rvPlan = model('Rvplan')->find($planId);
        $customer = model('customer')->find($customerId);
        $admin = \think\Session::get('admin');

        if (empty($customer) || empty($rvPlan)) {
            $this->error('Operation failed');
        } else {
            if (empty($customer->ctm_mobile)) {
                $this->error();
            }
            $rvPlan->generatePlanInfos($customer, $admin->id);

            //下次回访时间
            //unix_timestamp()在mysql中将date转为时间戳
            $time = strtotime(date('Y-m-d 23:59:59'));
            // 'unix_timestamp(rv_date) > '.$time
            // $nextRvinfo = model('rvinfo')->where('unix_timestamp(rv_date) > '.$time)->where(['customer_id' => $customerId])->where('rv_time IS NULL')->order('rv_date', 'ASC')->find();
            $nextRvinfo = model('rvinfo')->where('rv_date', '>=', date('Y-m-d'))->where(['customer_id' => $customerId])->where('rv_time IS NULL')->order('rv_date', 'ASC')->find();
            if($nextRvinfo) {
                $customerModel = model('customer');
                $customerModel->save([
                    'ctm_next_rvinfo' => $nextRvinfo->rv_date,
                    ],
                    ['ctm_id' => $customerId]
                );
            }

            $this->success();
        }

    }

    /**
     * 今日回访信息提警
     */
    public function todayrevisitnotices()
    {
        //客服科室
        // $deptments = model('deptment')->field('dept_id,dept_name')->where(['dept_status' => 1])->select();
        // $deptList  = ['' => __('NONE')];
        // foreach ($deptments as $deptment) {
        //     $deptList[$deptment['dept_id']] = $deptment['dept_name'];
        // }
        $deptList = (new \app\admin\model\Deptment)->getVariousTree();
        $this->view->assign('deptList', $deptList);
        //上门状态
        $ArriveStatus = ArriveStatus::getList();
        $this->view->assign('ArriveStatus', $ArriveStatus);

        //回访类型
        $typeList = model('rvtype')->column('rvt_name', 'rvt_id');
        $this->view->assign('typeList', $typeList);
        
        //首次客服项目
        $pducats  = model('CProject')->field('id, cpdt_name')->where(['cpdt_status' => 1])->order('id', 'ASC')->select();
        $cpdtList = ['' => __('NONE')];
        foreach ($pducats as $pducat) {
            $cpdtList[$pducat['id']] = $pducat['cpdt_name'];
        }
        $this->view->assign('cpdtList', $cpdtList);
         //顾虑点
        $fatLists = model('filter')->field('fat_id,fat_name')->select();
        $fatList = ['' => __('NONE')];
        foreach ($fatLists as $value) {
            $fatList[$value['fat_id']] = $value['fat_name'];
        }
        $this->view->assign('fatList', $fatList);

        $toolList = \app\admin\model\CocAcceptTool::getList();
        $this->view->assign('toolList', $toolList);

        $startDate     = date('Y-m-d');
        $endDate       = date('Y-m-d');
        $this->view->assign("startDate", $startDate);
        $this->view->assign("endDate", $endDate);

        $briefAdminList = model('Admin')->getBriefAdminList2();
        $this->view->assign("briefAdminList", $briefAdminList);

        $ctmSource  = model('Ctmsource')->field('sce_id, sce_name')->where(['sce_status' => 1])->order('sce_sort', 'desc')->select();
        $ctmSrcList = ['' => __('NONE')];
        foreach ($ctmSource as $source) {
            $ctmSrcList[$source['sce_id']] = $source['sce_name'];
        }
        $this->view->assign('ctmSrcList', $ctmSrcList);
        //营销渠道
        $channelLists = model('Ctmchannels')->field('chn_id, chn_name')->where(['chn_status' => 1])->order('chn_sort', 'desc')->select();
        $channelList  = ['' => __('NONE')];
        foreach ($channelLists as $source) {
            $channelList[$source['chn_id']] = $source['chn_name'];
        }
        $this->view->assign('channelList', $channelList);

        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null, false);

            $bWhere     = [];
            $extraWhere = [];
            foreach ($where as $key => $value) {
                if (strpos($value[0], '.') === false) {
                    $bWhere[$value[0]] = [$value[1], $value[2]];
                } else {
                    $extraWhere[$value[0]] = [$value[1], $value[2]];
                }
            }
            
            $admin = \think\Session::get('admin');
            $sort = 'rvinfo.rvi_id';
            if (!$this->auth->isSuperAdmin()) {
                $bWhere = array_merge([
                    'admin_id' => ['exp', 'in ' . $this->deptAuth->getAdminCondition($fields = 'id', $admin['id'])]],
                    $bWhere);
            }
             // 首次查询时显示当日
            if (!isset($bWhere['notOnlyUseToday'])) {
                $bWhere['rv_date'] = ['BETWEEN', [$startDate, $endDate]];
            } else {
                unset($bWhere['notOnlyUseToday']);
            }

            if (isset($bWhere['onlyNoneRevisit'])) {
                if ($bWhere['onlyNoneRevisit'][1] == true) {
                    $bWhere['rv_time'] = ['null', true];
                }
                unset($bWhere['onlyNoneRevisit']);
            }

            $total = model('Rvinfo')->getListCount($bWhere, $extraWhere);
            $list = model('Rvinfo')->getList($bWhere, $sort, $order, $offset, $limit, $extraWhere, $this->auth->isSuperAdmin());
            $fatList = model('Fat')->where(['status' => 1])->order('sort DESC, fat_id ', 'ASC')->cache("__fat_list__")->column('fat_name, fat_code, remark', 'fat_id');
            
            $result = array("total" => $total, "rows" => $list, 'fatList' => $fatList);

            return json($result);
        }

        return $this->view->fetch();
    }
}
