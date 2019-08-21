<?php

namespace app\admin\controller\base;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use think\Db;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Cproject extends Backend
{
    
    /**
     * CProject模型对象
     */
    protected $model = null;
    protected $noNeedRight = ['index', ];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('CProject');

        $rows =  model('deptment')->where('dept_status','=','1')->column('dept_name','dept_id');
        // var_dump($rows);
        $this->view->assign('rows', $rows);
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
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

            $list = model('cproject')->alias(' cpro')
                    ->field('cpro.*,dept.dept_name')
                    ->join(Db::getTable('deptment') . ' dept', 'cpro.dept_id=dept.dept_id', 'LEFT')
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    public function add()
    {
        if($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            $result = $this->model->save($params);
            if($result)
            {
                $this->success();
            }else
            {
                $this->error();
            }

        }
        return $this->view->fetch();
   }

    public function edit($ids = NULL)
    {
        $row = $this->model->get(['id' => $ids]);
        if(!$row)
            $this->error(__('No Results were found'));

        if($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            $result = $row->save($params);
            if($result)
            {
                $this->success();
            }else
            {
                $this->error();
            }
        }   
        $this->view->assign("row",$row);
        return $this->view->fetch();
    }

}
