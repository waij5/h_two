<?php

namespace app\admin\controller\customer;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use think\Db;

/**
 * 用户优惠券信息
 *
 * @icon fa fa-circle-o
 */
class Couponrecord extends Backend
{
    
    /**
     * CouponRecords模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('CouponRecords');

    }
    
    /**
     * 列表
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            return $this->getListJson();
        }

        return $this->view->fetch();
    }
    
    public function edit($ids = null)
    {
        $this->error('Permission denied');
    }

    /**
     * 弹窗选择
     * @param string mode single, multi, redirect
     * @param string parentSelector
     * 
     */
    public function comselectpop() {
        $customerId = input('customer_id', false);
        $useFilter = input('useFilter', false);


        if ($this->request->isAjax())
        {
            return $this->getListJson();
        }

        $url = 'customer/couponrecord/comselectpop';
        if ($customerId) {
            $url .= '?customer_id=' . $customerId;
        }
        if ($useFilter) {
            $url .= (strpos($url, '?') !== false ? '&' : '?') . 'useFilter=' . $useFilter;
        }

        $yjyComSelectParams = [
                                'url' => $url,
                                'pk' => 'id',
                                'sortName' => 'id',
                                'search' => false,
                                'commonSearch' => false,
                                //多选时表格 JQUERY 选择器
                                'parentSelector' => '#t-coupon-select',
                                'columns' => [
                                    ['field' => 'id', 'title' => __('Id')],
                                    ['field' => 'name', 'title' => __('Name')],
                                    ['field' => 'pay_amount', 'title' => __('Pay_amount')],
                                    ['field' => 'amount', 'title' => __('Amount')],
                                    ['field' => 'usage_limit', 'title' => __('Usage_limit')],
                                    ['field' => 'used_balance_id', 'title' => __('Used_balance_id')],
                                    ['field' => 'expiration', 'title' => __('Expiration'), 'formatter' => 'Backend.api.formatter.date'],
                                    ['field' => 'remark', 'title' => __('Remark'), 'formatter' => 'Backend.api.formatter.content'],
                                ]
                            ];
        
        $fields = ['id', 'name', 'pay_amount', 'amount', 'remark'];

        comselectinitparams($yjyComSelectParams, $fields);

        $yjyComSelectParams = json_encode($yjyComSelectParams);
        $this->view->assign('yjyComSelectParams', $yjyComSelectParams);

        //extra
        $this->view->assign('customerId', $customerId);
        $this->view->assign('useFilter', $useFilter);
        
        return $this->view->fetch();
    }

    private function getListJson()
    {
        //如果发送的来源是Selectpage，则转发到Selectpage
        if ($this->request->request('pkey_name'))
        {
            return $this->selectpage();
        }
        list($where, $sort, $order, $offset, $limit) = $this->buildparams();
        $customerId = input('customer_id', false);
        $useFilter = input('useFilter', false);
        $extraWhere = [];
        if ($customerId) {
            $extraWhere['customer_id'] = $customerId;
        }

        $couponJoin = 'LEFT';
        $filterWhere = [];
        if ($useFilter) {
            $couponJoin = 'INNER';
            $curTime = time();
            $filterWhere = "coupon_records.used_balance_id = 0 AND (coupon.expiration = 0 OR coupon.expiration > $curTime)";
        }
        // $subQuery = $this->model->where($where)->where($extraWhere)->buildSql();

        $total = $this->model->alias('coupon_records')
                    ->field('coupon_records.*, name, pay_amount, amount, expiration, remark')
                    ->join(Db::getTable('coupon') . ' coupon', ' coupon_records.coupon_id = coupon.id', $couponJoin)
                    ->join(Db::getTable('customer') . ' customer', ' coupon_records.customer_id = customer.ctm_id', 'LEFT')
                    ->where($filterWhere)
                    ->where($where)
                    ->where($extraWhere)
                    ->where(['status' => 0])
                    ->order('coupon_records.' . $sort, $order)
                    ->count();

        $list = $this->model->alias('coupon_records')
                    ->field('coupon_records.*, customer.ctm_name, name, pay_amount, amount, expiration, remark')
                    ->join(Db::getTable('coupon') . ' coupon', ' coupon_records.coupon_id = coupon.id', $couponJoin)
                    ->join(Db::getTable('customer') . ' customer', ' coupon_records.customer_id = customer.ctm_id', 'LEFT')
                    ->where($filterWhere)
                    ->where($where)
                    ->where($extraWhere)
                    ->where(['status' => 0])
                    ->order('coupon_records.' . $sort, $order)
                    ->limit($offset, $limit)
                    ->select();

        $result = array("total" => $total, "rows" => $list);

        return json($result);
    }
}
