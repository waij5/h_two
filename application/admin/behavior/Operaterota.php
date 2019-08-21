<?php

namespace app\admin\behavior;

use app\common\library\Sms;
use think\Config;

class Operaterota
{
    protected $siteConfig = null;

    public function __construct()
    {
        $this->siteConfig = Config::get('site');
    }

    public function success(&$operateBook, $extrInfo)
    {
        if ($this->siteConfig['flg_send_operate_book']) {
            try {
                $sms = new Sms;

                if (is_array($extrInfo['mobile'])) {
                    $sms->batchSend($extrInfo['mobile'], $extrInfo['content']);
                } else {
                    $sms->send($extrInfo['mobile'], $extrInfo['content']);
                }
            } catch (\Exception $e) {
                \think\Log::record($e->getMessage(), 'error');
            }
        }

    }

    public function cancel(&$operateBook, $extrInfo)
    {

    }
}
