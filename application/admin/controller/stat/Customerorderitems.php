<?php

namespace app\admin\controller\stat;

use app\admin\model\Admin;
use app\admin\model\Report;
use app\common\controller\Backend;
use think\Controller;
use app\admin\model\CustomerBalance;

/**
 * 提成相关
 */
class Customerorderitems extends Backend
{

    protected $noNeedRights = ['details', 'detailsfordevelop', 'detailsforosconsult'];
    /**
     * DailyStat模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 网电，分诊，现场客服业绩
     */
    public function index()
    {
        // date('Y-m-d', strtotime('-30 days', time()))
        $startDate = input('stat_date_start', date('Y-m-d'));
        $endDate   = input('stat_date_end', date('Y-m-d'));

        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null);

            if ($offset == 0) {
                $summary = Report::getCustomerOrderItemSummaryCount($where, true);
                $total   = $summary['count'];
            } else {
                $total   = Report::getCustomerOrderItemSummaryCount($where);
                $summary = array();
            }

            $list = array_values(Report::getCustomerOrderItemSummary($where, $offset, $limit));

            return json(['total' => $total, 'rows' => $list, 'summary' => $summary]);
        }

        $deptList = (new \app\admin\model\Deptment)->getVariousTree();
        // $deptList = \app\admin\model\Deptment::getDeptListCache();
        $briefAdminList = model('Admin')->getBriefAdminList();

        $this->view->assign('deptList', $deptList);
        $this->view->assign('briefAdminList', $briefAdminList);
        $this->view->assign('startDate', $startDate);
        $this->view->assign('endDate', $endDate);

        return $this->view->fetch();
    }

    /**
     * 订单项变动明细
     */
    public function changedetails()
    {
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null);

            $summary = \app\admin\model\OrderChangeLog::getListCount($where, $sort, $order, $offset, $limit, $extraWhere = []);
            $list    = \app\admin\model\OrderChangeLog::getList($where, $sort, $order, $offset, $limit, $extraWhere = []);

            return json(['total' => $summary['count'], 'rows' => $list, 'summary' => $summary]);
        }

        $changeTypeList = ['' => __('None'), \app\admin\model\OrderChangeLog::TYPE_RETURN => __(\app\admin\model\OrderChangeLog::TYPE_RETURN), \app\admin\model\OrderChangeLog::TYPE_SWITCH => __(\app\admin\model\OrderChangeLog::TYPE_SWITCH)];
        $this->view->assign('changeTypeList', $changeTypeList);
        return $this->view->fetch();
    }

    /**
     * 收款业绩
     */
    public function cashdetails()
    {
         //设置过滤方法
        $this->request->filter(['strip_tags']);
        $startDate = input('balance.createtime_start', strtotime(date('Y-m-d')));
        $endDate   = input('balance.createtime_end', strtotime(date('Y-m-d 23:59:59')));

        if ($this->request->isAjax()) {
            list($bWhere, $sort, $order, $offset, $limit, $extraWhere) = $this->dealParams($startDate, $endDate);

            $list = CustomerBalance::getList2($bWhere, $sort, $order, $offset, $limit, $extraWhere);

            if ($offset) {
                $total  = CustomerBalance::getListCount2($bWhere, $extraWhere);
                $result = array("total" => $total, "rows" => $list);
            } else {
                $summary = CustomerBalance::getListCount2($bWhere, $extraWhere, true);

                $result = array("total" => $summary['count'], "rows" => $list, 'summary' => $summary);
            }

            return json($result);
        }

        $deptList = (new \app\admin\model\Deptment)->getVariousTree();
        $this->view->assign('deptList', $deptList);

        $deptmentList     = model('Deptment')->where(['dept_status' => 1])->column('dept_name', 'dept_id');
        $deptmentList[''] = __('NONE');

        $adminList      = model('Admin')->getAdminCache(\app\admin\model\Admin::ADMIN_BRIEF_CACHE_KEY);
        $adminList['0'] = __('NONE');

        $payTypeList       = \app\admin\model\PayType::getList();
        $this->payTypeList = $payTypeList;

        $briefAdminList = model('Admin')->getBriefAdminList2();
        $this->view->assign('briefAdminList', $briefAdminList);

        $this->view->assign('payTypeList', $payTypeList);
        $this->view->assign('deptmentList', $deptmentList);
        $balanceTypeList = \app\admin\model\BalanceType::getList();
        $this->view->assign('balanceTypeList', $balanceTypeList);
        $this->view->assign('adminList', $adminList);

        $refundTypeList = \app\admin\model\BalanceType::getRefundList();
        $this->view->assign('refundTypeList', $refundTypeList);

        $ocsTypeArr = \app\admin\model\Osctype::getList();
        $this->view->assign('ocsTypeArr', $ocsTypeArr);

        $startDate = input('balance.createtime_start', strtotime(date('Y-m-d')));
        $endDate   = input('balance.createtime_end', strtotime(date('Y-m-d 23:59:59')));
        $start     = date('Y-m-d', $startDate);
        $end       = date('Y-m-d', $startDate);
        $this->view->assign("startDate", $start);
        $this->view->assign("endDate", $end);
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
        $toolList = \app\admin\model\CocAcceptTool::getList();
        $this->view->assign('toolList', $toolList);

        return $this->view->fetch();
    }

    // 收款业绩导出
    public function cashdetailsdownload()
    {
        $startDate = input('balance.createtime_start', strtotime(date('Y-m-d')));
        $endDate   = input('balance.createtime_end', strtotime(date('Y-m-d 23:59:59')));
        if (isset($_GET['op']) && $_GET['op'] == '') {
            unset($_GET['op']);
        }
        list($bWhere, $sort, $order, $offset, $limit, $extraWhere) = $this->dealParams($startDate, $endDate);
        \think\Request::instance()->get(['filter' => '']);
        return $this->commondownloadprocess('cashdetailsreport', 'cashdetails detail', $bWhere, $extraWhere);
    }

    /**
     * 订单项变动明细导出
     */
    public function download()
    {

        return $this->commondownloadprocess('Orderitemschangereport', 'changedetails stat');
    }

    public function details()
    {
        return $this->renderDetails('details');
    }

    /**
     * 营销人员项目明细
     */
    public function detailsfordevelop()
    {
        return $this->renderDetails('develop');
    }

    /**
     * 现场客服项目明细
     */
    public function detailsforosconsult()
    {
        return $this->renderDetails('osconsult');
    }

    /**
     * 划扣科室项目明细
     */
    public function detailsfordeductdept()
    {
        return $this->renderDetails('deductdept');
    }

    /**
     * 获取进度信息
     */
    public function downloadprocess()
    {
        $type = input('type', 'index');

        if ($type == 'index') {
            return $this->commondownloadprocess('orderitemsreport', 'Customer booked item summary');
        } else {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null, false);
            $bWhere                                      = [];
            $extraWhere                                  = [];
            foreach ($where as $key => $value) {
                if (strpos($value[0], '.') === false) {
                    $bWhere[$value[0]] = [$value[1], $value[2]];
                } else {
                    $extraWhere[$value[0]] = [$value[1], $value[2]];
                }
            }
            if (empty($_GET['op'])) {
                $extraWhere['order_items.item_paytime'] = ['BETWEEN', [strtotime(date('Y-m-d')), strtotime(date('Y-m-d 23:59:59'))]];
            }

            $this->filterCondition($type, $bWhere, $extraWhere);
            \think\Request::instance()->get(['filter' => '']);
            list($bwhere, $sort, $order, $offset, $limit) = $this->buildparams(null, null, false);

            return $this->commondownloadprocess('orderitemsdetailreport', 'Customer booked item details', [], $extraWhere);
        }
    }

    public function add()
    {
        $this->error(__('Access denied!'));
    }

    /**
     * 编辑
     */
    public function edit($ids = null)
    {
        $this->error(__('Access denied!'));
    }

    /**
     * 删除
     */
    public function del($ids = "")
    {
        $this->error(__('Access denied!'));
    }

    /**
     * 批量更新
     */
    public function multi($ids = "")
    {
        $this->error(__('Access denied!'));
    }

    /**
     * 渲染明细表
     */
    private function renderDetails($type = 'details')
    {
        $toolList      = \app\admin\model\CocAcceptTool::getList();
        $orderTypeList = \app\admin\model\Project::getTypeList();
        $cpdtList      = model('CProject')->column('id, cpdt_name');
        //客户来源
        $ctmSource  = model('Ctmsource')->field('sce_id, sce_name')->where(['sce_status' => 1])->order('sce_sort', 'desc')->select();
        $ctmSrcList = ['' => __('NONE')];
        foreach ($ctmSource as $source) {
            $ctmSrcList[$source['sce_id']] = $source['sce_name'];
        }
        $this->view->assign('ctmSrcList', $ctmSrcList);

        //营销渠道
        $channelLists = model('Ctmchannels')->field('chn_id, chn_name')->where(['chn_status' => 1])->order('chn_sort', 'desc')->select();
        $channelList  = ['' => __('NONE')];
        foreach ($channelLists as $val) {
            $channelList[$val['chn_id']] = $val['chn_name'];
        }
        $this->view->assign('channelList', $channelList);
        $pduList = model('pducat')->where(['pdc_pid' => 0,'pdc_status' => 1])->column('pdc_name', 'pdc_id');
        $this->view->assign('pduList',$pduList);

        $startDate = input('order_items.item_paytime_start', strtotime(date('Y-m-d')));
        $endDate   = input('order_items.item_paytime_end', strtotime(date('Y-m-d 23:59:59')));
        $start     = date('Y-m-d', $startDate);
        $end       = date('Y-m-d', $startDate);
        $this->view->assign("startDate", $start);
        $this->view->assign("endDate", $end);

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

            if (!isset($_GET['op'])) {
                $extraWhere['order_items.item_paytime'] = ['BETWEEN', [$startDate, $endDate]];
            }

            //========================================
            $this->filterCondition($type, $bWhere, $extraWhere);

            //========================================
            $briefAdminList = model('Admin')->getBriefAdminList();
            $deptList       = \app\admin\model\Deptment::getDeptListCache();
            // $toolList = \app\admin\model\CocAcceptTool::getList();

            $summary = Report::getOrderItemsDetailCntNdSummary2($bWhere, $extraWhere, !(bool) $offset);
            $total   = $summary['count'];
            $list    = Report::getOrderItemsDetail2($bWhere, $offset, $limit, $extraWhere);

            foreach ($list as $key => $row) {
                // $list[$key]['item_deduct_total'] = floor(100.00 * $row['item_used_times'] * $row['item_pay_amount_per_time']) / 100;
                $list[$key]['consult_admin_name'] = '';
                if (isset($briefAdminList[$row['consult_admin_id']])) {
                    $list[$key]['consult_admin_name'] = $briefAdminList[$row['consult_admin_id']];
                }
                $list[$key]['osconsult_admin_name'] = '';
                if (isset($briefAdminList[$row['osconsult_admin_id']])) {
                    $list[$key]['osconsult_admin_name'] = $briefAdminList[$row['osconsult_admin_id']];
                }
                $list[$key]['develop_admin_name'] = '';
                if (isset($briefAdminList[$row['develop_admin_id']])) {
                    $list[$key]['develop_admin_name'] = $briefAdminList[$row['develop_admin_id']];
                }
                $list[$key]['recept_admin_name'] = '';
                if (isset($briefAdminList[$row['recept_admin_id']])) {
                    $list[$key]['recept_admin_name'] = $briefAdminList[$row['recept_admin_id']];
                }
                $list[$key]['prescriber_name'] = '';
                if (isset($briefAdminList[$row['prescriber']])) {
                    $list[$key]['prescriber_name'] = $briefAdminList[$row['prescriber']];
                }

                $list[$key]['dept_name'] = '';
                if (isset($deptList[$row['dept_id']])) {
                    $list[$key]['dept_name'] = $deptList[$row['dept_id']]['dept_name'];
                }
                $list[$key]['osc_dept_name'] = '';
                if (isset($deptList[$row['osc_dept_id']])) {
                    $list[$key]['osc_dept_name'] = $deptList[$row['osc_dept_id']];
                }
                // ctm_first_tool_id
                $list[$key]['ctm_first_tool'] = '';
                if (isset($toolList[$row['ctm_first_tool_id']])) {
                    $list[$key]['ctm_first_tool'] = $toolList[$row['ctm_first_tool_id']];
                }

                $list[$key]['ctm_first_cpdt'] = '';
                if (isset($cpdtList[$row['ctm_first_cpdt_id']])) {
                    $list[$key]['ctm_first_cpdt'] = $cpdtList[$row['ctm_first_cpdt_id']];
                }

                $list[$key]['item_type_name'] = '';
                if (isset($orderTypeList[$row['item_type']])) {
                    $list[$key]['item_type_name'] = $orderTypeList[$row['item_type']];
                }
            }

            return json(['total' => $total, 'rows' => $list, 'summary' => $summary]);
        }

        // $deptList = \app\admin\model\Deptment::getDeptListCache();
        $deptList       = (new \app\admin\model\Deptment)->getVariousTree();
        $briefAdminList = model('Admin')->getBriefAdminList2();
        $cProjectList   = \app\admin\model\CProject::getCProjectCache();
        $oscTypeList    = \app\admin\model\Osctype::getList();

        $today = date('Y-m-d');
        // 订单状态
        $orderStatusList = ['' => __('All')];
        foreach (\app\admin\model\OrderItems::getStatusList() as $key => $value) {
            $orderStatusList[$key] = $value;
        }
        $this->view->assign('orderStatusList', $orderStatusList);
        $this->view->assign('today', $today);
        $this->view->assign('deptList', $deptList);
        $this->view->assign('briefAdminList', $briefAdminList);
        $this->view->assign('cProjectList', $cProjectList);
        $this->view->assign('oscTypeList', $oscTypeList);
        $this->view->assign('toolList', $toolList);
        $this->view->assign('type', $type);
        $this->view->assign('orderTypeList', $orderTypeList);
        return $this->view->fetch('stat/customerorderitems/details');
    }

    private function filterCondition($type, &$bWhere, &$extraWhere)
    {
        // 是否是超级管理员
        $admin = \think\Session::get('admin');

        $developAdminFlg = false;
        //营销部门，营销人员筛选基本处理
        $developAdminCon = model('admin')->field('id');

        if (!$this->auth->isSuperAdmin()) {
            //只显示自己的记录，其他人员的不显示
            // $extraWhere = array_merge(['osc_status' => ['neq', -1], 'admin_id' => $admin['id']], $secCondition);
            $deptAdminCon = $this->deptAuth->getAdminCondition($fields = 'id', $admin['id'], false, false);

            if ($type == 'osconsult') {
                if (!empty($extraWhere['order_items.admin_id'])) {
                    $deptAdminCon = $deptAdminCon->where(['id' => $extraWhere['order_items.admin_id']]);
                }
                $extraWhere['order_items.admin_id'] = ['exp', 'in ' . $deptAdminCon->buildSql()];
            } elseif ($type == 'develop') {
                //营销人员版，营销人员相关特殊处理
                $developAdminFlg = true;
                $developAdminCon = $this->deptAuth->getAdminCondition($fields = 'id', $admin['id'], false, false);
            } elseif ($type == 'deductdept') {
                //科室负责人特殊处理
                $selfDeptIds = $this->deptAuth->getDeptIds(true);
                if (is_array($selfDeptIds)) {
                    if (isset($bWhere['order_items.dept_id'])) {
                        $deptTree    = \app\admin\model\Deptment::getDeptTreeCache();
                        $selfDeptIds = array_intersect($selfDeptIds, array($bWhere['order_items.dept_id'][1]));
                    }
                    $bWhere['order_items.dept_id'] = ['in', $selfDeptIds];
                }

            } else {
                $type         = 'details';
                $deptAdminCon = model('admin')->field('id');
                $developFlg   = false;
                if (isset($extraWhere['customer.develop_dept_id'])) {
                    // $deptAdminCon = $deptAdminCon->where(['dept_id' => $extraWhere['customer.develop_dept_id']]);
                    $setDeptIds   = $this->deptAuth->deptTree->getChildrenIds($extraWhere['customer.develop_dept_id'][1], true);
                    $deptAdminCon = $deptAdminCon->where(['dept_id' => ['in', $setDeptIds]]);
                    $developFlg   = true;
                }
                if (!empty($extraWhere['order_items.consult_admin_id'])) {
                    $developFlg   = true;
                    $deptAdminCon = $deptAdminCon->where(['id' => $extraWhere['order_items.consult_admin_id']]);
                }

                // if ($developFlg) {
                //     $extraWhere['customer.admin_id'] = ['exp', 'in ' . $deptAdminCon->field('id')->buildSql()];
                // }
            }
        }

        if (isset($extraWhere['customer.develop_dept_id'])) {
            $developAdminFlg  = true;
            $deptTree         = \app\admin\model\Deptment::getDeptTreeCache();
            $allSelectedDepts = $deptTree->getChildrenIds($extraWhere['customer.develop_dept_id'][1], true);
            $developAdminCon  = $developAdminCon->where(['dept_id' => ['in', $allSelectedDepts]]);
        }
        if (!empty($extraWhere['order_items.consult_admin_id'])) {
            $developAdminFlg = true;
            $developAdminCon = $developAdminCon->where(['id' => $extraWhere['order_items.consult_admin_id']]);
        }
        if ($developAdminFlg) {
            $extraWhere['order_items.consult_admin_id'] = ['exp', 'in ' . $developAdminCon->field('id')->buildSql()];
        }
        if (isset($extraWhere['customer.develop_dept_id'])) {
            unset($extraWhere['customer.develop_dept_id']);
        }
    }

    private function dealParams($startDate, $endDate)
    {
        list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null, false);

        $bWhere     = [];
        $extraWhere = [];
        foreach ($where as $key => $value) {
            $bWhere[$value[0]] = [$value[1], $value[2]];
        }

        //营销部门搜索(上级部门可以显示下级部门数据)
        $developAdminFlg = false;
        $developAdminCon = model('admin')->field('id');

        $admin         = \think\Session::get('admin');
        $adminSecRules = json_decode($admin['sec_rules'], true);
        $isSuperAdmin  = $this->auth->isSuperAdmin();

        //默认取出自身 和 下属科室
        if (isset($bWhere['admin.dept_id'])) {
            $setDeptIds            = $this->deptAuth->deptTree->getChildrenIds($bWhere['admin.dept_id'][1], true);
            $bWhere['admin.dept_id'] = ['in', $setDeptIds];
        }

        if (!$isSuperAdmin) {
            //职员只能查自身业绩
            if ($admin->position == 0) {
                $extraWhere['customer.admin_id'] = $admin->id;
            }  else {
                    //不能查看所有科室数据---组长/主任 限制科室【】
                    if (!$adminSecRules['all']) {
                        $deptIds = $this->deptAuth->getDeptIds(true);
                        if (isset($bWhere['admin.dept_id'])) {
                            $deptIds = array_intersect($deptIds, $bWhere['admin.dept_id'][1]);
                        }

                        if (count($deptIds) == 0) {
                            //没有符合条件的
                            $bWhere['admin.dept_id'] = ['=', -1];
                        } elseif (count($deptIds) == 1) {
                            $bWhere['admin.dept_id'] = ['=', current($deptIds)];
                        } else {
                            $bWhere['admin.dept_id'] = ['in', $deptIds];
                        }
                    }
                }
        }

        $developAdminFlg  = false;
        $developAdminFlg2 = false;
        $developAdminCon  = (new \app\admin\model\Admin);
        $developAdminCon2 = (new \app\admin\model\Admin);

        if (isset($bWhere['admin.dept_id'])) {
            $developAdminFlg2 = true;
            $developAdminCon2 = $developAdminCon2->where(['dept_id' => $bWhere['admin.dept_id']]);
        }
        if (!empty($bWhere['customer.admin_id'])) {
            $developAdminFlg2 = true;
            $developAdminCon2 = $developAdminCon2->where(['id' => $bWhere['customer.admin_id']]);
        }
        if ($developAdminFlg2) {
            $bWhere['customer.admin_id'] = ['exp', 'in ' . $developAdminCon2->field('id')->buildSql()];
        }
        if (isset($bWhere['admin.dept_id'])) {
            unset($bWhere['admin.dept_id']);
        }

        // if (isset($bWhere['admin.dept_id'])) {
        //     $developAdminFlg  = true;
        //     $deptTree         = \app\admin\model\Deptment::getDeptTreeCache();
        //     $allSelectedDepts = $deptTree->getChildrenIds($bWhere['admin.dept_id'][1], true);
        //     $developAdminCon  = $developAdminCon->where(['dept_id' => ['in', $allSelectedDepts]]);
        // }
        // if (!empty($bWhere['customer.admin_id'])) {
        //     $developAdminFlg = true;
        //     $developAdminCon = $developAdminCon->where(['id' => $bWhere['customer.admin_id']]);
        // }
        // if ($developAdminFlg) {
        //     $bWhere['customer.admin_id'] = ['exp', 'in ' . $developAdminCon->buildSql()];
        // }
        // if (isset($bWhere['admin.dept_id'])) {
        //     unset($bWhere['admin.dept_id']);
        // }

        //首次查询时显示当日
        $timewhere = [];
        if (!isset($_GET['op'])) {
            $bWhere['balance.createtime'] = ['BETWEEN', [$startDate, $endDate]];
        }

        return [$bWhere, $sort, $order, $offset, $limit, $extraWhere];
    }

}
