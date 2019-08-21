<?php

namespace app\admin\model;

use think\Model;
use think\Cache;

class Operator extends Model
{
    // 表名
    protected $name = 'operator';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [

    ];
    const CACHE_KEY = '__CACHE_OPERATOR_LIST__';

    public function initialize()
    {
        parent::initialize();
        //event($event, $callback, $override = false)
        //afterInsert afterUpdate afterWrite afterDelete
        self::event('after_insert', function() {
            Cache::rm(self::CACHE_KEY);
        });
        self::event('after_update', function() {
            Cache::rm(self::CACHE_KEY);
        });
        self::event('after_delete', function() {
            sCache::rm(self::CACHE_KEY);
        });
    }

}
