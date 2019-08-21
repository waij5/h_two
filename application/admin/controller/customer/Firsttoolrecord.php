<?php

namespace app\admin\controller\Customer;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use app\admin\model\FirstToolId;
use app\admin\model\OrderApplyRecords;
use think\Session;


/**
 * 订单审批
 *
 * @icon fa fa-circle-o
 */
class Firsttoolrecord extends Backend
{
    
    /**
     * FirstToolRecord
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('FirstToolId');
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

            $total = model('FirstToolId')->alias('first')
                    ->join(model('Customer')->getTable() . ' customer', 'first.customer_id = customer.ctm_id', 'LEFT')
                    ->where($where)
                    ->count();

            $list = model('FirstToolId')->alias('first')
                    ->field('first.*, customer.*')
                    ->join(model('Customer')->getTable() . ' customer', 'first.customer_id = customer.ctm_id', 'LEFT')
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
                            OrderApplyRecords::STATUS_CANCELED => __('reply_status_' . str_replace('-', 'm_', OrderApplyRecords::STATUS_CANCELED)),
                            OrderApplyRecords::STATUS_DENYED => __('reply_status_' . str_replace('-', 'm_', OrderApplyRecords::STATUS_DENYED)),
                            OrderApplyRecords::STATUS_PENDING => __('reply_status_' . str_replace('-', 'm_', OrderApplyRecords::STATUS_PENDING)),
                            OrderApplyRecords::STATUS_ACCEPTED => __('reply_status_' . str_replace('-', 'm_', OrderApplyRecords::STATUS_ACCEPTED)),
                        ];
        $this->view->assign('applyStatusArr', $applyStatusArr);

        return $this->view->fetch();

    }

    

    /**
     * 审批申请
     */
    public function edit($ids = NULL)
    {
        $recId = input('applyRecId',false);
        $status = input('status',false);

        $list = $this->model->where(['rec_id' => $recId])->find();
        //已审批
        if($list->reply_status != 0) {
            $this->error('该条申请已审批，请刷新页面');
        }

        //首次网电记录的受理工具
        $ctmConsultTool = model('customerconsult')->where(['customer_id' => $list->customer_id])->order('createtime asc')->find();
        if(!$ctmConsultTool) {
            $this->error('客户没有网电客服记录,请添加');
        }

        //审批人
        $adminID = Session::get('admin')->id;

        //开始审批acceptapply(同意)denyapply(拒绝)
        if($status == 'acceptapply') {
            $customer = model('customer')->where(['ctm_id' => $list->customer_id])->find();
            $customer->ctm_first_tool_id = $ctmConsultTool->tool_id;
            $customer->save();

            $list->reply_status = 1;
            $list->reply_admin_id = $adminID;
            $list->updatetime = time();
            $list->save();
        }else{
            $list->reply_status = -1;
            $list->reply_admin_id = $adminID;
            $list->updatetime = time();
            $list->save();
        }

        $this->success('审批结束');
       
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
