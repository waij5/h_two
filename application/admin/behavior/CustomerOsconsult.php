<?php

namespace app\admin\behavior;

use app\admin\model\Rvinfo;

class CustomerOsconsult
{
    /**
     * 现场客服保存成功
     * @param array  
     */
    public function run(&$customerOsconsult, $customer)
    {
        if ($customerOsconsult instanceof \app\admin\model\CustomerOsconsult) {
            try {
                //现场客服失败
                if ($customerOsconsult->osc_status < 0) {
                    $siteConfig = \think\Config::get('site');
                    $oscFailPlanId = isset($siteConfig['osconsult_plan']) ? $siteConfig['osconsult_plan'] : false;

                    if ($oscFailPlanId) {
                        $rvplan = model('Rvplan')->get($oscFailPlanId);
                        if ($rvplan && $customer) {
                            if ($rvplan->rvp_status < 1) {
                                return false;
                            }

                            $rvTypeName = '--';
                            $rvType = model('Rvtype')->find($rvplan->rvtype_id);
                            if ($rvType != null) {
                                $rvTypeName = $rvType->rvt_name;
                            }
                            // $rvdays = model('Rvdays')->where(['rvplan_id' => $rvplan->rvp_id, 'rvd_status' => 1])->select();
                             $rvdays = model('Rvdays')->alias('rv_days')
                                ->join(model('Rvtype')->getTable() . ' rv_type', 'rv_days.rvtype_id = rv_type.rvt_id', 'LEFT')
                                ->where(['rvplan_id' => $rvplan->rvp_id, 'rvd_status' => 1])
                                ->column('rv_days.*, rv_type.rvt_name');

                            $admin = \think\Session::get('admin');

                            //新增回访记录
                            $curTime = time();
                            $checkFlg = false;
                            $todayStartTime = strtotime(date('Y-m-d'));
                            $todayEndTime = strtotime(date('Y-m-d') . ' 23:59:59');
                            foreach ($rvdays as $key => $daySet) {
                                $rvpName = is_null($daySet['rvd_name']) ? $rvplan->rvp_name : $rvplan->rvp_name.'+'.$daySet['rvd_name'];
                                if ($checkFlg == false) {
                                    $isRvTodayAdded = model('Rvinfo')
                                        ->where([
                                        'customer_id' => $customer->ctm_id,
                                        'admin_id'    => $admin->id,
                                        'rv_plan'     => $rvpName,
                                        'createtime'  => ['BETWEEN', [$todayStartTime, $todayEndTime]],
                                        ])
                                        ->count();
                                    if ($isRvTodayAdded) {
                                        break;
                                    }
                                    //今日已增加相同回访计划，不予处理
                                    $checkFlg = true;
                                }
                                $rvDate              = date('Y-m-d', strtotime('+' . $daySet['rvd_days'] . ' days', $curTime));
                                $rvinfo              = new Rvinfo();
                                $rvinfo->rvi_tel     = $customer->ctm_mobile;
                                $rvinfo->customer_id = $customer->ctm_id;
                                $rvinfo->rvt_type    = is_null($daySet['rvt_name']) ? '' : $daySet['rvt_name'];//回访类型名
                                $rvinfo->rv_plan     =  $rvpName;
                                $rvinfo->rvi_content = '';
                                $rvinfo->admin_id    = $admin->id;
                                $rvinfo->rv_date     = $rvDate;
                                $rvinfo->rv_is_valid = 0;
                                $rvinfo->save();
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                //添加程序 错误警告信息
                \think\log::record('osconsult fail msg error' . $e->getMessage(), 'debug');
            }
        }
    }
}