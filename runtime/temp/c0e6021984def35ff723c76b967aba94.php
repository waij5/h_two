<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:78:"D:\wamp\www\h_two\public/../application/admin\view\common\downloadprocess.html";i:1544673823;s:70:"D:\wamp\www\h_two\public/../application/admin\view\layout\default.html";i:1544673823;s:67:"D:\wamp\www\h_two\public/../application/admin\view\common\meta.html";i:1544673823;s:69:"D:\wamp\www\h_two\public/../application/admin\view\common\script.html";i:1544673823;}*/ ?>
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

    <div class="panel-body">
        <div id="myTabContent" class="tab-content">
            <div class="panel-body">
            <h2><?php echo __($downloadTitle); ?></h2>
            <hr />
            <input type="hidden" id="h-record-id" value="<?php echo $cmdRecord->id; ?>" />
            <?php if((empty($cmdRecord->filepath))): ?>
                <div class="progress progress-striped active" style="background-color: rgba(68,72,72,.5);">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" 
                        aria-valuemin="0.2" aria-valuemax="100" style="width: 0.2%;">
                        <span></span>
                    </div>
                </div>
                <div class="text-left text-warning" id="statusText"><?php echo __('PROCESSING'); ?></div>
                <hr />
                <div class="text-right">
                    <!-- <div class="text-left">每2000/5000行时可能会稍有卡顿，请耐心等待</div> -->
                    <a href="javascript:;" id="btn-del-download" class="btn btn-danger hidden" title="<?php echo __('Failed?You can try to delete and regenerate'); ?>" style="margin-right: 5px;"><span class="fa fa-trash">&nbsp;<?php echo __('Delete'); ?></span></a>

                    <a href="javascript:;" id="btn-regenerate" class="btn btn-danger hidden" style="margin-right: 5px;"><span class="fa fa-trash">&nbsp;<?php echo __('Regenerate'); ?></span></a>

                    <a href="<?php echo $downloadLink; ?>" id="btn-download" class="btn btn-success hidden"><span class="fa fa-download">&nbsp;&nbsp;<?php echo __('Download'); ?>&nbsp;&nbsp;</span></a>
                </div>
            <?php else: ?>
                <div class="progress progress-striped active">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" 
                        aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                        <span>100%</span>
                    </div>
                </div>
                <div class="text-left text-warning" id="statusText"><?php echo __('COMPLETED'); ?></div>
                <hr />
                <div class="text-right">
                    <a href="javascript:;" id="btn-regenerate" class="btn btn-danger" style="margin-right: 5px;"><span class="fa fa-trash">&nbsp;<?php echo __('Regenerate'); ?></span></a>
                    <a href="<?php echo $downloadLink; ?>" id="btn-download" class="btn btn-success"><span class="fa fa-download">&nbsp;&nbsp;<?php echo __('Download'); ?>&nbsp;&nbsp;</span></a>
                </div>
            <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
#ribbon{
    display: none;
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