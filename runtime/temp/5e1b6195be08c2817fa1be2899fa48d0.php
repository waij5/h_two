<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:76:"D:\wamp\www\h_two\public/../application/admin\view\wm\apparatus\edit_al.html";i:1561102928;s:70:"D:\wamp\www\h_two\public/../application/admin\view\layout\default.html";i:1544673823;s:67:"D:\wamp\www\h_two\public/../application/admin\view\common\meta.html";i:1544673823;s:69:"D:\wamp\www\h_two\public/../application/admin\view\common\script.html";i:1544673823;}*/ ?>
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
        <label for="a_code" class="control-label col-xs-12 col-sm-4">名称:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="a_name" data-rule="required" readonly="readonly" value="<?php echo $alData['a_name']; ?>" class="form-control" type="text">
            <input type="hidden" id="al_aid" name="row[al_aid]" value="<?php echo $alData['a_name']; ?>">
            <input type="hidden" id="al_id" name="row[al_id]" value="<?php echo $alData['al_id']; ?>">
        </div>
        <!-- <div class="col-xs-12 col-sm-2" style="">
            <a href="javascript:;" class="btn btn-success getNum"><i class="fa fa-refresh"></i> </a>
        </div> -->
    </div>
    <div class="form-group col-sm-6">
        <label for="alotnum" class="control-label col-xs-12 col-sm-4 red ">批号:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="alotnum" data-rule="required" class="form-control" name="row[alotnum]" type="text" value="<?php echo $alData['alotnum']; ?>">
        </div>
    </div>
<br>
    <div class="form-group col-sm-6">
        <label for="alcost" class="control-label col-xs-12 col-sm-4 red">价格:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="alcost" data-rule="required" class="form-control" name="row[alcost]" type="text" value="<?php echo $alData['alcost']; ?>">
        </div>
    </div>

    <div class="form-group col-sm-6">
        <label for="alnum" class="control-label col-xs-12 col-sm-4 red">数量:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="alnum" data-rule="required" class="form-control" name="row[alnum]" type="text"  value="<?php echo $alData['alnum']; ?>">
        </div>
    </div>

    <div class="form-group col-sm-6">
        <label for="alshop_time" class="control-label col-xs-12 col-sm-4 red">购买日期:</label>
        <div class="col-xs-12 col-sm-8">
        <input type="text" name="alshop_time" id="alshop_time"  value="<?php echo $alData['alshop_time']; ?>" class="form-control datetimepicker clear" data-use-current="true" data-date-format="YYYY-MM-DD" size="4">
        </div>
    </div>

    <div class="form-group col-sm-6">
        <label for="alstatus" class="control-label col-xs-12 col-sm-4 red">状态:</label>
        <div class="col-xs-12 col-sm-8">
            <select id="alstatus" class="form-control " disabled name="row[alstatus]" >
                <option  value="1" <?php if(in_array((1), is_array($alData['alstatus'])?$alData['alstatus']:explode(',',$alData['alstatus']))): ?>selected<?php endif; ?>>进库</option>
                <option  value="2" <?php if(in_array((2), is_array($alData['alstatus'])?$alData['alstatus']:explode(',',$alData['alstatus']))): ?>selected<?php endif; ?>>报废</option>
            </select>
        </div>
    </div>

    <div class="form-group col-sm-6">
        <label for="alstime" class="control-label col-xs-12 col-sm-4 red">生产日期:</label>
        <div class="col-xs-12 col-sm-8">
        <input type="text" name="alstime" id="alstime"  value="<?php echo $alData['alstime']; ?>" class="form-control datetimepicker clear" data-use-current="true" data-date-format="YYYY-MM-DD" size="4">
        </div>
    </div>

    <div class="form-group col-sm-6">
        <label for="aletime" class="control-label col-xs-12 col-sm-4 red">有效日期:</label>
        <div class="col-xs-12 col-sm-8">
        <input type="text" name="aletime" id="aletime" data-rule="required"  value="<?php echo $alData['aletime']; ?>" class="form-control datetimepicker clear" data-use-current="true" data-date-format="YYYY-MM-DD" size="4">
        </div>
    </div>

    

    <div class="form-group col-sm-6">
        <label for="alsupplier" class="control-label col-xs-12 col-sm-4 ">供应商:</label>
        <div class="col-xs-12 col-sm-8">
            <select id="alsupplier"  class="form-control " disabled name="row[alsupplier]" data-live-search="true">
                <option value="">---请选择---</option>
                <?php if(is_array($supplierList) || $supplierList instanceof \think\Collection || $supplierList instanceof \think\Paginator): if( count($supplierList)==0 ) : echo "" ;else: foreach($supplierList as $key=>$vo): ?>
                <option  value="<?php echo $vo['sup_id']; ?>" <?php if(in_array(($vo['sup_id']), is_array($alData['alsupplier'])?$alData['alsupplier']:explode(',',$alData['alsupplier']))): ?>selected<?php endif; ?>><?php echo $vo['sup_name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>

    <div class="form-group col-sm-6">
        <label for="alproducer" class="control-label col-xs-12 col-sm-4 ">生产厂家:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="alproducer"  class="form-control" name="row[alproducer]" type="text"  value="<?php echo $alData['alproducer']; ?>">
        </div>
    </div>


    <div class="form-group col-sm-6">
        <label for="aluser" class="control-label col-xs-12 col-sm-4 " >使用人:</label>
        <div class="col-xs-12 col-sm-8">
            <select id="aluser"  class=" form-control"  disabled name="row[aluser]" data-live-search="true">
                <option value="">---请选择---</option>
                <?php if(is_array($userList) || $userList instanceof \think\Collection || $userList instanceof \think\Paginator): if( count($userList)==0 ) : echo "" ;else: foreach($userList as $key=>$vo): if($vo['status'] == 'normal'): ?>
                <option  value="<?php echo $vo['id']; ?>" <?php if(in_array(($vo['id']), is_array($alData['aluser'])?$alData['aluser']:explode(',',$alData['aluser']))): ?>selected<?php endif; ?>><?php echo $vo['username']; ?>---<?php echo $vo['nickname']; ?></option>
                <?php endif; endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>

    <div class="form-group col-sm-6">
        <label for="aldepart" class="control-label col-xs-12 col-sm-4 " >使用科室:</label>
        <div class="col-xs-12 col-sm-8">
            <select id="aldepart"  class=" form-control" disabled name="row[aldepart]" data-live-search="true">
                <option value="">---请选择---</option>
                <?php if(is_array($deptList) || $deptList instanceof \think\Collection || $deptList instanceof \think\Paginator): if( count($deptList)==0 ) : echo "" ;else: foreach($deptList as $key=>$vo): ?>
                <option value="<?php echo $vo['dept_id']; ?>" <?php if(in_array(($vo['dept_id']), is_array($alData['aldepart'])?$alData['aldepart']:explode(',',$alData['aldepart']))): ?>selected<?php endif; ?>><?php echo $vo['dept_name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>



    <div class="form-group col-sm-12">
        <label for="alremark" class="control-label col-xs-12 col-sm-2">备注:</label>
        <div class="col-xs-12 col-sm-9">
            <input id="alremark" class="form-control" name="row[alremark]" type="text" value="<?php echo $alData['alremark']; ?>">
        </div>
    </div>
    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <!-- <button type="button" class="btn btn-success btn-embossed " id="sureAddAL"><?php echo __('OK'); ?></button> -->
            <!-- <button type="reset" class="btn btn-default btn-embossed"><?php echo __('Reset'); ?></button> -->
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