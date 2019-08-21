<?php

namespace app\admin\controller\cash;

use app\admin\model\AccountLog;
use app\admin\model\Admin;
use app\admin\model\CustomerOsconsult;
use app\admin\model\DeductImg;
use app\admin\model\DeductRecordImg;
use app\admin\model\Gender;
use app\admin\model\Job;
use app\admin\model\OrderItems;
use app\admin\model\Project;
use app\common\controller\Backend;
use think\Controller;
use think\Db;
use think\Request;
use think\Session;
use yjy\exception\TransException;

/**
 * 订单
 */
class Order extends Backend
{
    protected $model = null;

    protected $noNeedRight = ['staffquicksearch', 'deductview', 'adminfilteredlist2'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('OrderItems');

        $this->deptlist = model('Deptment')->getVariousTree("dept_id", "dept_id", "desc", "dept_pid", "dept_name");
        $deptdata       = [];
        foreach ($this->deptlist as $k => $v) {
            $deptdata[$v['id']] = $v['dept_name'];
        }
        $this->view->assign("deptdata", $deptdata);
        //客户类型
        $ctmtypeLists = model('customertype')->field('type_id,type_name')->select();
        $ctmtypeList  = ['' => __('NONE')];
        foreach ($ctmtypeLists as $ctmtype) {
            $ctmtypeList[$ctmtype['type_id']] = $ctmtype['type_name'];
        }
        $this->view->assign("ctmtypeList", $ctmtypeList);

        $briefAdminList = model('Admin')->getBriefAdminList2();
        $this->view->assign("briefAdminList", $briefAdminList);
        $toolList = \app\admin\model\CocAcceptTool::getList();
        $this->view->assign("toolList", $toolList);
        $deptList = (new \app\admin\model\Deptment)->getVariousTree();
        $this->view->assign('deptList', $deptList);
    }

    public function index()
    {
        return $this->listFilter(null);
    }

    /**
     * 待划扣列表
     */
    public function deductList()
    {
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null, false);
            list($bWhere, $extraWhere)                   = $this->buildparams2($where);

            $admin        = \think\Session::get('admin');
            $isSuperAdmin = $this->auth->isSuperAdmin();

            if (!$isSuperAdmin && $admin->dept_type != '*') {
                $canDeductDepts = explode(',', $admin->dept_type);
                if (!empty($extraWhere['order_items.dept_id'])) {
                    $canDeductDepts = array_intersect($canDeductDepts, array($extraWhere['order_items.dept_id'][1]));
                }
                $extraWhere['order_items.dept_id'] = ['in', $canDeductDepts];
            }
            $extraWhere['order_items.item_status'] = OrderItems::STATUS_PAYED;

            $total = OrderItems::getListCount($bWhere, $extraWhere);
            $list  = OrderItems::getList($bWhere, $sort, $order, $offset, $limit, $extraWhere);

            $deptList = \app\admin\model\Deptment::getDeptListCache();
            foreach ($list as $key => $row) {
                $list[$key]['dept_name'] = isset($deptList[$row['dept_id']]) ? $deptList[$row['dept_id']]['name'] : '';
            }

            if ($offset == 0) {
                //待划扣列表， 不存在退款单的干扰
                $summary = OrderItems::alias('order_items')
                    ->join(model('Customer')->getTable() . ' customer', 'order_items.customer_id = customer.ctm_id', 'LEFT')
                    ->where($bWhere)
                    ->where($extraWhere)
                    ->field('sum( order_items.item_total_times) as item_total_times, sum(order_items.item_used_times) as item_used_times, sum(order_items.item_cost) as item_cost, sum(order_items.item_discount_total) as item_discount_total, sum(order_items.item_total) as item_total, sum(item_undeducted_total) as undeducted_total')
                //sum(order_items.item_amount_per_time * order_items.item_used_times) as deducted_total')
                    ->find();

                $summary['deducted_total'] = $summary['item_total'] - $summary['undeducted_total'];
                $result                    = array("total" => $total, "rows" => $list, 'summary' => $summary);
            } else {
                $result = array("total" => $total, "rows" => $list);
            }

            return json($result);
        }

        $orderStatusList = ['' => __('All')];
        foreach (OrderItems::getStatusList() as $key => $value) {
            $orderStatusList[$key] = $value;
        }
        // $deptLists = (new \app\admin\model\Deptment)->getVariousTree();
        $deptLists = \app\admin\model\Deptment::where(['dept_type' => 'deduct'])->column('dept_name', 'dept_id');
        $this->view->assign('orderStatusList', $orderStatusList);
        $this->view->assign('deptLists', $deptLists);

        return $this->view->fetch();
    }

    //待划扣导出
    public function downloadprocess()
    {
        list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null, false);
        list($bWhere, $extraWhere)                   = $this->buildparams2($where);


	\think\Request::instance()->get(['filter' => '']);

        $admin        = \think\Session::get('admin');
        $isSuperAdmin = $this->auth->isSuperAdmin();

        if (!$isSuperAdmin && $admin->dept_type != '*') {
            $canDeductDepts = explode(',', $admin->dept_type);
            if (!empty($extraWhere['order_items.dept_id'])) {
                $canDeductDepts = array_intersect($canDeductDepts, array($extraWhere['order_items.dept_id'][1]));
            }
            $extraWhere['order_items.dept_id'] = ['in', $canDeductDepts];
        }
        $extraWhere['order_items.item_status'] = OrderItems::STATUS_PAYED;


        return $this->commondownloadprocess('deductlistreport', 'deductListreport', $bWhere, $extraWhere);
    }

    /**
     * 职员开单列表----按订单 项 显示
     * 注意 只有 item_total_times > 0才显示，未使用即退款，
     * 额外退款单，均未记入， 如退款单计入， 求和时， 原始支付金额（退款单为负值），
     * 实付额（退款单为0）可直接相加 ， 优惠券不能相加，剔除退款单券额(退款单为负值)
     */
    public function adminfilteredlist2()
    {
        // return $this->listFilter(null, null, true);
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {

            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null);

            $extraWhere = [];
            if (($customerId = input('customer_id', false))) {
                $extraWhere['customer_id'] = $customerId;
                $this->view->assign('customer_id', $customerId);
            }

            $customer = model('Customer')->find($customerId);

            if (empty($customer)) {
                $customer           = new \app\admin\model\Customer;
                $customer->ctm_name = '';
            }

            $total = OrderItems::getListCount($where, [
                'order_items.customer_id'      => $customerId,
                'order_items.item_total_times' => ['>', 0],
                'order_items.item_status'      => ['<>', OrderItems::STATUS_CANCELED],
            ]);
            $list = OrderItems::getList($where, $sort, $order, $offset, $limit, [
                'order_items.customer_id'      => $customerId,
                'order_items.item_total_times' => ['>', 0],
                'order_items.item_status'      => ['<>', OrderItems::STATUS_CANCELED],
            ]);

            $adminList = model('admin')->getBriefAdminList();
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

            if ($offset == 0) {
                // item_total item_coupon_total item_pay_total item_original_pay_total
                $summary = model('OrderItems')
                    ->alias('order_items')
                    ->where(['order_items.customer_id' => $customerId, 'order_items.item_total_times' => ['>', 0], 'order_items.item_paytime' => ['>', 0]])
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->column('sum(item_total) as item_total, sum(item_original_pay_total) as item_original_pay_total, sum(item_pay_total) as item_pay_total, sum(case when item_status = \'' . OrderItems::STATUS_CHARGEBACK . '\' then 0 else item_coupon_total end) as item_coupon_total');

                $summary = current($summary);
                foreach ($summary as $key => $value) {
                    $summary[$key] = floatval($summary[$key]);
                }

                $result = array("total" => $total, "rows" => $list, 'summary' => $summary);
            } else {
                $result = array("total" => $total, "rows" => $list);
            }

            return json($result);
        }
    }

    /**
     * 开项目单
     */
    public function createprojectorder()
    {
        $type = \app\admin\model\Project::TYPE_PROJECT;
        return $this->createOrder($type);
    }

    /**
     * 开处方单
     */
    public function createrecipeorder()
    {
        $customer_id = input('customer_id', false);
        // $osconsult   = model('customerosconsult')->where(['customer_id' => $customer_id])->order('createtime desc')->limit(1)->column('osc_id,admin_id');
        // foreach ($osconsult as $k => $val) {
        //     $osc_id     = $k;
        //     $oscAdminid = $val;
        // }

        $type = \app\admin\model\Project::TYPE_MEDICINE;
        // , $osc_id, $oscAdminid
        return $this->createOrder($type);
    }

    /**
     * 开物资单
     */
    public function createproductorder()
    {
        $customer_id = input('customer_id', false);
        // $osconsult   = model('customerosconsult')->where(['customer_id' => $customer_id])->order('createtime desc')->limit(1)->column('osc_id,admin_id');
        // foreach ($osconsult as $k => $val) {
        //     $osc_id     = $k;
        //     $oscAdminid = $val;
        // }

        $type = \app\admin\model\Project::TYPE_PRODUCT;
        // , $osc_id, $oscAdminid
        return $this->createOrder($type);
    }

    /**
     * 撤单
     */
    public function cancelorder($ids = '', $checkOperator = false)
    {
        $ids = explode(',', $ids);

        if ($checkOperator && !$this->auth->isSuperAdmin()) {
            $admin      = \think\Session::get('admin');
            $adminId    = $admin->id;
            $orderItems = OrderItems::where(['item_id' => ['in', $ids], 'item_status' => ['not in', [OrderItems::STATUS_PAYED, OrderItems::STATUS_COMPLETED]]])->where(function ($query) use ($adminId) {
                $query->where('admin_id', '=', $adminId)->whereOr('prescriber', '=', $adminId);
            })->select();
        } else {
            $orderItems = OrderItems::where(['item_id' => ['in', $ids], 'item_status' => ['not in', [OrderItems::STATUS_PAYED, OrderItems::STATUS_COMPLETED]]])->select();
        }

        if (count($orderItems) == 0) {
            $this->error('没有此订单或您不能取消此订单');
        }

        $canceledItemIds = array();
        //取消订单
        foreach ($orderItems as $key => $orderItem) {
            $orderItem->item_status = OrderItems::STATUS_CANCELED;
            $orderItem->save();

            array_push($canceledItemIds, $orderItem->item_id);
        }
        //取消相应订单调价申请
        \app\admin\model\OrderApplyRecords::update(['reply_status' => \app\admin\model\OrderApplyRecords::STATUS_CANCELED, 'reply_info' => '取消订单，申请自动结束'], ['item_id' => ['in', $canceledItemIds], 'reply_status' => \app\admin\model\OrderApplyRecords::STATUS_PENDING], ['reply_status', 'reply_info']);

        $this->success();
    }

    public function cancelOwnOrder($ids = '')
    {
        return $this->cancelorder($ids, true);
    }

    public function add()
    {
        $this->error('Access denied');
    }

    public function edit($ids = null)
    {
        if ($this->request->isPost()) {
            $action     = input('act', false);
            $applyRecId = input('applyRecId');
            $replyInfo  = input('reply_info', '');
            $admin      = Session::get('admin');
            if ($action == 'cancelorder') {
                return $this->cancelorder($ids);
            } elseif ($action == 'cancelapply' || $action == 'acceptapply' || $action == 'denyapply') {
                $applyRecId   = input('applyRecId');
                $applyRecord  = model('orderApplyRecords')->get($applyRecId);
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
                        $this->checkDeptAuth($applyRecord->apply_admin_id);
                    }
                    //$type会自动转为大写
                    $type   = str_replace('apply', '', $action);
                    $result = $applyRecord->dealApply($type, $admin->id, $replyInfo, $maxRateLimit);
                    if ($result['error']) {
                        $this->error($result['msg']);
                    } else {
                        $this->success($result['msg']);
                    }
                }
            } else {
                return $this->error(__('Invalid parameters'));
            }

            $this->error(__('Error occurs.'));
        }

        $orderItem = model('OrderItems')->alias('order_items')->field('order_items.*, product.lotnum')
            ->join(Db::getTable('product') . ' product', 'order_items.pro_id=product.id', 'LEFT')
            ->where(['item_id' => $ids])
            ->order('item_id', 'ASC')->select();
        if (count($orderItem) > 0) {
            $orderItems = $orderItem[0];

            $customer = model('Customer')->find($orderItems->customer_id);
            if (empty($customer)) {
                $this->error(__('Customer %s does not exist.'));
            }

            $this->assign('orderItems', $orderItems);
            $this->assign('orderItem', $orderItem);
            $this->assign('customer', $customer);

            $undeliverdListCount = OrderItems::getUndeliverdListCount([]);
            $this->assign('undeliverdListCount', $undeliverdListCount);

            // 审批记录处理
            // 最后一条审批记录，一般一个订单只允许有一个审批记录
            $orderApplyRecord  = null;
            $canReplyInfoEdit  = false;
            $orderApplyRecords = model('orderApplyRecords')->where(['item_id' => $ids])->limit(0, 1)->select();

            if ($orderApplyRecords) {
                $orderApplyRecord                     = $orderApplyRecords[0];
                $briefAdminList                       = model('Admin')->getBriefAdminList();
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

            $showDeductBtn       = false;
            $showCancelSwitchBtn = false;
            //待支付时，可取消，可支付 页面按钮处理
            $extraButtons = [];
            if ($orderItems['item_status'] == OrderItems::STATUS_PENDING) {
                array_push($extraButtons, ['title' => __('Pay order'), 'class' => 'btn btn-success', 'id' => 'btn-pay-order', 'icon' => 'fa fa-dollar']);
                array_push($extraButtons, ['title' => __('CANCEL'), 'class' => 'btn btn-danger', 'id' => 'btn-cancel-order', 'icon' => 'fa fa-trash']);
            } elseif ($orderItems['item_status'] == OrderItems::STATUS_APPLYING) {
                //待审核 可取消 非本人操作即上级操作 审批通过/拒绝
                if ($this->view->admin->id == $orderItems->admin_id) {
                    //本人操作
                    array_push($extraButtons, ['title' => __('CANCEL'), 'class' => 'btn btn-danger', 'id' => 'btn-cancel-apply', 'icon' => 'fa fa-trash']);
                } else {
                    //上级操作
                    array_push($extraButtons, ['title' => __('Accept apply'), 'class' => 'btn btn-success', 'id' => 'btn-accept-apply', 'icon' => 'fa fa-check']);
                    array_push($extraButtons, ['title' => __('Deny apply'), 'class' => 'btn btn-danger', 'id' => 'btn-deny-apply', 'icon' => 'fa fa-close']);
                }
                $extraButton = '';
            } elseif ($orderItems['item_status'] == OrderItems::STATUS_PAYED) {
                //已付款，可划扣， 可退款
                $showDeductBtn       = true;
                $showCancelSwitchBtn = true;
                array_push($extraButtons, ['title' => __('Chargeback'), 'class' => 'btn btn-danger', 'id' => 'btn-chargeback', 'icon' => 'fa fa-dollar']);
            }

            //是否显示划扣记录按钮
            $showDeductRecordBtn     = $this->auth->check('deduct/records/index');
            $showUndeliveriedlistBtn = $this->auth->check('deduct/records/undeliveriedlist');

            $this->view->assign('canReplyInfoEdit', $canReplyInfoEdit);
            $this->view->assign('orderApplyRecord', $orderApplyRecord);
            $this->view->assign('extraButtons', $extraButtons);
            $this->view->assign('showCancelSwitchBtn', $showCancelSwitchBtn);
            $this->view->assign('showDeductBtn', $showDeductBtn);
            $this->view->assign('showDeductRecordBtn', $showDeductRecordBtn);
            $this->view->assign('showUndeliveriedlistBtn', $showUndeliveriedlistBtn);

        }
        return $this->view->fetch();
    }

    public function switchitem($ids = null)
    {
        $orderItem = model('OrderItems')->find($ids);
        if (empty($orderItem)) {
            if (input("dialog", false)) {
                return __('No results were found');
            } else {
                $this->error(__('No Results were found'));
            }
        }
        if ($orderItem->item_status != OrderItems::STATUS_PAYED) {
            $this->error(__('You can\'t cancel this item: incorrect order status!'));
        }

        $customer = model('Customer')->find($orderItem->customer_id);
        if (empty($customer)) {
            $this->error(__('Customer %s does not exist.', @intval($orderItem->customer_id)));
        }

        $calcItemInfo = $orderItem->calcCancelItem();
        if ($calcItemInfo['error']) {
            $this->error($calcItemInfo['msg']);
        }
        $calcReturnTotal = $calcItemInfo['data']['returnTotal'];
        $maxAmount       = $customer->ctm_depositamt + $calcReturnTotal;

        if ($this->request->isPost()) {
            $itemParams     = $this->request->post("itemParams/a", []);
            $params         = $this->request->post("row/a", []);
            $dBalanceRemark = $params['dbalance_remark'];

            //暂时限制只能换一个项目
            if (count($itemParams) > 1) {
                $this->error(__('Plz switch with one item or none'));
            }

            try {
                Db::startTrans();
                //原始项目 及 更改后的 项目组
                $oldOrderItem  = $orderItem->getData();
                $newOrderItems = [];

                //订单项目保存失败
                if ($orderItem->saveWithCalcData($calcItemInfo['data']) === false) {
                    throw new TransException(__('Failed when updating order item info, all operation have been rolled back!'));
                }
                array_push($newOrderItems, $orderItem->getData());

                //本项目 退款单生成
                $cancelItem       = new OrderItems;
                $canceledItemData = $oldOrderItem;
                unset($canceledItemData['item_id']);
                $canceledItemData['item_total_times'] = $oldOrderItem['item_used_times'] - $oldOrderItem['item_total_times'];
                $canceledItemData['item_used_times']  = 0;

                $canceledItemData['item_local_total']    = $calcItemInfo['data']['item_local_total'] - $oldOrderItem['item_local_total'];
                $canceledItemData['item_ori_total']      = $calcItemInfo['data']['item_ori_total'] - $oldOrderItem['item_ori_total'];
                $canceledItemData['item_min_total']      = $calcItemInfo['data']['item_min_total'] - $oldOrderItem['item_min_total'];
                $canceledItemData['item_total']          = $calcItemInfo['data']['item_total'] - $oldOrderItem['item_total'];
                $canceledItemData['item_discount_total'] = $calcItemInfo['data']['item_discount_total'] - $oldOrderItem['item_discount_total'];
                $canceledItemData['item_coupon_total']   = -$calcItemInfo['data']['cancelCouponTotal'];

                $canceledItemData['item_pay_total']          = 0;
                $canceledItemData['item_original_total']     = $calcItemInfo['data']['item_total'] - $oldOrderItem['item_total'];
                $canceledItemData['item_original_pay_total'] = $calcItemInfo['data']['item_pay_total'] - $oldOrderItem['item_pay_total'];

                //设置退款单 生成及付款时间为 现在
                $canceledItemData['item_createtime'] = time();
                $canceledItemData['item_paytime']    = time();
                //未划扣金额
                $canceledItemData['item_undeducted_total'] = 0;

                $canceledItemData['item_old_id'] = $oldOrderItem['item_id'];
                $canceledItemData['item_status'] = OrderItems::STATUS_CHARGEBACK;

                if ($cancelItem->save($canceledItemData) == false) {
                    throw new TransException(__('Operation failed'));
                }
                array_push($newOrderItems, $cancelItem->getData());

                $depositChangeAmt = $calcReturnTotal;
                $couponChangeAmt  = 0;
                //订单订单使用优惠券面额削减
                $orderCouponChangeAmt = -$calcItemInfo['data']['cancelCouponTotal'];
                //新订单使用的优惠券总额
                $canUseCouponTotal = 0.00;
                //新订单每圆的优惠券额
                $newCouponPerCoin = 0.00;
                $itemsTotal       = 0;
                $itemCount        = count($itemParams);

                if ($itemCount) {
                    //总折后金额
                    $itemsTotal = array_sum(array_column($itemParams, 'item_total'));

                    if ($calcItemInfo['data']['cancelCouponTotal'] > 0 && $calcItemInfo['data']['oriReturnTotal'] > 0 && $itemsTotal > 0) {
                        //更换项目 每块钱能享受多少优惠额
                        $useCouponRate     = (1.00 * $itemsTotal / $calcItemInfo['data']['oriReturnTotal']);
                        $useCouponRate     = $useCouponRate >= 1 ? 1.00 : $useCouponRate;
                        $canUseCouponTotal = round(100 * $useCouponRate * $calcItemInfo['data']['cancelCouponTotal']) / 100;
                        $newCouponPerCoin  = $useCouponRate * $calcItemInfo['data']['cancelCouponTotal'] / $itemsTotal;
                    }
                    /**
                    支付过的订单处理
                    原始退还金额 此部分实际使用的券面额
                    此次更换的新项目可享受的优惠券 券面额为：
                    原项目实际使用券面额 * (新项目总额 / 原项目金额)(超过1按1计算)
                    新项目实际需定金额为  新项目总额 - 新项目可享受的优惠券 券面额
                    最后定金消费额为  新项目实际需定金额 - 原项目退还定金额
                    此计算方式 在客户更换项目时能保证原先的优惠券使用且并不会超出
                    每次更改项目低于原项目价格时， 先付的优惠券 实际 使用券面额会递减无法恢复
                    定金和退款 足够支付 新项目
                    实际定金变动值为  + 原项目定金退还值 - (新项目总额 - 可使用优惠面额)
                    $depositChangeAmt = $calcReturnTotal - ;
                     */
                    if ($maxAmount >= ($itemsTotal - $canUseCouponTotal)) {
                        $lastCanUseCouponTotal = $canUseCouponTotal;
                        $currentItemIndex      = 1;

                        $admin             = Session::get('admin');
                        $discountLimit     = $admin->getDiscountLimit();
                        $discountLimitFlag = \think\Config::get('site.discount_limit_flag', false);

                        //获取相应产品/项目信息，检查库存
                        $projects = Project::where(['pro_id' => ['in', array_column($itemParams, 'pk')], 'pro_status' => 1])->column('*', 'pro_id');
                        // foreach ($itemSaveDetect['data']['orderItems'] as $key => $subItem) {
                        foreach ($itemParams as $currentItemIndex => $itemParam) {
                            if (!isset($projects[$itemParam['pk']])) {
                                throw new TransException(__('Projects which ID in (%s) do not exist, or it is out of stock'));
                            }
                            $currentProject = $projects[$itemParam['pk']];
                            if ($currentProject['pro_type'] != Project::TYPE_PROJECT && $currentProject['pro_stock'] < $itemParam['qty']) {
                                throw new TransException(__('Projects which ID in (%s) do not exist, or it is out of stock'));
                            }
                            //订单项折扣超出了折扣权限, 不判断审批，直接中止
                            $itemAmount = $itemParam['item_total'] / $itemParam['qty'];
                            if ($discountLimitFlag && isDiscountAllowed($itemAmount, $currentProject['pro_amount'], $currentProject['pro_min_amount'], $discountLimit) == false) {
                                throw new TransException(__('Order can not be saved: discount out of your permission!'));
                            }
                        }

                        if (($currentItemIndex + 1) == $itemCount) {
                            $itemCouponTotal = $lastCanUseCouponTotal;
                        } else {
                            $itemCouponTotal = floor($itemParam['item_total'] * $newCouponPerCoin * 100) / 100;
                            $lastCanUseCouponTotal -= $itemCouponTotal;
                        }

                        $newItem            = new orderItems;
                        $newItem->item_type = $currentProject['pro_type'];
                        //订单 项目/产品 信息保存
                        $newItem->pro_id   = $currentProject['pro_id'];
                        $newItem->pro_name = $currentProject['pro_name'];
                        $newItem->item_qty = $itemParam['qty'];

                        $newItem->pro_use_times    = $currentProject['pro_use_times'];
                        $newItem->pro_local_amount = $currentProject['pro_local_amount'];
                        $newItem->pro_amount       = $currentProject['pro_amount'];
                        $newItem->pro_min_amount   = $currentProject['pro_min_amount'];
                        //单位，规模，科室，划扣点
                        $newItem->pro_unit    = $currentProject['pro_unit'];
                        $newItem->pro_spec    = $currentProject['pro_spec'];
                        $newItem->dept_id     = $currentProject['dept_id'];
                        $newItem->deduct_addr = $currentProject['deduct_addr'];

                        $newItem->customer_id = $orderItem->customer_id;
                        //单项金额，单次价格 项目折扣率， 项目折扣金额（总），项目本地总额， 项目总额， 项目最小总额，项目实际总额
                        $newItem->item_total_times = $currentProject['pro_use_times'] * $itemParam['qty'];
                        $newItem->item_used_times  = 0;
                        $newItem->item_amount      = $itemAmount;
                        // $newItem->item_amount_per_time  = @$itemAmount / $currentProject['pro_use_times'];
                        $newItem->item_cost             = $currentProject['pro_cost'];
                        $newItem->item_discount_percent = $currentProject['pro_amount'] == 0 ? 100 : (100.0 * $itemAmount / $currentProject['pro_amount']);
                        $newItem->item_discount_total   = $currentProject['pro_amount'] * $itemParam['qty'] - $itemParam['item_total'];
                        $newItem->item_local_total      = $currentProject['pro_local_amount'] * $itemParam['qty'];
                        $newItem->item_ori_total        = $currentProject['pro_amount'] * $itemParam['qty'];
                        $newItem->item_min_total        = $currentProject['pro_min_amount'] * $itemParam['qty'];
                        $newItem->item_total            = $itemParam['item_total'];
                        $newItem->item_coupon_total     = $itemCouponTotal;
                        $newItem->item_pay_total        = $itemParam['item_total'] - $itemCouponTotal;
                        $newItem->item_undeducted_total = $newItem->item_pay_total;

                        $newItem->item_amount_per_time     = bcdiv($itemParam['item_total'], $newItem->item_total_times, 4);
                        $newItem->item_pay_amount_per_time = bcdiv($newItem->item_pay_total, $newItem->item_total_times, 4);

                        //初始额，订单状态等保存
                        $newItem->balance_id              = $orderItem->balance_id;
                        $newItem->item_original_total     = $newItem->item_total;
                        $newItem->item_original_pay_total = $newItem->item_pay_total;
                        $newItem->item_paytime            = time();
                        $newItem->item_status             = OrderItems::STATUS_PAYED;

                        //原始订单，相关人员信息保存
                        $newItem->item_old_id      = $orderItem->item_id;
                        $newItem->osconsult_id     = $orderItem->osconsult_id;
                        $newItem->consult_admin_id = $orderItem->consult_admin_id;
                        $newItem->recept_admin_id  = $orderItem->recept_admin_id;
                        $newItem->admin_id         = $orderItem->admin_id;

                        if ($newItem->save() == false) {
                            throw new TransException(__('Operation failed'));

                        }
                        // item_pay_amount_per_time item_amount_per_time item_original_total
                        // item_original_pay_total item_paytime item_undeducted_total item_status
                        // item_old_id osconsult_id consult_admin_id recept_admin_id admin_id
                        array_push($newOrderItems, $newItem->getData());
                    } else {
                        throw new TransException(__('Not enough money, extra %s needed, plz prestore deposit.', ($itemsTotal - $canUseCouponTotal - $maxAmount)));
                    }

                    $depositChangeAmt = $depositChangeAmt - ($itemsTotal - $canUseCouponTotal);
                    //2019-04-09 退还优惠券  退还最大可用券【原券 / 总价 * 旧项目新价】 - 新项目使用券
                    $couponChangeAmt = $calcItemInfo['data']['cancelCouponTotal'] - $canUseCouponTotal;
                } else {
                    //纯退货
                }
                //更改顾客定金失败
                // if ($customer->changeDepositAmt($depositChangeAmt, AccountLog::TYPE_SWITCH_ITEM, $balanceDate = null, $dBalanceRemark) === false) {
                if ($customer->changeDepositNdCoupon($depositChangeAmt, $couponChangeAmt, AccountLog::TYPE_SWITCH_ITEM, $balanceDate = null, $dBalanceRemark) === false) {
                    throw new TransException(__('Failed when updating customer deposit info, all operation have been rolled back!'));
                } else {
                    //更新客户 消费额, 消费额 与 定金变动 符号相反
                    $customer->updateSalAmt(-$depositChangeAmt);
                }

                $hookParams = [
                    'oldOrderItems'    => $oldOrderItem,
                    'newOrderItems'    => $newOrderItems,
                    'admin_id'         => Session::get('admin')->id,
                    'depositChangeAmt' => $depositChangeAmt,
                    'customer'         => $customer,
                ];
                \think\Hook::listen(OrderItems::TAG_SWITCH_ITEM, $hookParams);

                Db::commit();
                $this->success();
            } catch (\Pdo\Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            } catch (TransException $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }

            $this->error(__('Invalid parameters'));
        }

        //渲染开单按钮
        $this->renderCreateOrder($orderItem->item_type);

        $this->view->assign('maxAmount', $maxAmount);
        // $this->view->assign('calcReturnTotal', $calcReturnTotal);
        $this->view->assign('calcItemData', $calcItemInfo['data']);
        $this->view->assign('customer', $customer);
        $this->view->assign('orderItem', $orderItem);

        return $this->view->fetch();
    }

    /**
     * 划扣
     */
    public function deduct($ids = '')
    {
        $row = model('OrderItems')->find($ids);
        if (!$row) {
            if (input("dialog", false)) {
                return __('No results were found');
            } else {
                $this->error(__('No Results were found'));
            }
        }
        if ($this->request->isPost()) {
            $params       = $this->request->post("row/a");
            $deductParams = $this->request->post("deduct/a", []);

            if ($params) {
                $deductImgs = $this->request->file('deductimgs');
                $savedImgs = [];
                if ($deductImgs) {
                    $imgIndex = 0;
                    $thumbSets = DeductImg::getThumbSets();
                    foreach ($deductImgs as $key => $deductImg) {
                        $info = $deductImg->check(['ext' => 'jpg,jpeg,png']);
                        // ->move(DeductImg::SAVE_PATH);
                        if ($info) {
                            try {
                                ++ $imgIndex;
                                $img = \think\Image::open($deductImg);
                                $subThumbPath = date('Ymd') . DS . md5(microtime(true)) . '_' . $imgIndex . '.' . $img->type();
                                $thumbPath = DeductImg::getSavePath($subThumbPath);
                                if (!is_dir(dirname($thumbPath))) {
                                    if (!mkdir(dirname($thumbPath), 0755, true)) {
                                        $this->error('创建目录失败');
                                    }
                                }
                                $img->thumb($thumbSets['width'], $thumbSets['height'])->save($thumbPath);
                                array_push($savedImgs, [$img, $subThumbPath, $thumbPath]);
                            } catch (\think\image\Exception $e) {
                                $this->error($e->getMessage());
                            }
                        } else {
                            $this->error($file->getError());
                        }
                    }
                } else {
                    $this->error('请上传票据(jpg,jpeg,png)');
                }

                $admin  = Session::get('admin');
                $result = OrderItems::deduct($params, $deductParams, $admin);

                if ($result['error']) {
                    foreach ($savedImgs as $key => $savedImgArr) {
                        @unlink($savedImgArr[2]);
                    }
                    $this->error($result['msg']);
                } else {
                    //成功建立关联
                    $deductRecordId = $result['data']['deductRecord']['id'];
                    foreach ($savedImgs as $key => $savedImgArr) {
                        if ($deductImg = deductImg::saveFromImage($savedImgArr[0], $savedImgArr[1], $savedImgArr[2])) {
                            $deductRecordImg                   = new DeductRecordImg();
                            $deductRecordImg->deduct_record_id = $deductRecordId;
                            $deductRecordImg->deduct_img_id    = $deductImg->id;
                            $deductRecordImg->save();
                        }
                    }
                    $this->success($result['msg'], null, $result['data']);
                }
            }
            $this->error(__('Invalid parameters'));
        }

        // $deductRoles = model('DeductRole')->where(['type' => $row->item_type])->select();
        $deductRoles = [];
        if ($row->item_type == Project::TYPE_PROJECT) {
            $deductRoles = model('DeductRole')->where(['use_when_project' => 1])->select();
        } else {
            $itemProject = model('Project')->find($row->pro_id);
            $this->view->assign("itemProject", $itemProject);

            if ($row->item_type == Project::TYPE_MEDICINE) {
                $deductRoles = model('DeductRole')->where(['use_when_medicine' => 1])->select();
            } elseif ($row->item_type == Project::TYPE_PRODUCT) {
                $deductRoles = model('DeductRole')->where(['use_when_product' => 1])->select();
            }
        }

        $this->view->assign("row", $row);
        $this->view->assign("deductRoles", $deductRoles);
        $this->view->assign('briefAdminList', model('Admin')->getBriefAdminList2());

        return $this->view->fetch();
    }

    public function del($ids = '')
    {
        $this->error('Access denied');
    }

    public function multideduct($ids = '', $customerId = 0, $itemType = \app\admin\model\Project::TYPE_PROJECT)
    {
        $this->request->filter(['strip_tags', 'htmlspecialchars']);
        $idsArr = is_array($ids) ? $ids : explode(',', $ids);
        if (!is_array($idsArr) && count($idsArr) == 0) {
            //无效参数
            $this->error(__('Invalid parameters'));
        }

        $customer = model('Customer')->find($customerId);
        if (empty($customer)) {
            $this->error(__('Customer %s does not exist.', $customerId));
        }
        //自动过滤没有划扣权限的订单项
        $isSuperAdmin = $this->auth->isSuperAdmin();
        if (!$isSuperAdmin) {
            $admin = Session::get('admin');
            if ($admin->dept_type == '*') {
                $deptFilter = [];
            } else {
                $canDeductDepts = explode(',', $admin->dept_type);
                $deptFilter     = ['dept_id' => ['in', $canDeductDepts]];
            }
        } else {
            $deptFilter = [];
        }

        $itemListCount = OrderItems::alias('order_items')
            ->where($deptFilter)
            ->where([
                'customer_id' => $customerId,
                'item_id'     => ['in', $idsArr],
                'item_type'   => $itemType,
                'item_status' => OrderItems::STATUS_PAYED,
            ])
            ->count();
        if ($itemListCount == 0) {
            $this->error('No results were found');
        }

        //非同一顾客的批量划扣， 不允许操作
        $itemList = OrderItems::alias('order_items')
            ->where($deptFilter)
            ->where([
                'customer_id' => $customerId,
                'item_id'     => ['in', $idsArr],
                'item_type'   => $itemType,
                'item_status' => OrderItems::STATUS_PAYED,
            ])
            ->order('item_id', 'ASC')
            ->field('*, (item_total_times - item_used_times) AS item_last_times')
            ->select();

        $itemArr        = collection($itemList)->toArray();
        $maxDeductTimes = min(array_column($itemArr, 'item_last_times'));

        if ($this->request->isAjax()) {
            $deductTimes = (int) $this->request->post('deduct_times', 0);
            if (empty($deductTimes)) {
                $this->error(__("Invalid parameters"));
            }
            $deductParams = $this->request->post("deduct/a", []);
            $deductImgs   = $this->request->file('deductimgs');
            $savedImgs = [];
            if ($deductImgs) {
                $thumbSets = DeductImg::getThumbSets();
                $imgIndex = 0;
                foreach ($deductImgs as $key => $deductImg) {
                    $info = $deductImg->check(['ext' => 'jpg,jpeg,png']);
                    if ($info) {
                        try {
                            ++ $imgIndex;
                            $img = \think\Image::open($deductImg);
                            $subThumbPath = date('Ymd') . DS . md5(microtime(true)) . '_' . $imgIndex . '.' . $img->type();
                            $thumbPath = DeductImg::getSavePath($subThumbPath);
                            if (!is_dir(dirname($thumbPath))) {
                                if (!mkdir(dirname($thumbPath), 0755, true)) {
                                    $this->error('创建目录失败');
                                }
                            }
                            $img->thumb($thumbSets['width'], $thumbSets['height'])->save($thumbPath);
                            array_push($savedImgs, [$img, $subThumbPath, $thumbPath]);
                        } catch (\think\image\Exception $e) {
                            $this->error($e->getMessage());
                        }
                    } else {
                        $this->error($file->getError());
                    }
                }
            } else {
                $this->error('请上传票据(jpg,jpeg,png)');
            }

            $result = OrderItems::batchDeduct4Single($customer, $itemType, $itemList, $deductTimes, $deductParams, $this->view->admin);

            if ($result['error']) {
                //失败删除文件
                foreach ($savedImgs as $key => $savedImgArr) {
                    @unlink($savedImgArr[2]);
                }

                $this->error($result['msg']);
            } else {
                //成功建立关联
                $deductRecordIds = $result['data']['deductRecordIds'];
                foreach ($savedImgs as $key => $savedImgArr) {
                    if ($deductImg = deductImg::saveFromImage($savedImgArr[0], $savedImgArr[1], $savedImgArr[2])) {
                        foreach ($deductRecordIds as $deductRecordId) {
                            $deductRecordImg                   = new DeductRecordImg();
                            $deductRecordImg->deduct_record_id = $deductRecordId;
                            $deductRecordImg->deduct_img_id    = $deductImg->id;
                            $deductRecordImg->save();
                        }
                    }
                }

                $this->success($result['msg']);
            }
        }

        $deductRoles = model('DeductRole')->where(['type' => $itemType])->select();
        $proNameStr  = join(array_column($itemArr, 'pro_name'), ' | ');

        $proStocks = Project::where('pro_type', 'neq', Project::TYPE_PROJECT)
            ->where('pro_id', 'in', array_column($itemArr, 'pro_id'))
            ->column('pro_stock', 'pro_id');

        $this->view->assign('ids', implode(',', array_column($itemArr, 'item_id')));
        $this->view->assign('customer', $customer);
        $this->view->assign('itemType', $itemType);
        $this->view->assign('itemList', $itemList);
        $this->view->assign('proNameStr', $proNameStr);
        $this->view->assign('maxDeductTimes', $maxDeductTimes);
        $this->view->assign("deductRoles", $deductRoles);
        $this->view->assign("proStocks", $proStocks);
        $this->view->assign('briefAdminList', model('Admin')->getBriefAdminList2());

        return $this->view->fetch();

    }

    /**
     * 取消单
     */
    public function cancellist()
    {
        $orderStatus = OrderItems::STATUS_CANCELED;
        return $this->listFilter($orderStatus);
    }

    /**
     * 未付款单
     */
    public function pendinglist()
    {
        $orderStatus = OrderItems::STATUS_PENDING;
        return $this->listFilter($orderStatus);
    }

    /**
     * 已付款单
     */
    public function payedlist()
    {
        // $orderStatus = [OrderItems::STATUS_PAYED, OrderItems::STATUS_COMPLETED];
        $orderStatus = OrderItems::STATUS_PAYED;
        return $this->listFilter($orderStatus);
    }

    /**
     * 业务完成单
     */
    public function completedlist()
    {
        // $orderStatus = [OrderItems::STATUS_PAYED, OrderItems::STATUS_COMPLETED];
        $orderStatus = OrderItems::STATUS_COMPLETED;
        return $this->listFilter($orderStatus);
    }

    /**
     * 手术单
     */
    public function operation()
    {
        $orderStatus = null;
        $orderType   = \app\admin\model\Project::TYPE_PROJECT;

        return $this->listFilter($orderStatus, $orderType, true);
    }

    /**
     * 科室消费
     */
    public function deptconsumption()
    {
        $orderStatus = null;
        $orderType   = \app\admin\model\Project::TYPE_MEDICINE;

        return $this->listFilter($orderStatus, $orderType, true);
    }

    /**
     * 物资单
     */
    public function productorderlist()
    {
        $orderStatus = null;
        $orderType   = \app\admin\model\Project::TYPE_PRODUCT;

        return $this->listFilter($orderStatus, $orderType, true);
    }

    /**
     * listFilter
     */
    private function listFilter($orderStatus = null, $orderType = null, $useAdminFilter = false, $hasSub = false)
    {
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {

            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name')) {
                return $this->selectpage();
            }

            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $extraWhere = array();
            if ($orderStatus !== null) {
                if (is_array($orderStatus)) {
                    $extraWhere['item_status'] = ['in', $orderStatus];
                } else {
                    $extraWhere['item_status'] = $orderStatus;
                }
            }
            if ($orderType !== null) {
                if (is_array($orderType)) {
                    $extraWhere['item_type'] = ['in', $orderType];
                } else {
                    $extraWhere['item_type'] = $orderType;
                }
            }

            if (($customerId = input('customer_id', false))) {
                $extraWhere['customer_id'] = $customerId;
                $this->view->assign('customer_id', $customerId);
            }

            $list  = OrderItems::getList($where, $sort, $order, $offset, $limit, $extraWhere);
            if ($offset == 0) {
                $summary = OrderItems::getListSummary(array_merge([$where], $extraWhere), true);
                $total   = $summary['count'];
                $result = array("total" => $total, "rows" => $list, 'summary' => $summary);
            } else {
                $total = OrderItems::getListCount($where, $extraWhere);
                $result = array("total" => $total, "rows" => $list);
            }

            return json($result);
        }

        $orderStatusList = ['' => __('All')];
        foreach (\app\admin\model\OrderItems::getStatusList() as $key => $value) {
            $orderStatusList[$key] = $value;
        }

        $newOrderBtn = '';
        if (!empty($orderType)) {
            $btnFormat = '<a href="javascript:;" class="btn btn-success" id="%s"><i class="fa fa-plus"></i>%s</a>';
            if ($orderType == \app\admin\model\Project::TYPE_PROJECT) {
                $btnId   = 'btn-createprojectorder';
                $btnText = __('create project order');
            } elseif ($orderType == \app\admin\model\Project::TYPE_MEDICINE) {
                $newOrderBtn .= sprintf($btnFormat, 'btn-createrecipeorder', __('create recipe order'));
                if ($this->auth->check('cash/order/createprojectorder')) {
                    $newOrderBtn .= '  ' . sprintf($btnFormat, 'btn-createprojectorder', __('create project order'));
                }
            } elseif ($orderType == \app\admin\model\Project::TYPE_PRODUCT) {
                $btnId   = 'btn-createproductorder';
                $btnText = __('create product order');
            }

            if (!empty($btnId)) {
                $newOrderBtn .= sprintf($btnFormat, $btnId, $btnText);
            }
        }

        $orderTypeList = Project::getTypeList();

        $this->view->assign('orderTypeList', $orderTypeList);
        $this->view->assign('orderStatusList', $orderStatusList);
        $this->view->assign('orderStatus', $orderStatus);
        $this->view->assign('orderType', $orderType);
        $this->view->assign('newOrderBtn', $newOrderBtn);

        return $this->view->fetch('cash/order/index');
    }

    /**
     * 职员快速搜索
     */
    public function staffquicksearch($userName = '')
    {
        // return json(Admin::getAdminByName($userName, 2));
        $pageCount                                   = \think\Config::get('ajax_list_page_count');
        list($where, $sort, $order, $offset, $limit) = $this->buildparams();

        $sort  = 'id';
        $order = 'DESC';
        $limit = $pageCount;

        $total = model('admin')
            ->where($where)
            ->order($sort, $order)
            ->count();

        $list = model('admin')
            ->where($where)
            ->field('id,nickname')
            ->order($sort, $order)
            ->limit($offset, $limit)
            ->select();

        $result = array("total" => $total, "rows" => $list);

        return json($result);
    }

    /**
     * 私有 通用 开单
     * , $osc_id = 0, $oscAdminid = 0,
     */
    protected function createorder($orderType = '', $admin = null)
    {
        if ($this->request->isPost()) {
            $osconsultId = input('osconsult_id', false);
            $params      = $this->request->post("row/a");
            $itemsParams = $this->request->post("itemParams/a", []);
            //超出权限 调价申请信息
            $doApply     = input('permissionRequest', false);
            $applyInfo   = input('applyInfo', '');
            $applyDetail = ['doApply' => $doApply, 'applyInfo' => $applyInfo];

            if ($params && $params['customer_id']) {
                //顾客信息
                $customer = model('Customer')->find($params['customer_id']);
                if (empty($customer)) {
                    $this->error(__('Customer %s does not exist.', $params['customer_id']));
                }

                //计提时以营销人员为准
                $consultAdminId = $customer->admin_id;
                $receptAdminId  = 0;

                //现场客服处理
                //是否应该更改相应客服状态， 有客服且客服未关联订单时需更改状态
                $shouldChangeOsconsult = false;

                if (empty($admin)) {
                    $admin = Session::get('admin');
                }
                //默认开单现场为 自身
                $adminId = $admin['id'];
                //订单 默认 关联最近一次现场记录
                $params['osconsult_id'] = $osconsultId ? $osconsultId : $customer->ctm_last_osc_id;

                if ($params['osconsult_id']) {
                    //未取OSC.ADMIN_ID 开单时以录入人为准，实际客服开单时应是现场客服本人
                    $osconsult = model('CustomerOsconsult')
                        ->where([
                            'osc_id' => $params['osconsult_id'],
                            // 'createtime' => [
                            //     'BETWEEN',
                            //     [
                            //         strtotime(date('Y-m-d')),
                            //         strtotime(date('Y-m-d 23:59:59')),
                            //     ],
                            // ],
                        ])
                        ->find();
                    if ($osconsult != null) {
                        //分诊人员
                        $receptAdminId = $osconsult->operator;
                        $adminId       = $osconsult->admin_id;

                        $orderDeveloperMode = \think\Config::get('site.developer_mode');
                        if (!empty($orderDeveloperMode) && $orderDeveloperMode == 2) {
                            if (!empty($osconsult->consult_id)) {
                                $preOrderConsult = model('CustomerConsult')->find($osconsult->consult_id);
                                if ($preOrderConsult) {
                                    $consultAdminId = $preOrderConsult->admin_id;
                                }
                            }
                        }
                    }
                }

                $params['consult_admin_id'] = $consultAdminId;
                $params['recept_admin_id']  = $receptAdminId;
                $prescriber                 = $admin['id'];

                $saveOrderRes = OrderItems::createOrder($params, $itemsParams, $applyDetail, $adminId, $prescriber, $admin);
                $msg          = '';

                if ($saveOrderRes['error']) {
                    $this->error($saveOrderRes['msg']);
                } else {
                    //开单改变现在客服状态
                    if (!empty($osconsult)) {
                        if ($osconsult->osc_status == CustomerOsconsult::STATUS_CONSULTING || $osconsult->osc_status == CustomerOsconsult::STATUS_FAIL) {
                            $osconsult->osc_status = CustomerOsconsult::STATUS_FAIL;
                            $osconsult->fat_id     = 0;

                            if ($osconsult->save()) {
                                $msg = __('Related Osconsult has been updated.');
                            }
                        }
                    }
                    $this->success($msg);
                }
            }

            $this->error(__('Parameter %s can not be empty', ''));
        }

        $customerId = input('customer_id', false);
        if (empty($customerId)) {
            $this->error(__('Customer %s does not exist.', $customerId));
        }
        $customer = model('Customer')->field('ctm_id, ctm_name, ctm_last_osc_id')->where(['ctm_id' => $customerId])->find();
        if (empty($customer)) {
            $this->error(__('Customer %s does not exist.', $customerId));
        }

        //开物资单osc_id为客户在现场中的最后一条现场记录
        $osconsultId = input('osc_id', $customer->ctm_last_osc_id);

        //渲染开单按钮
        $this->renderCreateOrder($orderType);

        $this->view->assign('customer', $customer);
        $this->view->assign('discountLimit', $this->view->admin->getDiscountLimit());
        $this->view->assign('osconsultId', $osconsultId);
        // $this->view->assign('consultAdminId', $consultAdminId);
        // $this->view->assign('receptAdminId', $receptAdminId);
        return $this->view->fetch('cash/order/createorder');
    }

    private function renderCreateOrder($orderType = '')
    {
        $preAddBtns = [
            '<a id="btn-add-project-set" href="javascript:;" class="btn btn-primary">' . __('Add project set') . '</a>',
            '<a id="btn-add-medicine-set" href="javascript:;" class="btn btn-primary">' . __('Add medicine set') . '</a>',
            '<a id="btn-add-product-set" href="javascript:;" class="btn btn-primary">' . __('Add product set') . '</a>',
        ];
        $addBtns    = [];
        $fieldsHtml = [];

        if ($orderType == '') {
            $addBtns = $preAddBtns;
        } else {
            switch ($orderType) {
                //项目
                case \app\admin\model\Project::TYPE_PROJECT:
                    array_push($addBtns, $preAddBtns[0]);
                    array_push($fieldsHtml, '<input type="hidden" id="project_set_id" class="h-set-id" /><input type="hidden" id="project_set_name" class="h-set-name" />');
                    break;
                //recipe, medicine 药品
                case \app\admin\model\Project::TYPE_MEDICINE:
                    array_push($addBtns, $preAddBtns[1]);
                    array_push($fieldsHtml, '<input type="hidden" id="product_1_set_id" class="h-set-id" /><input type="hidden" id="product_1_set_name" class="h-set-name" />');
                    break;
                //物资
                case \app\admin\model\Project::TYPE_PRODUCT:
                    array_push($addBtns, $preAddBtns[2]);
                    array_push($fieldsHtml, '<input type="hidden" id="product_2_set_id" class="h-set-id" /><input type="hidden" id="product_2_set_name" class="h-set-name" />');
                    break;
                default:
                    break;
            }
        }

        //划扣科室获取
        $deptListCache = \app\admin\model\Deptment::getDeptListCache();
        $this->view->assign('deptListCache', $deptListCache);
        $this->view->assign('orderType', $orderType);
        $this->view->assign('addBtns', $addBtns);
        $this->view->assign('fieldsHtml', $fieldsHtml);
    }

    public function deductview($ids = null)
    {
        $row = model('customer')->find($ids);
        if (empty($row)) {
            $this->error(__('Customer %s does not exist.', $ids));
        }

        $this->view->assign('row', $row);

        $genderList = Gender::getList();
        $this->view->assign('genderList', $genderList);

        $ctmSource  = model('Ctmsource')->field('sce_id, sce_name')->where(['sce_status' => 1])->order('sce_sort', 'desc')->select();
        $ctmSrcList = ['' => __('NONE')];
        foreach ($ctmSource as $source) {
            $ctmSrcList[$source['sce_id']] = $source['sce_name'];
        }
        $this->view->assign('ctmSrcList', $ctmSrcList);
        //营销渠道
        $channelLists = model('Ctmchannels')->field('chn_id, chn_name')->where(['chn_status' => 1])->order('chn_sort', 'desc')->select();
        $channelList  = ['' => __('NONE')];
        foreach ($channelLists as $source) {
            $channelList[$source['chn_id']] = $source['chn_name'];
        }
        $this->view->assign('channelList', $channelList);

        $this->view->assign('jobList', Job::getList());

        //项目类型
        $pducats  = model('CProject')->field('id, cpdt_name')->where(['cpdt_status' => 1])->order('id', 'ASC')->select();
        $cpdtList = ['' => __('NONE')];
        foreach ($pducats as $pducat) {
            $cpdtList[$pducat['id']] = $pducat['cpdt_name'];
        }
        $this->view->assign('cpdtList', $cpdtList);

        //推荐人
        // $rec_id =$row['rec_customer_id'];
        $recCustomer     = model('customer')->field('ctm_name,ctm_id')->where(['ctm_id' => $row['rec_customer_id']])->find();
        $recCustomerName = __('None');
        if (!empty($recCustomer)) {
            $recCustomerName = $recCustomer->ctm_id.'--'.$recCustomer->ctm_name;
        }
        //营销人员
        $developStaffName = __('None');

        $briefAdminList = model('Admin')->getBriefAdminList();
        if (isset($briefAdminList[$row['admin_id']])) {
            $developStaffName = $briefAdminList[$row['admin_id']];
        }

        $canReassignDev = $this->auth->check('customer/customer/reassigndev');

        $definedRvPlans = model('Rvplan')->where(['rvp_status' => 1])->column('rvp_name', 'rvp_id');
        $this->view->assign('definedRvPlans', $definedRvPlans);

        $this->view->assign("recCustomerName", $recCustomerName);
        $this->view->assign("developStaffName", $developStaffName);
        $this->view->assign('canReassignDev', $canReassignDev);

        //顾客图片
        $customerImgs = model('CustomerImg')->where(['customer_id' => $row->ctm_id])->order('weigh', 'DESC')->select();
        $this->view->assign('customerImgs', $customerImgs);

        //是否有过首次受理工具审批申请
        $bool = model('FirstToolId')->where(['customer_id' => $ids])->select();
        $this->view->assign('bool', $bool);

        return $this->view->fetch();
    }

    //显示该订单的所有详细信息
    public function orderitemdetail($ids = null)
    {
        return $this->view->fetch();
    }

    public function changedeveloper($orderStatus = null, $orderType = null, $useAdminFilter = false, $hasSub = false)
    {
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {

            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name')) {
                return $this->selectpage();
            }

            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $extraWhere = array();
            if ($orderStatus !== null) {
                if (is_array($orderStatus)) {
                    $extraWhere['item_status'] = ['in', $orderStatus];
                } else {
                    $extraWhere['item_status'] = $orderStatus;
                }
            }
            if ($orderType !== null) {
                if (is_array($orderType)) {
                    $extraWhere['item_type'] = ['in', $orderType];
                } else {
                    $extraWhere['item_type'] = $orderType;
                }
            }

            if (($customerId = input('customer_id', false))) {
                $extraWhere['customer_id'] = $customerId;
                $this->view->assign('customer_id', $customerId);
            }

            $total = OrderItems::getListCount($where, $extraWhere);
            $list  = OrderItems::getList($where, $sort, $order, $offset, $limit, $extraWhere);

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }

        $orderStatusList = ['' => __('All')];
        foreach (\app\admin\model\OrderItems::getStatusList() as $key => $value) {
            $orderStatusList[$key] = $value;
        }

        $newOrderBtn = '';
        if (!empty($orderType)) {
            $btnFormat = '<a href="javascript:;" class="btn btn-success" id="%s"><i class="fa fa-plus"></i>%s</a>';
            if ($orderType == \app\admin\model\Project::TYPE_PROJECT) {
                $btnId   = 'btn-createprojectorder';
                $btnText = __('create project order');
            } elseif ($orderType == \app\admin\model\Project::TYPE_MEDICINE) {
                $btnId   = 'btn-createrecipeorder';
                $btnText = __('create recipe order');
            } elseif ($orderType == \app\admin\model\Project::TYPE_PRODUCT) {
                $btnId   = 'btn-createproductorder';
                $btnText = __('create product order');
            }

            if (!empty($btnId)) {
                $newOrderBtn = sprintf($btnFormat, $btnId, $btnText);
            }
        }

        $orderTypeList = Project::getTypeList();

        $this->view->assign('orderTypeList', $orderTypeList);
        $this->view->assign('orderStatusList', $orderStatusList);
        $this->view->assign('orderStatus', $orderStatus);
        $this->view->assign('orderType', $orderType);
        $this->view->assign('newOrderBtn', $newOrderBtn);

        return $this->view->fetch();
    }

    /**
     * 批量修改 开单营销人员
     */
    public function changeadmin()
    {
        $customerId     = input('customerId', false);
        $ids            = input('ids', false);
        $briefAdminList = model('Admin')->getBriefAdminList2();
        $customers      = model('customer')->where(['ctm_id' => ['in', $customerId]])->column('ctm_name', 'ctm_id');
        $consultAdmin   = model('orderItems')->alias('orderItems')
            ->join(DB::getTable('Admin') . ' admin', 'orderItems.consult_admin_id = admin.id', 'LEFT')
            ->where(['orderItems.item_id' => ['in', $ids]])
            ->column('admin.nickname', 'orderItems.item_id');

        $this->view->assign("briefAdminList", $briefAdminList);
        $this->assign('ids', $ids);
        $this->assign('consultAdmin', $consultAdmin);
        $this->assign('customers', $customers);

        if ($this->request->isPost()) {

            $AdminId = input('adminid', false);
            $id      = input('id', false);
            $itemId  = explode(',', $id);

            //生成记录
            // $itemchange = new \app\admin\model\Itemchange;

            $itemList = \app\admin\model\OrderItems::where('item_id', 'in', $itemId)->select();

            $memoFormat = '类型： %s  %s 修改为 %s';
            Db::startTrans();
            try {
                foreach ($itemList as $key => $item) {
                    $oldConsultAdminId = $item->consult_admin_id;

                    $item->consult_admin_id = $AdminId;

                    $oldConsultAdminname = isset($briefAdminList[$oldConsultAdminId]) ? $briefAdminList[$oldConsultAdminId] : '自然到诊';
                    $newConsultAdminname = isset($briefAdminList[$item->consult_admin_id]) ? $briefAdminList[$item->consult_admin_id] : '自然到诊';

                    if ($item->save() !== false) {
                        $itemChange                         = new \app\admin\model\Itemchange;
                        $itemChange->item_id                = $item->item_id;
                        $itemChange->item_consult_old_admin = $oldConsultAdminId;
                        $itemChange->item_consult_new_admin = $item->consult_admin_id;
                        $itemChange->admin_id               = Session::get('admin')->id;
                        $itemChange->remark                 = sprintf($memoFormat, '网络客服', $oldConsultAdminId . $oldConsultAdminname, $item->consult_admin_id . $newConsultAdminname);
                        $itemChange->createtime             = time();
                        if ($itemChange->save() == false) {
                            throw new \yjy\exception\TranException("出错", 1);
                        }
                    } else {
                        throw new \yjy\exception\TranException("出错", 1);
                    }
                }

                Db::commit();
                $this->success();
            } catch (\yjy\exception\TranException $e) {
                Db::rollback();
                $this->error();
            } catch (\think\PDOException $e) {
                Db::rollback();
                $this->error();
            }

            // foreach ($itemId as $key => $value) {
            //     $itemList = $this->model->where(['item_id' => $value])->find();
            //     $itemchange->item_id = $value;
            //     $itemchange->item_consult_old_admin = $itemList->consult_admin_id;
            //     $itemchange->item_consult_new_admin = $AdminId;
            //     $itemchange->admin_id = Session::get('admin')->id;
            //     $itemchange->createtime = time();
            //     $itemchange->save();

            //     // rollback
            //     // commit();
            // }

            // if ($AdminId) {
            //     //修改营销人员
            //     $this->model->save(
            //         [
            //             'consult_admin_id' => $AdminId,
            //         ],
            //         ['item_id' => ['in', $itemId]]
            //     );

            // $this->success();
            // } else {
            //     $this->error(__('Invalid parameters'));
            // }
        }

        return $this->view->fetch();

    }

    public function itemremark($ids = '')
    {
        $orderItem = model('OrderItems')->find($ids);
        $customer = model('Customer')->find($orderItem->customer_id);
        if (empty($customer)) {
            $this->error(__('Customer %s does not exist.', @intval($orderItem->customer_id)));
        }
        $this->view->assign('orderItem', $orderItem);
        $this->view->assign('customer', $customer);

        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            $result = $orderItem->save($params);
            if ($result !== false) {
                $this->success();
            } else {
                $this->error();
            }
            
        }
        return $this->view->fetch();
    }


   

}
