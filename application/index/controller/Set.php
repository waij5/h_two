<?php

namespace app\index\controller;

use app\admin\model\Deptment;
use app\admin\model\Fpro;
use app\admin\model\FProSets;
use app\admin\model\Project;
use app\common\controller\Frontend;

class Set extends Frontend
{
    protected $pageLimit = 12;
    public function _initialize()
    {
        parent::_initialize();
        array_push($this->breadcrumb, ['url' => '/web/set/index', 'name' => '科室导航']);
        $this->view->assign('title', '科室导航');
        $this->view->assign('breadcrumb', $this->breadcrumb);
    }

    public function index()
    {
        $selectedDeptId = input('deptId', false);
        $keyword        = trim(input('keyword', ''));


        $deductDepts = Deptment::where('dept_f_status', '=', 1)
            ->where('dept_status', '=', 1)
            ->order('dept_sort desc, dept_id', 'asc')
            ->select();

        $where = ['status' => 1, 'is_suit' => 0];
        if ($keyword) {
            $where['name'] = ['like', '%' . $keyword . '%'];
        }
        if ($selectedDeptId) {
            $where['dept_id'] = $selectedDeptId;
        }

        // $total   = FProSets::where($where)->paginate($this->pageLimit);
        $page = input('page', 1);
        $proSets = FProSets::where($where)->order('is_recommend desc, is_new desc, sort desc, id', 'desc')->paginate($this->pageLimit, false, ['page' => $page]);
        if ($this->request->isAjax()) {

            $this->success('成功获取数据', '', $proSets);
        }

        $this->view->assign('deductDepts', $deductDepts);
        $this->view->assign('selectedDeptId', $selectedDeptId);
        $this->view->assign('proSets', $proSets);

        return $this->view->fetch();
    }

    public function detail($ids = null)
    {
        $proset = FProSets::where('status', '=', 1)->find($ids);
        if (!$proset) {
            abort(404);
        }
        // $settings = json_decode($proset->settings, true);

        return $this->renderForSetting($proset, 'DETAIL');
    }

    public function previewset($ids = null)
    {
        $proset = FProSets::where('status', '=', 1)->find($ids);
        if (!$proset) {
            abort(404);
        }
        // $settings = json_decode($proset->settings, true);

        return $this->renderForSetting($proset, 'PREVIEWSET');
    }

    private function renderForSetting($proset, $mode = 'DETAIL')
    {
        $settings = json_decode($proset->settings, true);
        if ($proset && is_array($settings)) {
            $usedProIds = isset($settings['usedProIds']) ? $settings['usedProIds'] : [];
            $proList = \app\admin\model\Project::alias('pro')
                ->join((new \app\admin\model\Fpro)->getTable() . ' fpro', 'pro.pro_id = fpro.pro_id')
                ->where(['pro.pro_status' => 1, 'fpro.status' => 1])
                ->whereIn('pro.pro_id', $usedProIds)
                ->column('pro.pro_id,pro.pro_name,pro.pro_code,pro.pro_spec,pro.pro_amount,pro.pro_local_amount,pro.pro_min_amount,pro.pro_cost,pro.pro_use_times,pro.pro_remark,pro.dept_id,fpro.cover,fpro.video,fpro.short_desc,fpro.desc', 'pro.pro_id');

            $usedSetIds = isset($settings['usedSetIds']) ? $settings['usedSetIds'] : [];
            $setList = \app\admin\model\FProSets::where('id', 'in', $usedSetIds)
                        ->where('status', '=', 1)
                        ->column('id, dept_id, name, price, set_price, pic,  desc, video', 'id');

            $tabs        = $settings['tabs'];
            $tabContents = $settings['tabContents'];

            $title = $proset->name;
            $viewPath = 'template/default/set/2';
            if ($mode == 'PREVIEW') {
                $title = '预览模式 - ' . $proset->name;
            } elseif ($mode == 'PREVIEWSET') {
                $viewPath = 'set/previewset';
            }

            array_push($this->breadcrumb, ['url' => 'javascript:;', 'name' => $title]);
            $this->view->assign('title', $title);
            $this->view->assign('breadcrumb', $this->breadcrumb);

            $siteConfig = \think\Config::get('site');
            $showOriginalPrice = true;
            if (isset($siteConfig['show_original_price']) && $siteConfig['show_original_price'] == 0) {
                $showOriginalPrice = false;
            }

            $this->view->assign(compact('proset', 'tabs', 'proList', 'setList', 'tabContents', 'showOriginalPrice'));

            return $this->view->fetch($viewPath);
        } else {
            $this->error('参数不正确');
        }
    }

    public function preview()
    {
        $prosetArr = $this->request->post("row/a");

        $validate = new \think\Validate([
            'dept_id'      => 'require',
            'name'         => 'require',
            'is_recommend' => 'require',
            'is_suit'      => 'require',
            'is_new'       => 'require',
            'pic'          => 'require',
            'video'        => 'require',
            'desc'         => 'require',
            'sort'         => 'require',
            'template_id'  => 'require',
            'status'       => 'require',
            'settings'     => 'require',
        ]);
        if (!$validate->check($prosetArr)) {
            dump($validate->getError());
            $this->error('参数不正确');
        }

        $proset = new FProSets($prosetArr);

        return $this->renderForSetting($proset, 'PREVIEW');

    }
}
