<?php

namespace app\index\controller;

use app\admin\model\Deptment;
use app\admin\model\Fpro;
use app\admin\model\FProSets;
use app\admin\model\Project;
use app\common\controller\Frontend;

class Order extends Frontend
{
    public function _initialize()
    {
        parent::_initialize();
    }

    public function cart()
    {
        $useCookie = $this->request->post('useCookie', false);
        $isSuit    = $this->request->post('is_suit', false);

        if ($useCookie) {
            $itemPreParams = [];
            $suitIds       = [];
            if (isset($_COOKIE['ycart']) && ($ycart = json_decode($_COOKIE['ycart'], true))) {
                if (isset($ycart['pros'])) {
                    $itemPreParams = $ycart['pros'];
                }
                if (isset($ycart['sets'])) {
                    $suitIds = $ycart['sets'];
                }
            }
        } else {
            $itemPreParams = $this->request->post("itemParams/a", []);
            $suitIds       = $this->request->post('suits', '');
            $suitIds       = explode(',', $suitIds);

        }
        $preProList = array_filter($itemPreParams, function ($row) {
            return $row['qty'] > 0;
        });

        $proList = Project::alias('project')
            ->join((new Fpro)
                    ->getTable() . ' fpro', 'project.pro_id = fpro.pro_id')
            ->where(['project.pro_status' => 1, 'fpro.status' => 1])
            ->where(['fpro.pro_id' => ['in', array_column($preProList, 'pk')]])
            ->column('project.pro_id, project.pro_name, project.pro_amount, project.pro_spec', 'project.pro_id');

        $settings   = array();
        $prosets    = array();
        $itemParams = [];

        if (!empty($suitIds)) {
            $settings = FProSets::where('id', 'in', $suitIds)->where('is_suit', '=', 1)->column('id, name,  price, set_price, settings');
        }
        if (!empty($proList)) {
            foreach ($preProList as $key => $row) {
                if (!empty($proList[$row['pk']])) {
                    $itemParams[] = ['pk' => $row['pk'], 'pro_name' => $proList[$row['pk']]['pro_name'], 'pro_spec' => $proList[$row['pk']]['pro_spec'], 'qty' => $row['qty'], 'pro_amount' => $proList[$row['pk']]['pro_amount'], 'price' => $row['price']];
                }
            }
        }
        // print_r($itemPreParams);
        $needSelectDept = in_array(0, array_column($itemPreParams, 'dept_id'));
        $deductDeptList = Deptment::where(['dept_type' => 'deduct', 'dept_status' => 1])->select();

        $this->view->assign('title', '订单确认');
        $this->assign('itemParams', $itemParams);
        $this->assign('settings', $settings);
        $this->assign('isSuit', $isSuit);
        $this->assign('needSelectDept', $needSelectDept);
        $this->assign('deductDeptList', $deductDeptList);
        return $this->view->fetch();
    }

    public function csuccess()
    {
        return $this->view->fetch();
    }

    public function checkout()
    {

    }
}
