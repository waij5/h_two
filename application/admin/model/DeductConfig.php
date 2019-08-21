<?php

namespace app\admin\model;

use think\Model;
use think\Cache;

class DeductConfig extends Model
{
    // 表名
    protected $name = 'deduct_config';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [

    ];
    
    const CACHE_KEY = 'cache_deduct_config';

    
    public function initialize()
    {
        parent::initialize();
        self::event('after_insert', function() {
            self::clearCache();
        });
        self::event('after_delete', function() {
            self::clearCache();
        });
    }

    /**
     * 每行数据为 纯数组，非SELECT后的OBJECT
     * @return array
     */
    public static function getCache()
    {
        if (Cache::get(self::CACHE_KEY) == null) {
            $tmplist = self::select();
            $list = [];
            foreach ($tmplist as $key => $row) {
                $list[$row['id']] = $row->getData();
            }

            Cache::set(self::CACHE_KEY, $list);
        }

        return Cache::get(self::CACHE_KEY, []);
    }


    public function clearCache()
    {
        Cache::rm(self::CACHE_KEY);
    }

}
