<?php

namespace app\admin\model;

use think\Db;

class ConsultStat
{
    /**
     * 按职员项目获取网电客服统计信息
     */
    public static function getListForStaff($where = [])
    {
        $list = model('Customerconsult')
                    ->alias('cst')
                    ->field('cst.admin_id, admin.username AS admin_username, admin.nickname AS admin_nickname, cpdt_id,cpdt_name, count(*) AS total, sum( CASE WHEN cst_status = 1 OR cst_status = 2 THEN 1 ELSE 0 END ) AS book_total, SUM( CASE WHEN cst_status = 2 THEN 1 ELSE 0 END ) AS arrive_total')
                    ->join(Db::getTable('c_project') . ' c_project', 'cst.cpdt_id = c_project.id', 'LEFT')
                    ->join(Db::getTable('admin') . ' admin', 'cst.admin_id = admin.id', 'LEFT')
                    ->join(Db::getTable('customer') . ' customer', 'customer.ctm_id = cst.customer_id', 'LEFT')
                    ->where($where)
                    ->group('admin_id, cpdt_id')
                    ->order('admin_id ASC, cpdt_id', 'ASC')
                    ->select();

        return $list;
    }

    /**
     * 按项目获取网电客服统计信息
     */
    public static function getListForProject($where = [])
    {
        $list = model('Customerconsult')
                    ->alias('cst')
                    ->field('cpdt_id,cpdt_name, count(*) AS total, sum( CASE WHEN cst_status = 1 OR cst_status = 2 THEN 1 ELSE 0 END ) AS book_total, SUM( CASE WHEN cst_status = 2 THEN 1 ELSE 0 END ) AS arrive_total')
                    ->join(Db::getTable('c_project') . ' c_project', 'cst.cpdt_id = c_project.id', 'LEFT')
                    ->join(Db::getTable('admin') . ' admin', 'cst.admin_id = admin.id', 'LEFT')
                    ->join(Db::getTable('customer') . ' customer', 'customer.ctm_id = cst.customer_id', 'LEFT')
                    ->where($where)
                    ->group('cpdt_id')
                    ->order('cpdt_id', 'ASC')
                    ->select();

        return $list;
    }

    /**
     * 获取条件筛选下 总计信息
     */
    public static function getSummary($where = [])
    {
        $summary = model('Customerconsult')
                        ->alias('cst')
                        ->field('count(*) AS total, sum( CASE WHEN cst_status = 1 OR cst_status = 2 THEN 1 ELSE 0 END ) AS book_total, SUM( CASE WHEN cst_status = 2 THEN 1 ELSE 0 END ) AS arrive_total')
                        ->join(Db::getTable('c_project') . ' c_project', 'cst.cpdt_id = c_project.id', 'LEFT')
                        ->join(Db::getTable('admin') . ' admin', 'cst.admin_id = admin.id', 'LEFT')
                        ->join(Db::getTable('customer') . ' customer', 'customer.ctm_id = cst.customer_id', 'LEFT')
                        ->where($where)
                        ->select();

        return $summary[0];
    }

}
