<?php

namespace app\admin\model;

use fast\Http;

class MallSync
{
    private static $instance = null;

    private $tokenBase          = '';
    private $customerSyncUrl    = '';
    private $customerBackUrl    = '';
    private $pointsToMallUrl    = '';
    private $pointsToHISUrl     = '';
    private $pointsToHISBackUrl = '';
    //检查 HIS 记录是否 同步到 MALL 的 URL
    private $checkSyncedToMallUrl = '';

    private $tokenSeed  = '';
    private $token      = '';
    private $curlOption = array();

    private function __construct()
    {
        if (!file_exists(APP_PATH . 'sync_config.ini')) {
            trigger_error('File sync_config.ini not exist.', E_USER_ERROR);
        }
        $mallConfig = parse_ini_file(APP_PATH . 'sync_config.ini', true);
        if ($mallConfig === false) {
            trigger_error('File sync_config.ini parse error.', E_USER_ERROR);
        }
        $this->tokenBase            = $mallConfig['token_base'];
        $this->customerSyncUrl      = $mallConfig['customer_sync_url'];
        $this->customerBackUrl      = $mallConfig['customer_back_url'];
        $this->pointsToMallUrl      = $mallConfig['points_to_mall_url'];
        $this->pointsToHISUrl       = $mallConfig['points_to_HIS_url'];
        $this->pointsToHISBackUrl   = $mallConfig['points_to_HIS_back_url'];
        //最近更新 防止之前的配置未更新出错
        $this->checkSyncedToMallUrl = isset($mallConfig['check_synced_to_mall_Url']) ? $mallConfig['check_synced_to_mall_Url'] : '';

        $this->tokenSeed  = substr(uniqid(), 0, 8);
        $this->token      = static::generateToken($this->tokenBase, $this->tokenSeed);
        $this->curlOption = [CURLOPT_HTTPHEADER => ['yjy-h-seed:' . $this->tokenSeed, 'yjy-h-token:' . $this->token], CURLOPT_CONNECTTIMEOUT => 8, CURLOPT_TIMEOUT => 30];

        return $this;
    }

    public static function instance()
    {
        if (!self::$instance instanceof MallSync) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /*
     * 生成令牌
     */
    public static function generateToken($tokenBase, $tokenSeed)
    {
        return md5(md5($tokenBase . $tokenSeed));
    }

    /*
     * 校验令牌
     */
    public static function checkToken($tokenBase, $tokenSeed, $token)
    {
        return $token == md5(md5($tokenBase . $tokenSeed));
    }

    public function getUnSyncedUsers($pageNumber = 1, $extraParams = array())
    {
        $params = array_merge(['p' => $pageNumber], $extraParams);
        return $this->postToMall($this->customerSyncUrl, $params);
    }

    /*
     * 将匹配的顾客信息回传至商城
     */
    public function postSyncUsersToMall($syncData)
    {
        $params = ['userData' => $syncData];

        return $this->postToMall($this->customerBackUrl, $params);
    }

    /**
     * 检查LOG是否已同步到商城
     */
    public function checkSyncToMall($hisLogIds = array())
    {
        $params = ['log_ids' => $hisLogIds];

        return $this->postToMall($this->checkSyncedToMallUrl, $params);
    }

    /**
     * 推送积分变动到商城
     */
    public function syncPoints(array $pointsData)
    {
        $params = ['syncPoints' => $pointsData];

        return $this->postToMall($this->pointsToMallUrl, $params);
    }

    /*
     * 获取商城积分变动
     * @params int $pageNumber 页数
     * @params array $userIds 筛选的商城 user_id
     * @return array ['list' => array(), 'hasMore' => true / false]
     */
    public function getMallPointsChange($pageNumber = 1, $userIds = array())
    {
        $params = ['p' => $pageNumber, 'user_id' => $userIds];

        return $this->postToMall($this->pointsToHISUrl, $params);
    }

    /*
     * 同步积分至 HIS 后 回传数据至 MALL
     */
    public function getMallPointsChangeBack($logIds = array())
    {
        $params = ['log_id' => $logIds];

        return $this->postToMall($this->pointsToHISBackUrl, $params);
    }

    /*
     * 通用发送POST请求至商城
     */
    public function postToMall($url, $params = array())
    {
        return Http::post($url, $params, $this->curlOption);
    }

    public static function prepareJSON($input)
    {
        if (substr($input, 0, 3) == pack("CCC", 0xEF, 0xBB, 0xBF)) {
            $input = substr($input, 3);
        }
        return $input;
    }

    /*
     * MALL使用0时区，相关时区转化
     */
    public static function gmTime($localTime = null)
    {
        if (!is_null($localTime)) {
            return ($localTime - date('Z'));
        } else {
            return (time() - date('Z'));
        }
    }

    /*
     * MALL使用0时区，相关时区转化
     * 格林威治时间 转为 本地时间戳
     */
    public static function gm2LocalTime($gmTime)
    {
        return ($gmTime + date('Z'));
    }

}
