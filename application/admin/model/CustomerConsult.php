<?php

namespace app\admin\model;

use think\Model;
use think\Db;

class Customerconsult extends Model
{
    // 表名
    protected $name = 'customer_consult';

    protected $pk = 'cst_id';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    
    // 追加属性
    protected $append = [

    ];

    const TAG_SAVE_CONSULT = 'customer_consult_save';

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
    //             ->where($secCondition)
    //             ->count();

    //     return $total;
    // }

    /**
     * 获取列表
     */
    // public function getList($where, $sort, $order, $offset, $limit, $secCondition = [])
    // {
    //     //Customerosconsult表子查询
    //     $subQuery = $this->where($where)
    //                 ->where($secCondition)
    //                 ->buildSql();

    //     // Db::table($subQuery)->alias('cst')
    //     $list = $this->table($subQuery . ' cst')
    //                 ->field('cst.*, fat.fat_name, admin.nickname as admin_nickname, customer.ctm_name, customer.ctm_mobile, customer.arrive_status, cproject.cpdt_name,customer.ctm_id,coc.createtime as coctime')
    //                 ->join(Db::getTable('admin') . ' admin', 'cst.admin_id=admin.id', 'LEFT')
    //                 ->join(Db::getTable('fat') . ' fat', 'cst.fat_id=fat.fat_id', 'LEFT')
    //                 ->join(Db::getTable('customer') . ' customer', 'cst.customer_id=customer.ctm_id', 'LEFT')
    //                 ->join(Db::getTable('c_project') . ' cproject', 'cst.cpdt_id=cproject.id', 'LEFT')
    //                 ->join(Db::getTable('customer_osconsult') . ' coc', 'cst.customer_id=coc.customer_id', 'LEFT')
    //                 ->order($sort, $order)
    //                 ->limit($offset, $limit)
    //                 ->select();

    //     return $list;
    // }
    
    /**
     * 获取列表2
     * @param array $mainTableWhere 主青WHERE条件
     */
    public function getListCount($mainWhere, $extraWhere = [])
    {
        $total = static::alias('cst')
            ->join(Db::getTable('admin') . ' admin', 'cst.admin_id=admin.id', 'LEFT')
            ->join(Db::getTable('fat') . ' fat', 'cst.fat_id=fat.fat_id', 'LEFT')
            ->join(Db::getTable('customer') . ' customer', 'cst.customer_id=customer.ctm_id', 'LEFT')
            ->join(Db::getTable('c_project') . ' cproject', 'cst.cpdt_id=cproject.id', 'LEFT')
            ->join(Db::getTable('ctmsource') . ' sce', 'customer.ctm_source=sce.sce_id', 'LEFT')
            ->join(Db::getTable('ctmchannels') . ' channels', 'customer.ctm_explore=channels.chn_id', 'LEFT')
            // ->join(Db::getTable('customer_osconsult') . ' coc', 'cst.cst_id=coc.consult_id', 'LEFT')
            ->where($mainWhere)
            ->where($extraWhere)
        // ->order($sort, $order)
        // ->limit($offset, $limit)
            ->count();

        return $total;
    }
    
    public function getList($mainWhere, $sort, $order, $offset, $limit, $extraWhere = [])
    {
        // coc.dept_id as coc_dept_id,coc.createtime as coctime , coc.admin_id as coc_admin_id')
        // $list = $this->table($subQuery . ' cst')
        $list = static::alias('cst')
            ->field('cst.*, fat.fat_name, admin.nickname as admin_nickname, admin.dept_id as cst_admin_dept_id, cst.dept_id,  customer.ctm_name, customer.ctm_mobile, customer.arrive_status, customer.ctm_addr, cproject.cpdt_name,customer.ctm_id, customer.admin_id as develop_admin_id, sce.sce_name as ctm_source, channels.chn_name as ctm_explore,
                customer.ctm_last_osc_dept_id as coc_dept_id, customer.ctm_last_recept_time as coctime, customer.ctm_last_osc_admin as coc_admin_id' 
                    )
                    ->join(Db::getTable('admin') . ' admin', 'cst.admin_id=admin.id', 'LEFT')
                    ->join(Db::getTable('fat') . ' fat', 'cst.fat_id=fat.fat_id', 'LEFT')
                    ->join(Db::getTable('customer') . ' customer', 'cst.customer_id=customer.ctm_id', 'LEFT')
                    ->join(Db::getTable('c_project') . ' cproject', 'cst.cpdt_id=cproject.id', 'LEFT')
                    ->join(Db::getTable('ctmsource') . ' sce', 'customer.ctm_source=sce.sce_id', 'LEFT')
                    ->join(Db::getTable('ctmchannels') . ' channels', 'customer.ctm_explore=channels.chn_id', 'LEFT')
                    // ->join(Db::getTable('customer_osconsult') . ' coc', 'cst.cst_id=coc.consult_id', 'LEFT')
            ->where($mainWhere)
            ->where($extraWhere)
            ->order($sort, $order)
            ->limit($offset, $limit)
            ->select();

        //客服科室
        // $deptment = Deptment::getDeptListCache();
        // $briefAdminList = model('Admin')->getBriefAdminList2();
        $adminM = new Admin;
        $briefAdminList = $adminM->getBriefAdminList2();
        $deptment = (new \app\admin\model\Deptment)->getDeptListCache();

        foreach($list as $key => $row){
            if (!empty($deptment[$list[$key]['dept_id']])) {
                $list[$key]['dept_id'] = $deptment[$list[$key]['dept_id']]['dept_name'];
            }
            if (!empty($briefAdminList[$list[$key]['coc_admin_id']])){
                $list[$key]['coc_admin_id'] = $briefAdminList[$list[$key]['coc_admin_id']];
            }
            $list[$key]['develop_staff_name'] = '自然到诊';
            if (!empty($briefAdminList[$list[$key]['develop_admin_id']])){
                $list[$key]['develop_staff_name'] = $briefAdminList[$list[$key]['develop_admin_id']];
            }

        }

        return $list;
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
        $list = $this->table($subQuery . ' cst');
        if ($joinTable) 
        {
            $list = $list->field('cst.*, fat.fat_name, admin.nickname as admin_nickname, customer.ctm_name, cproject.cpdt_name')
                    ->join(Db::getTable('admin') . ' admin', 'cst.admin_id=admin.id', 'LEFT')
                    ->join(Db::getTable('fat') . ' fat', 'cst.fat_id=fat.fat_id', 'LEFT')
                    ->join(Db::getTable('customer') . ' customer', 'cst.customer_id=customer.ctm_id', 'LEFT')
                    ->join(Db::getTable('c_project') . ' cproject', 'cst.cpdt_id=cproject.id', 'LEFT');
        } else {
            $list = $list->field('cst.*');
        }

        $list = $list->where($where)
                    ->limit(0, 1)
                    ->select();
        if ($list) {
            $obj = $list[0];
        }

        return $obj;
    }

    public function customer()
    {
        return $this->belongsTo('Customer', 'customer_id', 'ctm_id');
    }
}
