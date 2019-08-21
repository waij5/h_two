<?php

namespace app\admin\model;

use think\Model;

class CmdRecords extends Model
{
    // 表名
    protected $name = 'cmd_records';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;

    const STATUS_PROCESSING = 'PROCESSING';
    const STATUS_COMPLETED  = 'COMPLETED';
    const STATUS_FAILED     = 'FAILED';
    const STATUS_ABORT      = 'ABORT';

    //延迟写入设置，减少更新记录进度信息频率
    const DELAY_UPDATE_COUNT = 50;

    /**
     * 启动命令行
     */
    public function startCmd()
    {
        chdir(ROOT_PATH);
        system('php think ' . $this->command . ' -r ' . $this->id . ' >/dev/null 2>&1 &');
    }

    /**
     * 更新进度信息
     */
    public function updateProcessInfo($processJson)
    {
        if (!is_string($processJson)) {
            $processJson = json_encode($processJson);
        }
        $this->process = $processJson;
        $this->save();
    }

    /**
     * 延迟写入进度信息
     */
    public function delayUpdateProcessInfo($completedCount, $processJson, $delayUpdate = true)
    {
        if ($delayUpdate && ($completedCount % static::DELAY_UPDATE_COUNT) != 0) {
            return;
        } else {
            return $this->updateProcessInfo($processJson);
        }
    }

    /**
     * 获取进度信息
     */
    public function getProcessInfo()
    {
        return $this->process;
    }

    /**
     * 检测是否是超级管理员启动的命令
     */
    public function isSuperAdminCmd()
    {
        $groupAccess = \app\admin\mode\AuthGroupAccess::where(['uid' => $this->admin_id])->find();
        if (!empty($groupAccess) && $groupAccess->group_id == 1) {
            return true;
        }
        return false;
    }
}
