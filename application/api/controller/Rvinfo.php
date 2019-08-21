<?php

namespace app\api\controller;

use app\common\controller\Api;
use think\Controller;
use think\Session;
use think\Request;
use app\admin\model\Admin;
use app\admin\model\Rvinfo as MRvinfo;
use think\Db;


/**
 *
 *
 * @icon fa fa-circle-o
 */
class Rvinfo extends Api
{
	public function index(){
        // $adminId = 1;

        $admin = Request::instance()->admin;

        $adminId = $admin->id;

        $this->model = new MRvinfo;
		$adminM = new Admin;
        $briefAdminList = $adminM->getBriefAdminList2();

            // list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null);

            //如果不是超管进行筛选
            // $isSuperAdmin = $this->auth->isSuperAdmin();
            $where = [];
            $bwhere = [];
            $bwhere['rvinfo.admin_id'] = $adminId;


            //不是超管就只能看到自己的回访记录
            // if(!$isSuperAdmin){
            //     $bwhere = ['a.admin_id' => $admin['id']];
            // }
            
            // 如果不是超管，但是主任，部门权限有全部则可以看到下属员工的回访记录
            // if (!$isSuperAdmin) {
            //     $bwhere['rvinfo.admin_id'] = [
            //         'exp',
            //         'in ' . $this->deptAuth->getAdminCondition($fields = 'id', $admin['id']),
            //     ];
            // }

            // $filter = $this->request->get("filter", '');
            // $filter = json_decode($filter, TRUE);
            
            // if (empty($filter) && empty($bwhere)) {
            if (empty($bwhere)) {
                $total = $this->model->alias('rvinfo')->count();
            } else {
                $total = $this->model->alias('rvinfo')
                    ->join(DB::getTable('customer') . ' customer', 'rvinfo.customer_id = customer.ctm_id', 'LEFT')
                    ->join(DB::getTable('admin') . ' admin', 'rvinfo.admin_id = admin.id', 'LEFT')
                    ->join(DB::getTable('fat') . ' fat', 'rvinfo.fat_id = fat.fat_id', 'LEFT')
                    ->where($where)
                    ->where($bwhere)
                    ->count();
            }
            
                    
            $list  = $this->model->alias('rvinfo')
                ->field('rvinfo.*, customer.ctm_name, customer.ctm_mobile, customer.ctm_id, admin.nickname, fat.fat_name')
                ->join(DB::getTable('customer') . ' customer', 'rvinfo.customer_id = customer.ctm_id', 'LEFT')
                ->join(DB::getTable('admin') . ' admin', 'rvinfo.admin_id = admin.id', 'LEFT')
                ->join(DB::getTable('fat') . ' fat', 'rvinfo.fat_id = fat.fat_id', 'LEFT')
                ->where($where)
                ->where($bwhere)
                // ->order('rvi_id', 'DESC')
                // ->order($sort, $order)
                // ->limit($offset, $limit)
                ->select();

            // $result = array("total" => $total, "rows" => $list);
            // return json($result);
            $this->success('成功', null, [ 'total' => $total, 'list' => $list]);  
        // }
	}
}

