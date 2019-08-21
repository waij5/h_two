<?php

namespace app\api\controller;

use app\admin\model\Admin;
use app\admin\model\Customer;
use app\admin\model\CustomerOsconsult;
use app\admin\model\OrderItems;
use app\admin\model\FProSets;
use app\common\controller\Api;
use think\Controller;
use think\Request;
use think\Lang;
use think\Db;

class Order extends Api
{
    /**
     * copy from admin/controller/cash/order
     * attention!!! 同步修改
     */
    public function create()
    {
        $controllername = strtolower($this->request->controller());
        Lang::load(APP_PATH . 'index/lang/' . Lang::detect() . '/' . str_replace('.', '/', $controllername) . '.php');

        $admin = $this->request->admin;

        if ($this->request->isPost()) {
            $osconsultId = $this->request->post('osconsult_id', false);
            $params      = $this->request->post("row/a");
            $itemsParams = $this->request->post("itemParams/a", []);

            $setParams = $this->request->post("setParams/a", []);
            // print_r($setParams);exit;

            //超出权限 调价申请信息
            $doApply     = input('permissionRequest', false);
            $applyInfo   = input('applyInfo', '');
            $applyDetail = ['doApply' => $doApply, 'applyInfo' => $applyInfo];
            $oscAdminid  = 0;

            

            if ($params && $params['customer_id']) {
                //顾客信息
                $customer = Customer::find($params['customer_id']);
                if (empty($customer)) {
                    $this->error(__('Customer %s does not exist.', $params['customer_id']));
                } else {
                    //计提时以营销人员为准
                    $consultAdminId = $customer->admin_id;
                }

                Db::startTrans();

                //现场客服处理
                //是否应该更改相应客服状态， 有客服且客服未关联订单时需更改状态
                $shouldChangeOsconsult  = false;
                $params['osconsult_id'] = 0;
                if ($osconsultId) {
                    //未取OSC.ADMIN_ID 开单时以录入人为准，实际客服开单时应是现场客服本人
                    $osconsult = CustomerOsconsult::where([
                        'osc_id'     => $osconsultId,
                        'createtime' => [
                            'BETWEEN',
                            [
                                strtotime(date('Y-m-d')),
                                strtotime(date('Y-m-d 23:59:59')),
                            ],
                        ],
                    ])
                        ->find();
                    if ($osconsult != null) {
                        //分诊人员
                        $params['osconsult_id'] = $osconsult->osc_id;
                        $receptAdminId          = $osconsult->operator;

                        $adminId = $osconsult->admin_id;
                    }
                }

                if (!isset($consultAdminId) || $consultAdminId == null) {
                    $consultAdminId = 0;
                }
                if (!isset($receptAdminId) || $receptAdminId == null) {
                    $receptAdminId = 0;
                }
                $params['consult_admin_id'] = $consultAdminId;
                $params['recept_admin_id']  = $receptAdminId;

                if ($oscAdminid) {
                    //开物资处方单时开药人是当前登陆人员,现场客服师是最后一条现场客服的人员
                    $prescriber = $admin['id'];
                    $adminid    = $oscAdminid;
                } else {
                    //开手术单人员时现场客服师是当前登录人员，开药人为空
                    $adminid    = empty($adminId) ? $admin['id'] : $adminId;
                    $prescriber = $admin['id'];
                }

                //分套餐开单
                foreach($setParams as $setP) {
                    $set = FProSets::where('is_suit', '=', 1)->find($setP['id']);
                    if (empty($set)) {
                        continue;
                    }

                    $setSettings = json_decode($set->settings, true);
                    $setProParams = array();

                    foreach ($setSettings['tabContents'] as $key => $tabContent) {
                        foreach ($tabContent as $tabGroupID => $tabGroup) {
                            foreach ($tabGroup['proSets'] as $proTag => $proSet) {
                                if ($proSet > 0) {
                                    array_push($setProParams, array(
                                        'pk' => $proSet['pro_id'],
                                        // 'pro_id' => $proSet['pro_id'],
                                        'qty' => $proSet['qty'] * $setP['qty'],
                                        'item_total' => $proSet['price'] * $proSet['qty'] * $setP['qty'],
                                    ));
                                }
                            }
                        }
                    }

                    if (!empty($setProParams)) {
                        //非事务处理开单， 事务在前面最外层处理
                        $saveSetOrderRes = OrderItems::createOrder($params, $setProParams, $applyDetail, $adminid, $prescriber, $admin, false);
                        if ($saveSetOrderRes['error']) {
                            Db::rollback();
                            $this->error(__($saveSetOrderRes['msg']));
                        }
                    }
                }

                //多单项目开单 非事务处理开单， 事务在前面最外层处理
                $saveOrderRes = OrderItems::createOrder($params, $itemsParams, $applyDetail, $adminid, $prescriber, $admin, false);
                $msg          = '';

                if ($saveOrderRes['error']) {
                    Db::rollback();
                    $this->error(__($saveOrderRes['msg']));
                } else {
                    //开单改变现在客服状态
                    if (!empty($osconsult)) {
                        if ($osconsult->osc_status == CustomerOsconsult::STATUS_CONSULTING || $osconsult->osc_status == CustomerOsconsult::STATUS_FAIL) {
                            $osconsult->osc_status = CustomerOsconsult::STATUS_FAIL;
                            $osconsult->fat_id     = 0;

                            if ($osconsult->save()) {
                                $msg = __('Related Osconsult has been updated.');
                            }
                        }
                    }
                    Db::commit();
                    $this->success($msg);
                }
            }

            $this->error(__('Parameter %s can not be empty', ''));
        }

        return false;
    }
}
