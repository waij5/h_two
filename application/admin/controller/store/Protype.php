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
class Protype extends Backend
{
    
    /**
     * Protype模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Protype');
        // 必须将结果集转换为数组
        /*Tree::instance()->init(collection($this->model->order('weigh', 'desc')->select())->toArray());
        $this->parentLists = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'name');
        $parentList = [0 => __('None')];
        foreach ($this->parentLists as $k => $v)
        {
            $parentList[$v['id']] = $v['name'];
        }*/
		
        $tree = Tree::instance();
        // $where = [];
        $tree->init($this->model->order('weigh desc,id desc')->select(), 'pid');
        $this->pdutypelist = $tree->getTreeList($tree->getTreeArray(0), 'name');
        $parentList = [0 => ['type' => 'all', 'name' => __('Top')]];
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
            
            if ($this->request->request('pkey_name'))
            {
                return $this->selectpage();
            }
            // list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            //构造父类select列表选项数据
            $list = [];
            $tree = Tree::instance();
                // $where = [];
                // $tree->init($this->model->where($where)->order('weigh desc,id desc')->select(), 'pid');
            $tree->init($this->model->order('weigh desc,id desc')->select(), 'pid');
                $this->plist = $tree->getTreeList($tree->getTreeArray(0), 'name');
            if ($search)
            {
                
                foreach ($this->plist as $k => $v)
                {
                    if (stripos($v['name'], $search) !== false || stripos($v['code'], $search) !== false)
                    {
                        $list[] = $v;
                    }
                }
            }
            else
            {
                $list = $this->plist;
            }
           
            $total = count($list);
            $result = array( "rows" => $list);

            //   获取上级分类的中文名
            $pList = [0 => ['type' => 'all', 'name' => __('Top')]];
            foreach ($list as $k => $v) {
                $pList[$v['id']] = $v;
                $v['pid'] = $pList[$v['pid']]['name'];
            }
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
