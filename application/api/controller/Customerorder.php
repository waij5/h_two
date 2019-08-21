<?php

namespace app\api\controller;

use app\common\controller\Api;
use think\Controller;
use think\Session;
use think\Request;
use app\admin\model\Admin;
use app\admin\model\Customer;


/**
 *
 *
 * @icon fa fa-circle-o
 */
class Customerorder extends Api
{
	public function index(){
		$customerId = 1;
		// $adminId = 1;
        
        $admin = Request::instance()->admin;

        $adminId = $admin->id; 

        $adminM = new Admin;
        $briefAdminList = $adminM->getBriefAdminList2();

        $modelCustomer = new Customer;

		// $this->request->filter(['strip_tags']);
        // if ($this->request->isAjax()) {

            // list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null);
            $sort = '';
            $order = '';
            $offset = '';
            $limit = '';
            $where = [];


            $extraWhere = [];
            $extraWhere['customer_id'] = $customerId;
            $customer = $modelCustomer->find($customerId);
            if (empty($customer)) {
                $customer           = new \app\admin\model\Customer;
                $customer->ctm_name = '';
            }


            $total =  \app\admin\model\OrderItems::getListCount($where, ['order_items.customer_id' => $customerId, 'order_items.item_total_times' => ['>', 0]]);
            $list  =  \app\admin\model\OrderItems::getList($where, $sort, $order, $offset, $limit, ['order_items.customer_id' => $customerId, 'order_items.item_total_times' => ['>', 0] , 'order_items.admin_id' => $adminId]);

            $adminList = $adminM->getBriefAdminList();
            foreach ($list as $key => $row) {
                $list[$key]['ctm_name'] = $customer->ctm_name;
                if (isset($adminList[$row['admin_id']])) {
                    $list[$key]['admin_id'] = $adminList[$row['admin_id']];
                }
                if (isset($adminList[$row['consult_admin_id']])) {
                    $list[$key]['consult_admin_id'] = $adminList[$row['consult_admin_id']];
                } else {
                    $list[$key]['consult_admin_id'] = __('Natural diagnosis');
                }
            }
           
            // $result = array("total" => $total, "rows" => $list);

            // return json($result);
            $this->success('成功', null, [ 'total' => $total, 'list' => $list]);  
        // }
	}
}


