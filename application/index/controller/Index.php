<?php

namespace app\index\controller;

use app\admin\model\Fpro;
use app\admin\model\FProSets;
use app\admin\model\Project;
use app\common\controller\Frontend;

class Index extends Frontend
{

    public function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {
        return $this->view->fetch();
    }

    public function set($ids = null)
    {
        $proset = FProSets::find($ids);
        if (!$proset) {
            abort(404);
        }
        $settings = json_decode($proset->settings, true);

        return $this->renderForSetting($settings);
    }

    private function renderForSetting($settings)
    {
        $breadCrumb = <<<breadCrumb
    <ul class="breadcrumb">
        <li>
            <a href="/web/index">主页</a> <span class="divider">/</span>
        </li>
        <li>
            <a href="#">类目</a> <span class="divider">/</span>
        </li>
        <li class="active">
            主题
        </li>
    </ul>
breadCrumb;

        if ($settings) {
            $proList = \app\admin\model\Project::alias('pro')
                ->join((new \app\admin\model\Fpro)->getTable() . ' fpro', 'pro.pro_id = fpro.pro_id')
                ->where(['pro.pro_status' => 1, 'fpro.status' => 1])
                ->column('pro.pro_id,pro.pro_name,pro.pro_code,pro.pro_spec,pro.pro_amount,pro.pro_local_amount,pro.pro_min_amount,pro.pro_cost,pro.pro_use_times,pro.pro_remark,fpro.cover,fpro.video,fpro.short_desc,fpro.desc', 'pro.pro_id');
            $tabs        = $settings['tabs'];
            $tabContents = $settings['tabContents'];

            $this->view->assign(compact('breadCrumb', 'tabs', 'proList', 'tabContents'));

            return $this->view->fetch('template/default/set/2');
        } else {
            $this->error('参数不正确');
        }
    }

    public function preview()
    {
        $breadCrumb = <<<breadCrumb
    <ul class="breadcrumb">
        <li>
            <a href="/web/index">主页</a> <span class="divider">/</span>
        </li>
        <li>
            <a href="#">类目</a> <span class="divider">/</span>
        </li>
        <li class="active">
            主题
        </li>
    </ul>
breadCrumb;

        $settings = input('settings', '');
        $settings = json_decode($settings, true);
        if ($settings) {
            $proList = \app\admin\model\Project::alias('pro')
                ->join((new \app\admin\model\Fpro)->getTable() . ' fpro', 'pro.pro_id = fpro.pro_id')
                ->where(['pro.pro_status' => 1, 'fpro.status' => 1])
                ->column('pro.pro_id,pro.pro_name,pro.pro_code,pro.pro_spec,pro.pro_amount,pro.pro_local_amount,pro.pro_min_amount,pro.pro_cost,pro.pro_use_times,pro.pro_remark,fpro.cover,fpro.video,fpro.short_desc,fpro.desc', 'pro.pro_id');
            $tabs        = $settings['tabs'];
            $tabContents = $settings['tabContents'];

            $this->view->assign(compact('breadCrumb', 'tabs', 'proList', 'tabContents'));

            return $this->view->fetch('template/default/set/2');
        } else {
            $this->error('参数不正确');
        }
    }
}
