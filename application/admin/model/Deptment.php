<?php

namespace app\admin\model;

use think\Model;
use think\Cache;
use fast\Tree;

class Deptment extends Model
{
    // 表名
    protected $name = 'deptment';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    
    // 追加属性
    protected $append = [

    ];

    const DEPT_TREE_CACHE_KEY = 'cache_dept_tree';
    const DEPT_LIST_CACHE_KEY = 'cache_dept_list';

    public function initialize()
    {
        parent::initialize();
        //event($event, $callback, $override = false)
        //afterInsert afterUpdate afterWrite afterDelete
        self::event('after_insert', function() {
            self::clearCache();
        });
        self::event('after_update', function() {
            self::clearCache();
        });
        self::event('after_delete', function() {
            self::clearCache();
        });
    }


    /**
     * 获取各种目录树
     * @param bool $force 是否强制刷新缓存
     * @return $arrayName = array('' => , );
     */
    public function getVariousTree()
    {
        $tree = self::getDeptTreeCache();
        return $tree->getTreeList($tree->getTreeArray(0), 'name');
    }

    public static function getDeptTreeCache()
    {
        if (Cache::get(self::DEPT_TREE_CACHE_KEY) == null) {
            $deptList = static::field('dept_id as id, dept_id, dept_pid, dept_pid as pid, dept_code, dept_type, dept_name, dept_name as name, dept_f_status, dept_status, dept_sort, dept_remark, createtime, updatetime')->order("dept_pid", "ASC, dept_id ASC")->select();
            $deptTree = Tree::instance()->init($deptList, 'pid');

            Cache::set(self::DEPT_TREE_CACHE_KEY, $deptTree);
        }

        return Cache::get(self::DEPT_TREE_CACHE_KEY);
    }

    public static function getDeptListCache()
    {
        if (Cache::get(self::DEPT_LIST_CACHE_KEY) == null) {
            $deptList = static::order("dept_pid", "ASC, dept_id ASC")->column('dept_id as id, dept_id, dept_pid, dept_pid as pid, dept_code, dept_type, dept_name, dept_name as name, dept_f_status, dept_status, dept_sort, dept_remark, createtime, updatetime', 'dept_id');
            
            Cache::set(self::DEPT_LIST_CACHE_KEY, $deptList);
        }

        return Cache::get(self::DEPT_LIST_CACHE_KEY);
    }

    public static function clearCache()
    {
        Cache::rm(self::DEPT_LIST_CACHE_KEY);
        Cache::rm(self::DEPT_TREE_CACHE_KEY);
    }

}
