<?php

namespace app\admin\model;

use think\Model;
use think\Cache;

class CProject extends Model
{
    // 表名
    protected $name = 'c_project';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [

    ];

    const C_PROJECT_CACHE_KEY = 'cache_c_project_list';
    const C_PROJECT_ARRAY_CACHE_KEY = 'cache_c_project_list_array';

    public function initialize()
    {
        parent::initialize();
        //event($event, $callback, $override = false)
        // afterInsert afterUpdate afterWrite afterDelete

        //数据变动时，自动更新缓存
        self::event('after_insert', function() {
            self::refreshCache();
        });
        self::event('afterUpdate', function() {
            self::refreshCache();
        });
        self::event('after_delete', function() {
            self::refreshCache();
        });
    }
    
    /**
     * 获取职员信息，缓存
     * @param $key 缓存键值
     * @return array
     */
    public static function getCProjectCache()
    {
        if (Cache::get(self::C_PROJECT_CACHE_KEY) == null) {
            $cProjects = self::order('id', 'ASC')->select();

            Cache::set(self::C_PROJECT_CACHE_KEY, $cProjects);
        }

       return Cache::get(self::C_PROJECT_CACHE_KEY);
    }

    public static function cProjectArrayCache()
    {
        if (Cache::get(self::C_PROJECT_ARRAY_CACHE_KEY) == null) {
            $cProjects = self::getCProjectCache();

            $list = array();
            foreach ($cProjects as $key => $row) {
                $list[$row->id] = $row->getData();
            }

            Cache::set(self::C_PROJECT_ARRAY_CACHE_KEY, $list);
        }

       return Cache::get(self::C_PROJECT_ARRAY_CACHE_KEY);
    }

    
    public static function refreshCache()
    {
        Cache::rm(self::C_PROJECT_CACHE_KEY);
        self::getCProjectCache();
    }
}
