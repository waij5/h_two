<?php

namespace app\admin\controller\stat;

use app\admin\model\Admin;
use app\admin\model\DeductStaffRecords;
use app\common\controller\Backend;
use think\Controller;

/**
 * 提成相关
 */
class Benefit extends Backend
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
     * 医生，护士等业绩
     */
    public function operatebenefit()
    {
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->generateOperateWhere(false);
            // list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null, false);
            // $newWhere                                    = array();
            // foreach ($where as $key => $row) {
            //     $key = $row[0];
            //     array_shift($row);
            //     $newWhere[$key] = $row;
            // }

            // $admin = $this->view->admin;
            // if ($admin->position == 0) {
            //     $newWhere['staff_rec.admin_id'] = ['=', $admin->id];
            // }

            // if ($showAllDepts == false && $this->view->admin->position > 0) {
            //     if (isset($newWhere['admin.dept_id'])) {
            //         //admin.dept_id => ['=', deptId]
            //         $setDeptIds = $this->deptAuth->deptTree->getChildrenIds($newWhere['admin.dept_id'][1], true);
            //         $deptIds    = array_intersect($deptIds, $setDeptIds);
            //     }

            //     if (count($deptIds) == 1) {
            //         $newWhere['admin.dept_id'] = ['=', current($deptIds)];
            //     } else {
            //         $newWhere['admin.dept_id'] = ['in', $deptIds];
            //     }

            // }
            $list    = DeductStaffRecords::operateBenefitList($where, $sort, $order, $offset, $limit);
            $summary = DeductStaffRecords::operateBenefitSummary($where, !(bool) $offset);
            $result  = array("total" => $summary['count'], "rows" => $list, 'summary' => $summary);

            return json($result);
        }

        $adminSecRules = json_decode($this->view->admin['sec_rules'], true);
        $deptList      = array();
        $deptIds       = array();
        $showAllDepts  = false;
        if ($this->auth->isSuperAdmin or ($this->view->admin->position > 0 && $adminSecRules['all'])) {
            $showAllDepts = true;
        } elseif ($this->view->admin->position > 0) {
            $deptIds = $this->deptAuth->getDeptIds(true);
        }

        $deptList       = (new \app\admin\model\Deptment)->getVariousTree();
        $briefAdminList = model('admin')->getBriefAdminlist2();
        $this->view->assign('briefAdminList', $briefAdminList);
        $adminList =  model('admin')->getAdminlist();
        $this->view->assign('adminList', $adminList);
        $this->view->assign('showAllDepts', $showAllDepts);
        $this->view->assign('deptList', $deptList);
        $this->view->assign('deptIds', $deptIds);

        return $this->view->fetch();
    }

    public function downloadprocess()
    {
        $type = input('type', 'index');
        if ($type == 'operatebenefit') {
            list($whereAddon, $sort, $order, $offset, $limit) = $this->generateOperateWhere(true);
            return $this->commondownloadprocess('operatebenefit', 'Work detail', $whereAddon);
        }
    }

    public function index()
    {
        return $this->operatebenefit();
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

    private function generateOperateWhere($clearFilter = false)
    {
        $showAllDepts  = false;
        $deptIds       = array();
        $admin         = $this->view->admin;
        $adminSecRules = json_decode($admin['sec_rules'], true);

        list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null, false);
        $newWhere                                    = array();
        foreach ($where as $key => $row) {
            $key = $row[0];
            array_shift($row);
            $newWhere[$key] = $row;
        }

        if ($admin->position == 0) {
            $newWhere['staff_rec.admin_id'] = ['=', $admin->id];
        }

        //非超管 非职员且所管部门不为所有 ---- 有部分部门管理权限的 组长/主任
        if (!$this->auth->isSuperAdmin && ($this->view->admin->position > 0 && !$adminSecRules['all'])) {
            $deptIds = $this->deptAuth->getDeptIds(true);
            if (isset($newWhere['admin.dept_id'])) {
                //admin.dept_id => ['=', deptId]
                $setDeptIds = $this->deptAuth->deptTree->getChildrenIds($newWhere['admin.dept_id'][1], true);
                $deptIds    = array_intersect($deptIds, $setDeptIds);
            }

            if (count($deptIds) == 0) {
                //没有符合条件的
                $newWhere['admin.dept_id'] = ['=', -1];
            } elseif (count($deptIds) == 1) {
                $newWhere['admin.dept_id'] = ['=', current($deptIds)];
            } else {
                $newWhere['admin.dept_id'] = ['in', $deptIds];
            }
        }

        //清除请求，主要为下载处理【避免下载默认处理】
        if ($clearFilter) {
            \think\Request::instance()->get(['filter' => '']);
        }
        return [$newWhere, $sort, $order, $offset, $limit];
    }

}
