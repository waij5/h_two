<?php

namespace app\admin\controller\cash;

use app\admin\model\AccountLog;
use app\admin\model\BalanceType;
use app\admin\model\Customer;
use app\admin\model\CustomerBalance;
use app\admin\model\DeductRecords;
use app\admin\model\OrderItems;
use app\admin\model\PayType;
use app\admin\model\Project;
use app\common\controller\Backend;
use think\Controller;
use think\Db;
use think\Request;
use yjy\exception\TransException;
use app\admin\model\Osctype;

/**
 * 收银管理
 *
 * @icon fa fa-circle-o
 */
class Balance extends Backend
{
    protected $noNeedLogin = ['generatereceipt2', 'generateinvoice'];

    /**
     * CustomerBalance模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('CustomerBalance');

        $this->extraInit();
    }

    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个方法
     * 因此在当前控制器中可不用编写增删改查的代码,如果需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        $startDate = input('balance.createtime_start', strtotime(date('Y-m-d')));
        $endDate   = input('balance.createtime_end', strtotime(date('Y-m-d 23:59:59')));
        
        if ($this->request->isAjax()) {

            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name')) {
                return $this->selectpage();
            }
            list($bWhere, $sort, $order, $offset, $limit, $extraWhere) = $this->dealParams($startDate, $endDate);

            $list = CustomerBalance::getList2($bWhere, $sort, $order, $offset, $limit, $extraWhere);

            if ($offset) {
                $total  = CustomerBalance::getListCount2($bWhere, $extraWhere);
                $result = array("total" => $total, "rows" => $list);
            } else {
                $summary = CustomerBalance::getListCount2($bWhere, $extraWhere, true);

                $result = array("total" => $summary['count'], "rows" => $list, 'summary' => $summary);
            }

            return json($result);
        }

        $this->customerExtraInit();
        $start     = date('Y-m-d', $startDate);
        $end       = date('Y-m-d', $startDate);
        $this->view->assign("startDate", $start);
        $this->view->assign("endDate", $end);
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
        $toolList = \app\admin\model\CocAcceptTool::getList();
        $this->view->assign('toolList', $toolList);

        return $this->view->fetch();
    }

    /**
     * 今日统计
     */
    public function todaysummary()
    {

        $todayDate = date('Y-m-d');
        $startTime = strtotime($todayDate);
        $endTime   = strtotime(date('Y-m-d 23:59:59'));
        $summary   = CustomerBalance::getListCount2(['balance.createtime' => ['BETWEEN', [$startTime, $endTime]]], [], true);

        $feeSummary = OrderItems::generateDailyFeeSummary($startTime, $endTime);
        $feeTypeList = \app\admin\model\Fee::getList();

        $this->assign('todayDate', $todayDate);
        $this->assign('summary', $summary);
        $this->assign('feeSummary', $feeSummary);
        $this->assign('feeTypeList', $feeTypeList);
        $this->assign('title', '项目汇总');

        return $this->view->fetch('cash/balance/todaysummary');
    }

    public function dailyreport()
    {
        $selectedDate      = input('selectedDate', date('Y-m-d'));
        $startTime         = strtotime($selectedDate);
        $endTime           = strtotime($selectedDate . ' 23:59:59');
        $where             = ['osc.createtime' => ['BETWEEN', [$startTime, $endTime]]];
        $dailyOscStatistic = \app\admin\model\CustomerOsconsult::getSuccessStatistic($where);
        $this->view->assign('dailyOscStatistic', $dailyOscStatistic);

        //consult
        $cstSummary = \app\admin\model\ConsultStat::getListForProject(['cst.createtime' => ['BETWEEN', [$startTime, $endTime]]]);
        $cstListCnt = count($cstSummary);
        $cstPadCols = ceil($cstListCnt / 5) * 5 - $cstListCnt;
        $this->view->assign('cstPadCols', $cstPadCols);
        $this->view->assign('cstSummary', $cstSummary);

        $cProList    = \app\admin\model\CProject::where('cpdt_status', 1)->column('cpdt_name', 'id');
        // $cProListCnt = count($cProList);
        // $cProPadCols = ceil($cProListCnt / 5) * 5 - $cProListCnt;
        //osc
        //consult
        $oscCProCnt = \app\admin\model\CustomerOsconsult::where('is_delete', 0)
            ->where('createtime', 'BETWEEN', [$startTime, $endTime])
            ->group('cpdt_id')
            ->having('count(*) > 0')
            ->order('cpdt_id', 'asc')
            ->count();
        $cProPadCols = ceil($oscCProCnt / 5) * 5 - $oscCProCnt;
        $oscCProSummary = \app\admin\model\CustomerOsconsult::where('is_delete', 0)
            ->where('createtime', 'BETWEEN', [$startTime, $endTime])
            ->group('cpdt_id')
            ->having('count(*) > 0')
            ->order('cpdt_id', 'asc')
            ->column('count(*) as cnt', 'cpdt_id');
        $this->view->assign('cProList', $cProList);
        $this->view->assign('cProPadCols', $cProPadCols);
        $this->view->assign('oscCProSummary', $oscCProSummary);

        //deduct dept statistics
        // app\admin\model\Report::getOrderItemsDetailCntNdSummary2([], [], true);
        //科室统计， 到诊， 业绩【订购初始营收 以付款时间为准】
        $deductDepts = model('deptment')->where('dept_type', 'deduct')
                        ->where('dept_status', 1)
                        ->order('dept_status desc, dept_id', 'asc')
                        ->column('dept_name', 'dept_id');
        $deptOscSummary = \app\admin\model\CustomerOsconsult::where('is_delete', 0)
            ->where('createtime', 'BETWEEN', [$startTime, $endTime])
            ->group('dept_id')
            ->column('count(*) as cnt', 'dept_id');
        $deptSummary = \app\admin\model\OrderItems::where([
                            'item_paytime' => ['BETWEEN', [$startTime, $endTime]],
                        ])
                        ->group('dept_id')
                        ->column('sum(item_original_pay_total) as item_original_pay_total', 'dept_id');
        $this->view->assign(compact('deductDepts', 'deptOscSummary', 'deptSummary'));

        //balance
        $dailyBalanceSummary = \app\admin\model\CustomerBalance::getListCount2([
            'balance.create_date' => $selectedDate]
            , [], true);
        $monthStartDate = date(substr($selectedDate, 0, 7) . '-01');
        $monthEndDate   = date(substr($selectedDate, 0, 7) . '-t');

        $monthlyBalanceSummary = \app\admin\model\CustomerBalance::getListCount2(['balance.create_date' => ['BETWEEN', [$monthStartDate, $monthEndDate]]], [], true);
        $this->view->assign('dailyBalanceSummary', $dailyBalanceSummary);
        $this->view->assign('monthlyBalanceSummary', $monthlyBalanceSummary);

        $this->view->assign('selectedDate', $selectedDate);

        return $this->view->fetch();
    }

    /**
     * 获取进度信息
     */
    public function downloadprocess()
    {
        $startDate = input('coc.createtime_start', strtotime(date('Y-m-d')));
        $endDate   = input('coc.createtime_end', strtotime(date('Y-m-d 23:59:59')));
        if (isset($_GET['op']) && $_GET['op'] == '') {
            unset($_GET['op']);
        }
        list($bWhere, $sort, $order, $offset, $limit, $extraWhere) = $this->dealParams($startDate, $endDate);
        // return $this->commondownloadprocess('balancereport', 'Balance detail');

        \think\Request::instance()->get(['filter' => '']);

        return $this->commondownloadprocess('balancereport', 'Balance detail', $bWhere, $extraWhere);
    }

    /**
     * 预存定金
     */
    public function prestore()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                try
                {
                    $customer = model('Customer')->where(['ctm_id' => $params['customer_id']])->find();
                    if (empty($customer->ctm_id)) {
                        $this->error(__('Customer %s does not exist.', $params['customer_id']));
                    }

                    $this->dealPayParams($this->payTypeList, $params);

                    $params['total'] = $params['pay_total'];

                    Db::startTrans();
                    $this->dealOsc($customer, $params);
                    $result = $this->model->saveBalance($params);
                    if ($result['error'] == false) {
                        if ($customer->changeDepositNdCoupon($params['total'], $params['coupon_amt'], AccountLog::TYPE_PRESTORE) === false) {
                            throw new TransException('操作失败，请联系管理员');
                        }
                        // $customer->changeDepositAmt($params['total'], $params['coupon_amt'], AccountLog::TYPE_PRESTORE);
                        Db::commit();
                        $this->success();
                    } else {
                        Db::rollback();
                        $this->error($result['msg']);
                    }
                } catch (TransException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (\think\exception\PDOException $e) {
                    Db::rollback();
                    
                    $this->error('操作失败，请联系管理员');
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }

        $customerId = input('customer_id');
        $customer   = model('Customer')->find(['ctm_id' => $customerId]);
        if (empty($customer->ctm_id)) {
            $this->error(__('Customer %s does not exist.', $customerId));
        }
        $this->view->assign('customer', $customer);
        $this->view->assign('balanceType', BalanceType::TYPE_PRESTORE);

        return $this->view->fetch();
    }

    /**
     * 退优惠券
     */
    public function returncoupon()
    {
        $this->error('已弃用');
        $balanceId = intval(input('balance_id'));
        $customerId = intval(input('customer_id'));

        $customer   = model('Customer')->get($customerId);
        if (empty($customer)) {
            $this->error('Customer %s does not exist.', $customerId);
        }

        $CouponRecords = model('CouponRecords')->where(['balance_id' => $balanceId])->find();
        if($CouponRecords->status == 1){
            $this->error('优惠券已退');
        }
        // $used_balance_id = model('CouponRecords')->where(['balance_id' => $balanceId])->column('used_balance_id','id');
        if($CouponRecords){
            if ($CouponRecords->used_balance_id != 0) {
                $this->error('优惠券已使用!');
            }
        }else{
            $this->error('优惠券不存在');
        }

        $balance = $this->model->where(['balance_id' => $balanceId])->find();

        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");

            //判断是退定金还是退款(退定金0走客户资金调整,退款1走收银)
            if($params['return_type'] == 0){
                 $customer   = model('Customer')->find(['ctm_id' => $customerId]);//model
                 $customer->log_account_change($balance->total, 0, 0, 0, 0, time(), \app\admin\model\AccountLog::TYPE_ADJUST, $params['balance_remark'], $ip = 'SYSTEM', 'HIS', 0);
                $CouponRecords->status = 1;
                $CouponRecords->return_type = $params['return_type'];
                $CouponRecords->save();
            $this->success();
            }else{
                //退款balance_type
                $params['balance_type'] = BalanceType::TYPE_RETURN_COUPON;
                //退款记录            
                if ($params) {
                    try
                    {
                        Db::startTrans();
                        $customer = model('Customer')->where(['ctm_id' => @$params['customer_id']])->find();
                        if (empty($customer->ctm_id)) {
                            $this->error(__('Customer %s does not exist.', $params['customer_id']));
                        }

                        $this->dealPayParams($this->payTypeList, $params);
                        $params['total'] = $params['pay_total'];

                        if (abs($params['total']) != $balance->total) {
                            $this->error('退款金额必须和购券额相等');
                        }
                        $CouponRecords->status = 1;
                        $CouponRecords->return_type = $params['return_type'];
                        $CouponRecords->save();

                        $result = $this->model->saveBalance($params);
                        if ($result['error'] == false) {
                            // if ($customer->changeDepositAmt($params['total'], AccountLog::TYPE_PRESTORE_CHARGEBACK) === false) {
                            //     throw new TransException(__('Failed when updating customer deposit info, all operation have been rolled back!'));
                            // }

                            Db::commit();
                            $this->success();
                        } else {
                            throw new TransException($result['msg']);
                        }
                    } catch (\think\exception\PDOException $e) {
                        Db::rollback();
                        $this->error($e->getMessage());
                    } catch (TransException $e) {
                        Db::rollback();
                        $this->error($e->getMessage());
                    }
                }
                $this->error(__('Parameter %s can not be empty', ''));
            }
        }
         
        $this->assign('balance', $balance);
        $this->assign('customer', $customer);
        return $this->view->fetch();
    }

    /**
     * 购买优惠券
     */
    public function buycoupon()
    {
        //购券
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                try
                {
                    $customer = model('Customer')->where(['ctm_id' => $params['customer_id']])->find();
                    if (empty($customer->ctm_id)) {
                        $this->error(__('Customer %s does not exist.', $params['customer_id']));
                    }

                    $this->dealPayParams($this->payTypeList, $params);
                    //deposit_total pay_total
                    if ($params['coupon_amt'] < 0 || $params['pay_total'] < 0 || $params['deposit_total'] < 0 || $params['pay_points'] < 0 || $params['affiliate_amt'] < 0) {
                        $this->error(__('Invalid amount'));
                    }
                    //检查定金余额
                    if ($customer->ctm_depositamt < $params['deposit_total']) {
                        $this->error(__('Not enough deposit'));
                    }
                    //检查消费积分
                    if ($customer->ctm_pay_points < $params['pay_points']) {
                        $this->error(__('Not enough pay_points'));
                    }
                    //检查佣金
                    if ($customer->ctm_affiliate < $params['affiliate_amt']) {
                        $this->error(__('Not enough affiliate_amt'));
                    }

                    bcscale(2);
                    $params['total'] = bcadd($params['deposit_total'], $params['pay_total']);

                    //开始事务
                    Db::startTrans();
                    $this->dealOsc($customer, $params);
                    $result = $this->model->saveBalance($params);
                    if ($result['error'] == false) {
                        //更新顾客消费金额
                        $customer->updateSalAmt($params['total']);

                        // if ($customer->changeDepositNdCoupon(-$params['deposit_total'], $params['coupon_amt'], AccountLog::TYPE_COUPON) == false) {
                        if (Customer::logAccountChange($customer->ctm_id, - $params['deposit_total'], 0, 0, - $params['pay_points'], -$params['affiliate_amt'], $params['coupon_amt'], $changeTime = '', AccountLog::TYPE_COUPON, $changeDesc = '购买优惠券', $ip = 'SYSTEM', $source = 'HIS', $syncTime = 0) == false) {
                            Db::rollback();
                            $this->error(__('Failed when change depositamt, all operation has been undone.'));
                        }
                        Db::commit();
                        $this->success();
                    } else {
                        $this->error($result['msg']);
                    }
                } catch (\think\exception\PDOException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }

        $customerId = intval(input('customer_id'));
        $customer   = model('Customer')->get($customerId);
        if (empty($customer)) {
            return __('Customer %s does not exist.', $customerId);
        }

        $this->assign('customer', $customer);
        $this->assign('balanceType', BalanceType::TYPE_COUPON);

        return $this->view->fetch();
    }

    /**
     * 订单收款
     * @param int $customerId 顾客ID
     * @param int $ids 订单项ID
     */
    public function payorder($customerId, $ids = '')
    {
        $ids = explode(',', $ids);
        //order check
        $customer = model('Customer')->get($customerId);
        if (empty($customer)) {
            $this->error(__('Customer %s does not exist.', $customerId));
        }
        $order = OrderItems::alias('order_items')
            ->join(Db::getTable('project') . ' project', 'order_items.pro_id = project.pro_id', 'LEFT')
            ->where(['customer_id' => $customerId, 'item_status' => OrderItems::STATUS_PENDING, 'item_id' => ['in', $ids]])
            ->field('customer_id, SUM(item_local_total) AS local_total, SUM(item_ori_total) AS ori_total, SUM(item_total) AS total, SUM(item_discount_total) AS discount_total')
            ->find();
        if ($order->ori_total > 0) {
            $order->discount_percent = floor(10000 * $order->total / $order->ori_total) / 100;
        } else {
            $order->discount_percent = 100.00;
        }

        //支付
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $orderItems = OrderItems::where(['customer_id' => $customerId, 'item_status' => OrderItems::STATUS_PENDING, 'item_id' => ['in', $ids]])
                    ->order('item_id', 'ASC')
                    ->select();
                if (($orderItemsCount = count($orderItems)) == 0) {
                    $this->error(__('No results were found'));
                }

                //basic data check
                $this->dealPayParams($this->payTypeList, $params);
                if ($params['pay_total'] < 0 || $params['deposit_total'] < 0) {
                    $this->error(__('Invalid amount'));
                }

                $params['total'] = $order->total;

                //coupon check INNER JOIN
                //优惠券存在，属于此用户，未使用，未过期
                $params['coupon_cost']  = 0;
                // $params['coupon_total'] = 0;
                if ($params['coupon_total'] > 0) {
                    if ($params['coupon_total'] > $customer->ctm_coupamt) {
                        $this->error(__('Not enough coupon'));
                    }
                } else {
                    //负的 不会加价
                }
                // if ($params['coupon_record_id']) {
                //     $subQuery    = model('CouponRecords')->where(['customer_id' => $order->customer_id, 'id' => $params['coupon_record_id'], 'used_balance_id' => 0])->buildSql();
                //     $couponInfos = $this->model->table($subQuery)->alias('coupon_records')
                //         ->field('coupon_records.*, pay_amount, amount, expiration')
                //         ->join(Db::getTable('coupon') . ' coupon', ' coupon_records.coupon_id = coupon.id')
                //         ->where(['coupon.expiration' => 0])
                //         ->whereOr(['coupon.expiration' => ['gt', time()]])
                //         ->order('coupon_records.id', 'ASC')
                //         ->limit(0, 1)
                //         ->select();
                //     if (empty($couponInfos)) {
                //         $this->error(__('Invalid coupon(not exists, not belong to this customer, used, expirated)'));
                //     }
                //     $coupon                 = $couponInfos[0];
                //     $params['coupon_cost']  = $coupon->pay_amount;
                //     $params['coupon_total'] = $coupon->amount;
                // }
                //金额检验: 总金额 = 优惠券券面额 + 定金 + 支付金额
                // if ($params['total'] != ($params['pay_total'] + $params['deposit_total'] + $params['coupon_total'])) {
                //     $this->error(__('Invalid amount'));
                // }
                if (bccomp($params['total'], $params['pay_total'] + $params['deposit_total'] + $params['coupon_total'], 2) != 0) {
                    $this->error(__('Invalid amount'));
                }

                try {
                    //开始事务 保存流水，划扣优惠券，修改顾客定金，更改订单状态
                    Db::startTrans();
                    //取首个项目 对应 现场客服ID
                    $firstItem = current($orderItems);
                    $this->dealOsc($customer, $params, $firstItem->osconsult_id);

                    $result = $this->model->saveBalance($params);
                    if ($result['error'] == false) {
                        if ($customer->changeDepositNdCoupon(-$params['deposit_total'], -$params['coupon_total'], AccountLog::TYPE_PAY_ORDER, $balanceDate = null, '付款', $ids)) {
                        // if ($customer->changeDepositAmt(-$params['deposit_total'], AccountLog::TYPE_PAY_ORDER, $balanceDate = null, '定金付款', $ids)) {
                            //使用优惠券
                            // if (isset($coupon)) {
                            //     $couponRecordResult = model('CouponRecords')->where('id', $coupon->id)->update(['used_balance_id' => $this->model->balance_id]);
                            //     if ($couponRecordResult == false) {
                            //         throw new TransException(__('Failed when save coupon data, all operation has been undone.'));
                            //     }
                            // }

                            $payTotal = $order->total - $params['coupon_total'];

                            //bcmatch 高精度计算函数 需要bcmatch 扩展
                            if ($order->total > 0) {
                                $payTotalPerY = bcdiv($payTotal, $order->total, 6);
                            } else {
                                $payTotalPerY = 0;
                            }

                            $lastPayTotal   = $payTotal;
                            $osconsultIdArr = array();
                            $orderItemIdArr = array();
                            // orderItemsCount
                            foreach ($orderItems as $key => &$orderItem) {
                                array_push($osconsultIdArr, $orderItem->osconsult_id);
                                array_push($orderItemIdArr, $orderItem->item_id);

                                $orderItem->balance_id = $this->model->balance_id;
                                // $orderItem->item_status = OrderItems::STATUS_PAYED;
                                $orderItem->item_paytime = $this->model->createtime;

                                if ($orderItemsCount == ($key + 1)) {
                                    $itemPayTotal = $lastPayTotal;
                                } else {
                                    $itemPayTotal = bcmul($payTotalPerY, $orderItem->item_total, 2);
                                    $lastPayTotal -= $itemPayTotal;
                                }

                                $orderItem->item_original_pay_total  = $itemPayTotal;
                                $orderItem->item_pay_total           = $itemPayTotal;
                                $orderItem->item_coupon_total        = $orderItem->item_total - $itemPayTotal;
                                $orderItem->item_pay_amount_per_time = bcdiv($itemPayTotal, $orderItem->item_total_times, 4);

                                //自动划扣
                                if ($orderItem->deduct_switch) {
                                    $orderItem->item_status           = OrderItems::STATUS_COMPLETED;
                                    $orderItem->item_used_times       = $orderItem->item_total_times;
                                    $orderItem->item_undeducted_total = 0.00;
                                    //划扣地点
                                    $orderItem->deduct_addr = $this->view->admin->dept_id;

                                    if ($orderItem->item_type == Project::TYPE_PROJECT) {
                                        $deductStatus = DeductRecords::STATUS_COMPLETED;
                                    } else {
                                        $deductStatus = DeductRecords::STATUS_PENGING;
                                    }

                                    $deductRecord = new deductRecords;
                                    $deductData   = [
                                        'order_item_id'         => $orderItem->item_id,
                                        'deduct_times'          => $orderItem->item_total_times,
                                        'deduct_amount'         => $orderItem->item_pay_total,
                                        'deduct_benefit_amount' => $orderItem->item_pay_total - bcmul($orderItem->item_total_times, @$orderItem->item_cost / $orderItem->pro_use_times * 1.00, 2),
                                        'status'                => $deductStatus,
                                        'admin_id'              => $this->view->admin->id,
                                        'stat_date'             => date('Y-m-d'),
                                    ];
                                    if ($deductRecord->save($deductData) == false) {
                                        throw new TransException(__('Failed when auto deduct, all operation has been undone.'));
                                    }
                                } else {
                                    $orderItem->item_status           = OrderItems::STATUS_PAYED;
                                    $orderItem->item_undeducted_total = $itemPayTotal;
                                }

                                if ($orderItem->save() == false) {
                                    throw new TransException(__('Failed when save order data, all operation has been undone.'));
                                }
                            }
                            //更新客户 消费额
                            $customer->updateSalAmt($payTotal);
                            //提交事务
                            Db::commit();
                            //传入 &订单，收银
                            $osconsultIdArr = array_unique($osconsultIdArr);
                            \think\Hook::listen(OrderItems::TAG_PAY_ORDER, $this->model, ['osconsultIdArr' => $osconsultIdArr, 'customer' => $customer, 'orderItemIdArr' => $orderItemIdArr]);
                            $this->success();
                        } else {
                            Db::rollback();
                            $this->error(__('Failed when change depositamt, all operation has been undone.'));
                        }
                    } else {
                        $this->error($result['msg']);
                    }
                } catch (TransException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (\think\exception\PDOException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
            }
        }

        $orderItems = OrderItems::alias('order_items')
        // ->field('order_items.*, project.pro_name')
            ->join(Db::getTable('project') . ' project', 'order_items.pro_id = project.pro_id', 'LEFT')
            ->where(['customer_id' => $customerId, 'item_status' => OrderItems::STATUS_PENDING, 'item_id' => ['in', $ids]])
            ->order('item_id', 'ASC')
            ->column('order_items.*, project.pro_name', 'item_id');
        if (empty($orderItems)) {
            $this->error(__('No results were found'));
        }
        $projectTypes = \app\admin\model\Project::getTypeList();

        $this->view->assign('order', $order);
        $this->view->assign('customer', $customer);
        $this->view->assign('orderItems', $orderItems);
        $this->view->assign('balanceType', BalanceType::TYPE_PROJECT_PAY);
        $this->view->assign('projectTypes', $projectTypes);
        return $this->view->fetch();
    }

    /**
     * 退款， 定金/项目
     */
    public function refund()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                try
                {
                    Db::startTrans();
                    $customer = model('Customer')->where(['ctm_id' => @$params['customer_id']])->find();
                    if (empty($customer->ctm_id)) {
                        $this->error(__('Customer %s does not exist.', $params['customer_id']));
                    }

                    $this->dealPayParams($this->payTypeList, $params);
                    $params['total'] = $params['pay_total'];

                    if ($customer->ctm_depositamt < 0 || abs($params['total']) > $customer->ctm_depositamt) {
                        $this->error(__('Refund total can not be greater than ctm_depositamt!'));
                    }
                    $this->dealOsc($customer, $params);
                    $result = $this->model->saveBalance($params);
                    if ($result['error'] == false) {
                        if ($customer->changeDepositAmt($params['total'], AccountLog::TYPE_PRESTORE_CHARGEBACK) === false) {
                            throw new TransException(__('Failed when updating customer deposit info, all operation have been rolled back!'));
                        }

                        Db::commit();

                        $this->success();
                    } else {
                        throw new TransException($result['msg']);
                    }
                } catch (\think\exception\PDOException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (TransException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }

        $customerId = input('customer_id');
        $customer   = model('Customer')->find(['ctm_id' => $customerId]);
        if (empty($customer->ctm_id)) {
            $this->error(__('Customer %s does not exist.', $customerId));
        }
        $this->view->assign('customer', $customer);
        $this->view->assign('balanceType', BalanceType::TYPE_PRESTORE_CHARGEBACK);

        return $this->view->fetch();
    }

    /**
     * 业务收入冲减
     */
    public function adjustbalance()
    {
        if ($this->request->isPost()) {
            try
            {
                $params = $this->request->post("row/a");
                $customer = model('Customer')->where(['ctm_id' => @$params['customer_id']])->find();
                if (empty($customer->ctm_id)) {
                    $this->error(__('Customer %s does not exist.', $params['customer_id']));
                }
                $this->dealOsc($customer, $params);
                $this->dealPayParams($this->payTypeList, $params);
                $params['total'] = $params['pay_total'];

                $result = $this->model->saveBalance($params);
                if ($result['error'] == false) {
                    $this->success();
                } else {
                    $this->error($result['msg']);
                }
            } catch (\think\exception\PDOException $e) {
                $this->error($e->getMessage());
            }
        }

        $adjustBalanceTypeList = BalanceType::getAdjustList();
        $this->view->assign('adjustBalanceTypeList', $adjustBalanceTypeList);

        return $this->view->fetch();
    }

    public function edit($ids = null)
    {
        $this->error();
    }

    public function del($ids = null)
    {
        $this->error();
    }

    /**
     * 初始化
     */
    private function extraInit()
    {
        // $customerId = input('customer_id');
        // $customer = model('Customer')->find(['ctm_id' => $customerId]);
        // if (empty($customer->ctm_id)) {
        //     $this->error(__('Customer %s does not exist.', $customerId));
        // }
        $deptList = (new \app\admin\model\Deptment)->getVariousTree();
        $this->view->assign('deptList', $deptList);

        $deptmentList     = model('Deptment')->where(['dept_status' => 1])->column('dept_name', 'dept_id');
        $deptmentList[''] = __('NONE');

        $adminList      = model('Admin')->getAdminCache(\app\admin\model\Admin::ADMIN_BRIEF_CACHE_KEY);
        $adminList['0'] = __('NONE');

        $payTypeList       = PayType::getList();
        $this->payTypeList = $payTypeList;

        $briefAdminList = model('Admin')->getBriefAdminList2();
        $this->view->assign('briefAdminList', $briefAdminList);

        $this->view->assign('payTypeList', $payTypeList);
        $this->view->assign('deptmentList', $deptmentList);
        $balanceTypeList = BalanceType::getList();
        $this->view->assign('balanceTypeList', $balanceTypeList);
        $this->view->assign('adminList', $adminList);

        $refundTypeList = BalanceType::getRefundList();
        $this->view->assign('refundTypeList', $refundTypeList);
    }

    private function dealPayParams($payTypeList, &$params)
    {
        $params['pay_total'] = 0.00;

        if ($params['balance_type'] > 0) {
            foreach ($payTypeList as $key => $payType) {
                if (!isset($params[$payType['name']])) {
                    $params[$payType['name']] = 0.00;
                }

                $params['pay_total'] += $params[$payType['name']];
            }
        } else {
            foreach ($payTypeList as $key => $payType) {
                if (!isset($params[$payType['name']])) {
                    $params[$payType['name']] = 0.00;
                } else {
                    $params[$payType['name']] = -$params[$payType['name']];
                }

                $params['pay_total'] += $params[$payType['name']];
            }
        }
        return $params;
    }

    private function dealOsc(&$customer, &$params, $oscId = 0)
    {
        if ($oscId) {
            $params['osconsult_id'] = $oscId;
        } else {
            $params['osconsult_id'] = $customer->ctm_last_osc_id;
        }
        $params['b_osc_type'] = 5;
        if ($params['osconsult_id']) {
            $ctmLastOsc = model('CustomerOsconsult')->find($customer->ctm_last_osc_id);
            //osc other 其它
            $params['b_osc_type'] = $ctmLastOsc ? $ctmLastOsc->osc_type : 5;
        }
    }

    /**
     * 获取票据
     */
    public function generatereceipt2()
    {
        $offsetX = floatval(\think\Config::get('site.receiptOffsetX'));
        $offsetY = floatval(\think\Config::get('site.receiptOffsetY'));

        $balanceIds = input('balanceIds');
        if (empty($balanceIds)) {
            return 'empty ids';
        } else {
            if (!is_array($balanceIds)) {
                $balanceIds = array($balanceIds);
            }
        }
        $adminId = input('aId', 0);

        $balances = model('CustomerBalance')
            ->alias('balance')
            ->where(['balance_id' => ['in', $balanceIds]])
            ->column('balance.*', 'balance_id');

        if (empty($balances)) {
            $this->error();
        }

        //多个顾客的收银流水无法合并
        $isMultiCustomer = (count(array_unique(array_column($balances, 'customer_id'))) > 1);
        if ($isMultiCustomer) {
            $this->error('多个顾客的收银流水无法合并');
        }

        $receipt_mode = \think\Config::get('site.receipt_mode');
        if ($receipt_mode == 1) {
            $printList = CustomerBalance::generateReceipt1($balances, $balanceIds, $adminId);
        } elseif ($receipt_mode == 2) {
            $printList = CustomerBalance::generateReceipt2($balances, $balanceIds, $adminId);
        } else {
            $printList = CustomerBalance::generateReceipt3($balances, $balanceIds, $adminId);
        }

        return json($printList);
    }

    /**
     * 获取发票
     */
    public function generateinvoice()
    {
        $offsetX = floatval(\think\Config::get('site.invoiceOffsetX'));
        $offsetY = floatval(\think\Config::get('site.invoiceOffsetY'));

        $balanceIds = input('balanceIds');
        if (empty($balanceIds)) {
            return 'empty ids';
        } else {
            if (!is_array($balanceIds)) {
                $balanceIds = array($balanceIds);
            }
        }
        $adminId = input('aId', 1);

        $balances = model('CustomerBalance')
            ->alias('balance')
            ->where(['balance_id' => ['in', $balanceIds]])
            ->column('balance.*', 'balance_id');

        if (empty($balances)) {
            $this->error();
        }

        //多个顾客的收银流水无法合并
        $isMultiCustomer = (count(array_unique(array_column($balances, 'customer_id'))) > 1);
        if ($isMultiCustomer) {
            $this->error('多个顾客的收银流水无法合并');
        }

        //打印初始化
        $printDate = date('y/m/d');
        // $printTime = date('y/m/d H:i');
        $printList       = array();
        $defaultFontSize = 10;
        $cusFontSize1    = 9;

        $customer     = model('Customer')->field('ctm_name')->find(current($balances)['customer_id']);
        $customerName = '--';
        if (!empty($customer)) {
            $customerName = $customer['ctm_name'];
        }
        //[str, font, color, posX, posY]
        $balanceTotal           = 0.00;
        $balanceCouponCostTotal = 0.00;
        $balanceCouponTotal     = 0.00;
        $balanceDepositTotal    = 0.00;
        $extraBalanceTotal      = 0.00;

        $projectOrderIds = array();
        $productOrderIds = array();

        $allOrderIds = array();
        foreach ($balances as $key => $balance) {
            $balanceTotal += $balance['pay_total'];

            $balanceCouponTotal += $balance['coupon_total'];
            if ($balance['coupon_total'] > 0) {
                $balanceCouponCostTotal += $balance['coupon_cost'] * $balance['used_coupon_total'] / $balance['coupon_total'];
            }

            $balanceDepositTotal += $balance['deposit_total'];
            //非项目付款，记入额外营收
            if ($balance['balance_type'] != BalanceType::TYPE_PROJECT_PAY) {
                $extraBalanceTotal += $balance['pay_total'];
                continue;
            }
        }

        //初始化费用列表--按费用类型--固定 1-11, other
        $feeList = array();
        for ($i = 1; $i <= 11; $i++) {
            $feeList[$i] = 0.00;
        }
        $feeList[\app\admin\model\Fee::TYPE_OTHER] = 0.00;

        $deptFeeList = array();
        $deptList    = \app\admin\model\Deptment::getDeptListCache();

        //剔除 退款单，因原单已校正 item_total, item_pay_total
        $projectItems = model('OrderItems')->alias('order_items')
            ->join(model('Project')->getTable() . ' pro', 'order_items.pro_id = pro.pro_id', 'LEFT')
            ->where([
                'order_items.balance_id'  => ['in', $balanceIds],
                'order_items.item_status' => ['neq', OrderItems::STATUS_CHARGEBACK],
            ])
            ->column('order_items.pro_id, order_items.pro_name, order_items.item_total, order_items.item_pay_total, order_items.pro_spec, order_items.dept_id, pro.pro_fee_type', 'order_items.pro_id');
        $this->receiptDeal($projectItems, $feeList, $deptFeeList, $deptList);

        $curFeeNum = 1;
        //11之后归于其它
        foreach ($feeList as $key => $value) {
            $feeMod     = $curFeeNum % 6;
            $feeDivided = $curFeeNum / 6;

            if ($feeDivided <= 1) {
                $posX = 51.5;
            } else {
                $posX = 95;
            }
            $curFeeNum++;

            $posY = 30.5 + ($feeMod == 0 ? 6 : $feeMod) * 5;
            if ($key == \app\admin\model\Fee::TYPE_OTHER) {
                $value += $extraBalanceTotal;
            }

            array_push($printList, ['str' => (string) $value, 'posX' => $posX, 'posY' => $posY, 'fontSize' => $defaultFontSize]);
        }

        //最终实付金额：
        //定金 + 原支付额 + 购券额（非面额） - （更换项目 退还/消耗的 额外定金）
        $realPayTotal       = round(($balanceTotal + $balanceDepositTotal) * 100) / 100;
        $realPayTotalChnStr = number2chinese($realPayTotal, true, false);

        $admin = model('Admin')->field('nickname')->find($adminId);
        if (!empty($admin)) {
            $adminNickName = $admin['nickname'];
        } else {
            $adminNickName = '--';
        }

        //医院
        $hosName = \think\Config::get('site.hospital');
        $hosName = $hosName ? $hosName : '';
        array_push($printList, ['str' => $hosName, 'posX' => 28, 'posY' => 15.0, 'fontSize' => $defaultFontSize]);
        //顾客
        array_push($printList, ['str' => $customerName, 'posX' => 28, 'posY' => 18.2, 'fontSize' => $defaultFontSize]);
        //日期
        array_push($printList, ['str' => $printDate, 'posX' => 90, 'posY' => 22.5, 'fontSize' => $defaultFontSize]);
        //金额
        array_push($printList, ['str' => $realPayTotal, 'posX' => 53, 'posY' => 67, 'fontSize' => $defaultFontSize]);
        array_push($printList, ['str' => $realPayTotalChnStr, 'posX' => 57, 'posY' => 72, 'fontSize' => $defaultFontSize]);
        //收银
        array_push($printList, ['str' => $adminNickName, 'posX' => 34, 'posY' => 84, 'fontSize' => $defaultFontSize]);

        if (!empty($offsetX) || !empty($offsetY)) {
            foreach ($printList as $key => $row) {
                $printList[$key]['posX'] += $offsetX;
                $printList[$key]['posY'] += $offsetY;
            }
        }

        return json($printList);
    }

    private function receiptDeal($proItems, &$feeList, &$deptFeeList, $deptList)
    {
        foreach ($proItems as $key => $proItem) {
            if (isset($feeList[$proItem['pro_fee_type']])) {
                $feeList[$proItem['pro_fee_type']] += $proItem['item_total'];
            } else {
                $feeList[\app\admin\model\Fee::TYPE_OTHER] += $proItem['item_total'];
            }

            if (!isset($deptFeeList[$proItem['dept_id']])) {
                $deptFeeList[$proItem['dept_id']] = array(
                    'dept_name'  => isset($deptList[$proItem['dept_id']]) ? $deptList[$proItem['dept_id']]['dept_name'] : '--',
                    'dept_total' => 0.00,
                    'dept_pros'  => array(),
                );
            }

            array_push($deptFeeList[$proItem['dept_id']]['dept_pros'], $proItem['pro_name']);
            $deptFeeList[$proItem['dept_id']]['dept_total'] += $proItem['item_total'];
        }
    }

    public function modifyInvoice()
    {
        $balance_id = input('balance_id',false);
        $invoice_no = input('invoice_no',false);

        $this->model->save([
                'invoice_no' => $invoice_no,
            ],
                ['balance_id' => ['in', $balance_id]]
            );
        
        $this->success();
    }

    private function dealParams($startDate, $endDate)
    {
        list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null, false);

        $bWhere     = [];
        $extraWhere = [];
        foreach ($where as $key => $value) {
            $bWhere[$value[0]] = [$value[1], $value[2]];
        }

        //营销部门搜索(上级部门可以显示下级部门数据)
        $developAdminFlg = false;
        $developAdminCon = model('admin')->field('id');
        if (isset($bWhere['admin.dept_id'])) {
            $developAdminFlg  = true;
            $deptTree         = \app\admin\model\Deptment::getDeptTreeCache();
            $allSelectedDepts = $deptTree->getChildrenIds($bWhere['admin.dept_id'][1], true);
            $developAdminCon  = $developAdminCon->where(['dept_id' => ['in', $allSelectedDepts]]);
        }
        if (!empty($bWhere['customer.admin_id'])) {
            $developAdminFlg = true;
            $developAdminCon = $developAdminCon->where(['id' => $bWhere['customer.admin_id']]);
        }
        if ($developAdminFlg) {
            $bWhere['customer.admin_id'] = ['exp', 'in ' . $developAdminCon->buildSql()];
        }
        if (isset($bWhere['admin.dept_id'])) {
            unset($bWhere['admin.dept_id']);
        }

        //首次查询时显示当日
        $timewhere = [];
        if (!isset($_GET['op'])) {
            $bWhere['balance.createtime'] = ['BETWEEN', [$startDate, $endDate]];
        }

        return [$bWhere, $sort, $order, $offset, $limit, $extraWhere];
    }
}
