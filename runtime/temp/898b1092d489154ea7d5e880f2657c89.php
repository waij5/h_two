<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:74:"D:\wamp\www\h_two\public/../application/admin\view\wm\drugs\searchlot.html";i:1559618879;s:70:"D:\wamp\www\h_two\public/../application/admin\view\layout\default.html";i:1544673823;s:67:"D:\wamp\www\h_two\public/../application/admin\view\common\meta.html";i:1544673823;s:69:"D:\wamp\www\h_two\public/../application/admin\view\common\script.html";i:1544673823;}*/ ?>
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
                                <style type="text/css">
    .disnone{display: none}
</style>
<div class="panel panel-default panel-intro">
    <?php echo build_heading(); ?>
    <div class="panel-body">
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade active in" id="one">
                <div class="widget-body no-padding">
                    <div id="toolbar" class="toolbar">

                    <table id="table" class=" table table-striped table-bordered table-hover" style="width: 100%;" >
                        <tr style="">
                            <th>Id</th>
                            <th>批号</th>
                            <th>剩余库存</th>
                            <th style="width: 12%">成本单价</th>
                            <th style="width: 12%">零售价</th>
                            <th>生产日期</th>
                            <th>有效日期</th>
                            <th style="text-align: center">操作</th>
                            <!-- <?php if($auth->isSuperAdmin()): endif; ?> -->
                            
                        </tr>
                        <?php if(is_array($row) || $row instanceof \think\Collection || $row instanceof \think\Paginator): if( count($row)==0 ) : echo "" ;else: foreach($row as $k=>$v): ?>
                        <tr>
                            <td><?php echo $v['lot_id']; ?></td>
                            <td><?php echo $v['lotnum']; ?></td>
                            <td><?php echo $v['lstock']; ?></td>
                            <td class="lcost"><?php echo $v['lcost']; ?></td>
                            <td class="lprice"><?php echo $v['lprice']; ?></td>
                            <td><?php if($v['lstime']>0): ?><?php echo date('Y-m-d',$v['lstime']); endif; ?></td>
                            <td class="letime">
                                <span class="letimes" ><?php if($v['letime']>0): ?><?php echo date('Y-m-d',$v['letime']); endif; ?></span>
                                
                            </td>
                            <td class="letimess disnone"><input type="text" value="" class="datetimepicker letimesss"  data-date-format="YYYY-MM-DD" name="letime"  size="7"></td>
                            <td style="text-align: center" class="operate">
                            <!-- <?php if($auth->isSuperAdmin()): ?> && $v.lcost=="0"-->
                                <!-- <?php if($v['lchangecost_type']==""): ?><button class="btn btn-xs btn-success change_cost" data-lot="<?php echo $v['lot_id']; ?>" >改&nbsp;&nbsp;&nbsp;价</button ><?php elseif($v['lchangecost_type']=="1"): ?><a href="javascript:;" class="btn btn-xs btn-primary "  >已改价</a><?php endif; ?> -->
                            <!-- <?php endif; ?> -->
                            <?php if($v['lchangecost_type']=="" && $v['lcost']=="0"): ?><button class="btn btn-xs btn-success change_cost" data-lot="<?php echo $v['lot_id']; ?>" >改&nbsp;&nbsp;&nbsp;价</button ><?php elseif($v['lchangecost_type']=="1"): ?><a href="javascript:;" class="btn btn-xs btn-primary "  >已改价</a><?php endif; ?>
                            <a href="javascript:;" class="btn btn-xs btn-primary change_letime" data-lots="<?php echo $v['lot_id']; ?>" >改效期</a>
                            </td>
                        </tr>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </table>
                    </div>
                                    
                </div>
            </div>

        </div>
    </div>
</div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
<script src="__CDN__/assets/js/require.js" data-main="__CDN__/assets/js/require-backend.js?v=<?php echo $site['version']; ?>"></script>
    </body>
</html>