<?php

namespace app\admin\controller\cash;

use app\common\controller\Backend;

use think\Controller;
use think\Request;


class Orderadminchange extends Backend
{
    
    /**
     * Orderadminchange
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Itemchange');

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

            $list = $this->model
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            $briefAdminList = model('Admin')->getBriefAdminList2();
           
            foreach($list as $key => $row){
                $list[$key]['admin_id_name'] = '';
                if (!empty($briefAdminList[$list[$key]['admin_id']])){
                    $list[$key]['admin_id_name'] = $briefAdminList[$list[$key]['admin_id']];
                }
                $list[$key]['item_consult_old_admin_name'] = '';
                if (!empty($briefAdminList[$list[$key]['item_consult_old_admin']])){
                    $list[$key]['item_consult_old_admin_name'] = $briefAdminList[$list[$key]['item_consult_old_admin']];
                }
                $list[$key]['item_consult_new_admin_name'] = '';
                if (!empty($briefAdminList[$list[$key]['item_consult_new_admin']])){
                    $list[$key]['item_consult_new_admin_name'] = $briefAdminList[$list[$key]['item_consult_new_admin']];
                }
                

            }

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

}
