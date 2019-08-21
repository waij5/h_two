<?php

namespace app\admin\controller\stat;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use app\admin\model\MonthCustomerStat;

/**
 * 顾客月度信息统计
 *
 * @icon fa fa-circle-o
 */
class Customermonthlystat extends Backend
{
    
    /**
     * MonthCustomerStat模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('MonthCustomerStat');

    }
    
    public function index()
    {
        $statDateStart = date('Y-m', time());
        $statDateEnd = date('Y-m', time());
        $this->view->assign('statDateStart',$statDateStart);
        $this->view->assign('statDateEnd',$statDateEnd);
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildParams(null, null, false);
            list($bwhere, $extraWhere) = $this->buildParams2($where);
            $sort = 'customer_id';
        
            //默认当月
            $statDateStart = isset($bwhere['stat_date'][1][0]) ? $bwhere['stat_date'][1][0] : date('Y-m', time());
            $statDateEnd = isset($bwhere['stat_date'][1][1]) ? $bwhere['stat_date'][1][1] : date('Y-m', time());
            
            $pureWhere = $bwhere;
            unset($pureWhere['stat_date']);

            //期初
            $lastPeriod = date('Y-m', strtotime('-1 month', strtotime($statDateStart)));

            $list = MonthCustomerStat::getDataList($statDateStart, $statDateEnd, $lastPeriod, $pureWhere, $sort, $order, $offset, $limit);

            // $total = $this->model
            //     ->where($bwhere)
            //     ->order($sort, $order)
            //     ->count();
            $total = MonthCustomerStat::getDataListCnt($statDateStart, $statDateEnd, $lastPeriod, $pureWhere);

            $result = array("total" => $total, "rows" => $list);


            if($offset == 0) {
                $summary = MonthCustomerStat::getDataSummary($statDateStart, $statDateEnd, $lastPeriod, $pureWhere);
                $result = array("total" => $total, "rows" => $list, 'summary' => $summary);
            } else {
                $result = array("total" => $total, "rows" => $list);
            }



            return json($result);
        }
        return $this->view->fetch();
    }

      /**
     * 获取进度信息
     */
    public function downloadprocess()
    {
        return $this->commondownloadprocess('monthlycustomerstatreport', 'customermonthlystat stat');
    }

    public function add()
    {
        $this->error(__('Access denied!'));
    }

    public function edit($ids = NULL)
    {
        $this->error(__('Access denied!'));
    }

    

    public function del($ids = "")
    {
        $this->error(__('Access denied!'));
    }

    public function multi($ids = "")
    {
        $this->error(__('Access denied!'));
    }

}
