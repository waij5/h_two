<?php

namespace app\admin\controller\base;

use app\common\controller\Backend;

use think\Controller;
use think\Request;

/**
 * 回访计划提醒
 *
 * @icon fa fa-circle-o
 */
class Rvplan extends Backend
{
    
    /**
     * Rvplan模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Rvplan');

        // ->where(['rvt_status' => 1])
        $rvtypeList = model('Rvtype')->column('rvt_name','rvt_id');
        $this->view->assign('rvtypeList', $rvtypeList);
    }

    /**
     * 查看
     */
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
            $total = $this->model->getListCount($where, []);

            $list = $this->model->getList($where, $sort, $order, $offset, $limit, []);

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 回访计划日程编辑
     */
    public function editdetail($ids)
    {
        $row = $this->model->find($ids);
        if (!$row)
            $this->error(__('No Results were found'));
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params)
            {
                foreach ($params as $k => &$v)
                {
                    $v = is_array($v) ? implode(',', $v) : $v;
                }
                try
                {
                    //是否采用模型验证
                    if ($this->modelValidate)
                    {
                        $name = basename(str_replace('\\', '/', get_class($this->model)));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : true) : $this->modelValidate;
                        $row->validate($validate);
                    }
                    $result = $row->save($params);
                    if ($result !== false)
                    {
                        $this->success();
                    }
                    else
                    {
                        $this->error($row->getError());
                    }
                }
                catch (think\exception\PDOException $e)
                {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign("row", $row);
        $this->view->assign('rvdays', $row->rvdays);
        return $this->view->fetch();
    }

    /**
     * 删除
     */
    public function del($ids = "")
    {
        if ($ids)
        {
            $isRvplanDeleted = true;
            $rvplan = $this->model->get($ids);

            if ($rvplan) {
                if (!$rvplan->is_deletable) {
                    $this->error(__('Can\'t delete resource when it has been set undeletable!'));
                } else {
                    $isRvplanDeleted = $rvplan->delete();
                }
            }

            if ($isRvplanDeleted !== false) {
                $subCount = model('Rvdays')->where(['rvplan_id' => $ids])->delete();
                $this->success();
            }
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }
}
