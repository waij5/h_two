<?php

namespace app\admin\model;

use think\Db;
use think\Model;
use yjy\exception\TransException;

class DeductRecords extends Model
{
    // 表名
    protected $name = 'deduct_records';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    // 追加属性
    protected $append = [
    ];
    protected $resultSetType = '';

    const STATUS_PENGING   = 1;
    const STATUS_COMPLETED = 2;
    //被反划扣
    const STATUS_REVERSED = 3;
    //反划扣
    const STATUS_REVERSE = -1;

    public static function getStatusList()
    {
        return [
            self::STATUS_PENGING   => __('Status_0'),
            self::STATUS_COMPLETED => __('Status_1'),
            self::STATUS_REVERSED  => __('Status_2'),
            self::STATUS_REVERSE   => __('Status_3'),

        ];
    }

    /**
     * 反划扣
     */
    public function reverseDeduct($adminId)
    {
        $result = [
            'error' => true,
            'msg'   => 'Error occurs',
            'data'  => [],
        ];
        //订单获取
        $orderItem = model('OrderItems')->find($this->order_item_id);
        if ($orderItem == null) {
            $result['msg'] = __('Failed to reverse deduction: could not find this order item!');
            return $result;
        }

        if ($orderItem->item_type == \app\admin\model\Project::TYPE_PROJECT) {
            if ($this->status != DeductRecords::STATUS_COMPLETED) {
                $result['msg'] = __('This deduct record could not be reversed: incorrect status!');
                return $result;
            }
        } else {
            if ($this->status == DeductRecords::STATUS_COMPLETED) {
                $result['msg'] = __('This deduct record could not be reversed: plz reverse delivering first!');
                return $result;
            } elseif ($this->status != DeductRecords::STATUS_PENGING) {
                $result['msg'] = __('This deduct record could not be reversed: incorrect status!');
                return $result;
            }
        }

        try {
            Db::startTrans();

            $newDeductRecord                        = new static;
            $newDeductRecord->order_item_id         = $this->order_item_id;
            $newDeductRecord->deduct_times          = -$this->deduct_times;
            $newDeductRecord->deduct_amount         = -$this->deduct_amount;
            $newDeductRecord->deduct_benefit_amount = -$this->deduct_benefit_amount;
            //反划扣
            $newDeductRecord->status   = self::STATUS_REVERSE;
            $newDeductRecord->admin_id = $adminId;
            //存储原划扣记录ID
            $newDeductRecord->old_record_id = $this->id;
            $newDeductRecord->stat_date     = date('Y-m-d');
            $newDeductRecord->save();

            $oldDeductStaffRecords = model('DeductStaffRecords')
                ->where(['deduct_record_id' => $this->id])
                ->select();
            foreach ($oldDeductStaffRecords as $key => $oldDeductStaffRecord) {
                $newDeductStaffRecord                       = new DeductStaffRecords;
                $newDeductStaffRecord->deduct_record_id     = $newDeductRecord->id;
                $newDeductStaffRecord->deduct_role_id       = $oldDeductStaffRecord->deduct_role_id;
                $newDeductStaffRecord->admin_id             = $oldDeductStaffRecord->admin_id;
                $newDeductStaffRecord->percent              = -$oldDeductStaffRecord->percent;
                $newDeductStaffRecord->final_percent        = -$oldDeductStaffRecord->final_percent;
                $newDeductStaffRecord->final_amount         = -$oldDeductStaffRecord->final_amount;
                $newDeductStaffRecord->final_benefit_amount = -$oldDeductStaffRecord->final_benefit_amount;
                //保存 职员划扣记录
                if ($newDeductStaffRecord->save() === false) {
                    throw new TransException(__('Failed to reverse deduction: error occurs while saving reverse deduction!'));
                }
            }

            $this->status = DeductRecords::STATUS_REVERSED;
            //更新 原划扣记录状态为  被反划扣
            if ($this->save() === false) {
                throw new TransException(__('Failed to reverse deduction: error occurs while saving old deduction!'));
            }


            //更新订单信息
            $orderItem->item_status = \app\admin\model\OrderItems::STATUS_PAYED;
            $orderItem->item_used_times = ['exp', 'item_used_times - ' . $this->deduct_times];
            $orderItem->item_undeducted_total =  ['exp', 'item_undeducted_total + ' . $this->deduct_amount];
            if ($orderItem->save() === false) {
                throw new TransException(__('Failed to reverse deduction: error occurs while updating order!'));
            }
            Db::commit();
            $hookParams = ['orderItem' => $orderItem, 'deductRecord' => $newDeductRecord];
            \think\Hook::listen(\app\admin\model\OrderItems::TAG_REVERSE_DEDUCT, $hookParams);
            $result['error'] = false;
            $result['msg']   = __('Operation completed');
        } catch (TransException $e) {
            Db::rollback();
            $result['msg'] = $e->getMessage();
        } catch (\think\Exception\PDOException $e) {
            Db::rollback();
            $result['msg'] = $e->getMessage();
        }

        return $result;
    }

    /**
     * 批量反划扣
     * 注意：所有操作，所有变更全部必须同时生效(如逻辑变更，changedOrderItems, deductRecordsMap相关需更改)
     * @param string $deductIds 待反划扣的 划扣记录ID
     * @param $adminId 操作人ID
     * @return array $result = ['error' => true, 'msg'   => 'Error occurs', 'data'  => [], ];
     */
    public static function batchReverseDeduct($deductIds, $adminId)
    {
        $result = [
            'error' => true,
            'msg'   => 'Error occurs',
            'data'  => [],
        ];

        if (empty($deductIds) || !is_array($deductIds)) {
            $result['msg'] = __('Parameter %s is empty or Invalid!');
            return $result;
        }

        $subSql       = static::where(['id' => ['in', $deductIds]])->buildSql();
        //内连接订单项表，获取订单项类型，自动过滤掉订单项不存在的数据
        $deductRecords = static::table($subSql . ' deduct_records')
            ->join(model('OrderItems')->getTable() . ' order_items', 'deduct_records.order_item_id = order_items.item_id', 'INNER')
            // ->field('deduct_records.*, order_items.item_type, order_items.customer_id')
            ->order('deduct_records.order_item_id', 'ASC')
            ->column('deduct_records.*, order_items.item_type, order_items.customer_id', 'deduct_records.id');
        //按订单划分的变动次数及变动金额
        $changedOrderItems = static::table($subSql . ' deduct_records')
            ->join(model('OrderItems')->getTable() . ' order_items', 'deduct_records.order_item_id = order_items.item_id', 'INNER')
            ->group('order_items.item_id')
            ->column('SUM(deduct_times) AS deduct_times, SUM(deduct_amount) AS deduct_amount', 'order_items.item_id');

        //原划扣人员提成记录
        $deductStaffRecords    = model('DeductStaffRecords')
            ->where(['deduct_record_id' => ['in', $deductIds]])
            ->column('*');
        //$deductRecordsMap 划扣-反划扣 ID 对照表
        $deductRecordsMap = array();

        $hookParams  = array();
        try {
            Db::startTrans();

            foreach ($deductRecords as $key => $deductRecord) {
                if ($deductRecord['item_type'] == \app\admin\model\Project::TYPE_PROJECT) {
                    if ($deductRecord['status'] != DeductRecords::STATUS_COMPLETED) {
                        throw new TransException('[ID: ' . $deductRecord['id'] . '] ' . __('This deduct record could not be reversed: incorrect status!'));
                    }
                } else {
                    if ($deductRecord['status'] == static::STATUS_COMPLETED) {
                        throw new TransException('[ID: ' . $deductRecord['id'] . '] ' . __('This deduct record could not be reversed: plz reverse delivering first!'));
                    } elseif ($deductRecord['status'] != static::STATUS_PENGING) {
                        throw new TransException('[ID: ' . $deductRecord['id'] . '] ' . __('This deduct record could not be reversed: incorrect status!'));
                    }
                }

                //反划扣记录生成及保存
                $newDeductRecord                        = new static;
                $newDeductRecord->order_item_id         = $deductRecord['order_item_id'];
                $newDeductRecord->deduct_times          = -$deductRecord['deduct_times'];
                $newDeductRecord->deduct_amount         = -$deductRecord['deduct_amount'];
                $newDeductRecord->deduct_benefit_amount = -$deductRecord['deduct_benefit_amount'];
                //反划扣
                $newDeductRecord->status   = static::STATUS_REVERSE;
                $newDeductRecord->admin_id = $adminId;
                //存储原划扣记录ID
                $newDeductRecord->old_record_id = $deductRecord['id'];
                $newDeductRecord->stat_date     = date('Y-m-d');
                if ($newDeductRecord->save() === false) {
                    throw new TransException(__('Failed to reverse deduction: error occurs while saving old deduction!'));
                }
                //更新 划扣-反划扣 ID映射
                $deductRecordsMap[$deductRecord['id']] = $newDeductRecord->id;

                if (!isset($hookParams[$deductRecord['customer_id']])) {
                    $hookParams[$deductRecord['customer_id']] = [
                                                                'orderItemIdArr' => [],
                                                                'reverseTotal' => 0.00,
                    ];
                }
                array_push($hookParams[$deductRecord['customer_id']]['orderItemIdArr'], $deductRecord['order_item_id']);
                $hookParams[$deductRecord['customer_id']]['reverseTotal'] += $newDeductRecord->deduct_amount;

                //更新 原划扣记录状态为 被反划扣
                $oldRecord = new static;
                if ($oldRecord->save(['status' => DeductRecords::STATUS_REVERSED], ['id' => $deductRecord['id']]) === false) {
                    throw new TransException(__('Failed to reverse deduction: error occurs while saving old deduction!'));
                }
            }
            //批量更改 划扣职员业绩
            foreach ($deductStaffRecords as $key => $oldDeductStaffRecord) {
                $newDeductStaffRecord                       = new DeductStaffRecords;
                $newDeductStaffRecord->deduct_record_id     = $deductRecordsMap[$oldDeductStaffRecord['deduct_record_id']];
                $newDeductStaffRecord->deduct_role_id       = $oldDeductStaffRecord['deduct_role_id'];
                $newDeductStaffRecord->admin_id             = $oldDeductStaffRecord['admin_id'];
                $newDeductStaffRecord->percent              = -$oldDeductStaffRecord['percent'];
                $newDeductStaffRecord->final_percent        = -$oldDeductStaffRecord['final_percent'];
                $newDeductStaffRecord->final_amount         = -$oldDeductStaffRecord['final_amount'];
                $newDeductStaffRecord->final_benefit_amount = -$oldDeductStaffRecord['final_benefit_amount'];
                //保存 职员划扣记录
                if ($newDeductStaffRecord->save() === false) {
                    throw new TransException(__('Failed to reverse deduction: error occurs while saving reverse deduction!'));
                }
            }
            //更新订单情况
            foreach ($changedOrderItems as $itemId => $row) {
                if (\app\admin\model\OrderItems::update(['item_status' => \app\admin\model\OrderItems::STATUS_PAYED, 'item_used_times' => ['exp', 'item_used_times - ' . $row['deduct_times']], 'item_undeducted_total' => ['exp', 'item_undeducted_total + ' . $row['deduct_amount']]], ['item_id' => $itemId]) == false) {
                    throw new TransException(__('Failed to reverse deduction: error occurs while updating order item\'s item_used_times!'));
                }
            }

            Db::commit();
            \think\Hook::listen(\app\admin\model\OrderItems::TAG_BATCH_REVERSE, $hookParams);
            $result['error'] = false;
            $result['msg']   = __('Operation completed');
        } catch (TransException $e) {
            Db::rollback();
            $result['msg'] = $e->getMessage();
        } catch (\think\Exception\PDOException $e) {
            Db::rollback();
            $result['msg'] = $e->getMessage();
        }

        return $result;
    }

    /**
     * 获取划扣记录列表总数及相关统计
     */
    public static function getRecordsCntNdSummary($where, $extraWhere, $includeSummary = false)
    {
        $cols = 'count(*) as count';
        if ($includeSummary) {
            $cols .= ', sum(deduct_records.deduct_times) as deduct_times, sum(deduct_records.deduct_amount) as deduct_total, sum(deduct_records.deduct_benefit_amount) as deduct_benefit_total';
        }

        $summarys = static::alias('deduct_records')
            ->join(\app\admin\model\OrderItems::getTable() . ' order_items', 'deduct_records.order_item_id = order_items.item_id', 'LEFT')
            ->join(\app\admin\model\Customer::getTable() . ' customer', 'order_items.customer_id = customer.ctm_id', 'LEFT')
            ->join(\app\admin\model\Project::getTable() . ' project', 'order_items.pro_id = project.pro_id', 'LEFT')
            ->join(\app\admin\model\CustomerOsconsult::getTable() . ' coc', 'order_items.osconsult_id = coc.osc_id', 'LEFT')
            ->where($where)
            ->where($extraWhere)
            ->limit(1)
            ->column($cols);

        $summary = current($summarys);
        if (is_array($summary)) {
            foreach ($summary as $key => $value) {
                if (is_null($value)) {
                    $summary[$key] = 0.00;
                } else {
                    $summary[$key] = floor($value * 100) / 100;
                }
            }
        } else {
            $count = $summary;
            $summary = array();
            $summary['count'] = $count;
        }

        return $summary;
    }

    /**
     * 获取划扣记录列表，包含顾客，现场客服等信息
     * , $includeStaffInfo = false
     */
    public static function getRecords($where, $sort, $order, $offset, $limit, $extraWhere)
    {
        $records = static::alias('deduct_records')
            ->join(\app\admin\model\OrderItems::getTable() . ' order_items', 'deduct_records.order_item_id = order_items.item_id', 'LEFT')
            ->join(\app\admin\model\Customer::getTable() . ' customer', 'order_items.customer_id = customer.ctm_id', 'LEFT')
            ->join(\app\admin\model\Project::getTable() . ' project', 'order_items.pro_id = project.pro_id', 'LEFT')
            ->join(\app\admin\model\CustomerOsconsult::getTable() . ' coc', 'order_items.osconsult_id = coc.osc_id', 'LEFT')
            ->where($where)
            ->where($extraWhere)  
            ->order($sort, $order)
            ->limit($offset, $limit)
            ->column('deduct_records.*, order_items.dept_id, order_items.pro_name as item_name, item_total, item_total_times, item_qty, item_cost, order_items.item_type, order_items.pro_unit as item_unit, order_items.pro_spec as item_spec, customer.ctm_name, customer.ctm_source, customer.ctm_explore, customer.ctm_id, customer.admin_id as develop_admin_id, order_items.osconsult_id, order_items.consult_admin_id as consult_admin_id, order_items.admin_id as osconsult_admin_id, order_items.recept_admin_id, order_items.item_paytime, order_items.prescriber, project.pro_cat1, project.pro_cat2, project.pro_cat3, project.pro_fee_type, coc.osc_type', 'id');

        return $records;
    }
}
