<?php

namespace app\admin\controller\store;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use think\DB;

/**
 * 处方发药
 *
 * @icon fa fa-circle-o
 */
class Drugks extends Backend
{
    
    /**
     * DepotOutks模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('DepotOutks');
		$producerList = [];
		$depotList = [];
		$userList = [];
		$deptList = [];
		$customList = [];
		$producerLists = model('Producer')->where('status','normal')->order('id', 'asc')->select();
		foreach ($producerLists as $k => $v)
        {
            $producerList[$v['id']] = $v;
        }
		$depotLists = model('Depot')->where('type','1')->where('status','normal')->order('id', 'asc')->select();
		foreach ($depotLists as $k => $v)
        {
            $depotList[$v['id']] = $v;
        }
		$userLists = model('Admin')->order('id', 'asc')->select();
		foreach ($userLists as $k => $v)
        {
            $userList[$v['id']] = $v;
        }
		$deptLists = model('Deptment')->where('dept_status','1')->order('dept_id', 'asc')->select();
		foreach ($deptLists as $k => $v)
        {
            $deptList[$v['dept_id']] = $v;
        }
		// $customLists = model('Customer')->order('ctm_id', 'asc')->select();
		// foreach ($customLists as $k => $v)
  //       {
  //           $customList[$v['ctm_id']] = $v;
  //       }
        $this->view->assign("typeList", $this->model->getTypeList());
        $this->view->assign("statusList", $this->model->getStatusList());
        $this->view->assign("producerList", $producerList);
        $this->view->assign("depotList", $depotList);
        $this->view->assign("userList", $userList);
        $this->view->assign("deptList", $deptList);
        // $this->view->assign("customList", $customList);
    }

    /**
     *  待发药处方列表
     */
    public function index()
    {
         if ($this->request->isAjax())
        {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $extraWhere = ['order_items.item_type' => \app\admin\model\Project::TYPE_MEDICINE];
            $total = \app\admin\model\OrderItems::getUndeliveriedOrderListCount($where, $extraWhere);
            $list = \app\admin\model\OrderItems::getUndeliveriedOrderList($where, $sort, $order, $offset, $limit, $extraWhere);

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     *  已发药处方列表
     */
    public function index_two()
    {
         if ($this->request->isAjax())
        {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $extraWhere = ['order_items.item_type' => \app\admin\model\Project::TYPE_MEDICINE];
            $total = \app\admin\model\OrderItems::getdeliveriedOrderListCount($where, $extraWhere);
            $list = \app\admin\model\OrderItems::getdeliveriedOrderList($where, $sort, $order, $offset, $limit, $extraWhere);

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }
    
   

    /**
     * Selectpage搜索
     * 
     * @internal
     */
    public function selectpage()
    {
        return parent::selectpage();
    }

    /**
     * 选取药品
     * 
     * @internal
     */
    public function selectdrug()
    {	
		$depot_id = $this->request->get('depot_id');
        if ($this->request->isAjax())
        {   
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name'))
            {
                return $this->selectpage();
            }
            $search = $this->request->request("search");
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
			
			$total = model('Product')->where('depot_id',$depot_id)->where('status','normal')->where($where)->order('id', 'asc')->count();
            $list = model('Product')->where('depot_id',$depot_id)->where('status','normal')->where($where)->order('id', 'asc')->order($sort, $order)->limit($offset, $limit)->select();

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        $this->view->assign('depot_id', $depot_id);
        return $this->view->fetch();
    }
    
	

    /**
     * 添加
     */
    public function add(){
		if($this->model->select()){
            $num=$this->model->field('max(id) as id')->find();
			$nums = $num['id']+1;
			if(strlen($nums)<6){
				$num=str_pad($nums,6,"0",STR_PAD_LEFT);
			}else{
				$num=$nums;
			}
			$order_num = 'ly'.$num;
			$this->view->assign("order_num", $order_num);
        }else{
			$this->view->assign("order_num", 'ly000001');
        }
		if ($this->request->isPost()){
            $params = $this->request->post("row/a");
			$drugs_id = $this->request->post("drugs_id/a");
			$storage_num = $this->request->post("storage_num/a");
			$drugRows = array();
			foreach ($drugs_id as $k => &$v){
				$drugRows[$k]['drugs_id'] = $v;
			}
			foreach ($storage_num as $k => &$v){
				$drugRows[$k]['storage_num'] = $v;
			}
			
            if ($params){
                foreach ($params as $k => $v){
                    $v = is_array($v) ? implode(',', $v) : $v;
                }
                try{
                    //是否采用模型验证
                    if ($this->modelValidate){
                        $name = basename(str_replace('\\', '/', get_class($this->model)));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : true) : $this->modelValidate;
                        $this->model->validate($validate);
                    }
                    $result = $this->model->save($params);
					if ($result !== false){
                        $depotout_id = $this->model->id;//var_dump($drugRows);exit;
						$data = [];
						foreach ($drugRows as $k => $v){	
							$promodel = model('Product')->get(['id' => $v['drugs_id']]);
							if ($promodel){
								$stock = $promodel['stock'];
								model('Product')->where('id', $v['drugs_id'])->update([
										'stock' => $stock - $v['storage_num'],
								]);
							}
							
							$data[] = ['goods_id' => $v['drugs_id'], 'depotout_id' => $depotout_id, 'goods_num' => $v['storage_num']];
						}
						$dataresult = model('DepartProduct')->saveAll($data);
						if ($dataresult !== false){
							$this->success();
						}else{
							$this->error(model('DepartProduct')->getError());
						}
                    }elseif($result == false){
                        $this->error($this->model->getError());
                    }
                }
                catch (\think\exception\PDOException $e){
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function edit($ids = NULL)
    {
        if ($this->request->isPost())
        {
            $action = input('act', false);
            $orderId = input('order_id');
            $applyRecId = input('applyRecId');
            $replyInfo = input('reply_info', '');
            $admin = Session::get('admin');
            // $secAuthCondition = $this->secAuth->getCusSecCondition();

            $secAuthCondition = [];
            if ($action == 'cancelorder') {
                $where = $secAuthCondition;
                $where['order_id'] = $orderId;
                //已筛选出符合条件的订单
                $order = model('Orderinfo')->get($where);
                if (empty($order)) {
                    return $this->error(__('No results were found'));
                } else {
                    $this->checkDeptAuth($order['admin_id']);
                    $result = $order->cancelOrder();
                    if ($result['error']) {
                        $this->error($result['msg']);
                    } else {
                        $this->success($result['msg']);
                    }
                }
            } elseif ($action == 'cancelapply' || $action == 'acceptapply' || $action == 'denyapply') {
                $applyRecId = input('applyRecId');
                $applyRecord = model('orderApplyRecords')->get($applyRecId);
                $maxRateLimit = $admin->getDiscountLimit();
                
                if (empty($applyRecord)) {
                    $this->error(__('No results were found'));
                } else {
                    if ($action == 'cancelapply') {
                        if ($admin->id != $applyRecord->apply_admin_id) {
                            //需本人取消
                            $this->error(__('You have no permission'));
                        }
                    } else {
                        //非最高权限 && (没有任何下级 || 申请人不属于自己下级)
                        $this->checkDeptAuth($applyRecord->apply_admin_id);
                    }
                    //$type会自动转为大写
                    $type = str_replace('apply', '', $action);
                    $result = $applyRecord->dealApply($type, $admin->id, $replyInfo, $maxRateLimit);
                    // var_dump($result);
                    if ($result['error']) {
                        $this->error($result['msg']);
                    } else {
                        $this->success($result['msg']);
                    }
                }
            } elseif ($action == 'chargeback') {
                
            } else {
                return $this->error(__('Invalid parameters'));
            }

            $this->error(__('Error occurs.'));
        }

        //权限控制
        // $extraWhere = $this->secAuth->getCusSecCondition('admin_id');
        $extraWhere = [];
        $orderInfos = model('Orderinfo')->getList(['order_id' => $ids], 'order_id', 'DESC', 0, 1, $extraWhere);
        if (count($orderInfos) > 0) {
            $orderInfo = $orderInfos[0];

            // $orderItems = model('OrderItems')->alias('order_items')->field('order_items.*, project.pro_name')->join(Db::getTable('project') . ' project', 'order_items.pro_id=project.pro_id', 'LEFT')->where(['order_id' => $orderInfo->order_id])->order('item_id', 'ASC')->select();

            $orderItems = model('OrderItems')
                          ->alias('oi')->field('oi.*,p.lotnum,p.stock,p.extime')
                          ->join(DB::getTable('Product') . ' p', 'oi.pro_id = p.id', 'LEFT')
                          ->where(['oi.order_id' => $orderInfo->order_id])
                          ->order('oi.item_id', 'ASC')
                          ->select();
                          // var_dump($orderItems);die();
            $this->assign('orderInfo', $orderInfo);
            $this->assign('orderItems', $orderItems);

            $undeliverdListCount = $orderInfo->getUndeliverdListCount([]);
            $deliverdListCount = $orderInfo->getdeliverdListCount([]);
            $this->assign('undeliverdListCount', $undeliverdListCount);
            $this->assign('deliverdListCount', $deliverdListCount);

            // 审批记录处理
            // 最后一条审批记录，一般一个订单只允许有一个审批记录
            $orderApplyRecord = null;
            $canReplyInfoEdit = false;
            $orderApplyRecords = model('orderApplyRecords')->where(['order_id' => $ids])->limit(0, 1)->select();
            
            if ($orderApplyRecords) {
                $orderApplyRecord = $orderApplyRecords[0];
                $briefAdminList = model('Admin')->getBriefAdminList();
                $orderApplyRecord['apply_admin_name'] = '--';
                $orderApplyRecord['reply_admin_name'] = '--';
                if (isset($briefAdminList[$orderApplyRecord->apply_admin_id])) {
                    $orderApplyRecord['apply_admin_name'] = $briefAdminList[$orderApplyRecord->apply_admin_id];
                }
                if (isset($briefAdminList[$orderApplyRecord->reply_admin_id])) {
                    $orderApplyRecord['reply_admin_name'] = $briefAdminList[$orderApplyRecord->reply_admin_id];
                }
                //未回复过方能回复
                if ($orderApplyRecord->reply_status == $orderApplyRecord::STATUS_PENDING) {
                    $canReplyInfoEdit = true;
                }
            }

            $showDeductBtn = false;
            //待支付时，可取消，可支付 页面按钮处理
            $extraButtons = [];
            if ($orderInfo['order_status']  == \app\admin\model\Orderinfo::STATUS_PENDING) {
                array_push($extraButtons, ['title' => __('Pay order'),  'class' => 'btn btn-success', 'id' => 'btn-pay-order', 'icon' => 'fa fa-dollar']);
                array_push($extraButtons, ['title' => __('CANCEL'),  'class' => 'btn btn-danger', 'id' => 'btn-cancel-order', 'icon' => 'fa fa-trash']);
            } elseif ($orderInfo['order_status']  == \app\admin\model\Orderinfo::STATUS_APPLYING) {
                //待审核 可取消 非本人操作即上级操作 审批通过/拒绝
                if ($this->view->admin->id == $orderInfo->admin_id) {
                    //本人操作
                    array_push($extraButtons, ['title' => __('CANCEL'),  'class' => 'btn btn-danger', 'id' => 'btn-cancel-apply', 'icon' => 'fa fa-trash']);
                } else {
                    //上级操作
                    array_push($extraButtons, ['title' => __('Accept apply'),  'class' => 'btn btn-success', 'id' => 'btn-accept-apply', 'icon' => 'fa fa-check']);
                    array_push($extraButtons, ['title' => __('Deny apply'),  'class' => 'btn btn-danger', 'id' => 'btn-deny-apply', 'icon' => 'fa fa-close']);
                }
                $extraButton = '';
            } elseif ($orderInfo['order_status']  == \app\admin\model\Orderinfo::STATUS_PAYED) {
                 //已付款，可划扣， 可退款
                $showDeductBtn = true;
                array_push($extraButtons, ['title' => __('Chargeback'),  'class' => 'btn btn-danger', 'id' => 'btn-chargeback', 'icon' => 'fa fa-dollar']);
            }

            $this->view->assign('canReplyInfoEdit', $canReplyInfoEdit);
            $this->view->assign('orderApplyRecord', $orderApplyRecord);
            $this->view->assign('extraButtons', $extraButtons);
            $this->view->assign('showDeductBtn', $showDeductBtn);
        } else {
            // $this->error(__('No results were found'));
        }
        return $this->view->fetch();
    }
    


    /**
     * 订单未发药列表
     */
    public function undeliveriedlist($ids = '')
    {
        if ($this->request->isAjax())
        {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $orderInfo = model('OrderInfo')
                            ->where(function($query) {
                                $query->where('order_status', \app\admin\model\OrderInfo::STATUS_PAYED)
                                      ->whereOr('order_status', \app\admin\model\OrderInfo::STATUS_COMPLETED);
                            })
                            ->where('order_id', $ids)
                            ->find();


            if (empty($orderInfo)) {
                return json(array("total" => 0, "rows" => []));
            }

            $total = $orderInfo->getUndeliverdListCount($where);
            $list = $orderInfo->getUndeliverdList($where, $sort, $order, $offset, $limit);

            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }

        $this->view->assign('ids', $ids);

        return $this->view->fetch();
    }

    /**
     * 确认发药
     */
    public function dispensing(){
        if($this->request->isAjax()){
            $id = $this->request->post('id');
            $ids = $this->request->post('ids');
            $qty = '-'.$this->request->post('qty');     #药房发药  产品数量 例如：-11
            $money = $this->request->post('money');
            $order_id = $this->request->post('order_id');
            $price = $this->request->post('price');
            $type = '6'; #药房发药
            $status = '2'; # 1: 未发药  2：已发药
            $exp = '药房处方单:';

            $res = model('Stock')->stock_drugks_log($id,$ids,$status,$qty,$money,$price,$order_id,$type,$exp);
            return $res;
            
        }
    }



    /**
     * 订单已发药列表
     */
    public function deliveriedlist($ids = '')
    {
        if ($this->request->isAjax())
        {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $orderInfo = model('OrderInfo')
                            ->where(function($query) {
                                $query->where('order_status', \app\admin\model\OrderInfo::STATUS_PAYED)
                                      ->whereOr('order_status', \app\admin\model\OrderInfo::STATUS_COMPLETED);
                            })
                            ->where('order_id', $ids)
                            ->find();


            if (empty($orderInfo)) {
                return json(array("total" => 0, "rows" => []));
            }

            $total = $orderInfo->getdeliverdListCount($where);
            $list = $orderInfo->getdeliverdList($where, $sort, $order, $offset, $limit);

            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }

        $this->view->assign('ids', $ids);

        return $this->view->fetch();
    }


    /**
     * 撤销发药
     */
    public function revoke(){
        if($this->request->isAjax()){
            $id = $this->request->post('id');
            $ids = $this->request->post('ids');
            $qty = $this->request->post('qty');     #药房撤销发药  产品数量 为正数
            $money = $this->request->post('money');
            $price = $this->request->post('price');
            $order_id = $this->request->post('order_id');
            $type = '7'; #药房撤销发药
            $status = '1'; # 1: 未发药  2：已发药
            $exp = '药房处方单:';

            $res = model('Stock')->stock_drugks_log($id,$ids,$status,$qty,$money,$price,$order_id,$type,$exp);
            return $res;
            
        }
    }
}
