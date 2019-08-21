<?php

namespace app\admin\model;

use think\Model;

class Manifest extends Model
{
    // 表名   货单表
    protected $name = 'wm_manifest';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'mcreatetime';
    protected $updateTime = '';


    public function getRkType(){
    	return ['21' =>  __('rk_type 21'), '22' => __('rk_type 22'), '23' => __('rk_type 23'), '24' => __('rk_type 24')];
    }

    public function getCjType(){
        return ['31' =>  __('cj_type 31'), '32' => __('cj_type 32'), '33' => __('cj_type 33'), '34' => __('cj_type 34')];
    }

    public function getPsiType(){
        return ['1' => __('Type 1'), '2' =>  __('Type 2'), '3' => __('Type 3')];
    }


    public function getChangedetailsType(){
        return ['1' => __('Type 1'), '21' =>  __('Type 21'), '22' =>  __('Type 22'), '23' =>  __('Type 23'), '24' =>  __('Type 24'), '31' => __('Type 31'), '32' => __('Type 32'), '33' => __('Type 33'), '34' => __('Type 34'), '4' => __('Type 4'), '5' => __('Type 5'), '6' => __('Type 6'), '7' => __('Type 7')];
    }

   /* public function judgeRepeatArray($array){
        // 获取去掉重复数据的数组   
        $newArr =[];
        $newArr['unique_arr'] = array_unique($array);   
        // 获取重复数据的数组   
        $newArr['repeat_arr'] = array_diff_assoc($array, $unique_arr );   
        return $newArr;
    }*/
    public static function stockTypeDeal($data, $type =''){
        if($data && $type==''){

            foreach ($data as $key => $value) {

                if(!isset($data[$key]['jh'])){
                    $data[$key]['jh'] = 0;
                }
                if(!isset($data[$key]['jhallcost'])){
                    $data[$key]['jhallcost'] = 0;
                }
                if(!isset($data[$key]['jhallprice'])){
                    $data[$key]['jhallprice'] = 0;
                }
                if(!isset($data[$key]['qtrk'])){
                    $data[$key]['qtrk'] = 0;
                }
                if(!isset($data[$key]['qtrkallcost'])){
                    $data[$key]['qtrkallcost'] = 0;
                }
                if(!isset($data[$key]['qtrkallprice'])){
                    $data[$key]['qtrkallprice'] = 0;
                }
                if(!isset($data[$key]['dbrk'])){
                    $data[$key]['dbrk'] = 0;
                }
                if(!isset($data[$key]['dbrkallcost'])){
                    $data[$key]['dbrkallcost'] = 0;
                }
                if(!isset($data[$key]['dbrkallprice'])){
                    $data[$key]['dbrkallprice'] = 0;
                }
                if(!isset($data[$key]['pyrk'])){
                    $data[$key]['pyrk'] = 0;
                }
                if(!isset($data[$key]['pyrkallcost'])){
                    $data[$key]['pyrkallcost'] = 0;
                }
                if(!isset($data[$key]['pyrkallprice'])){
                    $data[$key]['pyrkallprice'] = 0;
                }
                if(!isset($data[$key]['thrk'])){
                    $data[$key]['thrk'] = 0;
                }
                if(!isset($data[$key]['thrkallcost'])){
                    $data[$key]['thrkallcost'] = 0;
                }
                if(!isset($data[$key]['thrkallprice'])){
                    $data[$key]['thrkallprice'] = 0;
                }
                if(!isset($data[$key]['rkcj'])){
                    $data[$key]['rkcj'] = 0;
                }
                if(!isset($data[$key]['rkcjallcost'])){
                    $data[$key]['rkcjallcost'] = 0;
                }
                if(!isset($data[$key]['rkcjallprice'])){
                    $data[$key]['rkcjallprice'] = 0;
                }
                if(!isset($data[$key]['pkcj'])){
                    $data[$key]['pkcj'] = 0;
                }
                if(!isset($data[$key]['pkcjallcost'])){
                    $data[$key]['pkcjallcost'] = 0;
                }
                if(!isset($data[$key]['pkcjallprice'])){
                    $data[$key]['pkcjallprice'] = 0;
                }
                if(!isset($data[$key]['gqcj'])){
                    $data[$key]['gqcj'] = 0;
                }
                if(!isset($data[$key]['gqcjallcost'])){
                    $data[$key]['gqcjallcost'] = 0;
                }
                if(!isset($data[$key]['gqcjallprice'])){
                    $data[$key]['gqcjallprice'] = 0;
                }
                if(!isset($data[$key]['qtcj'])){
                    $data[$key]['qtcj'] = 0;
                }
                if(!isset($data[$key]['qtcjallcost'])){
                    $data[$key]['qtcjallcost'] = 0;
                }
                if(!isset($data[$key]['qtcjallprice'])){
                    $data[$key]['qtcjallprice'] = 0;
                }
                if(!isset($data[$key]['ly'])){
                    $data[$key]['ly'] = 0;
                }
                if(!isset($data[$key]['lyallcost'])){
                    $data[$key]['lyallcost'] = 0;
                }
                if(!isset($data[$key]['lyallprice'])){
                    $data[$key]['lyallprice'] = 0;
                }
                if(!isset($data[$key]['ll'])){
                    $data[$key]['ll'] = 0;
                }
                if(!isset($data[$key]['llallcost'])){
                    $data[$key]['llallcost'] = 0;
                }
                if(!isset($data[$key]['llallprice'])){
                    $data[$key]['llallprice'] = 0;
                }

                if(!isset($data[$key]['fy'])){
                    $data[$key]['fy'] = 0;
                }
                if(!isset($data[$key]['fyallcost'])){
                    $data[$key]['fyallcost'] = 0;
                }
                if(!isset($data[$key]['fyallprice'])){
                    $data[$key]['fyallprice'] = 0;
                }

                if(!isset($data[$key]['cy'])){
                    $data[$key]['cy'] = 0;
                }
                if(!isset($data[$key]['cyallcost'])){
                    $data[$key]['cyallcost'] = 0;
                }
                if(!isset($data[$key]['cyallprice'])){
                    $data[$key]['cyallprice'] = 0;
                }

            }
            
            return $data;

        }elseif($data && $type=='1'){
            foreach ($data as $key => $value) {

                if(!isset($data[$key]['jh'])){
                    $data[$key]['jh'] = 0;
                }
                
                if(!isset($data[$key]['qtrk'])){
                    $data[$key]['qtrk'] = 0;
                }
                
                if(!isset($data[$key]['dbrk'])){
                    $data[$key]['dbrk'] = 0;
                }
                
                if(!isset($data[$key]['pyrk'])){
                    $data[$key]['pyrk'] = 0;
                }
                
                if(!isset($data[$key]['thrk'])){
                    $data[$key]['thrk'] = 0;
                }
                
                if(!isset($data[$key]['rkcj'])){
                    $data[$key]['rkcj'] = 0;
                }
                
                if(!isset($data[$key]['pkcj'])){
                    $data[$key]['pkcj'] = 0;
                }
                
                if(!isset($data[$key]['gqcj'])){
                    $data[$key]['gqcj'] = 0;
                }
                
                if(!isset($data[$key]['qtcj'])){
                    $data[$key]['qtcj'] = 0;
                }
                
                if(!isset($data[$key]['ly'])){
                    $data[$key]['ly'] = 0;
                }
                
                if(!isset($data[$key]['ll'])){
                    $data[$key]['ll'] = 0;
                }

                if(!isset($data[$key]['fy'])){
                    $data[$key]['fy'] = 0;
                }

                if(!isset($data[$key]['cy'])){
                    $data[$key]['cy'] = 0;
                }
                

            }
            
            return $data;
        }
        else{
            return '';
        }
        
    }



    public static function changepoolsArrDeal($list, $seachType=''){
        if($list && $seachType==''){
            $datas = [];
            $all_jh = [];
            foreach ($list as $key => $v) {
                
                $data[$v['pro_id']][$v['smalltype']][] = $v;
                $datas[$v['pro_id']]['pro_id'] = $v['pro_id'];
                $datas[$v['pro_id']]['pro_name'] = $v['pro_name'];
                $datas[$v['pro_id']]['pro_code'] = $v['pro_code'];
                $datas[$v['pro_id']]['pro_spec'] = $v['pro_spec'];
                $datas[$v['pro_id']]['pro_stock'] = $v['pro_stock'];
                $datas[$v['pro_id']]['uname'] = $v['uname'];
            }
            foreach ($data as $keys => $vals) {
                $datas[$keys]['jh'] = 0;
                $datas[$keys]['qtrk'] = 0;
                $datas[$keys]['dbrk'] = 0;
                $datas[$keys]['pyrk'] = 0;
                $datas[$keys]['thrk'] = 0;
                $datas[$keys]['rkcj'] = 0;
                $datas[$keys]['pkcj'] = 0;
                $datas[$keys]['gqcj'] = 0;
                $datas[$keys]['qtcj'] = 0;
                $datas[$keys]['ly'] = 0;
                $datas[$keys]['ll'] = 0;
                $datas[$keys]['fy'] = 0;
                $datas[$keys]['cy'] = 0;

                foreach ($vals as $key => $val) {
                    if($key == '1'){
                        foreach ($val as $ke => $va) {
                            $all_jh[$keys][] = $va['slnum'];
                            foreach ($all_jh as $k => $v) {
                                $datas[$keys]['jh'] = array_sum($v);
                            }
                        }
                    }

                    elseif($key == '21'){
                        foreach ($val as $ke => $va) {
                            $all_qtrk[$keys][] = $va['slnum'];
                            foreach ($all_qtrk as $k => $v) {
                                $datas[$keys]['qtrk'] = array_sum($v);
                            }
                        }
                    }elseif($key == '22'){
                        foreach ($val as $ke => $va) {
                            $all_dbrk[$keys][] = $va['slnum'];
                            foreach ($all_dbrk as $k => $v) {
                                $datas[$keys]['dbrk'] = array_sum($v);
                            }
                        }
                    }elseif($key == '23'){
                        foreach ($val as $ke => $va) {
                            $all_pyrk[$keys][] = $va['slnum'];
                            foreach ($all_pyrk as $k => $v) {
                                $datas[$keys]['pyrk'] = array_sum($v);
                            }
                        }
                    }elseif($key == '24'){
                        foreach ($val as $ke => $va) {
                            $all_thrk[$keys][] = $va['slnum'];
                            foreach ($all_thrk as $k => $v) {
                                $datas[$keys]['thrk'] = array_sum($v);
                            }
                        }
                    }

                    elseif($key == '31'){
                        foreach ($val as $ke => $va) {
                            $all_rkcj[$keys][] = $va['slnum'];
                            foreach ($all_rkcj as $k => $v) {
                                $datas[$keys]['rkcj'] = array_sum($v);
                            }
                        }
                    }elseif($key == '32'){
                        foreach ($val as $ke => $va) {
                            $all_pkcj[$keys][] = $va['slnum'];
                            foreach ($all_pkcj as $k => $v) {
                                $datas[$keys]['pkcj'] = array_sum($v);
                            }
                        }
                    }elseif($key == '33'){
                        foreach ($val as $ke => $va) {
                            $all_gqcj[$keys][] = $va['slnum'];
                            foreach ($all_gqcj as $k => $v) {
                                $datas[$keys]['gqcj'] = array_sum($v);
                            }
                        }
                    }elseif($key == '34'){
                        foreach ($val as $ke => $va) {
                            $all_qtcj[$keys][] = $va['slnum'];
                            foreach ($all_qtcj as $k => $v) {
                                $datas[$keys]['qtcj'] = array_sum($v);
                            }
                        }
                    }

                    elseif($key == '4'){
                        foreach ($val as $ke => $va) {
                            $all_ly[$keys][] = $va['slnum'];
                            foreach ($all_ly as $k => $v) {
                                $datas[$keys]['ly'] = array_sum($v);
                            }
                        }
                    }elseif($key == '5'){
                        foreach ($val as $ke => $va) {
                            $all_ll[$keys][] = $va['slnum'];
                            foreach ($all_ll as $k => $v) {
                                $datas[$keys]['ll'] = array_sum($v);
                            }
                        }
                    }elseif($key == '6'){
                        foreach ($val as $ke => $va) {
                            $all_fy[$keys][] = $va['slnum'];
                            foreach ($all_fy as $k => $v) {
                                $datas[$keys]['fy'] = array_sum($v);
                            }
                        }
                    }elseif($key == '7'){
                        foreach ($val as $ke => $va) {
                            $all_cy[$keys][] = $va['slnum'];
                            foreach ($all_cy as $k => $v) {
                                $datas[$keys]['cy'] = array_sum($v);
                            }
                        }
                    }

                }

            }
    /*qtrk
    dbrk
    pyrk
    thrk
    rkcj
    pkcj
    gqcj
    qtcj*/
            return $datas;


        }elseif($list && $seachType=='1'){
            // $datas = [];
            // $all_jh = [];
            foreach ($list as $key => $v) {
                $data[$v['pro_id']][$v['smalltype']][] = $v;
                $datas[$v['pro_id']]['pro_id'] = $v['pro_id'];
                $datas[$v['pro_id']]['pro_name'] = $v['pro_name'];
                $datas[$v['pro_id']]['pro_code'] = $v['pro_code'];
                $datas[$v['pro_id']]['pro_spec'] = $v['pro_spec'];
                $datas[$v['pro_id']]['pro_stock'] = $v['pro_stock'];
                $datas[$v['pro_id']]['uname'] = $v['uname'];
                $datas[$v['pro_id']]['pro_cat1'] = $v['pro_cat1'];
                // $judgeRepeat[$v['slotid']] = '';
            }
            foreach ($data as $keys => $vals) {
                // $datas[$keys]['surplusCost'] = 0;
                $datas[$keys]['jh'] = 0;
                $datas[$keys]['jhallcost'] = 0;
                $datas[$keys]['jhallprice'] =0;

                $datas[$keys]['qtrk'] = 0;
                $datas[$keys]['qtrkallcost'] = 0;
                $datas[$keys]['qtrkallprice'] =0;

                $datas[$keys]['dbrk'] = 0;
                $datas[$keys]['dbrkallcost'] = 0;
                $datas[$keys]['dbrkallprice'] =0;

                $datas[$keys]['pyrk'] = 0;
                $datas[$keys]['pyrkallcost'] = 0;
                $datas[$keys]['pyrkallprice'] =0;

                $datas[$keys]['thrk'] = 0;
                $datas[$keys]['thrkallcost'] = 0;
                $datas[$keys]['thrkallprice'] =0;

                $datas[$keys]['rkcj'] = 0;
                $datas[$keys]['rkcjallcost'] = 0;
                $datas[$keys]['rkcjallprice'] =0;

                $datas[$keys]['pkcj'] = 0;
                $datas[$keys]['pkcjallcost'] = 0;
                $datas[$keys]['pkcjallprice'] =0;

                $datas[$keys]['gqcj'] = 0;
                $datas[$keys]['gqcjallcost'] = 0;
                $datas[$keys]['gqcjallprice'] =0;

                $datas[$keys]['qtcj'] = 0;
                $datas[$keys]['qtcjallcost'] = 0;
                $datas[$keys]['qtcjallprice'] =0;

                $datas[$keys]['ly'] = 0;
                $datas[$keys]['lyallcost'] = 0;
                $datas[$keys]['lyallprice'] =0;

                $datas[$keys]['ll'] = 0;
                $datas[$keys]['llallcost'] = 0;
                $datas[$keys]['llallprice'] =0;

                $datas[$keys]['fy'] = 0;
                $datas[$keys]['fyallcost'] = 0;
                $datas[$keys]['fyallprice'] =0;

                $datas[$keys]['cy'] = 0;
                $datas[$keys]['cyallcost'] = 0;
                $datas[$keys]['cyallprice'] =0;

                foreach ($vals as $key => $val) {
                    /*foreach ($val as $ks => $vs) {
                        
                    }*/
                    if($key == '1'){
                        foreach ($val as $ke => $va) {
                            /*$datas[$keys]['surplusCost'] += $va['lcost']* $va['lstock'];*/

                            $all_jh[$keys][] = $va['slnum'];
                            foreach ($all_jh as $k => $v) {
                                $datas[$keys]['jh'] = array_sum($v);
                            }

                            $jhallcost[$keys][] = $va['slallcost'];
                            foreach ($jhallcost as $k => $v) {
                                $datas[$keys]['jhallcost'] = array_sum($v);
                            }

                            $jhallprice[$keys][] = $va['slallprice'];
                            foreach ($jhallprice as $k => $v) {
                                $datas[$keys]['jhallprice'] = array_sum($v);
                            }
                        }
                    }

                    elseif($key == '21'){
                        foreach ($val as $ke => $va) {

                            $all_qtrk[$keys][] = $va['slnum'];
                            foreach ($all_qtrk as $k => $v) {
                                $datas[$keys]['qtrk'] = array_sum($v);
                            }

                            $qtrkallcost[$keys][] = $va['slallcost'];
                            foreach ($qtrkallcost as $k => $v) {
                                $datas[$keys]['qtrkallcost'] = array_sum($v);
                            }

                            $qtrkallprice[$keys][] = $va['slallprice'];
                            foreach ($qtrkallprice as $k => $v) {
                                $datas[$keys]['qtrkallprice'] = array_sum($v);
                            }

                        }
                    }

                    elseif($key == '22'){
                        foreach ($val as $ke => $va) {

                            $all_dbrk[$keys][] = $va['slnum'];
                            foreach ($all_dbrk as $k => $v) {
                                $datas[$keys]['dbrk'] = array_sum($v);
                            }

                            $dbrkallcost[$keys][] = $va['slallcost'];
                            foreach ($dbrkallcost as $k => $v) {
                                $datas[$keys]['dbrkallcost'] = array_sum($v);
                            }

                            $dbrkallprice[$keys][] = $va['slallprice'];
                            foreach ($dbrkallprice as $k => $v) {
                                $datas[$keys]['dbrkallprice'] = array_sum($v);
                            }

                        }
                    }

                    elseif($key == '23'){
                        foreach ($val as $ke => $va) {

                            $all_pyrk[$keys][] = $va['slnum'];
                            foreach ($all_pyrk as $k => $v) {
                                $datas[$keys]['pyrk'] = array_sum($v);
                            }

                            $pyrkallcost[$keys][] = $va['slallcost'];
                            foreach ($pyrkallcost as $k => $v) {
                                $datas[$keys]['pyrkallcost'] = array_sum($v);
                            }

                            $pyrkallprice[$keys][] = $va['slallprice'];
                            foreach ($pyrkallprice as $k => $v) {
                                $datas[$keys]['pyrkallprice'] = array_sum($v);
                            }

                        }
                    }

                    elseif($key == '24'){
                        foreach ($val as $ke => $va) {

                            $all_thrk[$keys][] = $va['slnum'];
                            foreach ($all_thrk as $k => $v) {
                                $datas[$keys]['thrk'] = array_sum($v);
                            }

                            $thrkallcost[$keys][] = $va['slallcost'];
                            foreach ($thrkallcost as $k => $v) {
                                $datas[$keys]['thrkallcost'] = array_sum($v);
                            }

                            $thrkallprice[$keys][] = $va['slallprice'];
                            foreach ($thrkallprice as $k => $v) {
                                $datas[$keys]['thrkallprice'] = array_sum($v);
                            }

                        }
                    }

                    elseif($key == '31'){
                        foreach ($val as $ke => $va) {

                            $all_rkcj[$keys][] = $va['slnum'];
                            foreach ($all_rkcj as $k => $v) {
                                $datas[$keys]['rkcj'] = array_sum($v);
                            }

                            $rkcjallcost[$keys][] = $va['slallcost'];
                            foreach ($rkcjallcost as $k => $v) {
                                $datas[$keys]['rkcjallcost'] = array_sum($v);
                            }

                            $rkcjallprice[$keys][] = $va['slallprice'];
                            foreach ($rkcjallprice as $k => $v) {
                                $datas[$keys]['rkcjallprice'] = array_sum($v);
                            }

                        }
                    }

                    elseif($key == '32'){
                        foreach ($val as $ke => $va) {

                            $all_pkcj[$keys][] = $va['slnum'];
                            foreach ($all_pkcj as $k => $v) {
                                $datas[$keys]['pkcj'] = array_sum($v);
                            }

                            $pkcjallcost[$keys][] = $va['slallcost'];
                            foreach ($pkcjallcost as $k => $v) {
                                $datas[$keys]['pkcjallcost'] = array_sum($v);
                            }

                            $pkcjallprice[$keys][] = $va['slallprice'];
                            foreach ($pkcjallprice as $k => $v) {
                                $datas[$keys]['pkcjallprice'] = array_sum($v);
                            }

                        }
                    }

                    elseif($key == '33'){
                        foreach ($val as $ke => $va) {

                            $all_gqcj[$keys][] = $va['slnum'];
                            foreach ($all_gqcj as $k => $v) {
                                $datas[$keys]['gqcj'] = array_sum($v);
                            }

                            $gqcjallcost[$keys][] = $va['slallcost'];
                            foreach ($gqcjallcost as $k => $v) {
                                $datas[$keys]['gqcjallcost'] = array_sum($v);
                            }

                            $gqcjallprice[$keys][] = $va['slallprice'];
                            foreach ($gqcjallprice as $k => $v) {
                                $datas[$keys]['gqcjallprice'] = array_sum($v);
                            }

                        }
                    }

                    elseif($key == '34'){
                        foreach ($val as $ke => $va) {

                            $all_qtcj[$keys][] = $va['slnum'];
                            foreach ($all_qtcj as $k => $v) {
                                $datas[$keys]['qtcj'] = array_sum($v);
                            }

                            $qtcjallcost[$keys][] = $va['slallcost'];
                            foreach ($qtcjallcost as $k => $v) {
                                $datas[$keys]['qtcjallcost'] = array_sum($v);
                            }

                            $qtcjallprice[$keys][] = $va['slallprice'];
                            foreach ($qtcjallprice as $k => $v) {
                                $datas[$keys]['qtcjallprice'] = array_sum($v);
                            }

                        }
                    }

                    elseif($key == '4'){
                        foreach ($val as $ke => $va) {

                            $all_ly[$keys][] = $va['slnum'];
                            foreach ($all_ly as $k => $v) {
                                $datas[$keys]['ly'] = array_sum($v);
                            }

                            $lyallcost[$keys][] = $va['slallcost'];
                            foreach ($lyallcost as $k => $v) {
                                $datas[$keys]['lyallcost'] = array_sum($v);
                            }

                            $lyallprice[$keys][] = $va['slallprice'];
                            foreach ($lyallprice as $k => $v) {
                                $datas[$keys]['lyallprice'] = array_sum($v);
                            }

                        }
                    }

                    elseif($key == '5'){
                        foreach ($val as $ke => $va) {
                            /*$datas[$keys]['surplusCost'] += $va['lcost']* $va['lstock'];*/

                            $all_ll[$keys][] = $va['slnum'];
                            foreach ($all_ll as $k => $v) {
                                $datas[$keys]['ll'] = array_sum($v);
                            }

                            $llallcost[$keys][] = $va['slallcost'];
                            foreach ($llallcost as $k => $v) {
                                $datas[$keys]['llallcost'] = array_sum($v);
                            }

                            $llallprice[$keys][] = $va['slallprice'];
                            foreach ($llallprice as $k => $v) {
                                $datas[$keys]['llallprice'] = array_sum($v);
                            }
                            
                        }
                    }

                    elseif($key == '6'){
                        foreach ($val as $ke => $va) {
                            /*$datas[$keys]['surplusCost'] += $va['lcost']* $va['lstock'];*/

                            $all_fy[$keys][] = $va['slnum'];
                            foreach ($all_fy as $k => $v) {
                                $datas[$keys]['fy'] = array_sum($v);
                            }

                            $fyallcost[$keys][] = $va['slallcost'];
                            foreach ($fyallcost as $k => $v) {
                                $datas[$keys]['fyallcost'] = array_sum($v);
                            }

                            $fyallprice[$keys][] = $va['slallprice'];
                            foreach ($fyallprice as $k => $v) {
                                $datas[$keys]['fyallprice'] = array_sum($v);
                            }
                            
                        }
                    }

                    elseif($key == '7'){
                        foreach ($val as $ke => $va) {
                            /*$datas[$keys]['surplusCost'] += $va['lcost']* $va['lstock'];*/

                            $all_cy[$keys][] = $va['slnum'];
                            foreach ($all_cy as $k => $v) {
                                $datas[$keys]['cy'] = array_sum($v);
                            }

                            $cyallcost[$keys][] = $va['slallcost'];
                            foreach ($cyallcost as $k => $v) {
                                $datas[$keys]['cyallcost'] = array_sum($v);
                            }

                            $cyallprice[$keys][] = $va['slallprice'];
                            foreach ($cyallprice as $k => $v) {
                                $datas[$keys]['cyallprice'] = array_sum($v);
                            }
                            
                        }
                    }


                }

            }
    /*qtrk
    dbrk
    pyrk
    thrk
    rkcj
    pkcj
    gqcj
    qtcj*/

            return $datas;
        }else{
            return '';
        }
        
    }

}