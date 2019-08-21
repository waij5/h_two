<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:76:"D:\wamp\www\h_two\public/../application/admin\view\general\notice\index.html";i:1559804939;s:70:"D:\wamp\www\h_two\public/../application/admin\view\layout\default.html";i:1544673823;s:67:"D:\wamp\www\h_two\public/../application/admin\view\common\meta.html";i:1544673823;s:69:"D:\wamp\www\h_two\public/../application/admin\view\common\script.html";i:1544673823;}*/ ?>
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
    <ul class="nav nav-tabs" id="myTab">
        <li class="active">
            <a data-toggle="tab" href="#notice-rv">
                回访提醒
            </a>
        </li>
        <li>
            <a data-toggle="tab" href="#notice-birth">
                生日提醒
            </a>
        </li>
    </ul>
    <div class="panel-body">
        <div id="myTabContent" class="tab-content" style="">
            <div class="tab-pane fade active in" id="notice-rv" style=" height: 550px; height: calc(100VH - 80px);height: height:  -moz-calc(100VH - 80px); height: -ms-calc(100VH - 80px);">
                <iframe src="/customer/rvinfo/todayrevisitnotices" class="notice-iframe" style="border: none; width: 100%; height: 550px; height: calc(100VH - 80px);height: height:  -moz-calc(100VH - 80px); height: -ms-calc(100VH - 80px);"></iframe>
            </div>
            <div class="tab-pane fade" id="notice-birth">
                <iframe src="/customer/customer/listofbirth"  class="notice-iframe" style="border: none; width: 100%; height: 550px; height: calc(100VH - 80px);height: height:  -moz-calc(100VH - 80px); height: -ms-calc(100VH - 80px);"></iframe>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .notice-iframe body {
        background: none;
    }
    .notice-iframe #ribbon {
        display: none
    }
</style>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
<script src="__CDN__/assets/js/require.js" data-main="__CDN__/assets/js/require-backend.js?v=<?php echo $site['version']; ?>"></script>
    </body>
</html>