<?php

namespace app\admin\behavior;

use app\admin\model\AccountLog;
use app\admin\model\Customer as MCustomer;
use app\common\library\Sms;
use think\Config;

class Customer
{
    protected $siteConfig = null;

    public function __construct()
    {
        $this->siteConfig = Config::get('site');
    }

    /**
     * 资金变动
     * @param array
     */
    public function accountChange(&$accountLog)
    {
        if (!empty($this->siteConfig['flg_send_change_sms'])) {
            $customer = MCustomer::find($accountLog->customer_id);
            try {
                if ($customer) {
                    switch ($accountLog->change_type) {
                        case AccountLog::TYPE_EXCHANGE:
                            $content = <<<cf
尊敬的【{:customerName}】：
您好，本次兑换【{:logPayPoint}】积分。截止目前，您的消费积分为【{:customerPaypoint}】。
【{:hospitalName}】祝您越来越美丽
cf;
                            break;
                        case AccountLog::TYPE_PAY_ORDER:
                            $content = <<<cf
尊敬的【{:customerName}】：
您好，本次消费您获得【{:logPayPoint}】积分。截止目前，您的消费积分为【{:customerPaypoint}】。
【{:hospitalName}】祝您越来越美丽
cf;
                            break;
                        default:
                            break;
                    }

                    $matches = [
                        '{:customerName}'     => $customer->ctm_name,
                        '{:logPayPoint}'      => $accountLog->pay_points,
                        '{:customerPaypoint}' => $customer->ctm_pay_points,
                        '{:hospitalName}'     => isset($this->siteConfig['hospital']) ? $this->siteConfig['hospital'] : '壹加壹',
                    ];

                    if (isset($content)) {
                        foreach ($matches as $match => $replace) {
                            $content = str_replace($match, $replace, $content);
                        }
                        $sms = new Sms;
                        $sms->send($customer->ctm_mobile, $content);
                    }
                }
            } catch (\Exception $e) {
                \think\Log::record($e->getMessage(), 'error');
            }
        }
    }

}
