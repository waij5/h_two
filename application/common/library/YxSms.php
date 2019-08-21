<?php

namespace app\common\library;

use \fast\Http;

/**
 * 亿信无线短信类
 */
class YxSms implements \app\common\library\Constracts\Sms
{
    //配置信息
    public $config;
    const SEND_URL = "http://106.ihuyi.cn/webservice/sms.php?method=Submit";

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function send($mobile, $content)
    {
        if (empty($mobile)) {
            return ['error' => true, 'msg' => '手机号码不能为空', 'sendSuccess' => false, 'sendBackData' => ''];
        }
        if (empty($content)) {
            return ['error' => true, 'msg' => '发送内容不能为空', 'sendSuccess' => false, 'sendBackData' => ''];
        }

        $params = [
            'account'  => $this->config['appId'],
            'password' => $this->config['appKey'],
            'mobile'   => $mobile,
            'content'  => mb_convert_encoding($content, 'utf8'),
        ];
        $options = [CURLOPT_CONNECTTIMEOUT => 5, CURLOPT_TIMEOUT => 5];
        $postRes = Http::post(static::SEND_URL, $params, $options);

        if ($postRes == '') {
            return ['error' => true, 'msg' => '请求失败', 'sendSuccess' => false, 'sendBackData' => ''];
        } else {
            $res = xml_to_array($postRes);
            return ['error' => false, 'msg' => '请求成功', 'sendSuccess' => $res['SubmitResult']['code'] == 2, 'sendBackData' => $postRes];
        }
    }

    public function batchSend($mobiles, $content)
    {
        if (!is_array($mobiles)) {
            $mobiles = array($mobiles);
        }
        $mobiles = implode(',', $mobiles);

        return $this->send($mobiles, $content);
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
