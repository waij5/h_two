<?php

namespace app\admin\controller\customer;

use app\admin\model\Admin;
use app\admin\model\ArriveStatus;
use app\admin\model\CocAcceptTool;
use app\admin\model\Rvinfo;
use app\common\controller\Backend;
use think\Controller;
use think\Db;
use think\Request;
use think\Session;
use yjy\exception\TransException;

/**
 * 客户管理
 *
 * @icon fa fa-circle-o
 */
class Customer extends Backend
{

    /**
     * Customer模型对象
     */
    protected $model = null;

    protected $noNeedRight = ['staffquicksearch', 'comselectpop', 'viewhmhistory', 'listofbirth', 'quicksearch', 'search'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Customer');

        $this->customerExtraInit();
        //营销人员
        $briefAdminList = model('Admin')->getBriefAdminList2();
        $this->view->assign("briefAdminList", $briefAdminList);
        //客户类型
        $ctmtypeLists = model('customertype')->field('type_id,type_name')->select();
        $ctmtypeList  = ['' => __('NONE')];
        foreach ($ctmtypeLists as $ctmtype) {
            $ctmtypeList[$ctmtype['type_id']] = $ctmtype['type_name'];
        }
        $this->view->assign("ctmtypeList", $ctmtypeList);
        $toolList = \app\admin\model\CocAcceptTool::getList();
        $this->view->assign("toolList", $toolList);

    }

    public function search()
    {
        \think\Request::instance()->get(['offset' => 0, 'limit' => 5]);
        return $this->renderList('index');
    }

    public function index()
    {
        return $this->renderList('index');
    }
    //废弃客户
    public function invalid()
    {
        return $this->renderList('invalid');
    }

    public function listofbirth()
    {
        return $this->renderList('listofbirth');
    }

    /**
     * 获取进度信息
     */
    public function downloadprocess()
    {
        $type = input('type', 'index');

        list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null, false);
        list($bWhere, $extraWhere)                   = $this->handleRequest($type, $where);

        if (isset($extraWhere['customer.month'])) {
            $birthWhereF                 = '%-' . str_pad($extraWhere['customer.month'][1], 2, '0', STR_PAD_LEFT) . '-%';
            $extraWhere['ctm_birthdate'] = ['like', $birthWhereF];
            unset($extraWhere['customer.month']);
        }

        if (isset($bWhere['ctm_birthdate'])) {
            $ageStart = $bWhere['ctm_birthdate'][1][0];
            $ageEnd   = $bWhere['ctm_birthdate'][1][1] + 1;
            $bigAge   = getBirthDate($ageStart);
            $smallAge = getBirthDate($ageEnd);

            $bWhere['ctm_birthdate'][1][0] = $smallAge;
            $bWhere['ctm_birthdate'][1][1] = $bigAge;
        }

        \think\Request::instance()->get(['filter' => '']);

        return $this->commondownloadprocess('customerprofile', 'Customer profiles export', $bWhere, $extraWhere);
    }

    public function mergeprocess()
    {
        $main   = input('main', 0);
        $second = input('second', 0);
        $param  = $this->request->post('row/a', []);

        return $this->commondownloadprocess('mergehiscustomer', 'Customer profiles merge', [], [], ['main' => $main, 'second' => $second, 'param' => $param]);
    }

    /**
     * 共享客户列表
     */
    public function publist()
    {
        return $this->renderList('publist');
    }

    public function listforosconsult()
    {
        return $this->renderList('osconsult');
    }

    /**
     * 网电公有客户
     */
    public function cstpublist()
    {
        return $this->renderList('cstpublist');
    }

    /**
     * 我的网电顾客
     */
    public function mycstlist()
    {
        return $this->renderList('mycstlist');
    }

    /**
     * 编辑
     */
    public function edit($ids = null)
    {
        $row = $this->model->find($ids);
        // $row['ctm_mobile'] = getMaskString($row['ctm_mobile']);
        // $row['ctm_tel'] = getMaskString($row['ctm_tel']);

        if (!$row) {
            $this->error(__('No Results were found'));
        }

        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            $rows   = $this->model->find($ids);
            // $params['ctm_tel']         = $rows['ctm_tel'];

            // $ctmId = $this->model->where(['ctm_tel' => $params['ctm_tel']])->whereOr(['ctm_mobile' => $params['ctm_tel']])->column('ctm_id');
            // if($ctmId){
            //     if($ctmId[0] != $ids){
            //         $this->error(__("该电话号码已存在"));
            //     }
            // }

            $params['ctm_mobile']      = $rows['ctm_mobile'];
            $params['rec_customer_id'] = $row['rec_customer_id'];
            if ($params) {
                foreach ($params as $k => &$v) {
                    $v = is_array($v) ? implode(',', $v) : $v;
                }
                try
                {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name     = basename(str_replace('\\', '/', get_class($this->model)));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : true) : $this->modelValidate;
                        $row->validate($validate);
                    }
                    $forbiddens = ['ctm_depositamt', 'ctm_psumamt', 'ctm_salamt', 'ctm_rank_points', 'ctm_pay_points', 'ctm_frozen_depositamt', 'ctm_affiliate'];
                    foreach ($forbiddens as $key => $forbidKey) {
                        if (isset($params[$forbidKey])) {
                            unset($params[$forbidKey]);
                        }
                    }
                    $result = $row->save($params);
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error($row->getError());
                    }
                } catch (think\exception\PDOException $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }

        //推荐人
        // $rec_id =$row['rec_customer_id'];
        $recCustomer     = model('customer')->field('ctm_name,ctm_id')->where(['ctm_id' => $row['rec_customer_id']])->find();
        $recCustomerName = __('None');
        if (!empty($recCustomer)) {
            $recCustomerName = $recCustomer->ctm_id.'--'.$recCustomer->ctm_name;
        }

        //营销人员
        $developStaffName = __('None');

        $briefAdminList = model('Admin')->getBriefAdminList();
        if (isset($briefAdminList[$row['admin_id']])) {
            $developStaffName = $briefAdminList[$row['admin_id']];
        }

        $canReassignDev = $this->auth->check('customer/customer/reassigndev');

        $definedRvPlans = model('Rvplan')->where(['rvp_status' => 1])->column('rvp_name', 'rvp_id');
        $this->view->assign('definedRvPlans', $definedRvPlans);

        $this->view->assign("recCustomerName", $recCustomerName);
        $this->view->assign("developStaffName", $developStaffName);
        $this->view->assign('canReassignDev', $canReassignDev);

        $this->view->assign("row", $row);
        //顾客图片
        $customerImgs = model('CustomerImg')->where(['customer_id' => $row->ctm_id])->order('weigh', 'DESC')->select();
        $this->view->assign('customerImgs', $customerImgs);

        return $this->view->fetch();
    }

    /**
     * 批量增加 回访计划
     */
    public function batchaddrvtype($id = '')
    {
        $rvtypeList = model('Rvtype')->column('rvt_name', 'rvt_id');
        $this->view->assign('rvtypeList', $rvtypeList);
        $data = date('Y-m-d', time());
        $this->view->assign('data', $data);

        $briefAdminList = model('Admin')->getBriefAdminList2();
        $customerCount  = $this->model->where(['ctm_id' => ['in', $id]])->count();
        $customers      = $this->model->where(['ctm_id' => ['in', $id]])->column('ctm_name', 'ctm_id');
        $this->view->assign("briefAdminList", $briefAdminList);
        $this->assign('id', $id);
        $this->assign('customerCount', $customerCount);
        $this->assign('customers', $customers);

        if ($this->request->isPost()) {
            $id = explode(',', $id);

            $row   = $this->request->post('row/a');
            $admin = \think\Session::get('admin');
            if (empty($row['admin_id'])) {
                $row['admin_id'] = $admin->id;
            }
            $rvTypeName = '--';
            $rvType     = model('Rvtype')->find($row['rvt_type']);
            if ($rvType != null) {
                $rvTypeName = $rvType->rvt_name;
            }
            $nowTime = strtotime($data);
            $time    = strtotime($row['rvd_days']);
            if ($nowTime > $time) {
                $this->error(__("回访计划时间不能在今天之前"));
            }
            $rvDate = $row['rvd_days'];

            $customerList = model('customer')->where(['ctm_id' => ['in', $id]])->select();
            foreach ($customerList as $customer) {
                $rvinfo          = new Rvinfo();
                $rvinfo->rvi_tel = $customer->ctm_mobile;
                if (empty($customer->ctm_mobile)) {
                    $rvinfo->rvi_tel = $customer->ctm_tel;
                }
                $rvinfo->customer_id = $customer->ctm_id;
                $rvinfo->rvt_type    = $rvTypeName;
                $rvinfo->rv_plan     = $row['rv_plan'];
                $rvinfo->rvi_content = '';
                $rvinfo->admin_id    = $row['admin_id'];
                $rvinfo->rv_date     = $rvDate;
                $rvinfo->rv_is_valid = 0;
                $rvinfo->save();

               // //下次回访时间
               //  //unix_timestamp()在mysql中将date转为时间戳
               //  $time = strtotime(date('Y-m-d 23:59:59'));
               //  $nextRvinfo = model('rvinfo')->where('unix_timestamp(rv_date) > '.$time)->where(['customer_id' => $customer->ctm_id])->where('rv_time IS NULL')->order('rv_date', 'ASC')->find();
               //  if($nextRvinfo) {
               //      $customerModel = model('customer');
               //      $customerModel->save([
               //          'ctm_next_rvinfo' => $nextRvinfo->rv_date,
               //          ],
               //          ['ctm_id' => $customer->ctm_id]
               //      );
               //  }

                //today 判断是否需要更新下次回访
                if (!empty($customer->ctm_next_rvinfo) && $customer->ctm_next_rvinfo < $rvDate) {
                    $customer->ctm_next_rvinfo = $customer->ctm_next_rvinfo;
                } else {
                    $customer->ctm_next_rvinfo = $rvDate;
                }
                 $customer->save();
                
            }

            $this->success();
        }

        return $this->view->fetch();

    }

    //批量移出公有池
    public function batchpublicout($id = '', $ctmType = '')
    {
        if ($this->request->isPost()) {
            $id = explode(',', $id);
            if ($ctmType == 'publist') {
                $this->model->save(
                    [
                        'ctm_is_public'   => 0,
                        'ctm_public_time' => time(),
                    ],
                    ['ctm_id' => ['in', $id]]
                );
            }
            if ($ctmType == 'cstpublist') {
                $this->model->save(
                    [
                        'ctm_is_cst_public' => 0,
                    ],
                    ['ctm_id' => ['in', $id]]
                );
            }

            $this->success();
        }
        $customerCount = $this->model->where(['ctm_id' => ['in', $id]])->count();
        $customers     = $this->model->where(['ctm_id' => ['in', $id]])->column('ctm_name', 'ctm_id');
        $this->assign('id', $id);
        $this->assign('customerCount', $customerCount);
        $this->assign('customers', $customers);

        return $this->view->fetch();
    }
    //批量移出废弃池
    public function batchinvalidout($id = '')
    {
        if ($this->request->isPost()) {
            $id = explode(',', $id);
            $this->model->save(
                [
                    'ctm_status' => 1,
                ],
                ['ctm_id' => ['in', $id]]
            );
            $this->success();
        }
        $customerCount = $this->model->where(['ctm_id' => ['in', $id]])->count();
        $customers     = $this->model->where(['ctm_id' => ['in', $id]])->column('ctm_name', 'ctm_id');
        $this->assign('id', $id);
        $this->assign('customerCount', $customerCount);
        $this->assign('customers', $customers);

        return $this->view->fetch();
    }

    //客户移出废弃池
    // public function invalidcustomerOut()
    // {
    //     $customerId = input('customerId',false);
    //     $customer = $this->model->find($customerId);
    //     if(empty($customer)){
    //         $this->error('找不到该客户');
    //     }
    //     if($customer->ctm_status == 0) {
    //         $customer->ctm_status = 1;
    //     }
    //     $bool = $customer->save();
    //     $this->success('已移出废弃池');
    // }

    /**
     * 批量修改 营销人员
     */
    public function adminid($id = '')
    {
        if ($this->request->isPost()) {
            $AdminId = input('adminid', false);
            //同步更新 受理人员
            $syncCstAdmin = input('syncCstAdmin', false);
            $id           = explode(',', $id);
            //同步订单
            $syncOrderAdmin = input('syncOrderAdmin', false);
            //同步订单 时间范围模式
            $rangeMode = input('rangeMode', false);
            if ($syncOrderAdmin) {
                if (empty($rangeMode) && !in_array($rangeMode, ['curMonth', 'curYear', 'all', 'set'])) {
                    $this->error('请选择时间范围模式【本月，本年，所有，自定义】');
                }
            }

            if ($AdminId) {
                try {
                    Db::startTrans();

                    $subSql = $this->model->where(['ctm_id' => ['in', $id]])->column('admin_id');
                    if ($this->model->save(['admin_id' => $AdminId], ['ctm_id' => ['in', $id]]) === false) {
                        throw new TransException('操作失败');
                    }

                    //同步修改受理人员
                    if ($syncCstAdmin) {
                        if (\app\admin\model\CustomerConsult::update(
                            ['admin_id' => $AdminId],
                            ['admin_id' => ['in', $subSql], 'customer_id' => ['in', $id]]
                        ) === false) {
                            throw new TransException('操作失败');
                        }
                    }

                    //同步修改订单营销人员
                    if ($syncOrderAdmin) {
                        $syncOrderWhere = ['customer_id' => ['in', $id]];
                        if ($rangeMode == 'curMonth') {
                            $itemCreateTime = strtotime(date('Y-m-01'));
                            // strtotime(date('Y-m-t') . .' 23:59:59');
                            //本月--现在
                            $itemEndTime                       = time();
                            $syncOrderWhere['item_createtime'] = ['between', [$itemCreateTime, $itemEndTime]];
                        } elseif ($rangeMode == 'curYear') {
                            $itemCreateTime                    = strtotime(date('Y-01-01'));
                            $itemEndTime                       = time();
                            $syncOrderWhere['item_createtime'] = ['between', [$itemCreateTime, $itemEndTime]];
                        } elseif ($rangeMode == 'all') {
                        } elseif ($rangeMode == 'set') {
                            $itemCreateTime = input('item_createtime_start', false);
                            $itemEndTime    = input('item_createtime_end', false);
                            if ($itemCreateTime == false) {
                                $this->error('自定义范围时请输入开始时间');
                            }

                            $itemCreateTime = strtotime($itemCreateTime);
                            if ($itemEndTime) {
                                $itemEndTime                       = strtotime($itemEndTime . ' 23:59:59');
                                $syncOrderWhere['item_createtime'] = ['between', [$itemCreateTime, $itemEndTime]];
                            } else {
                                $syncOrderWhere['item_createtime'] = ['>=', $itemCreateTime];
                            }
                        }
                        if (\app\admin\model\OrderItems::update(
                            ['consult_admin_id' => $AdminId],
                            $syncOrderWhere
                        ) === false) {
                            throw new TransException('操作失败');
                        }
                    }

                    Db::commit();
                } catch (\Pdo\Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (TransException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }

                $this->success();
            } else {
                $this->error('请选择网络客服');
                // $this->error(__('Invalid parameters'));
            }
        }
        //营销人员
        $briefAdminList = model('Admin')->getBriefAdminList2();
        $customerCount  = $this->model->where(['ctm_id' => ['in', $id]])->count();
        $customers      = $this->model->where(['ctm_id' => ['in', $id]])->column('ctm_name', 'ctm_id');

        $this->view->assign("briefAdminList", $briefAdminList);
        $this->assign('id', $id);
        $this->assign('customerCount', $customerCount);
        $this->assign('customers', $customers);

        return $this->view->fetch();

    }

    /**
     * 批量修改 现场客服记录
     */
    public function batchupdateosc($ids = '')
    {
        // $ids = explode(',', $ids);

        if ($this->request->isPost()) {
            $oscAdminId = input('oscAdminId', false);
            $ids        = explode(',', $ids);

            //同步订单
            $syncOrderAdmin = input('syncOrderAdmin', false);
            //同步订单 时间范围模式
            $rangeMode = input('rangeMode', false);
            if ($syncOrderAdmin) {
                if (empty($rangeMode) && !in_array($rangeMode, ['curMonth', 'curYear', 'all', 'set'])) {
                    $this->error('请选择时间范围模式【本月，本年，所有，自定义】');
                }
            }

            if ($oscAdminId) {
                try {
                    Db::startTrans();
                    //更新顾客首次及最近现场客服
                    $this->model->save([
                        'ctm_first_osc_admin' => $oscAdminId,
                        'ctm_last_osc_admin'  => $oscAdminId,
                    ],
                        ['ctm_id' => ['in', $ids]]);
                    model('CustomerOsconsult')->save(
                        ['admin_id' => $oscAdminId],
                        ['customer_id' => ['in', $ids]]
                    );
                    //同步修改订单营销人员
                    if ($syncOrderAdmin) {
                        $syncOrderWhere = ['customer_id' => ['in', $ids]];
                        if ($rangeMode == 'curMonth') {
                            $itemCreateTime = strtotime(date('Y-m-01'));
                            // strtotime(date('Y-m-t') . .' 23:59:59');
                            //本月--现在
                            $itemEndTime                       = time();
                            $syncOrderWhere['item_createtime'] = ['between', [$itemCreateTime, $itemEndTime]];
                        } elseif ($rangeMode == 'curYear') {
                            $itemCreateTime                    = strtotime(date('Y-01-01'));
                            $itemEndTime                       = time();
                            $syncOrderWhere['item_createtime'] = ['between', [$itemCreateTime, $itemEndTime]];
                        } elseif ($rangeMode == 'all') {
                        } elseif ($rangeMode == 'set') {
                            $itemCreateTime = input('item_createtime_start', false);
                            $itemEndTime    = input('item_createtime_end', false);
                            if ($itemCreateTime == false) {
                                $this->error('自定义范围时请输入开始时间');
                            }

                            $itemCreateTime = strtotime($itemCreateTime);
                            if ($itemEndTime) {
                                $itemEndTime                       = strtotime($itemEndTime . ' 23:59:59');
                                $syncOrderWhere['item_createtime'] = ['between', [$itemCreateTime, $itemEndTime]];
                            } else {
                                $syncOrderWhere['item_createtime'] = ['>=', $itemCreateTime];
                            }
                        }
                        if (\app\admin\model\OrderItems::update(
                            ['admin_id' => $oscAdminId],
                            $syncOrderWhere
                        ) === false) {
                            throw new TransException('操作失败');
                        }
                    }

                    Db::commit();
                } catch (\Pdo\Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (TransException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }

                $this->success();
            } else {
                $this->error('请选择现场客服');
                // $this->error(__('Invalid parameters'));
            }
        }

        //客服人员
        $osconsultDeptId  = \think\Config::get('site.osconsult_dept_id');
        $osconsultDeptId  = $osconsultDeptId ? @floatval($osconsultDeptId) : 14;
        $deptTree         = \app\admin\model\Deptment::getDeptTreeCache();
        $osconsultDeptIds = ($deptTree->getChildrenIds($osconsultDeptId, true));
        $oscAdminList     = model('Admin')->where(['dept_id' => ['in', $osconsultDeptIds]])->order('username', 'ASC')->column("concat(username, '-', nickname)", 'id');

        $customerCount = $this->model->where(['ctm_id' => ['in', $ids]])->count();
        $customers     = $this->model->where(['ctm_id' => ['in', $ids]])->column('ctm_name', 'ctm_id');

        $this->assign('oscAdminList', $oscAdminList);
        $this->assign('ids', $ids);
        $this->assign('customerCount', $customerCount);
        $this->assign('customers', $customers);

        return $this->view->fetch();
    }

    /**
     * 选择顾客通用弹窗
     */
    public function comselectpop()
    {
        $type = input('type', false);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name')) {
                return $this->selectpage();
            }

            //开处方单和物资单时客户必须有前台分诊
            if ($type) {
                list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null, false);
                $where2                                      = array();
                foreach ($where as $key => $value) {
                    $where2['customer.' . $value[0]] = [$value[1], $value[2]];
                }
                // ctm_last_recept_time
                // print_r($where2);exit;
                // \think\Log::record('========' . print_r($where2, true));
                $time  = strtotime(date("Y-m-d"), time());
                $total = model('customer')->alias('customer')
                    ->join(model('customerosconsult')->getTable() . ' coc', 'customer.ctm_id=coc.customer_id', 'LEFT')
                    ->group('coc.customer_id')
                    ->order('customer.ctm_last_recept_time', 'DESC')
                // ->distinct(true)
                    ->where($where2)
                    ->where(['coc.createtime' => ['gt', $time]])
                    ->where(['coc.is_delete' => 0])
                    ->count();

                $list = model('customer')->alias('customer')
                    ->join(model('customerosconsult')->getTable() . ' coc', 'customer.ctm_id=coc.customer_id', 'LEFT')
                // ->distinct(true)
                    ->group('coc.customer_id')
                    ->order('customer.ctm_last_recept_time', 'DESC')
                    ->where($where2)
                    ->where(['coc.createtime' => ['gt', $time]])
                    ->where(['coc.is_delete' => 0])
                    ->select();
            } else {
                list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null);
                $total                                       = $this->model->where($where)->order($sort, $order)->count();
                $list                                        = $this->model->getList($where, $sort, $order, $offset, $limit);
            }

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }

        $url = 'customer/customer/comselectpop';
        if ($type) {
            $url = 'customer/customer/comselectpop?type=' . $type;
        }
        $yjyComSelectParams = [
            'url'            => $url,
            'pk'             => 'ctm_id',
            'sortName'       => 'ctm_last_recept_time desc, ctm_id',
            'sortOrder'      => 'desc',
            'search'         => false,
            'commonSearch'   => false,
            //多选时表格 JQUERY 选择器
            'parentSelector' => '#t-customer-select',
            'columns'        => [
                ['field' => 'ctm_id', 'title' => __('Ctm_id')],
                ['field' => 'ctm_name', 'title' => __('Ctm_name')],
                ['field' => 'ctm_sex', 'title' => __('Ctm_sex')],
                ['field' => 'ctm_addr', 'title' => __('Ctm_addr')],
                ['field' => 'ctm_mobile', 'title' => __('Ctm_mobile')],
                ['field' => 'ctm_job', 'title' => __('Ctm_job')],
                ['field' => 'ctm_remark', 'title' => __('Ctm_remark'), 'formatter' => 'Backend.api.formatter.content'],
            ],
        ];

        $fields = ['ctm_id', 'ctm_name', 'ctm_job', 'ctm_remark'];
        comselectinitparams($yjyComSelectParams, $fields);

        $yjyComSelectParams = json_encode($yjyComSelectParams);
        $this->view->assign('yjyComSelectParams', $yjyComSelectParams);

        return $this->view->fetch();
    }

    /**
     * 获取顾客历史客服getConsultHistory
     */
    public function getcsthistory($ids = null)
    {
        if ($this->request->isAjax()) {
            if ($ids && ($ids = intval($ids))) {
                list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null);
                $pk                                          = model('Customerconsult')->getPk();
                if ($sort == 'id' && $sort != $pk) {
                    $sort = $pk;
                }
                $extraWhere = ['cst.customer_id' => $ids, 'cst.status' => 1];

                $total = model('Customerconsult')->getListCount($where, $extraWhere);
                $list  = model('Customerconsult')->getList($where, $sort, $order, $offset, $limit, $extraWhere);

                $result = array("total" => $total, "rows" => $list);

                return json($result);
            }
        }

        $this->view->assign('ids', $ids);
        return $this->view->fetch();
    }

    /**
     * 获取顾客历史现场客服
     */
    public function getcochistory($ids = null)
    {
        if ($this->request->isAjax()) {
            if ($ids && ($ids = intval($ids))) {
                list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null);
                $pk                                          = model('Customerosconsult')->getPk();
                if ($sort == 'id' && $sort != $pk) {
                    $sort = $pk;
                }
                $extraWhere = [];
                //原WHERE未起作用
                $where = function ($query) use ($ids) {
                    $query->where(['coc.customer_id' => $ids]);
                };

                $extraWhere['coc.is_delete'] = 0;

                $total = model('Customerosconsult')->getListCount($where, $extraWhere);
                $list  = model('Customerosconsult')->getList($where, $sort, $order, $offset, $limit, $extraWhere);

                $result = array("total" => $total, "rows" => $list);

                return json($result);
            }
        }

        $this->view->assign('ids', $ids);
        return $this->view->fetch();
    }

    /**
     * 重新分配营销人员
     */
    public function reassigndev($type = 'normal', $customerId = '', $newDevStaff = '')
    {
        if ($type == 'search') {
            /**
             * 职员快速搜索
             */
            $userName = input('userName', '');
            return json(Admin::getAdminByName($userName, 2));
        } else {
            $customer = $this->model->find($customerId);

            if (empty($customer)) {
                $this->error(__('No Results were found'));
            }

            $customer->admin_id = $newDevStaff;
            if ($customer->save() !== false) {
                $this->success(__('Operation completed'));
            } else {
                $this->error(__('Operation failed'));
            }
        }
    }

    /**
     * 科室回访顾客
     * 付款，完成，退款单对应的顾客列表，按顾客ID分组
     */
    public function deptcustomerlist()
    {
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null);

            //科室
            $deptWhere = [];
            if (!$this->auth->isSuperAdmin()) {
                $deptWhere = $this->deptAuth->getCusdeptCondition('order_items.dept_id', true);
            }

            $total = $this->model->alias('customer')->join(model('OrderItems')->getTable() . ' order_items', 'customer.ctm_id = order_items.customer_id', 'INNER')
                ->order($sort, $order)
                ->where($where)
                ->where(['order_items.item_status' => ['in', [\app\admin\model\OrderItems::STATUS_PAYED, \app\admin\model\OrderItems::STATUS_COMPLETED, \app\admin\model\OrderItems::STATUS_CHARGEBACK]]])
                ->where($deptWhere)
                ->group('customer.ctm_id')
                ->count();

            $list = $this->model->alias('customer')->join(model('OrderItems')->getTable() . ' order_items', 'customer.ctm_id = order_items.customer_id', 'INNER')
                ->where($where)
                ->where(['order_items.item_status' => ['in', [\app\admin\model\OrderItems::STATUS_PAYED, \app\admin\model\OrderItems::STATUS_COMPLETED, \app\admin\model\OrderItems::STATUS_CHARGEBACK]]])
                ->where($deptWhere)
                ->group('customer.ctm_id')
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $jobs    = \app\admin\model\Job::getList();
            $genders = \app\admin\model\Gender::getList();
            foreach ($list as $key => $row) {
                $list[$key]['ctm_job'] = isset($jobs[$row['ctm_job']]) ? $jobs[$row['ctm_job']] : '-';
                $list[$key]['ctm_sex'] = @$genders[$row['ctm_sex']];

                unset($list[$key]['ctm_tel']);
                unset($list[$key]['ctm_mobile']);
            }

            return json(['total' => $total, 'rows' => $list]);
        }

        return $this->view->fetch();
    }

    public function viewhmhistory($ids = '')
    {

        if ($this->request->isAjax()) {
            $customer = $this->model->find($ids);
            if (empty($customer)) {
                $this->error('Customer %s does not exist.');
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null);

            $total = $customer->getCusHMOrderListCount($where);
            $list  = $customer->getCusHMOrderList($where, $sort, $order, $offset, $limit);
            if ($offset) {
                return json(['total' => $total, 'rows' => $list]);
            } else {
                $summary = $customer->getCusHMOrderListSummary($where);
                return json(['total' => $total, 'rows' => $list, 'summary' => $summary]);
            }

        }
        // return $this->view->fetch();
    }

    /**
     * 快速检索顾客
     * 因为是精确查找，并且MOBILE将更换为唯一的
     * limit 10
     */
    public function quicksearch()
    {
        $mobile      = trim(input('mobile', false));
        $id          = trim(input('id', false));
        $oldCtmCode  = input('old_ctm_code', false);
        $redirectUrl = input('redirectUrl', false);
        $where       = [];

        if (empty($redirectUrl)) {
            $this->error(__('Invalid parameters'));
        }
        if ($mobile) {
            $where['ctm_mobile'] = $mobile;
        }
        if ($id) {
            $where['ctm_id'] = $id;
        }
        if ($oldCtmCode) {
            $where['old_ctm_code'] = $oldCtmCode;
        }

        $customerId = 0;
        if ($where) {
            $doesCustomerExist = $this->model->getListCount($where);
            if ($doesCustomerExist) {
                $customers  = $this->model->getList($where, 'ctm_id', 'DESC', 0, 1);
                $customer   = $customers[0];
                $customerId = $customer->ctm_id;
            } else {
                if ($id) {
                    $this->view->assign('id', $id);
                    $this->view->assign('mobile', $mobile);
                    return view('customer/customer/notfound');
                }

                //联系电话ctm_tel可能会有重复
                $bwhere            = [];
                $bwhere['ctm_tel'] = $mobile;
                $doesCustomerExist = $this->model->getListCount($bwhere);
                if ($doesCustomerExist) {
                    $customers  = $this->model->getList($bwhere, 'ctm_id', 'DESC', 0, 1);
                    $customer   = $customers[0];
                    $customerId = $customer->ctm_id;
                }
                return redirect($redirectUrl, ['phone' => $mobile, 'customer_id' => $customerId, 'dialog' => 1]);
            }

            return redirect($redirectUrl, ['phone' => $mobile, 'customer_id' => $customerId, 'dialog' => 1]);
        }

        return $this->error(__('Invalid parameters'));
    }

    /**
     * 职员快速搜索
     */
    public function staffquicksearch($userName = '')
    {
        return json(Admin::getAdminByName($userName, 2));
    }

    /**
     * 渲染顾客列表
     */
    private function renderList($type = 'index')
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);

        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null, false);

            list($bWhere, $extraWhere) = $this->handleRequest($type, $where);

            if (is_null(input('op'))) {
                if ($type == 'invalid') {
                    $bWhere['ctm_status'] = 0;
                } else {
                    $bWhere['ctm_status'] = 1;
                }
            }

            if (isset($extraWhere['customer.month'])) {
                $birthWhereF                 = '%-' . str_pad($extraWhere['customer.month'][1], 2, '0', STR_PAD_LEFT) . '-%';
                $extraWhere['ctm_birthdate'] = ['like', $birthWhereF];
                unset($extraWhere['customer.month']);
            }

            if (isset($bWhere['ctm_birthdate']) && !($bWhere['ctm_birthdate'] instanceof \Closure)) {
                $ageStart = $bWhere['ctm_birthdate'][1][0];
                $ageEnd   = $bWhere['ctm_birthdate'][1][1] + 1;
                $bigAge   = getBirthDate($ageStart);
                $smallAge = getBirthDate($ageEnd);

                $bWhere['ctm_birthdate'][1][0] = $smallAge;
                $bWhere['ctm_birthdate'][1][1] = $bigAge;
            }

            if (isset($bWhere['ctm_mobile'])) {
                $mobileOperator  = $bWhere['ctm_mobile'][0];
                $mobileCondition = $bWhere['ctm_mobile'][1];
                unset($bWhere['ctm_mobile']);
                $bWhere[] = function ($query) use ($mobileOperator, $mobileCondition) {
                    $query->where('ctm_mobile', $mobileOperator, $mobileCondition)
                        ->whereOr('ctm_tel', $mobileOperator, $mobileCondition);
                };
            }

            if (isset($bWhere['potential_cpdt'])) {
                $cpdtOperator  = $bWhere['potential_cpdt'][0];
                $cpdtCondition = $bWhere['potential_cpdt'][1];
                unset($bWhere['potential_cpdt']);
                $bWhere[] = function ($query) use ($cpdtOperator, $cpdtCondition) {
                    $query->where('potential_cpdt1', $cpdtOperator, $cpdtCondition)
                        ->whereOr('potential_cpdt2', $cpdtOperator, $cpdtCondition)
                        ->whereOr('potential_cpdt3', $cpdtOperator, $cpdtCondition);
                };
            }

            $total = $this->model->getListCount($bWhere, $extraWhere);
            $list  = $this->model->getList($bWhere, $sort, $order, $offset, $limit, $extraWhere);

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }

        $this->customerExtraInit();

        //上门状态
        $ArriveStatus = ArriveStatus::getList();
        $this->view->assign('ArriveStatus', $ArriveStatus);
        //客服科室
        // $deptments = model('deptment')->field('dept_id,dept_name')->where(['dept_status' => 1])->cache('__cache_brief_dept_list__')->select();
        // $deptList  = ['' => __('NONE')];
        // foreach ($deptments as $deptment) {
        //     $deptList[$deptment['dept_id']] = $deptment['dept_name'];
        // }
        $deptList = (new \app\admin\model\Deptment)->getVariousTree();
        $this->view->assign('deptList', $deptList);
        $deductDepts = model('deptment')->field('dept_id,dept_name')->where(['dept_status' => 1, 'dept_type' => 'deduct'])->select();
        $this->view->assign('deductDepts', $deductDepts);
        $lvl1Pdc = model('Pducat')->where('pdc_pid', '=', 0)->where('pdc_status', '=', 1)->column('pdc_name', 'pdc_id');
        $this->view->assign('lvl1Pdc', $lvl1Pdc);

        $this->view->engine->layout('layout/' . 'columns2');
        return $this->view->fetch('customer/customer/index');
    }

    /**
     * 列表参数处理
     */
    private function handleRequest($type = 'index', $where)
    {
        $extraWhere = [];
        $bWhere     = [];

        foreach ($where as $key => $value) {
            if (strpos($value[0], '.') === false) {
                $bWhere[$value[0]] = [$value[1], $value[2]];
            } else {
                $extraWhere[$value[0]] = [$value[1], $value[2]];
            }
        }

        if (input('op', '') == '') {
            if ($type == 'invalid') {
                $bWhere['ctm_status'] = 0;
            } else {
                $bWhere['ctm_status'] = 1;
            }
        }

        $admin = \think\Session::get('admin');
        //上级部门可以显示下级部门数据
        $developAdminFlg = false;
        $developAdminCon = model('admin')->field('id');
        $deptTree         = \app\admin\model\Deptment::getDeptTreeCache();

        if (isset($extraWhere['admin.dept_id'])) {
            $developAdminFlg  = true;
            $allSelectedDepts = $deptTree->getChildrenIds($extraWhere['admin.dept_id'][1], true);
            $developAdminCon  = $developAdminCon->where(['dept_id' => ['in', $allSelectedDepts]]);
        }
        if (!empty($extraWhere['customer.admin_id'])) {
            $developAdminFlg = true;
            $developAdminCon = $developAdminCon->where(['id' => $extraWhere['customer.admin_id']]);
        }
        if (isset($extraWhere['admin.dept_id'])) {
            unset($extraWhere['admin.dept_id']);
        }

        if ($type == 'publist') {
            $bWhere['ctm_is_public'] = 1;
        } elseif ($type == 'cstpublist') {
            $bWhere['ctm_is_cst_public'] = 1;

            //非超管 只能查看 本部门及下级部门 公有数据
            if (!$this->auth->isSuperAdmin()) {
                $developAdminFlg = true;
                $selfDepts = $deptTree->getChildrenIds($this->view->admin->dept_id, true);
                $developAdminCon  = $developAdminCon->where(['dept_id' => ['in', $selfDepts]]);
            }
        } elseif ($type == 'osconsult') {
            //现场顾客列表过滤
            if (!$this->auth->isSuperAdmin()) {
                $admin = \think\Session::get('admin');
                if ($admin->position == 0) {
                    $bWhere['ctm_last_osc_admin'] = $admin->id;
                } else {
                    $deptAdminConSql = $this->deptAuth->getAdminCondition('id', $admin['id'], false, true);
                    if (stripos($deptAdminConSql, 'WHERE') !== false) {
                        $bWhere['ctm_last_osc_admin'] = ['exp', 'in ' . $deptAdminConSql];
                    }
                }
            }
            $osclistIncludePublic = \think\Config::get('site.osclist_include_public');
            if (empty($osclistIncludePublic)) {
                $bWhere['ctm_is_public'] = 0;
            }
        } elseif ($type == 'listofbirth') {
            $bWhere['ctm_ifbirth']       = 1;
            $birthDate                   = date('m-d');
            $extraWhere['ctm_birthdate'] = function ($query) use ($birthDate) {
                $query->where(['ctm_birthdate' => ['like', '%-' . $birthDate]]);
            };
        } elseif ($type == 'mycstlist') {
            $developAdminFlg = false;
            $extraWhere['customer.admin_id'] = $admin->id;
            $cstlistIncludePublic = \think\Config::get('site.cstlist_include_public');
            if (empty($cstlistIncludePublic)) {
                $bWhere['ctm_is_cst_public'] = 0;
            }
        }

        if ($developAdminFlg) {
            $extraWhere['customer.admin_id'] = ['exp', 'in ' . $developAdminCon->buildSql()];
        }

        return [$bWhere, $extraWhere];
    }

    //外呼接口调用
    public function callPhone()
    {
        die;

        $FromExten = input('FromExten');
        $Exten     = input('Exten');

        $accountid = "N00000004238"; //云呼账号
        $secret    = "f7cc81b0-ac31-11e8-b4e3-c33ff1871943"; //云呼密码
        $time      = date("YmdHis");
//echo $time;exit;
        $authorization = base64_encode($accountid . ":" . $time);
        $sig           = strtoupper(md5($accountid . $secret . $time));

        // $url =   "".$accountid."?sig=".$sig;
        // $url = 'http://apis.7moor.com/v20160818/account/getCCAgentsByAcc/%s?sig=%s';
        // $url = vsprintf($url, array($accountid, $sig));
        $url = 'http://apis.7moor.com/v20160818/call/dialout/%s?sig=%s';
        $url = vsprintf($url, array($accountid, $sig));

        // $data    =   array("midNum"=>$midNum,"called"=>$called,"caller"=>$caller);
        $data = array("Exten" => $Exten, "FromExten" => $FromExten);

        $header[] = "Accept: application/json";
        $header[] = "Content-type: application/json;charset='utf-8'";
        $header[] = "Content-Length: " . strlen(json_encode($data));
        $header[] = "Authorization: " . $authorization;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, ($url)); //地址
        curl_setopt($ch, CURLOPT_POST, 1); //请求方式为post
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); //post传输的数据。
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        $return = curl_exec($ch);

        if ($return === false) {
            echo "CURL Error:" . curl_error($ch);exit;
        }

        curl_close($ch);

        echo ($return);
        exit;

    }

    //首次受理工具修改申请
    public function firstToolIdApply()
    {
        $ctm_id   = input('customerId', false);
        $customer = $this->model->find($ctm_id);
        if (empty($customer)) {
            $this->error('找不到该客户');
        }
        //首次网电记录的受理工具
        $ctmConsultTool = model('customerconsult')->where(['customer_id' => $ctm_id])->order('createtime asc')->find();
        if (!$ctmConsultTool) {
            $this->error('客户没有网电客服记录,请添加');
        }
        $toolList = model('Tooltype')->where(['tool_id' => $ctmConsultTool->tool_id])->find();
        $adminid  = Session::get('admin')->id;

        $FirstToolList                   = array();
        $FirstToolList['customer_id']    = $ctm_id;
        $FirstToolList['apply_info']     = '首次受理工具由自然到诊,更改为' . $ctmConsultTool->tool_id . $toolList->tool_name;
        $FirstToolList['reply_status']   = 0;
        $FirstToolList['apply_admin_id'] = $adminid;
        // $FirstToolList['reply_admin_id'] = $ctm_id;
        $bool = model('FirstToolId')->save($FirstToolList);
        $this->success('申请成功');
    }

    //废弃客户申请
    public function invalidCustomer()
    {
        $customerId = input('customerId', false);
        $customer   = $this->model->find($customerId);
        if (empty($customer)) {
            $this->error('找不到该客户');
        }

        $ifCustomer = model('CustomerInvalid')->where(['customer_id' => $customerId])->find();
        if ($ifCustomer) {
            $this->error('该客户已被标记为废弃客户，请耐心等待管理员核准');
        }

        $CustomerInvalid                   = array();
        $CustomerInvalid['customer_id']    = $customerId;
        $CustomerInvalid['apply_admin_id'] = Session::get('admin')->id;
        $CustomerInvalid['status']         = 0;

        $bool = model('CustomerInvalid')->save($CustomerInvalid);
        $this->success('申请成功，等待管理员核准');
    }

    //顾客移出公有池
    public function publicCustomer()
    {
        $customerId = input('customerId', false);
        $ctmType    = input('ctmType', false);
        $customer   = $this->model->find($customerId);
        if (empty($customer)) {
            $this->error('找不到该客户');
        }
        if ($customer->ctm_is_public == 1 && $ctmType == 'publist') {
            $customer->ctm_is_public   = 0;
            $customer->ctm_public_time = time();
        }
        if ($customer->ctm_is_cst_public == 1 && $ctmType == 'cstpublist') {
            $customer->ctm_is_cst_public = 0;
        }

        $bool = $customer->save();
        $this->success('移出公有客户');
    }

    //客户移出废弃池
    public function invalidcustomerOut()
    {
        $customerId = input('customerId', false);
        $customer   = $this->model->find($customerId);
        if (empty($customer)) {
            $this->error('找不到该客户');
        }
        if ($customer->ctm_status == 0) {
            $customer->ctm_status = 1;
        }
        $bool = $customer->save();
        $this->success('已移出废弃池');
    }

    //修改客户手机号码
    public function customerMobile()
    {
        $newmobile  = input('newmobile', false);
        $customerId = input('customerId', false);
        $customer   = $this->model->find($customerId);
        if (empty($customer)) {
            $this->error('找不到该客户');
        }
        $ctmId = $this->model->where(['ctm_mobile' => $newmobile])->whereOr(['ctm_tel' => $newmobile])->column('ctm_id');
        if ($ctmId && $ctmId[0] != $customerId) {
            $this->error('该号码已被其他客户使用');
        } else {
            $customer->ctm_mobile = $newmobile;
            $customer->save();
            $this->success('手机号修改成功');
        }
    }

    //修改客户推荐人
    public function RecCtmId()
    {
        $newrecCtmId = input('newrecCtmId', false);
        $customerId  = input('customerId', false);
        $customer    = $this->model->find($customerId);
        if (empty($customer)) {
            $this->error('找不到该客户');
        }
        $customer->rec_customer_id = $newrecCtmId;
        $customer->save();
        $this->success('推荐人修改成功');
    }

    //合并客户
    public function MergeHisCustomer()
    {
        $value = input('value');
        if (preg_match_all('/^([\d]+)[\D]+([\d]+)$/', $value, $matches)) {
            if ($matches[1][0] == $matches[2][0]) {
                $this->error('无法对同一个顾客进行合并');
            }
            $maincustomer   = $this->model->find($matches[1][0]);
            $secondcustomer = $this->model->find($matches[2][0]);
            if (empty($maincustomer) || empty($secondcustomer)) {
                $this->error('找不到该客户');
            }
        } else {
            $this->error('请输入2个正确卡号');
        }
        $typeList          = model('Chntype')->getList();
        $sourceList        = \app\admin\model\Ctmsource::column('sce_name', 'sce_id');
        $toolList          = CocAcceptTool::getList();
        $briefAdminList    = model('Admin')->getBriefAdminList2();
        $briefAdminList[0] = '自然到诊';
        $this->view->assign("briefAdminList", $briefAdminList);
        $this->view->assign('maincustomer', $maincustomer);
        $this->view->assign('secondcustomer', $secondcustomer);
        $this->view->assign('typeList', $typeList);
        $this->view->assign('sourceList', $sourceList);
        $this->view->assign('toolList', $toolList);

        return $this->view->fetch();
    }

}
