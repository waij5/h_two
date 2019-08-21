<?php

namespace app\admin\controller\fservice;

use app\common\controller\Backend;
use think\Controller;
use think\Request;

/**
 * 项目设置
 *
 * @icon fa fa-circle-o
 */
class Pro extends Backend
{

    /**
     * Fpro模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Fpro');

    }

    public function add()
    {
        $this->error('功能已禁用');
    }

    /**
     * 编辑
     */
    public function edit($ids = null)
    {
        $proId = input('pro_id', false);
        if ($ids) {
            $row = $this->model->get($ids);
            if (!$row) {
                $this->error(__('No Results were found'));
            }
        } else {
            $row = $this->model->get(function ($query) use ($proId) {
                $query->where('pro_id', '=', $proId);
            });
        }

        if (!$row) {
            $row = (new $this->model());
            if ($proId) {
                $row->pro_id = $proId;
            }
            $row->status     = 1;
            $row->cover      = '';
            $row->video      = '';
            $row->short_desc = '';
            $row->desc       = '';
        }

        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name     = basename(str_replace('\\', '/', get_class($this->model)));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : true) : $this->modelValidate;
                        $row->validate($validate);
                    }
                    $result = $row->allowField(true)->save($params);
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error($row->getError());
                    }
                } catch (\think\exception\PDOException $e) {
                    $this->error($e->getMessage());
                } catch (\think\Exception $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign("row", $row);

        $curProject = model('Project')->find($row->pro_id);
        if ($curProject) {
            $this->view->assign('curProject', $curProject);
        } else {
            $this->error('对应 项目/物资/药品 不存在');
        }
        return $this->view->fetch();
    }

    public function comselectpop()
    {
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $total = $this->model
                ->alias('fpro')
                ->join((new \app\admin\model\Project)->getTable() . ' pro', 'fpro.pro_id = pro.pro_id')
                ->where($where)
                ->where(['pro_status' => 1, 'status' => 1])
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->alias('fpro')
                ->join((new \app\admin\model\Project)->getTable() . ' pro', 'fpro.pro_id = pro.pro_id')
                ->where($where)
                ->where(['pro_status' => 1, 'status' => 1])
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->field([
                    'pro.pro_id',
                    'pro.pro_name',
                    'pro.pro_code',
                    'pro.pro_spec',
                    'pro.pro_amount',
                    'pro.pro_local_amount',
                    'pro.pro_min_amount',
                    'pro.pro_cost',
                    'pro.pro_use_times',
                    'pro.pro_remark',
                    'fpro.cover',
                    'fpro.video',
                    'fpro.short_desc',
                    'fpro.desc',
                ])
                ->select();

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }

        $url = 'fservice/pro/comselectpop';

        $yjyComSelectParams = [
            'url'            => $url,
            'pk'             => 'pro.pro_id',
            'sortName'       => 'pro.pro_id',
            'search'         => false,
            'commonSearch'   => false,
            //多选时表格 JQUERY 选择器
            'parentSelector' => '#t-project-select',
            'columns'        => [
                ['field' => 'pro_id', 'title' => __('Pro_id')],
                ['field' => 'cover', 'title' => __('Cover'), 'formatter' => 'Backend.api.formatter.pic'],
                ['field' => 'pro_code', 'title' => __('Pro_code')],
                ['field' => 'pro_name', 'title' => __('Pro_name')],
                ['field' => 'pro_use_times', 'title' => __('Pro_use_times')],
                ['field' => 'pro_amount', 'title' => __('Pro_amount')],
                // ['field' => 'pro_price', 'title' => __('Pro_price')],
                ['field' => 'pro_remark', 'title' => __('Pro_remark'), 'formatter' => 'Backend.api.formatter.content'],
            ],
            'callback' => 'comselcallback',
        ];

        $fields = ['pro_id', 'pro_code', 'pro_name', 'pro_amount'];

        comselectinitparams($yjyComSelectParams, $fields);

        $yjyComSelectParams = json_encode($yjyComSelectParams);
        $this->view->assign('yjyComSelectParams', $yjyComSelectParams);

        // $this->deptlist = $this->modelDept->getVariousTree("dept_id", "dept_id", "desc", "dept_pid", "dept_name");
        // $deptdata = [];
        // foreach ($this->deptlist as $k => $v)
        // {
        //     $deptdata[$v['id']] = $v['dept_name'];
        // }
        $deptlist = (new \app\admin\model\Deptment)->where(['dept_type' => 'deduct'])->select();
        $deptdata = [];
        $deptdata = ['' => __('NONE')];
        foreach ($deptlist as $k => $v) {
            $deptdata[$v['dept_id']] = $v['dept_name'];
        }
        $this->view->assign('deptdata', $deptdata);

        $feeTypeList = \app\admin\model\Fee::getList();
        $this->view->assign("feeTypeList", $feeTypeList);

        $pdcList = (new \app\admin\model\Pducat)->where(['pdc_pid' => 0, 'pdc_status' => 1])->column('pdc_name', 'pdc_id');
        $this->view->assign('pdcList', $pdcList);

        return $this->view->fetch();
    }

}
