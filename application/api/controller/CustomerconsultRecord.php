<?php

namespace app\api\controller;

use app\common\controller\Api;
use think\Controller;
use think\Session;
use think\Request;
use app\admin\model\CustomerConsult;
use app\admin\model\Customerosconsult;
use app\admin\model\Admin;
use app\admin\model\Customer;
use app\admin\model\Customertype;
use app\admin\model\Ctmchannels;
use app\admin\model\Ctmsource;
use app\admin\model\Cproject;
use app\admin\model\Deptment;
use app\admin\model\Chntype;
use app\admin\model\CocAccepttool;
use app\admin\model\Fat;
use app\admin\model\ArriveStatus;
use app\admin\model\Rvinfo;



/**
 *
 *
 * @icon fa fa-circle-o
 */
class CustomerconsultRecord extends Api
{

// 传过来手机号,ID判断admin判断是否属于，客服有效天数，是否是超管,可以的返回url，不可以的提示
// 查询数据显示，是否必填，类型excel
// 接受传过来的数据保存，post

    public function judge(){
        $admin = Request::instance()->admin;
        $adminId = $admin->id;

        $phone = 12345678901;
        $customerId = 1;

        $customerM = new Customer;
        $this->model = new CustomerConsult;

        $customerCount = $customerM->where(['ctm_mobile' => ['like', "%$phone%"]])->whereOr(['ctm_tel' => ['like', "%$phone%"]])->count();

        $url = 'customer/customerconsult/add'
        if ($customerCount == 0) {
            //未查找任意记录,直接跳转新增
            // return redirect('customer/customerconsult/add', ['phone' => $phone, 'dialog' => 1]);
             $this->success('成功', null, ['url' => $url, 'phone' => $phone]);
        }else{
             $consultList = $this->model->alias('cst')
            ->field('cst.*, fat.fat_name, admin.nickname as admin_nickname, admin.dept_id as cst_admin_dept_id, cst.dept_id,  customer.ctm_name, customer.ctm_mobile, customer.arrive_status, customer.ctm_addr, cproject.cpdt_name,customer.ctm_id, customer.admin_id as develop_admin_id, sce.sce_name as ctm_source, channels.chn_name as ctm_explore,
                customer.ctm_last_osc_dept_id as coc_dept_id, customer.ctm_last_recept_time as coctime, customer.ctm_last_osc_admin as coc_admin_id' 
                    )
            ->join(Db::getTable('admin') . ' admin', 'cst.admin_id=admin.id', 'LEFT')
            ->join(Db::getTable('fat') . ' fat', 'cst.fat_id=fat.fat_id', 'LEFT')
            ->join(Db::getTable('customer') . ' customer', 'cst.customer_id=customer.ctm_id', 'LEFT')
            ->join(Db::getTable('c_project') . ' cproject', 'cst.cpdt_id=cproject.id', 'LEFT')
            ->join(Db::getTable('ctmsource') . ' sce', 'customer.ctm_source=sce.sce_id', 'LEFT')
            ->join(Db::getTable('ctmchannels') . ' channels', 'customer.ctm_explore=channels.chn_id', 'LEFT')
            ->where(['customer.ctm_mobile' => ['like', "%$phone%"]])
            ->whereOr(['customer.ctm_tel' => ['like', "%$phone%"]])
            ->find();
            // $customer = $customerM->where(['ctm_mobile' => ['like', "%$phone%"]])->whereOr(['ctm_tel' => ['like', "%$phone%"]])->find();
            // $consultList = $this->model->where(['customer_id' => $customer->ctm_id])->find();
            //有记录,判断登陆admin_id与录入信息的admin_id是否相同,以及上一条记录的客服受理人员
            if($adminId == $consultList->develop_admin_id || $adminId == $consultList->admin_id){
                // return redirect('customer/customerconsult/add', ['phone' => $phone, 'customer_id' => $consultList->ctm_id, 'dialog' => 1]);
                 $this->success('成功', null, ['url' => $url, 'phone' => $phone, 'customer_id' => $consultList->ctm_id]);
            }else{
                 $this->success('成功', null, ['url' => $url, 'phone' => $phone]);
            }
        }
    }



	public function create(){
        $phone = 12345678901;
        $customerM = new Customer;
		$this->model = new CustomerConsult;

        //职员
		$adminM = new Admin;
		$briefAdminList = $adminM->getBriefAdminList2();
        //客户类型
        $customertypeM = new Customertype;
        $ctmtypeLists = $customertypeM->field('type_id,type_name')->select();
        $ctmtypeList  = ['' => __('NONE')];
        foreach ($ctmtypeLists as $ctmtype) {
            $ctmtypeList[$ctmtype['type_id']] = $ctmtype['type_name'];
        }
        //营销渠道
        $CtmchannelsM = new Ctmchannels;
        $channelLists = $CtmchannelsM->field('chn_id, chn_name')->where(['chn_status' => 1])->order('chn_sort', 'desc')->select();
        $channelList  = ['' => __('NONE')];
        foreach ($channelLists as $source) {
            $channelList[$source['chn_id']] = $source['chn_name'];
        }
       //客户来源
        $CtmsourceM = new Ctmsource;
        $ctmSource  = $CtmsourceM->field('sce_id, sce_name')->where(['sce_status' => 1])->order('sce_sort', 'desc')->select();
        $ctmSrcList = ['' => __('NONE')];
        foreach ($ctmSource as $ctmSrc) {
            $ctmSrcList[$ctmSrc['sce_id']] = $ctmSrc['sce_name'];
        }
        //客服项目
        $cprojectM = new Cproject;
        $cproLists = $cprojectM->field('id,cpdt_name')->where(['cpdt_status' => 1])->select();
        $cproList  = ['' => __('NONE')];
        foreach ($cproLists as $cpro) {
            $cproList[$cpro['id']] = $cpro['cpdt_name'];
        }
        //客服科室
        $deptList = \app\admin\model\Deptment::getDeptListCache();
        // 受理类型
        $typeList = \app\admin\model\Chntype::getList();
        //受理工具
        $toolList = \app\admin\model\CocAccepttool::getList();
        //未成交原因
        $fatM = new Fat;
        $fatLists = $fatM->field('fat_id,fat_name')->select();
        $fatList  = ['' => __('NONE')];
        foreach ($fatLists as $fatvalue) {
            $fatList[$fatvalue['fat_id']] = $fatvalue['fat_name'];
        }
        // 客户上门状态
        $ArriveStatus = \app\admin\model\ArriveStatus::getList();
        //回访类型
        $rvoinfoM = new Rvinfo;
        $rvinfoLists = $rvoinfoM->field('rvt_id,rvt_name')->select();
        $rvinfoList  = ['' => __('NONE')];
        foreach ($rvinfoLists as $rvvalue) {
            $rvinfoList[$rvvalue['rvt_id']] = $rvvalue['rvt_name'];
        }

        $this->success('成功', null, [ 
            'briefAdminList' => $briefAdminList,
             'ctmtypeList'   => $ctmtypeList,
             'channelList'   => $channelList,
             'ctmSrcList'    => $ctmSrcList,
             'cproList'      => $cproList,
             'deptList'      => $deptList,
             'typeList'      => $typeList,
             'toolList'      => $toolList,
             'fatList'       => $fatList,
             'ArriveStatus'  => $ArriveStatus,
             'rvinfoList'    => $rvinfoList,
             ]);  

	}

    public function store(){
        //接收传过来的具体信息 params customer
        $consultParams = Request::instance()->params;
        $customerParams = Request::instance()->customer;
        $bookParams = Request::instance()->book;
        $rvplanParams = Request::instance()->rvplan;

        $admin = Request::instance()->admin;

        $customerM  =  new customer;
        $CustomerosconsultM = new Customerosconsult;
        $consultM = new CustomerConsult;


        //手机电话双判断
            if (!empty($customerParams['ctm_tel'])) {
                $checkCustomerId = empty($customerParams['ctm_id']) ? 0 : $customerParams['ctm_id'];
                $phoneId         = $customerM->where(['ctm_id' => ['neq', $checkCustomerId]])->where(['ctm_mobile' => $customerParams['ctm_tel']])->column('ctm_id');
                if ($phoneId) {
                    $this->error('手机号码已存在');
                } 
            }

            $where         = [];
            if (!empty($customerParams['ctm_id'])) {
                $oldCustomer = $customerM->get(['ctm_id' => $customerParams['ctm_id']]);
                if (empty($oldCustomer)) {
                    $this->error('客户不存在');
                }

                //对于已有记录的客户，如手机/电话有记录，阻止修改
                if ($oldCustomer['ctm_mobile'] && isset($customerParams['ctm_mobile'])) {
                    $customerParams['ctm_mobile'] = $oldCustomer['ctm_mobile'];
                }
                if ($oldCustomer['ctm_tel'] && isset($customerParams['ctm_tel'])) {
                    $customerParams['ctm_tel'] = $oldCustomer['ctm_tel'];
                }
                $where = ['ctm_id' => $customerParams['ctm_id']];
            } else {
                $customerParams['admin_id'] = empty($consultParams['admin_id']) ? $admin['id'] : $consultParams['admin_id'];

                $customerParams['ctm_first_tool_id'] = isset($consultParams['tool_id']) ? $consultParams['tool_id'] : 0;
                $customerParams['ctm_first_dept_id'] = isset($consultParams['dept_id']) ? $consultParams['dept_id'] : 0;
                $customerParams['ctm_first_cpdt_id'] = isset($consultParams['cpdt_id']) ? $consultParams['cpdt_id'] : 0;
            }
            // 如果该用户在之前有过现场客服，则显示已上门
            if (!empty($customerParams['ctm_id']) && $CustomerosconsultM->where(['customer_id' => $customerParams['ctm_id']])->count()) {
                $customerParams['arrive_status'] = 1;
            }

            if ($customerM->saveWhenConsult($customerParams, $where) === false) {
                $this->error(__('Failed while trying to save customer data！'));
            }

             //获取 新增/更新 的顾客ID
            if ($customerParams['ctm_id']) {
                $customerId = $customerParams['ctm_id'];
            } else {
                $customerId = $customerM->getLastInsID();
            }

            //新增，==
            if (empty($consultParams['admin_id'])) {
                $consultParams['admin_id'] = $admin['id'];
            }

            //预约成功，清除失败原由
            if ($consultParams['cst_status']) {
                unset($consultParams['fat_id']);
                $consultParams['book_time'] = strtotime($consultParams['book_time']);
            } else {
                if (isset($consultParams['book_time'])) {
                    unset($consultParams['book_time']);
                }
            }

            $consultParams['customer_id'] = $customerId;

            if ($consultM->save($consultParams) === false) {
                $this->error(__('Failed while trying to save consult data！'));
            } else {
                //增加回访记录
                if (!empty($rvplanParams)) {
                    $rvplanName    = empty($rvplanParams['rv_plan']) ? '' : $rvplanParams['rv_plan'];
                    $rvplanAdminId = empty($rvplanParams['admin_id']) ? '' : $rvplanParams['admin_id'];

                    if (!empty($rvplanParams['admin_id']) && !empty($rvplanParams['rvt_type'])) {
                        foreach ($rvplanParams['rvt_type'] as $key => $value) {
                            if (empty($rvplanParams['rv_date'][$key])) {
                                continue;
                            }

                            $rvinfo              = new Rvinfo;
                            $rvinfo->rvi_tel     = $customerM->ctm_mobile;
                            $rvinfo->customer_id = $customerM->ctm_id;
                            $rvinfo->rvt_type    = $rvplanParams['rvt_type'][$key];
                            $rvinfo->rvi_content = $rvplanParams['rv_remark'][$key];
                            $rvinfo->rv_plan     = $rvplanParams['rv_plan'];
                            $rvinfo->rv_date     = $rvplanParams['rv_date'][$key];
                            $rvinfo->admin_id    = $rvplanParams['admin_id'];
                            $rvinfo->rv_is_valid = 0;
                            $rvinfo->save();
                        }
                    }
                }
            }

             //hook
            \think\Hook::listen(\app\admin\model\CustomerConsult::TAG_SAVE_CONSULT, $consultM, $customerM));

            $this->success(__('All data saved successfully.'));

    }


}

