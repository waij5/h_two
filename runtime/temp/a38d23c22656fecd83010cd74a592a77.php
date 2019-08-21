<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:83:"D:\wamp\www\h_two\public/../application/admin\view\wmreport\stocksurplus\index.html";i:1565084835;s:70:"D:\wamp\www\h_two\public/../application/admin\view\layout\default.html";i:1544673823;s:67:"D:\wamp\www\h_two\public/../application/admin\view\common\meta.html";i:1544673823;s:69:"D:\wamp\www\h_two\public/../application/admin\view\common\script.html";i:1544673823;}*/ ?>
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
#table tr td{border:1px black solid;word-wrap:break-word;text-align: center;vertical-align:middle;width: 80px}
#table tr th{border:1px black solid;text-align: center;vertical-align:middle}
</style>
    <div class="panel-body">
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade active in" id="one">
                <div class="widget-body no-padding">

                    <div class="commonsearch-table">
                        <form class="form-inline  nice-validator nice-validator n-default" action="<?php echo url('wmreport/stocksurplus/index'); ?>" id="f-commonsearch" role="form" method="post">
                            <fieldset style="text-align: left;">
                                
                                <div class="form-group dislocationAll">
                                    <label for="p_num" class="control-label labelLocation">产品编号</label>
                                    <input type="text" name="p_num" class="form-control clear" size="8" value="<?php echo \think\Request::instance()->param('p_num'); ?>">
                                </div>
                                <div class="form-group dislocationAll">
                                    <label for="p_name" class="control-label labelLocation">产品名称</label>
                                    <input type="text" name="p_name" class="form-control clear" size="8" value="<?php echo \think\Request::instance()->param('p_name'); ?>">
                                </div>
                                <div class="form-group dislocationAll">
                                    <label for="depot_id" class="control-label labelLocation" >仓库</label>
                                    <select id="depot_id" data-rule="" class="form-control " name="depot_id" style="width: 100px">
                                        <option value='' >------ALL------</option>
                                        <?php if(is_array($depotList) || $depotList instanceof \think\Collection || $depotList instanceof \think\Paginator): if( count($depotList)==0 ) : echo "" ;else: foreach($depotList as $key=>$vo): ?>
                                        <option value="<?php echo $vo['id']; ?>" <?php if(in_array(($vo['id']), is_array(\think\Request::instance()->param('depot_id'))?\think\Request::instance()->param('depot_id'):explode(',',\think\Request::instance()->param('depot_id')))): ?>selected<?php endif; ?>><?php echo $vo['name']; ?></option>
                                        <?php endforeach; endif; else: echo "" ;endif; ?>
                                        
                                    </select>
                                </div>
                                <!--<br>-->
                                <div class="form-group dislocationAll">
                                    <label for="id" class="control-label labelLocation">发生日期</label>
                                    <input type="text" name="stime" class="form-control datetimepicker " data-use-current="true" data-date-format="YYYY-MM-DD" <?php if(is_null(\think\Request::instance()->param('stime'))): ?> value="<?php echo date('Y-m-01');?>"<?php else: ?> value="<?php echo \think\Request::instance()->param('stime'); ?>"<?php endif; ?> size="8">  ~  <input type="text" name="etime" class="form-control datetimepicker " data-use-current="true" data-date-format="YYYY-MM-DD" <?php if(is_null(\think\Request::instance()->param('etime'))): ?> value="<?php echo date('Y-m-d',strtotime(date('Y-m-01').' +1 month -1 day'));?>"<?php else: ?> value="<?php echo \think\Request::instance()->param('etime'); ?>"<?php endif; ?> size="8">
                                </div>
                                


                                <!--<br>-->
                                <div class="form-group dislocationAll">
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
                       <div style="text-align: center;font-weight:bold;font-size: 20px;"> 产品库存结余表</div>
					</div>
					<div class="proreportDate" style="font-family: KaiTi;width: 100%;height: 22px;">
                        <?php if(!empty($total)): ?>
                        <div style="font-weight:normal;font-size: 15px;float: left;" >
                            <b>期初参考成本:</b><span style="color: #18bc9c"><?php echo !empty($total['beginCost'])?$total['beginCost']:'0'; ?></span>&nbsp;&nbsp;
                            <b>本期入库成本:</b><span style="color: #18bc9c"><?php echo !empty($total['enterCost'])?$total['enterCost']:'0'; ?></span>&nbsp;&nbsp;
                            <b>本期出库成本:</b><span style="color: #18bc9c"><?php echo !empty($total['outCost'])?$total['outCost']:'0'; ?></span>&nbsp;&nbsp;
                            <b>期末参考成本:</b><span style="color: #18bc9c"><?php echo !empty($total['endCost'])?$total['endCost']:'0'; ?></span>&nbsp;&nbsp;
                        </div>
                        <?php endif; ?>
                        <div style="font-weight:normal;font-size: 13px;float: right;">打印日期：<?php echo date('Y-m-d');?></div>
                    </div>
  					<div id="consumTable" style="position: relative;overflow-y: auto;">
                        <table id="table" class="table table-striped table-bordered table-hover scrolltable" cellspacing="0" cellpadding="0" style="width: 100%;table-layout: fixed;overflow-x: auto" >
                           <thead style="">
                           <tr>
                               
                                <!-- <a href="javascript:;" class="nameSort">产品名称</a> -->
                                <th rowspan="2">产品编号</th>
                                <th rowspan="2" style="color: red">产品名称</th>
                                <th rowspan="2">规格</th>
                                <th rowspan="2">类别</th>
                                <th rowspan="2">单位</th>

                                <th rowspan="2" style="color: red">现有库存</th>
                                <th rowspan="1" colspan="10">库存变动汇总</th>
                            </tr>
                            <tr >
                                
                                <th rowspan="1" colspan="1" >期初库存</th>
                                <th rowspan="1" colspan="1" >参考成本</th>
                                <th rowspan="1" colspan="1" >应销金额</th>

                                <th rowspan="1" colspan="1" style="color: red">本期入库</th>
                                <th rowspan="1" colspan="1" >参考成本</th>
                                <th rowspan="1" colspan="1" style="color: red">本期出库</th>
                                <th rowspan="1" colspan="1" >参考成本</th>

                                <th rowspan="1" colspan="1" >期末库存</th>
                                <th rowspan="1" colspan="1" >参考成本</th>
                                <th rowspan="1" colspan="1" >应销金额</th>
                            </tr>
                            </thead>
                           
                            <?php if(!empty($data)): ?>

                                    <tbody style="max-height: 200px; overflow-y: overlay;">
                                    <?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): if( count($data)==0 ) : echo "" ;else: foreach($data as $k=>$v): ?>
                                    
                                    <tr>

                                        
                                        <td><?php echo $v['pro_code']; ?></td>
                                        <td style="color: red"><?php echo $v['pro_name']; ?></td>
                                        <td><?php echo $v['pro_spec']; ?></td>
                                        <td><?php echo $v['pro_cat1']; ?></td>
                                        <td><?php echo $v['uname']; ?></td>
                                        <td><?php echo $v['pro_stock']; ?></td>
                                        <td>
                                            <?php echo !empty($v['beginStock'])?$v['beginStock']:'0'; ?>
                                        </td>

                                        <td>
                                            <?php echo !empty($v['beginCost'])?$v['beginCost']:'0'; ?>
                                        </td>
                                        <td>
                                            <?php echo !empty($v['beginPrice'])?$v['beginPrice']:'0'; ?>
                                        </td>

                                        <td>
                                            <?php echo !empty($v['nowEnterStock'])?$v['nowEnterStock']:'0'; ?>
                                        </td>
                                        <td>
                                            <?php echo !empty($v['nowEnterCost'])?$v['nowEnterCost']:'0'; ?>
                                        </td>
                                        <td>
                                            <?php echo !empty($v['nowOutStock'])?$v['nowOutStock']:'0'; ?>
                                        </td>
                                        <td>
                                            <?php echo !empty($v['nowOutCost'])?$v['nowOutCost']:'0'; ?>
                                        </td>

                                        <td>
                                            <?php echo !empty($v['endStock'])?$v['endStock']:'0'; ?>
                                        </td>
                                        <td><?php echo !empty($v['endCost'])?$v['endCost']:'0'; ?></td>
                                        <td><?php echo !empty($v['endPrice'])?$v['endPrice']:'0'; ?></td>
                                        
                                    </tr>
                                   
                                    <?php endforeach; endif; else: echo "" ;endif; ?>
                                    </tbody>
                            <?php endif; ?>
                            
                        </table>
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