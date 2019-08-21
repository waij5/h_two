<?php

namespace app\admin\controller\stat;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use app\admin\model\Dailystat as MDailyStat;

/**
 * 营收日结管理
 *
 * @icon fa fa-circle-o
 */
class Dailystat extends Backend
{
    
    /**
     * DailyStat模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('DailyStat');

    }

    public function test() {
        // $dateArr = ['2017-10-25'];
        // for($i = 0; $i < count($dateArr); $i ++) {
        //     MDailyStat::generateDailyStat($dateArr[$i], 1, true);
        // }
    }
    
    /**
     * 查看
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        list($where, $sort, $order, $offset, $limit) = $this->buildparams();

        $sort = 'stat_date';
        $total = $this->model
                ->where($where)
                ->order($sort, $order)
                ->count();

        $list = $this->model
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

        $result = array("total" => $total, "rows" => $list);

        $this->view->assign('result', $result);

        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function edit($ids = NULL)
    {
        $row = $this->model->find($ids);
        if (!$row)
            $this->error(__('No Results were found'));
        if ($this->request->isPost())
        {
            $this->error(__('Access denied!'));
        }
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

    /**
     * 删除
     */
    public function del($ids = "")
    {
       $this->error(__('Access denied!'));
    }



}
