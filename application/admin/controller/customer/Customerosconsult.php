<?php

namespace app\admin\controller\customer;

use app\admin\model\Chntype;
use app\admin\model\CustomerOsconsult as MCustomerOsconsult;
use app\admin\model\Osctype;
use app\admin\model\Rvinfo;
use app\common\controller\Backend;
use think\Controller;
use think\Request;
use think\Session;
use \think\Db;

/**
 * 现场客服管理
 *
 * @icon fa fa-circle-o
 */
class Customerosconsult extends Backend
{
    protected $noNeedRight = ['historyfordevelop'];
    /**
     * CustomerOsconsult模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Customerosconsult');

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
        //客户类型
        $ctmtypeLists = model('customertype')->field('type_id,type_name')->select();
        $ctmtypeList  = ['' => __('NONE')];
        foreach ($ctmtypeLists as $ctmtype) {
            $ctmtypeList[$ctmtype['type_id']] = $ctmtype['type_name'];
        }
        $this->view->assign("ctmtypeList", $ctmtypeList);
    }

    /**
     * 列表
     */
    public function index()
    {
        return $this->renderList('osconsult');
    }

    /**
     * 简洁版 今日 列表
     */
    public function quicktodaylist()
    {
        return $this->renderList('quicktodaylist');
    }

    /**
     * 到诊记录
     */
    public function historyfordevelop()
    {
        return $this->renderList('consult');
    }
    /**
     * 科室到诊
     */
    public function deductdept()
    {
        return $this->renderList('deductdept');
    }

    //预约当天并且到诊
    public function bookcustomer()
    {
        return $this->renderList('bookcustomer');
    }

    /**
     * 修改客服内容, 接受客服
     */
    public function edit($ids = null)
    {
        //判断是否是超级管理员
        $superadmin = $this->auth->isSuperAdmin();
        $this->view->assign("superadmin", $superadmin);
        //现场客服
        $briefAdminList = model('Admin')->getBriefAdminList2();
        $this->view->assign("briefAdminList", $briefAdminList);
        //主管
        $admintrue = false;
        $admin     = \think\Session::get('admin');
        if ($admin['position'] > 1) {
            $admintrue = true;
        }
        $this->view->assign("admintrue", $admintrue);

        //判断该条记录是否是今天添加的
        $todaytime = $this->model->field('createtime')->where(['osc_id' => $ids])->find();
        $startDate = strtotime(date('Y-m-d'));
        $endDate   = strtotime(date('Y-m-d 23:59:59'));

        $todaytrue = false;
        if ($todaytime['createtime'] >= $startDate && $todaytime['createtime'] <= $endDate) {
            $todaytrue = true;
        }
        if ($superadmin) {
            $todaytrue = true;
        }
        $this->view->assign("todaytrue", $todaytrue);

        $secCondition = [];
        $row          = $this->model->getOne(['osc_status' => ['neq', -1], 'osc_id' => $ids], $secCondition, false);
        $osc_type     = Osctype::getTypeById($row['osc_type']);
        $this->view->assign('osc_type', $osc_type);

        if (!$row) {
            $this->error(__('No results were found'));
        }

        if ($this->request->isPost()) {
            // if ($todaytrue == false) {
            //     $this->error(__('该条记录不是今天的'));
            // }
            $admin = Session::get('admin');
            // if ($row['osc_status'] <= 0) {
            //     $this->error(__('Sorry,you can not modify a closed consult!'));
            // }

            $this->checkDeptAuth($row['admin_id']);

            $rowParams = $this->request->post("row/a");

            $osconsultParams                = array();
            $osconsultParams['osc_content'] = $rowParams['osc_content'];
            $osconsultParams['fat_id']      = $rowParams['fat_id'];

            $useHook = false;
            if ($row->osc_status == MCustomerOsconsult::STATUS_CONSULTING) {
                $osconsultParams['osc_status'] = MCustomerOsconsult::STATUS_FAIL;
                $useHook                       = true;
            }

            $customerParams = $this->request->post("customer/a");
            if ($osconsultParams) {
                if (empty($osconsultParams['osc_content'])) {
                    $this->error(__('Osconsult content can not be empty'));
                }
                Db::startTrans();
                //保存顾客信息--没有的不保存
                if ($customerParams['ctm_id']) {
                    //电话号码可以更改
                    $saveCustomerRes = model('Customer')->checkSave($customerParams, $admin['id']);

                    if ($saveCustomerRes['error']) {
                        Db::rollback();
                        $this->error($saveCustomerRes['msg']);
                    }
                    $customerId = $saveCustomerRes['customer_id'];
                } else {
                    Db::rollback();
                    $this->error(__('Customer %s does not exist.', ''));
                }

                // 超管可以修改现场客服 导医
                if ($superadmin) {
                    $osconsultParams['osc_type']         = $rowParams['osc_type'];
                    $osconsultParams['osc_status']       = $rowParams['osc_status'];
                    $osconsultParams['admin_id']         = $rowParams['admin_id'];
                    $osconsultParams['service_admin_id'] = $rowParams['service_admin_id'];
                    $osconsultParams['cpdt_id']          = $rowParams['cpdt_id'];
                    $osconsultParams['dept_id']          = $rowParams['dept_id'];
                } elseif ($admintrue && $todaytrue) {
                    //检查 初诊复诊..类型
                    $osconsultParams['osc_type']         = $rowParams['osc_type'];
                    $osconsultParams['osc_status']       = $rowParams['osc_status'];
                    $osconsultParams['admin_id']         = $rowParams['admin_id'];
                    $osconsultParams['service_admin_id'] = $rowParams['service_admin_id'];
                    $osconsultParams['cpdt_id']          = $rowParams['cpdt_id'];
                    $osconsultParams['dept_id']          = $rowParams['dept_id'];
                }

                //是否 修改受理类型， 同步收银 初复诊状态
                if (isset($osconsultParams['osc_type']) && $osconsultParams['osc_type'] != $row['osc_type']) {
                    \app\admin\model\CustomerBalance::update(['b_osc_type' => $osconsultParams['osc_type']], ['osconsult_id' => $row->osc_id], ['b_osc_type']);
                }

                //客服内容是否为空
                if (empty($osconsultParams['osc_content'])) {
                    $this->error(__('Parameter %s can not be empty', '(' . __('Osc_content') . ')'));
                }

                if (($saveRes = $row->save($osconsultParams)) === false) {
                    Db::rollback();
                    $this->error(__('Failed while trying to save osconsult data！'));
                }
                Db::commit();
                if ($useHook && $row->osc_status == MCustomerOsconsult::STATUS_FAIL) {
                    \think\Hook::listen(\app\admin\model\CustomerOsconsult::TAG_SAVE_OSCONSULT, $row, model('Customer'));
                }
                $this->success(__('All data saved successfully.'));
            }
            $this->error(__('Parameter %s can not be empty'));
        }

        $admins = model('Admin')->getAdminList();
        // $adminList = array();
        // foreach ($admins as $admin) {
        //     $adminList[$admin['id']] = $admin['nickname'];
        // }
        $adminList = model('Admin')->getBriefAdminList2();

        $customer          = $row->customer;
        $consultId         = $row['consult_id'];
        $preConsultContent = '';

        //推荐人
        // $rec_name = model('customer')->field('ctm_name')->where(['ctm_id' => $customer['rec_customer_id']])->find();
        // if (empty($rec_name)) {
        //     $rec_name['ctm_name'] = '无';
        // }

          //推荐人
        // $rec_id =$row['rec_customer_id'];
        $recCustomer     = model('customer')->field('ctm_name,ctm_id')->where(['ctm_id' => $customer['rec_customer_id']])->find();
        $recCustomerName = __('None');
        if (!empty($recCustomer)) {
            $recCustomerName = $recCustomer->ctm_id.'--'.$recCustomer->ctm_name;
        }

        $statusTitle = MCustomerOsconsult::getStatusTitle($row->osc_status);
        $doesSuccess = false;
        if ($row->osc_status == MCustomerOsconsult::STATUS_SUCCESS || $row->osc_status == MCustomerOsconsult::STATUS_SUCCESS_PAYED) {
            $doesSuccess = true;
        }

        $showCreateOrderBtn = false;
        if ($row->createtime >= strtotime(date('Y-m-d')) && $row->createtime <= strtotime(date('Y-m-d 23:59:59'))) {
            $showCreateOrderBtn = true;
        }
        $definedRvPlans = model('Rvplan')->where(['rvp_status' => 1])->column('rvp_name', 'rvp_id');
        $this->view->assign('definedRvPlans', $definedRvPlans);

        $deptList = \app\admin\model\Deptment::getDeptListCache();
        $this->view->assign('deptList', $deptList);

        //顾客图片
        $customerImgs = model('CustomerImg')->where(['customer_id' => $customer->ctm_id])->order('weigh', 'DESC')->select();
        $this->view->assign('customerImgs', $customerImgs);
        //成交状态
        $StatusList = model('CustomerOsconsult')->getStatusList();
        $this->view->assign('StatusList', $StatusList);

        $this->view->assign('statusTitle', $statusTitle);
        $this->view->assign('doesSuccess', $doesSuccess);
        $this->view->assign("recCustomerName", $recCustomerName);
        $this->view->assign('adminList', $adminList);
        $this->view->assign("row", $row);
        $this->view->assign('consultId', $consultId);
        $this->view->assign('customer', $customer);
        $this->view->assign('preConsultContent', $preConsultContent);
        $this->view->assign('showCreateOrderBtn', $showCreateOrderBtn);
        $this->customerExtraInit();
        return $this->view->fetch();
    }

    public function accept($ids = null)
    {
        // $secCondition = $this->secAuth->getCusSecCondition('admin_id');
        $secCondition = [];
        // 'osc_status' => ['neq', -1],
        $row = $this->model->getOne(['osc_status' => ['neq', -1], 'osc_id' => $ids], $secCondition, false);

        $result = ['error' => true, 'msg' => __('Error occors!'), 'flag' => false];
        if ($row) {
            $this->checkDeptAuth($row['admin_id']);

            $result['error'] = false;
            $result['msg']   = __('');
            if ($row['osc_status'] == 0) {
                if ($row->save(['osc_status' => 1])) {
                    $result['msg']  = __('Accept successfully.');
                    $result['flag'] = true;
                }
            }
        } else {
            $result['msg'] = __('No Results were found');
        }

        return $result;
    }

    public function deny($ids = null)
    {

        $secCondition = [];
        $row          = $this->model->getOne(['osc_status' => ['neq', -1], 'osc_id' => $ids], $secCondition, false);

        $result = ['error' => true, 'msg' => __('Error occors!'), 'flag' => false];
        if ($row) {
            $this->checkDeptAuth($row['admin_id']);

            $result['error'] = false;
            $result['msg']   = __('');
            if ($row['osc_status'] == 0) {
                if ($row->save(['osc_status' => -1])) {
                    $result['msg']  = __('Deny successfully.');
                    $result['flag'] = true;
                }
            }
        } else {
            $result['msg'] = __('No Results were found');
        }

        return $result;
    }

    public function add()
    {
        return $this->error('Access denied!');
    }

    //客服失败时添加个性回访计划(不保存)
    public function addrvtype()
    {
        $customer_id = input('ctm_id');
        $rvtypeList  = model('Rvtype')->column('rvt_name', 'rvt_id');
        $this->view->assign('rvtypeList', $rvtypeList);
        $data = date('Y-m-d', time());
        $this->view->assign('data', $data);
        $briefAdminList = model('Admin')->getBriefAdminList2();
        $this->view->assign("briefAdminList", $briefAdminList);

        if ($this->request->isPost()) {
            $row = $this->request->post('row/a');

            $admin = \think\Session::get('admin');
            if (empty($row['admin_id'])) {
                $row['admin_id'] = $admin->id;
            }

            $customer = model('customer')->where(['ctm_id' => $customer_id])->find();
            if (empty($customer->ctm_mobile) && empty($customer->ctm_tel)) {
                $this->error(__('Parameter %s can not be empty', '电话号码'));
            }
            $rvTypeName = '--';
            $rvType     = model('Rvtype')->find($row['rvt_type']);
            if ($rvType != null) {
                $rvTypeName = $rvType->rvt_name;
            }

            $nowTime = strtotime($data);
            $time    = strtotime($row['rvd_days']);
            if ($nowTime > $time) {
                $this->error(__("回访计划时间不能在今天之前"));
            }

            $rvDate          = $row['rvd_days'];
            $rvinfo          = new Rvinfo();
            $rvinfo->rvi_tel = $customer->ctm_mobile;
            if (empty($customer->ctm_mobile)) {
                $rvinfo->rvi_tel = $customer->ctm_tel;
            }
            $rvinfo->customer_id = $customer->ctm_id;
            $rvinfo->rvt_type    = $rvTypeName;
            $rvinfo->rv_plan     = $row['rv_plan'];
            $rvinfo->rvi_content = '';
            $rvinfo->admin_id    = $row['admin_id'];
            $rvinfo->rv_date     = $rvDate;
            $rvinfo->rv_is_valid = 0;
            $rvinfo->save();

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

            $this->success();

        }
        return $this->view->fetch();
    }

    private function renderList($type)
    {
        // getSecCondition()
        //设置过滤方法
        $this->request->filter(['strip_tags']);

        $startDate = input('coc.createtime_start', strtotime(date('Y-m-d')));
        $endDate   = input('coc.createtime_end', strtotime(date('Y-m-d 23:59:59')));
        $start     = date('Y-m-d', $startDate);
        $end       = date('Y-m-d', $startDate);

        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name')) {
                return $this->selectpage();
            }
            list($bWhere, $sort, $order, $offset, $limit, $extraWhere) = $this->dealParams($type, $startDate, $endDate);

            if (isset($bWhere['customer.ctm_mobile'])) {
                $mobileOperator  = $bWhere['customer.ctm_mobile'][0];
                $mobileCondition = $bWhere['customer.ctm_mobile'][1];
                unset($bWhere['customer.ctm_mobile']);
                $bWhere[] = function ($query) use ($mobileOperator, $mobileCondition) {
                    $query->where('customer.ctm_mobile', $mobileOperator, $mobileCondition)
                        ->whereOr('customer.ctm_tel', $mobileOperator, $mobileCondition);
                };
            }
            if (isset($bWhere['customer.potential_cpdt'])) {
                $cpdtOperator  = $bWhere['customer.potential_cpdt'][0];
                $cpdtCondition = $bWhere['customer.potential_cpdt'][1];
                unset($bWhere['customer.potential_cpdt']);
                $bWhere[] = function ($query) use ($cpdtOperator, $cpdtCondition) {
                    $query->where('customer.potential_cpdt1', $cpdtOperator, $cpdtCondition)
                        ->whereOr('customer.potential_cpdt2', $cpdtOperator, $cpdtCondition)
                        ->whereOr('customer.potential_cpdt3', $cpdtOperator, $cpdtCondition);
                };
            }
            if (!$this->auth->isSuperAdmin()) {
                $osclistIncludePublic = \think\Config::get('site.osclist_include_public');
                if (empty($osclistIncludePublic)) {
                    $extraWhere['customer.ctm_is_public'] = 0;
                }
            }

            $total = $this->model->getListCount($bWhere, $extraWhere);
            $list  = $this->model->getList($bWhere, $sort, $order, $offset, $limit, $extraWhere);

            //手机号仅超管显示全部
            if (!$this->auth->isSuperAdmin()) {
                foreach ($list as $key => $row) {
                    $list[$key]['ctm_mobile'] = getMaskString($list[$key]['ctm_mobile']);
                    $list[$key]['ctm_tel'] = getMaskString($list[$key]['ctm_tel']);
                }
            }

            if ($offset) {
                $result = array("total" => $total, "rows" => $list);
            } else {
                $oscSummary = $this->model->getListTypeSummary($bWhere, $extraWhere);
                $result     = array("total" => $total, "rows" => $list, 'summary' => $oscSummary);
            }
            return json($result);
        }
        $this->view->assign("startDate", $start);
        $this->view->assign("endDate", $end);

        $briefAdminList = model('Admin')->getBriefAdminList2();
        $this->view->assign("briefAdminList", $briefAdminList);
        //初复诊人次显示
        $oscSummary = [];
        foreach (Osctype::getList() as $key => $value) {
            $oscSummary[$key] = 0;
        }
        $admin = \think\Session::get('admin');
        // echo $this->deptAuth->getAdminCondition($fields = 'id', $admin['id']);
        // 是否是超级管理员
        $superadmin = $this->auth->isSuperAdmin();

        $oscStatusArr = MCustomerOsconsult::getStatusList();
        $this->view->assign('oscStatusArr', $oscStatusArr);

        $ocsTypeArr = Osctype::getList();
        //merge resort , with all key '', original key 1 change to 0
        // $ocsTypeArr = array_merge(['' => __('All')], $ocsTypeArr);
        $this->view->assign('ocsTypeArr', $ocsTypeArr);

        //项目类型
        $pducats  = model('CProject')->field('id, cpdt_name')->where(['cpdt_status' => 1])->order('id', 'ASC')->select();
        $cpdtList = ['' => __('NONE')];
        foreach ($pducats as $pducat) {
            $cpdtList[$pducat['id']] = $pducat['cpdt_name'];
        }
        $this->view->assign('cpdtList', $cpdtList);
        //客服科室
        // $deptments = model('deptment')->field('dept_id,dept_name')->where(['dept_status' => 1])->select();
        // $deptList  = ['' => __('NONE')];
        // foreach ($deptments as $deptment) {
        //     $deptList[$deptment['dept_id']] = $deptment['dept_name'];
        // }
        // $this->view->assign('deptList', $deptList);
        //
        $this->view->assign('deptList', (new \app\admin\model\deptment)->getVariousTree());
        //未成交原因
        $fatLists = model('fat')->field('fat_id,fat_name')->select();
        $fatList  = ['' => __('NONE')];
        foreach ($fatLists as $fatvalue) {
            $fatList[$fatvalue['fat_id']] = $fatvalue['fat_name'];
        }
        $this->view->assign('fatList', $fatList);
        $toolList = \app\admin\model\CocAcceptTool::getList();

        $chnTypeList = Chntype::getList();
        $this->view->assign('chnTypeList', $chnTypeList);
        $this->view->assign('type', $type);
        $this->view->assign('toolList', $toolList);

        $this->view->engine->layout('layout/' . 'columns2');

        if ($type == 'quicktodaylist') {
            return $this->view->fetch('customer/customerosconsult/quicktodaylist');
        } else {
            return $this->view->fetch('customer/customerosconsult/index');
        }
    }

    public function downloadprocess()
    {
        $type = input('type', '');
        // //删除的不显示
        // $extraWhere['coc.is_delete'] = 0;
        $startDate = input('coc.createtime_start', strtotime(date('Y-m-d')));
        $endDate   = input('coc.createtime_end', strtotime(date('Y-m-d 23:59:59')));
        if (isset($_GET['op']) && $_GET['op'] == '') {
            unset($_GET['op']);
        }
        list($bWhere, $sort, $order, $offset, $limit, $extraWhere) = $this->dealParams($type, $startDate, $endDate);

        \think\Request::instance()->get(['filter' => '']);

        return $this->commondownloadprocess('CustomerosconsultReport', 'customer customerosconsult', $bWhere, $extraWhere);
    }

    private function dealParams($type, $startDate, $endDate)
    {
        list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null, false);
        $bWhere                                      = [];
        $extraWhere                                  = [];
        foreach ($where as $key => $value) {
            $bWhere[$value[0]] = [$value[1], $value[2]];
        }

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
        $developAdminCon = model('admin')->field('id');
        //并不只选择营销人员
        if (!(isset($bWhere['customer.admin_id']) && !isset($bWhere['admin.dept_id']))) {
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
        }
        if (isset($bWhere['admin.dept_id'])) {
            unset($bWhere['admin.dept_id']);
        }

        // 是否是超级管理员
        $admin = \think\Session::get('admin');
        if (!$this->auth->isSuperAdmin()) {
            //只显示自己的记录，其他人员的不显示
            // $extraWhere = array_merge(['osc_status' => ['neq', -1], 'admin_id' => $admin['id']], $secCondition);
            $deptAdminCon = $this->deptAuth->getAdminCondition($fields = 'id', $admin['id'], false, false);
            if ($type == 'osconsult') {
                if (!empty($bWhere['coc.admin_id'])) {
                    $deptAdminCon = $deptAdminCon->where(['id' => $bWhere['coc.admin_id']]);
                }

                $bWhere['coc.admin_id'] = ['exp', 'in ' . $deptAdminCon->buildSql()];
            } elseif ($type == 'consult') {
                if (!empty($bWhere['customer.admin_id'])) {
                    $deptAdminCon = $deptAdminCon->where(['id' => $bWhere['customer.admin_id']]);
                }

                $bWhere['customer.admin_id'] = ['exp', 'in ' . $deptAdminCon->buildSql()];
            } elseif ($type == 'deductdept') {
                $deptAdminCon1 = $this->deptAuth->getDeptIds(true);

                if ($deptAdminCon1 == '*') {
                    $deptAdminCon1 = [];
                } else {
                    $deptAdminCon1 = ['dept_id' => ['in', $deptAdminCon1]];
                }

                $deptAdminCon = model('Deptment')->field('dept_id')->where($deptAdminCon1);
                if (!empty($bWhere['coc.dept_id'])) {
                    $deptAdminCon = $deptAdminCon->where(['dept_id' => $bWhere['coc.dept_id']]);
                }

                $bWhere['coc.dept_id'] = ['exp', 'in ' . $deptAdminCon->buildSql()];
            }
        }

        //首次查询时显示当日
        $timewhere = [];
        if (!isset($_GET['op'])) {
            $bWhere['coc.createtime'] = ['BETWEEN', [$startDate, $endDate]];
        }
        if (isset($bWhere['customer.ctm_mobile'])) {
            $searchMobile                  = trim($bWhere['customer.ctm_mobile'][1], '%');
            $bWhere['customer.ctm_mobile'] = ['exp', "like '%{$searchMobile}%' or customer.ctm_tel like '%{$searchMobile}%'"];
        }
        //删除的不显示
        $extraWhere['coc.is_delete'] = 0;

        //预约时间是当天并且有到诊
        if ($type == 'bookcustomer') {
            $bookTime_start          = strtotime(date('Y-m-d'));
            $bookTime_end            = strtotime(date('Y-m-d 23:59:59'));
            $bWhere['cst.book_time'] = ['BETWEEN', [$bookTime_start, $bookTime_end]];
        }

        return [$bWhere, $sort, $order, $offset, $limit, $extraWhere];
    }
}
