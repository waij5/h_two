<?php

namespace app\admin\controller\stat;

use app\common\controller\Backend;
use think\Controller;
use app\admin\model\Admin;
use app\admin\model\Osctype;
use app\admin\model\CocAcceptTool;
/**
 * 提成相关
 */
class Consultrate extends Backend
{

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
        $endDate = input('stat_date_end', date('Y-m-d'));

        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null, false);

            $bWhere                                      = [];
            foreach ($where as $key => $value) {
                $bWhere[$value[0]] = [$value[1], $value[2]];
            }

            if (isset($bWhere['customer.ctm_birthdate'])){
                $ageStart = $bWhere['customer.ctm_birthdate'][1][0];
                $ageEnd = $bWhere['customer.ctm_birthdate'][1][1]+1;
                $bigAge = getBirthDate($ageStart);
                $smallAge = getBirthDate($ageEnd);
                
                $bWhere['customer.ctm_birthdate'][1][0] = $smallAge;
                $bWhere['customer.ctm_birthdate'][1][1] = $bigAge;
            }

            //营销部门搜索(上级部门可以显示下级部门数据)
            $developAdminFlg = false;
            $developAdminCon = model('admin')->field('id');

            $allowDeptIds = $this->deptAuth->getDeptIds(true);
            if ($allowDeptIds != '*') {
                $developAdminFlg = true;
                $developAdminCon = $this->deptAuth->getAdminCondition($fields = 'id', $this->view->admin['id'], false, false);
            }

            if (isset($bWhere['admin.dept_id'])) {
                $developAdminFlg = true;
                $deptTree = \app\admin\model\Deptment::getDeptTreeCache();
                $allSelectedDepts = $deptTree->getChildrenIds($bWhere['admin.dept_id'][1], true);
                $developAdminCon = $developAdminCon->where(['dept_id' => ['in', $allSelectedDepts]]);
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
             //删除的不显示
            $bWhere['osc.is_delete'] = 0;

            $list = \app\admin\model\CustomerOsconsult::getDevSuccessStatistic($bWhere);

            return json($list);
        }

        // $deptList = \app\admin\model\Deptment::getDeptListCache();
        $deptList = (new \app\admin\model\Deptment)->getVariousTree();
        $allowDeptIds = $this->deptAuth->getDeptIds(true);
        if (!$this->auth->isSuperAdmin() && $allowDeptIds != '*') {
            foreach ($deptList as $key => $row) {
                if (!in_array($key, $allowDeptIds)) {
                    unset($deptList[$key]);
                }
            }
        }
        $briefAdminList = model('Admin')->getBriefAdminList2();
        //客服项目
        $pducats  = model('CProject')->field('id, cpdt_name')->where(['cpdt_status' => 1])->order('id', 'ASC')->select();
        $cpdtList = ['' => __('NONE')];
        foreach ($pducats as $pducat) {
            $cpdtList[$pducat['id']] = $pducat['cpdt_name'];
        }
        $this->view->assign('cpdtList', $cpdtList);
        $ocsTypeArr    = Osctype::getList();
        $this->view->assign('ocsTypeArr', $ocsTypeArr);
        // 客户来源
        $ctmSource = model('Ctmsource')->field('sce_id, sce_name')->where(['sce_status' => 1])->order('sce_sort', 'desc')->select();
        $ctmSrcList = ['' => __('NONE')];
        foreach ($ctmSource as $source) {
            $ctmSrcList[$source['sce_id']] = $source['sce_name'];
        }
        $this->view->assign('ctmSrcList', $ctmSrcList);
        // 首次受理工具
        $toolList = \app\admin\model\CocAcceptTool::getList();
        $this->view->assign('toolList', $toolList);

        $this->view->assign('deptList', $deptList);
        $this->view->assign('briefAdminList', $briefAdminList);
        $this->view->assign('startDate', $startDate);
        $this->view->assign('endDate', $endDate);

        return $this->view->fetch();
    }

    /**
     * 获取进度信息
     */
    public function downloadprocess()
    {
        list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null, false);
        $bWhere                                      = [];
        foreach ($where as $key => $value) {
            $bWhere[$value[0]] = [$value[1], $value[2]];
        }

        if (isset($bWhere['customer.ctm_birthdate'])){
            $ageStart = $bWhere['customer.ctm_birthdate'][1][0];
            $ageEnd = $bWhere['customer.ctm_birthdate'][1][1];
            $bigAge = getBirthDate($ageStart);
            $smallAge = getBirthDate($ageEnd);
            
            $bWhere['customer.ctm_birthdate'][1][0] = $smallAge;
            $bWhere['customer.ctm_birthdate'][1][1] = $bigAge;
        }

        //营销部门搜索(上级部门可以显示下级部门数据)
        $developAdminFlg = false;
        $developAdminCon = model('admin')->field('id');

        $allowDeptIds = $this->deptAuth->getDeptIds(true);
        if ($allowDeptIds != '*') {
            $developAdminFlg = true;
            $developAdminCon = $this->deptAuth->getAdminCondition($fields = 'id', $this->view->admin['id'], false, false);
        }

        if (isset($bWhere['admin.dept_id'])) {
            $developAdminFlg = true;
            $deptTree = \app\admin\model\Deptment::getDeptTreeCache();
            $allSelectedDepts = $deptTree->getChildrenIds($bWhere['admin.dept_id'][1], true);
            $developAdminCon = $developAdminCon->where(['dept_id' => ['in', $allSelectedDepts]]);
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
         //删除的不显示
        $bWhere['osc.is_delete'] = 0;
        \think\Request::instance()->get(['filter' => '']);

        return $this->commondownloadprocess('consultrate', 'All business consult statistic', $bWhere);
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

}
