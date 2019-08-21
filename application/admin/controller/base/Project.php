<?php

namespace app\admin\controller\base;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use think\Db;
use app\admin\model\Fpro;

/**
 * 治疗项目管理
 *
 * @icon fa fa-circle-o
 */
class Project extends Backend
{
    
    /**
     * Project模型对象
     */
    protected $model = null;
    protected $modelDept = null;

    protected $noNeedRight = ['ajaxlist', 'comselectpop', ];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Project');
        $this->modelDept = model('Deptment');
        // $this->deptlist = $this->modelDept->getVariousTree("dept_id", "dept_id", "desc", "dept_pid", "dept_name");
        // $deptdata = [];
        // foreach ($this->deptlist as $k => $v)
        // {
        //     $deptdata[$v['id']] = $v['dept_name'];
        // }
        $deptlist = $this->modelDept->where(['dept_type' => 'deduct'])->select();
        $deptdata = [];
        $deptdata  = ['' => __('NONE')];
        foreach ($deptlist as $k => $v)
        {
            $deptdata[$v['dept_id']] = $v['dept_name'];
        }
        $this->view->assign('deptdata',$deptdata);

        $proFeeType = model('Fee')->getList();
        $this->view->assign("proFeeType", $proFeeType);

        $list = model('pducat')->where(['pdc_pid' => 0,'pdc_status' => 1])->column('pdc_name', 'pdc_id');
        $this->view->assign('list',$list);
        
    }

    /**
     * 查看
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name'))
            {
               return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $total = $this->model->alias('pro')
                    ->where(['pro_type' => \app\admin\model\Project::TYPE_PROJECT])
                    ->join(Db::getTable('deptment') . ' dept', 'dept.dept_id = pro.dept_id', 'LEFT')
                    ->join((new Fpro)->getTable() . ' fpro', 'pro.pro_id = fpro.pro_id', 'LEFT')
                    ->where($where)
                    ->order($sort, $order)
                    ->count();
                    
            $list =  model('Project')->alias('pro')
                    ->field('pro.*,dept.dept_name, fpro.id as fpro_id, fpro.status as fpro_status')
                    ->join(Db::getTable('deptment') . ' dept', 'dept.dept_id = pro.dept_id', 'LEFT')
                    ->join((new Fpro)->getTable() . ' fpro', 'pro.pro_id = fpro.pro_id', 'LEFT')
                    ->where(['pro_type' => \app\admin\model\Project::TYPE_PROJECT])
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            //类别
            $pdcList = model('pducat')->column('pdc_name', 'pdc_id');
            //费用类型
            $proFeeType = model('Fee')->getList();

            foreach ($list as $key => $row) {
                if(isset($pdcList[$row['pro_cat1']])){
                    $list[$key]['pro_cat1'] = $pdcList[$row['pro_cat1']];
                }else{
                    $list[$key]['pro_cat1'] = '';
                }
                if(isset($pdcList[$row['pro_cat2']])){
                    $list[$key]['pro_cat2'] = $pdcList[$row['pro_cat2']];
                }else{
                    $list[$key]['pro_cat2'] = '';
                }
                if(isset($pdcList[$row['pro_cat3']])){
                    $list[$key]['pro_cat3'] = $pdcList[$row['pro_cat3']];
                }else{
                    $list[$key]['pro_cat3'] = '';
                }

                $list[$key]['pro_fee_type_name'] = '';
                if ($row['pro_fee_type'] && isset($proFeeType[$row['pro_fee_type']])) {
                    $list[$key]['pro_fee_type_name'] = $proFeeType[$row['pro_fee_type']];
                }
            }

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

     /**
     * 获取进度信息
     */
    public function downloadprocess()
    {
        
        return $this->commondownloadprocess('project', 'All business project statistic');
    }

    /**
     * 添加
     */
    public function add()
    {
        $list = model('pducat')->where(['pdc_pid' => 0,'pdc_status' => 1])->column('pdc_name', 'pdc_id');
        $this->view->assign('list',$list);

        if ($this->request->isPost())
        {
 
            $params = $this->request->post("row/a");

            if($params['pro_cat1'] == 0){
                $this->error(__('No choose cat'));
            }
            $params['pro_type'] = \app\admin\model\Project::TYPE_PROJECT;

            $result = $this->model->create($params);
            if($result)
            {
                $this->success();
            }else
            {
                $this->error();
            }
        }   

        $proFeeType = model('Fee')->getList();
        $this->view->assign('proFeeType', $proFeeType);

        return $this->view->fetch();
    }

    public function edit($ids = NULL)
    {
        $list = model('pducat')->where(['pdc_pid' => 0])->column('pdc_name', 'pdc_id');
        $this->view->assign('list',$list);

        $row = $this->model->get(['pro_id' => $ids]);

        if(!$row)
            $this->error(__('No Results were found'));

        if($this->request->isPost())
        {
            $params = ['' => __('All')];
            // none all
            $params = $this->request->post("row/a");
            $params['pro_type'] = \app\admin\model\Project::TYPE_PROJECT;

            $result = $row->save($params);
            if($result !== false)
            {
                $this->success();
            }else
            {
                $this->error();
            }
        }   

        $this->view->assign("row", $row);

        $proFeeType = model('Fee')->getList();
        $this->view->assign('proFeeType', $proFeeType);
        return $this->view->fetch();
    }

    public function getLv2Cate() 
    {
        $pid = input('cate_id');

        $data = ['' => __('None')];
        $list = model('pducat')->where(['pdc_pid' => $pid])->column('pdc_name', 'pdc_id');
        foreach($list as $key => $value) {
            $data[$key] = $value;
        }

        return json($data);
    }

    /**
     * 快速检索
     */
    public function ajaxlist($type = 'PROJECT')
    {
        list($where, $sort, $order, $offset, $limit) = $this->buildparams();
        $pageNum = input('pageNum', 0);
        if ((int)$pageNum <= 0) {
            $pageNum = 1;
        }

        $sort = 'pro_id';
        $order = 'DESC';
        $limit = \think\Config::get('site.ajax_list_page_count');
        $limit = intval($limit) ? intval($limit) : 15;
        $offset = ($pageNum - 1) *  $limit;

        $extraWhere = ['pro_status' => 1];

        switch ($type) {
            case 'PROJECT':
                $extraWhere['pro_type'] = \app\admin\model\Project::TYPE_PROJECT;
                break;
            case 'MEDICINE':
                $extraWhere['pro_type'] = \app\admin\model\Project::TYPE_MEDICINE;
                break;
            case 'PRODUCT':
                $extraWhere['pro_type'] = \app\admin\model\Project::TYPE_PRODUCT;
                break;
            default:
                break;
        }

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
                ->column('*', 'pro_id');
        $maxPageNum = ceil($total / $limit);

        $result = array("total" => $total, "rows" => $list, 'hasMore' => ($maxPageNum > $pageNum));

        return json($result);
    }
    
    /**
     * 弹窗选择
     * @param string mode single, multi, redirect
     * @param string parentSelector
     * 
     */
    public function comselectpop() {

        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $total = $this->model
                    ->where($where)
                    ->where(['pro_status' => 1])
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->where($where)
                    ->where(['pro_status' => 1])
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }


        $url = 'base/project/comselectpop';

        $yjyComSelectParams = [
                                'url' => $url,
                                'pk' => 'pro_id',
                                'sortName' => 'pro_id',
                                'search' => false,
                                'commonSearch' => false,
                                //多选时表格 JQUERY 选择器
                                'parentSelector' => '#t-project-select',
                                'columns' => [
                                    ['field' => 'pro_id', 'title' => __('Pro_id')],
                                    ['field' => 'pro_code', 'title' => __('Pro_code')],
                                    ['field' => 'pro_name', 'title' => __('Pro_name')],
                                    ['field' => 'pro_use_times', 'title' => __('Pro_use_times')],
                                    ['field' => 'pro_amount', 'title' => __('Pro_amount')],
                                    // ['field' => 'pro_price', 'title' => __('Pro_price')],
                                    ['field' => 'pro_remark', 'title' => __('Pro_remark'), 'formatter' => 'Backend.api.formatter.content'],
                                ]
                            ];
        
        $fields = ['pro_id', 'pro_code', 'pro_name', 'pro_amount'];

        comselectinitparams($yjyComSelectParams, $fields);

        $yjyComSelectParams = json_encode($yjyComSelectParams);
        $this->view->assign('yjyComSelectParams', $yjyComSelectParams);

        return $this->view->fetch();
    }

}
