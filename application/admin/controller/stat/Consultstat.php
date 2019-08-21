<?php

namespace app\admin\controller\stat;

use app\common\controller\Backend;
use think\Controller;
use app\admin\model\ConsultStat as MConsultStat;

/**
 * 客服统计信息
 */
class Consultstat extends Backend
{
    /**
     * 网电客服统计-按职员
     */
    public function consult()
    {
        $startDate = input('stat_date_start', date('Y-m-d', strtotime('-30 days', time())));
        $endDate   = input('stat_date_end', date('Y-m-d'));
        $deptId    = input('dept_id');
        $typeId    = input('type_id');
        $toolId    = input('tool_id');
        $adminId    = input('admin_id');
        $dept_id    = input('admin_dept_id');

        $briefAdminList = model('Admin')->getBriefAdminList2();
        // $deptList = model('Deptment')->column('dept_id, dept_name');
        $deptList = (new \app\admin\model\Deptment)->getVariousTree();

        $startTime = strtotime($startDate);
        $endTime   = strtotime($endDate . ' 23:59:59');
        $where     = ['cst.createtime' => ['BETWEEN', [$startTime, $endTime]]];
        if (!empty($deptId)) {
            $where['cst.dept_id'] = $deptId;
        }
        if (!empty($typeId)) {
            $where['cst.type_id'] = $typeId;
        }
        if (!empty($toolId)) {
            $where['cst.tool_id'] = $toolId;
        }

        $extraWhere = [];
        if (!empty($adminId)) {
            $extraWhere['customer.admin_id'] = $adminId;
        }
        if (!empty($dept_id)) {
            $extraWhere['admin.dept_id'] = $dept_id;
        }

        //营销部门搜索(上级部门可以显示下级部门数据)
        $developAdminFlg = false;
        $developAdminCon = model('admin')->field('id');
        if (isset($extraWhere['admin.dept_id'])) {
            $developAdminFlg  = true;
            $deptTree         = \app\admin\model\Deptment::getDeptTreeCache();
            $allSelectedDepts = $deptTree->getChildrenIds($extraWhere['admin.dept_id'], true);
            $developAdminCon  = $developAdminCon->where(['dept_id' => ['in', $allSelectedDepts]]);
        }
        if (!empty($extraWhere['customer.admin_id'])) {
            $developAdminFlg = true;
            $developAdminCon = $developAdminCon->where(['id' => $extraWhere['customer.admin_id']]);
        }
        if ($developAdminFlg) {
            $extraWhere['customer.admin_id'] = ['exp', 'in ' . $developAdminCon->buildSql()];
        }
        if (isset($extraWhere['admin.dept_id'])) {
            unset($extraWhere['admin.dept_id']);
        }

        $where = array_merge($where,$extraWhere);

        $listByStaff = MConsultStat::getListForStaff($where);

        $listByProject = MConsultStat::getListForProject($where);

        $summary = MConsultStat::getSummary($where);

        $this->view->assign('startDate', $startDate);
        $this->view->assign('endDate', $endDate);
        $this->view->assign('dept_id', $deptId);
        $this->view->assign('type_id', $typeId);
        $this->view->assign('tool_id', $toolId);
        $this->view->assign('briefAdminList', $briefAdminList);
        $this->view->assign('deptList', $deptList);

        $this->view->assign('listByStaff', $listByStaff);
        $this->view->assign('listByProject', $listByProject);
        $this->view->assign('summary', $summary);

        return $this->view->fetch();
    }

    /**
     * 网电客服统计-按项目
     */

    /**
     * 现场客服统计信息
     */
    public function osconsult()
    {
        $list = model('Customerconsult')
            ->alias('cst')
            ->field('cpdt_id,cpdt_name, count(*) AS total, sum( CASE WHEN cst_status = 1 OR cst_status = 2 THEN 1 ELSE 0 END ) AS book_total, SUM( CASE WHEN cst_status = 2 THEN 1 ELSE 0 END ) AS arrive_total')
            ->join(Db::getTable('c_project') . ' c_project', 'cst.cpdt_id = c_project.id', 'LEFT')
            ->where(['cst.createtime' => ['BETWEEN', [$startTime, $endTime]]])
            ->group('cpdt_id')
            ->order('cpdt_id', 'ASC')
            ->select(false);
    }

}
