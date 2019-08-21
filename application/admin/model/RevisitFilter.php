<?php

namespace app\admin\model;

use think\Model;

class RevisitFilter extends Model
{
    // 表名
    protected $name = 'revisit_filter';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;

    // 追加属性
    protected $append = [

    ];

    public static function isContentValid($content = '')
    {
        if (empty($content)) {
            return false;
        } else {
            $filterWords = static::where(['filter_status' => 1])
                    ->order('filter_sort desc, filter_id', 'desc')->cache("__cache_revisit_filter___")->column('filter_name');

            if (\think\Config::get('strict_revisit_filter')) {
                foreach ($filterWords as $filterWord) {
                    //UTF8，忽略大小写
                    if (preg_match('/\b' . preg_quote($filterWord) . '\b/ui', $content)) {
                        return false;
                    }
                }
            } else {
                foreach ($filterWords as $filterWord) {
                    if (mb_stripos($content, $filterWord, 0, 'utf-8') !== false) {
                        return false;
                    }
                }
            }
        }
        return true;
    }

}
