<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:70:"D:\wamp\www\h_two\public/../application/admin\view\wm\drugscj\add.html";i:1552289088;s:70:"D:\wamp\www\h_two\public/../application/admin\view\layout\default.html";i:1544673823;s:67:"D:\wamp\www\h_two\public/../application/admin\view\common\meta.html";i:1544673823;s:69:"D:\wamp\www\h_two\public/../application/admin\view\common\script.html";i:1544673823;}*/ ?>
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
    .resultList tr th{background-color:#ddddddd6;text-align: center;};
</style>
<form id="add-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action=""  autocomplete="off" onkeydown="if(event.keyCode==13){return false;}">
<div>
    <div class="form-group col-sm-6">
        <label for="c-man_num" class="control-label col-xs-12 col-sm-4"><?php echo __('man_num'); ?>:</label>
        <div class="col-xs-12 col-sm-5">
            <input id="c-man_num" data-rule="required" readonly="readonly" value="<?php echo $man_num; ?>" class="form-control" name="row[man_num]" type="text">
        </div>
        <div class="col-xs-12 col-sm-2" style="">
            <a href="javascript:;" class="btn btn-success getNum"><i class="fa fa-refresh"></i> </a>
        </div>
    </div>

    <div class="form-group col-sm-6">
        <label for="c-muid" class="control-label col-xs-12 col-sm-4"><?php echo __('muid'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-muid" data-rule="required" readonly="readonly" class="form-control" name="row[muid]" type="text" value="<?php echo $admin['nickname']; ?>">
        </div>
    </div>

    <div class="form-group col-sm-6">
        <label for="c-msupplier_id" class="control-label col-xs-12 col-sm-4"><?php echo __('msupplier_id'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select id="c-msupplier_id" data-rule="" class="form-control selectpicker" name="row[msupplier_id]" data-live-search="true">
                <option value=""></option>
                <?php if(is_array($supplierList) || $supplierList instanceof \think\Collection || $supplierList instanceof \think\Paginator): if( count($supplierList)==0 ) : echo "" ;else: foreach($supplierList as $key=>$vo): ?>
                <option  value="<?php echo $vo['sup_id']; ?>"><?php echo $vo['sup_name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>

    <div class="form-group col-sm-6">
        <label for="c-mdepot_id" class="control-label col-xs-12 col-sm-4" style="color: red"><?php echo __('mdepot_id'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select id="c-mdepot_id" data-rule="required" class="form-control " name="row[mdepot_id]">
                <?php if(is_array($depotList) || $depotList instanceof \think\Collection || $depotList instanceof \think\Paginator): if( count($depotList)==0 ) : echo "" ;else: foreach($depotList as $key=>$vo): ?>
                <option data-type="<?php echo $vo['name']; ?>" value="<?php echo $vo['id']; ?>" <?php if(in_array(($vo['id']), explode(',',""))): ?>selected<?php endif; ?>><?php echo $vo['name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>


<!-- 次类型（冲减类型）：31、入库冲减；32：盘亏冲减；33：过期冲减；34：其他冲减 -->
    <div class="form-group col-sm-6">
        <label for="c-msecond_type" class="control-label col-xs-12 col-sm-4" style="color: red"><?php echo __('cj_type'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select id="c-msecond_type" data-rule="required" class="form-control " name="row[msecond_type]">
                <?php if(is_array($cjType) || $cjType instanceof \think\Collection || $cjType instanceof \think\Paginator): if( count($cjType)==0 ) : echo "" ;else: foreach($cjType as $key=>$vo): ?>
                <option  value="<?php echo $key; ?>" ><?php echo $vo; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>

    <!-- 主类型3：冲减 -->
    <input type="hidden" name="row[mprimary_type]" value="3">

    
    <!-- mbelong_type所属类型 1：药品 -->
    <input type="hidden" name="row[mbelong_type]" value="1">
    <input type="hidden" name="row[mstatus]" value="1">

    <div class="form-group col-sm-12">
        <label for="c-mremark" class="control-label col-xs-12 col-sm-2"><?php echo __('mremark'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-mremark" class="form-control" name="row[mremark]" type="text">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><b style="color: red">药品列表:</b></label>



        <div class="" style="">
                <div class="col-xs-6 col-sm-7" style="">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12" id="pro_menu" onmouseleave="$('#word').addClass('hidden');">
                            <input type="text" id="pro_search" onmouseenter="$('#word').removeClass('hidden')" value="" placeholder="请输入药品拼音简码" style="position: relative;" class="form-control keyword" />
                            <div style="position: relative;width: 135%" >
                                <table id="word" data-index="" style="list-style:none;position: absolute;cursor: pointer;z-index: 999;height: auto;max-height: 200px;overflow-y: auto;padding-bottom: 0;padding-top: 0;table-layout:fixed" class="form-control resultList table table-striped table-bordered table-hover alldata">

                                </table>
                            </div>
                        </div>
                        <a href="javascript:;" id="pro_add" class="btn btn-success btn-add hidden"><i class="fa fa-plus"></i></a>
                    </div>
                </div>
            <div class="col-sm-3 col-xs-3" style="">           
                <a href="javascript:;" class="btn btn-danger" id="clear"><?php echo __('Clear'); ?></a>
            </div>
        </div>
    </div>
</div>


    <div class="form-group" style="width: 100%;overflow-x: scroll;">
      	<div class="col-xs-12 col-sm-12">
        <table id="selectedDrugs" class="table table-striped table-bordered table-hover alldata" style="width: 1200px;text-align: center;">
            <tr><td style="color: red;width: 120px;">药品名称</td><td style="color: red;width: 60px;">批号</td><td style="width: 50px;">库存</td><td style="color: red;width: 50px;">冲减数量</td><td style="width: 60px;">规格</td><td style="width: 40px;">单位</td><td style="width: 50px;color: red;">进货价</td><td style="width: 70px;">总进价</td><!-- <td style="width: 60px;color: red;">零售价</td><td style="width: 70px;">总售价</td> --><td style="color: red;width: 60px;">到期日期</td><td style="width: 70px;">生产日期</td><td style="width: 60px;">生产厂家</td><td style="width: 70px;">产地</td><td style="width: 60px;">批准文号</td><td style="width: 60px;">注册文号</td><td style="width: 30px;">操作</td></tr>
        </table>
        </div>
    </div>
    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-success btn-embossed disabled" id="sure"><?php echo __('OK'); ?></button>
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