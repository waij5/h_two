<?php

namespace app\common\library;
use app\admin\model\SmsRecord;
use think\Log;

class Sms implements \app\common\library\Constracts\Sms
{
    public $instance = null;

    public function __construct()
    {
        $smsConfig = include_once APP_PATH . 'sms.php';
        $class = $smsConfig['resolutions'][$smsConfig['default']]['class'];

        $reflectCls = new \ReflectionClass($class);
        $this->instance = $reflectCls->newInstance($smsConfig['resolutions'][$smsConfig['default']]['config']);

        return $this;
    }

    public function send($mobile, $content)
    {
        $result = false;
        try {
            $res = $this->instance->send($mobile, $content);
            if ($res['error'] == false) {
                $smsRecord = new SmsRecord;
                $smsRecord->mobiles = $mobile;
                $smsRecord->content = $content;
                $smsRecord->result = $res['sendSuccess'];
                $smsRecord->ori_return = $res['sendBackData'];;
                $smsRecord->createtime = time();
                $smsRecord->save();

                $result = true;
            }
        } catch(\Exception $e) {
            Log::record('------ sms send exception ------' . PHP_EOL . $e->getMessage());
        }

        return $result;
    }

    public function batchSend($mobiles, $content)
    {
        $result = false;
        try {
            $res = $this->instance->batchSend($mobiles, $content);
            if ($res['error'] == false) {
                $smsRecord = new SmsRecord;
                $smsRecord->mobiles = $mobile;
                $smsRecord->content = $content;
                $smsRecord->result = $res['sendSuccess'];
                $smsRecord->ori_return = $res['sendBackData'];;
                $smsRecord->createtime = time();
                $smsRecord->save();

                $result = true;
            }
        } catch(\Exception $e) {
            Log::record('------ sms send exception ------' . PHP_EOL . $e->getMessage());
        }

        return $result;
    }

    public function sendMms()
    {

    }

    public function batchsendMms()
    {

    }

    public function queryLastNum()
    {

    }
}