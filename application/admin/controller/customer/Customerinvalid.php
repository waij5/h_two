<?php

namespace app\admin\controller\Customer;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use app\admin\model\FirstToolId;
use app\admin\model\OrderApplyRecords;
use think\Session;
use app\admin\model\ArriveStatus;


/**
 * 订单审批
 *
 * @icon fa fa-circle-o
 */
class Customerinvalid extends Backend
{
    
    /**
     * FirstToolRecord
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('CustomerInvalid');
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

            $total = model('CustomerInvalid')->alias('cid')
                    ->join(model('Customer')->getTable() . ' customer', 'cid.customer_id = customer.ctm_id', 'LEFT')
                    ->where($where)
                    ->count();

            $list = model('CustomerInvalid')->alias('cid')
                    ->field('cid.*, customer.ctm_name, customer.ctm_id, customer.arrive_status')
                    ->join(model('Customer')->getTable() . ' customer', 'cid.customer_id = customer.ctm_id', 'LEFT')
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
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
                            OrderApplyRecords::STATUS_DENYED => __('reply_status_' . str_replace('-', 'm_', OrderApplyRecords::STATUS_DENYED)),
                            OrderApplyRecords::STATUS_PENDING => __('reply_status_' . str_replace('-', 'm_', OrderApplyRecords::STATUS_PENDING)),
                            OrderApplyRecords::STATUS_ACCEPTED => __('reply_status_' . str_replace('-', 'm_', OrderApplyRecords::STATUS_ACCEPTED)),
                        ];
        $this->view->assign('applyStatusArr', $applyStatusArr);
        $ArriveStatus = ArriveStatus::getList();
        $this->view->assign('ArriveStatus', $ArriveStatus);

        return $this->view->fetch();

    }

    /**
     * 审批申请
     */
    public function edit($ids = NULL)
    {
        $logId = input('applyLogId',false);
        $status = input('status',false);

        $list = $this->model->where(['log_id' => $logId])->find();
        //已审批
        if($list->status != 0) {
            $this->error('该条申请已审批，请刷新页面');
        }

        //审批人
        $adminID = Session::get('admin')->id;

        //开始审批acceptapply(同意)denyapply(拒绝)
        if($status == 'acceptapply') {
            $customer = model('customer')->where(['ctm_id' => $list->customer_id])->find();
            $customer->ctm_status = 0;
            $customer->save();

            $list->status = 1;
            $list->reply_admin_id = $adminID;
            $list->updatetime = time();
            $list->save();
        }else{
            $list->status = -1;
            $list->reply_admin_id = $adminID;
            $list->updatetime = time();
            $list->save();
        }

        $this->success('审批结束');
       
    }

    public function batchinvalid($id = '')
    {
        //审批人
        $adminID = Session::get('admin')->id;
        $count  = $this->model->where(['customer_id' => ['in', $id]])->where(['status' => 0])->count();
        // $list = model('CustomerInvalid')->alias('cid')
        //             ->join(model('Customer')->getTable() . ' customer', 'cid.customer_id = customer.ctm_id', 'LEFT')
        //             ->where(['cid.status' => 0])
        //             ->where(['cid.customer_id' => ['in', $id]])
        //             ->column('customer.ctm_name', 'customer.ctm_id');
        $list   = model('customer')->where(['ctm_id' => ['in', $id]])->column('ctm_name', 'ctm_id');
        
        if($this->request->isPost()) {

            $status = input('customerInvalid', false);
            $id     = explode(',', $id);

            if($status == 1) {
                model('customer')->save(
                    [
                        'ctm_status' => 0,
                    ],
                    ['ctm_id' => ['in', $id]]
                );

                $this->model->save(
                    [
                        'reply_admin_id' => $adminID,
                        'status' => 1,
                        'updatetime' => time(),
                    ],
                    ['customer_id' => ['in', $id]]
                );

            }else{
                $this->model->save(
                    [
                        'reply_admin_id' => $adminID,
                        'status' => -1,
                        'updatetime' => time(),
                    ],
                    ['customer_id' => ['in', $id]]
                );
            }

            $this->success('审批结束');
            
        }

        $this->assign('id', $id);
        $this->assign('count', $count);
        $this->assign('list', $list);
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
