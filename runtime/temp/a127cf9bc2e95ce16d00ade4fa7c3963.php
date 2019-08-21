<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:68:"D:\wamp\www\h_two\public/../application/admin\view\wm\depot\add.html";i:1552289055;s:70:"D:\wamp\www\h_two\public/../application/admin\view\layout\default.html";i:1544673823;s:67:"D:\wamp\www\h_two\public/../application/admin\view\common\meta.html";i:1544673823;s:69:"D:\wamp\www\h_two\public/../application/admin\view\common\script.html";i:1544673823;}*/ ?>
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
                                <script src="__ROOT__assets/js/alphabet.js" type="text/javascript"></script>
<form id="add-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action=""  autocomplete="off" onkeydown="if(event.keyCode==13){return false;}">



<div class="form-group col-sm-6">
        <label for="c-name" class="control-label col-xs-12 col-sm-4" style="color: red" ><?php echo __('Name'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-name" data-rule="required" class="form-control" name="row[name]" oninput="setCode('row[name]','row[spell]')" onporpertychange="setCode('row[name]','row[spell]')" type="text">
        </div>
    </div>
    
    <div class="form-group col-sm-6">
        <label for="c-spell" class="control-label col-xs-12 col-sm-4"><?php echo __('Spell'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-spell" data-rule="" readonly="" class="form-control alphanumeric" name="row[spell]" type="text">
        </div>
    </div>
    
    <div class="form-group col-sm-6">
        <label for="c-type" class="control-label col-xs-12 col-sm-4"><?php echo __('Type'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
                        
            <select  id="c-type" data-rule="required" class="form-control selectpicker" name="row[type]">
                <?php if(is_array($typeList) || $typeList instanceof \think\Collection || $typeList instanceof \think\Paginator): if( count($typeList)==0 ) : echo "" ;else: foreach($typeList as $key=>$vo): ?>
                    <option value="<?php echo $key; ?>" <?php if(in_array(($key), explode(',',"1"))): ?>selected<?php endif; ?>><?php echo $vo; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>

        </div>
    </div>
    <div class="form-group col-sm-6">
        <label for="c-manager" class="control-label col-xs-12 col-sm-4"><?php echo __('Manager'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select  id="c-manager" data-rule="required" class="form-control selectpicker" name="row[manager]" data-live-search="true">
                <?php if(is_array($adminList) || $adminList instanceof \think\Collection || $adminList instanceof \think\Paginator): if( count($adminList)==0 ) : echo "" ;else: foreach($adminList as $key=>$vo): ?>
                    <option value="<?php echo $key; ?>" <?php if(in_array(($key), explode(',',""))): ?>selected<?php endif; ?>><?php echo $vo['nickname']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>
    <div class="form-group col-sm-6">
        <label for="c-tel" class="control-label col-xs-12 col-sm-4"><?php echo __('Tel'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-tel"  class="form-control" name="row[tel]" type="text">
        </div>
    </div>
    <div class="form-group col-sm-6">
        <label for="c-addr" class="control-label col-xs-12 col-sm-4"><?php echo __('Addr'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-addr"  class="form-control" name="row[addr]" type="text">
        </div>
    </div>
    <div class="form-group col-sm-6">
        <label for="c-status" class="control-label col-xs-12 col-sm-4"><?php echo __('Status'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
                        
            <?php if(is_array($statusList) || $statusList instanceof \think\Collection || $statusList instanceof \think\Paginator): if( count($statusList)==0 ) : echo "" ;else: foreach($statusList as $key=>$vo): ?>
            <label><input name="row[status]" type="radio" value="<?php echo $key; ?>" <?php if(in_array(($key), explode(',',"normal"))): ?>checked<?php endif; ?> /> <?php echo $vo; ?></label> 
            <?php endforeach; endif; else: echo "" ;endif; ?>

        </div>
    </div>
    <div class="form-group col-sm-6">
        <label for="c-remark" class="control-label col-xs-12 col-sm-4"><?php echo __('Remark'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-remark"  class="form-control" name="row[remark]" type="text">
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