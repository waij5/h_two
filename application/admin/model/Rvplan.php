<?php

namespace app\admin\model;

use think\Model;

class Rvplan extends Model
{
    // 表名
    protected $name = 'rvplan';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [

    ];

    /**
     * 获取列表总数
     * @param mixed $where 基本查询条件
     * @param string $sort
     * @param string $order
     * @param array $secCondition
     */
    public function getListCount($where, $extraCondition = [])
    {
        $total = $this->where($where)
                ->where($extraCondition)
                ->count();

        return $total;
    }

    /**
     * 获取列表
     */
    public function getList($where, $sort, $order, $offset, $limit, $extraCondition = [])
    {
        //Customerosconsult表子查询
        $subQuery = $this->where($where)
                    ->where($extraCondition)
                    ->buildSql();

        $list = $this->table($subQuery . ' rvplan')
            ->field('rvplan.*, rvtype.rvt_name as rvt_name')
            ->join(\think\Db::getTable('rvtype') . ' rvtype', 'rvplan.rvtype_id=rvtype.rvt_id', 'LEFT')
            ->order($sort, $order)
            ->limit($offset, $limit)
            ->select();

        return $list;
    }

    public function rvdays()
    {
        return $this->hasMany('Rvdays');
    }

    public function generatePlanInfos($customer, $adminId)
    {
        if ($this->rvp_status < 1) {
            // return false;
        }

        $rvtypeId = model('rvdays')->where(['rvplan_id' => $this->rvp_id])->column('rvtype_id');

        $rvTypeName = '--';
        $rvType = model('Rvtype')->find($rvtypeId);
        if ($rvType != null) {
            $rvTypeName = $rvType->rvt_name;
        }

        $rvdays = model('Rvdays')->alias('rv_days')
                    ->join(model('Rvtype')->getTable() . ' rv_type', 'rv_days.rvtype_id = rv_type.rvt_id', 'LEFT')
                    ->where(['rvplan_id' => $this->rvp_id, 'rvd_status' => 1])
                    ->column('rv_days.*, rv_type.rvt_name');

        //新增回访记录
        $curTime = time();
        foreach ($rvdays as $key => $daySet) {
            $rvDate = date('Y-m-d', strtotime('+' . $daySet['rvd_days'] . ' days', $curTime));
            $rvinfo = new Rvinfo();
            $rvinfo->rvi_tel = $customer->ctm_mobile;
            $rvinfo->customer_id = $customer->ctm_id;
            $rvinfo->rvt_type = is_null($daySet['rvt_name']) ? '' : $daySet['rvt_name'];//回访类型名
            $rvinfo->rv_plan = is_null($daySet['rvd_name']) ? $this->rvp_name : $this->rvp_name.'+'.$daySet['rvd_name'];
            // $rvinfo->rv_plan = $this->rvp_name.'+'.$daySet['rvd_name'];
            $rvinfo->rvi_content = '';
            $rvinfo->admin_id = $adminId;
            $rvinfo->rv_date = $rvDate;
            $rvinfo->rv_is_valid = 0;
            $rvinfo->save();
        }

        return true;
    }
}
