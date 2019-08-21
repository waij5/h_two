<?php

namespace app\admin\model;

use think\Model;
use think\Cache;

class Tooltype extends Model
{
    // 表名
    protected $name = 'tooltype';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [

    ];
    
    const TOOL_LIST_CACHE_KEY = 'cache_tool_list';

    public function initialize()
    {
        parent::initialize();
        //event($event, $callback, $override = false)
        //afterInsert afterUpdate afterWrite afterDelete
        self::event('after_insert', function() {
            self::refreshCache();
        });
        self::event('after_update', function() {
            self::refreshCache();
        });
        self::event('after_delete', function() {
            self::refreshCache();
        });
    }


    public static function getToolListCache($force = false)
    {
        if ($force || Cache::get(self::TOOL_LIST_CACHE_KEY) == null) {
            $toolList = self::order("tool_sort desc, tool_id", "desc")->column('tool_name', 'tool_id');

            $toolList2 = array('' => '--');
            foreach ($toolList as $key => $value) {
                $toolList2[$key] = $value;
            }
            Cache::set(self::TOOL_LIST_CACHE_KEY, $toolList2);
        }

        return Cache::get(self::TOOL_LIST_CACHE_KEY);
    }

    public static function refreshCache()
    {
        // Cache::rm(self::TOOL_LIST_CACHE_KEY);
        static::getToolListCache(true);
    }

}
