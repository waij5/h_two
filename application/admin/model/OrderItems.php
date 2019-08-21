<?php

namespace app\admin\model;

use app\admin\model\Fee;
use app\admin\model\Project;
use think\Db;
use think\Model;
use think\Session;
use yjy\exception\TransException;

class OrderItems extends Model
{
    // 表名
    protected $name = 'order_items';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    protected $pk                 = 'item_id';
    // 定义时间戳字段名
    protected $createTime = 'item_createtime';
    protected $updateTime = false;
    // 追加属性
    protected $append = [

    ];
    /*
     * 订单状态
     * 待审核, 退款, 撤单, 未付款, 已付款, 已完成（划扣）
     */
    const STATUS_APPLYING = -3;
    //CHARGEBACK 退款单
    const STATUS_CHARGEBACK = -2;
    const STATUS_CANCELED   = -1;
    const STATUS_PENDING    = 0;
    const STATUS_PAYED      = 1;
    const STATUS_COMPLETED  = 2;

    const TAG_DEDUCT_SUCCESS = 'order_deduct_success';
    const TAG_BATCH_DEDUCT   = 'order_batch_deduct';
    const TAG_REVERSE_DEDUCT = 'order_reverse_deduct';
    const TAG_BATCH_REVERSE  = 'order_batch_reverse';
    const TAG_SWITCH_ITEM    = 'order_switch_item';
    const TAG_PAY_ORDER      = 'order_pay_order';
    const TAG_CHARGEBACK     = 'order_chargeback';

    /**
     * 取消项目数额计算
     */
    public function calcCancelItem()
    {
        $result = [
            'error' => true,
            'msg'   => __('Error occurs'),
            'data'  => array(),
        ];
        if ($this->item_status != static::STATUS_PAYED || $this->item_used_times >= $this->item_total_times) {
            $result['msg'] = __('You can\'t cancel this item: incorrect order status!');
        } else {
            if ($this->item_total_times > 0 && $this->pro_use_times > 0) {
                $canceledTimes = $this->item_total_times - $this->item_used_times;
                //单价 / 单个产品可用次数 * （总次数 - 新总次数） --  单次价格 * 未划扣次数
                $oriReturnTotal = 1.00 * $this->item_amount_per_time * $canceledTimes;
                $itemOriTotal   = $this->pro_amount / $this->pro_use_times * $this->item_used_times;
                //新金额含券
                $itemTotal = floor(100 * $this->item_amount_per_time * $this->item_used_times) / 100;
                //新支付额
                $itemPayTotal = floor(100 * $this->item_pay_total * $this->item_used_times / $this->item_total_times) / 100;
                //新券额
                $itemCouponTotal = $itemTotal - $itemPayTotal;

                //退回金额含券
                $returnItemTotal = $this->item_total - $itemTotal;

                $returnTotal       = $this->item_pay_total - $itemPayTotal;
                $cancelCouponTotal = $this->item_coupon_total - $itemCouponTotal;

                $data = [
                    'item_total_times'    => $this->item_used_times,
                    'item_local_total'    => $this->pro_local_amount / $this->pro_use_times * $this->item_used_times,
                    'item_ori_total'      => $itemOriTotal,
                    'item_min_total'      => $this->pro_min_amount / $this->pro_use_times * $this->item_used_times,
                    'item_total'          => $itemTotal,
                    'item_discount_total' => ($itemOriTotal - $itemTotal),
                    'oriReturnTotal'      => $oriReturnTotal,
                    'cancelCouponTotal'   => $cancelCouponTotal,
                    'returnTotal'         => $returnTotal,

                    'item_pay_total'      => $itemPayTotal,
                    'item_coupon_total'   => $itemCouponTotal,
                ];
                $result['error'] = false;
                $result['msg']   = '';
                $result['data']  = $data;
            } else {
                $result['msg'] = __('You can\'t cancel the item,it seems like that the item info is not exactlly right!');
                //始数据问题，导致无法操作，非正常极其特殊情况
            }
        }

        return $result;
    }

    /**
     * 以预计算的数据更新项目
     */
    public function saveWithCalcData($data)
    {
        $updateData = array(
            'item_total_times'      => $data['item_total_times'],
            'item_local_total'      => $data['item_local_total'],
            'item_ori_total'        => $data['item_ori_total'],
            'item_min_total'        => $data['item_min_total'],
            'item_total'            => $data['item_total'],
            'item_discount_total'   => $data['item_discount_total'],

            'item_pay_total'        => $data['item_pay_total'],
            'item_coupon_total'     => $data['item_coupon_total'],
            'item_undeducted_total' => 0.00,
        );

        if ($this->item_status == static::STATUS_PAYED && $this->item_used_times == $data['item_total_times']) {
            $updateData['item_status'] = static::STATUS_COMPLETED;
        }

        return $this->save($updateData);
    }

    /**
     * 获取划扣项目列表总数
     */
    public static function getListCount($where, $extraWhere = [])
    {
        $list = static::alias('order_items')
            ->join(Db::getTable('customer') . ' customer', 'order_items.customer_id = customer.ctm_id', 'LEFT')
            ->join(Db::getTable('admin') . ' admin', 'customer.admin_id = admin.id', 'LEFT')
            ->where($where)
            ->where($extraWhere)
            ->count();

        return $list;
    }
    /**
     * 获取划扣项目列表
     */
    public static function getList($where, $sort, $order, $offset, $limit, $extraWhere = [])
    {
        $list = static::alias('order_items')
            ->join(Db::getTable('customer') . ' customer', 'order_items.customer_id = customer.ctm_id', 'LEFT')
            ->field('order_items.*, customer.ctm_name, customer.admin_id as develop_admin_id')
            ->join(Db::getTable('admin') . ' admin', 'customer.admin_id = admin.id', 'LEFT')
            ->where($where)
            ->where($extraWhere)
            ->order($sort, $order)
            ->limit($offset, $limit)
            ->select();

        $admin          = new \app\admin\model\Admin;
        $briefAdminList = $admin->getBriefAdminList();
        foreach ($list as $key => $row) {
            $list[$key]['osconsult_admin_name'] = ($row['admin_id'] && isset($briefAdminList[$row['admin_id']])) ? $briefAdminList[$row['admin_id']] : '';
            $list[$key]['develop_admin_name']   = ($row['develop_admin_id'] && isset($briefAdminList[$row['develop_admin_id']])) ? $briefAdminList[$row['develop_admin_id']] : '';
            $list[$key]['consult_admin_name']   = ($row['consult_admin_id'] && isset($briefAdminList[$row['consult_admin_id']])) ? $briefAdminList[$row['consult_admin_id']] : '';
            $list[$key]['recept_admin_name']    = ($row['recept_admin_id'] && isset($briefAdminList[$row['recept_admin_id']])) ? $briefAdminList[$row['recept_admin_id']] : '';
            $list[$key]['prescriber_name']      = ($row['prescriber'] && isset($briefAdminList[$row['prescriber']])) ? $briefAdminList[$row['prescriber']] : '';
        }

        return $list;
    }

    /**
     * 统计项目金额
     */
    public function getItemSummary($mainTableWhere, $extraWhere)
    {
        if (!empty($mainTableWhere)) {
            $m = static::table($subQuery = static::where($mainTableWhere)
                    ->buildSql())->alias();
        } else {
            $m = static::alias('order_items');
        }

        $summary = $m->join(model('Customer')->getTable() . ' customer', 'order_items.customer_id = customer.ctm_id', 'LEFT')
            ->where($extraWhere)
            ->field('sum(order_items.item_total_times) as item_total_times, sum(order_items.item_used_times) as item_used_times, sum(order_items.item_cost) as item_cost, sum(order_items.item_discount_total) as item_discount_total, sum(order_items.item_total) as item_total, sum(order_items.item_amount_per_time * order_items.item_used_times) as deducted_total')
            ->find();

        $summary['undeducted_total'] = $summary['item_total'] - $summary['deducted_total'];

        return $summary;
    }

    /**
     *  统计数目，金额等
     */
    public static function getListSummary($where, $includeAllSummary = false)
    {
        if ($includeAllSummary) {
            $columns = "count(order_items.item_id) as count, sum(order_items.item_total_times) as item_total_times, sum(order_items.item_used_times) as item_used_times, sum(case when item_status='%s' then 0 else item_total end) as item_total, sum(item_pay_total) as item_pay_total, sum(case when item_status='%s' then 0 else item_coupon_total end) as item_coupon_total, sum(item_original_total) as item_original_total, sum(item_original_pay_total) as item_original_pay_total,sum(case when item_status='%s' then 0 else item_total_times end) as total_times, sum(case when item_status='%s' then 0 else item_used_times end) as used_total_times, sum(item_undeducted_total) as undeducted_total, sum(case when item_old_id <> 0 then item_original_pay_total else 0 end) as item_switch_total";
            $columns = sprintf($columns, OrderItems::STATUS_CHARGEBACK, OrderItems::STATUS_CHARGEBACK, OrderItems::STATUS_CHARGEBACK, OrderItems::STATUS_CHARGEBACK);

            return current(OrderItems::alias('order_items')
                ->join(Customer::getTable() . ' customer', 'order_items.customer_id = customer.ctm_id', 'LEFT')
                ->join(Db::getTable('admin') . ' admin', 'customer.admin_id = admin.id', 'LEFT')
                ->where($where)
                // ->where(['order_items.item_status' => ['in', [OrderItems::STATUS_PAYED, OrderItems::STATUS_COMPLETED, OrderItems::STATUS_CHARGEBACK]]])
                ->limit(1)
                ->column($columns));
        } else {
            return OrderItems::alias('order_items')
                ->join(Customer::getTable() . ' customer', 'order_items.customer_id = customer.ctm_id', 'LEFT')
                ->join(Db::getTable('admin') . ' admin', 'customer.admin_id = admin.id', 'LEFT')
                ->where($where)
                // ->where(['order_items.item_status' => ['in', [OrderItems::STATUS_PAYED, OrderItems::STATUS_COMPLETED, OrderItems::STATUS_CHARGEBACK]]])
                // ->group('order_items.customer_id')
                ->count();
        }
    }

    /**
     * 检测划扣权限
     */
    public function canStaffDeduct($admin)
    {
        if (empty($admin)) {
            return false;
        }
        if ($admin->dept_type == '*') {
            return true;
        } else {
            $canDeductDepts = explode(',', $admin->dept_type);
            if (in_array($this->dept_id, $canDeductDepts)) {
                return true;
            }
        }

        return false;
    }

    /**
     * 批量生成订单
     * @params array ['customer_id', 'osconsult_id', 'consult_admin_id', 'recept_admin_id', 'admin_id']
     */
    public static function createOrder($params, $itemsParams, $applyDetail = ['doApply' => false, 'applyInfo' => ''], $adminid, $prescriber, $admin = null, $useTrans = true)
    {
        if (empty($admin)) {
            $admin = Session::get('admin');
        }
        
        $result = [
            'error' => true,
            'msg'   => __('Error occurs'),
        ];

        // $projectIdArr = array_column($itemsParams, 'pro_id');
        $projectIdArr = array_column($itemsParams, 'pk');

        $projects = Project::where([
            'pro_status' => 1,
            'pro_id'     => ['in', $projectIdArr],
        ])
            ->column('pro_id, pro_name, pro_use_times, pro_local_amount, pro_amount, pro_min_amount, pro_cost as cost, pro_unit, pro_spec, dept_id, deduct_addr, pro_stock, pro_type, deduct_switch', 'pro_id');

        $notMatchedProject = array_diff($projectIdArr, array_keys($projects));
        if (!empty($notMatchedProject)) {
            $result['msg'] .= '<br />' . __('Projects which ID in (%s) do not exist!', implode(',', $notMatchedProject));
            return $result;
        }

        try {
            //开始事务
            if ($useTrans) {
                Db::startTrans();
            }
            

            //初始化统计信息
            $applyingItemsCount = 0;
            $discountLimit      = $admin->getDiscountLimit();

            $discountLimitFlag = \think\Config::get('site.discount_limit_flag', false);

            //订单项目保存 部分项目保存失败 不中断
            foreach ($itemsParams as $key => $row) {
                // $row['amount']
                if ($row['qty'] <= 0) {
                    //错误的项目数量，退出
                    continue;
                }

                $itemAmount = $row['item_total'] / $row['qty'];
                $subItem    = $projects[$row['pk']];

                //超出库存, 不检查项目
                if ($subItem['pro_type'] != Project::TYPE_PROJECT && ($subItem['pro_stock'] - $row['qty']) < 0) {
                    throw new TransException(__('Out of stock, id: %s!, name: %s!, stock: %s!', $row['pk'], $subItem['pro_name'], $subItem['pro_stock']));
                }

                $itemStatus = static::STATUS_PENDING;
                if ($discountLimitFlag && isDiscountAllowed($itemAmount, $subItem['pro_amount'], $subItem['pro_min_amount'], $discountLimit) == false) {
                    //订单项折扣超出了折扣权限, 中止 或者 直接进入审批环节
                    //仅当是职员，并且 自动申请领导对调价进行审批进入
                    if ($admin->position == 0 && $applyDetail['doApply']) {
                        $itemStatus = self::STATUS_APPLYING;
                    } else {
                        throw new TransException(__('Order can not be saved: discount out of your permission!'));
                    }
                }

                $orderItem            = new OrderItems;
                $orderItem->item_type = $subItem['pro_type'];
                $orderItem->pro_id    = $subItem['pro_id'];
                $orderItem->pro_name  = $subItem['pro_name'];
                $orderItem->item_qty  = $row['qty'];

                $orderItem->pro_use_times    = $subItem['pro_use_times'];
                $orderItem->item_total_times = $subItem['pro_use_times'] * $row['qty'];
                $orderItem->item_used_times  = 0;

                $orderItem->pro_local_amount = $subItem['pro_local_amount'];
                $orderItem->pro_amount       = $subItem['pro_amount'];
                $orderItem->pro_min_amount   = $subItem['pro_min_amount'];
                $orderItem->customer_id      = $params['customer_id'];

                //单项金额，单次价格 项目折扣率， 项目折扣金额（总），项目本地总额， 项目总额， 项目最小总额，项目实际总额
                $orderItem->item_amount           = $itemAmount;
                $orderItem->item_amount_per_time  = @bcdiv($itemAmount / $subItem['pro_use_times'], 4);
                $orderItem->item_cost             = $subItem['cost'];
                $orderItem->item_discount_percent = $subItem['pro_amount'] == 0 ? 100 : (100.0 * $itemAmount / $subItem['pro_amount']);
                $orderItem->item_discount_total   = $subItem['pro_amount'] * $row['qty'] - $row['item_total'];
                $orderItem->item_local_total      = $subItem['pro_local_amount'] * $row['qty'];
                $orderItem->item_ori_total        = $subItem['pro_amount'] * $row['qty'];
                $orderItem->item_min_total        = $subItem['pro_min_amount'] * $row['qty'];
                $orderItem->item_total            = $row['item_total'];

                //单位，规模，科室，划扣点
                $orderItem->pro_unit      = $subItem['pro_unit'];
                $orderItem->pro_spec      = $subItem['pro_spec'];
                $orderItem->dept_id       = $subItem['dept_id'] ? $subItem['dept_id'] : $params['dept_id'];
                $orderItem->deduct_addr   = $subItem['deduct_addr'];
                $orderItem->deduct_switch = $subItem['deduct_switch'];

                $orderItem->balance_id            = 0;
                $orderItem->item_undeducted_total = 0.00;
                $orderItem->item_status           = $itemStatus;
                $orderItem->osconsult_id          = isset($params['osconsult_id']) ? $params['osconsult_id'] : 0;
                $orderItem->consult_admin_id      = isset($params['consult_admin_id']) ? $params['consult_admin_id'] : 0;
                $orderItem->recept_admin_id       = isset($params['recept_admin_id']) ? $params['recept_admin_id'] : 0;
                $orderItem->admin_id              = $adminid;
                $orderItem->prescriber            = $prescriber;

                // 创建订单， 非 ===
                if ($orderItem->save() == false) {
                    throw new TransException();
                } else {
                    if ($orderItem->item_status == self::STATUS_APPLYING) {
                        $orderApplyRecord                 = new \app\admin\model\OrderApplyRecords;
                        $orderApplyRecord->customer_id    = $orderItem->customer_id;
                        $orderApplyRecord->item_id        = $orderItem->item_id;
                        $orderApplyRecord->apply_info     = strip_tags($applyDetail['applyInfo']) . '<br /><br />产品/项目名： ' . $orderItem->pro_name . '<br />规格：' . $orderItem->pro_spec . '<br />数量：' . $orderItem->item_qty . '(' . $orderItem->item_total_times . ')<br />' . '原价：' . $orderItem->item_ori_total . '<br />折后价：' . $orderItem->item_total . '<br />折扣:' . $orderItem->item_discount_total . '(' . number_format($orderItem->item_discount_percent, 2, ".", "") . '%)';
                        $orderApplyRecord->reply_info     = '';
                        $orderApplyRecord->apply_admin_id = $admin->id;
                        $orderApplyRecord->reply_status   = \app\admin\model\OrderApplyRecords::STATUS_PENDING;
                        $orderApplyRecord->save();
                        $applyingItemsCount++;
                    }
                }
            }

            if ($useTrans) {
                Db::commit();
            }

            $result['error'] = false;
            if ($applyingItemsCount) {
                $result['msg'] = __('All order have been saved successfully(%s are waiting to be confirm by manager!)');
            } else {
                $result['msg'] = __('All order have been created successfully');
            }
        } catch (TransException $e) {
            if ($useTrans) {
                Db::rollback();
            }
            $result['msg'] = $e->getMessage();
        } catch (\think\exception\PDOException $e) {
            if (\think\Config::get('app_debug')) {
                $result['msg'] = $e->getMessage();
            } else {
                $result['msg'] = __('An unexpected error occurred');
            }

            if ($useTrans) {
                Db::rollback();
            }
        }

        return $result;
    }

    /**
     * 划扣--BETA
     * @param int $orderItemId 划扣子项ID
     * @param array $params 划扣基本信息
     * @param array $deductParams 划扣职工提成信息
     * @return array $result
     */
    public static function deduct($params, $deductParams, $admin)
    {
        $result = ['error' => true, 'msg' => __('An unexpected error occurred'), 'data' => []];

        if (!is_array($params) || !is_array($deductParams) || (!array_key_exists('order_item_id', $params) || !array_key_exists('deduct_times', $params))) {
            $result['msg'] = __('Invalid parameters');
            return $result;
        }

        try
        {
            $orderItem = model('OrderItems')->get($params['order_item_id']);
            if ($orderItem == null) {
                throw new TransException(__('No results were found'));
            }
            if (empty($admin)) {
                $admin = \think\Session::get('admin');
            }
            // 检测划扣权限
            if ($orderItem->canStaffDeduct($admin) != true) {
                throw new TransException(__('You have no permission'));
            }
            if ($orderItem->item_status != static::STATUS_PAYED) {
                throw new TransException(__('Operation could be done!The order is not in status PAYED'));
            }

            $realDeductTimes        = @intval($params['deduct_times']);
            if ($realDeductTimes <= 0) {
                $result['msg'] = __('Invalid parameters');
                return $result;
            }
            $calcedTotalDeductTimes = $realDeductTimes + $orderItem->item_used_times;
            if ($calcedTotalDeductTimes > $orderItem->item_total_times) {
                throw new TransException(__('Deduct times is out of range!'));
            } elseif ($calcedTotalDeductTimes == $orderItem->item_total_times) {
                //更新订单状态
                $orderItem->item_status = static::STATUS_COMPLETED;
            }

            $deductAmout            = bcmul($orderItem->item_pay_amount_per_time, $realDeductTimes, 2);
            $params['deduct_times'] = $realDeductTimes;
            $itemCostPerTimes       = $orderItem->item_cost / $orderItem->pro_use_times;

            $deductBenefitAmout = $deductAmout - ($realDeductTimes * $orderItem->item_cost / $orderItem->pro_use_times);

            if ($orderItem->item_type == Project::TYPE_PROJECT) {
                $deductStatus = \app\admin\model\DeductRecords::STATUS_COMPLETED;
            } else {
                $deductStatus = \app\admin\model\DeductRecords::STATUS_PENGING;
            }

            $deductData = [
                'order_item_id'         => $params['order_item_id'],
                'deduct_times'          => $params['deduct_times'],
                'deduct_amount'         => $deductAmout,
                'deduct_benefit_amount' => $deductBenefitAmout,
                'status'                => $deductStatus,
                'admin_id'              => $admin->id,
                'stat_date'             => date('Y-m-d'),
            ];
            $deductRecord = new \app\admin\model\DeductRecords;

            Db::startTrans();
            //划扣， 更新订单项， 生成划扣记录
            //更新划扣次数
            $orderItem->item_used_times       = ['exp', 'item_used_times + ' . $realDeductTimes];
            $orderItem->item_undeducted_total = $orderItem->item_undeducted_total - $deductAmout;
            if ($orderItem->save() === false || $deductRecord->save($deductData) == false) {
                throw new TransException(__('Failed when try to save deduct record!'));
            }

            //此类型允许的划扣角色数组
            $dedcutRoleIs  = array();
            $deductRoles   = model('DeductRole')->where(['type' => $orderItem->item_type])->column('*', 'id');
            $dedcutRoleIds = array_keys($deductRoles);

            foreach ($deductParams as $roldId => $roleParams) {
                //过滤 非法 ROLE
                if (!isset($deductRoles[$roldId])) {
                    continue;
                }
                $roleOriPercent   = $deductRoles[$roldId]['percent'];
                $roleTotalPercent = 0.0;
                foreach ($roleParams as $key => $deductStaffSet) {
                    $roleTotalPercent += floatval($deductStaffSet['percent']);
                }

                if ($roleTotalPercent <= 0) {
                    continue;
                } else {
                    //少于百分百， 1， 大于百分百 调整
                    $roleAdjustPercent = $roleTotalPercent > 100 ? 1.0 * 100 / $roleTotalPercent : 1.0;
                    foreach ($roleParams as $key => $deductStaffSet) {
                        //角色总提成比率 * 职工比例 * 角色调整比率
                        $deductStaffPercent = $roleOriPercent * $deductStaffSet['percent'] / 100.0 * $roleAdjustPercent;
                        //划扣次数 * (单项价格 - 单项成本) / 本项次数 * 职工提成比率
                        if ($orderItem->pro_use_times > 0) {
                            $deductStaffAmount        = $deductRecord->deduct_amount * $deductStaffPercent / 100.00;
                            $deductStaffBenefitAmount = $deductRecord->deduct_benefit_amount * $deductStaffPercent / 100.00;
                        } else {
                            $deductStaffAmount        = 0.00;
                            $deductStaffBenefitAmount = 0.00;
                        }

                        //model 默认是单例， 保存后会记录信息， 包括主键
                        $staffRecordData = [
                            'deduct_record_id'     => $deductRecord->id,
                            'deduct_role_id'       => $roldId,
                            'admin_id'             => $deductStaffSet['admin_id'],
                            'percent'              => $deductStaffSet['percent'],
                            'final_percent'        => $deductStaffPercent,
                            'final_amount'         => $deductStaffAmount,
                            'final_benefit_amount' => $deductStaffBenefitAmount,
                        ];

                        $deductStaffRecord = new DeductStaffRecords();
                        if ($deductStaffRecord->save($staffRecordData) == false) {
                            throw new TransException(__('Failed to save deduct staff record!'));
                        }
                    }
                }
            }
            //事务提交
            Db::commit();
            $hookParams = ['orderItem' => $orderItem, 'deductRecord' => $deductRecord];
            \think\Hook::listen(self::TAG_DEDUCT_SUCCESS, $hookParams);

            $result['error'] = false;
            $result['msg']   = __('Deducted successfully.');
            $result['data']  = [
                'orderStatus'    => $orderItem->item_status,
                'orderItemId'    => $orderItem->item_id,
                'itemUsedTimes'  => $orderItem->item_used_times,
                'itemTotalTimes' => $orderItem->item_total_times,
                'isItemFinished' => ($orderItem->item_used_times >= $orderItem->item_total_times),
                'deductRecord'   => $deductRecord,
            ];
            return $result;
        } catch (think\exception\PDOException $e) {
            Db::rollback();
            $result['msg'] = $e->getMessage();
            return $result;
        } catch (TransException $e) {
            Db::rollback();
            $result['msg'] = $e->getMessage();
            return $result;
        }
    }

    /**
     * 单人批量划扣
     * 批量划扣的权限检查不在MODEL层
    // $deductParams,
     */
    public static function batchDeduct4Single($customer, $itemType, $itemList, $deductTimes, $deductParams, $admin)
    {
        $result      = ['error' => true, 'msg' => __('Error occurs'), 'data' => []];
        $deductTimes = intval($deductTimes);
        if ($deductTimes <= 0) {
            $result['msg'] = __("Invalid parameters");
            return $result;
        }
        $successCount = 0;
        $failCount    = 0;

        $batchDeductTotal = 0.00;
        $orderItemIdArr   = array();

        if ($itemType == Project::TYPE_PROJECT) {
            $deductStatus = \app\admin\model\DeductRecords::STATUS_COMPLETED;
        } else {
            $deductStatus = \app\admin\model\DeductRecords::STATUS_PENGING;
        }

        //此类型允许的划扣角色数组
        $dedcutRoleIs  = array();
        $deductRoles   = model('DeductRole')->where(['type' => $itemType])->column('*', 'id');
        $dedcutRoleIds = array_keys($deductRoles);

        $deductRecordIds = array();

        Db::startTrans();
        try {
            foreach ($itemList as $key => $orderItem) {
                if ($orderItem->item_status != static::STATUS_PAYED) {
                    throw new TransException(__('Could not deduct: incorrect order status!'));
                }

                $calcedTotalDeductTimes = $deductTimes + $orderItem->item_used_times;
                if ($calcedTotalDeductTimes > $orderItem->item_total_times) {
                    throw new TransException(__('Deduct times is out of range!'));
                } elseif ($calcedTotalDeductTimes == $orderItem->item_total_times) {
                    $deductAmout = $orderItem->item_undeducted_total;
                    //更新订单状态
                    $orderItem->item_status = static::STATUS_COMPLETED;
                } else {
                    $deductAmout = bcmul($orderItem->item_pay_amount_per_time, $deductTimes, 2);
                }

                $itemCostPerTimes   = $orderItem->item_cost / $orderItem->pro_use_times;
                $deductBenefitAmout = $deductAmout - ($deductTimes * $orderItem->item_cost / $orderItem->pro_use_times);

                //订单实付金额 / 订单金额 比值

                $deductData = [
                    'order_item_id'         => $orderItem->item_id,
                    'deduct_times'          => $deductTimes,
                    'deduct_amount'         => $deductAmout,
                    'deduct_benefit_amount' => $deductBenefitAmout,
                    'status'                => $deductStatus,
                    'admin_id'              => $admin->id,
                    'stat_date'             => date('Y-m-d'),
                ];
                $deductRecord = new \app\admin\model\DeductRecords();

                //划扣， 更新订单项， 生成划扣记录
                //更新划扣次数
                $orderItem->item_used_times       = ['exp', 'item_used_times + ' . $deductTimes];
                $orderItem->item_undeducted_total = ['exp', 'item_undeducted_total - ' . $deductAmout];
                if ($orderItem->save() === false || $deductRecord->save($deductData) == false) {
                    throw new TransException(__('Failed when try to save deduct record!'));
                }
                array_push($deductRecordIds, $deductRecord->id);
                array_push($orderItemIdArr, $orderItem->item_id);

                $batchDeductTotal += $deductAmout;

                foreach ($deductParams as $roldId => $roleParams) {
                    //过滤 非法 ROLE
                    if (!isset($deductRoles[$roldId])) {
                        continue;
                    }

                    $roleOriPercent   = $deductRoles[$roldId]['percent'];
                    $roleTotalPercent = 0.0;
                    foreach ($roleParams as $key => $deductStaffSet) {
                        $roleTotalPercent += floatval($deductStaffSet['percent']);
                    }

                    if ($roleTotalPercent <= 0) {
                        continue;
                    } else {
                        //少于百分百， 1， 大于百分百 调整
                        $roleAdjustPercent = $roleTotalPercent > 100 ? 1.0 * 100 / $roleTotalPercent : 1.0;
                        foreach ($roleParams as $key => $deductStaffSet) {
                            //角色总提成比率 * 职工比例 * 角色调整比率
                            $deductStaffPercent = $roleOriPercent * $deductStaffSet['percent'] / 100.0 * $roleAdjustPercent;
                            //划扣次数 * (单项价格 - 单项成本) / 本项次数 * 职工提成比率
                            if ($orderItem->pro_use_times > 0) {
                                $deductStaffAmount        = $deductRecord->deduct_amount * $deductStaffPercent / 100.00;
                                $deductStaffBenefitAmount = $deductRecord->deduct_benefit_amount * $deductStaffPercent / 100.00;
                            } else {
                                $deductStaffAmount        = 0.00;
                                $deductStaffBenefitAmount = 0.00;
                            }

                            //model 默认是单例， 保存后会记录信息， 包括主键
                            $staffRecordData = [
                                'deduct_record_id'     => $deductRecord->id,
                                'deduct_role_id'       => $roldId,
                                'admin_id'             => $deductStaffSet['admin_id'],
                                'percent'              => $deductStaffSet['percent'],
                                'final_percent'        => $deductStaffPercent,
                                'final_amount'         => $deductStaffAmount,
                                'final_benefit_amount' => $deductStaffBenefitAmount,
                            ];

                            $deductStaffRecord = new DeductStaffRecords();
                            if ($deductStaffRecord->save($staffRecordData) == false) {
                                throw new TransException(__('Failed to save deduct staff record!'));
                            }
                        }
                    }
                }
            }

        } catch (TransException $e) {
            Db::rollback();
            $result['msg'] = $e->getMessage();
            return $result;
        } catch (\think\PDOException $e) {
            Db::rollback();
            $result['msg'] = $e->getMessage();
            return $result;
        }

        Db::commit();
        $hookParams = ['batchDeductTotal' => $batchDeductTotal, 'orderItemIdArr' => $orderItemIdArr];
        \think\Hook::listen(self::TAG_BATCH_DEDUCT, $customer, $hookParams);
        \think\Log::record('============== TAG_BATCH_DEDUCT');
        $result['error'] = false;
        $result['msg']   = __('Operation completed');
        $result['data']['deductRecordIds'] = $deductRecordIds;
        return $result;
    }

    /**
     * 返回订单状态列表
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_APPLYING   => __('order_status_m_3'),
            self::STATUS_CHARGEBACK => __('order_status_m_2'),
            self::STATUS_CANCELED   => __('order_status_m_1'),
            self::STATUS_PENDING    => __('order_status_0'),
            self::STATUS_PAYED      => __('order_status_1'),
            self::STATUS_COMPLETED  => __('order_status_2'),
        ];
    }

    /**
     * ===================
     * 出库相关
     * ===================
     */

    /**
     * 获取订单已划扣未出库的子项数
     */
    public static function getUndeliverdListCount($where, $extraWhere = [])
    {
        return \app\admin\model\DeductRecords::alias('deduct_records')
            ->join(static::getTable() . ' order_items', 'deduct_records.order_item_id = order_items.item_id', 'INNER')
            ->where($where)
            ->where($extraWhere)
            ->where(['deduct_records.status' => \app\admin\model\DeductRecords::STATUS_PENGING])
            ->count();
    }

    /**
     * 获取订单已划扣已出库的子项数
     */
    public static function getdeliverdListCount($where, $extraWhere = [])
    {
        return \app\admin\model\DeductRecords::alias('deduct_records')
            ->join(static::getTable() . ' order_items', 'deduct_records.order_item_id = order_items.item_id', 'INNER')
            ->join(\think\Db::getTable('wm_recipe') . ' r', 'deduct_records.id = r.rdr_id', 'LEFT')
            ->join(\think\Db::getTable('wm_stocklog') . ' s', 'r.rsl_id = s.sl_id', 'LEFT')
            ->where($where)
            ->where($extraWhere)
            ->where(['deduct_records.status' => \app\admin\model\DeductRecords::STATUS_COMPLETED])
            ->count();
    }

    /**
     * 获取订单已划扣未出库的子项列表      20180122
     */
    public static function getUndeliverdList($where, $sort, $order, $offset, $limit, $extraWhere = [])
    {
        return \app\admin\model\DeductRecords::alias('deduct_records')
            ->join(static::getTable() . ' order_items', 'deduct_records.order_item_id = order_items.item_id', 'INNER')
            ->join(\think\Db::getTable('admin') . ' admin', 'deduct_records.admin_id = admin.id', 'LEFT')
            ->join(\think\Db::getTable('customer') . ' customer', 'order_items.customer_id = customer.ctm_id', 'LEFT')
            ->where($where)
            ->where($extraWhere)
            ->where(['deduct_records.status' => \app\admin\model\DeductRecords::STATUS_PENGING])
            ->field('deduct_records.*, customer.ctm_name, order_items.pro_name, order_items.pro_unit, order_items.pro_spec, order_items.dept_id, order_items.item_paytime, order_items.item_type, order_items.item_total_times, order_items.customer_id, admin.nickname as admin_nickname,order_items.pro_id, order_items.item_cost')
        // , p.lotnum, p.stock, p.extime
            ->order($sort, $order)
            ->limit($offset, $limit)
            ->select();
    }

    /**
     * 获取订单已划扣已出库的子项列表          20180122
     */
    public static function getdeliverdList($where, $sort, $order, $offset, $limit, $extraWhere = [])
    {
        return \app\admin\model\DeductRecords::alias('deduct_records')
            ->join(static::getTable() . ' order_items', 'deduct_records.order_item_id = order_items.item_id', 'INNER')
            ->join(\think\Db::getTable('admin') . ' admin', 'deduct_records.admin_id = admin.id', 'LEFT')
            ->join(\think\Db::getTable('customer') . ' customer', 'order_items.customer_id = customer.ctm_id', 'LEFT')
            ->join(\think\Db::getTable('wm_recipe') . ' r', 'deduct_records.id = r.rdr_id', 'LEFT')
            ->join(\think\Db::getTable('wm_stocklog') . ' s', 'r.rsl_id = s.sl_id', 'LEFT')
            ->where($where)
            ->where($extraWhere)
            ->where(['deduct_records.status' => \app\admin\model\DeductRecords::STATUS_COMPLETED])
            ->field('deduct_records.*, customer.ctm_name, order_items.pro_name, order_items.pro_unit, order_items.pro_spec, order_items.dept_id, order_items.item_paytime, order_items.item_type, order_items.item_total_times, order_items.customer_id, admin.nickname as admin_nickname,order_items.pro_id, order_items.item_cost, s.sltime')
            ->order($sort, $order)
            ->limit($offset, $limit)
            ->select();
    }

    public static function generateDailyFeeSummary($payTimeStart, $payTimeEnd)
    {
        $feeSummary  = array();
        $feeTypeList = Fee::getList();

        $feeSummary[0] = ['pay_total' => 0.00, 'original_pay_total' => 0.00];

        foreach ($feeTypeList as $key => $feeType) {
            $feeSummary[$key] = ['pay_total' => 0.00, 'original_pay_total' => 0.00];
        }

        $summary = static::alias('order_items')
            ->join((new Project)->getTable() . ' pro', 'order_items.pro_id = pro.pro_id', 'LEFT')
            ->join((new \app\admin\model\CustomerBalance)->getTable() . ' balance', 'order_items.balance_id = balance.balance_id')
            ->where([
                'order_items.item_paytime' => ['between', [$payTimeStart, $payTimeEnd]],
                'balance.createtime'       => ['between', [$payTimeStart, $payTimeEnd]],
            ])
            ->where(['item_paytime' => ['>', 0]])
            ->group('pro.pro_fee_type')
            ->column('pro.pro_fee_type, sum(order_items.item_original_pay_total) as item_original_pay_total, sum(order_items.item_pay_total) as item_pay_total', 'pro.pro_fee_type');

        foreach ($summary as $feeType => $row) {
            $feeSummary[0]['pay_total'] += $row['item_pay_total'];
            $feeSummary[0]['original_pay_total'] += $row['item_original_pay_total'];

            if (!empty($feeType) && isset($feeSummary[$feeType])) {
                $feeSummary[$feeType]['pay_total'] += $row['item_pay_total'];
                $feeSummary[$feeType]['original_pay_total'] += $row['item_original_pay_total'];
            } else {
                $feeSummary[Fee::TYPE_OTHER]['pay_total'] += $row['item_pay_total'];
                $feeSummary[Fee::TYPE_OTHER]['original_pay_total'] += $row['item_original_pay_total'];
            }
        }

        return $feeSummary;
    }

}
