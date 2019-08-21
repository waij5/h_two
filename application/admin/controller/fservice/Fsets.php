<?php

namespace app\admin\controller\fservice;

use app\common\controller\Backend;
use think\Controller;
use think\Request;

/**
 * 顾客自助品项
 *
 * @icon fa fa-circle-o
 */
class Fsets extends Backend
{

    /**
     * FproSets模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('FProSets');

        $this->assign('deductDeptList', model('Deptment')->where(['dept_f_status' => 1, 'dept_status' => 1])->column('dept_name', 'dept_id'));
        $cusTemplateList = include_once APP_PATH . 'cus_template_config.php';
        $this->view->assign('templateList', $cusTemplateList);
    }

    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个方法
     * 因此在当前控制器中可不用编写增删改查的代码,如果需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    /**
     * 删除
     */
    public function del($ids = "")
    {
        if ($ids) {
            $pk    = $this->model->getPk();
            $count = $this->model->where($pk, '=', $ids)->update(['status' => 0]);
            if ($count !== false) {
                $this->success();
            } else {
                $this->error(__('No rows were deleted'));
            }
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }

    /**
     * 编辑
     */
    public function edit($ids = null)
    {
        $row = $this->model->find($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }

        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                foreach ($params as $k => &$v) {
                    $v = is_array($v) ? implode(',', $v) : $v;
                }
                try
                {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name     = basename(str_replace('\\', '/', get_class($this->model)));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : true) : $this->modelValidate;
                        $row->validate($validate);
                    }
                    $result = $row->save($params);
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error($row->getError());
                    }
                } catch (think\exception\PDOException $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }

        $settings   = json_decode($row->settings, true);
        $usedProIds = isset($settings['usedProIds']) ? $settings['usedProIds'] : array();
        $proList    = \app\admin\model\Project::alias('pro')
            ->join((new \app\admin\model\Fpro)->getTable() . ' fpro', 'pro.pro_id = fpro.pro_id')
        // ->where(['pro.pro_status' => 1, 'fpro.status' => 1])
            ->where(['pro.pro_id' => ['in', $usedProIds]])
            ->column('pro.pro_id,pro.pro_name,pro.pro_code,pro.pro_spec,pro.pro_amount,pro.pro_local_amount,pro.pro_min_amount,pro.pro_cost,pro.pro_use_times,pro.pro_remark,fpro.cover,fpro.video,fpro.short_desc,fpro.desc', 'pro.pro_id');

        $usedSetIds = isset($settings['usedSetIds']) ? $settings['usedSetIds'] : array();
        $sets       = \app\admin\model\FProSets::alias('fprosets')
                        ->join(model('deptment')->getTable() . ' dept', 'fprosets.dept_id = dept.dept_id', 'LEFT')
                        ->where('fprosets.id', 'in', $usedSetIds)
                        ->column('fprosets.id, fprosets.dept_id, fprosets.name, fprosets.pic, fprosets.desc, dept.dept_name', 'fprosets.id');

        $this->view->assign("row", $row);
        $this->view->assign("settings", $settings);
        $this->view->assign("proList", $proList);
        $this->view->assign("sets", $sets);

        return $this->view->fetch();
    }

    public function comselectpop()
    {
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name')) {
                return $this->selectpage();
            }
            // $isSet = input('is_set', 0);

            list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null);
            $total                                       = $this->model->alias('fprosets')->where($where)->where('is_suit', '=', 1)->order($sort, $order)->count();
            $list                                        = $this->model->alias('fprosets')->join(model('deptment')->getTable() . ' dept', 'fprosets.dept_id = dept.dept_id', 'LEFT')->where($where)->where('is_suit', '=', 1)->order($sort, $order)->limit($offset, $limit)->field('fprosets.id, fprosets.dept_id, fprosets.name, fprosets.pic, fprosets.desc, dept.dept_name')->select();

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }

        $url                = 'fservice/fsets/comselectpop'; //$this->request->url();
        $yjyComSelectParams = [
            'url'            => $url,
            'pk'             => 'id',
            'sortName'       => 'sort desc, id',
            'sortOrder'      => 'desc',
            'search'         => false,
            'commonSearch'   => false,
            //多选时表格 JQUERY 选择器
            'parentSelector' => '#t-pro-set-select',
            'columns'        => [
                ['field' => 'id', 'title' => __('id')],
                ['field' => 'dept_name', 'title' => __('dept_id')],
                ['field' => 'name', 'title' => __('name')],
                ['field' => 'pic', 'title' => __('pic'), 'formatter' => 'Backend.api.formatter.pic'],
                ['field' => 'desc', 'title' => __('desc'), 'formatter' => 'Backend.api.formatter.content'],
            ],
            'callback'       => 'setcomselcallback',
        ];

        $fields = ['id', 'dept_id', 'name', 'pic'];
        comselectinitparams($yjyComSelectParams, $fields);

        $yjyComSelectParams = json_encode($yjyComSelectParams);
        $this->view->assign('yjyComSelectParams', $yjyComSelectParams);

        return $this->view->fetch();
    }
}
