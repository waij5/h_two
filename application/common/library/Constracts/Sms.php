<?php

namespace app\common\library\Constracts;

/**
 * 短信接口
 */
interface Sms
{
    /*
     * 发送短信
     * send, batchsend 返回结果
     * ['error' => true, 'msg' => '手机号码不能为空', 'sendSuccess' => false, 'sendBackData' => '']
     */
    function send($mobile, $content);

    function batchSend($mobiles, $content);

    function sendMms();

    function batchsendMms();

    function queryLastNum();
}
