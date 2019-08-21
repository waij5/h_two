<?php

namespace app\admin\controller\stat;

use app\admin\model\Admin;
use app\admin\model\CocAccepttool;
use app\common\controller\Backend;
use think\Controller;

/**
 * 提成相关
 */
class Rvinfosummary extends Backend
{

    /**
     * DailyStat模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Rvinfo');
    }

    /**
     * 网电，分诊，现场客服业绩
     */
    public function index()
    {
        // date('Y-m-d', strtotime('-30 days', time()))
        $startDate = $endDate = date('Y-m-d');

        if ($this->request->isAjax()) {
            // list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null);
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null, false);
            list($bWhere, $extraWhere)                   = $this->buildparams2($where);
            if (!isset($bWhere['notOnlyToday'])) {
                $bWhere['rv_date'] = ['BETWEEN', [$startDate, $endDate]];
            } else {
                unset($bWhere['notOnlyToday']);
            }
            if (isset($extraWhere['admin.dept_id'])) {
                $deptTree = \app\admin\model\Deptment::getDeptTreeCache();
                $deptIds = $deptTree->getChildrenIds($extraWhere['admin.dept_id'][1], true);

                if (count($deptIds) == 1) {
                    $extraWhere['admin.dept_id'] = ['=', current($deptIds)];
                } else {
                    $extraWhere['admin.dept_id'] = ['in', $deptIds];
                }
            }

            $adminTable = model('admin')->getTable();
            // if (!empty($bWhere['admin_id']))

            if (empty($bWhere)) {
                $mainTable = model('Rvinfo')->getTable();
            } else {
                $mainTable = model('Rvinfo')->where($bWhere)->buildSql();
            }
            $list = model('Rvinfo')->table($mainTable . ' rvinfo')
                    ->join(model('Customer')->getTable() . ' customer',
                        'rvinfo.customer_id = customer.ctm_id', 'LEFT')
                    ->join(model('Admin')->getTable() . ' admin', 'rvinfo.admin_id =
                admin.id', 'LEFT')
                    ->where($extraWhere)
                    ->group('rvinfo.admin_id')
                    ->column('rvinfo.admin_id, count(*) as count,
                count(distinct customer_id) as customer_count,
                admin.username, admin.nickname, admin.dept_id, admin.position', 'rvinfo.admin_id');

            $list2 = model('Rvinfo')->table($mainTable . ' rvinfo')
                    ->join(model('Customer')->getTable() . ' customer',
                        'rvinfo.customer_id = customer.ctm_id', 'LEFT')
                    ->join(model('Admin')->getTable() . ' admin', 'rvinfo.admin_id =
                admin.id', 'LEFT')
                    ->where($extraWhere)
                    ->where('rv_is_valid', '=', 1)
                    ->group('rvinfo.admin_id')
                    ->column('rvinfo.admin_id, count(*) as count,
                count(distinct customer_id) as customer_count,
                admin.username, admin.nickname, admin.dept_id, admin.position', 'rvinfo.admin_id');

            foreach ($list as $key => $row) {
                $list[$key]['avaiable_count'] = 0;
                $list[$key]['avaiable_customer_count'] = 0;
                if (isset($list2[$key])) {
                    $list[$key]['avaiable_count'] = $list2[$key]['count'];
                $list[$key]['avaiable_customer_count'] = $list2[$key]['customer_count'];
                }
            }
           
           $list = array_values($list);

            return json(['total' => 10, 'rows' => $list]);
        }

        $rvTypeList       = model('Rvtype')->where(['rvt_status' => 1])->column('rvt_name', 'rvt_id');
        // $deptList         = model('Deptment')->getDeptListCache();
        $deptList = (new \app\admin\model\Deptment)->getVariousTree();
        $arriveStatusList = \app\admin\model\ArriveStatus::getList();
        $cProjectList     = \app\admin\model\CProject::getCProjectCache();

        $briefAdminList = model('Admin')->getBriefAdminList2();
        $toolList = \app\admin\model\CocAcceptTool::getList();
        $this->view->assign('toolList', $toolList);
        $this->view->assign("briefAdminList", $briefAdminList);
        $this->view->assign('startDate', $startDate);
        $this->view->assign('endDate', $endDate);
        $this->view->assign('rvTypeList', $rvTypeList);
        $this->view->assign('deptList', $deptList);
        $this->view->assign('cProjectList', $cProjectList);
        $this->view->assign('arriveStatusList', $arriveStatusList);

        return $this->view->fetch();
    }

    /**
     * 获取进度信息
     */
    public function downloadprocess()
    {
        $this->commondownloadprocess('rvinforeport', 'Customer revisit records');
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

}
