<?php

namespace app\admin\model;

use app\admin\model\Admin;
use app\admin\model\Customer;
use app\admin\model\Fat;
use think\Model;

class Rvinfo extends Model
{
    // 表名
    protected $name = 'rvinfo';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;

    // 追加属性
    protected $append = [

    ];

    /**
     * 获取汇总信息
     */
    public static function getListSummary($where = [], $extraWhere = [])
    {
        if (empty($where)) {
            $query    = static::alias('rvinfo');
            $subQuery = static::getTable();
        } else {
            $subQuery = static::where($where)->buildSql();
            $query    = static::table($subQuery . ' rvinfo');
        }
        
        $summary = $query
            ->join(Customer::getTable() . ' customer', 'rvinfo.customer_id = customer.ctm_id', 'LEFT')
            ->join(Admin::getTable() . ' admin', 'rvinfo.admin_id = admin.id', 'LEFT')
            ->where($extraWhere)
            ->limit(1)
            ->column('count(*) as count, count(distinct customer_id) as customer_count, sum(case when (rv_time is not null) then 1 else 0 end) as visited_count');

        $summary = current($summary);
        foreach ($summary as $key => $value) {
            if (is_null($summary[$key])) {
                $summary[$key] = $value;
            }
        }

        // $filterWords = \app\admin\model\RevisitFilter::where(['filter_status' => 1])->select();
        // static::table($query . ' rvinfo')
        $avaiableArr = static::table($subQuery . ' rvinfo')
            ->join(Customer::getTable() . ' customer', 'rvinfo.customer_id = customer.ctm_id', 'LEFT')
            ->join(Admin::getTable() . ' admin', 'rvinfo.admin_id = admin.id', 'LEFT')
            ->where($extraWhere)
            ->where(['rv_is_valid' => 1])
        // ->where(function ($query2) use ($filterWords) {
        //     $query2->where(['rv_time' => ['notnull', true]])
        //         ->where(['rvi_content' => ['notnull', true]])
        //         ->where(['rvi_content' => ['neq', '']]);
        //     foreach ($filterWords as $filterWord) {
        //         $query2->where('rvi_content', 'notlike', '%' . $filterWord->filter_name . '%');
        //     }
        // })
        // ->count();
            ->limit(1)
            ->field('count(*) as avaiable_visited_count, count(distinct customer_id) as avaiable_visited_customer_count')
            ->select();
        $avaiableArr1 = current($avaiableArr);

        $summary['avaiable_visited_count']          = is_null($avaiableArr1['avaiable_visited_count']) ? 0 : $avaiableArr1['avaiable_visited_count'];
        $summary['avaiable_visited_customer_count'] = is_null($avaiableArr1['avaiable_visited_customer_count']) ? 0 : $avaiableArr1['avaiable_visited_customer_count'];

        return $summary;
    }

    /**
     * 获取列表数目
     * @return int $count 列表数
     */
    public static function getListCount($where = [], $extraWhere = [])
    {
        if (empty($where)) {
            $query = static::alias('rvinfo');
        } else {
            //解决遗留问题
            if (is_array($where)) {
                $pWhere = [];
                foreach ($where as $key => $value) {
                    if (strpos($key, 'rvinfo.') === false) {
                        $pWhere['rvinfo.' . $key] = $value;
                    } else {
                        $pWhere[$key] = $value;
                    }
                }
                $query    = static::alias('rvinfo')->where($pWhere);
            } else {
                $subQuery = static::where($where)->buildSql();
                $query    = static::table($subQuery . ' rvinfo');
            }
        }

        $count = $query
            ->join(Customer::getTable() . ' customer', 'rvinfo.customer_id = customer.ctm_id', 'LEFT')
            ->join(Admin::getTable() . ' admin', 'rvinfo.admin_id = admin.id', 'LEFT')
            ->where($extraWhere)
            ->count();

        return $count;
    }

    /**
     * 获取列表
     * @return array $list 列表
     */
    public static function getList($where, $sort, $order, $offset, $limit, $extraWhere = [], $isSuperAdmin = false)
    {
        $admin          = new Admin;
        $briefAdminList = $admin->getBriefAdminList();
        // $list = static::table($subQuery . ' rvinfo')

        if (empty($where)) {
            $query = static::alias('rvinfo');
        } else {
            //解决遗留问题
            if (is_array($where)) {
                $pWhere = [];
                foreach ($where as $key => $value) {
                    if (strpos($key, 'rvinfo.') === false) {
                        $pWhere['rvinfo.' . $key] = $value;
                    } else {
                        $pWhere[$key] = $value;
                    }
                }
                $query    = static::alias('rvinfo')->where($pWhere);
            } else {
                $subQuery = static::where($where)->buildSql();
                $query    = static::table($subQuery . ' rvinfo');
            }
        }

        $list = $query
            ->join(Customer::getTable() . ' customer', 'rvinfo.customer_id = customer.ctm_id', 'LEFT')
            ->join(Admin::getTable() . ' admin', 'rvinfo.admin_id = admin.id', 'LEFT')
            ->join(Fat::getTable() . ' fat', 'rvinfo.fat_id = fat.fat_id', 'LEFT')
            ->join(Deptment::getTable() . ' dept', 'admin.dept_id = dept.dept_id', 'LEFT')
            ->field('rvinfo.*, fat.fat_name, customer.ctm_name, customer.ctm_id, customer.arrive_status, customer.ctm_sex, customer.ctm_birthdate , customer.ctm_mobile, customer.ctm_depositamt, customer.ctm_tel, customer.ctm_source, customer.ctm_first_dept_id, customer.ctm_first_cpdt_id, customer.ctm_first_osc_cpdt_id, customer.ctm_first_osc_dept_id, customer.ctm_next_rvinfo, admin.nickname, admin.dept_id, dept.dept_name')
            ->where($extraWhere)
            ->order($sort, $order)
            ->limit($offset, $limit)
            ->select();

        $curYear    = date('Y');
        $todayStart = strtotime(date('Y-m-d'));
        $todayEnd   = strtotime(date('Y-m-d 23:59:59'));
        $cpdtList   = \app\admin\model\CProject::column('cpdt_name', 'id');
        $deptment   = \app\admin\model\Deptment::column('dept_name', 'dept_id');
        foreach ($list as $key => $row) {
            //亦可在FIELD处理
            $list[$key]['admin_name'] = '';
            if ($list[$key]['admin_id'] && isset($briefAdminList[$list[$key]['admin_id']])) {
                $list[$key]['admin_name'] = $briefAdminList[$list[$key]['admin_id']];
            }

            $list[$key]['resolve_admin_name'] = '';
            if ($list[$key]['resolve_admin_id'] && isset($briefAdminList[$list[$key]['resolve_admin_id']])) {
                $list[$key]['resolve_admin_name'] = $briefAdminList[$list[$key]['resolve_admin_id']];
            }

            $list[$key]['ctm_first_cpdt_name'] = '';
            if ($list[$key]['ctm_first_cpdt_id'] && isset($cpdtList[$list[$key]['ctm_first_cpdt_id']])) {
                $list[$key]['ctm_first_cpdt_name'] = $cpdtList[$list[$key]['ctm_first_cpdt_id']];
            }

            $list[$key]['ctm_first_dept_name'] = '';
            if ($list[$key]['ctm_first_dept_id'] && isset($deptment[$list[$key]['ctm_first_dept_id']])) {
                $list[$key]['ctm_first_dept_name'] = $deptment[$list[$key]['ctm_first_dept_id']];
            }

            $list[$key]['ctm_first_osc_cpdt_name'] = '';
            if ($list[$key]['ctm_first_osc_cpdt_id'] && isset($cpdtList[$list[$key]['ctm_first_osc_cpdt_id']])) {
                $list[$key]['ctm_first_osc_cpdt_name'] = $cpdtList[$list[$key]['ctm_first_osc_cpdt_id']];
            }

            $list[$key]['ctm_first_osc_dept_name'] = '';
            if ($list[$key]['ctm_first_osc_dept_id'] && isset($deptment[$list[$key]['ctm_first_osc_dept_id']])) {
                $list[$key]['ctm_first_osc_dept_name'] = $deptment[$list[$key]['ctm_first_osc_dept_id']];
            }

            // $list[$key]['ctm_age'] = '';
            // if ($list[$key]['ctm_birthdate'] != '0000-00-00 00:00:00') {
            //     $list[$key]['ctm_age'] = $curYear - intval(substr($list[$key]['ctm_birthdate'], 0, 4));
            // }
            $list[$key]['ctm_age'] = \calcAge($list[$key]['ctm_birthdate']);

            if (empty($row['rv_time']) || $isSuperAdmin) {
                $list[$key]['canEdit'] = true;
            } else {
                $list[$key]['canEdit'] = false;
            }
        }

        return $list;
    }
}
