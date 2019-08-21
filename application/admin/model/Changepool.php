<?php

namespace app\admin\model;

use think\Model;

class Changepool extends Model
{
    public static function dealArr($list){
        if($list){
            $data = [];
            foreach ($list as $k => $v) {
                    // if(!isset($data[$v['l_pid']])){
                    //  $data[][] = $v;
                    // }else{
                    //  $data[$v['l_pid']][] = $v;
                    // }
                    // if(!isset($data[$v['l_pid']][$v['l_type']])){
                    //     $data[$v['l_pid']][$v['l_type']][] = $v;
                    //     $data[$v['l_pid']]['name'] = $v['name'];
                    //     $data[$v['l_pid']]['num'] = $v['num'];
                    //     $data[$v['l_pid']]['pename'] = $v['pename'];
                    //     $data[$v['l_pid']]['sizes'] = $v['sizes'];
                    //     $data[$v['l_pid']]['unit'] = $v['unit'];
                    //     $data[$v['l_pid']]['lotnum'] = $v['lotnum'];
                    //     $data[$v['l_pid']]['cost'] = $v['cost'];
                    //     $data[$v['l_pid']]['price'] = $v['price'];
                    //     $data[$v['l_pid']]['stock'] = $v['stock'];
                    //     $data[$v['l_pid']]['pdutype_id'] = $v['pdutype_id'];
                    //     $data[$v['l_pid']]['pdutype2_id'] = $v['pdutype2_id'];
                    // }else{
                        $data[$v['l_pid']][$v['l_type']][] = $v;
                        $data[$v['l_pid']]['name'] = $v['name'];
                        $data[$v['l_pid']]['num'] = $v['num'];
                        $data[$v['l_pid']]['pename'] = $v['pename'];
                        $data[$v['l_pid']]['sizes'] = $v['sizes'];
                        $data[$v['l_pid']]['unit'] = $v['unit'];
                        $data[$v['l_pid']]['lotnum'] = $v['lotnum'];
                        $data[$v['l_pid']]['cost'] = $v['cost'];
                        $data[$v['l_pid']]['price'] = $v['price'];
                        $data[$v['l_pid']]['stock'] = $v['stock'];
                        $data[$v['l_pid']]['pdutype_id'] = $v['pdutype_id'];
                        $data[$v['l_pid']]['pdutype2_id'] = $v['pdutype2_id'];
                    // }
                }

            foreach ($data as $ke => $va) {
                // var_dump($va);

                // $va['jh'] = $va[1]['l_num'];
                foreach ($va as $key => $val) {
                    // var_dump($val);
                    if($key =='0'){     //进货
                        foreach ($val as $keys => $vals) {
                            
                            $all_jh[$ke][] = $vals['l_num'];
                            foreach ($all_jh as $keyss => $valss) {
                                $data[$ke]['jh'] = array_sum($valss);
                            }
                            
                        }
                        
                    }
                    elseif($key =='1'){     //入库
                        foreach ($val as $keys => $vals) {
                            
                            $all_rk[$ke][] = $vals['l_num'];
                            foreach ($all_rk as $keyss => $valss) {
                                $data[$ke]['rk'] = array_sum($valss);
                            }
                            
                        }
                        
                    }
                    elseif($key =='11'){        //调拨入库
                        foreach ($val as $keys => $vals) {
                            
                            $all_dbrk[$ke][] = $vals['l_num'];
                            foreach ($all_dbrk as $keyss => $valss) {
                                $data[$ke]['dbrk'] = array_sum($valss);
                            }
                            
                        }
                        
                    }
                    elseif($key =='12'){        //盘盈入库
                        foreach ($val as $keys => $vals) {
                            
                            $all_pyrk[$ke][] = $vals['l_num'];
                            foreach ($all_pyrk as $keyss => $valss) {
                                $data[$ke]['pyrk'] = array_sum($valss);
                            }
                            
                        }
                        
                    }
                    elseif($key =='13'){        //退货入库
                        foreach ($val as $keys => $vals) {
                            
                            $all_thrk[$ke][] = $vals['l_num'];
                            foreach ($all_thrk as $keyss => $valss) {
                                $data[$ke]['thrk'] = array_sum($valss);
                            }
                            
                        }
                        
                    }
                    elseif($key =='15'){        //其他入库
                        foreach ($val as $keys => $vals) {
                            
                            $all_qtrk[$ke][] = $vals['l_num'];
                            foreach ($all_qtrk as $keyss => $valss) {
                                $data[$ke]['qtrk'] = array_sum($valss);
                            }
                            
                        }
                        
                    }
                    elseif($key =='1111'){      //删除调拨入库
                        foreach ($val as $keys => $vals) {
                            
                            $all_scdbrk[$ke][] = $vals['l_num'];
                            foreach ($all_scdbrk as $keyss => $valss) {
                                $data[$ke]['scdbrk'] = array_sum($valss);
                            }
                            
                        }
                        
                    }
                    elseif($key =='1211'){      //删除盘盈入库
                        foreach ($val as $keys => $vals) {
                            
                            $all_scpyrk[$ke][] = $vals['l_num'];
                            foreach ($all_scpyrk as $keyss => $valss) {
                                $data[$ke]['scpyrk'] = array_sum($valss);
                            }
                            
                        }
                        
                    }
                    elseif($key =='1311'){      //删除退货入库
                        foreach ($val as $keys => $vals) {
                            
                            $all_scthrk[$ke][] = $vals['l_num'];
                            foreach ($all_scthrk as $keyss => $valss) {
                                $data[$ke]['scthrk'] = array_sum($valss);
                            }
                            
                        }
                        
                    }
                    elseif($key =='1511'){      //删除其他入库
                        foreach ($val as $keys => $vals) {
                            
                            $all_scqtrk[$ke][] = $vals['l_num'];
                            foreach ($all_scqtrk as $keyss => $valss) {
                                $data[$ke]['scqtrk'] = array_sum($valss);
                            }
                            
                        }
                        
                    }
                    elseif($key =='2'){     //冲减
                        foreach ($val as $keys => $vals) {
                            
                            $all_cj[$ke][] = $vals['l_num'];
                            foreach ($all_cj as $keyss => $valss) {
                                $data[$ke]['cj'] = array_sum($valss);
                            }
                            
                        }
                        
                    }
                    elseif($key =='21'){        //入库冲减
                        foreach ($val as $keys => $vals) {
                            
                            $all_rkcj[$ke][] = $vals['l_num'];
                            foreach ($all_rkcj as $keyss => $valss) {
                                $data[$ke]['rkcj'] = array_sum($valss);
                            }
                            
                        }
                        
                    }
                    elseif($key =='23'){        //盘亏冲减
                        foreach ($val as $keys => $vals) {
                            
                            $all_pkcj[$ke][] = $vals['l_num'];
                            foreach ($all_pkcj as $keyss => $valss) {
                                $data[$ke]['pkcj'] = array_sum($valss);
                            }
                            
                        }
                        
                    }
                    elseif($key =='24'){        //过期冲减
                        foreach ($val as $keys => $vals) {
                            
                            $all_gqcj[$ke][] = $vals['l_num'];
                            foreach ($all_gqcj as $keyss => $valss) {
                                $data[$ke]['gqcj'] = array_sum($valss);
                            }
                            
                        }
                        
                    }
                    elseif($key =='25'){        //其他冲减
                        foreach ($val as $keys => $vals) {
                            
                            $all_qtcj[$ke][] = $vals['l_num'];
                            foreach ($all_qtcj as $keyss => $valss) {
                                $data[$ke]['qtcj'] = array_sum($valss);
                            }
                            
                        }
                        
                    }
                    elseif($key =='2111'){      //删除入库冲减
                        foreach ($val as $keys => $vals) {
                            
                            $all_scrkcj[$ke][] = $vals['l_num'];
                            foreach ($all_scrkcj as $keyss => $valss) {
                                $data[$ke]['scrkcj'] = array_sum($valss);
                            }
                            
                        }
                        
                    }
                    elseif($key =='2311'){      //删除盘亏冲减
                        foreach ($val as $keys => $vals) {
                            
                            $all_scpkcj[$ke][] = $vals['l_num'];
                            foreach ($all_scpkcj as $keyss => $valss) {
                                $data[$ke]['scpkcj'] = array_sum($valss);
                            }
                            
                        }
                        
                    }
                    elseif($key =='2411'){      //删除过期冲减
                        foreach ($val as $keys => $vals) {
                            
                            $all_scgqcj[$ke][] = $vals['l_num'];
                            foreach ($all_scgqcj as $keyss => $valss) {
                                $data[$ke]['scgqcj'] = array_sum($valss);
                            }
                            
                        }
                        
                    }
                    elseif($key =='2511'){      //删除其他冲减
                        foreach ($val as $keys => $vals) {
                            
                            $all_scqtcj[$ke][] = $vals['l_num'];
                            foreach ($all_scqtcj as $keyss => $valss) {
                                $data[$ke]['scqtcj'] = array_sum($valss);
                            }
                            
                        }
                        
                    }





                    elseif($key =='5'){     //领料
                        foreach ($val as $keys => $vals) {
                            
                            $all_ll[$ke][] = $vals['l_num'];
                            foreach ($all_ll as $keyss => $valss) {
                                $data[$ke]['ll'] = array_sum($valss);
                            }
                            
                        }
                        
                    }
                    elseif($key =='6'){     //药房发药
                        foreach ($val as $keys => $vals) {
                            
                            $all_fy[$ke][] = $vals['l_num'];
                            foreach ($all_fy as $keyss => $valss) {
                                $data[$ke]['fy'] = array_sum($valss);
                            }
                            
                        }
                        
                    }
                    elseif($key =='7'){     //药房撤销发药，撤药
                        foreach ($val as $keys => $vals) {
                            
                            $all_cy[$ke][] = $vals['l_num'];
                            foreach ($all_cy as $keyss => $valss) {
                                $data[$ke]['cy'] = array_sum($valss);
                            }
                            
                        }
                        
                    }
                    elseif($key =='8'){     //领药
                        foreach ($val as $keys => $vals) {
                            
                            $all_ly[$ke][] = $vals['l_num'];
                            foreach ($all_ly as $keyss => $valss) {
                                $data[$ke]['ly'] = array_sum($valss);
                            }
                            
                        }
                        
                    }
                    elseif($key =='10'){        //删除进货
                        foreach ($val as $keys => $vals) {
                            
                            $all_scjh[$ke][] = $vals['l_num'];
                            foreach ($all_scjh as $keyss => $valss) {
                                $data[$ke]['scjh'] = array_sum($valss);
                            }
                            
                        }
                        
                    }
                    elseif($key =='111'){       //删除入库
                        foreach ($val as $keys => $vals) {
                            
                            $all_scrk[$ke][] = $vals['l_num'];
                            foreach ($all_scrk as $keyss => $valss) {
                                $data[$ke]['scrk'] = array_sum($valss);
                            }
                            
                        }
                        
                    }
                    elseif($key =='211'){       //删除冲减
                        foreach ($val as $keys => $vals) {
                            
                            $all_sccj[$ke][] = $vals['l_num'];
                            foreach ($all_sccj as $keyss => $valss) {
                                $data[$ke]['sccj'] = array_sum($valss);
                            }
                            
                        }
                        
                    }
                    elseif($key =='511'){       //删除领料
                        foreach ($val as $keys => $vals) {
                            
                            $all_scll[$ke][] = $vals['l_num'];
                            foreach ($all_scll as $keyss => $valss) {
                                $data[$ke]['scll'] = array_sum($valss);
                            }
                            
                        }
                        
                    }
                    elseif($key =='811'){       //删除领药
                        foreach ($val as $keys => $vals) {
                            
                            $all_scly[$ke][] = $vals['l_num'];
                            foreach ($all_scly as $keyss => $valss) {
                                $data[$ke]['scly'] = array_sum($valss);
                            }
                            
                        }
                        
                    }
                    // else{
                    //  $data[$ke]['sc'] = array_sum($valss);
                    // }
                }
    // dump($data[$ke]['jh']);
                
            }

    // var_dump($data['3']);
            foreach ($data as $key => $v) {

                if(!isset($data[$key]['jh'])){
                    $data[$key]['jh'] = '0';
                }
                if(!isset($data[$key]['rk'])){
                    $data[$key]['rk'] = '0';
                }
                if(!isset($data[$key]['dbrk'])){
                    $data[$key]['dbrk'] = '0';
                }
                if(!isset($data[$key]['pyrk'])){
                    $data[$key]['pyrk'] = '0';
                }
                if(!isset($data[$key]['thrk'])){
                    $data[$key]['thrk'] = '0';
                }
                if(!isset($data[$key]['qtrk'])){
                    $data[$key]['qtrk'] = '0';
                }
                if(!isset($data[$key]['scdbrk'])){
                    $data[$key]['scdbrk'] = '0';
                }
                if(!isset($data[$key]['scpyrk'])){
                    $data[$key]['scpyrk'] = '0';
                }
                if(!isset($data[$key]['scthrk'])){
                    $data[$key]['scthrk'] = '0';
                }
                if(!isset($data[$key]['scqtrk'])){
                    $data[$key]['scqtrk'] = '0';
                }


                if(!isset($data[$key]['cj'])){
                    $data[$key]['cj'] = '0';
                }
                if(!isset($data[$key]['rkcj'])){
                    $data[$key]['rkcj'] = '0';
                }
                if(!isset($data[$key]['pkcj'])){
                    $data[$key]['pkcj'] = '0';
                }
                if(!isset($data[$key]['gqcj'])){
                    $data[$key]['gqcj'] = '0';
                }
                if(!isset($data[$key]['qtcj'])){
                    $data[$key]['qtcj'] = '0';
                }
                if(!isset($data[$key]['scrkcj'])){
                    $data[$key]['scrkcj'] = '0';
                }
                if(!isset($data[$key]['scpkcj'])){
                    $data[$key]['scpkcj'] = '0';
                }
                if(!isset($data[$key]['scgqcj'])){
                    $data[$key]['scgqcj'] = '0';
                }
                if(!isset($data[$key]['scqtcj'])){
                    $data[$key]['scqtcj'] = '0';
                }




                if(!isset($data[$key]['ll'])){
                    $data[$key]['ll'] = '0';
                }
                if(!isset($data[$key]['fy'])){
                    $data[$key]['fy'] = '0';
                }
                if(!isset($data[$key]['cy'])){
                    $data[$key]['cy'] = '0';
                }
                if(!isset($data[$key]['ly'])){
                    $data[$key]['ly'] = '0';
                }
                if(!isset($data[$key]['scjh'])){
                    $data[$key]['scjh'] = '0';
                }
                if(!isset($data[$key]['scrk'])){
                    $data[$key]['scrk'] = '0';
                }
                if(!isset($data[$key]['sccj'])){
                    $data[$key]['sccj'] = '0';
                }
                if(!isset($data[$key]['scll'])){
                    $data[$key]['scll'] = '0';
                }
                if(!isset($data[$key]['scly'])){
                    $data[$key]['scly'] = '0';
                }

                // $data[$key]['qckc'] = $v['stock']-isset(var);
            }

            return $data;
        }else{
            return '';
        }
        
    }
}
