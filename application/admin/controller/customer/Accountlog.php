<?php

namespace app\admin\controller\customer;

use app\common\controller\Backend;
use think\Controller;
use think\Request;
use app\admin\model\Customer;


/**
 * 客户资金积分变动表
 *
 * @icon fa fa-circle-o
 */
class Accountlog extends Backend
{

    /**
     * AccountLog模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('AccountLog');

        $typeList = $this->model->getList();
        $this->view->assign("typeList", $typeList);

    }

    /**
     * 客户资金变动记录
     */
    public function index()
    {
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null);
            $total = $this->model->alias('account_log')
                ->where($where)
                ->join(model('Customer')->getTable() . ' customer', 'account_log.customer_id = customer.ctm_id', 'LEFT')
                ->order($sort, $order)
                ->count();
            $list = $this->model
                ->alias('account_log')
                ->where($where)
                ->join(model('Customer')->getTable() . ' customer', 'account_log.customer_id = customer.ctm_id', 'LEFT')
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->field('account_log.*, customer.ctm_name')
                ->select();

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }

        return $this->view->fetch();
    }

     //积分兑换
    public function exchange()
    {   
        $customerId = input('customer_id');
        $customer   = model('Customer')->find(['ctm_id' => $customerId]);
        if (empty($customer->ctm_id)) {
            $this->error(__('Customer %s does not exist.', $customerId));
        }

        if ($this->request->isPost()) {

            $params = $this->request->post("row/a");

            if (!isset($params['ctm_pay_points'])) {
                $this->error(__('Parameter %s can not be empty', __('ctm_pay_points')));
            }
            if($params['ctm_pay_points'] < 0){
                $this->err(__('Parameter %s can not be negative', __('ctm_pay_points')));
            }
            if ($params['ctm_pay_points'] > $customer['ctm_pay_points']) {
                $this->error(__('Refund total can not be greater than ctm_pay_points!'));
            }

            $customer->log_account_change(0, 0, 0, -$params['ctm_pay_points'], 0, time(), \app\admin\model\AccountLog::TYPE_EXCHANGE, $params['change_desc'], $ip = 'SYSTEM', 'HIS', 0);
            $this->success();
        }

        $this->view->assign('customer', $customer);
        return $this->view->fetch();
       
    }

    //定金/佣金/等级积分调整
    public function adjust()
    {
        $customerId = input('customer_id');
        $customer   = model('Customer')->find(['ctm_id' => $customerId]);
        if (empty($customer->ctm_id)) {
            $this->error(__('Customer %s does not exist.', $customerId));
        }

        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            
            if ($params['deposit_amt'] < 0 && abs($params['deposit_amt']) > $customer['ctm_depositamt']) {

                $this->error(__('Refund total can not be greater than deposit_amt!'));
            }
            if ($params['affiliate_amt'] < 0 && abs($params['affiliate_amt']) > $customer['ctm_affiliate']) {
                
                $this->error(__('Refund total can not be greater than affiliate_amt!'));
            }
            if ($params['coupon_amt'] < 0 && abs($params['coupon_amt']) > $customer['ctm_coupamt']) {
                
                $this->error(__('Refund total can not be greater than ctm_coupamt!'));
            }
            if ($params['rank_points'] < 0 && abs($params['rank_points']) > $customer['ctm_rank_points']) {
                
                $this->error(__('Refund total can not be greater than rank_points!'));
            }
            if ($params['pay_points'] < 0 && abs($params['pay_points']) > $customer['ctm_pay_points']) {

                $this->error(__('Refund total can not be greater than pay_points!'));
            }

            // $customer->log_account_change($params['deposit_amt'], 0, $params['rank_points'], $params['pay_points'], $params['affiliate_amt'], time(), \app\admin\model\AccountLog::TYPE_ADJUST, $params['change_desc'], $ip = 'SYSTEM', 'HIS', 0);

            Customer::logAccountChange($customer->ctm_id, $params['deposit_amt'], 0, $params['rank_points'], $params['pay_points'], $params['affiliate_amt'], $params['coupon_amt'], time(), \app\admin\model\AccountLog::TYPE_ADJUST, $params['change_desc'], $ip = 'SYSTEM', 'HIS', 0);

            $this->success();
        }

        $this->view->assign('customer', $customer);
        return $this->view->fetch();
    }

    public function add()
    {
        $this->error();
    }

    public function edit($ids = '')
    {
        $this->error();
    }
}
