<?php

namespace app\admin\model;

use app\admin\model\AccountLog;
use app\admin\model\CtmZptDRec;
use app\admin\model\Gender;
use app\admin\model\Job;
use fast\Random;
use think\Db;
use think\Model;

class Customer extends Model
{
    // 表名
    protected $name = 'customer';

    protected $pk = 'ctm_id';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    protected $hidden = ['ctm_pass'];

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    // 追加属性
    protected $append = [
    ];

    public static $maskFields      = ['ctm_mobile', 'ctm_tel'];
    public static $fieldMaskSymbol = '*';
    public static $fieldMaskLength = 4;

    public function fade(array $data = array())
    {
        $customer             = array();
        $customer['ctm_id']   = '';
        $customer['ctm_pass'] = Random::alpha(8);

        $customer['ctm_name']      = '';
        $customer['ctm_sex']       = 1;
        $customer['ctm_birthdate'] = '';
        $customer['ctm_mobile']    = '';
        $customer['ctm_tel']       = '';

        $customer['ctm_zip']  = '';
        $customer['ctm_addr'] = is_null(\think\Config::get('site.default_addr')) ? '' : \think\Config::get('site.default_addr');

        $customer['ctm_email'] = '';

        $customer['ctm_ifbirth']   = 1;
        $customer['ctm_ifrevmail'] = 1;

        $customer['ctm_explore'] = '';
        $customer['ctm_source']  = '';
        $customer['ctm_company'] = '';
        $customer['ctm_job']     = '';

        $customer['ctm_remark'] = '';

        $customer['ctm_rank_points'] = 0;
        $customer['ctm_pay_points']  = 0;

        $customer['ctm_depositamt'] = 0;
        $customer['ctm_psumamt']    = 0;
        $customer['ctm_salamt']     = 0;
        $customer['ctm_discamt']    = 0;
        $customer['ctm_coupamt']    = 0;

        $customer['ctm_wxid'] = '';
        $customer['ctm_qq']   = '';

        $customer['ctm_first_search'] = '';
        $customer['rec_customer_id']  = 0;

        $data = array_merge($customer, $data);

        $this->data($data);

        return $this;
    }

    /**
     * 消费金额等禁止直接修改, 参数过滤
     */
    public function saveWhenConsult($data = array(), $where = [], $sequence = null)
    {
        $protectedFields = array('ctm_depositamt', 'ctm_psumamt', 'ctm_salamt', 'ctm_discamt', 'ctm_coupamt', 'ctm_pass');
        foreach ($protectedFields as $key => $field) {
            if (isset($data[$field])) {
                unset($data[$field]);
            }
        }

        // return parent::save($data, $where, $sequence);
        return $this->save($data, $where, $sequence);
    }

    /**
     * 检查并保存顾客信息
     * @param $customerParams array
     * @param $adminId array
     * @return ['error' => true/false, 'msg' => $msg, 'customer_id' => $customer_id/null]
     */
    public function checkNdSave(array $customerParams, $adminId = null)
    {
        // $adminId = @intval($adminId);
        $where  = [];
        $result = [
            'error'       => true,
            'msg'         => __('Error occurs'),
            'customer_id' => null,
        ];

        if (!empty($customerParams['ctm_id'])) {
            $oldCustomer = model('Customer')->get(['ctm_id' => $customerParams['ctm_id']]);
            if (empty($oldCustomer)) {
                $result['msg'] = __('Customer %s does not exist.', $customerParams['ctm_id']);
                return $result;
            }

            //对于已有记录的客户，如手机/电话有记录，阻止修改
            if ($oldCustomer['ctm_mobile'] && isset($customerParams['ctm_mobile'])) {
                // unset($customerParams['ctm_mobile']);
                $customerParams['ctm_mobile'] = $oldCustomer['ctm_mobile'];
            }

            //阻止修改电话号码
            if ($oldCustomer['ctm_tel'] && isset($customerParams['ctm_tel'])) {
                // unset($customerParams['ctm_tel']);
                $customerParams['ctm_tel'] = $oldCustomer['ctm_tel'];
            }

            $where = ['ctm_id' => $customerParams['ctm_id']];
        } else {
            //新增客户
            if ($adminId) {
                $customerParams['admin_id'] = $adminId;
            }

            //防止手机号码是其他客户的手机或电话
            if (!empty($customerParams['ctm_mobile'])) {
                $samePhoneCustomer = $this->where(['ctm_mobile' => $customerParams['ctm_mobile']])->whereOr(['ctm_tel' => $customerParams['ctm_mobile']])->order('ctm_id', 'DESC')->find();
                if ($samePhoneCustomer != null) {
                    $result['msg'] = __('Customer with phone(%s) exist, customer id is %s', $samePhoneCustomer->ctm_mobile, $samePhoneCustomer->ctm_id);
                    return $result;
                }
            }
            //防止联系电话是其他客户的手机或电话
            if (!empty($customerParams['ctm_tel'])) {
                $sameTELCustomer = $this->where(['ctm_tel' => $customerParams['ctm_tel']])->where(['ctm_mobile' => $customerParams['ctm_tel']])->order('ctm_id', 'DESC')->find();
                if ($sameTELCustomer != null) {
                    $result['msg'] = __('Customer with tel(%s) exist, customer id is %s', $sameTELCustomer->ctm_mobile, $sameTELCustomer->ctm_id);
                    return $result;
                }
            }

        }

        // 保存客户信息失败， 注意 ===
        if (($saveRes = $this->saveWhenConsult($customerParams, $where)) === false) {
            $result['msg'] = __('Failed while trying to save customer data！');
            return $result;
        } else {
            //获取 新增/更新 的顾客ID
            if ($customerParams['ctm_id']) {
                $customerId = $customerParams['ctm_id'];
            } else {
                $customerId = $this->getLastInsID();
            }
            $result['error']       = false;
            $result['msg']         = __('Customer has been saved successfully.');
            $result['customer_id'] = $customerId;
            return $result;
        }
    }

    /**
     * 检查并保存顾客信息(客户号码可以更改)
     * @param $customerParams array
     * @param $adminId array
     * @return ['error' => true/false, 'msg' => $msg, 'customer_id' => $customer_id/null]
     */
    public function checkSave(array $customerParams, $adminId)
    {
        $adminId = @intval($adminId);
        $where   = [];
        $result  = [
            'error'       => true,
            'msg'         => __('Error occurs'),
            'customer_id' => null,
        ];

        if (!empty($customerParams['ctm_id'])) {
            $oldCustomer = model('Customer')->get(['ctm_id' => $customerParams['ctm_id']]);
            if (empty($oldCustomer)) {
                $result['msg'] = __('Customer %s does not exist.', $customerParams['ctm_id']);
                return $result;
            }
            if ($oldCustomer['ctm_mobile'] && isset($customerParams['ctm_mobile'])) {
                // unset($customerParams['ctm_mobile']);
                $customerParams['ctm_mobile'] = $oldCustomer['ctm_mobile'];
            }

            // if ($oldCustomer['ctm_tel'] && isset($customerParams['ctm_tel'])) {
            //     // unset($customerParams['ctm_mobile']);
            //     $customerParams['ctm_tel'] = $oldCustomer['ctm_tel'];
            // }

            $where = ['ctm_id' => $customerParams['ctm_id']];
        } else {
            //新增客户
            $customerParams['admin_id'] = $adminId;
            //防止手机号码是其他客户的手机或电话
            if (!empty($customerParams['ctm_mobile'])) {
                $samePhoneCustomer = $this->where(['ctm_mobile' => $customerParams['ctm_mobile']])->whereOr(['ctm_tel' => $customerParams['ctm_mobile']])->order('ctm_id', 'DESC')->find();
                if ($samePhoneCustomer != null) {
                    $result['msg'] = __('Customer with phone(%s) exist, customer id is %s', $samePhoneCustomer->ctm_mobile, $samePhoneCustomer->ctm_id);
                    return $result;
                }
            }
            //防止联系电话是其他客户的手机或电话
            if (!empty($customerParams['ctm_tel'])) {
                $sameTELCustomer = $this->where(['ctm_tel' => $customerParams['ctm_tel']])->where(['ctm_mobile' => $customerParams['ctm_tel']])->order('ctm_id', 'DESC')->find();
                if ($sameTELCustomer != null) {
                    $result['msg'] = __('Customer with tel(%s) exist, customer id is %s', $sameTELCustomer->ctm_mobile, $sameTELCustomer->ctm_id);
                    return $result;
                }
            }
        }

        // 保存客户信息失败， 注意 ===
        if (($saveRes = $this->saveWhenConsult($customerParams, $where)) === false) {
            $result['msg'] = __('Failed while trying to save customer data！');
            return $result;
        } else {
            //获取 新增/更新 的顾客ID
            if ($customerParams['ctm_id']) {
                $customerId = $customerParams['ctm_id'];
            } else {
                $customerId = $this->getLastInsID();
            }
            $result['error']       = false;
            $result['msg']         = __('Customer has been saved successfully.');
            $result['customer_id'] = $customerId;
            return $result;
        }
    }

    /**
     * 获取列表总数
     * @param mixed $where 基本查询条件
     * @param string $sort
     * @param string $order
     * @param array $secCondition
     */
    // public function getListCount($where, $secCondition = [])
    // {
    //     $total = $this->where($where)
    //         ->where($secCondition)
    //         ->count();

    //     return $total;
    // }

    /**
     * 获取列表
     */
    public function getListCount($mainTableWhere, $extraWhere = [])
    {
        //Customerosconsult表子查询
        // $subQuery = $this->where($mainTableWhere)
        //     ->buildSql();
        // $this->table($subQuery . ' customer')
        $includeExtraTable = false;
        if (!empty($extraWhere)) {
            foreach ($extraWhere as $key => $value) {
                if (stripos($key, 'order_items.') !== false || stripos($key, 'project.') !== false) {
                    $includeExtraTable = true;
                    break;
                }
            }
        }

        if ($includeExtraTable) {
            $total = self::alias('customer')
                ->join(Db::getTable('ctmsource') . ' sce', 'customer.ctm_source=sce.sce_id', 'LEFT')
                ->join(Db::getTable('ctmchannels') . ' channels', 'customer.ctm_explore=channels.chn_id', 'LEFT')
                ->join(Db::getTable('admin') . ' admin', 'customer.admin_id=admin.id', 'LEFT')
                ->join(Db::getTable('order_items') . ' order_items', 'customer.ctm_id=order_items.customer_id', 'INNER')
                ->join(Db::getTable('project') . ' project', 'order_items.pro_id=project.pro_id', 'LEFT')
                ->group('customer.ctm_id')
                ->where($mainTableWhere)
                ->where($extraWhere)
                ->count();
        } else {
            $total = self::alias('customer')
                ->join(Db::getTable('ctmsource') . ' sce', 'customer.ctm_source=sce.sce_id', 'LEFT')
                ->join(Db::getTable('ctmchannels') . ' channels', 'customer.ctm_explore=channels.chn_id', 'LEFT')
                ->join(Db::getTable('admin') . ' admin', 'customer.admin_id=admin.id', 'LEFT')
                ->where($mainTableWhere)
                ->where($extraWhere)
                ->count();
        }

        return $total;
    }

    public function getList($mainTableWhere, $sort, $order, $offset, $limit, $extraWhere = [])
    {
        //Customerosconsult表子查询
        // $subQuery = $this->where($mainTableWhere)
        //     // ->order($sort, $order)
        //     // ->limit($offset, $limit)
        //     ->buildSql();
        // $this->table($subQuery . ' customer')

        //match pattern project. order_item.
        $includeExtraTable = false;
        if (!empty($extraWhere)) {
            foreach ($extraWhere as $key => $value) {
                if (stripos($key, 'order_items.') !== false || stripos($key, 'project.') !== false) {
                    $includeExtraTable = true;
                    break;
                }
            }
        }

        if ($includeExtraTable) {
            $list = self::alias('customer')
                ->field('customer.*, sce.sce_name as ctm_source, channels.chn_name as ctm_explore, admin.dept_id as dept_id')
                ->join(Db::getTable('ctmsource') . ' sce', 'customer.ctm_source=sce.sce_id', 'LEFT')
                ->join(Db::getTable('ctmchannels') . ' channels', 'customer.ctm_explore=channels.chn_id', 'LEFT')
                ->join(Db::getTable('admin') . ' admin', 'customer.admin_id=admin.id', 'LEFT')
                ->join(Db::getTable('order_items') . ' order_items', 'customer.ctm_id=order_items.customer_id', 'INNER')
                ->join(Db::getTable('project') . ' project', 'order_items.pro_id=project.pro_id', 'LEFT')
                ->group('customer.ctm_id')
                ->where($mainTableWhere)
                ->where($extraWhere)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
        } else {
            $list = self::alias('customer')
                ->field('customer.*, sce.sce_name as ctm_source, channels.chn_name as ctm_explore, admin.dept_id as dept_id')
                ->join(Db::getTable('ctmsource') . ' sce', 'customer.ctm_source=sce.sce_id', 'LEFT')
                ->join(Db::getTable('ctmchannels') . ' channels', 'customer.ctm_explore=channels.chn_id', 'LEFT')
                ->join(Db::getTable('admin') . ' admin', 'customer.admin_id=admin.id', 'LEFT')
                ->where($mainTableWhere)
                ->where($extraWhere)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
        }
        

        $jobs    = Job::getList();
        $genders = Gender::getList();

        if (PHP_SAPI != 'cli') {
            $auth         = \app\admin\library\Auth::instance();
            $isSuperAdmin = $auth->isSuperAdmin();
        } else {
            $isSuperAdmin = true;
        }

        $adminBriefList = model('admin/Admin')->getBriefAdminList();
        $cpdtList       = \app\admin\model\CProject::column('cpdt_name', 'id');

        if ($isSuperAdmin) {
            foreach ($list as $key => $row) {
                $list[$key]['ctm_job'] = isset($jobs[$row['ctm_job']]) ? $jobs[$row['ctm_job']] : '-';
                $list[$key]['ctm_sex'] = isset($genders[$row['ctm_sex']]) ? $genders[$row['ctm_sex']] : '';

                $list[$key]['developStaffName'] = __('Natural diagnosis');
                if (isset($adminBriefList[$list[$key]['admin_id']])) {
                    $list[$key]['developStaffName'] = $adminBriefList[$list[$key]['admin_id']];
                }

                $list[$key]['ctm_first_cpdt_name'] = '';
                if (isset($cpdtList[$row['ctm_first_cpdt_id']])) {
                    $list[$key]['ctm_first_cpdt_name'] = $cpdtList[$row['ctm_first_cpdt_id']];
                }
                $list[$key]['ctm_last_osc_cpdt_name'] = '';
                if (isset($cpdtList[$row['ctm_last_osc_cpdt_id']])) {
                    $list[$key]['ctm_last_osc_cpdt_name'] = $cpdtList[$row['ctm_last_osc_cpdt_id']];
                }

                $list[$key]['ctm_last_osc_admin_name'] = __('Natural diagnosis');
                if (isset($adminBriefList[$list[$key]['ctm_last_osc_admin']])) {
                    $list[$key]['ctm_last_osc_admin_name'] = $adminBriefList[$list[$key]['ctm_last_osc_admin']];
                }

                
            }
            unset($row);
        } else {
            foreach ($list as $key => $row) {
                $list[$key]['ctm_job'] = isset($jobs[$row['ctm_job']]) ? $jobs[$row['ctm_job']] : '-';
                $list[$key]['ctm_sex'] = @$genders[$row['ctm_sex']];

                foreach (self::$maskFields as $maskField) {
                    if (isset($list[$key][$maskField])) {
                        $list[$key][$maskField] = getMaskString($list[$key][$maskField], self::$fieldMaskSymbol, self::$fieldMaskLength);
                    }

                    $list[$key]['developStaffName'] = __('Natural diagnosis');
                    if (isset($adminBriefList[$list[$key]['admin_id']])) {
                        $list[$key]['developStaffName'] = $adminBriefList[$list[$key]['admin_id']];
                    }
                }
            }
            unset($row);
        }

        unset($cpdtList);
        unset($adminBriefList);
        return $list;
    }

    public function save($data = array(), $where = [], $sequence = null)
    {
        if (isset($data['ctm_pass'])) {
            if (strlen($data['ctm_pass']) > 0) {
                $data['ctm_pass'] = md5($data['ctm_pass']);
            } else {
                unset($data['ctm_pass']);
            }
        }

        if (!empty($data['province'])) {
            $data['ctm_addr'] = $data['province'];
            if (!empty($data['city']) && !empty($data['area'])) {
                $data['ctm_addr'] = implode('-', [$data['province'], $data['city'], $data['area']]);
            } else {
                if (!empty($data['city'])) {
                    $data['ctm_addr'] = implode('-', [$data['province'], $data['city']]);
                }
            }
        }
        if (isset($data['province'])) {
            unset($data['province']);
        }

        if (isset($data['city'])) {
            unset($data['city']);
        }

        if (isset($data['area'])) {
            unset($data['area']);
        }

        if (!empty($data['rec_customer_id'])) {
            $recCustomer = static::find($data['rec_customer_id']);
            //推荐人
            if (!empty($recCustomer)) {
                $data['sales1_id'] = $data['rec_customer_id'];
                if ($recCustomer->rec_customer_id > 0) {
                    $data['sales2_id'] = $recCustomer->rec_customer_id;
                }
            } else {
                unset($data['rec_customer_id']);
            }
        }

        return parent::save($data, $where, $sequence);
    }

    /**
     * 客户定金调整
     * ctm_depositamt
     * 转化为数字并取绝对值
     * 退款时注意 校验是否可以退款足够金额
     */
    public function changeDepositAmt($amount, $changeType = '', $balanceDate = null, $balanceRemark = '', $orderId = 0)
    {
        $amount = floatval($amount);
        if ($amount == 0) {
            return true;
        }
        $balanceRemark = strip_tags($balanceRemark);
        if (($this->ctm_depositamt + $amount) < 0) {
            return false;
        }
        // $this->log_account_change($amount, 0, 0, 0, 0, '', $changeType, $balanceRemark);
        return static::logAccountChange($this->ctm_id, $amount, 0, 0, 0, 0, 0, '', $changeType, $balanceRemark);
    }

    /**
     * 客户定金调整
     * ctm_depositamt
     * 转化为数字并取绝对值
     * 退款时注意 校验是否可以退款足够金额
     */
    public function changeDepositNdCoupon($depositAmt, $couponAmt, $changeType = '', $balanceDate = null, $balanceRemark = '', $orderId = 0)
    {
        $depositAmt = floatval($depositAmt);
        $couponAmt = floatval($couponAmt);
        if ($depositAmt == 0 && $couponAmt == 0) {
            return true;
        }
        $balanceRemark = strip_tags($balanceRemark);
        if (($this->ctm_depositamt + $depositAmt) < 0 || ($this->ctm_coupamt + $couponAmt) < 0) {
            return false;
        }
        return static::logAccountChange($this->ctm_id, $depositAmt, 0, 0, 0, 0, $couponAmt, '', $changeType, $balanceRemark);
    }

    /**
    * 遗留代码
    **/
    public function log_account_change($depositAmt = 0, $frozenDepositAmt = 0, $rankPoints = 0, $payPoints = 0, $affiliateAmt = 0, $changeTime = '', $changeType = '', $changeDesc = '', $ip = 'SYSTEM', $source = 'HIS', $syncTime = 0)
    {
        return static::logAccountChange($this->ctm_id, $depositAmt, $frozenDepositAmt, $rankPoints, $payPoints, $affiliateAmt, 0, $changeTime, $changeType, $changeDesc, $ip, $source, $syncTime);
    }

    public static function logAccountChange($customerId, $depositAmt = 0, $frozenDepositAmt = 0, $rankPoints = 0, $payPoints = 0, $affiliateAmt = 0, $couponAmt = 0, $changeTime = '', $changeType = '', $changeDesc = '', $ip = 'SYSTEM', $source = 'HIS', $syncTime = 0)
    {
        $depositAmt       = floatval($depositAmt);
        $frozenDepositAmt = floatval($frozenDepositAmt);
        $rankPoints       = intval($rankPoints);
        $payPoints        = intval($payPoints);
        $affiliateAmt     = floatval($affiliateAmt);
        $couponAmt        = floatval($couponAmt);

        //没有任意实际改动时不保存
        if ($depositAmt == 0 && $frozenDepositAmt == 0 && $rankPoints == 0 && $payPoints == 0 && $affiliateAmt == 0 && $couponAmt == 0) {
            return true;
        }

        $customerUpdateData = [
            'ctm_depositamt'        => ['exp', 'ctm_depositamt + ' . $depositAmt],
            'ctm_frozen_depositamt' => ['exp', 'ctm_frozen_depositamt + ' . $frozenDepositAmt],
            'ctm_rank_points'       => ['exp', 'ctm_rank_points + ' . $rankPoints],
            'ctm_pay_points'        => ['exp', 'ctm_pay_points + ' . $payPoints],
            'ctm_affiliate'         => ['exp', 'ctm_affiliate + ' . $affiliateAmt],
            'ctm_coupamt'           => ['exp', 'ctm_coupamt + ' . $couponAmt],

            'ctm_rank_points'       => ['exp', 'ctm_rank_points + ' . $rankPoints],
            'ctm_pay_points'        => ['exp', 'ctm_pay_points + ' . $payPoints],
        ];

        //至少影响了一条数据
        $sql = 'UPDATE ' . Customer::getTable() . ' SET  `ctm_depositamt` = `ctm_depositamt` + :depositAmt, `ctm_frozen_depositamt` = `ctm_frozen_depositamt` + :frozenDepositAmt, `ctm_rank_points` = `ctm_rank_points` + :rankPoints, `ctm_pay_points` = `ctm_pay_points` + :payPoints, `ctm_affiliate` = `ctm_affiliate` + :affiliateAmt, `ctm_coupamt` = `ctm_coupamt` + :couponAmt WHERE ctm_id = :customerId';

        if (Db::execute($sql, ['customerId' => $customerId, 'depositAmt' => $depositAmt, 'frozenDepositAmt' => $frozenDepositAmt, 'rankPoints' => $rankPoints, 'payPoints' => $payPoints, 'affiliateAmt' => $affiliateAmt, 'couponAmt' => $couponAmt])) {
            if (empty($changeTime)) {
                $changeTime = time();
            }
            $changeDate = date('Y-m-d', $changeTime);

            $accountLogData = [
                'customer_id'        => $customerId,
                'deposit_amt'        => $depositAmt,
                'frozen_deposit_amt' => $frozenDepositAmt,
                'rank_points'        => $rankPoints,
                'pay_points'         => $payPoints,
                'affiliate_amt'      => $affiliateAmt,
                'coupon_amt'         => $couponAmt,
                'change_time'        => $changeTime,
                'change_date'        => $changeDate,
                'change_desc'        => $changeDesc,
                'change_type'        => $changeType,
                'ip'                 => $ip,
                'source'             => $source,
                'sync_time'          => $syncTime,
            ];
            $accountLog = new AccountLog;
            $res        = (bool) ($accountLog->save($accountLogData));
            if ($res) {
                \think\hook::listen('customer_account_change', $accountLog);
            }
            return $res;
        }

        return false;
    }

    /**
     * 获取顾客宏脉订单数
     */
    public function getCusHMOrderListCount($where)
    {
        // "select * from  zsb_ctmzpt_d_rec where ctp_ctmcode='$ctf_ctmcode'"
        $total = CtmZptDRec::where(['ctp_ctmcode' => $this->old_ctm_code])->where($where)->count();
        return $total;
    }
    /**
     * 获取顾客宏脉订单列表
     */
    public function getCusHMOrderList($where, $sort, $order, $offset, $limit)
    {
        $list = CtmZptDRec::where(['ctp_ctmcode' => $this->old_ctm_code])
            ->where($where)
            ->order($sort, $order)
            ->limit($offset, $limit)
            ->select();

        return $list;
    }
    /**
     * 获取顾客宏脉订单汇总
     */
    public function getCusHMOrderListSummary($where)
    {
        $summarys = CtmZptDRec::where(['ctp_ctmcode' => $this->old_ctm_code])
            ->where($where)
            ->field("sum(case when ctp_zptcode = 'QYF' then 0 else cpy_account end) as cpy_account_total, sum(case when ctp_zptcode = 'QYF' then 0 else cpy_pay end) as cpy_pay_total")
            ->limit(1)
            ->select();

        $summary                      = $summarys[0];
        $summary['cpy_account_total'] = floatval($summary['cpy_account_total']);
        $summary['cpy_pay_total']     = floatval($summary['cpy_pay_total']);
        return $summary;
    }

    /**
     * 更新顾客消费金额
     */
    public function updateSalAmt($step, $lazyTime = 0)
    {
        $this->setInc('ctm_salamt', $step, $lazyTime);
    }
}
