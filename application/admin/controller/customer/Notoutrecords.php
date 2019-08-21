<?php

namespace app\admin\controller\customer;

use app\common\controller\Backend;

use think\Controller;
use think\Request;

/**
 * 顾客未出账金额记录
 *
 * @icon fa fa-circle-o
 */
class Notoutrecords extends Backend
{
    
    /**
     * NotoutRecords模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('NotoutRecords');

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个方法
     * 因此在当前控制器中可不用编写增删改查的代码,如果需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        $yesterday = date('Y-m-d', strtotime('-1 day', time()));
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name'))
            {
                return $this->selectpage();
            }
            // if (input('stat', fal))
            $extraWhere = ['status' => 1];
            $filter = $this->request->get("filter", '');
            $filter = json_decode($filter, TRUE);
            if (empty($filter['rec.stat_date'])) {
                $extraWhere['rec.stat_date'] = $yesterday;
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model->alias(' rec')
                    ->join(model('Customer')->getTable() . ' customer', 'rec.customer_id = customer.ctm_id', 'LEFT')
                    ->where($where)
                    ->where($extraWhere)
                    ->order($sort, $order)
                    ->count();

            // $subSql = $this->model
            //             ->where($where)
            //             ->where(['status' => 1])
            //             ->order($sort, $order)
            //             ->limit($offset, $limit)
            //             ->buildSql();

            $list = $this->model->alias(' rec')
                    // ->table($subSql . ' rec')
                    ->field('rec.*, customer.ctm_name')
                    ->join(model('Customer')->getTable() . ' customer', 'rec.customer_id = customer.ctm_id', 'LEFT')
                    ->where($where)
                    ->where($extraWhere)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        $this->view->assign('yesterday', $yesterday);
        return $this->view->fetch();
    }

    public function add($ids = null)
    {
        $this->error(__('Records can not be add!'));
    }

    public function edit($ids = null)
    {
        $this->error(__('Records can not be edit!'));
    }

    public function del($ids = "")
    {
        $this->error(__('Records can not be delete!'));
    }

    public function multi($ids = "")
    {
        $this->error(__('Access denied!'));
    }


}
