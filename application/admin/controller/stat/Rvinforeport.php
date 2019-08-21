<?php

namespace app\admin\controller\stat;

use app\common\controller\Backend;
use think\Controller;
use app\admin\model\Admin;
use app\admin\model\Deptment;

/**
 * 提成相关
 */
class Rvinforeport extends Backend
{

    /**
     * DailyStat模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Rvinfo');

        //客户类型
        $ctmtypeLists = model('customertype')->field('type_id,type_name')->select();
        $ctmtypeList  = ['' => __('NONE')];
        foreach ($ctmtypeLists as $ctmtype) {
            $ctmtypeList[$ctmtype['type_id']] = $ctmtype['type_name'];
        }
        $this->view->assign("ctmtypeList", $ctmtypeList);
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
            // list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null);
            list($bWhere, $sort, $order, $offset, $limit, $extraWhere) = $this->handleRequest();

            $list = $this->model->getList($bWhere, $sort, $order, $offset, $limit, $extraWhere);

            if (!$this->auth->isSuperAdmin()) {
                foreach ($list as $key => $row) {
                    $list[$key]['rvi_tel'] = getMaskString($row['rvi_tel']);
                    $list[$key]['ctm_mobile'] = getMaskString($row['ctm_mobile']);
                }
            }

            if($offset == 0) {
                // $total = $this->model->getListCount($bWhere, $extraWhere);
                $summary = $this->model->getListSummary($bWhere, $extraWhere);
                return json(['total' => $summary['count'], 'rows' => $list, 'summary' => $summary]);
            } else {
                return json(['rows' => $list]);
            }
            
        }

        $rvTypeList = model('Rvtype')->where(['rvt_status' => 1])->column('rvt_name', 'rvt_id');
        // $deptList = model('Deptment')->getDeptListCache();
        $deptList = (new \app\admin\model\Deptment)->getVariousTree();
        $tmpArriveStatusList = \app\admin\model\ArriveStatus::getList();
        $arriveStatusList = array('' => __("All"));
        foreach ($tmpArriveStatusList as $key => $value) {
            $arriveStatusList[$key] = $value;
        }

        $briefAdminList = model('Admin')->getBriefAdminList2();
        $cProjectList = \app\admin\model\CProject::getCProjectCache();
        $toolList = \app\admin\model\CocAcceptTool::getList();
        $this->view->assign("toolList", $toolList);
        $this->view->assign("cProjectList", $cProjectList);
        $this->view->assign("briefAdminList", $briefAdminList);
        $this->view->assign('startDate', $startDate);
        $this->view->assign('endDate', $endDate);
        $this->view->assign('rvTypeList', $rvTypeList);
        $this->view->assign('deptList', $deptList);
        $this->view->assign('arriveStatusList', $arriveStatusList);

        return $this->view->fetch();
    }

    /**
     * 获取进度信息
     */
    public function downloadprocess()
    {
        list($bWhere, $sort, $order, $offset, $limit, $extraWhere) = $this->handleRequest();
        \think\Request::instance()->get(['filter' => '']);
        return $this->commondownloadprocess('rvinforeport', 'Customer revisit records', $bWhere, $extraWhere, ['sort' => $sort, 'order' => $order]);
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

    private function handleRequest()
    {
        list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null, false);
        $bWhere                                      = [];
        $extraWhere                                  = [];
        foreach ($where as $key => $value) {
            if (strpos($value[0], '.') === false) {
                if (count($value) == 3) {
                    $bWhere[$value[0]] = [$value[1], $value[2]];
                } elseif (count($value) == 2) {
                    $bWhere[$value[0]] = $value[1];
                }
            } else {
                //额外处理
                if ($value[0] == 'admin.dept_id') {
                    $deptTree = Deptment::getDeptTreeCache();
                    $deptIds = $deptTree->getChildrenIds($value[2], true);

                    if (count($deptIds) == 1) {
                        $extraWhere[$value[0]] = ['=', current($deptIds)];
                    } else {
                        $extraWhere[$value[0]] = ['in', $deptIds];
                    }
                    continue;
                }

                if (count($value) == 3) {
                    $extraWhere[$value[0]] = [$value[1], $value[2]];
                } elseif (count($value) == 2) {
                    $extraWhere[$value[0]] = $value[1];
                }
            }
        }
        $onlyNoneRevisit = input('onlyNoneRevisit', false);
        if ($onlyNoneRevisit && $onlyNoneRevisit != 'false') {
            $extraWhere['rvinfo.rv_time'] = ['exp', 'IS NULL'];
        }

        return [$bWhere, $sort, $order, $offset, $limit, $extraWhere];
    }

}
