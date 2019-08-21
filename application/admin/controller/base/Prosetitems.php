<?php

namespace app\admin\controller\base;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use think\Db;

/**
 * 套餐项目
 *
 * @icon fa fa-circle-o
 */
class Prosetitems extends Backend
{
    
    /**
     * ProSetItems模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('ProSetItems');
    }

    /**
     * 已弃用
     */
    public function index()
    {
        return $this->error();
    }
    
    /**
     * 指定套餐项目列表
     */
    public function setitemlist($setId)
    {
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            $proSet = model('ProSets')->get($setId);

            if (!$proSet)
                $this->error(__('No Results were found'));

            $proTable = Db::getTable('project') . ' pro';
            $joinCondition = 'pro_set_item.set_pro_id = pro.pro_id';
            $selectFields = 'pro.pro_name as pro_name, pro.pro_amount';

            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $subQuery = $this->model
                    ->where($where)
                    ->where(['pro_set_id' => $setId])
                    ->buildSql();

            $total = $this->model
                        ->table($subQuery . ' pro_set_item')
                        ->join($proTable, 'pro_set_item.set_pro_id = pro.pro_id', 'LEFT')
                        ->order($sort, $order)
                        ->count();

            $list = $this->model
                        ->table($subQuery . ' pro_set_item')
                        ->field('pro_set_item.*, ' . $selectFields)
                        ->join($proTable, $joinCondition, 'LEFT')
                        ->order($sort, $order)
                        ->limit($offset, $limit)
                        ->select();

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
    }

    /**
     * 添加套餐项
     */
    public function add()
    {
        if ($this->request->isPost())
        {
            try
            {
                $params = input();
                $result = $this->model->save($params);
                if ($result !== false)
                {
                    return json(['error' => false, 'msg' => 'Operation completed']);
                }
                else
                {
                    return json(['error' => true, 'msg' => $this->model->getError()]);
                }
            }
            catch (\think\exception\PDOException $e)
            {
                return json(['error' => true, 'msg' => $e->getMessage()]);
            }

            return json(['error' => true, 'msg' => __('Operation failed')]);
        }

        $setId = input('setId', null);
        if (empty($setId)) {
            $this->error(__('Invalid parameters'));
        }
        $proSet = model('ProSets')->get($setId);
        if ($proSet == null) {
            $this->error(__('No Results were found'));
        }

        $this->view->assign('proSet', $proSet);

        return $this->view->fetch();
    }

    public function edit($ids = null)
    {
        $row = $this->model->find($ids);
        if (!$row)
            $this->error(__('No Results were found'));
        if ($this->request->isPost())
        {
            try
            {
                $setItemAmout = input('set_item_amount', -1);
                $setItemQty = input('set_item_qty', -1);

                if ($setItemAmout < 0 || $setItemQty < 0) {
                    return json(['error' => true, 'msg' => 'Invalid parameters']);
                }

                $result = $row->save(['set_item_amount' => $setItemAmout, 'set_item_qty' => $setItemQty]);
                if ($result !== false)
                {
                    return json(['error' => false, 'msg' => 'Operation completed']);
                }
                else
                {
                    return json(['error' => true, 'msg' => $this->model->getError()]);
                }
            }
            catch (\think\exception\PDOException $e)
            {
                return json(['error' => true, 'msg' => $e->getMessage()]);
            }

            return json(['error' => true, 'msg' => __('Operation failed')]);
        }
    }
}
