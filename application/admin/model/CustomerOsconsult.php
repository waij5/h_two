<?php

namespace app\admin\model;

use app\admin\model\Admin;
use app\admin\model\OrderItems;
use think\Db;
use think\Model;

class CustomerOsconsult extends Model
{
    // 表名
    protected $name = 'customer_osconsult';

    protected $pk = 'osc_id';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    // 追加属性
    protected $append = [

    ];

    protected $validate = [
    ];

    const TAG_SAVE_OSCONSULT = 'customer_osconsult_save';

    /*
    客服状态
    0  待接受
    1 服务中，
    2 成功
    3 成功并已支付
    -1 拒绝 可重新指派
    -2 失败
     */
    const STATUS_PENDING       = 0;
    const STATUS_CONSULTING    = 1;
    const STATUS_SUCCESS       = 2;
    const STATUS_SUCCESS_PAYED = 3;
    const STATUS_REFUSED       = -1;
    const STATUS_FAIL          = -2;

    public static function getStatusList()
    {
        return [
            self::STATUS_PENDING       => __('Status_0'),
            self::STATUS_CONSULTING    => __('Status_1'),
            self::STATUS_SUCCESS       => __('Status_2'),
            self::STATUS_SUCCESS_PAYED => __('Status_3'),
            self::STATUS_REFUSED       => __('Status_m_1'),
            self::STATUS_FAIL          => __('Status_m_2'),
        ];
    }

    public static function getStatusTitle($status)
    {
        $statusArr   = self::getStatusList();
        $statusTitle = '';
        if (isset($statusArr[$status])) {
            $statusTitle = $statusArr[$status];
        }

        return $statusTitle;
    }

    /**
     * 获取列表总数2
     * @param mixed $where 基本查询条件
     * @param string $sort
     * @param string $order
     * @param array $secCondition
     */
    public function getListCount($mainWhere, $extraWhere = [])
    {
        $total = static::alias('coc')
        // ->field('coc.*, cst.cst_content, customer.ctm_name,customer.ctm_id, cpro.cpdt_name, customer.admin_id as develop_admin')
            ->join(Db::getTable('customer') . ' customer', 'customer.ctm_id=coc.customer_id', 'INNER')
            ->join(Db::getTable('customer_consult') . ' cst', 'coc.consult_id=cst.cst_id', 'LEFT')
            ->join(Db::getTable('c_project') . ' cpro', 'coc.cpdt_id=cpro.id', 'LEFT')
            ->join(Db::getTable('admin') . ' admin', 'customer.admin_id=admin.id', 'LEFT')
            ->join(Db::getTable('ctmsource') . ' sce', 'customer.ctm_source=sce.sce_id', 'LEFT')
            ->join(Db::getTable('ctmchannels') . ' channels', 'customer.ctm_explore=channels.chn_id', 'LEFT')
            ->where($mainWhere)
            ->where($extraWhere)
        // ->order($sort, $order)
        // ->limit($offset, $limit)
            ->count();

        return $total;
    }

    /**
     * 获取列表2
     * @param array $mainTableWhere 主青WHERE条件
     */
    public function getList($mainWhere, $sort, $order, $offset, $limit, $extraWhere = [])
    {
        //Customerosconsult表子查询
        $list = static::alias('coc')
            ->field('coc.*, cst.cst_content, cst.admin_id as cst_admin_id, cst.cpdt_id as cst_cpdt_id, cst.dept_id as cst_dept_id, customer.ctm_name, customer.ctm_mobile, customer.ctm_tel, customer.ctm_id, customer.ctm_sex, customer.ctm_source, customer.ctm_pay_points, customer.ctm_rank_points, cpro.cpdt_name, customer.admin_id as develop_admin, customer.ctm_first_tool_id, customer.createtime as ctm_createtime, customer.ctm_salamt, customer.ctm_depositamt, cst.tool_id, admin.dept_id as admin_dept, sce.sce_name as ctm_source, channels.chn_name as ctm_explore')
            ->join(Db::getTable('customer') . ' customer', 'customer.ctm_id=coc.customer_id', 'INNER')
            ->join(Db::getTable('customer_consult') . ' cst', 'coc.consult_id=cst.cst_id', 'LEFT')
            ->join(Db::getTable('c_project') . ' cpro', 'coc.cpdt_id=cpro.id', 'LEFT')
            ->join(Db::getTable('admin') . ' admin', 'customer.admin_id=admin.id', 'LEFT')
            ->join(Db::getTable('ctmsource') . ' sce', 'customer.ctm_source=sce.sce_id', 'LEFT')
            ->join(Db::getTable('ctmchannels') . ' channels', 'customer.ctm_explore=channels.chn_id', 'LEFT')
            ->where($mainWhere)
            ->where($extraWhere)
            ->order($sort, $order)
            ->limit($offset, $limit)
            ->select();

        //获取 网电/现场客服，收银人员 昵称 失败原因  客服科室
        $adminM    = new Admin;
        $adminList = $adminM->getBriefAdminList();
        $fatlists  = (new \app\admin\model\Fat)->column('fat_id,fat_name');
        $deptLists = (new \app\admin\model\Deptment)->getDeptListCache();
        $cproList = \app\admin\model\CProject::column('cpdt_name', 'id');
        foreach ($list as $key => $row) {
            $list[$key]['admin_name'] = '';
            if (isset($adminList[$row['admin_id']])) {
                // $list[$key]['admin_id'] = $adminList[$row['admin_id']];
                $list[$key]['admin_name'] = $adminList[$row['admin_id']];
            }

            $list[$key]['operator_name'] = '';
            if (isset($adminList[$row['operator']])) {
                $list[$key]['operator_name'] = $adminList[$row['operator']];
            }

            $list[$key]['develop_admin_name'] = __('Natural diagnosis');
            if (isset($adminList[$row['develop_admin']])) {
                $list[$key]['develop_admin_name'] = $adminList[$row['develop_admin']];
            }

            $list[$key]['fat_name'] = '';
            if ($row['fat_id'] && isset($fatlists[$row['fat_id']])) {
                $list[$key]['fat_name'] = $fatlists[$row['fat_id']];
            }
            $list[$key]['cst_dept_name'] = '';
            if (!empty($row['cst_dept_id']) && isset($deptLists[$row['cst_dept_id']])) {
                $list[$key]['cst_dept_name'] = $deptLists[$row['cst_dept_id']]['dept_name'];
            }
            $list[$key]['cst_cpdt_name'] = '';
            if (!empty($row['cst_cpdt_id']) && isset($cproList[$row['cst_cpdt_id']])) {
                $list[$key]['cst_cpdt_name'] = $cproList[$row['cst_cpdt_id']];
            }

            $list[$key]['dept_name'] = '';
            if ($row['dept_id'] && isset($deptLists[$row['dept_id']])) {
                $list[$key]['dept_name'] = $deptLists[$row['dept_id']]['dept_name'];
            }

            $list[$key]['admin_dept_name'] = '';
            if ($row['admin_dept'] && isset($deptLists[$row['admin_dept']])) {
                $list[$key]['admin_dept_name'] = $deptLists[$row['admin_dept']]['dept_name'];
            }

            $list[$key]['service_admin_name'] = '';
            if ($row['service_admin_id'] && isset($adminList[$row['service_admin_id']])) {
                $list[$key]['service_admin_name'] = $adminList[$row['service_admin_id']];
            }
        }

        return $list;
    }

    /**
     * 获取列表到诊统计
     * @param array $mainTableWhere 主表WHERE条件
     */
    public function getListTypeSummary($mainWhere, $extraWhere = [])
    {
        $summary = static::alias('coc')
            ->join(Db::getTable('customer') . ' customer', 'customer.ctm_id=coc.customer_id', 'LEFT')
            ->join(Db::getTable('customer_consult') . ' cst', 'coc.consult_id=cst.cst_id', 'LEFT')
            ->join(Db::getTable('c_project') . ' cpro', 'coc.cpdt_id=cpro.id', 'LEFT')
            ->where($mainWhere)
            ->where($extraWhere)
            ->group('coc.osc_type')
            ->column('count(*) as count', 'osc_type');

        $result = ['total-osc-type-all' => array_sum($summary)];

        $oscTypeList = \app\admin\model\Osctype::getList();
        foreach ($oscTypeList as $key => $value) {
            $result['total-osc-type-' . $key] = 0;
            if (isset($summary[$key])) {
                $result['total-osc-type-' . $key] = $summary[$key];
            }
        }

        return $result;
    }

    /**
     * 获取现场客服表记录，带查询及部门权限检查
     */
    public function getOne($where, $secCondition, $joinTable = false)
    {
        $obj = null;

        $subQuery = $this->where($where)
            ->where($secCondition)
            ->buildSql();
        $list = $this->table($subQuery . ' coc');
        if ($joinTable) {
            $list = $list->field('coc.*, cst.admin_id as consult_admin, cst.cst_content, customer.ctm_name')
                ->join(Db::getTable('customer') . ' customer', 'customer.ctm_id=coc.customer_id', 'LEFT')
                ->join(Db::getTable('customer_consult') . ' cst', 'coc.consult_id=cst.cst_id', 'LEFT');
        } else {
            $list = $list->field('coc.*');
        }

        $list = $list->where($where)
            ->limit(0, 1)
            ->select();
        if ($list) {
            $obj = $list[0];
        }

        return $obj;
    }

    /**
     * 获取对应客户
     */
    public function customer()
    {
        return $this->belongsTo('Customer', 'customer_id', 'ctm_id');
    }

    /**
     * 获取对应的预约
     */
    public function consult()
    {
        return $this->belongsTo('Customerconsult', 'consult_id', 'cst_id');
    }

    /**
     * 部门成功率统计
     * 不计算券面额，券使用额
     */
    public static function getSuccessStatistic($where)
    {
        if (!empty($where)) {
            $subQuery = static::where($where)->buildSql();
        } else {
            $subQuery = static::getTable();
        }

        //osc_status > 1, 2成功，3已成交
        $groupedData = static::alias('osc')
            ->where($where)->where(['osc.is_delete' => 0])->group('admin_id, osc_type')->order('admin_id', 'ASC')->field('admin_id, osc_type, COUNT(*) AS count, SUM(CASE WHEN osc_status > 1 THEN 1 ELSE 0 END) AS success_count')
            ->select();

        $bList = static::alias('osc')
            ->join(OrderItems::getTable() . ' order_items', 'osc.osc_id = order_items.osconsult_id', 'INNER')
            ->group('order_items.admin_id')
            ->where([
                'order_items.item_status' => ['in', [OrderItems::STATUS_PAYED, OrderItems::STATUS_COMPLETED]],
            ])
            ->where($where)
            ->where(['osc.is_delete' => 0])
            ->column('SUM(order_items.item_original_pay_total) AS pay_total, SUM(CASE WHEN osc_type = 1 THEN order_items.item_original_pay_total ELSE 0 END) AS first_v_total, SUM(CASE WHEN osc_type = 2 THEN order_items.item_original_pay_total ELSE 0 END) AS return_v_total, SUM(CASE WHEN osc_type = 3 THEN order_items.item_original_pay_total ELSE 0 END) AS reconsume_total, SUM(CASE WHEN osc_type = 4 THEN order_items.item_original_pay_total ELSE 0 END) AS review_v_total, SUM(CASE WHEN osc_type = 5 THEN order_items.item_original_pay_total ELSE 0 END) AS other_v_total, SUM(order_items.item_coupon_total) AS coupon_total, COUNT(DISTINCT order_items.customer_id) AS customer_count', 'order_items.admin_id');
            // item_pay_total

        return static::dealStatisticParams($groupedData, $bList);
    }

    /**
     * 营销人员部门成功率统计
     * 不计算券面额，券使用额
     */
    public static function getDevSuccessStatistic($where)
    {

        $subQuery = static::getTable();

        //osc_status > 1, 2成功，3已成交
        $groupedData = static::alias('osc')
            ->join(\app\admin\model\Customer::getTable() . ' customer', 'osc.customer_id = customer.ctm_id', 'LEFT')
            ->group('customer.admin_id, osc_type')->order('customer.admin_id', 'ASC')->field('customer.admin_id, osc_type, COUNT(*) AS count, SUM(CASE WHEN osc_status > 1 THEN 1 ELSE 0 END) AS success_count')
            ->where($where)
            ->select();

        $bList = static::alias('osc')
            ->join(OrderItems::getTable() . ' order_items', 'osc.osc_id = order_items.osconsult_id', 'INNER')
            ->join(\app\admin\model\Customer::getTable() . ' customer', 'order_items.customer_id = customer.ctm_id', 'LEFT')
            ->group('customer.admin_id')
            ->where([
                'order_items.item_status' => ['in', [OrderItems::STATUS_PAYED, OrderItems::STATUS_COMPLETED]],
            ])
            ->where($where)
            ->column('SUM(order_items.item_original_pay_total) AS pay_total, SUM(CASE WHEN osc_type = 1 THEN order_items.item_original_pay_total ELSE 0 END) AS first_v_total, SUM(CASE WHEN osc_type = 2 THEN order_items.item_original_pay_total ELSE 0 END) AS return_v_total, SUM(CASE WHEN osc_type = 3 THEN order_items.item_original_pay_total ELSE 0 END) AS reconsume_total, SUM(CASE WHEN osc_type = 4 THEN order_items.item_original_pay_total ELSE 0 END) AS review_v_total, SUM(CASE WHEN osc_type = 5 THEN order_items.item_original_pay_total ELSE 0 END) AS other_v_total,SUM(order_items.item_coupon_total) AS coupon_total, COUNT(DISTINCT order_items.customer_id) AS customer_count', 'customer.admin_id');

        return static::dealStatisticParams($groupedData, $bList);
    }

    private static function dealStatisticParams(&$groupedData, &$bList)
    {
        $briefAdminList = Admin::getAdminCache(Admin::ADMIN_BRIEF_CACHE_KEY);
        $subs           = array();
        $total          = array(
            'staffName'               => __('Summary'),
            //初诊
            'first_v_count'           => 0,
            'first_v_success_count'   => 0,
            'first_v_success_rate'    => 0.00,
            'first_v_total'           => 0.00,

            // 复诊
            'return_v_count'          => 0,
            'return_v_success_count'  => 0,
            'return_v_success_rate'   => 0.00,
            'return_v_total'          => 0.00,

            //再消费
            'reconsume_count'         => 0,
            'reconsume_success_count' => 0,
            'reconsume_success_rate'  => 0.00,
            'reconsume_total'         => 0.00,
            //复查
            'review_v_count'          => 0,
            'review_v_success_count'  => 0,
            'review_v_success_rate'   => 0.00,
            'review_v_total'          => 0.00,
            //其他
            'other_v_count'           => 0,
            'other_v_success_count'   => 0,
            'other_v_success_rate'    => 0.00,
            'other_v_total'           => 0.00,
            //总接诊----不包括复诊的人次数
            'reception_total'         => 0,
            'success_total'           => 0,
            'success_total_rate'      => 0.00,
            'reception_percent'       => '',
            //费用
            'consumption_total'       => 0.00,
            'percent'                 => '',
            'consumption_per_person'  => '',
        );
        //###attention SUM(order_nd_osc.deposit_change_amt) AS dbalance_total 订单定金变动值与客户定金变动相反，如是退款自动是负值，直接与订单原收银相加即是实际值（含券）
        if (!empty($bList)) {
            $total['consumption_total'] = array_sum(array_column($bList, 'pay_total'));

            $total['first_v_total']   = array_sum(array_column($bList, 'first_v_total'));
            $total['return_v_total']  = array_sum(array_column($bList, 'return_v_total'));
            $total['reconsume_total'] = array_sum(array_column($bList, 'reconsume_total'));
            $total['review_v_total']  = array_sum(array_column($bList, 'review_v_total'));
            $total['other_v_total']   = array_sum(array_column($bList, 'other_v_total'));
        }

        foreach ($groupedData as $key => $row) {
             // . '(ID:' . $row['admin_id'] . ')'
            if (!isset($subs[$row['admin_id']])) {
                $subs[$row['admin_id']] = array(
                    'staffName'               => (isset($briefAdminList[$row['admin_id']]) ? $briefAdminList[$row['admin_id']] : __('None')),
                    // 初诊
                    'first_v_count'           => 0,
                    'first_v_success_count'   => 0,
                    'first_v_success_rate'    => 0.00,
                    'first_v_total'           => 0.00,
                    // 复诊
                    'return_v_count'          => 0,
                    'return_v_success_count'  => 0,
                    'return_v_success_rate'   => 0.00,
                    'return_v_total'          => 0.00,
                    //再消费
                    'reconsume_count'         => 0,
                    'reconsume_success_count' => 0,
                    'reconsume_success_rate'  => 0.00,
                    'reconsume_total'         => 0.00,
                    //复查
                    'review_v_count'          => 0,
                    'review_v_success_count'  => 0,
                    'review_v_success_rate'   => 0.00,
                    'review_v_total'          => 0.00,
                    //其他
                    'other_v_count'           => 0,
                    'other_v_success_count'   => 0,
                    'other_v_success_rate'    => 0.00,
                    'other_v_total'           => 0.00,
                    //总接诊----不包括复诊的人次数
                    'reception_total'         => 0,
                    'success_total'           => 0,
                    'success_total_rate'      => 0.00,
                    'reception_percent'       => 0.00,
                    //费用
                    'consumption_total'       => 0.00,
                    'percent'                 => 0.00,
                    'consumption_per_person'  => 0.00,
                );
                if (isset($bList[$row['admin_id']])) {
                    $rowConsumptionTotal                       = $bList[$row['admin_id']]['pay_total'];
                    $subs[$row['admin_id']]['first_v_total']   = $bList[$row['admin_id']]['first_v_total'];
                    $subs[$row['admin_id']]['return_v_total']  = $bList[$row['admin_id']]['return_v_total'];
                    $subs[$row['admin_id']]['reconsume_total'] = $bList[$row['admin_id']]['reconsume_total'];
                    $subs[$row['admin_id']]['review_v_total']  = $bList[$row['admin_id']]['review_v_total'];
                    $subs[$row['admin_id']]['other_v_total']   = $bList[$row['admin_id']]['other_v_total'];

                    $subs[$row['admin_id']]['consumption_total'] = $rowConsumptionTotal;
                    if ($total['consumption_total'] > 0) {
                        $subs[$row['admin_id']]['percent'] = floor(10000 * $rowConsumptionTotal / $total['consumption_total']) / 100;
                    }
                    if ($bList[$row['admin_id']]['customer_count']) {
                        $subs[$row['admin_id']]['consumption_per_person'] = floor(100 * $rowConsumptionTotal / $bList[$row['admin_id']]['customer_count']) / 100;
                    }
                }
            }
            if ($row['osc_type'] == 1) {
                // '1' => '初诊'
                $subs[$row['admin_id']]['first_v_count'] += $row['count'];
                $subs[$row['admin_id']]['first_v_success_count'] += $row['success_count'];
                if ($subs[$row['admin_id']]['first_v_count'] > 0) {
                    $subs[$row['admin_id']]['first_v_success_rate'] = floor(10000 * $subs[$row['admin_id']]['first_v_success_count'] / $subs[$row['admin_id']]['first_v_count']) / 100;
                }

                $total['first_v_count'] += $row['count'];
                $total['first_v_success_count'] += $row['success_count'];
            } elseif ($row['osc_type'] == 2) {
                // '2' => '复诊',
                $subs[$row['admin_id']]['return_v_count'] += $row['count'];
                $subs[$row['admin_id']]['return_v_success_count'] += $row['success_count'];

                if ($subs[$row['admin_id']]['return_v_count'] > 0) {
                    $subs[$row['admin_id']]['return_v_success_rate'] = floor(10000 * $subs[$row['admin_id']]['return_v_success_count'] / $subs[$row['admin_id']]['return_v_count']) / 100;
                }

                $total['return_v_count'] += $row['count'];
                $total['return_v_success_count'] += $row['success_count'];
                // //部分统计不包括复诊，提前结束当前循环
                // continue;
            } elseif ($row['osc_type'] == 3) {
                // '3' => '再消费',
                $subs[$row['admin_id']]['reconsume_count'] += $row['count'];
                $subs[$row['admin_id']]['reconsume_success_count'] += $row['success_count'];
                if ($subs[$row['admin_id']]['reconsume_count'] > 0) {
                    $subs[$row['admin_id']]['reconsume_success_rate'] = floor(10000 * $subs[$row['admin_id']]['reconsume_success_count'] / $subs[$row['admin_id']]['reconsume_count']) / 100;
                }

                $total['reconsume_count'] += $row['count'];
                $total['reconsume_success_count'] += $row['success_count'];
            } elseif ($row['osc_type'] == 4) {
                // '4' => '复查'
                $subs[$row['admin_id']]['review_v_count'] += $row['count'];
                $subs[$row['admin_id']]['review_v_success_count'] += $row['success_count'];
                if ($subs[$row['admin_id']]['review_v_count'] > 0) {
                    $subs[$row['admin_id']]['review_v_success_rate'] = floor(10000 * $subs[$row['admin_id']]['review_v_success_count'] / $subs[$row['admin_id']]['review_v_count']) / 100;
                }

                $total['review_v_count'] += $row['count'];
                $total['review_v_success_count'] += $row['success_count'];

            } elseif ($row['osc_type'] == 5) {
                // '5' => '其他'
                $subs[$row['admin_id']]['other_v_count'] += $row['count'];
                $subs[$row['admin_id']]['other_v_success_count'] += $row['success_count'];
                if ($subs[$row['admin_id']]['other_v_count'] > 0) {
                    $subs[$row['admin_id']]['other_v_success_rate'] = floor(10000 * $subs[$row['admin_id']]['other_v_success_count'] / $subs[$row['admin_id']]['other_v_count']) / 100;
                }

                $total['other_v_count'] += $row['count'];
                $total['other_v_success_count'] += $row['success_count'];
            }

            $subs[$row['admin_id']]['reception_total'] += $row['count'];
            $subs[$row['admin_id']]['success_total'] += $row['success_count'];
            if ($subs[$row['admin_id']]['reception_total'] > 0) {
                $subs[$row['admin_id']]['success_total_rate'] = floor(10000.00 * $subs[$row['admin_id']]['success_total'] / $subs[$row['admin_id']]['reception_total']) / 100;
            }

            $total['reception_total'] += $row['count'];
            $total['success_total'] += $row['success_count'];
        }

        $receptionTotal = $total['reception_total'];
        if ($receptionTotal > 0) {
            foreach ($subs as $key => $row) {
                $subs[$key]['reception_percent'] = floor(10000 * $row['reception_total'] / $receptionTotal) / 100;
            }
        }

        if ($total['first_v_count'] > 0) {
            $total['first_v_success_rate'] = floor(10000 * $total['first_v_success_count'] / $total['first_v_count']) / 100;
        }
        if ($total['reconsume_count'] > 0) {
            $total['reconsume_success_rate'] = floor(10000 * $total['reconsume_success_count'] / $total['reconsume_count']) / 100;
        }
        if ($total['reception_total'] > 0) {
            $total['success_total_rate'] = floor(10000 * $total['success_total'] / $total['reception_total']) / 100;
        }

        return ['total' => $total, 'subs' => $subs];
    }
}
