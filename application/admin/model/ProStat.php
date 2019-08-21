<?php

namespace app\admin\model;

use think\Model;

class ProStat extends Model
{
    // 表名
    protected $name = 'pro_stat';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    
    // 追加属性
    protected $append = [

    ];
    

    /**
     * 数据统计
     */
    public static function proDataStat($list, $catList)
    {
        //总计
        $total = [
                    'pstat_order_count' => 0,
                    'pstat_qty' => 0,
                    'pstat_local_total' => 0,
                    'pstat_ori_total' => 0,
                    'pstat_min_total' => 0,
                    'pstat_total' => 0,
        ];

        $data = array();
        foreach ($list as $key => $row) {
            //更新总计数据
            $total['pstat_order_count'] += $row->pstat_order_count;
            $total['pstat_qty'] += $row->pstat_qty;
            $total['pstat_local_total'] += $row->pstat_local_total;
            $total['pstat_ori_total'] += $row->pstat_ori_total;
            $total['pstat_min_total'] += $row->pstat_min_total;
            $total['pstat_total'] += $row->pstat_total;

            //更新一级分类统计数据
            if (!isset($data[$row->pro_cat1])) {
                $data[$row->pro_cat1] = array(
                                                'summary' => [
                                                                'cat_name' => @$catList[$row->pro_cat1]['name'],
                                                                'pstat_order_count' => 0,
                                                                'pstat_qty' => 0,
                                                                'pstat_local_total' => 0,
                                                                'pstat_ori_total' => 0,
                                                                'pstat_min_total' => 0,
                                                                'pstat_total' => 0,
                                                ],
                                                'sub' => [],
                );
            }

            $data[$row->pro_cat1]['summary']['pstat_order_count'] += $row->pstat_order_count;
            $data[$row->pro_cat1]['summary']['pstat_qty'] += $row->pstat_qty;
            $data[$row->pro_cat1]['summary']['pstat_local_total'] += $row->pstat_local_total;
            $data[$row->pro_cat1]['summary']['pstat_ori_total'] += $row->pstat_ori_total;
            $data[$row->pro_cat1]['summary']['pstat_min_total'] += $row->pstat_min_total;
            $data[$row->pro_cat1]['summary']['pstat_total'] += $row->pstat_total;

            //统计二级分类统计数据
            if (!isset($data[$row->pro_cat1]['sub'][$row->pro_cat2])) {
                $data[$row->pro_cat1]['sub'][$row->pro_cat2] = [
                                                                'cat_name' => @$catList[$row->pro_cat2]['name'],
                                                                'pstat_order_count' => 0,
                                                                'pstat_qty' => 0,
                                                                'pstat_local_total' => 0,
                                                                'pstat_ori_total' => 0,
                                                                'pstat_min_total' => 0,
                                                                'pstat_total' => 0,
                                                            ];
            }

            $data[$row->pro_cat1]['sub'][$row->pro_cat2]['pstat_order_count'] += $row->pstat_order_count;
            $data[$row->pro_cat1]['sub'][$row->pro_cat2]['pstat_qty'] += $row->pstat_qty;
            $data[$row->pro_cat1]['sub'][$row->pro_cat2]['pstat_local_total'] += $row->pstat_local_total;
            $data[$row->pro_cat1]['sub'][$row->pro_cat2]['pstat_ori_total'] += $row->pstat_ori_total;
            $data[$row->pro_cat1]['sub'][$row->pro_cat2]['pstat_min_total'] += $row->pstat_min_total;
            $data[$row->pro_cat1]['sub'][$row->pro_cat2]['pstat_total'] += $row->pstat_total;

        }

        return ['total' => $total, 'data' => $data];
    }






}
