<?php

namespace app\admin\controller\stat;

use app\common\controller\Backend;
use think\Controller;
use app\admin\model\Admin;
use app\admin\model\Osctype;

/**
 * 提成相关
 */
class Osconsultrate extends Backend
{

    /**
     * DailyStat模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('CustomerOsconsult');
    }

    /**
     * 网电，分诊，现场客服业绩
     */
    public function index()
    {
        $startDate = input('stat_date_start', date('Y-m-d'));
        $endDate = input('stat_date_end', date('Y-m-d'));

        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null);

            $list = \app\admin\model\CustomerOsconsult::getSuccessStatistic($where);

            return json($list);
        }

        // $deptList = \app\admin\model\Deptment::getDeptListCache();
        $deptList = (new \app\admin\model\Deptment)->getVariousTree();
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
        return $this->commondownloadprocess('osconsultrate', 'All business osconsult statistic');
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
