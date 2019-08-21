<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:77:"D:\wamp\www\h_two\public/../application/admin\view\wm\apparatus\scrap_al.html";i:1561099278;s:70:"D:\wamp\www\h_two\public/../application/admin\view\layout\default.html";i:1544673823;s:67:"D:\wamp\www\h_two\public/../application/admin\view\common\meta.html";i:1544673823;s:69:"D:\wamp\www\h_two\public/../application/admin\view\common\script.html";i:1544673823;}*/ ?>
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
<form id="add-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action="<?php echo url('/wm/drugscf/dispensing'); ?>"  autocomplete="off">
<br><div>
    
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><b style="color: red">器械批号列表:</b></label>


        

        <div class="" style="">
                <div class="col-xs-6 col-sm-7" style="">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12" id="pro_menu"  onmouseleave="$('#words').addClass('hidden');">
                            <input type="text" readonly <?php if($data): ?>value="<< 器械名称：<?php echo $data[0]['a_name']; ?> >> "<?php else: ?>value=''<?php endif; ?> style="position: relative;" class="form-control keyword" onmouseenter="$('#words').removeClass('hidden')"/>
                            <div style="position: relative;width: 130%">
                                <table id="words" data-index="" style="list-style:none;position: absolute;cursor: pointer;z-index: 999;height: auto;max-height: 200px;overflow-y: auto;padding: 0;table-layout:fixed;background-color: white" class=" resultList table table-striped table-bordered table-hover alldata">
                                    <tr class="onloading"><th width="12%">批号id</th><th width="25%">器械名称</th><th width="12%">批号</th><th width="12%">可报废数量</th><th width="13%">有效期</th><th width="10%">价格</th></tr>
                                    <?php if($data): if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): if( count($data)==0 ) : echo "" ;else: foreach($data as $key=>$v): ?>
                                    <tr class="tdcenter" onmouseover="$(this).css('background-color','#18bc9c')" onmouseout="$(this).css('background-color','')" style="word-wrap:break-word"   data-index="<?php echo $v['alot_id']; ?>")"><td align="center"><?php echo $v['alot_id']; ?></td><td align="center"><?php echo $v['a_name']; ?></td><td align="center"><?php echo $v['alotnum']; ?></td><td align="center"><?php echo $v['alusable_num']; ?></td><td align="center"><?php if($v['aletime']>0): ?><?php echo date('Y-m-d',$v['aletime']); endif; ?></td><td align="center"><?php echo $v['alcost']; ?></td></tr>
                                    <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                                </table>
                            </div>
                        </div>
                        <a href="javascript:;" id="pro_add" class="btn btn-success btn-add hidden"><i class="fa fa-plus"></i></a>
                    </div>
                </div>
            <div class="col-sm-3 col-xs-3" style="">           
                <a href="javascript:;" class="btn btn-danger" id="clear">清除</a>
            </div>
        </div>
    </div>
</div>


    <div class="form-group" style="width: 100%;overflow-x: scroll;">
        <div class="col-xs-12 col-sm-12">
        <table id="selectedDrugs" class="table table-striped table-bordered table-hover alldata" style="width: 770px;text-align: center;">
            <tr>
            <td style="width: 50px;">批号id</td>
            <td style="color: red;width: 100px;">名称</td>
            <td style="color: red;width: 60px;">批号</td>
            <td style="width: 50px;">报废数量</td>
            <td style="color: red;width: 50px;">可报废数量</td>
            <td style="color: red;width: 60px;">有效期</td>
            <td style="width: 40px;">价格</td>
            <td style="width: 30px;">操作</td></tr>
        </table>
        </div>
    </div>
    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="button" class="btn btn-success btn-embossed disabled" id="sureScrap">确定报废</button>
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