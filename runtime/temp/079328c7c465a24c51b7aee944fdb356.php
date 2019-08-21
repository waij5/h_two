<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:74:"D:\wamp\www\h_two\public/../application/admin\view\wmreport\psi\index.html";i:1547448706;s:70:"D:\wamp\www\h_two\public/../application/admin\view\layout\default.html";i:1544673823;s:67:"D:\wamp\www\h_two\public/../application/admin\view\common\meta.html";i:1544673823;s:69:"D:\wamp\www\h_two\public/../application/admin\view\common\script.html";i:1544673823;}*/ ?>
<!DOCTYPE html>
<html lang="<?php echo $config['language']; ?>">
    <head>
        <meta charset="utf-8">
<title><?php echo (isset($title) && ($title !== '')?$title:''); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">

<link rel="shortcut icon" href="__CDN__/assets/img/favicon.ico" />
<!-- Loading Bootstrap -->

<link href="__CDN__/assets/css/backend.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">

<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
<!--[if lt IE 9]>
  <script src="__CDN__/assets/js/html5shiv.js"></script>
  <script src="__CDN__/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
    var require = {
        config:  <?php echo json_encode($config); ?>
    };
</script>
    </head>

    <body class="inside-header inside-aside <?php echo defined('IS_DIALOG') && IS_DIALOG ? 'is-dialog' : ''; ?>">
        <div id="main" role="main">
            <div class="tab-content tab-addtabs">
                <div id="content">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <section class="content-header hide">
                                <h1>
                                    <?php echo __('Dashboard'); ?>
                                    <small><?php echo __('Control panel'); ?></small>
                                </h1>
                            </section>
                            <?php if(!IS_DIALOG): ?>
                            <!-- RIBBON -->
                            <div id="ribbon">
                                <ol class="breadcrumb pull-left">
                                    <li><a href="dashboard" class="addtabsit"><i class="fa fa-dashboard"></i> <?php echo __('Dashboard'); ?></a></li>
                                </ol>
                                <ol class="breadcrumb pull-right">
                                    <?php foreach($breadcrumb as $vo): ?>
                                    <li><a href="javascript:;" data-url="<?php echo $vo['url']; ?>"><?php echo $vo['title']; ?></a></li>
                                    <?php endforeach; ?>
                                </ol>
                            </div>
                            <!-- END RIBBON -->
                            <?php endif; ?>
                            <div class="content">
                                <div class="panel panel-default panel-intro">
    <?php echo build_heading(); ?>
<style type="text/css">
.red{color: red}
#table tr td{border:1px black solid;word-wrap:break-word;text-align: center;vertical-align:middle}
#table tr th{border:1px black solid;text-align: center;vertical-align:middle}
#two_table tr th{border:2px black solid;text-align: center;word-wrap:break-word;color: red;vertical-align:middle}
</style>
    <div class="panel-body">
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade active in" id="one">
                <div class="widget-body no-padding">

                    <div class="commonsearch-table">
                        <form class="form-inline  nice-validator nice-validator n-default" action="<?php echo url('wmreport/psi/index'); ?>" id="f-commonsearch" role="form" method="post">
                            <fieldset style="text-align: left;">
                                <div class="form-group dislocationAll">
                                    <label for="id" class="control-label labelLocation">进货日期</label>
                                    <input type="text" name="sintime" <?php if(is_null(\think\Request::instance()->param('sintime'))): ?> value="<?php echo date('Y-m-d');?>"<?php else: ?> value="<?php echo \think\Request::instance()->param('sintime'); ?>"<?php endif; ?> class="form-control datetimepicker " data-use-current="true" data-date-format="YYYY-MM-DD" size="8">  ~  
                                    <input type="text" name="eintime"  <?php if(is_null(\think\Request::instance()->param('eintime'))): ?> value="<?php echo date('Y-m-d');?>"<?php else: ?> value="<?php echo \think\Request::instance()->param('eintime'); ?>"<?php endif; ?>  class="form-control datetimepicker " data-use-current="true" data-date-format="YYYY-MM-DD"  size="8">
                                </div>
                                <div class="form-group dislocationAll">
                                    <label for="p_num" class="control-label labelLocation">产品编号</label>
                                    <input type="text" name="p_num" class="form-control clear" size="8" value="<?php echo \think\Request::instance()->param('p_num'); ?>">
                                </div>
                                <div class="form-group dislocationAll">
                                    <label for="p_name" class="control-label labelLocation">产品名称</label>
                                    <input type="text" name="p_name" class="form-control clear" size="8" value="<?php echo \think\Request::instance()->param('p_name'); ?>">
                                </div>
                                <div class="form-group dislocationAll">
                                    <label for="lotnum" class="control-label labelLocation">产品批号</label>
                                    <input type="text" name="lotnum" class="form-control clear" size="8" value="<?php echo \think\Request::instance()->param('lotnum'); ?>">
                                </div>

                                
                                
                                <br>
                                
                                <div class="form-group dislocationAll">
                                    <label for="depot_id" class="control-label labelLocation" >仓库</label>
                                    <select id="depot_id" data-rule="" class="form-control " name="depot_id" style="width: 100px">
                                        <!-- <option data-type="" value='' >--- 请选择 ---</option> -->
                                        <?php if(is_array($depotList) || $depotList instanceof \think\Collection || $depotList instanceof \think\Paginator): if( count($depotList)==0 ) : echo "" ;else: foreach($depotList as $key=>$vo): ?>
                                        <option value="<?php echo $vo['id']; ?>" <?php if(in_array(($vo['id']), is_array(\think\Request::instance()->param('depot_id'))?\think\Request::instance()->param('depot_id'):explode(',',\think\Request::instance()->param('depot_id')))): ?>selected<?php endif; ?>><?php echo $vo['name']; ?></option>
                                        <?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                </div>

                                <div class="form-group dislocationAll">
                                    <label for="order_num" class="control-label labelLocation">单号</label>
                                    <input type="text" name="order_num" class="form-control clear" size="8" value="<?php echo \think\Request::instance()->param('order_num'); ?>">
                                </div>
                                <div class="form-group dislocationAll">
                                    <label for="supplier_id" class="control-label labelLocation">供应商</label>
                                    <select id="supplier_id"  class=" clear selectpicker" name="supplier_id"  data-live-search="true">
                                        <option value='' >--- 请选择 ---</option>
                                        <?php if(is_array($supplier) || $supplier instanceof \think\Collection || $supplier instanceof \think\Paginator): if( count($supplier)==0 ) : echo "" ;else: foreach($supplier as $key=>$vo): ?>
                                        <option  value="<?php echo $vo['sup_id']; ?>" <?php if(in_array(($vo['sup_id']), is_array(\think\Request::instance()->param('producer_id'))?\think\Request::instance()->param('producer_id'):explode(',',\think\Request::instance()->param('producer_id')))): ?>selected<?php endif; ?>><?php echo $vo['sup_name']; ?></option>
                                        <?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                </div>
                                <div class="form-group dislocationAll">
                                    <label for="type" class="control-label labelLocation">状态</label>
                                    <select id="type" data-rule="" class="form-control " name="type" >
                                        <?php if(is_array($typeList) || $typeList instanceof \think\Collection || $typeList instanceof \think\Paginator): if( count($typeList)==0 ) : echo "" ;else: foreach($typeList as $key=>$vo): ?>
                                        <option value="<?php echo $key; ?>" <?php if(in_array(($key), is_array(\think\Request::instance()->param('type'))?\think\Request::instance()->param('type'):explode(',',\think\Request::instance()->param('type')))): ?>selected<?php endif; ?>><?php echo $vo; ?></option>
                                        <?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                </div>
                                <!--<br>-->
                                <div class="form-group" style="margin: 0 0 0 20%">
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-success dislocationRight">提交</button>
                                        <a type="reset" class="btn btn-default" id="btn-customer-clear">重置</a>
                                        <button type="button" class="btn btn-default" id="btn-export">导出</button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>

                    <div id="toolbar" class="toolbar">
                        <a href="javascript:;" class="btn btn-primary btn-refresh" onclick="window.location.reload()"><i class="fa fa-refresh"></i> </a> &nbsp;
                        <!-- <a href="javascript:;" class="btn btn-primary" id="isPrint">打印</a> -->
                        
                    </div>
                    
<!--startprint-->
					
                   	<div class="form-group " style="font-family: KaiTi;width: 100%;">
                       <div style="text-align: center;font-weight:bold;font-size: 20px;"> 壹加壹医学美容医院<br>产品进入冲明细表</div>
					</div>
					<div class="proreportDate" style="font-family: KaiTi;width: 100%;height: 22px;">
                        <?php if(!empty($alls)): ?>
                        <div style="font-weight:normal;font-size: 15px;float: left;" >
                            <b>总成本:</b><span style="color: #18bc9c"><?php echo !empty($alls['mallcost'])?$alls['mallcost']:'0'; ?></span>&nbsp;&nbsp;
                            <b>总售价:</b><span style="color: #18bc9c"><?php echo !empty($alls['mallprice'])?$alls['mallprice']:'0'; ?></span>&nbsp;&nbsp;
                            <b>进销差额:</b><span style="color: #18bc9c"><?php echo !empty($alls['mallprice'])?$alls['mallprice']-$alls['mallcost']:'0'; ?>
                        </div>
                        <?php endif; ?>
                        <div style="font-weight:normal;font-size: 13px;float: right;">打印日期：<?php echo date('Y-m-d');?></div>
                    </div>
                    
                     <div id="consumTable" style="position: relative;overflow-y: auto;">

                    <?php if(!empty($data)): ?>

                    <table id="table" class=" table table-striped table-bordered table-hover" style="width: 100%;table-layout: fixed;" >
                    <?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): if( count($data)==0 ) : echo "" ;else: foreach($data as $k=>$v): ?> 
                        
                            <tr style="border:1px solid;">
                                <th width="4.6%">单号</th>
                                <th width="5.7%">供应商</th>
                                <th width="5.5%">进货日期</th>
                                <th width="5.5%">产品编号</th>
                                <th width="11%" class="red">产品名称</th>
                                <th width="6%" class="red">批号</th>
                                <th width="3%">单位</th>
                                <th width="4%">规格</th>
                                <th width="4.2%">数量</th>
                                <th width="4.5%">成本单价</th>
                                <th width="5%">总成本</th>
                                <th width="4.5%">零售价</th>
                                <th width="5%">总售价</th>
                                <th width="5%">进销差额</th>
                                <th width="5.5%">有效日期</th>
                                <th width="6%">生产厂家</th>
                                <th width="10%">说明</th>
                            </tr>
                        <?php if(is_array($v) || $v instanceof \think\Collection || $v instanceof \think\Paginator): if( count($v)==0 ) : echo "" ;else: foreach($v as $kk=>$vo): ?>
                            <tr style="border:1px solid;">
                                <td style="color: red"><?php if($counts[$k]-$kk <$counts[$k]): else: ?> <?php echo $vo['man_num']; endif; ?></td>
                                <td><?php echo $vo['sup_name']; ?></td>
                                <td><?php echo datetime($vo['mcreatetime'],"Y-m-d"); ?></td>
                                <td><?php echo $vo['pro_code']; ?></td>
                                <td class="red"><?php echo $vo['pro_name']; ?></td>
                                <td class="red"><?php echo $vo['lotnum']; ?></td>
                                <td><?php echo $vo['uname']; ?></td>
                                <td><?php echo $vo['pro_spec']; ?></td>
                                <td><?php echo $vo['mpro_num']; ?></td>
                                <td><?php echo $vo['mcost']; ?></td>
                                <td><?php echo $vo['mallcost']; ?></td>
                                <td><?php echo $vo['mprice']; ?></td>
                                <td><?php echo $vo['mallprice']; ?></td>
                                <td><?php echo $vo['mallprice']-$vo['mallcost']; ?></td>
                                <td><?php if($vo['metime']>0): ?><?php echo date('Y-m-d',$vo['metime']); endif; ?></td>
                                <td><?php echo $vo['lproducer']; ?></td>
                                <td><?php echo $vo['mremark']; ?></td>
                            </tr>
                        <?php endforeach; endif; else: echo "" ;endif; if(is_array($datas) || $datas instanceof \think\Collection || $datas instanceof \think\Paginator): if( count($datas)==0 ) : echo "" ;else: foreach($datas as $kk=>$vv): if($kk == $k): ?> 
                            <tr style="border-bottom: 2px solid;" class="one_table">
                                <th colspan="1">状态</th>
                                <th colspan="2" style="color: red"><?php echo __('Type '.$vv['type']); ?></th>
                                <th colspan="4" style="color: red"></th>
                                <th colspan="1" style="color: red;">小计</th>
                                <th colspan="1" style="color: red;"><?php echo $vv['all_num']; ?></th>
                                <th colspan="1"></th>
                                <th colspan="1" style="color: red;"><?php echo $vv['all_totalcost']; ?></th>
                                <th colspan="1"></th>
                                <th colspan="1" style="color: red;"><?php echo $vv['all_totalprice']; ?></th>
                                <th colspan="1" style="color: red;"><?php echo $vv['all_totalprice']-$vv['all_totalcost']; ?></th>
                                <th colspan="3"></th>
                            </tr><?php endif; endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
                             
                                    <!--  <tfoot class="proreport-tfoot" >
                                        <tr  style="border-bottom: 2px solid;" class="two_table">
                                            <th colspan="1"></th>
                                            <th colspan="2" style="color: red"></th>
                                            <th colspan="4" style="color: red"></th>
                                            <th colspan="1" style="color: red;">总计</th>
                                            <th colspan="1" style="color: red;"><?php echo !empty($alls['num'])?$alls['num']:''; ?></th>
                                            <th colspan="1"></th>
                                            <th colspan="1" style="color: red;"><?php echo !empty($alls['cost'])?$alls['cost']:''; ?></th>
                                            <th colspan="1"></th>
                                            <th colspan="1" style="color: red;"><?php echo !empty($alls['price'])?$alls['price']:''; ?></th>
                                            <th colspan="1" style="color: red;"><?php echo !empty($alls['price'])?$alls['price']-$alls['cost']:''; ?></th>
                                            <th colspan="3"></th>

                                            
                                        </tr>
                                    </tfoot> -->
                                </table>


                                <div class="text-center" id="div-load-more">
                                    <!-- <a href="javascript:;" id="btn-rec-load-more">加载更多...</a> -->
                                </div>

                            <?php endif; ?>    
                           </div>
<!--endprint-->

                </div>
            </div>

        </div>
    </div>
</div>
<span id="h_yjy_where" class="hidden"><?php if((isset($where))): ?><?php echo $where; else: ?>[]<?php endif; ?></span>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
<script src="__CDN__/assets/js/require.js" data-main="__CDN__/assets/js/require-backend.js?v=<?php echo $site['version']; ?>"></script>
    </body>
</html>