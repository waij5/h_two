<?php

namespace app\common\library;

use \fast\Http;

/**
 * HIS系统 统一服务平台 短信发送
 */
class PmHisApi implements \app\common\library\Constracts\Sms
{
    //配置信息
    public $config;

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
            'tag' => $this->config['tag'],
            'mobiles'   => $mobile,
            'old_id' => 0,
            'content'  => mb_convert_encoding($content, 'utf8'),
        ];
        $options = [CURLOPT_CONNECTTIMEOUT => 8, CURLOPT_TIMEOUT => 8];
        $postRes = Http::post($this->config['url'], $params, $options);
        if ($postRes > 0) {
            return ['error' => false, 'msg' => '请求成功', 'sendSuccess' => true, 'sendBackData' => $postRes];
        } else {
            return ['error' => true, 'msg' => '请求失败', 'sendSuccess' => false, 'sendBackData' => $postRes];
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
