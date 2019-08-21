<?php

namespace app\admin\behavior;

use app\admin\model\Customer;

class Rvinfo
{
    /**
     * 现场客服保存成功
     * @param array
     */
    public function run(&$rvinfo)
    {
        // \think\Log::record('rvinfo_save behavior');
        try {
            $customerId = $rvinfo->customer_id;
            $customer = Customer::find($customerId);
            if (!empty($customer)) {
                $saveFlg = false;

                $autoNonPublic = \think\Config::get('site.auto_non_public');
                $autoCstNonPublic = \think\Config::get('site.auto_cst_non_public');
                if (!empty($autoNonPublic)) {
                    if ($customer->ctm_is_public == 1) {
                        $customer->ctm_is_public = 0;
                        $customer->ctm_public_time = time();
                        $saveFlg = true;
                    }
                }
                if (!empty($autoCstNonPublic)) {
                    if ($customer->ctm_is_cst_public == 1) {
                        $customer->ctm_is_cst_public = 0;
                        $saveFlg = true;
                    }
                }
                
                if ($rvinfo->rv_time && $customer->ctm_last_rv_time < $rvinfo->rv_time) {
                    $saveFlg = true;
                    $customer->ctm_last_rv_time = $rvinfo->rv_time;
                }

                if ($saveFlg) {
                    $customer->save();
                }
            }

        } catch (\Exception $e) {
            //添加程序 错误警告信息
            \think\log::record('rvinfo save to confiscate customer fail msg error' . $e->getMessage(), 'debug');
        }
    }
}
