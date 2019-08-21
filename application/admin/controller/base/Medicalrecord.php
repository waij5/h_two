<?php

namespace app\admin\controller\base;

use app\common\controller\Backend;
use think\Controller;

/**
 * 电子病历
 *
 * @icon fa fa-circle-o
 */
class Medicalrecord extends Backend
{

    /**
     * Coupon模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {

    }

    /**
     * 模板列表
     */
    public function templateList()
    {

    }

    /**
     * 模板新增
     */
    public function templateAdd()
    {
        // return $this->view->fetch();
    }

    /**
     * 模板编辑
     */
    public function templateEdit()
    {

    }

    /**
     * 模板编辑
     */
    public function templateDelete()
    {

    }

    /**
     * 元数据列表
     */
    public function metaList()
    {

    }
    /**
     * 元数据编辑
     */
    public function metaAdd()
    {

    }

    /**
     * 元数据编辑
     */
    public function metaEdit()
    {

    }

    /**
     * 元数据编辑
     */
    public function metaDelete()
    {

    }

    /**
     * 根据顾客卡号， 模板ID
     * 新建病历
     */
    public function caseAdd($customerId, $templateId)
    {
    }

    public function add()
    {
    }

    public function edit($ids = null)
    {
    }

    public function delete($ids = '')
    {
    }

    public function multi($ids = '')
    {
    }

}