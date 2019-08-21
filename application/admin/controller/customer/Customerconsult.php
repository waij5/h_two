<?php

namespace app\admin\controller\customer;

use app\admin\model\ArriveStatus;
use app\admin\model\Chntype;
use app\admin\model\CmdRecords;
use app\admin\model\CocAccepttool;
use app\common\controller\Backend;
use think\Controller;
use think\Db;
use think\Exception\TransException;
use think\Request;
use think\Session;

/**
 *
 *
 * @icon fa fa-circle-o
 */
class CustomerConsult extends Backend
{

    /**
     * CustomerConsult模型对象
     */
    protected $model = null;

    protected $noNeedRight = ['ajaxlist'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('CustomerConsult');
        //客户类型
        $ctmtypeLists = model('customertype')->field('type_id,type_name')->select();
        $ctmtypeList  = ['' => __('NONE')];
        foreach ($ctmtypeLists as $ctmtype) {
            $ctmtypeList[$ctmtype['type_id']] = $ctmtype['type_name'];
        }
        $this->view->assign("ctmtypeList", $ctmtypeList);
        //营销渠道
        $channelLists = model('Ctmchannels')->field('chn_id, chn_name')->where(['chn_status' => 1])->order('chn_sort', 'desc')->select();
        $channelList  = ['' => __('NONE')];
        foreach ($channelLists as $source) {
            $channelList[$source['chn_id']] = $source['chn_name'];
        }
        $this->view->assign('channelList', $channelList);

        $deptList = \app\admin\model\Deptment::getDeptListCache();
        // $deptList = (new \app\admin\model\Deptment)->getVariousTree();
        $this->view->assign('deptList', $deptList);
    }

    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);

        $startDate = input('cst.createtime_start', strtotime(date('Y-m-d')));
        $endDate   = input('cst.createtime_end', strtotime(date('Y-m-d 23:59:59')));
        $start     = date('Y-m-d', $startDate);
        $end       = date('Y-m-d', $startDate);
        $this->view->assign("startDate", $start);
        $this->view->assign("endDate", $end);
        $briefAdminList = model('Admin')->getBriefAdminList2();
        //上门状态
        $ArriveStatus = ArriveStatus::getList();
        $this->view->assign('ArriveStatus', $ArriveStatus);
        //客户来源
        $ctmSource  = model('Ctmsource')->field('sce_id, sce_name')->where(['sce_status' => 1])->order('sce_sort', 'desc')->select();
        $ctmSrcList = ['' => __('NONE')];
        foreach ($ctmSource as $source) {
            $ctmSrcList[$source['sce_id']] = $source['sce_name'];
        }
        $this->view->assign('ctmSrcList', $ctmSrcList);

        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null, false);

            $bWhere     = [];
            $extraWhere = [];
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

            // 是否是超级管理员
            $admin      = \think\Session::get('admin');
            $superadmin = $this->auth->isSuperAdmin();
            if ($superadmin) {
                //额外加入条件， 排除自身已拒绝的
                $extraWhere['cst.status'] = 1;
            } else {
                //只显示自己的记录，其他人员的不显示
                // $extraWhere = array_merge(['admin_id' => $admin['id'],'status' => 1], $secCondition);
                $extraWhere['cst.admin_id'] = ['exp', 'in ' . $this->deptAuth->getAdminCondition($fields = 'id', $admin['id'])];
                $extraWhere['cst.status']   = 1;

                $cstlistIncludePublic = \think\Config::get('site.cstlist_include_public');
                if (empty($cstlistIncludePublic)) {
                    $extraWhere['customer.ctm_is_cst_public'] = 0;
                }
            }

            //首次查询时显示当日
            $timewhere = [];
            if (!isset($_GET['op'])) {
                // $timewhere = ['createtime' => ['BETWEEN', [$startDate, $endDate]]];
                // $bWhere['coc.createtime'] = ['BETWEEN', [$startDate, $endDate]];
                $bWhere['cst.createtime'] = ['BETWEEN', [$startDate, $endDate]];
            }

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

            $total = $this->model->getListCount($bWhere, $extraWhere);
            $list  = $this->model->getList($bWhere, $sort, $order, $offset, $limit, $extraWhere);

            if (!$superadmin) {
                foreach ($list as $key => $row) {
                    $list[$key]['ctm_mobile']         = getMaskString($list[$key]['ctm_mobile']);
                    $list[$key]['develop_staff_name'] = '自然到诊';
                    if (isset($briefAdminList[$row['develop_admin_id']])) {
                        $list[$key]['develop_staff_name'] = $briefAdminList[$row['develop_admin_id']];
                    }
                }
            } else {
                foreach ($list as $key => $row) {
                    $list[$key]['develop_staff_name'] = '自然到诊';
                    if (isset($briefAdminList[$row['develop_admin_id']])) {
                        $list[$key]['develop_staff_name'] = $briefAdminList[$row['develop_admin_id']];
                    }
                }
            }

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        //客服科室
        // $deptments = model('deptment')->field('dept_id,dept_name')->where(['dept_status' => 1])->select();
        // $deptList  = ['' => __('NONE')];
        // foreach ($deptments as $deptment) {
        //     $deptList[$deptment['dept_id']] = $deptment['dept_name'];
        // }
        $deptList = (new \app\admin\model\Deptment)->getVariousTree();
        $this->view->assign('deptList', $deptList);

        //项目类型
        $pducats  = model('CProject')->field('id, cpdt_name')->where(['cpdt_status' => 1])->order('id', 'ASC')->select();
        $cpdtList = ['' => __('NONE')];
        foreach ($pducats as $pducat) {
            $cpdtList[$pducat['id']] = $pducat['cpdt_name'];
        }
        $this->view->assign('cpdtList', $cpdtList);

        //受理类型
        $typeList = model('Chntype')->getList();
        $this->view->assign('typeList', $typeList);

        //受理工具
        $toolList = CocAccepttool::getList();
        $this->view->assign('toolList', $toolList);
        $this->view->assign('briefAdminList', $briefAdminList);

        return $this->view->fetch();
    }

    //导出
    public function downloadprocess()
    {
        list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null, false);

        $bWhere     = [];
        $extraWhere = [];
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

        \think\Request::instance()->get(['filter' => '']);

        return $this->commondownloadprocess('CustomerconsultReport', 'customer customerconsult', $bWhere, $extraWhere);
    }

    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $consultParams  = $this->request->post("row/a");
            $customerParams = $this->request->post("customer/a");
            $bookParams     = $this->request->post("book/a");
            $rvplanParams   = $this->request->post("rvplan/a");

            //手机电话双判断
            if (!empty($customerParams['ctm_tel'])) {
                $checkCustomerId = empty($customerParams['ctm_id']) ? 0 : $customerParams['ctm_id'];
                $phoneId         = model('customer')->where(['ctm_id' => ['neq', $checkCustomerId]])->where(['ctm_mobile' => $customerParams['ctm_tel']])->column('ctm_id');
                if ($phoneId) {
                    $this->error('手机号码已存在');
                } else {
                    // $telId = model('customer')->where(['ctm_id' => ['neq', $checkCustomerId]])->where(['ctm_tel' => $customerParams['ctm_tel']])->column('ctm_id');
                    // if ($telId) {
                    //     $this->error(__('THE TEL IS EXIST'));
                    // }
                }
            }

            $admin = Session::get('admin');

            $customerModel = model('Customer');
            $where         = [];
            if (!empty($customerParams['ctm_id'])) {
                $oldCustomer = model('Customer')->get(['ctm_id' => $customerParams['ctm_id']]);
                if (empty($oldCustomer)) {
                    throw new Exception(__('Customer does not exist.'), 1);
                }

                //对于已有记录的客户，如手机/电话有记录，阻止修改
                if ($oldCustomer['ctm_mobile'] && isset($customerParams['ctm_mobile'])) {
                    // unset($customerParams['ctm_mobile']);
                    $customerParams['ctm_mobile'] = $oldCustomer['ctm_mobile'];
                }
                if ($oldCustomer['ctm_tel'] && isset($customerParams['ctm_tel'])) {
                    // unset($customerParams['ctm_tel']);
                    $customerParams['ctm_tel'] = $oldCustomer['ctm_tel'];
                }

                $where = ['ctm_id' => $customerParams['ctm_id']];
            } else {
                // $customerParams['admin_id'] = $admin['id'];
                $customerParams['admin_id'] = empty($consultParams['admin_id']) ? $admin['id'] : $consultParams['admin_id'];

                $customerParams['ctm_first_tool_id'] = isset($consultParams['tool_id']) ? $consultParams['tool_id'] : 0;
                $customerParams['ctm_first_dept_id'] = isset($consultParams['dept_id']) ? $consultParams['dept_id'] : 0;
                $customerParams['ctm_first_cpdt_id'] = isset($consultParams['cpdt_id']) ? $consultParams['cpdt_id'] : 0;
            }
            // 如果该用户在之前有过现场客服，则显示已上门
            if (!empty($customerParams['ctm_id']) && model('Customerosconsult')->where(['customer_id' => $customerParams['ctm_id']])->count()) {
                $customerParams['arrive_status'] = 1;
            }

            if ($customerModel->saveWhenConsult($customerParams, $where) === false) {
                $this->error(__('Failed while trying to save customer data！'));
            }

            //获取 新增/更新 的顾客ID
            if ($customerParams['ctm_id']) {
                $customerId = $customerParams['ctm_id'];
            } else {
                $customerId = $customerModel->getLastInsID();
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

            // $consultModel = model('CustomerConsult');
            $consultModel = new \app\admin\model\CustomerConsult;
            if ($consultModel->save($consultParams) === false) {
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

                            $rvinfo              = new \app\admin\model\Rvinfo;
                            $rvinfo->rvi_tel     = $customerModel->ctm_mobile;
                            $rvinfo->customer_id = $customerModel->ctm_id;
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
            \think\Hook::listen(\app\admin\model\CustomerConsult::TAG_SAVE_CONSULT, $consultModel, model('Customer'));

            $this->success(__('All data saved successfully.'));
        }

        $phone      = trim(input('phone', ''));
        $customerId = input('customer_id', false);

        $urlParams           = [];
        $urlParams['dialog'] = true;
        $urlParams['force']  = true;
        if ($phone) {
            $urlParams['phone'] = $phone;
        }

        $Superadmin = $this->auth->isSuperAdmin();
        $this->view->assign("Superadmin", $Superadmin);
        $force = input('force', false);

        $iftrue = ($force && $Superadmin);

        $briefAdminList = model('Admin')->getBriefAdminList2();
        $this->view->assign('briefAdminList', $briefAdminList);

        $toolList = CocAccepttool::getList();
        $this->view->assign('toolList', $toolList);

        $showWarnings    = false;
        $warnings        = [];
        $customerAdminid = false;
        if (!empty($customerId)) {
            $customer = model('Customer')->get(['ctm_id' => $customerId]);
            if (empty($customer->ctm_id)) {
                $this->error(__('Customer does not exist.', [$customerId]));
            }
            //如果当前登陆人员就是客户的营销人员
            if (Session::get('admin')->id == $customer['admin_id']) {
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

                    $cstSubSql = model('CustomerConsult')
                        ->where([
                            'status'      => 1,
                            'createtime'  => ['gt', $searchStartTime],
                            'customer_id' => $customer->ctm_id,
                        ])
                        ->order('createtime', 'DESC')
                        ->limit(1)
                        ->buildSql();

                    $customerPreCon = model('CustomerConsult')
                        ->table($cstSubSql)
                        ->alias('cst')
                        ->field('cst.*, customer.ctm_name, customer.arrive_status, customer.ctm_first_tool_id, cpdt.cpdt_name, customer.ctm_last_osc_admin')
                        ->join(model('Customer')->getTable() . ' customer', 'cst.customer_id = customer.ctm_id', 'LEFT')
                        ->join(model('CProject')->getTable() . ' cpdt', 'cst.cpdt_id = cpdt.id', 'LEFT')
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
                        // $this->view->assign('intervalDays', $intervalDays);
                        // $forceUrl = url('customer/customerconsult/add', $urlParams);
                        // $this->view->assign('forceUrl', $forceUrl);
                        // return $this->view->fetch();
                    }
                }

                if ($showWarnings) {
                    $canEditPreCon = $this->checkDeptAuth($customerPreCon['admin_id'], false);
                    $forceUrl      = url('customer/customerconsult/add', $urlParams);

                    $this->view->assign('customerAdminid', $customerAdminid);
                    $this->view->assign('forceUrl', $forceUrl);
                    $this->view->assign('showWarnings', $showWarnings);
                    $this->view->assign('warnings', $warnings);
                    return $this->view->fetch();
                }
            }

            //顾客图片
            $customerImgs = model('CustomerImg')->where(['customer_id' => $customer->ctm_id])->order('weigh', 'DESC')->select();
            $this->view->assign('customerImgs', $customerImgs);
        } else {
            $customer = model('Customer')->fade(['ctm_mobile' => $phone, 'ctm_tel' => $phone]);
        }
        //如果是已存在用户,号码信息进行掩码
        // if (!empty($customer['ctm_id'])) {
        //     $customer['ctm_mobile'] = getMaskString($customer['ctm_mobile']);
        //     $customer['ctm_tel']    = getMaskString($customer['ctm_tel']);
        // }

        //客服项目
        $cprolists = model('CProject')->field('id,cpdt_name')->where(['cpdt_status' => 1])->select();
        $List      = ['' => __('NONE')];
        foreach ($cprolists as $cprolist) {
            $List[$cprolist['id']] = $cprolist['cpdt_name'];
        }
        $this->view->assign('List', $List);

        //推荐人
        // $rec_id   = $customer['rec_customer_id'];
        // $rec_name = model('customer')->field('ctm_name,ctm_id')->where(['ctm_id' => $rec_id])->find();
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
        $this->view->assign("recCustomerName", $recCustomerName);

        //默认预约时间在2小时后
        $bookTime = date('Y-m-d H:i:00', strtotime('+2 hours', time()));
        $this->assign('bookTime', $bookTime);
        $this->view->assign('customer', $customer);
        $this->customerExtraInit();

        if (empty($customerPreCon)) {
            //回访类型
            $rvTypeList = model('rvtype')->column('rvt_name', 'rvt_id');
            // $this->view->assign('rvTypeList', json_encode($rvTypeList));
            $rvTypeSelect = '\'<select name="rvplan[rvt_type][]" class="form-control">';
            foreach ($rvTypeList as $key => $value) {
                $rvTypeSelect .= '<option value="' . $value . '">' . $value . '</option>';
            }
            $rvTypeSelect .= '</select>\'';
            $this->view->assign('rvTypeSelect', $rvTypeSelect);
        }
        $definedRvPlans = model('Rvplan')->where(['rvp_status' => 1])->column('rvp_name', 'rvp_id');
        $this->view->assign('definedRvPlans', $definedRvPlans);

        $this->view->assign('cocTypeList', CocAccepttool::getList());
        $this->view->assign('Chntype', Chntype::getList());
        $forceUrl = url('customer/customerconsult/add', $urlParams);
        $this->view->assign('admin', \think\Session::get('admin'));
        $this->view->assign('forceUrl', $forceUrl);
        $this->view->assign('showWarnings', $showWarnings);
        $this->view->assign('warnings', $warnings);
        $this->view->assign("iftrue", $iftrue);

        return $this->view->fetch();
    }

    /**
     * 查询手机号等是否存在
     */
    public function presearch()
    {
        $phone      = trim(input('phone', false));
        $customerId = input('customer_id', false);

        //在有相关号码时 选择新增顾客及客服 / 新增已有顾客客服
        if ($this->request->isPost()) {
            return redirect('customer/customerconsult/add', ['phone' => $phone, 'customer_id' => $customerId, 'dialog' => 1]);
        }

        $customerCount = model('Customer')->where(['ctm_mobile' => ['like', "%$phone%"]])->whereOr(['ctm_tel' => ['like', "%$phone%"]])->count();

        //未查找任意记录， 直接跳转新增
        if ($customerCount == 0) {
            return redirect('customer/customerconsult/add', ['phone' => $phone, 'dialog' => 1]);
        }

        $customers = model('Customer')->where(['ctm_mobile' => ['like', "%$phone%"]])->whereOr(['ctm_tel' => ['like', "%$phone%"]])->select();

        $this->view->assign('customers', $customers);
        $this->view->assign('phone', $phone);

        return $this->view->fetch();
    }

    public function edit($ids = null)
    {
        //判断是否是超级管理员
        $superadmin = $this->auth->isSuperAdmin();
        $this->view->assign("superadmin", $superadmin);

        //上门状态
        $ArriveStatus = ArriveStatus::getList();
        $this->view->assign('ArriveStatus', $ArriveStatus);

        $row = $this->model->getOne(['cst_id' => $ids], [], false);

        if (!$row) {
            $this->error(__('No Results were found'));
        }

        //时间
        if ($row['book_time']) {
            $time = date("Y-m-d H:i:s", $row['book_time']);
        } else {
            $time = '';
        }
        $this->view->assign("time", $time);

        if ($this->request->isPost()) {
            // if ($row['cst_status'] <= 0) {
            //     $this->error(__('Sorry,you can not modify a closed consult!'));
            // }

            //权限检查
            $this->checkDeptAuth($row['admin_id']);

            $consultParams  = $this->request->post("row/a");
            $customerParams = $this->request->post("customer/a");
            $admin          = Session::get('admin');

            //预约状态
            if ($consultParams['cst_status'] == '1') {
                $consultParams['fat_id']    = 0;
                $consultParams['book_time'] = strtotime($consultParams['book_time']);
            } elseif ($consultParams['cst_status'] == '0') {
                $consultParams['book_time'] = null;
            } else {
                if (!$this->auth->isSuperAdmin()) {
                    unset($consultParams['cst_status']);
                    unset($consultParams['fat_id']);
                    unset($consultParams['book_time']);
                } else {
                    $consultParams['fat_id']    = 0;
                    $consultParams['book_time'] = null;
                }

            }

            try {
                //保存前受理工具
                $oldCstToolId = is_null($row->tool_id) ? 0 : $row->tool_id;
                if (($saveRes = $row->save($consultParams)) === false) {
                    throw new TransException(__('Failed while trying to save osconsult data！'));
                } else {
                    //保存顾客信息--没有的不保存
                    if ($customerParams['ctm_id']) {
                        $customer = model('Customer')->find($customerParams['ctm_id']);
                        if (!empty($customer->ctm_id)) {
                            if (!empty($customer->ctm_mobile) && !empty($customerParams['ctm_mobile'])) {
                                unset($customerParams['ctm_mobile']);
                            }
                            //受理工具不为空--第一次为网电，受理工具与本次原来相同可能此为第一次，检查确认
                            if (!empty($customer->ctm_first_tool_id) && $customer->ctm_first_tool_id == $oldCstToolId) {
                                $isCstFirst = !((bool) $this->model->where(['customer_id' => $customer->ctm_id, 'createtime' => ['<', $row->createtime]])->count());

                                if ($isCstFirst) {
                                    $customerParams['ctm_first_tool_id'] = $row->tool_id;
                                    $customerParams['ctm_first_dept_id'] = $row->dept_id;
                                    $customerParams['ctm_first_cpdt_id'] = $row->cpdt_id;
                                }
                            }
                            //手机电话双判断ctm_tel
                            if (!empty($customerParams['ctm_tel'])) {
                                $phoneId = model('customer')->where(['ctm_mobile' => $customerParams['ctm_tel']])->column('ctm_id');
                                if ($phoneId && $phoneId['0'] != $customer['ctm_id']) {
                                    $this->error('手机号码已存在');
                                } else {
                                    $telId = model('customer')->where(['ctm_tel' => $customerParams['ctm_tel']])->column('ctm_id');
                                    if ($telId && $telId['0'] != $customer['ctm_id']) {
                                        $this->error('电话号码已存在');
                                    }
                                }
                            }

                            $customer->saveWhenConsult($customerParams);
                        } else {
                            throw new TransException('Customer %s does not exist.', $customerParams['ctm_id']);
                        }
                    }

                    if ($customerParams['ctm_id']) {
                        if (!empty($customerParams['ctm_tel'])) {
                            //手机电话双判断
                            $phoneId = model('customer')->where(['ctm_mobile' => $customerParams['ctm_tel']])->column('ctm_id');
                            if ($phoneId && $phoneId['0'] != $customer['ctm_id']) {
                                $this->error('手机号码已存在');
                            } else {
                                $telId = model('customer')->where(['ctm_tel' => $customerParams['ctm_tel']])->column('ctm_id');
                                if ($telId && $telId['0'] != $customer['ctm_id']) {
                                    $this->error('电话号码已存在');
                                }
                            }
                        }

                        $saveCustomerRes = model('Customer')->checkNdSave($customerParams, $admin['id']);
                        if ($saveCustomerRes['error']) {
                            $this->error($saveCustomerRes['msg']);
                        }
                    }
                }

                Db::commit();
                $this->success(__('All data saved successfully.'));
            } catch (TransException $e) {
                Db::rollback();
                $this->error($e->getMessage());
            } catch (think\exception\PDOException $e) {
                Db::rollback();
                $this->error(__('Error occurs'));
            }
        }

        $customer = $row->customer;
        //掩藏电话号码
        // $customer['ctm_mobile'] = getMaskString($customer['ctm_mobile']);
        // $customer['ctm_tel'] = getMaskString($customer['ctm_tel']);
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
        $this->view->assign("recCustomerName", $recCustomerName);

        $definedRvPlans = model('Rvplan')->where(['rvp_status' => 1])->column('rvp_name', 'rvp_id');
        $this->view->assign('definedRvPlans', $definedRvPlans);

        $briefAdminList = model('Admin')->getBriefAdminList2();
        $this->view->assign('briefAdminList', $briefAdminList);
        $this->view->assign("row", $row);
        // $this->view->assign('consultId', $consultId);
        $this->view->assign('customer', $customer);
        // $this->view->assign('preConsultContent', $preConsultContent);
        $this->customerExtraInit();
        $this->view->assign('cocTypeList', CocAccepttool::getList());
        $this->view->assign('Chntype', Chntype::getList());
        $cpdtName = '';
        $cpdtList = \app\admin\model\CProject::cProjectArrayCache();
        if (isset($cpdtList[$row->cpdt_id])) {
            $cpdtName = $cpdtList[$row->cpdt_id]['cpdt_name'] . '|' . $cpdtList[$row->cpdt_id]['cpdt_type'];
        }
        $this->view->assign('cpdtName', $cpdtName);
        //顾客图片
        $customerImgs = model('CustomerImg')->where(['customer_id' => $customer->ctm_id])->order('weigh', 'DESC')->select();
        $this->view->assign('customerImgs', $customerImgs);

        return $this->view->fetch();
    }

    public function importfromcsv()
    {
        if ($this->request->isPost()) {
            $type = input('type', 'NEW');

            //NEW VIEW
            if ($type == 'NEW') {
                $force   = input('force', false);
                $source  = input('ctm_source', false);
                $explore = input('ctm_explore', false);
                $tool    = input('tool', false);
                $chnType = input('chn_type', false);
                $rvplan  = input('rvplan', false);

                if (empty($source) || empty($source) || empty($source) || empty($chnType)) {
                    $this->error('参数有误');
                } else {
                    $file = request()->file('cstimport');

                    // 移动到框架应用根目录/public/uploads/ 目录下
                    if ($file) {
                        $info = $file->validate(['ext' => 'csv'])->move(APP_PATH . DS . 'data' . DS . 'uploads');
                        if ($info) {
                            $filePath = $info->getSaveName();
                        } else {
                            $this->error($file->getError());
                        }
                    } else {
                        $this->error('请上传文件');
                    }

                    //以日期，命令，参数，管理员ID作为特征值
                    $admin    = $this->view->admin;
                    $command  = 'importcst';
                    $filePath = APP_PATH . 'data' . DS . 'uploads' . DS . $filePath;
                    @chmod($filePath, 0754);
                    $params       = json_encode(['filePath' => $filePath, 'source' => $source, 'explore' => $explore, 'tool' => $tool, 'type' => $chnType, 'rvplan' => $rvplan]);
                    $featureValue = md5(date('Y-m-d') . '_' . $command . '_' . hash_file('md5', $filePath) . '_' . $admin->id);
                    $cmdRecord    = model('CmdRecords')->where(['feature_value' => $featureValue])->where(function ($query) {
                        $query->where('status', '=', CmdRecords::STATUS_COMPLETED)
                            ->whereOr('status', '=', CmdRecords::STATUS_PROCESSING);
                    })->order('id', 'DESC')->find();

                    if (!empty($cmdRecord) && !$force) {
                        $this->error('检测到今日您已上传同样内容的文件', null, $cmdRecord);
                    }

                    $cmdRecord  = model('CmdRecords');
                    $saveResult = $cmdRecord->save(
                        array(
                            'feature_value' => $featureValue,
                            'command'       => $command,
                            'params'        => $params,
                            'admin_id'      => $admin->id,
                        )
                    );

                    if ($saveResult === false) {
                        $this->error('保存任务失败');
                    } else {
                        //开始生成文件并自动更新进度
                        $cmdRecord->startCmd();
                        $this->success('成功开始任务', $url = null, $cmdRecord);
                    }

                }
            } elseif ($type == 'VIEW') {
                $id       = input('id', false);
                $response = new \think\Response();
                $response->contentType('application/json', 'utf-8');
                if ($id) {
                    //获取进度
                    $cmdRecord = model('CmdRecords')->find($id);
                    if ($cmdRecord) {
                        $processInfo = $cmdRecord->getProcessInfo();
                        if (!empty($processInfo)) {
                            $response->data($processInfo);
                            return $response;
                        }
                    }
                }
                $response->data('[]');
                return $response;
            } elseif ($type == 'DELETE') {
                if ($this->auth->isSuperAdmin() || $cmdRecord->admin_id == $admin->id) {
                    //删除
                    if (!empty($cmdRecord->filepath)) {
                        @unlink(APP_PATH . 'data' . iconv('utf8', 'gb2312', $cmdRecord->filepath));
                    }
                    $cmdRecord->delete();

                    $this->success();
                }
            }

            $this->error();
        }

        $typeList       = model('Chntype')->getList();
        $sourceList     = \app\admin\model\Ctmsource::column('sce_name', 'sce_id');
        $toolList       = CocAcceptTool::getList();
        $definedRvPlans = model('Rvplan')->where(['rvp_status' => 1])->column('rvp_name', 'rvp_id');
        $this->view->assign('definedRvPlans', $definedRvPlans);
        $this->view->assign('typeList', $typeList);
        $this->view->assign('sourceList', $sourceList);
        $this->view->assign('toolList', $toolList);
        $this->view->assign('downloadLink', url('/general/attachment/downloadtgz/id/'));

        return $this->view->fetch();
    }

    public function ajaxlist()
    {
        $pageCount                                   = \think\Config::get('ajax_list_page_count');
        list($where, $sort, $order, $offset, $limit) = $this->buildparams();

        $sort  = 'id';
        $order = 'DESC';
        $limit = $pageCount;

        $total = model('CProject')
            ->where($where)
            ->order($sort, $order)
            ->count();

        $list = model('CProject')
            ->where($where)
            ->order($sort, $order)
            ->limit($offset, $limit)
            ->select();

        $result = array("total" => $total, "rows" => $list);

        return json($result);
    }

    public function del($ids = null)
    {
        if ($ids) {
            //删除时不删除数据，将记录隐藏
            $this->model->where(['cst_id' => $ids])->update(['status' => 0]);

            $this->success();
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }

    public function multi($ids = '')
    {

    }
}
