<?php

namespace app\admin\controller\base;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use fast\Tree;

/**
 * 科室管理
 *
 * @icon fa fa-circle-o
 */
class Deptment extends Backend
{
    
    /**
     * Deptment模型对象
     */
    protected $model = null;
    public $pidname = 'dept_pid';

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Deptment');
        $this->request->filter(['strip_tags']);

        $this->deptlist = $this->model->getVariousTree();

        $deptdata = [0 => __('None')];
        foreach ($this->deptlist as $k => $v)
        {
            $deptdata[$v['id']] = $v['name'];
        }
        $this->view->assign("deptdata", $deptdata);

        $dept_type = model('Depttype')->getList();
        $this->view->assign("dept_type", $dept_type);
    }


    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个方法
     * 因此在当前控制器中可不用编写增删改查的代码,如果需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    /**
     * 查看
     */
    public function index()
    {
        if ($this->request->isAjax())
        {
            $search = $this->request->request("search");
            //构造父类select列表选项数据
            $list = [];
            if ($search)
            {
                foreach ($this->deptlist as $k => $v)
                {
                    if (stripos($v['name'], $search) !== false || stripos($v['nickname'], $search) !== false)
                    {
                        $list[] = $v;
                    }
                }
            }
            else
            {
                $list = $this->deptlist;
            }
            $total = count($list);
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    public function edit($ids = NULL)
    {
        //状态禁用时在编辑时不显示
        $this->model = model('Deptment');
        $this->request->filter(['strip_tags']);
        $this->deptlist = $this->model->getVariousTree();
        $deptdata = [0 => __('None')];
        foreach ($this->deptlist as $k => $v)
        {
            $deptdata[$v['id']] = $v['name'];
        }
        $this->view->assign("data", $deptdata);

        $row = $this->model->get(['dept_id' => $ids]);
        if(!$row)
            $this->error(__('No Results were found'));

        if($this->request->isPost())
        {
            //判断修改的父级不能是自己的子级
            $id = model('deptment')->where(['dept_pid' => $ids])->column('dept_id');
            //判断是否有子级
            if($id){
                $param = $this->request->post();
                $pid = $param['row']['dept_pid']; //修改后的pid
                if($pid == $id['0'])
                    $this->error(__('No choose child'));
            }
            $params = $this->request->post("row/a");
            $result = $row->save($params);
            if($result !== false)
            {
                $this->success();
            }else
            {
                $this->error();
            }
        }   
        
        $this->view->assign("row", $row);

        return $this->view->fetch();
    }

}
