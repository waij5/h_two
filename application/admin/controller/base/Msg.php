<?php

namespace app\admin\controller\base;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use app\admin\model\Msgtype;

/**
 * 消息管理
 *
 * @icon fa fa-circle-o
 */
class Msg extends Backend
{
    
    /**
     * Msg模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Msg');

        $this->view->assign('msgTypeList', Msgtype::getList());
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

            $admin = \think\Session::get('admin');
            //创建时间到今天为止
            $todayEnd = strtotime(date('Y-m-d 23:59:59'));
            $extraWhere = ['msg_to' => $admin->id, 'createtime' => ['elt', $todayEnd]];

            $total = $this->model
                    ->where($where)
                    ->where($extraWhere)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->where($where)
                    ->where($extraWhere)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            $admins = model('Admin')->getBriefAdminList();
            foreach ($list as $key => $row) {
                $list[$key]['msg_from_admin_name'] = '--';
                $list[$key]['msg_to_admin_name'] = '--';
                if (isset($admins[$row['msg_from']])) {
                    $list[$key]['msg_from_admin_name'] = $admins[$row['msg_from']];
                }
                if (isset($admins[$row['msg_to']])) {
                    $list[$key]['msg_to_admin_name'] = $admins[$row['msg_to']];
                }
            }

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }
 
    public function add()
    {
        $info = model('admin')->column('id,username'); 

        if($this->request->isPost())
         {
             $params = $this->request->post();

             $dataset = [];       
             $dataset['msg_type'] = $params['row']['msg_type'];
             $dataset['msg_from'] = $params['row']['msg_from'];
             $dataset['msg_to'] = $params['row']['msg_to'];
             $dataset['msg_title'] = $params['row']['msg_title'];
             $dataset['msg_content'] = $params['row']['msg_content'];

             $result = $this->model->create($dataset);
             if($result)
             {
                $this->success();
             }else
             {
                $this->error();
             } 

         }

         $this->view->assign('info', $info);
         return $this->view->fetch();
    }

    public function edit($ids = NULL)
    {
        $info = model('admin')->column('id,username'); 
        $row = $this->model->get(['msg_id' => $ids]);

        //消息为回访类型
        $msg_type = \app\admin\model\Msgtype::TYPE_REVISIT;
        $this->view->assign("msg_type", $msg_type);

        if(!$row)
            $this->error(__('No Results were found'));

        //更新阅读时间
        if($row['updatetime'] == '0')
        {
            $this->model->where(['msg_id' => $ids])->update(['updatetime' => time()]);
        }
//      if($this->request->isPost())
        // {
//             $params = $this->request->post();
//              $dataset = [];       
//              $dataset['msg_type'] = $row['msg_type'];
//              $dataset['msg_from'] = $row['msg_from'];
//              $dataset['msg_to'] = $row['msg_to'];
//              $dataset['msg_title'] = $row['msg_title'];
//              $dataset['msg_content'] = $row['msg_content'];
//              $dataset['createtime'] = $row['createtime'];
//              if($row['updatetime'] = '0'){
//                 $dataset['updatetime'] = '1';
//              }else{
//                 $dataset['updatetime'] = $row['updatetime'];
//              }
//             $result = $row->save($dataset);
//              if($result)
//              {
//                 $this->success();
//              }else
//              {
//                 $this->error();
//              }
        // }

        $this->view->assign('info', $info);
        $this->view->assign('msgTypeList', Msgtype::getList());
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }
}
