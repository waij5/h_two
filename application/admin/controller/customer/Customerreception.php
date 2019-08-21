<?php

namespace app\admin\controller\customer;

use app\admin\model\CustomerOsconsult as MCustomerOsconsult;
use app\admin\model\Osctype;
use app\common\controller\Backend;
use think\Controller;
use think\Request;
use think\Session;
use \think\Config;
use \think\Db;

/**
 * 分诊接待
 */
class Customerreception extends Backend
{

    /**
     * CustomerBook模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Customerosconsult');
        //客户类型
        $ctmtypeLists = model('customertype')->field('type_id,type_name')->select();
        $ctmtypeList  = ['' => __('NONE')];
        foreach ($ctmtypeLists as $ctmtype) {
            $ctmtypeList[$ctmtype['type_id']] = $ctmtype['type_name'];
        }
        $this->view->assign("ctmtypeList", $ctmtypeList);

        $deptList = \app\admin\model\Deptment::getDeptListCache();
        $this->view->assign('deptList', $deptList);
        $this->view->assign('CtmdeptList', (new \app\admin\model\deptment)->getVariousTree());
        // 受理工具
        $toolList = \app\admin\model\CocAcceptTool::getList();
        $this->view->assign('toolList', $toolList);
        // 客户来源
        $ctmSource  = model('Ctmsource')->field('sce_id, sce_name')->where(['sce_status' => 1])->order('sce_sort', 'desc')->select();
        $ctmSrcList = ['' => __('NONE')];
        foreach ($ctmSource as $source) {
            $ctmSrcList[$source['sce_id']] = $source['sce_name'];
        }
        $this->view->assign('ctmSrcList', $ctmSrcList);


    }

    public function index()
    {
        // getSecCondition()
        //设置过滤方法
        $this->request->filter(['strip_tags']);

        $startDate = input('createtime_start', strtotime(date('Y-m-d')));
        $endDate   = input('createtime_end', strtotime(date('Y-m-d 23:59:59')));

        $start = date('Y-m-d', $startDate);
        $end   = date('Y-m-d', $startDate);
        $this->view->assign("startDate", $start);
        $this->view->assign("endDate", $end);

        $briefAdminList = model('Admin')->getBriefAdminList2();
        $this->view->assign('briefAdminList', $briefAdminList);

        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name')) {
                return $this->selectpage();
            }

            list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null, false);
            $bWhere                                      = [];
            $extraWhere                                  = [];
            foreach ($where as $key => $value) {
                    $bWhere[$value[0]] = [$value[1], $value[2]];
            }
            //首次查询时显示当日
            $timewhere = [];
            if (!isset($_GET['op'])) {
                $bWhere['coc.createtime'] = ['BETWEEN', [$startDate, $endDate]];
            }
            //删除的不显示
            $extraWhere['coc.is_delete'] = 0;


            //营销部门搜索(上级部门可以显示下级部门数据)
            $deptTree         = \app\admin\model\Deptment::getDeptTreeCache();
            $developAdminFlg = false;
            $developAdminCon = model('admin')->field('id');
            if (isset($bWhere['admin.dept_id'])) {
                $developAdminFlg  = true;
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

            $deptAdminCon = model('Admin')->field('id');//$this->deptAuth->getAdminCondition($fields = 'id', $this->view->admin['id'], false, false);

            $deptTree    = \app\admin\model\Deptment::getDeptTreeCache();
            $oscAdminFlg = false;
            if (!empty($bWhere['coc.admin_id'])) {
                $deptAdminCon = $deptAdminCon->where(['id' => $bWhere['coc.admin_id']]);
                $oscAdminFlg = true;
            }
            if (!empty($bWhere['coc.admin_dept_id'])) {
                $selectedOscDeptIds = $deptTree->getChildrenIds($bWhere['coc.admin_dept_id'][1], true);
                $deptAdminCon = $deptAdminCon->where(['dept_id' => ['in', $selectedOscDeptIds]]);
                $oscAdminFlg = true;
                unset($bWhere['coc.admin_dept_id']);
            }

            if ($oscAdminFlg) {
                $bWhere['coc.admin_id'] = ['exp', 'in ' . $deptAdminCon->buildSql()];
            }
            

            $total = $this->model->getListCount($bWhere, $extraWhere);
            $list  = $this->model->getList($bWhere, $sort, $order, $offset, $limit, $extraWhere);

            if ($offset) {
                $result = array("total" => $total, "rows" => $list);
            } else {
                $oscSummary = $this->model->getListTypeSummary($bWhere, $extraWhere);
                $result     = array("total" => $total, "rows" => $list, 'summary' => $oscSummary);
            }
            return json($result);
        }

        $oscStatusArr = MCustomerOsconsult::getStatusList();
        $this->view->assign('oscStatusArr', $oscStatusArr);

        $ocsTypeArr[''] = __('All');
        $ocsTypeArr2    = Osctype::getList();
        foreach ($ocsTypeArr2 as $key => $value) {
            $ocsTypeArr[$key] = $value;
        }
        $this->view->assign('ocsTypeArr', $ocsTypeArr2);

        return $this->view->fetch();
    }

    /**
     * 新增现场客服
     * 可能会同时新增顾客----顾客不存在的情况
     * 为防止新增客户成功而保存客服失败，之后重新提交，两次新增客户
     * 采用事务
     */
    public function add()
    {
        //pririoty
        $consultId  = input('consult_id', false);
        $customerId = input('customer_id', false);
        $phone      = trim(input('phone', ''));
        $force      = input('force', false);
        //预约的网电客服内容， 自动填入本次客服内容中
        $preConsultContent = '';
        $consultDeptId     = null;
        $consultCpdtId     = null;
        $ctmDepositamt     = 0;
        $NoCome            = false; 

        if (!empty($customerId)) {
            $customer = model('Customer')->get($customerId);
            if (empty($customer)) {
                $this->error(__('Customer does not exist.', $customerId));
            }
        } else {
            $customer = model('Customer')->fade(['ctm_mobile' => $phone, 'ctm_tel' => $phone]);
        }

        //实际保存区
        if ($this->request->isPost()) {
            $osconsultParams = $this->request->post("row/a");
            $customerParams  = $this->request->post("customer/a");

            $admin = Session::get('admin');
            //新增，==
            if (empty($osconsultParams['admin_id'])) {
                $osconsultParams['admin_id'] = $admin['id'];
            }

            if (empty($osconsultParams['cpdt_id'])) {
                $this->error('请选择客服项目');
            }
            $oscCpdtId = $osconsultParams['cpdt_id'];
            $oscDeptId = isset($osconsultParams['dept_id']) ? $osconsultParams['dept_id'] : 0;

            Db::startTrans();
            //只要经过分诊肯定显示已上门
            $customerParams['arrive_status'] = 1;
            if (empty($customer['ctm_id'])) {
                $customerParams['ctm_first_tool_id'] = isset($osconsultParams['tool_id']) ? $osconsultParams['tool_id'] : 0;
                $customerParams['ctm_first_dept_id'] = $oscDeptId;
                $customerParams['ctm_first_cpdt_id'] = $oscCpdtId;
            } else {
                if ($customer['ctm_is_public']) {
                    $customerParams['ctm_is_public']   = 0;
                    $customerParams['ctm_public_time'] = time();
                }
                if ($customer['ctm_is_cst_public']) {
                    $customerParams['ctm_is_cst_public'] = 0;
                }
            }

            //首次到诊信息为空时，更新首次到诊信息
            if (empty($customer->ctm_first_recept_time)) {
                $customerParams['ctm_first_recept_time'] = time();
                $customerParams['ctm_first_osc_admin']   = $osconsultParams['admin_id'];
                $customerParams['ctm_first_osc_dept_id'] = $oscDeptId;
                $customerParams['ctm_first_osc_cpdt_id'] = $oscCpdtId;
            }

            //更新最近到诊信息
            $customerParams['ctm_last_recept_time'] = time();
            $customerParams['ctm_last_osc_admin']   = $osconsultParams['admin_id'];
            $customerParams['ctm_last_osc_dept_id'] = $oscDeptId;
            $customerParams['ctm_last_osc_cpdt_id'] = $oscCpdtId;

        
            // 手机号和电话号码双判断
            $errMsg = '';
            if (!empty($customerParams['ctm_tel'])) {
                $phoneId = model('customer')->where(['ctm_mobile' => $customerParams['ctm_tel']])->column('ctm_id');
                if ($phoneId && $phoneId['0'] != $customer['ctm_id']) {
                     $errMsg = ',联系电话已有手机号码使用';
                     // $this->error('联系电话已有手机号码使用');
                } else {
                    $telId = model('customer')->where(['ctm_tel' => $customerParams['ctm_tel']])->column('ctm_id');
                    if ($telId &&  $telId['0'] != $customer['ctm_id']) {
                        $errMsg = ',电话号码已存在';
                        // $this->error('电话号码已存在');
                    }
                }
            }
            
            // 分诊时候手机电话双查询，最后在保存成功时添加提示
            // $doesPhoneExist = (bool)model('customer')->where(function($query) use () {
            //     $query->where(['ctm_mobile' => $customerParams['ctm_tel']])
            //         ->whereOr(['ctm_tel' => $customerParams['ctm_tel']])
            //         ->whereOr(['ctm_tel' => $customerParams['ctm_mobile']])})
            //         ->where('ctm_id', '<>', $customer['ctm_id'])
            //         ->count();

            // if ($doesPhoneExist) {
            //     if ($customer['ctm_id']) {
            //         $errMsg = ',联系方式已存在!';
            //     } else {
            //         $this->error(__('联系方式(手机/号码)已存在, 无法保存'));
            //     }
            // }

            //保存顾客信息
            $saveCustomerRes = model('Customer')->checkNdSave($customerParams);
            if ($saveCustomerRes['error']) {
                Db::rollback();
                $this->error($saveCustomerRes['msg']);
            }

            $customerId = $saveCustomerRes['customer_id'];
            //对应预约处理----更新预约到诊 / 清除现场客服对应的网电客服
            if (!empty($osconsultParams['consult_id'])) {
                // 'cst_status' => 1,  之前客服，去除预约情况
                $preConsult = model('Customerconsult')->where(['cst_id' => $osconsultParams['consult_id'], 'status' => 1])->find();
                if (!empty($preConsult)) {
                    $preConsult->save(['cst_status' => 2]);
                } else {
                    unset($osconsultParams['consult_id']);
                }
            }

            $osconsultParams['customer_id'] = $customerId;
            //指派人员
            $osconsultParams['operator'] = $admin['id'];
            $osconsultParams['cpdt_id']  = $oscCpdtId;
            if (($saveRes = model('Customerosconsult')->save($osconsultParams)) == false) {
                Db::rollback();
                $this->error(__('Failed while trying to save osconsult data！'));
            }

            //更新顾客 首次，最近到诊现场客服ID
            $updateCustomerInfo = array();
            if (isset($customerParams['ctm_first_recept_time'])) {
                $updateCustomerInfo['ctm_first_osc_id'] = model('Customerosconsult')->osc_id;
            }
            $updateCustomerInfo['ctm_last_osc_id'] = model('Customerosconsult')->osc_id;
            $customer->save($updateCustomerInfo);

            Db::commit();
            $this->success(__('All data saved successfully.'));
        }

        //客服项目
        $cprolists = model('CProject')->field('id,cpdt_name')->where(['cpdt_status' => 1])->select();
        $List      = ['' => __('NONE')];
        foreach ($cprolists as $cprolist) {
            $List[$cprolist['id']] = $cprolist['cpdt_name'];
        }
        $this->view->assign('List', $List);

        $this->customerExtraInit();

        if ($customer['ctm_id']) {
            $consult = model('Customerconsult')->where(['customer_id' => $customer['ctm_id']])->order('createtime', 'DESC')->find();

            //为空不中断， 改为customerId处理
            if ($consult) {
                $customerId        = $consult['customer_id'];
                $consultDeptId     = $consult['dept_id'];
                $consultCpdtId     = $consult['cpdt_id'];
                $preConsultContent = $consult['cst_content'];
                $consultId         = $consult['cst_id'];

                $consultCustomer = model('customer')->where(['ctm_id' => $customer['ctm_id']])->find();
                if ($consultCustomer) {
                    $arriveStatus = $consultCustomer->arrive_status;
                    if($arriveStatus == 0) {
                        $NoCome  = true;
                        $ctmDepositamt = $consultCustomer->ctm_depositamt;
                    }
                }
            }
        }
        
        
        $cpdt_name = model('CProject')->field('cpdt_name')->where(['id' => $consultCpdtId])->find();
        if (empty($cpdt_name)) {
            $cpdt_name['cpdt_name'] = '';
        }
        $this->view->assign("cpdt_name", $cpdt_name);

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
        $this->view->assign("recCustomerName", $recCustomerName);

        $this->view->assign('consultId', $consultId);
        $this->view->assign('customer', $customer);
        $this->view->assign('preConsultContent', $preConsultContent);
        $this->view->assign('consultDeptId', $consultDeptId);
        $this->view->assign('consultCpdtId', $consultCpdtId);
        $this->view->assign('ctmDepositamt', $ctmDepositamt);
        $this->view->assign('NoCome', $NoCome);
        $cpdtName = '';
        $cpdtList = \app\admin\model\CProject::cProjectArrayCache();
        if (isset($cpdtList[$consultCpdtId])) {
            $cpdtName = $cpdtList[$consultCpdtId]['cpdt_name'] . '|' . $cpdtList[$consultCpdtId]['cpdt_type'];
        }
        $this->view->assign('cpdtName', $cpdtName);

        //前次现场客服师ID
        $lastOscStaffId = 0;
        if ($customer->ctm_id) {
            // $lastOsconsult = $this->model->where(['customer_id' => $customer->ctm_id])->order('createtime', 'desc')->find();

            $lastOsconsult  = null;
            $lastOsconsults = $this->model->getList(['coc.customer_id' => $customer->ctm_id], 'createtime', 'desc', 0, 1, []);

            if (!empty($lastOsconsults)) {
                $lastOsconsult = current($lastOsconsults);
                // ->admin_id
                $lastOscStaffId = $lastOsconsult->admin_id;

                $todayStartTime = strtotime(date('Y-m-d'));
                $todayEndTime   = strtotime(date('Y-m-d 23:59:59'));
                if (!$force && ($lastOsconsult->createtime >= $todayStartTime && $lastOsconsult->createtime <= $todayEndTime)) {
                    // && $lastOsconsult->createtime
                    $this->view->assign('showReceptNotice', true);
                    $this->view->assign('lastOsconsult', $lastOsconsult);
                    $this->view->assign('forceUrl', $this->request->url(true) . (strpos($this->request->url(true), '?') === false ? '?' : '&') . 'force=1');
                    return $this->view->fetch();
                }
            }

            //顾客图片
            $customerImgs = model('CustomerImg')->where(['customer_id' => $customer->ctm_id])->order('weigh', 'DESC')->select();
            $this->view->assign('customerImgs', $customerImgs);
        }
        $this->view->assign('lastOscStaffId', $lastOscStaffId);
        //项目类型
        $pducats = model('Pducat')->field('pdc_id, pdc_name')->where(['pdc_status' => 1])->order('pdc_sort', 'DESC')->order('pdc_id', 'ASC')->select();
        $pdcList = ['' => __('NONE')];
        foreach ($pducats as $pducat) {
            $pdcList[$pducat['pdc_id']] = $pducat['pdc_name'];
        }
        $this->view->assign('pdcList', $pdcList);

        //客服人员
        $siteConfig      = \think\Config::get('site');
        $osconsultDeptId = isset($siteConfig['osconsult_dept_id']) ? @floatval($siteConfig['osconsult_dept_id']) : 14;
        // $admins    = model('Admin')->where(['dept_id' => $osconsultDeptId])->select();
        $deptTree         = \app\admin\model\Deptment::getDeptTreeCache();
        $osconsultDeptIds = ($deptTree->getChildrenIds($osconsultDeptId, true));

        $admins    = model('Admin')->where(['dept_id' => ['in', $osconsultDeptIds]])->select();
        $adminList = array();
        foreach ($admins as $admin) {
            $adminList[$admin['id']] = $admin['nickname'];
        }

        $briefAdminList    = model('Admin')->getBriefAdminList2();
        $ctmOsconsultAdmin = __('None');
        if (!empty($customer['ctm_osconsult_admin'])) {
            $ctmOsconsultAdmin = '<' . $customer['ctm_osconsult_admin'] . '>';
            if (isset($briefAdminList[$customer['ctm_osconsult_admin']])) {
                $ctmOsconsultAdmin .= $briefAdminList[$customer['ctm_osconsult_admin']];
            }
        }

        $this->view->assign('briefAdminList', $briefAdminList);
        $this->view->assign('adminList', $adminList);
        $this->view->assign('ctmOsconsultAdmin', $ctmOsconsultAdmin);

        $definedRvPlans = model('Rvplan')->where(['rvp_status' => 1])->column('rvp_name', 'rvp_id');
        $this->view->assign('definedRvPlans', $definedRvPlans);

        return $this->view->fetch();
    }

    /**
     * 对 指派未接受，现场客服因故拒绝的现场到诊 进行重新指派
     */
    public function reassign($ids)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        } elseif ($row['osc_status'] != 0 && $row['osc_status'] != -1) {
            $this->error(__('Osconsult %s can not be reassigned!', $ids));
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                try
                {
                    if (!empty($params['admin_id'])) {
                        $data   = ['admin_id' => $params['admin_id'], 'osc_status' => 0];
                        $result = $row->save($data);

                        if ($result !== false) {
                            $customer = model('Customer')->find($row->customer_id);
                            if ($customer->ctm_id) {
                                $cusFlag = false;
                                if ($customer->ctm_first_osc_id == $row->osc_id) {
                                    $customer->ctm_first_osc_admin = $row['admin_id'];
                                    $cusFlag                       = true;
                                }
                                if ($customer->ctm_last_osc_id == $row->osc_id) {
                                    $customer->ctm_last_osc_admin = $row['admin_id'];
                                    $cusFlag                      = true;
                                }
                                if ($cusFlag) {
                                    $customer->save();
                                }
                            }
                            $this->success();
                        } else {
                            $this->error($row->getError());
                        }
                    }
                    $this->error(__('Admin_id can not be empty'));
                } catch (think\exception\PDOException $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty'));
        }

        //客服人员
        $siteConfig      = \think\Config::get('site');
        $osconsultDeptId = isset($siteConfig['osconsult_dept_id']) ? @floatval($siteConfig['osconsult_dept_id']) : 14;

        $deptTree         = \app\admin\model\Deptment::getDeptTreeCache();
        $osconsultDeptIds = ($deptTree->getChildrenIds($osconsultDeptId, true));

        $admins = model('Admin')->where(['dept_id' => ['in', $osconsultDeptIds]])->select();

        $adminList = array();
        foreach ($admins as $admin) {
            $adminList[$admin['id']] = $admin['nickname'];
        }

        $this->view->assign('adminList', $adminList);
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

    /**
     * 对于未指派, 或指派后未接受的直接结束客服
     * 客户已离场状态值 -3
     */
    public function close($ids = "")
    {
        if ($ids) {
            $osconsults   = $this->model->select($ids);
            $successCount = 0;

            foreach ($osconsults as $row) {
                if ($row['osc_status'] == -1 || $row['osc_status'] == 0) {
                    $res = $row->save(['osc_status' => -3]);
                    $successCount += $res;
                }
            }

            $this->success(__('Completed! %s items changed.', [$successCount]));
        }

        $this->error(_('Parameter %s can not be empty', 'ids'));
    }

    //获取客服人员
    public function getAdmin()
    {
        //科室id
        $type = $this->request->post("type");

        // $list = model('admin')->where(['dept_id' => $type])->select();
        $list = model('admin')->select();

        if (!empty($list)) {
            $data = array();
            foreach ($list as $admin) {
                $data[$admin['id']] = $admin['nickname'];
            }
        } else {
            $data = [];
        }

        return json($data);
    }

    public function edit($ids = null)
    {
        $this->error(__('Access denied!'));
    }

    public function del($ids = "")
    {
        if ($ids) {
            // $count = $this->model->destroy($ids);
            // if ($count) {
            //     $this->success();
            // }
            $this->model->where(['osc_id' => $ids])->update(['is_delete' => 1]);
            $this->success();
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }

    public function multi($ids = "")
    {
        $this->error(__('Access denied!'));
    }
}
