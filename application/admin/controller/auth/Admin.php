<?php

namespace app\admin\controller\auth;

use app\admin\model\AdminResetPwd;
use app\common\controller\Backend;
use app\common\library\Email;
use fast\Random;
use fast\Tree;

/**
 * 管理员管理
 *
 * @icon fa fa-users
 * @remark 一个管理员可以有多个角色组,左侧的菜单根据管理员所拥有的权限进行生成
 */
class Admin extends Backend
{

    protected $model = null;
    //当前登录管理员所有子节点组别
    protected $childrenIds = [];

    protected $noNeedLogin = ['sendresetpwdemail', 'verifyforgetpwdemail'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Admin');

        $groups = $this->auth->getGroups();

        // 取出所有分组
        $grouplist = model('AuthGroup')->all(['status' => 'normal']);
        $objlist   = [];
        foreach ($groups as $K => $v) {
            // 取出包含自己的所有子节点
            $childrenlist = Tree::instance()->init($grouplist)->getChildren($v['id'], true);
            $obj          = Tree::instance()->init($childrenlist)->getTreeArray($v['pid']);
            $objlist      = array_merge($objlist, Tree::instance()->getTreeList($obj));
        }
        $groupdata = [];
        foreach ($objlist as $k => $v) {
            $groupdata[$v['id']] = $v['name'];
        }
        $this->childrenIds = array_keys($groupdata);
        $this->view->assign('groupdata', $groupdata);


        $deptType = model('deptment')->where(['dept_type' => 'deduct'])->column('dept_name', 'dept_id');
        $deptType[0] = '无科室(物资类等)';
        $deptType['*'] = '所有';
        $this->view->assign('deptType', $deptType);

        // $deptList = model('deptment')->getVariousTree("dept_id", "dept_id", "desc", "dept_pid", "dept_name");
        // $deptdata = [0 => __('None')];
        // foreach ($deptList as $k => $v) {
        //     $deptdata[$v['id']] = $v['dept_name'];
        // }

        $deptList = (new \app\admin\model\Deptment)->getVariousTree();
        $this->view->assign('deptList', $deptList);
    }

    /**
     * 查看
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            $groupData = model('AuthGroup')->where('status', 'normal')->column('id,name');

            $childrenAdminIds = [];
            $authGroupList    = model('AuthGroupAccess')
                ->field('uid,group_id')
                ->where('group_id', 'in', $this->childrenIds)
                ->select();

            $adminGroupName = [];
            foreach ($authGroupList as $k => $v) {
                $childrenAdminIds[] = $v['uid'];
                if (isset($groupData[$v['group_id']])) {
                    $adminGroupName[$v['uid']][$v['group_id']] = $groupData[$v['group_id']];
                }

            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total                                       = $this->model
                ->where($where)
                ->where('id', 'in', $childrenAdminIds)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->where($where)
                ->where('id', 'in', $childrenAdminIds)
                ->field(['password', 'salt', 'token'], true)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            //获取部门名称
            // $deptLists = model('deptment')->column('dept_name', 'dept_id');
            $deptLists = \app\admin\model\Deptment::getDeptListCache();
            foreach ($list as $key => $row) {
                if (isset($deptLists[$row['dept_id']])) {
                    $list[$key]['dept_id'] = $deptLists[$row['dept_id']]['dept_name'];
                } else {
                    $list[$key]['dept_id'] = '--';
                }
            }

            foreach ($list as $k => &$v) {
                $groups           = isset($adminGroupName[$v['id']]) ? $adminGroupName[$v['id']] : [];
                $v['groups']      = implode(',', array_keys($groups));
                $v['groups_text'] = implode(',', array_values($groups));
            }

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    public function ressigndevelopstaff()
    {

    }

    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params["dept_id"] == '0') {
                $this->error(__('DEPT_NAME CAN NOT BE EMPTY'));
            }
            if ($params) {
                //加入重名检查， username--unique
                if (!empty($params['username'])) {
                    $doesNameExist = $this->model->where(['username' => $params['username']])->count();
                    if ($doesNameExist) {
                        $this->error(__('usernames exists.', [$params['username']]));
                    }
                }

                $params['salt']     = Random::alnum();
                $params['password'] = md5(md5($params['password']) . $params['salt']);
                $params['avatar']   = '/assets/img/avatar.png'; //设置新管理员默认头像。

                //部门权限管理
                $secRules = array(
                    'direct_sub' => 0,
                    'all_sub'    => 0,
                    'same_level' => 0,
                    'all'        => 0,
                );
                $position = intval($params['position']);
                if ($position > 2 || $position < 0) {
                    $position = 0;
                }
                //只有不为普通职工时部门权限设置才有效
                if ($params['position'] && isset($params['sec_rules'])) {
                    $secRules = array_merge($secRules, $params['sec_rules']);
                }

                $params['sec_rules'] = json_encode($secRules);

                //划扣科室
                $type = $this->request->post("type/a", array());
                $params['dept_type'] = implode(',', $type);
                //如果划扣科室中含有*
                if (strstr($params['dept_type'],'*')) {
                    $params['dept_type'] = '*';
                }

                $admin = $this->model->create($params);
                $group = $this->request->post("group/a");

                //过滤不允许的组别,避免越权
                $group   = array_intersect($this->childrenIds, $group);
                $dataset = [];
                foreach ($group as $value) {
                    $dataset[] = ['uid' => $admin->id, 'group_id' => $value];
                }
                model('AuthGroupAccess')->saveAll($dataset);
                //清楚缓存
                $this->model->clearCache();
                $this->success();
            }
            $this->error();
        }
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function edit($ids = null)
    {
        $row = $this->model->get(['id' => $ids]);
  
        $depttype = explode(',', $row['dept_type']);
        $this->view->assign("depttype", $depttype);

        if (!$row) {
            $this->error(__('No Results were found'));
        }

        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params["dept_id"] == '0') {
                $this->error(__('DEPT_NAME CAN NOT BE EMPTY'));
            }
            if ($params) {
                if ($params['password']) {
                    $params['salt']     = Random::alnum();
                    $params['password'] = md5(md5($params['password']) . $params['salt']);
                } else {
                    unset($params['password'], $params['salt']);
                }

                //部门权限管理
                $secRules = array(
                    'direct_sub' => 0,
                    'all_sub'    => 0,
                    'same_level' => 0,
                    'all'        => 0,
                );
                $position = intval($params['position']);
                if ($position > 2 || $position < 0) {
                    $position = 0;
                }
                //只有不为普通职工时部门权限设置才有效
                if ($params['position'] && isset($params['sec_rules'])) {
                    $secRules = array_merge($secRules, $params['sec_rules']);
                }

                $params['sec_rules'] = json_encode($secRules);

                //划扣科室
                $type = $this->request->post("type/a", array());
                $params['dept_type'] = implode(',', $type);
                //如果划扣科室中含有*
                if (strstr($params['dept_type'],'*')) {
                    $params['dept_type'] = '*';
                }
                $row->save($params);

                // 先移除所有权限
                model('AuthGroupAccess')->where('uid', $row->id)->delete();

                $group = $this->request->post("group/a");

                // 过滤不允许的组别,避免越权
                $group = array_intersect($this->childrenIds, $group);

                $dataset = [];
                foreach ($group as $value) {
                    $dataset[] = ['uid' => $row->id, 'group_id' => $value];
                }
                model('AuthGroupAccess')->saveAll($dataset);
                //清楚缓存
                $this->model->clearCache();
                $this->success();
            }
            $this->error();
        }
        $grouplist = $this->auth->getGroups($row['id']);
        $groupids  = [];
        foreach ($grouplist as $k => $v) {
            $groupids[] = $v['id'];
        }
        $row['sec_rules'] = json_decode($row['sec_rules'], true);
        $this->view->assign("row", $row);
        $this->view->assign("groupids", $groupids);

        return $this->view->fetch();
    }

    /**
     * 删除
     */
    public function del($ids = "")
    {
        if ($ids) {
            // 避免越权删除管理员
            $childrenGroupIds = $this->childrenIds;
            $adminList        = $this->model->where('id', 'in', $ids)->where('id', 'in', function ($query) use ($childrenGroupIds) {
                $query->name('auth_group_access')->where('group_id', 'in', $childrenGroupIds)->field('uid');
            })->select();
            if ($adminList) {
                $deleteIds = [];
                foreach ($adminList as $k => $v) {
                    $deleteIds[] = $v->id;
                }
                $deleteIds = array_diff($deleteIds, [$this->auth->id]);
                if ($deleteIds) {
                    $this->model->destroy($deleteIds);
                    model('AuthGroupAccess')->where('uid', 'in', $deleteIds)->delete();
                    //清楚缓存
                    $this->model->clearCache();
                    $this->success();
                }
            }
        }
        $this->error();
    }

    /**
     * 发送修改密码邮件
     */
    public function sendresetpwdemail()
    {
        if ($this->request->isAjax()) {
            $userName = input('userName');
            $staff    = $this->model->where(['username' => $userName])->find();
            if ($staff == null) {
                $this->error(__('Account does not exist'));
            } else {
                if (!empty($staff->email)) {
                    $resetPwd              = new AdminResetPwd;
                    $resetPwd->admin_id    = $staff->id;
                    $resetPwd->token       = md5(str_pad($staff->id, 11, '0', STR_PAD_LEFT) . Random::alnum(16));
                    $resetPwd->expire_time = time() + 60 * 60 * 24;
                    $resetPwd->status      = 0;
                    if ($resetPwd->save() === false) {
                        $this->error(__('Error occurs'));
                    }

                    $url = url('auth/admin/verifyforgetpwdemail', ['validkey' => $resetPwd->token], $suffix = true, true);
                    $this->view->engine->layout(false);
                    $this->view->assign('staff', $staff);
                    $this->view->assign('url', $url);
                    $mailBody = $this->view->fetch('mail/admin/resetpassword');

                    $email      = new Email;
                    $sendResult = $email->to($staff->email)
                        ->subject(__('Get your password'))
                        ->message($mailBody)
                        ->send();
                    if ($sendResult) {
                        $this->success(__('Mail has been sent to your mail box(%s), please read the mail and reset password', getMaskString($staff->email, '*', 6)));
                    } else {
                        $this->error(__('Failed to send email'));
                    }
                } else {
                    $this->error('There is no email address');
                }
            }
        }

        return $this->view->fetch();
    }

    /**
     * 验证邮箱，修改密码
     */
    public function verifyforgetpwdemail()
    {
        $validkey = input('validkey', false);
        //令牌，未使用，未超时
        $resetPwd = AdminResetPwd::where(['token' => $validkey, 'status' => 0, 'expire_time' => ['egt', time()]])->find();

        if (empty($resetPwd)) {
            return $this->error(__('Parameter %s is empty or Invalid!', 'validkey'));
        } else {
            $staff = $this->model->find($resetPwd->admin_id);
            if (empty($staff)) {
                return $this->error(__('Parameter %s is empty or Invalid!', 'validkey'));
            }
        }

        //重置密码
        if ($this->request->isAjax()) {
            $password = input('password', false);
            if (empty($password) || strlen($password) < 6) {
                return $this->error(__('Parameter %s is empty or Invalid!', __('password')));
            }

            $adminId = $resetPwd->admin_id;
            $updateAdmin = $this->model->get($adminId);
            if ($updateAdmin == null) {
                $this->error(__('Staff %s does not exist.', $adminId));
            } else {
                $params             = array();
                $params['salt']     = Random::alnum();
                $params['password'] = md5(md5($password) . $params['salt']);

                $updateAdmin->save($params);
                $resetPwd->status = 1;
                $resetPwd->save();

                $this->success();
            }
        }

        $this->view->engine->layout(false);
        $this->view->assign('validkey', $validkey);
        $this->view->assign('staff', $staff);
        return $this->view->fetch();
    }

    /**
     * 批量更新
     * @internal
     */
    public function multi($ids = "")
    {
        // 管理员禁止批量操作
        $this->error();
    }

}
