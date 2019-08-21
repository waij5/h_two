<?php

namespace app\admin\model;

use think\Model;

class Oscstatus
{
    //Status_0  Lang
    static $instance = null;
    private $statusData;

    private function __construct()
    {
        $this->statusData = [
                                // '0' => \think\Lang::get('Status_0', [], ''),
                                // '1' => \think\Lang::get('Status_1', [], ''),
                                '2' => \think\Lang::get('Status_2', [], ''),
                                // '-1' => \think\Lang::get('Status_m_1', [], ''),
                                '-2' => \think\Lang::get('Status_m_2', [], ''),
                                // '-3' => \think\Lang::get('Status_m_3', [], ''),
                            ];
        return $this;
    }

    public static function instance()
    {
        if (self::$instance == null) {
            self::$instance = new Oscstatus();
        }

        return self::$instance;
    }


    public static function getStatusById($id)
    {
        $statusList = self::getList();

        $status = '';
        if (isset($statusList['"' . $id . '"'])) {
            $status = $statusList['"' . $id . '"'];
        }

        return $status;
    }

    public static function getList()
    {
        $instance = self::instance();

        return $instance->statusData;
    }
}