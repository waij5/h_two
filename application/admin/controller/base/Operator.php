<?php

namespace app\admin\controller\base;

use app\common\controller\Backend;

use think\Controller;
use think\Request;

/**
 * 值班人员
 *
 * @icon fa fa-circle-o
 */
class Operator extends Backend
{
    
    /**
     * Operator模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Operator');
    }

    public function add()
    {
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
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : true) : $this->modelValidate;
                        $this->model->validate($validate);
                    }
                    if (empty($params['admin_id'])) {
                        $this->error('请选择对应职员');
                    } else {
                        $doesStaffExist = model('Operator')->where('admin_id', '=', $params['admin_id'])->count();
                        if($doesStaffExist) {
                            $this->error('请勿重复添加');
                        }
                    }
                    $staff = model('admin')->find($params['admin_id']);
                    if (empty($staff)) {
                        $this->error('职员未找到');
                    }

                    $params['name'] = $staff->nickname;

                    $result = $this->model->save($params);
                    if ($result !== false)
                    {
                        $this->success();
                    }
                    else
                    {
                        $this->error($this->model->getError());
                    }
                }
                catch (\think\exception\PDOException $e)
                {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }

        $adminList = model('Admin')->getBriefAdminList();
        $this->view->assign('adminList', $adminList);

        return $this->view->fetch();
    }
}
