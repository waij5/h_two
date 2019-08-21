<?php

namespace app\api\controller;

use app\common\controller\Api;
use think\Controller;
use think\Session;
use think\Request;
use app\admin\model\CustomerConsult as MCustomerconsult;
use app\admin\model\Admin;
use app\admin\model\Customerosconsult;
use app\admin\model\Customer;
use app\admin\model\Customertype;
use app\admin\model\Ctmchannels;
use app\admin\model\Ctmsource;
use app\admin\model\Cproject;
use app\admin\model\Deptment;
use app\admin\model\Chntype;
use app\admin\model\CocAccepttool;
use app\admin\model\Fat;
use app\admin\model\ArriveStatus;
use app\admin\model\Rvinfo;
use app\admin\model\Rvplan;
use app\admin\model\Rvtype;
use think\Db;


/**
 *
 *
 * @icon fa fa-circle-o
 */
class Customerconsult extends Api
{
    protected $searchFields = 'cst_id';
    
	public function index(){

		// $adminId = 1;
        $admin = Request::instance()->admin;

        $adminId = $admin->id;

		$this->model = new MCustomerconsult;
		$adminM = new Admin;
		$briefAdminList = $adminM->getBriefAdminList2();

		// if ($this->request->isAjax()) {
	    list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null, false);

        $bWhere     = [];
        $extraWhere = [];
        foreach ($where as $key => $value) {
            $bWhere[$value[0]] = [$value[1], $value[2]];
        }
 

        // $bWhere     = [];
        // $bWhere['cst.admin_id'] = $adminId;


        // $extraWhere = [];
        // foreach ($where as $key => $value) {
        //     $bWhere[$value[0]] = [$value[1], $value[2]];
        // }

        if (isset($bWhere['customer.ctm_birthdate'])) {
            $ageStart = $bWhere['customer.ctm_birthdate'][1][0];
            $ageEnd   = $bWhere['customer.ctm_birthdate'][1][1] + 1;
            $bigAge   = getBirthDate($ageStart);
            $smallAge = getBirthDate($ageEnd);

            $bWhere['customer.ctm_birthdate'][1][0] = $smallAge;
            $bWhere['customer.ctm_birthdate'][1][1] = $bigAge;
        }

        //营销部门搜索(上级部门可以显示下级部门数据)
        $developAdminFlg = false;
        $developAdminCon = $adminM->field('id');
        if (isset($bWhere['admin.dept_id'])) {
            $developAdminFlg  = true;
            $deptTree         = \app\admin\model\Deptment::getDeptTreeCache();
            $allSelectedDepts = $deptTree->getChildrenIds($bWhere['admin.dept_id'][1], true);
            $developAdminCon  = $developAdminCon->where(['dept_id' => ['in', $allSelectedDepts]]);
        }
        if (!empty($bWhere['customer.admin_id'])) {
            $developAdminFlg = true;
            $developAdminCon = $developAdminCon->where(['id' => $bWhere['customer.admin_id']]);
        }
        if ($developAdminFlg) {
            $bWhere['customer.admin_id'] = ['exp', 'in ' . $developAdminCon->buildSql()];
        }
        if (isset($bWhere['admin.dept_id'])) {
            unset($bWhere['admin.dept_id']);
        }

        // // 是否是超级管理员
        // $admin      = \think\Session::get('admin');
        // $superadmin = $this->auth->isSuperAdmin();
        // if ($superadmin) {
        //     //额外加入条件， 排除自身已拒绝的
        //     $extraWhere['cst.status'] = 1;
        // } else {
        //     //只显示自己的记录，其他人员的不显示
        //     $extraWhere['cst.admin_id'] = ['exp', 'in ' . $this->deptAuth->getAdminCondition($fields = 'id', $admin['id'])];
        //     $extraWhere['cst.status']   = 1;
        // }
        

        $startDate = 0;// input('cst.createtime_start', strtotime(date('Y-m-d')));
        $endDate   = input('cst.createtime_end', strtotime(date('Y-m-d 23:59:59')));
        // 首次查询时显示当日
        $timewhere = [];
        if (!isset($_GET['op'])) {
            $bWhere['cst.createtime'] = ['BETWEEN', [$startDate, $endDate]];
        }

        $total = $this->model->getListCount($bWhere, $extraWhere);
        $list  = $this->model->getList($bWhere, $sort, $order, $offset, $limit, $extraWhere);

        foreach ($list as $key => $row) {
            $list[$key]['develop_staff_name'] = '自然到诊';
            if (isset($briefAdminList[$row['develop_admin_id']])) {
                $list[$key]['develop_staff_name'] = $briefAdminList[$row['develop_admin_id']];
            }
        }
        

        // $result = array("total" => $total, "rows" => $list);

        // return json($result);
         $this->success('成功', null, [ 'total' => $total, 'list' => $list]);       
	}


    public function judge(){
        $admin = Request::instance()->admin;
        $adminId = $admin->id;

        // $phone = 12345678901;
        // $customerId = 1;
        $phone      = trim(input('phone', ''));
        $customerId = input('customer_id', false);

        $customerM = new Customer;
        $this->model = new MCustomerConsult;

        $customerCount = $customerM->where(['ctm_mobile' => ['like', "%$phone%"]])->whereOr(['ctm_tel' => ['like', "%$phone%"]])->count();

        $url = 'customer/customerconsult/add';
        if ($customerCount == 0) {
            //未查找任意记录,直接跳转新增
            // return redirect('customer/customerconsult/add', ['phone' => $phone, 'dialog' => 1]);
             $this->success('成功', null, ['url' => $url, 'phone' => $phone]);
        }else{
             $consultList = $this->model->alias('cst')
            ->field('cst.*, fat.fat_name, admin.nickname as admin_nickname, admin.dept_id as cst_admin_dept_id, cst.dept_id,  customer.ctm_name, customer.ctm_mobile, customer.arrive_status, customer.ctm_addr, cproject.cpdt_name,customer.ctm_id, customer.admin_id as develop_admin_id, sce.sce_name as ctm_source, channels.chn_name as ctm_explore,
                customer.ctm_last_osc_dept_id as coc_dept_id, customer.ctm_last_recept_time as coctime, customer.ctm_last_osc_admin as coc_admin_id' 
                    )
            ->join(Db::getTable('admin') . ' admin', 'cst.admin_id=admin.id', 'LEFT')
            ->join(Db::getTable('fat') . ' fat', 'cst.fat_id=fat.fat_id', 'LEFT')
            ->join(Db::getTable('customer') . ' customer', 'cst.customer_id=customer.ctm_id', 'LEFT')
            ->join(Db::getTable('c_project') . ' cproject', 'cst.cpdt_id=cproject.id', 'LEFT')
            ->join(Db::getTable('ctmsource') . ' sce', 'customer.ctm_source=sce.sce_id', 'LEFT')
            ->join(Db::getTable('ctmchannels') . ' channels', 'customer.ctm_explore=channels.chn_id', 'LEFT')
            ->where(['customer.ctm_mobile' => ['like', "%$phone%"]])
            ->whereOr(['customer.ctm_tel' => ['like', "%$phone%"]])
            ->find();
            // $customer = $customerM->where(['ctm_mobile' => ['like', "%$phone%"]])->whereOr(['ctm_tel' => ['like', "%$phone%"]])->find();
            // $consultList = $this->model->where(['customer_id' => $customer->ctm_id])->find();
            //有记录,判断登陆admin_id与录入信息的admin_id是否相同,以及上一条记录的客服受理人员
            if($consultList){
                if($adminId == $consultList->develop_admin_id || $adminId == $consultList->admin_id){
                    $this->success('成功', null, ['url' => $url, 'phone' => $phone, 'customer_id' => $consultList->ctm_id]);
                }
            }else{
                 $this->success('成功', null, ['url' => $url, 'phone' => $phone]);
            }
        }
    }

   public function create(){
        $phone      = trim(input('phone', ''));
        $customerId = input('customer_id', false);

        $urlParams           = [];
        $urlParams['dialog'] = true;
        $urlParams['force']  = true;
        if ($phone) {
            $urlParams['phone'] = $phone;
        }

        $Superadmin = $this->auth->isSuperAdmin();
        $force = input('force', false);
        $iftrue = ($force && $Superadmin);

        $AdminM = new Admin;
        $customerM = new Customer;
        $briefAdminList = $AdminM->getBriefAdminList2();
        $toolList = \app\admin\model\CocAccepttool::getList();

        $showWarnings    = false;
        $warnings        = [];
        $customerAdminid = false;
        if (!empty($customerId)) {
            $customer = $customerM->get(['ctm_id' => $customerId]);
            if (empty($customer->ctm_id)) {
                $this->error(__('Customer does not exist.', [$customerId]));
            }
            //如果当前登陆人员就是客户的营销人员
            $admin = Request::instance()->admin;
            if ($admin->id == $customer['admin_id']) {
                $customerAdminid = true;
                if ($force) {
                    $iftrue = true;
                }
            }

            $urlParams['customer_id'] = $customer['ctm_id'];
            $intervalDays             = \think\Config::get('site.consult_interval_days');
            $arriveIntervalDays       = \think\Config::get('site.consult_arrive_interval_days');

            if (!$iftrue) {
                //顾客已上门 -1无限制， 0全限制
                if ($arriveIntervalDays > -1) {
                    if ($arriveIntervalDays == 0) {
                        if ($customer->arrive_status) {
                            $showWarnings = true;
                            array_push($warnings, __('Customer %s(ID:%s) has arrived', [$customer->ctm_name, $customer->ctm_id]));
                        }
                    } else {
                        $searchArriveStartTime = strtotime('-' . $arriveIntervalDays . ' days', strtotime(date('Y-m-d')));

                        if ($customer->ctm_last_recept_time > $searchArriveStartTime) {
                            $showWarnings = true;
                            array_push($warnings, __('Customer %s(ID:%s) has arrived', [$customer->ctm_name, $customer->ctm_id]));
                        }
                    }
                }

                if ($intervalDays) {
                    $searchStartTime = strtotime('-' . $intervalDays . ' days', strtotime(date('Y-m-d')));
                    $CustomerConsultM = new MCustomerconsult;
                    $cstSubSql = $CustomerConsultM
                        ->where([
                            'status'      => 1,
                            'createtime'  => ['gt', $searchStartTime],
                            'customer_id' => $customer->ctm_id,
                        ])
                        ->order('createtime', 'DESC')
                        ->limit(1)
                        ->buildSql();

                    $customerPreCon = $CustomerConsultM
                        ->table($cstSubSql)
                        ->alias('cst')
                        ->field('cst.*, customer.ctm_name, customer.arrive_status, customer.ctm_first_tool_id, cpdt.cpdt_name, customer.ctm_last_osc_admin')
                        ->join(DB::getTable('Customer') . ' customer', 'cst.customer_id = customer.ctm_id', 'LEFT')
                        ->join(DB::getTable('CProject') . ' cpdt', 'cst.cpdt_id = cpdt.id', 'LEFT')
                        ->find();

                    if (!empty($customerPreCon)) {
                        $showWarnings = true;
                        array_push($warnings, __('Consult of this customer has existed(validate days: %s)', $intervalDays));
                        $canEditPreCon = $this->checkDeptAuth($customerPreCon['admin_id'], false);

                        if (isset($briefAdminList[$customerPreCon['admin_id']])) {
                            $customerPreCon['developStaffName'] = $briefAdminList[$customerPreCon['admin_id']];
                        } else {
                            $customerPreCon['developStaffName'] = __('Natural diagnosis');
                        }

                        if ($customerPreCon->arrive_status == 1) {
                            if (isset($briefAdminList[$customerPreCon['ctm_last_osc_admin']])) {
                                $customerPreCon['coc_admin_id'] = $briefAdminList[$customerPreCon['ctm_last_osc_admin']];
                            } else {
                                $customerPreCon['coc_admin_id'] = __('Natural diagnosis');
                            }
                        } else {
                            $customerPreCon['coc_admin_id'] = '';
                        }

                        if (isset($toolList[$customerPreCon['ctm_first_tool_id']])) {
                            $customerPreCon['ctm_first_tool_id'] = $toolList[$customerPreCon['ctm_first_tool_id']];
                        } else {
                            $customerPreCon['ctm_first_tool_id'] = '';
                        }

                        $this->view->assign('canEditPreCon', $canEditPreCon);
                        $this->view->assign('customerPreCon', $customerPreCon);
                    }
                }

                if ($showWarnings) {
                    $canEditPreCon = $this->checkDeptAuth($customerPreCon['admin_id'], false);
                    $forceUrl      = url('customer/customerconsult/add', $urlParams);
                    $this->success('成功', null , [
                        'customerAdminid' => $customerAdminid,
                        'forceUrl' => $forceUrl,
                        'showWarnings' => $showWarnings,
                        'warnings' => $warnings,
                        ]);
                }
            }

            //顾客图片
            $CustomerImgM = new CustomerImg;
            $customerImgs = $CustomerImgM->where(['customer_id' => $customer->ctm_id])->order('weigh', 'DESC')->select();
        } else {
            $customer =  $customerM->fade(['ctm_mobile' => $phone, 'ctm_tel' => $phone]);
        }

        //客服项目
        $CProjectM = new CProject;
        $cprolists = $CProjectM->field('id,cpdt_name')->where(['cpdt_status' => 1])->select();
        $List      = ['' => __('NONE')];
        foreach ($cprolists as $cprolist) {
            $List[$cprolist['id']] = $cprolist['cpdt_name'];
        }
        
        //推荐人
        $rec_id   = $customer['rec_customer_id'];
        $rec_name = $customerM->field('ctm_name')->where(['ctm_id' => $rec_id])->find();
        if (empty($rec_name)) {
            $rec_name['ctm_name'] = '无';
        }

        //默认预约时间在2小时后
        $bookTime = date('Y-m-d H:i:00', strtotime('+2 hours', time()));
        $this->customerExtraInit();

        if (empty($customerPreCon)) {
            //回访类型
            $rvtypeM = new rvtype;
            $rvTypeList = $rvtypeM->column('rvt_name', 'rvt_id');
            // $this->view->assign('rvTypeList', json_encode($rvTypeList));
            $rvTypeSelect = '\'<select name="rvplan[rvt_type][]" class="form-control">';
            foreach ($rvTypeList as $key => $value) {
                $rvTypeSelect .= '<option value="' . $value . '">' . $value . '</option>';
            }
            $rvTypeSelect .= '</select>\'';
        }
        $RvplanM = new Rvplan;
        $definedRvPlans = $RvplanM->where(['rvp_status' => 1])->column('rvp_name', 'rvp_id');
        $forceUrl = url('customer/customerconsult/add', $urlParams);
        $cocTypeList = \app\admin\model\CocAccepttool::getList();
        $Chntype = \app\admin\model\Chntype::getList();
        $admin = Request::instance()->admin;

          $this->success('成功', null, [ 
            'definedRvPlans' => $definedRvPlans,
             'cocTypeList'   => $cocTypeList,
             'Chntype'       => $Chntype,
             'admin'         => $admin,
             'forceUrl'      => $forceUrl,
             'showWarnings'  => $showWarnings,
             'warnings'      => $warnings,
             'toolList'      => $toolList,
             'iftrue'        => $iftrue,
             'Superadmin'    => $Superadmin,
             'rec_name'      => $rec_name,
             'List'          => $List,
             'customer'      => $customer,
             'bookTime'      => $bookTime,
             'rvTypeSelect'  => $rvTypeSelect,
             'customerImgs'  => $customerImgs,
             ]); 

   }

    public function settings(){
        // $phone = 12345678901;
        $phone      = trim(input('phone', ''));
        $customerM = new Customer;
        $this->model = new MCustomerConsult;

        //职员
        $adminM = new Admin;
        $briefAdminList = $adminM->getBriefAdminList2();
        //客户类型
        $customertypeM = new Customertype;
        $ctmtypeLists = $customertypeM->field('type_id,type_name')->select();
        $ctmtypeList  = ['' => __('NONE')];
        foreach ($ctmtypeLists as $ctmtype) {
            $ctmtypeList[$ctmtype['type_id']] = $ctmtype['type_name'];
        }
        //营销渠道
        $CtmchannelsM = new Ctmchannels;
        $channelLists = $CtmchannelsM->field('chn_id, chn_name')->where(['chn_status' => 1])->order('chn_sort', 'desc')->select();
        $channelList  = ['' => __('NONE')];
        foreach ($channelLists as $source) {
            $channelList[$source['chn_id']] = $source['chn_name'];
        }
       //客户来源
        $CtmsourceM = new Ctmsource;
        $ctmSource  = $CtmsourceM->field('sce_id, sce_name')->where(['sce_status' => 1])->order('sce_sort', 'desc')->select();
        $ctmSrcList = ['' => __('NONE')];
        foreach ($ctmSource as $ctmSrc) {
            $ctmSrcList[$ctmSrc['sce_id']] = $ctmSrc['sce_name'];
        }
        //客服项目
        $cprojectM = new Cproject;
        $cproLists = $cprojectM->field('id,cpdt_name')->where(['cpdt_status' => 1])->select();
        $cproList  = ['' => __('NONE')];
        foreach ($cproLists as $cpro) {
            $cproList[$cpro['id']] = $cpro['cpdt_name'];
        }
        //客服科室
        $deptList = \app\admin\model\Deptment::getDeptListCache();
        // 受理类型
        $typeList = \app\admin\model\Chntype::getList();
        //受理工具
        $toolList = \app\admin\model\CocAccepttool::getList();
        //未成交原因
        $fatM = new Fat;
        $fatLists = $fatM->field('fat_id,fat_name')->select();
        $fatList  = ['' => __('NONE')];
        foreach ($fatLists as $fatvalue) {
            $fatList[$fatvalue['fat_id']] = $fatvalue['fat_name'];
        }
        // 客户上门状态
        $ArriveStatus = \app\admin\model\ArriveStatus::getList();
        //回访类型
        $rvoinfoM = new Rvinfo;
        $rvinfoLists = $rvoinfoM->field('rvt_id,rvt_name')->select();
        $rvinfoList  = ['' => __('NONE')];
        foreach ($rvinfoLists as $rvvalue) {
            $rvinfoList[$rvvalue['rvt_id']] = $rvvalue['rvt_name'];
        }

        $this->success('成功', null, [ 
            'briefAdminList' => $briefAdminList,
             'ctmtypeList'   => $ctmtypeList,
             'channelList'   => $channelList,
             'ctmSrcList'    => $ctmSrcList,
             'cproList'      => $cproList,
             'deptList'      => $deptList,
             'typeList'      => $typeList,
             'toolList'      => $toolList,
             'fatList'       => $fatList,
             'ArriveStatus'  => $ArriveStatus,
             'rvinfoList'    => $rvinfoList,
             ]);  

    }

    public function store(){
        //接收传过来的具体信息 params customer
        $consultParams = Request::instance()->params;
        $customerParams = Request::instance()->customer;
        $bookParams = Request::instance()->book;
        $rvplanParams = Request::instance()->rvplan;

        $admin = Request::instance()->admin;

        $customerM  =  new customer;
        $CustomerosconsultM = new Customerosconsult;
        $consultM = new MCustomerConsult;


        //手机电话双判断
            if (!empty($customerParams['ctm_tel'])) {
                $checkCustomerId = empty($customerParams['ctm_id']) ? 0 : $customerParams['ctm_id'];
                $phoneId         = $customerM->where(['ctm_id' => ['neq', $checkCustomerId]])->where(['ctm_mobile' => $customerParams['ctm_tel']])->column('ctm_id');
                if ($phoneId) {
                    $this->error('手机号码已存在');
                } 
            }

            $where         = [];
            if (!empty($customerParams['ctm_id'])) {
                $oldCustomer = $customerM->get(['ctm_id' => $customerParams['ctm_id']]);
                if (empty($oldCustomer)) {
                    $this->error('客户不存在');
                }

                //对于已有记录的客户，如手机/电话有记录，阻止修改
                if ($oldCustomer['ctm_mobile'] && isset($customerParams['ctm_mobile'])) {
                    $customerParams['ctm_mobile'] = $oldCustomer['ctm_mobile'];
                }
                if ($oldCustomer['ctm_tel'] && isset($customerParams['ctm_tel'])) {
                    $customerParams['ctm_tel'] = $oldCustomer['ctm_tel'];
                }
                $where = ['ctm_id' => $customerParams['ctm_id']];
            } else {
                $customerParams['admin_id'] = empty($consultParams['admin_id']) ? $admin['id'] : $consultParams['admin_id'];

                $customerParams['ctm_first_tool_id'] = isset($consultParams['tool_id']) ? $consultParams['tool_id'] : 0;
                $customerParams['ctm_first_dept_id'] = isset($consultParams['dept_id']) ? $consultParams['dept_id'] : 0;
                $customerParams['ctm_first_cpdt_id'] = isset($consultParams['cpdt_id']) ? $consultParams['cpdt_id'] : 0;
            }
            // 如果该用户在之前有过现场客服，则显示已上门
            if (!empty($customerParams['ctm_id']) && $CustomerosconsultM->where(['customer_id' => $customerParams['ctm_id']])->count()) {
                $customerParams['arrive_status'] = 1;
            }

            if ($customerM->saveWhenConsult($customerParams, $where) === false) {
                $this->error(__('Failed while trying to save customer data！'));
            }

             //获取 新增/更新 的顾客ID
            if ($customerParams['ctm_id']) {
                $customerId = $customerParams['ctm_id'];
            } else {
                $customerId = $customerM->getLastInsID();
            }

            //新增，==
            if (empty($consultParams['admin_id'])) {
                $consultParams['admin_id'] = $admin['id'];
            }

            //预约成功，清除失败原由
            if ($consultParams['cst_status']) {
                unset($consultParams['fat_id']);
                $consultParams['book_time'] = strtotime($consultParams['book_time']);
            } else {
                if (isset($consultParams['book_time'])) {
                    unset($consultParams['book_time']);
                }
            }

            $consultParams['customer_id'] = $customerId;

            if ($consultM->save($consultParams) === false) {
                $this->error(__('Failed while trying to save consult data！'));
            } else {
                //增加回访记录
                if (!empty($rvplanParams)) {
                    $rvplanName    = empty($rvplanParams['rv_plan']) ? '' : $rvplanParams['rv_plan'];
                    $rvplanAdminId = empty($rvplanParams['admin_id']) ? '' : $rvplanParams['admin_id'];

                    if (!empty($rvplanParams['admin_id']) && !empty($rvplanParams['rvt_type'])) {
                        foreach ($rvplanParams['rvt_type'] as $key => $value) {
                            if (empty($rvplanParams['rv_date'][$key])) {
                                continue;
                            }

                            $rvinfo              = new Rvinfo;
                            $rvinfo->rvi_tel     = $customerM->ctm_mobile;
                            $rvinfo->customer_id = $customerM->ctm_id;
                            $rvinfo->rvt_type    = $rvplanParams['rvt_type'][$key];
                            $rvinfo->rvi_content = $rvplanParams['rv_remark'][$key];
                            $rvinfo->rv_plan     = $rvplanParams['rv_plan'];
                            $rvinfo->rv_date     = $rvplanParams['rv_date'][$key];
                            $rvinfo->admin_id    = $rvplanParams['admin_id'];
                            $rvinfo->rv_is_valid = 0;
                            $rvinfo->save();
                        }
                    }
                }
            }

             //hook
            \think\Hook::listen(\app\admin\model\CustomerConsult::TAG_SAVE_CONSULT, $consultM, $customerM);

            $this->success(__('All data saved successfully.'));

    }
}

