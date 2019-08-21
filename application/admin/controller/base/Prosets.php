<?php

namespace app\admin\controller\base;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use think\Db;
use app\admin\model\Project;

/**
 * 套餐
 *
 * @icon fa fa-circle-o
 */
class Prosets extends Backend
{
    
    /**
     * ProSets模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('ProSets');

        //项目，处方， 物资
        $this->view->assign('setTypeList', Project::getTypeList());
    }


    /**
     * 删除
     */
    public function del($ids = "")
    {
        if ($ids)
        {
            $count = $this->model->destroy($ids);
            if ($count !== false) {
                model('ProSetItems')->where(['pro_set_id' => $ids])->delete();
            }

            $this->success();
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }

    /**
     * 弹窗选择
     * @param string mode single, multi, redirect
     * @param string parentSelector
     * 
     */
    public function comselectpop() {
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            $extraWhere = ['set_status' => 1];
            if (($setType = input('set_type', false))) {
                $extraWhere['set_type'] = $setType;
            }

            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->where($where)
                    ->where($extraWhere)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->where($where)
                    ->where($extraWhere)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }

        $url = 'base/prosets/comselectpop?set_status=1';
        if (($setType = input('setType', false))) {
            $url .= '&set_type=' . $setType;
        }
        $yjyComSelectParams = [
                                'url' => $url,
                                'pk' => 'set_id',
                                'sortName' => 'set_id',
                                'search' => false,
                                'commonSearch' => false,
                                //多选时表格 JQUERY 选择器
                                'parentSelector' => '#t-prosets-select',
                                'columns' => [
                                    ['field' => 'set_id', 'title' => __('Set_id')],
                                    ['field' => 'set_name', 'title' => __('Set_name'), 'formatter' => 'Backend.api.formatter.content'],
                                    ['field' => 'set_remark', 'title' => __('Set_remark'), 'formatter' => 'Backend.api.formatter.content'],
                                ]
                            ];
        
        $fields = ['set_id', 'set_name', 'set_type', 'set_status', 'set_remark'];

        comselectinitparams($yjyComSelectParams, $fields);

        $yjyComSelectParams = json_encode($yjyComSelectParams);
        $this->view->assign('yjyComSelectParams', $yjyComSelectParams);

        return $this->view->fetch();
    }

    /**
     * 套餐开单回调
     * 方法基本与prosetitems中一致
     * 为保持 非套餐分类开单及原JS基本不改
     * 此处php部分有修改
     */
    public function render($setId = null)
    {
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            $proSet = model('ProSets')->get($setId);

            if (!$proSet) {
                $this->error(__('No Results were found'));
            }

            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $subQuery = model('ProSetItems')
                    ->where(['pro_set_id' => $setId])
                    ->buildSql();

            $list = $this->model
                        ->table($subQuery . ' pro_set_item')
                        ->field('pro_set_item.set_item_id, pro_set_item.pro_set_id, pro_set_item.set_item_qty as row_qty, pro_set_item.set_item_amount as row_amount, pro_type, pro.pro_id, pro.pro_name, pro.pro_amount, pro.pro_spec, pro.pro_stock')
                        ->join(Db::getTable('project') . ' pro', 'pro_set_item.set_pro_id = pro.pro_id', 'LEFT')
                        ->order('set_item_id', 'ASC')
                        ->select();

            return json($list);
        }
    }
}
