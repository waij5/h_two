<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:86:"D:\wamp\www\h_two\public/../application/admin\view\wmreport\newstocksurplus\index.html";i:1562664196;s:70:"D:\wamp\www\h_two\public/../application/admin\view\layout\default.html";i:1544673823;s:67:"D:\wamp\www\h_two\public/../application/admin\view\common\meta.html";i:1544673823;s:69:"D:\wamp\www\h_two\public/../application/admin\view\common\script.html";i:1544673823;}*/ ?>
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

                    <div class="commonsearch-table ">
                        <form class="form-inline form-commonsearch nice-validator n-default" action="" novalidate="novalidate" id="f-commonsearch" role="form" method="post">
                            <fieldset>
                                <div class="form-group dislocationAll">
                                    <label for="pro_code" class="control-label labelLocation">产品编号</label>
                                    <input type="text" name="p.pro_code" class="form-control clear" size="8" value="<?php echo \think\Request::instance()->param('pro_code'); ?>">
                                </div>
                                <div class="form-group dislocationAll">
                                    <label for="pro_name" class="control-label labelLocation">产品名称</label>
                                    <input type="text" name="p.pro_name" class="form-control clear" size="8" value="<?php echo \think\Request::instance()->param('pro_name'); ?>">
                                </div>
                                <div class="form-group dislocationAll">
                                    <label for="depot_id" class="control-label labelLocation" >仓库</label>
                                    <select id="depot_id" data-rule="" class="form-control " name="p.depot_id" style="width: 100px">
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
            
                                <!-- <div class="form-group marginZero">
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-success dislocationRight">提交</button>
                                        <button type="reset" class="btn btn-default">重置</button>
                                    </div>
                                </div> -->
                                <div class="form-group dislocationAll">
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-success dislocationRight">提交</button>
                                        <a type="reset" class="btn btn-default" id="btn-customer-clear">重置</a>
                                        <button type="button" class="btn btn-default" id="btn-export">导出</button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div><br><br>

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
                    
  					<table class="table table-bordered table-hover" id="table" width="100%"> 
                        

                    </table>
                  
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