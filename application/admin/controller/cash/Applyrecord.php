<?php

namespace app\admin\controller\cash;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use app\admin\model\OrderApplyRecords;

/**
 * 订单审批
 *
 * @icon fa fa-circle-o
 */
class Applyrecord extends Backend
{
    
    /**
     * OrderApplyRecords模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('OrderApplyRecords');
    }

    /**
     * 订单审批列表
     */
    public function index()
    {
        $briefAdminList = model('Admin')->getBriefAdminList();

        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {

            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null);

            $total = $this->model
                    ->alias('recs')
                    ->join(\app\admin\model\Customer::getTable() . ' customer', 'recs.customer_id = customer.ctm_id', 'LEFT')
                    ->where($where)
                    ->count();

            $list = $this->model
                    ->alias('recs')
                    ->join(\app\admin\model\Customer::getTable() . ' customer', 'recs.customer_id = customer.ctm_id', 'LEFT')
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->field('recs.*, customer.ctm_name, customer.ctm_mobile, ctm_tel')
                    ->select();

            //申请人， 审批人姓名
            $adminList = model('Admin')->getBriefAdminList();
            foreach ($list as $key => $row) {
                if (isset($adminList[$row['apply_admin_id']])) {
                    $list[$key]['apply_admin_name'] = $adminList[$row['apply_admin_id']];
                }
                if (isset($adminList[$row['reply_admin_id']])) {
                    $list[$key]['reply_admin_name'] = $adminList[$row['reply_admin_id']];
                }
            }


            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }


        $applyStatusArr = [
                            '' => __('All'),
                            OrderApplyRecords::STATUS_CANCELED => __('reply_status_' . str_replace('-', 'm_', OrderApplyRecords::STATUS_CANCELED)),
                            OrderApplyRecords::STATUS_DENYED => __('reply_status_' . str_replace('-', 'm_', OrderApplyRecords::STATUS_DENYED)),
                            OrderApplyRecords::STATUS_PENDING => __('reply_status_' . str_replace('-', 'm_', OrderApplyRecords::STATUS_PENDING)),
                            OrderApplyRecords::STATUS_ACCEPTED => __('reply_status_' . str_replace('-', 'm_', OrderApplyRecords::STATUS_ACCEPTED)),
                        ];
        $this->view->assign('applyStatusArr', $applyStatusArr);

        return $this->view->fetch();

    }

    /**
     * 禁止访问
     */
    public function add()
    {
        $this->error(__('You have no permission'));
    }

    /**
     * 禁止访问
     */
    public function edit($ids = NULL)
    {
        $this->error(__('You have no permission'));
    }

    /**
     * 禁止访问
     */
    public function del($ids = "")
    {
        $this->error(__('You have no permission'));
    }

    /**
     * 禁止访问
     */
    public function multi($ids = "")
    {
        $this->error(__('You have no permission'));
    }
}
