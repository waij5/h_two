<?php

namespace app\admin\model;

use think\Model;
use think\Session;
use think\Cache;
use think\Config;
use fast\Tree;
use yjy\Passport\HasApiTokens;

class Admin extends Model
{
    use HasApiTokens;

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    const ADMIN_CACHE_KEY = 'cache_admin_list';
    const ADMIN_BRIEF_CACHE_KEY = 'cache_brief_admin_list';
    const ADMIN_BRIEF_CACHE_2_KEY = 'cache_brief_admin_list2';

    public function initialize()
    {
        parent::initialize();
        //event($event, $callback, $override = false)
        //afterInsert afterUpdate afterWrite afterDelete
        // self::event('after_insert', function() {
        //     self::clearCache();
        // });
        // self::event('after_delete', function() {
        //     self::clearCache();
        // });
    }

    /**
     * 重置用户密码
     * @author baiyouwen
     */
    public function resetPassword($uid, $NewPassword)
    {
        $passwd = $this->encryptPassword($NewPassword);
        $ret = $this->where(['id' => $uid])->update(['password' => $passwd]);
        return $ret;
    }

    // 密码加密
    protected function encryptPassword($password, $salt = '', $encrypt = 'md5')
    {
        return $encrypt($password . $salt);
    }

    public function getAdminList()
    {
        return static::getAdminCache(static::ADMIN_CACHE_KEY);
    }

    public function getBriefAdminList()
    {
        return static::getAdminCache(static::ADMIN_BRIEF_CACHE_KEY);
    }
    public function getBriefAdminList2()
    {
        return static::getAdminCache(static::ADMIN_BRIEF_CACHE_2_KEY);
    }

    /**
     * 获取职员信息，缓存
     * @param $key 缓存键值
     * @return array
     */
    public static function getAdminCache($key = null)
    {
        if (Cache::get(static::ADMIN_CACHE_KEY) == null || Cache::get(static::ADMIN_BRIEF_CACHE_KEY) == null) {
            $admins = static::field('id,username,nickname,dept_id,avatar,status')->where(['status' => 'normal'])->order('username', 'ASC')->select();

            $adminList = array();
            $briefAdminList = array();
            $briefAdminList2 = array();
            foreach ($admins as $key => $admin) {
                $adminList[$admin['id']] = $admin;
                $briefAdminList[$admin['id']] = $admin['nickname'];
                $briefAdminList2[$admin['id']] = $admin['username'] . '-' . $admin['nickname'];
            }

            Cache::set(static::ADMIN_CACHE_KEY, $adminList);
            Cache::set(static::ADMIN_BRIEF_CACHE_KEY, $briefAdminList);
            Cache::set(static::ADMIN_BRIEF_CACHE_2_KEY, $briefAdminList2);
        }

        if ($key == null) {
            return ['adminList' => Cache::get(static::ADMIN_CACHE_KEY), 'briefAdminList' => Cache::get(static::ADMIN_BRIEF_CACHE_KEY), 'briefAdminList2' => Cache::get(static::ADMIN_BRIEF_CACHE_2_KEY)];
        } else {
            return Cache::get($key, []);
        }
    }


    public function clearCache()
    {
        Cache::rm(static::ADMIN_CACHE_KEY);
        Cache::rm(static::ADMIN_BRIEF_CACHE_KEY);
        Cache::rm(static::ADMIN_BRIEF_CACHE_2_KEY);
    }

    /**
     * 获取管理员最大折扣率 XX %,获取的为 100.00类型数字,不包括百分号%
     * 普通职员 normal_discount_percent
     * 组长/主任 manager_discount_percent
     */
    public function getDiscountLimit()
    {
        $maxRate = 100.00;
        $site = Config::get('site');

        if ($this->position == 0) {
            $maxRate = $site['normal_discount_percent'];
        } else {
            $maxRate = $site['manager_discount_percent'];
        }

        return $maxRate;
    }

    /**
     * 昵称搜索
     * @param int $mode 1后部模糊匹配，2前后模糊匹配
     * @return array
     */
    public static function getAdminByName($userName, $mode = 1) {
        if (empty($userName)) {
            return static::getAdminCache(static::ADMIN_BRIEF_CACHE_KEY);
        }

        if ($mode == 1) {
            $searchWord = $userName . '%';
        } else {
            $searchWord = '%' . $userName . '%';
        }

        return static::where(['username' => ['like', $searchWord]])->column('nickname', 'id');
    }

}