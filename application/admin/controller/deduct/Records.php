<?php

namespace app\admin\controller\deduct;

use app\admin\model\DeductRecords;
use app\admin\model\Deptment;
use app\admin\model\OrderItems;
use app\common\controller\Backend;
use think\Controller;
use think\Request;
use app\admin\model\Osctype;

/**
 *
 *
 * @icon fa fa-circle-o
 */
class Records extends Backend
{

    /**
     * DeductRecords模型对象
     */
    protected $model = null;

    protected $noNeedRight = ['listforitem'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('DeductRecords');
        // $this->deptlist = model('Deptment')->getVariousTree("dept_id", "dept_id", "desc", "dept_pid", "dept_name");
        // $deptdata       = [];
        // foreach ($this->deptlist as $k => $v) {
        //     $deptdata[$v['id']] = $v['dept_name'];
        // }
        $deptdata = (new \app\admin\model\Deptment)->getVariousTree();
        $toolList = \app\admin\model\CocAcceptTool::getList();
        $this->view->assign("toolList", $toolList);
        $this->view->assign("deptdata", $deptdata);
        $ocsTypeArr = Osctype::getList();
        $this->view->assign('ocsTypeArr', $ocsTypeArr);
        $pduList = model('pducat')->where(['pdc_pid' => 0,'pdc_status' => 1])->column('pdc_name', 'pdc_id');
        $this->view->assign('pduList',$pduList);
    }

    public function index()
    {
        return $this->renderList('DEDUCT');
    }

    public function listforosc()
    {
        return $this->renderList('OSC');
    }

    public function listforitem($ids = '')
    {
        $where      = array('deduct_records.order_item_id' => $ids);
        $extraWhere = array();
        $sort       = 'deduct_records.createtime';
        $order      = 'ASC';
        $tList      = DeductRecords::getRecords($where, $sort, $order, $offset = 0, $limit = null, $extraWhere);

        $briefAdminList = model('Admin')->getBriefAdminList();
        $recordIds      = array_keys($tList);
        $staffRecords   = model('DeductStaffRecords')
            ->alias('staff_records')
            ->where(['deduct_record_id' => ['in', $recordIds]])
            ->join(\think\Db::getTable('deduct_role') . ' deduct_role', 'deduct_role.id = staff_records.deduct_role_id', 'LEFT')
            ->order('staff_records.id', 'ASC')
            ->order('staff_records.deduct_role_id', 'ASC')
            // ->select();
            ->column('staff_records.*, deduct_role.name as role_name, deduct_role.percent as role_percent', 'staff_records.id');

        $staffInfos = array();
        foreach ($staffRecords as $key => $staffRecord) {
            if (!isset($staffInfos[$staffRecord['deduct_record_id']])) {
                $staffInfos[$staffRecord['deduct_record_id']] = array();
            }

            if (!isset($staffInfos[$staffRecord['deduct_record_id']][$staffRecord['deduct_role_id']])) {
                $staffInfos[$staffRecord['deduct_record_id']][$staffRecord['deduct_role_id']] = array(
                    'role_name'    => $staffRecord['role_name'],
                    'role_percent' => $staffRecord['role_percent'],
                    'role_staffs'  => array(),
                );
            }

            $staffRecord['admin_name'] = @$briefAdminList[$staffRecord['admin_id']];
            array_push($staffInfos[$staffRecord['deduct_record_id']][$staffRecord['deduct_role_id']]['role_staffs'], $staffRecord);
        }
        unset($staffRecords);

        $deptList = model('Deptment')->getDeptListCache();

        foreach ($tList as $key => $row) {
            if (isset($briefAdminList[$row['admin_id']])) {
                $tList[$key]['admin_nickname'] = $briefAdminList[$row['admin_id']];
            } else {
                $tList[$key]['admin_nickname'] = '';
            }
            if (isset($briefAdminList[$row['osconsult_admin_id']])) {
                $tList[$key]['osconsult_admin_name'] = $briefAdminList[$row['osconsult_admin_id']];
            } else {
                $tList[$key]['osconsult_admin_name'] = '';
            }
            if (isset($briefAdminList[$row['recept_admin_id']])) {
                $tList[$key]['recept_admin_name'] = $briefAdminList[$row['recept_admin_id']];
            } else {
                $tList[$key]['recept_admin_name'] = '';
            }

            if (isset($staffInfos[$row['id']])) {
                $tList[$key]['staff_records'] = $staffInfos[$row['id']];
            } else {
                $tList[$key]['staff_records'] = array();
            }
            $tList[$key]['dept_name'] = '';
            if (isset($deptList[$row['dept_id']])) {
                $tList[$key]['dept_name'] = $deptList[$row['dept_id']]['dept_name'];
            }
            //为满足特定格式
            // array_push($list, $tList[$key]);
            // unset($tList[$key]);
        }
        // unset($tList);

        $this->view->assign('list', $tList);

        $this->view->assign('deductStatusList', DeductRecords::getStatusList());

        $this->view->engine->layout(false);
        echo $this->view->fetch();
    }

    /**
     * 导出
     */
    public function downloadprocess()
    {
        $type = input('type', false);
        // if ($this->auth->check())
        list($bWhere, $extraWhere, $sort, $order, $offset, $limit) = $this->generateRecWhere($type);
        \think\Request::instance()->get(['filter' => '']);

        return $this->commondownloadprocess('deductrecordsreport', 'Deduct records', $bWhere, $extraWhere);
    }

    /**
     * 订单未出货列表
     */
    public function undeliveriedlist($ids = '')
    {
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null, false);

            $where = array('order_items.item_id' => $ids);
            $total = OrderItems::getUndeliverdListCount($where);
            $list  = OrderItems::getUndeliverdList($where, $sort, $order, $offset, $limit);

            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }

        $this->view->assign('ids', $ids);

        return $this->view->fetch();
    }

    /**
     * 反划扣
     */
    public function reverse($ids = null)
    {
        $oldDeductRecord = model('DeductRecords')->find($ids);

        if (!$oldDeductRecord) {
            $this->error();
        }

        if (!$this->deptAuth->checkAuth($oldDeductRecord->admin_id, false)) {
            $this->error(__('You have no permission'));
        }

        $result = ($oldDeductRecord->reverseDeduct(\think\Session::get('admin')->id));
        if ($result['error']) {
            $this->error($result['msg']);
        } else {
            $this->success($result['msg']);
        }
    }

    /**
     * 批量反划扣
     * @param string $ids 待反划扣的划扣记录ID（逗号分隔）
     */
    public function batchreverse($ids = '', $itemType = 'project')
    {
        $deductIds = @explode(',', $ids);
        if (empty($deductIds)) {
            return __('Parameter %s is empty or Invalid!');
        }

        $admin = \think\Session::get('admin');
        return json($this->model->batchReverseDeduct($deductIds, $admin->id));
    }

    public function edit($ids = null)
    {
        $this->error('Access denied!');
    }

    public function delete($ids = '')
    {
        $this->error('Access denied!');
    }

    public function multi($ids = "")
    {
        $this->error('Access denied!');
    }

    public function deductImg()
    {
        $deductRecordsId = input('deductRecordsId');
        // var_dump($deductRecordsId);die;
        $imgId = model('DeductRecordImg')->where(['deduct_record_id' => $deductRecordsId])->column('deduct_img_id');

        $url = model('DeductImg')->where(['id' => ['in', $imgId]])->column('url', 'id');

        return json($url);
    }

    private function renderList($type)
    {
        // 营销渠道
        $channelLists = model('Ctmchannels')->field('chn_id, chn_name')->where(['chn_status' => 1])->order('chn_sort', 'desc')->select();
        $channelList  = ['' => __('NONE')];
        foreach ($channelLists as $source) {
            $channelList[$source['chn_id']] = $source['chn_name'];
        }
        $this->view->assign('channelList', $channelList);
        //客户来源
        $ctmSource  = model('Ctmsource')->field('sce_id, sce_name')->where(['sce_status' => 1])->order('sce_sort', 'desc')->select();
        $ctmSrcList = ['' => __('NONE')];
        foreach ($ctmSource as $source) {
            $ctmSrcList[$source['sce_id']] = $source['sce_name'];
        }
        $this->view->assign('ctmSrcList', $ctmSrcList);

        //设置过滤方法
        $this->request->filter(['strip_tags']);
        $orderItemId = input('order_item_id', false);

        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name')) {
                return $this->selectpage();
            }
            list($bWhere, $extraWhere, $sort, $order, $offset, $limit) = $this->generateRecWhere($type);

            $summary = DeductRecords::getRecordsCntNdSummary($bWhere, $extraWhere, !(bool) $offset);
            $total   = $summary['count'];
            $tList   = DeductRecords::getRecords($bWhere, $sort, $order, $offset, $limit, $extraWhere);

            $briefAdminList = model('Admin')->getBriefAdminList();
            $recordIds      = array_keys($tList);
            $staffRecords   = model('DeductStaffRecords')
                ->alias('staff_records')
                ->where(['deduct_record_id' => ['in', $recordIds]])
                ->join(\think\Db::getTable('deduct_role') . ' deduct_role', 'deduct_role.id = staff_records.deduct_role_id', 'LEFT')
                ->order('staff_records.id', 'ASC')
                ->order('staff_records.deduct_role_id', 'ASC')
                // ->select();
                ->column('staff_records.*, deduct_role.name as role_name, deduct_role.percent as role_percent', 'staff_records.id');

            $staffInfos = array();
            foreach ($staffRecords as $key => $staffRecord) {
                if (!isset($staffInfos[$staffRecord['deduct_record_id']])) {
                    $staffInfos[$staffRecord['deduct_record_id']] = array();
                }

                if (!isset($staffInfos[$staffRecord['deduct_record_id']][$staffRecord['deduct_role_id']])) {
                    $staffInfos[$staffRecord['deduct_record_id']][$staffRecord['deduct_role_id']] = array(
                        'role_name'    => $staffRecord['role_name'],
                        'role_percent' => $staffRecord['role_percent'],
                        'role_staffs'  => array(),
                    );
                }

                $staffRecord['admin_name'] = @$briefAdminList[$staffRecord['admin_id']];
                array_push($staffInfos[$staffRecord['deduct_record_id']][$staffRecord['deduct_role_id']]['role_staffs'], $staffRecord);
            }
            unset($staffRecords);

            $deptList = model('Deptment')->getDeptListCache();
             //类别
            $pdcList = model('pducat')->column('pdc_name', 'pdc_id');
            $list     = array();
            foreach ($tList as $key => $row) {
                if (isset($briefAdminList[$row['admin_id']])) {
                    $tList[$key]['admin_nickname'] = $briefAdminList[$row['admin_id']];
                } else {
                    $tList[$key]['admin_nickname'] = '';
                }
                if (isset($briefAdminList[$row['consult_admin_id']])) {
                    $tList[$key]['consult_admin_name'] = $briefAdminList[$row['consult_admin_id']];
                } else {
                    $tList[$key]['consult_admin_name'] = '';
                }
                if (isset($briefAdminList[$row['osconsult_admin_id']])) {
                    $tList[$key]['osconsult_admin_name'] = $briefAdminList[$row['osconsult_admin_id']];
                } else {
                    $tList[$key]['osconsult_admin_name'] = '';
                }
                if (isset($briefAdminList[$row['recept_admin_id']])) {
                    $tList[$key]['recept_admin_name'] = $briefAdminList[$row['recept_admin_id']];
                } else {
                    $tList[$key]['recept_admin_name'] = '';
                }

                if (isset($briefAdminList[$row['prescriber']])) {
                    $tList[$key]['prescriber_name'] = $briefAdminList[$row['prescriber']];
                } else {
                    $tList[$key]['prescriber_name'] = '';
                }

                if (isset($staffInfos[$row['id']])) {
                    $tList[$key]['staff_records'] = $staffInfos[$row['id']];
                } else {
                    $tList[$key]['staff_records'] = array();
                }
                $tList[$key]['dept_name'] = '';
                if (isset($deptList[$row['dept_id']])) {
                    $tList[$key]['dept_name'] = $deptList[$row['dept_id']]['dept_name'];
                }
                $tList[$key]['dept_name'] = '';
                if (isset($deptList[$row['dept_id']])) {
                    $tList[$key]['dept_name'] = $deptList[$row['dept_id']]['dept_name'];
                }
                $tList[$key]['dept_name'] = '';
                if (isset($deptList[$row['dept_id']])) {
                    $tList[$key]['dept_name'] = $deptList[$row['dept_id']]['dept_name'];
                }
                if (isset($channelList[$row['ctm_explore']])) {
                    $tList[$key]['ctm_explore'] = $channelList[$row['ctm_explore']];
                } else {
                    $tList[$key]['ctm_explore'] = '';
                }
                if (isset($ctmSrcList[$row['ctm_source']])) {
                    $tList[$key]['ctm_source'] = $ctmSrcList[$row['ctm_source']];
                } else {
                    $tList[$key]['ctm_source'] = '';
                }

                if(isset($pdcList[$row['pro_cat1']])){
                    $tList[$key]['pro_cat1'] = $pdcList[$row['pro_cat1']];
                }else{
                    $tList[$key]['pro_cat1'] = '';
                }
                if(isset($pdcList[$row['pro_cat2']])){
                    $tList[$key]['pro_cat2'] = $pdcList[$row['pro_cat2']];
                }else{
                    $tList[$key]['pro_cat2'] = '';
                }


                //为满足特定格式
                // array_push($list, $tList[$key]);
                // unset($tList[$key]);
            }
            // unset($tList);
            $list = array_values($tList);
            unset($tList);

            $result = array("total" => $total, "rows" => $list, 'summary' => $summary);

            return json($result);
        }

        $briefAdminList = model('Admin')->getBriefAdminList2();
        //划扣状态
        $statusList = DeductRecords::getStatusList();
        //划扣角色
        $roleSets = \app\admin\model\DeductRole::order('id', 'asc')->column('*', 'id');
        $this->view->assign('statusList', $statusList);
        $this->view->assign('orderItemId', $orderItemId);
        $this->view->assign('briefAdminList', $briefAdminList);
        $this->view->assign('roleSets', $roleSets);
        return $this->view->fetch('deduct/records/index');
    }

    private function generateRecWhere($type = 'DEDUCT')
    {
        if (!in_array($type, ['DEDUCT', 'OSC'])) {
            throw new \Exception('Bad params');
        }
        $orderItemId                                 = input('order_item_id', false);
        list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null, false);

        $bWhere = [];
        foreach ($where as $key => $value) {
            $bWhere[$value[0]] = [$value[1], $value[2]];
        }

        $extraWhere = [];
        if ($orderItemId) {
            $extraWhere['order_item_id'] = $orderItemId;
        }

        $admin         = \think\Session::get('admin');
        $adminSecRules = json_decode($admin['sec_rules'], true);
        $isSuperAdmin  = $this->auth->isSuperAdmin();

        //默认取出自身 和 下属科室
        if (isset($bWhere['coc.dept_id'])) {
            $setDeptIds            = $this->deptAuth->deptTree->getChildrenIds($bWhere['coc.dept_id'][1], true);
            $bWhere['coc.dept_id'] = ['in', $setDeptIds];
        }

        //现场版 现场客服， 现场科室限制
        if ($type == 'OSC') {
            if (!$isSuperAdmin) {
                //职员只能查自身业绩
                if ($admin->position == 0) {
                    $extraWhere['order_items.admin_id'] = $admin->id;
                } else {
                    //不能查看所有科室数据---组长/主任 限制科室【】
                    if (!$adminSecRules['all']) {
                        $deptIds = $this->deptAuth->getDeptIds(true);
                        if (isset($bWhere['coc.dept_id'])) {
                            $deptIds = array_intersect($deptIds, $bWhere['coc.dept_id'][1]);
                        }

                        if (count($deptIds) == 0) {
                            //没有符合条件的
                            $bWhere['coc.dept_id'] = ['=', -1];
                        } elseif (count($deptIds) == 1) {
                            $bWhere['coc.dept_id'] = ['=', current($deptIds)];
                        } else {
                            $bWhere['coc.dept_id'] = ['in', $deptIds];
                        }
                    }
                }
            }
        } elseif ($type == 'DEDUCT') {
            //划扣科室版  科室限制
            if (!$isSuperAdmin && $admin->dept_type != '*') {
                $canDeductDepts                    = explode(',', $admin->dept_type);
                $extraWhere['order_items.dept_id'] = ['in', $canDeductDepts];
            }

        }

        // if (!$this->auth->isSuperAdmin()) {
        // $deptFilter = $this->deptAuth->getCusdeptCondition('order_items.dept_id', true);
        // $extraWhere = array_merge($extraWhere, $deptFilter);
        // }

        //部门搜索(上级部门可以显示下级部门数据)
        $developAdminFlg  = false;
        $developAdminFlg2 = false;
        $developAdminCon  = (new \app\admin\model\Admin);
        $developAdminCon2 = (new \app\admin\model\Admin);

        // $allowDeptIds = $this->deptAuth->getDeptIds(true);
        // if ($allowDeptIds != '*') {
        //     $developAdminFlg = true;
        //     $developAdminCon = $this->deptAuth->getAdminCondition($fields = 'id', $this->view->admin['id'], false, false);
        // }

        if (isset($bWhere['admin.dept_id'])) {
            $developAdminFlg  = true;
            $deptTree         = \app\admin\model\Deptment::getDeptTreeCache();
            $allSelectedDepts = $deptTree->getChildrenIds($bWhere['admin.dept_id'][1], true);
            $developAdminCon  = $developAdminCon->where(['dept_id' => ['in', $allSelectedDepts]]);
        }
        if (!empty($bWhere['order_items.consult_admin_id'])) {
            $developAdminFlg = true;
            $developAdminCon = $developAdminCon->where(['id' => $bWhere['order_items.consult_admin_id']]);
        }
        if ($developAdminFlg) {
            $bWhere['order_items.consult_admin_id'] = ['exp', 'in ' . $developAdminCon->field('id')->buildSql()];
        }
        unset($developAdminCon);

        //现场客服科室
        if (isset($bWhere['coc.dept_id'])) {
            $developAdminFlg2 = true;
            $developAdminCon2 = $developAdminCon2->where(['dept_id' => $bWhere['coc.dept_id']]);
        }
        if (!empty($bWhere['order_items.admin_id'])) {
            $developAdminFlg2 = true;
            $developAdminCon2 = $developAdminCon2->where(['id' => $bWhere['order_items.admin_id']]);
        }
        if ($developAdminFlg2) {
            $bWhere['order_items.admin_id'] = ['exp', 'in ' . $developAdminCon2->field('id')->buildSql()];
        }
        if (isset($bWhere['admin.dept_id'])) {
            unset($bWhere['admin.dept_id']);
        }
        if (isset($bWhere['coc.dept_id'])) {
            unset($bWhere['coc.dept_id']);
        }

        return [$bWhere, $extraWhere, $sort, $order, $offset, $limit];
    }
}
