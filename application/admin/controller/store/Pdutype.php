<?php

namespace app\admin\controller\store;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use fast\Tree;

/**
 * 产品项目分类
 *
 * @icon fa fa-circle-o
 */
class Pdutype extends Backend
{
    
    /**
     * Pdutype模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Pdutype');

        $tree = Tree::instance();
        $tree->init($this->model->order('weigh desc,id desc')->select(), 'pid');
        $this->pdutypelist = $tree->getTreeList($tree->getTreeArray(0), 'name');
        $parentList = [0 => ['type' => 'all', 'name' => __('None')]];
        foreach ($this->pdutypelist as $k => $v)
        {
            $parentList[$v['id']] = $v;
        }
        $this->view->assign("typeList", $this->model->getTypeList());
        $this->view->assign("statusList", $this->model->getStatusList());
        $this->view->assign("parentList", $parentList);
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
    {//var_dump($this->pdutypelist);
        if ($this->request->isAjax())
        {
            $search = $this->request->request("search");
            //构造父类select列表选项数据
            $list = [];
            if ($search)
            {
                foreach ($this->pdutypelist as $k => $v)
                {
                    if (stripos($v['name'], $search) !== false || stripos($v['code'], $search) !== false)
                    {
                        $list[] = $v;
                    }
                }
            }
            else
            {
                $list = $this->pdutypelist;
            }
            $total = count($list);
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * Selectpage搜索
     * 
     * @internal
     */
    public function selectpage()
    {
        return parent::selectpage();
    }

}
