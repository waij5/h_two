<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:70:"D:\wamp\www\h_two\public/../application/admin\view\wm\pducat\edit.html";i:1544673823;s:70:"D:\wamp\www\h_two\public/../application/admin\view\layout\default.html";i:1544673823;s:67:"D:\wamp\www\h_two\public/../application/admin\view\common\meta.html";i:1544673823;s:69:"D:\wamp\www\h_two\public/../application/admin\view\common\script.html";i:1544673823;}*/ ?>
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
                                <form id="edit-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action="">

    <div class="form-group">
        <label for="c-pdc_code" class="control-label col-xs-12 col-sm-2"><?php echo __('Pdc_code'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-pdc_code" data-rule="required" class="form-control" name="row[pdc_code]" type="text" value="<?php echo $row['pdc_code']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="c-pdc_name" class="control-label col-xs-12 col-sm-2"><?php echo __('Pdc_name'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-pdc_name" data-rule="required" class="form-control" name="row[pdc_name]" type="text" value="<?php echo $row['pdc_name']; ?>">
        </div>
    </div>
    
    <div class="form-group">
        <label for="c-pdc_pid" class="control-label col-xs-12 col-sm-2"><?php echo __('Pdc_pid'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
          
            <?php echo build_select('row[pdc_pid]', $pduList, $row['pdc_pid'], ['class'=>'form-control selectpicker', 'required'=>'']); ?>
        </div>
    </div>
    <div class="form-group">
        <label for="c-pdc_status" class="control-label col-xs-12 col-sm-2"><?php echo __('Pdc_status'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-pdc_status" data-rule="required" class="form-control" name="row[pdc_status]" type="hidden" value="<?php echo $row['pdc_status']; ?>">
            <input id="status-switch" type="checkbox" checked />
        </div>
    </div>
    <div class="form-group">
        <label for="c-pdc_sort" class="control-label col-xs-12 col-sm-2"><?php echo __('Pdc_sort'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-pdc_sort" data-rule="" class="form-control" name="row[pdc_sort]" type="number" value="<?php echo $row['pdc_sort']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="c-pdc_remark" class="control-label col-xs-12 col-sm-2"><?php echo __('Pdc_remark'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-pdc_remark" data-rule="" class="form-control" name="row[pdc_remark]" type="text" value="<?php echo $row['pdc_remark']; ?>">
        </div>
    </div>
    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-success btn-embossed disabled"><?php echo __('OK'); ?></button>
            <button type="reset" class="btn btn-default btn-embossed"><?php echo __('Reset'); ?></button>
        </div>
    </div>
</form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
<script src="__CDN__/assets/js/require.js" data-main="__CDN__/assets/js/require-backend.js?v=<?php echo $site['version']; ?>"></script>
    </body>
</html>