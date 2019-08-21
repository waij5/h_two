<?php

namespace app\admin\controller\auth;

use app\common\controller\Backend;
use fast\Tree;
use think\Db;

/**
 * 角色组
 *
 * @icon fa fa-group
 * @remark 角色组可以有多个,角色有上下级层级关系,如果子角色有角色组和管理员的权限则可以派生属于自己组别下级的角色组或管理员
 */
class Group extends Backend
{

    protected $model = null;
    //当前登录管理员所有子节点组别
    protected $childrenIds = [];
    //当前组别列表数据
    protected $groupdata = [];
    //无需要权限判断的方法
    protected $noNeedRight = ['roletree'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('AuthGroup');

        $groups = $this->auth->getGroups();

        // 取出所有分组
        $grouplist = model('AuthGroup')->all(['status' => 'normal']);
        $objlist = [];
        foreach ($groups as $K => $v)
        {
            // 取出包含自己的所有子节点
            $childrenlist = Tree::instance()->init($grouplist)->getChildren($v['id'], TRUE);
            $obj = Tree::instance()->init($childrenlist)->getTreeArray($v['pid']);
            $objlist = array_merge($objlist, Tree::instance()->getTreeList($obj));
        }

        $groupdata = [];
        foreach ($objlist as $k => $v)
        {
            $groupdata[$v['id']] = $v['name'];
        }
        $this->groupdata = $groupdata;
        $this->childrenIds = array_keys($groupdata);
        $this->view->assign('groupdata', $groupdata);
    }

    /**
     * 查看
     */
    public function index()
    {
        if ($this->request->isAjax())
        {
            $list = [];
            foreach ($this->groupdata as $k => $v)
            {
                $data = $this->model->get($k);
                $data->name = $v;
                $list[] = $data;
            }
            $total = count($list);
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a", [], 'strip_tags');
            $params['rules'] = explode(',', $params['rules']);
            if (!in_array($params['pid'], $this->childrenIds))
            {
                $this->error(__('The parent group can not be its own child'));
            }
            $parentmodel = model("AuthGroup")->get($params['pid']);
            if (!$parentmodel)
            {
                $this->error(__('The parent group can not found'));
            }
            // 父级别的规则节点
            $parentrules = explode(',', $parentmodel->rules);
            // 当前组别的规则节点
            $currentrules = $this->auth->getRuleIds();
            $rules = $params['rules'];
            // 如果父组不是超级管理员则需要过滤规则节点,不能超过父组别的权限
            $rules = in_array('*', $parentrules) ? $rules : array_intersect($parentrules, $rules);
            // 如果当前组别不是超级管理员则需要过滤规则节点,不能超当前组别的权限
            $rules = in_array('*', $currentrules) ? $rules : array_intersect($currentrules, $rules);
            $params['rules'] = implode(',', $rules);
            if ($params)
            {
                $this->model->create($params);
                $this->success();
            }
            $this->error();
        }
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function edit($ids = NULL)
    {
        $row = $this->model->get(['id' => $ids]);
        if (!$row)
            $this->error(__('No Results were found'));
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a", [], 'strip_tags');
            // 父节点不能是它自身的子节点
            if (!in_array($params['pid'], $this->childrenIds))
            {
                $this->error(__('The parent group can not be its own child'));
            }
            $params['rules'] = explode(',', $params['rules']);

            $parentmodel = model("AuthGroup")->get($params['pid']);
            if (!$parentmodel)
            {
                $this->error(__('The parent group can not found'));
            }
            // 父级别的规则节点
            $parentrules = explode(',', $parentmodel->rules);
            // 当前组别的规则节点
            $currentrules = $this->auth->getRuleIds();
            $rules = $params['rules'];
            // 如果父组不是超级管理员则需要过滤规则节点,不能超过父组别的权限
            $rules = in_array('*', $parentrules) ? $rules : array_intersect($parentrules, $rules);
            // 如果当前组别不是超级管理员则需要过滤规则节点,不能超当前组别的权限
            $rules = in_array('*', $currentrules) ? $rules : array_intersect($currentrules, $rules);
            $params['rules'] = implode(',', $rules);
            if ($params)
            {
                $row->save($params);
                $this->success();
            }
            $this->error();
            return;
        }
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

    /**
     * 删除
     */
    public function del($ids = "")
    {
        if ($ids)
        {
            $ids = explode(',', $ids);
            $grouplist = $this->auth->getGroups();
            $group_ids = array_map(function($group) {
                return $group['id'];
            }, $grouplist);
            // 移除掉当前管理员所在组别
            $ids = array_diff($ids, $group_ids);

            // 循环判断每一个组别是否可删除
            $grouplist = $this->model->where('id', 'in', $ids)->select();
            $groupaccessmodel = model('AuthGroupAccess');
            foreach ($grouplist as $k => $v)
            {
                //禁止删除ID 为1，2的角色组， 超级管理员，主任
                if ($v['id'] == 1 || $v['id'] == 2) {
                    $ids = array_diff($ids, [$v['id']]);
                    continue;
                }
                // 当前组别下有管理员
                $groupone = $groupaccessmodel->get(['group_id' => $v['id']]);
                if ($groupone)
                {
                    $ids = array_diff($ids, [$v['id']]);
                    continue;
                }
                // 当前组别下有子组别
                $groupone = $this->model->get(['pid' => $v['id']]);
                if ($groupone)
                {
                    $ids = array_diff($ids, [$v['id']]);
                    continue;
                }
            }
            if (!$ids)
            {
                $this->error(__('You can not delete group that contain child group and administrators'));
            }
            $count = $this->model->where('id', 'in', $ids)->delete();
            if ($count)
            {
                $this->success();
            }
        }
        $this->error();
    }

    /**
     * 批量更新
     * @internal
     */
    public function multi($ids = "")
    {
        // 组别禁止批量操作
        $this->error();
    }

    /**
     * 读取角色权限树
     * 
     * @internal
     */
    public function roletree()
    {
        $this->loadlang('auth/group');

        $model = model('AuthGroup');
        $id = $this->request->post("id");
        $pid = $this->request->post("pid");
        $parentgroupmodel = $model->get($pid);
        $currentgroupmodel = NULL;
        if ($id)
        {
            $currentgroupmodel = $model->get($id);
        }
        if (($pid || $parentgroupmodel) && (!$id || $currentgroupmodel))
        {
            $id = $id ? $id : NULL;
            $ruleList = collection(model('AuthRule')->order('weigh', 'desc')->select())->toArray();
            //读取父类角色所有节点列表
            $parentRuleList = [];
            if (in_array('*', explode(',', $parentgroupmodel->rules)))
            {
                $parentRuleList = $ruleList;
            }
            else
            {
                $parent_rule_ids = explode(',', $parentgroupmodel->rules);
                foreach ($ruleList as $k => $v)
                {
                    if (in_array($v['id'], $parent_rule_ids))
                    {
                        $parentRuleList[] = $v;
                    }
                }
            }

            //当前所有正常规则列表
            Tree::instance()->init($ruleList);

            //读取当前角色下规则ID集合
            $admin_rule_ids = $this->auth->getRuleIds();
            //是否是超级管理员
            $superadmin = $this->auth->isSuperAdmin();
            //当前拥有的规则ID集合
            $current_rule_ids = $id ? explode(',', $currentgroupmodel->rules) : [];

            if (!$id || !in_array($pid, Tree::instance()->getChildrenIds($id, TRUE)))
            {
                $ruleList = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'name');
                $hasChildrens = [];
                foreach ($ruleList as $k => $v)
                {
                    if ($v['haschild'])
                        $hasChildrens[] = $v['id'];
                }
                $nodelist = [];
                foreach ($parentRuleList as $k => $v)
                {
                    if (!$superadmin && !in_array($v['id'], $admin_rule_ids))
                        continue;
                    $state = array('selected' => in_array($v['id'], $current_rule_ids) && !in_array($v['id'], $hasChildrens));
                    $nodelist[] = array('id' => $v['id'], 'parent' => $v['pid'] ? $v['pid'] : '#', 'text' => $v['title'], 'type' => 'menu', 'state' => $state);
                }
                $this->success('',null,$nodelist);
            }
            else
            {
                $this->error(__('Can not change the parent to child'));
            }
        }
        else
        {
            $this->error(__('Group not found'));
        }
    }

    /**
     * 合并角色组/部门
     */
    public function Merge()
    {
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");

            $params['pid'] = intval($params['pid']);
            $params['pid1'] = intval($params['pid1']);
            $params['pid2'] = intval($params['pid2']);
            $params['rules'] = explode(',', $params['rules']);

            //顶级部门为1，不能合并顶级部门
            if ($params['pid1'] <=1 || $params['pid2'] <= 1) {
                $this->error(__('Can\'t merge groups when top group included.'));
            }
            //不额外对pid进行检查，部门4，5合并到部门4是允许的,新部门ID会变
            if ($params['pid1'] == $params['pid2']) {
                $this->error(__('Invalid operation, groups to merge are the same.'));
            }
            $parentmodel = model("AuthGroup")->get($params['pid']);
            if (!$parentmodel)
            {
                $this->error(__('The parent group can not found'));
            }
            //部门下是否有其它子部门
            $hasSub = $this->model->where(['pid' => $params['pid1']])->whereOr(['pid' => $params['pid2']])->count();
            if ($hasSub) {
                $this->error(__('Merge groups with sub groups not supported.'));
            }

            // 父级别的规则节点
            $parentrules = explode(',', $parentmodel->rules);
            // 当前组别的规则节点
            $currentrules = $this->auth->getRuleIds();
            $rules = $params['rules'];
            // 如果父组不是超级管理员则需要过滤规则节点,不能超过父组别的权限
            $rules = in_array('*', $parentrules) ? $rules : array_intersect($parentrules, $rules);
            // 如果当前组别不是超级管理员则需要过滤规则节点,不能超当前组别的权限
            $rules = in_array('*', $currentrules) ? $rules : array_intersect($currentrules, $rules);
            $params['rules'] = implode(',', $rules);

            // 开始合并部门操作
            Db::startTrans();
            try{
                $newGroupData = array(
                                        'pid' => $params['pid'],
                                        'name' => $params['name'],
                                        'rules' => $params['rules'],
                                        'status' => $params['status'],
                    );
                if ($this->model->save($newGroupData) == false) {
                    throw new Exception("Error when create new section", 1);
                }

                $newGroupId = $this->model->id;

                //转移 待合并部门一，二的人员 到 新的部门下，保留原职务
                $updateGroupData = array('group_id' => $newGroupId);
                model('AuthGroupAccess')->where(['group_id'=> $params['pid1']])->whereOr(['group_id' => $params['pid2']])->update($updateGroupData);
                //由于合并的都是没有子部门的，无需进行下级的处理，如有下面参考未测试,
                //修正原合并部门 下级部门 的上级关系 
                /*
                $this->model->where(['pid' => $params['pid1']])->whereOr(['pid' => $params['pid2']])->update(['pid' => $newSecId]);
                */
                //清除原待合并部门
                $this->model->where(['id' => $params['pid1']])->whereOr(['id' => $params['pid2']])->delete();
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                $this->error(__('Error occurs when merging groups,plz try again.If error occurs again, contact manager.'));
            }

            $this->success(__('
                ', $params['name']));
        }

        return $this->view->fetch();
    }

}
