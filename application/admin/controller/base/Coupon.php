<?php

namespace app\admin\controller\base;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use think\Db;

/**
 * 优惠券设置
 *
 * @icon fa fa-circle-o
 */
class Coupon extends Backend
{
    
    /**
     * Coupon模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Coupon');

    }

    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $customerId = input('customer_id', false);
            //有顾客ID时，购券， 加入条件 过期时间， 使用次数筛选
            if ($customerId) {
                $subQuery = model('CouponRecords')->field('coupon_id, count(*) as count')->where(['customer_id' => $customerId])->group('coupon_id')->buildSql();

                $curTime = time();
                $total = $this->model->alias('coupon')
                        ->join($subQuery . ' coupon_records', 'coupon.id=coupon_records.count', 'LEFT')
                        ->where($where)
                        ->where(function ($query) use ($curTime) {
                                                    $query->where('expiration = 0')
                                                          ->whereOr('expiration', 'gt', $curTime);
                                                 })
                        ->where(function ($query) {
                                                    $query->where('coupon.usage_per_customer = 0')
                                                    ->whereOr('coupon_records.count is null')
                                                    ->whereOr('coupon_records.count < coupon.usage_per_customer');
                                                })
                        ->order($sort, $order)
                        ->count();
                $list = $this->model->alias('coupon')
                        ->join($subQuery . ' coupon_records', 'coupon.id=coupon_records.count', 'LEFT')
                        ->where($where)
                        ->where(function ($query) use ($curTime) {
                                                    $query->where('expiration = 0')
                                                          ->whereOr('expiration', 'gt', $curTime);
                                                 })
                        ->where(function ($query) {
                                                    $query->where('coupon.usage_per_customer = 0')
                                                    ->whereOr('coupon_records.count is null')
                                                    ->whereOr('coupon_records.count < coupon.usage_per_customer');
                                                })
                        ->order($sort, $order)
                        ->limit($offset, $limit)
                        ->select();
            } else {
                $total = $this->model
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

                $list = $this->model
                        ->where($where)
                        ->order($sort, $order)
                        ->limit($offset, $limit)
                        ->select();
            }

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }

        return $this->view->fetch();
    }
    
    /**
     * 弹窗选择
     * @param string mode single, multi, redirect
     * @param string parentSelector
     * 
     */
    public function comselectpop() {
        $customerId = input('customer_id', false);
        $url = 'base/coupon/index';
        if ($customerId) {
            $url .= '?customer_id=' . $customerId;
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
                                    ['field' => 'expiration', 'title' => __('Expiration'), 'formatter' => 'Backend.api.formatter.datetime'],
                                    ['field' => 'remark', 'title' => __('Remark'), 'formatter' => 'Backend.api.formatter.content'],
                                ]
                            ];
        
        $fields = ['id', 'name', 'pay_amount', 'amount', 'remark'];

        comselectinitparams($yjyComSelectParams, $fields);

        $yjyComSelectParams = json_encode($yjyComSelectParams);
        $this->view->assign('yjyComSelectParams', $yjyComSelectParams);

        return $this->view->fetch();
    }

}
