<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:86:"D:\wamp\www\h_two\public/../application/admin\view\customer\customer\comselectpop.html";i:1547103702;s:70:"D:\wamp\www\h_two\public/../application/admin\view\layout\default.html";i:1544673823;s:67:"D:\wamp\www\h_two\public/../application/admin\view\common\meta.html";i:1544673823;s:69:"D:\wamp\www\h_two\public/../application/admin\view\common\script.html";i:1544673823;}*/ ?>
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
            <div class="tab-pane fade active in" id="one">
                <div class="widget-body no-padding">
                    <div class="commonsearch-table">
                        <form class="form-inline form-commonsearch nice-validator n-default" action="" novalidate="novalidate" id="f-commonsearch" role="form" method="post">
                            <fieldset>
                                <div class="form-group" style="margin:5px">
                                    <label for="ctm_id" class="control-label searchPadding"><?php echo __('Ctm_id'); ?></label>
                                    <input type="text" class="form-control" name="ctm_id" value="" placeholder="Id" id="ctm_id">
                                </div>
                                <div class="form-group" style="margin:5px">
                                    <label for="ctm_mobile" class="control-label searchPadding"><?php echo __('Ctm_mobile'); ?></label>
                                    <input type="text" class="form-control" name="ctm_mobile" value="" placeholder="<?php echo __('Ctm_mobile'); ?>" id="ctm_mobile">
                                </div>
                                <div class="form-group" style="margin:5px">
                                    <label for="ctm_name" class="control-label searchPadding"><?php echo __('Ctm_name'); ?></label>
                                    <input type="text" class="form-control" name="ctm_name" value="" placeholder="<?php echo __('Ctm_name'); ?>" id="ctm_name">
                                </div>
                                <div class="form-group" style="margin:5px">
                                    <label for="ctm_name" class="control-label searchPadding">
                                        宏脉卡号
                                    </label>
                                    <input type="text" class="form-control" name="old_ctm_code" value="" placeholder="宏脉卡号" id="old_ctm_code">
                                </div>
                                <!-- <div class="clearfix"></div> -->
                                <div class="form-group" style="margin:5px">
                                    <label for="createtime" class="control-label searchPadding">录入时间:</label>
                                    <input class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" name="createtime_start" type="text" value="" id="createtime_start"> ~ <input  class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" name="createtime_end" type="text" value="" id="createtime_end">
                                </div>
                                <div class="form-group" style="margin:5px">
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-success" style="margin-right: 5px;">提交</button>
                                        <button type="reset" class="btn btn-default">重置</button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div id="toolbar" class="toolbar">
                        <!-- <?php echo build_toolbar(); ?> -->
                    </div>
                    <table id="table" class="table table-striped table-bordered table-hover" 
                           data-operate-edit="<?php echo $auth->check('customer/customer/edit'); ?>" 
                           data-operate-del="<?php echo $auth->check('customer/customer/del'); ?>" 
                           width="100%">
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
    var yjyComSelectParams = <?php echo $yjyComSelectParams; ?>;
</script>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
<script src="__CDN__/assets/js/require.js" data-main="__CDN__/assets/js/require-backend.js?v=<?php echo $site['version']; ?>"></script>
    </body>
</html>