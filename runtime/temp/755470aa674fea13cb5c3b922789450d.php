<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:79:"D:\wamp\www\h_two\public/../application/admin\view\wmreport\checklot\index.html";i:1565084758;s:70:"D:\wamp\www\h_two\public/../application/admin\view\layout\default.html";i:1544673823;s:67:"D:\wamp\www\h_two\public/../application/admin\view\common\meta.html";i:1544673823;s:69:"D:\wamp\www\h_two\public/../application/admin\view\common\script.html";i:1544673823;}*/ ?>
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
                        <form class="form-inline  nice-validator nice-validator n-default" action="<?php echo url('wmreport/checklot/index'); ?>" id="f-commonsearch" role="form" method="post">
                            <fieldset style="text-align: left;">

                                <div class="form-group dislocationAll">
                                    <label for="c_num" class="control-label labelLocation">产品编号</label>
                                    <input type="text" name="c_num" class="form-control clear" size="8"  value="<?php echo \think\Request::instance()->param('c_num'); ?>"/>
                                </div>
                                <div class="form-group dislocationAll">
                                    <label for="c_name" class="control-label labelLocation">产品名称</label>
                                    <input type="text" name="c_name" class="form-control clear" size="8" value="<?php echo \think\Request::instance()->param('c_name'); ?>">
                                </div>

                                <div class="form-group dislocationAll">
                                    <label for="c_depot_id" class="control-label labelLocation">所属仓库</label>
                                    <select id="c_depot_id" class="form-control clear" name="c_depot_id">
                                        <option data-type="" value='' >--- 请选择 ---</option>
                                        <?php if(is_array($depotList) || $depotList instanceof \think\Collection || $depotList instanceof \think\Paginator): if( count($depotList)==0 ) : echo "" ;else: foreach($depotList as $key=>$vo): ?>
                                        <option  value="<?php echo $vo['id']; ?>" <?php if(in_array(($vo['id']), is_array(\think\Request::instance()->param('c_depot_id'))?\think\Request::instance()->param('c_depot_id'):explode(',',\think\Request::instance()->param('c_depot_id')))): ?>selected<?php endif; ?>><?php echo $vo['name']; ?></option>
                                        <?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                </div>

                                <!--<br>-->
                                <div class="form-group" >
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
                       <div style="text-align: center;font-weight:bold;font-size: 20px;">产品批号盘点表</div>
					</div>
                    <div class="proreportDate" style="font-family: KaiTi;width: 100%;height: 22px;">
                        <?php if(!empty($total)): ?>
                        <div style="font-weight:normal;font-size: 15px;float: left;" >
                            <b>总成本: </b><span style="color: #18bc9c"><?php echo $total['cost']; ?></span>&nbsp;&nbsp;
                            <b>总库存: </b><span style="color: #18bc9c"><?php echo $total['stock']; ?></span>&nbsp;&nbsp;
                        </div>
                        <?php endif; ?>
                        <div style="font-weight:normal;font-size: 13px;float: right;">打印日期：<?php echo date('Y-m-d');?></div>
                    </div>
                    <div id="consumTable" style="position: relative;overflow-y: auto;">
                    <?php if(!empty($data)): ?>
                        <table id="table" class=" table table-striped table-bordered table-hover scrolltable" style="width: 100%;table-layout: fixed;" >
                        
                            <thead>
                            <tr style="border:1px solid;">
                                <th>产品编号</th>
                                <th class="red">产品名称</th>
                                <th>单位</th>
                                <th>规格</th>
                                <th>所属仓库</th>
                                <th>类别</th>
                                <th class="red">批号</th>
                                <th class="red">成本单价</th>
                                <th class="red">库存</th>
                                <th>有效期</th>
                                <th>供应商</th>
                                <th>生产厂家</th>
                                
                                
                            </tr>
</thead>
                            <?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): if( count($data)==0 ) : echo "" ;else: foreach($data as $ka=>$va): if(is_array($va) || $va instanceof \think\Collection || $va instanceof \think\Paginator): if( count($va)==0 ) : echo "" ;else: foreach($va as $ko=>$vo): ?>
                                    <tr style="border:1px solid;">
                                        <td><?php if($counts[$ka]-$ko <$counts[$ka]): else: ?> <?php echo $vo['pro_code']; endif; ?></td>
                                        <td class="red"><?php if($counts[$ka]-$ko <$counts[$ka]): else: ?> <?php echo $vo['pro_name']; endif; ?></td>
                                        <td><?php if($counts[$ka]-$ko <$counts[$ka]): else: ?> <?php echo $vo['uname']; endif; ?></td>
                                        <td><?php if($counts[$ka]-$ko <$counts[$ka]): else: ?> <?php echo $vo['pro_spec']; endif; ?></td>
                                        <td><?php if($counts[$ka]-$ko <$counts[$ka]): else: ?> <?php echo $vo['dname']; endif; ?></td>
                                        <td><?php echo $vo['pro_cat1']; ?>*<?php echo $vo['pro_cat2']; ?></td>
                                        <td class="red"><?php echo $vo['lotnum']; ?></td>
                                        <td class="red"><?php echo $vo['lcost']; ?></td>
                                        <td class="red"><?php echo $vo['lstock']; ?></td>
                                        <td><?php if($vo['letime']>0): ?><?php echo date('Y-m-d',$vo['letime']); endif; ?></td>
                                        <td><?php echo $vo['sup_name']; ?></td>
                                        <td><?php echo $vo['lproducer']; ?></td>
                                        
                                    </tr>
                                <?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
                        </table>
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