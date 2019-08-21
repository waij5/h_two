<?php

namespace app\api\controller;

use app\admin\model\CustomerOsconsult as MCustomerosconsult;
use app\common\controller\Api;
use think\Controller;
use think\Request;

/**
 *
 *
 * @icon fa fa-circle-o
 */
class Customerosconsult extends Api
{
    public function index()
    {
        // $adminId = 1;
        $admin = Request::instance()->admin;

        $adminId = $admin->id;

        $this->model = new MCustomerosconsult;

        // if ($this->request->isAjax()) {

        // list($bWhere, $sort, $order, $offset, $limit, $extraWhere) = $this->dealParams(null, null);
        $sort   = '';
        $order  = '';
        $offset = '';
        $limit  = '';

        $bWhere['coc.admin_id'] = $adminId;
        $extraWhere             = [];

        $total = $this->model->getListCount($bWhere, $extraWhere);
        $list  = $this->model->getList($bWhere, $sort, $order, $offset, $limit, $extraWhere);

        // $result = array("total" => $total, "rows" => $list);

        // return json($result);
        $this->success('成功', null, ['total' => $total, 'list' => $list]);
        // }
    }

    /**
     * 今日客服清单
     * 只显示自己的已接受的 内容较少 不做分页处理
     */
    public function todayvisit()
    {
        $admin      = Request::instance()->admin;
        $todayStart = strtotime(date('Y-m-d'));
        $todayEnd   = time();
        $where      = [
            'coc.admin_id'   => $admin->id,
            'coc.createtime' => ['between', [$todayStart, $todayEnd]],
            'coc.osc_status' => ['in', [
                MCustomerosconsult::STATUS_CONSULTING,
                MCustomerosconsult::STATUS_SUCCESS,
                MCustomerosconsult::STATUS_SUCCESS_PAYED,
                MCustomerosconsult::STATUS_FAIL,
            ]],
        ];
        $list = (new MCustomerosconsult)->getList($where, 'createtime', 'DESC', 0, 999);
        array_map(function ($row) {
            //手机号 隐藏中间号码
            $row['ctm_mobile'] = getMaskString($row['ctm_mobile']);
            $row['ctm_tel']    = getMaskString($row['ctm_tel']);
        }, $list);

        $this->success('获取分诊信息成功', null, $list);
    }
}
