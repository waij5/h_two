<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:72:"D:\wamp\www\h_two\public/../application/admin\view\wm\apparatus\add.html";i:1561102636;s:70:"D:\wamp\www\h_two\public/../application/admin\view\layout\default.html";i:1544673823;s:67:"D:\wamp\www\h_two\public/../application/admin\view\common\meta.html";i:1544673823;s:69:"D:\wamp\www\h_two\public/../application/admin\view\common\script.html";i:1544673823;}*/ ?>
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
    .red{color: red}
</style>
<!-- <script src="__ROOT__assets/js/alphabet.js" type="text/javascript"></script> -->
<form id="add-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action=""  autocomplete="off"  onkeydown="if(event.keyCode==13){return false;}">
<br>
    <div class="form-group col-sm-6">
        <label for="a_code" class="control-label col-xs-12 col-sm-4">编号:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="a_code" data-rule="required" value="<?php echo $a_code; ?>" class="form-control" name="row[a_code]" type="text">
        </div>
        <!-- <div class="col-xs-12 col-sm-2" style="">
            <a href="javascript:;" class="btn btn-success getNum"><i class="fa fa-refresh"></i> </a>
        </div> -->
    </div>
    <div class="form-group col-sm-6">
        <label for="a_name" class="control-label col-xs-12 col-sm-4 red ">器械名称:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="a_name" data-rule="required" class="form-control" name="row[a_name]" type="text">
        </div>
    </div>
<br>
    <div class="form-group col-sm-6">
        <label for="a_unit" class="control-label col-xs-12 col-sm-4 red">单位:</label>
        <div class="col-xs-12 col-sm-8">
            <select id="a_unit" data-rule="required" class="form-control selectpicker" name="row[a_unit]">
                <?php if(is_array($unitList) || $unitList instanceof \think\Collection || $unitList instanceof \think\Paginator): if( count($unitList)==0 ) : echo "" ;else: foreach($unitList as $key=>$vo): ?>
                <option value="<?php echo $vo['id']; ?>" ><?php echo $vo['name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>

    <div class="form-group col-sm-6">
        <label for="a_spec" class="control-label col-xs-12 col-sm-4 red">规格型号:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="a_spec" data-rule="required" class="form-control" name="row[a_spec]" type="text" value="">
        </div>
    </div>

    <div class="form-group col-sm-6">
        <label for="a_depot" class="control-label col-xs-12 col-sm-4 red">仓库:</label>
        <div class="col-xs-12 col-sm-8">
            <select id="a_depot" data-rule="required" class="form-control " name="row[a_depot]">
                <?php if(is_array($depotList) || $depotList instanceof \think\Collection || $depotList instanceof \think\Paginator): if( count($depotList)==0 ) : echo "" ;else: foreach($depotList as $key=>$vo): ?>
                <option value="<?php echo $vo['id']; ?>" <?php if(in_array(($key), explode(',',""))): ?>selected<?php endif; ?>><?php echo $vo['name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>

    <div class="form-group col-sm-12">
        <label for="a_remark" class="control-label col-xs-12 col-sm-2">备注:</label>
        <div class="col-xs-12 col-sm-9">
            <input id="a_remark" class="form-control" name="row[a_remark]" type="text">
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