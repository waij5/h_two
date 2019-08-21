<?php

namespace app\admin\controller\wm;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use think\DB;

/**
 * 物资发料
 *
 * @icon fa fa-circle-o
 */
class Goodscflist extends Backend
{
    
    /**
     * DepotOutks模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $depotList = DB::table('yjy_depot')->where(['type'=>'2', 'status'=>'normal'])->select();
        $this->view->assign('depotList', $depotList);

        $deptmentList = DB::table('yjy_deptment')->where(['dept_status'=>'1'])->select();
        $this->view->assign('deptmentList', $deptmentList);
    }

    // 处方单列表
    public function index(){
        if ($this->request->isAjax()){

            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            // $whereData = $where;
            $list = DB::table('yjy_order_items')->alias('oi')
                ->field('oi.item_id,oi.customer_id,oi.pro_name,dr.order_item_id,dr.deduct_times,dr.status,c.ctm_name,dr.createtime,max(dr.createtime) as drtime,max(dr.order_item_id) as dr_oid')
                ->join('yjy_deduct_records dr', 'oi.item_id=dr.order_item_id', 'LEFT')
                ->join('yjy_customer c', 'oi.customer_id=c.ctm_id', 'LEFT')
                ->join('yjy_admin a', 'oi.prescriber=a.id', 'LEFT')
                ->join('yjy_project pro', 'oi.pro_id=pro.pro_id','LEFT')
                ->where($where)
                ->where('dr.status', 'in',[1,2])
                ->where('oi.item_type', '2')
                // ->order($sort, $order)
                // ->order('dr.status','ASC')
                ->order('drtime','DESC')
                ->limit($offset, $limit)
                ->group('oi.customer_id')
                ->select();
            $total = DB::table('yjy_order_items')->alias('oi')
                ->join('yjy_deduct_records dr', 'oi.item_id=dr.order_item_id', 'LEFT')
                ->join('yjy_customer c', 'oi.customer_id=c.ctm_id', 'LEFT')
                ->join('yjy_admin a', 'oi.prescriber=a.id', 'LEFT')
                ->join('yjy_project pro', 'oi.pro_id=pro.pro_id','LEFT')
                ->where($where)
                ->where('dr.status', 'in',[1,2])
                ->where('oi.item_type', '2')
                ->group('oi.customer_id')
                ->count();

            foreach ($list as $k => $v) {
                //根据开单人获取最新开单科室
                $list[$k]['prescriber'] = DB::table('yjy_order_items')->alias('oi')
                                // ->field('a.nickname')
                                ->join('yjy_admin a', 'oi.prescriber=a.id','LEFT')
                                ->join('yjy_deptment d', 'a.dept_id=d.dept_id','LEFT')
                                ->where('item_id',$v['dr_oid'])
                                ->value('d.dept_name');
                //是否发料，iffy=0表示无待发料的划扣；iffy>0表示有有待发料的划扣。
                $list[$k]['iffy']=DB::table('yjy_order_items')->alias('oi')
                        ->join('yjy_deduct_records dr', 'oi.item_id=dr.order_item_id', 'LEFT')
                        ->where('oi.customer_id',$v['customer_id'])
                        ->where('oi.item_type', '2')
                        ->where('dr.status','1')
                        ->count();


            }
            // $lists = sort($list);

            // array_multisort($tmp,SORT_DESC,$list);
            
            // $deptData = db('admin')->alias('a')->where('id','in',)
            // $total = count($list);
            
            // dump($whereData);
            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
            return $this->view->fetch();
    }




    public function edit($ids = NULL,$depot_id = NULL,$dept_id = NULL){
        if($this->request->isGet()){
        
            if (!$ids){
                $this->error(__('No Results were found'));
            }
            $where=[];
            if($depot_id){
                $where['pro.depot_id'] = $depot_id;
            }
            if($dept_id){
                $where['a.dept_id'] = $dept_id;
            }
            // var_dump($where);
            $list = DB::table('yjy_order_items')->alias('oi')
                    ->field('oi.item_id,oi.customer_id,oi.pro_name,oi.item_total_times,dr.id as drid,dr.order_item_id,dr.deduct_times,dr.status,c.ctm_name,dr.createtime,d.dept_id,d.dept_name,depot.id as depotid,depot.name as depot_name')
                    ->join('yjy_deduct_records dr', 'oi.item_id=dr.order_item_id', 'LEFT')
                    ->join('yjy_customer c', 'oi.customer_id=c.ctm_id', 'LEFT')
                    ->join('yjy_admin a', 'oi.prescriber=a.id', 'LEFT')
                    ->join('yjy_deptment d', 'a.dept_id=d.dept_id','LEFT')
                    ->join('yjy_project pro', 'oi.pro_id=pro.pro_id','LEFT')
                    ->join('yjy_depot depot', 'pro.depot_id=depot.id','LEFT')
                    ->where('oi.customer_id',$ids)
                    ->where('dr.status', 'in',[1,2])
                    ->where('oi.item_type', '2')
                    ->where($where)
                    ->order('dr.id','DESC')
                    // ->order('dr.createtime','DESC')
                    ->select();

            $depotData = [];
            $deptmentData = [];
            

            // var_dump($depotData);var_dump($deptmentData);

            if($list){
                foreach ($list as $k => $v) {
                    $depotData[$v['depotid']] =$v['depot_name'];
                    $deptmentData[$v['dept_id']] =$v['dept_name'];
                }

                $this->view->assign('depotData',$depotData);
                $this->view->assign('deptmentData',$deptmentData);
                $this->view->assign('list',$list);
            }
        }
        return $this->view->fetch();
    }


    public function cf_print($ids=NULL,$depot_id = NULL,$dept_id = NULL,$printType=0){
    //$printType=0 为处方列表的打印；$printType=1 为所有客户的打印。
        if($this->request->isGet() && $printType==0){
            if (!$ids){
                $this->error(__('No Results were found'));
            }
            $ids = explode(',', $ids);
            /*$data = DB::table('yjy_wm_recipe')->alias('r')
                    ->field('r.re_id,sl.sldr_id, sl.slcustomer_id, sl.slnum, sl.slotid, d.name as dname,p.pro_name, p.pro_spec, lot.lotnum, u.name as uname')
                    ->join('yjy_wm_stocklog sl', 'r.rsl_id = sl.sl_id', 'LEFT')
                    ->join('yjy_wm_lotnum lot', 'sl.slotid = lot.lot_id', 'LEFT')
                    ->join('yjy_project p', 'lot.lpro_id = p.pro_id', 'LEFT')
                    ->join('yjy_unit u', 'p.pro_unit = u.id', 'LEFT')
                    ->join('yjy_depot d', 'p.depot_id = d.id', 'LEFT')
                    ->where('r.rdr_id', 'in',$ids)
                    ->order('r.rdr_id', 'DESC')
                    ->select();*/
            $data = DB::table('yjy_deduct_records')->alias('dr')
                    ->field('dr.id as drid,p.pro_name,p.pro_spec,dr.deduct_times,d.name as dname,u.name as uname')
                    ->join('yjy_order_items oi','dr.order_item_id=oi.item_id','LEFT')
                    ->join('yjy_project p', 'oi.pro_id=p.pro_id','LEFT')
                    ->join('yjy_unit u', 'p.pro_unit = u.id', 'LEFT')
                    ->join('yjy_depot d', 'p.depot_id=d.id','LEFT')
                    ->where('dr.id', 'in',$ids)
                    ->order('dr.id', 'DESC')
                    ->select();
            if($data){
                $this->view->assign('data',$data);
            }
        }else if($this->request->isGet() && $printType==1){
            if (!$ids){
                $this->error(__('No Results were found'));
            }
            $ids = explode(',', $ids);
            $where=[];
            if($depot_id){
                $where['p.depot_id'] = $depot_id;
            }
            if($dept_id){
                $where['a.dept_id'] = $dept_id;
            }

            $allPdata = DB::table('yjy_deduct_records')->alias('dr')
                    ->field('dr.id as drid,p.pro_id,p.pro_name,p.pro_spec,sum(dr.deduct_times) as all_deduct_times,d.name as dname,u.name as uname')
                    ->join('yjy_order_items oi','dr.order_item_id=oi.item_id','LEFT')
                    ->join('yjy_project p', 'oi.pro_id=p.pro_id','LEFT')
                    ->join('yjy_admin a', 'oi.prescriber=a.id', 'LEFT')
                    ->join('yjy_unit u', 'p.pro_unit = u.id', 'LEFT')
                    ->join('yjy_depot d', 'p.depot_id=d.id','LEFT')
                    ->where('oi.customer_id', 'in',$ids)
                    ->where('dr.status', 1)
                    ->where('oi.item_type', '2')
                    ->where($where)
                    ->group('p.pro_id')
                    ->order('dr.id', 'DESC')
                    ->select();
                    // var_dump($allPdata);
            if($allPdata){
                $this->view->assign('allPdata',$allPdata);
            }
        }
        return $this->view->fetch();
    }





}
