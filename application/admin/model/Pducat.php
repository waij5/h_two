<?php

namespace app\admin\model;

use think\Model;
use think\Cache;
use fast\Tree;

class Pducat extends Model
{
    // 表名
    protected $name = 'pducat';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    
    // 追加属性
    protected $append = [

    ];
    

    /**
     * 初始化操作， 在写入，删除时 清除缓存
     */
    public function initialize()
    {
        parent::initialize();
    }


    public function getPdtAddtimeTextAttr($value, $data)
    {
        $value = $value ? $value : $data['createtime'];
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setPdtAddtimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }


     /**
     * 获取部门树
     * @param bool $force 是否强制刷新缓存
     * @return $arrayName = array('' => , );
     */
    public function getTree($force = false)
    {
        // if ($force || (Cache::get(self::SECTION_TREE_CACHE_KEY, false) == false))
        {
            $tree = Tree::instance();
            $tree->init(collection($this->field('*, pdc_id as id')->order('pdc_pid', 'asc')->select())->toArray(), 'pdc_pid');

            $secList = $tree->getTreeList($tree->getTreeArray(0), 'pdc_name');
            return $secList;

            // Cache::set(self::SECTION_TREE_CACHE_KEY, $secList);
        }

        // return Cache::get(self::SECTION_TREE_CACHE_KEY);
    }

    public function getTreeProduct($force = false)
    {
        // if ($force || (Cache::get(self::SECTION_TREE_CACHE_KEY, false) == false))
        {
            $tree = Tree::instance();
            $tree->init(collection($this->field('*, pdc_id as id')->where('pdc_zpttype','=','4')->order('pdc_pid', 'asc')->select())->toArray(), 'pdc_pid');

            $secList = $tree->getTreeList($tree->getTreeArray(0), 'pdc_name');
            return $secList;

            // Cache::set(self::SECTION_TREE_CACHE_KEY, $secList);
        }

        // return Cache::get(self::SECTION_TREE_CACHE_KEY);
    }

}
