<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:76:"D:\wamp\www\h_two\public/../application/admin\view\wm\drugscflist\index.html";i:1555659300;s:70:"D:\wamp\www\h_two\public/../application/admin\view\layout\default.html";i:1544673823;s:67:"D:\wamp\www\h_two\public/../application/admin\view\common\meta.html";i:1544673823;s:69:"D:\wamp\www\h_two\public/../application/admin\view\common\script.html";i:1544673823;}*/ ?>
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
    /*style="width: 40%;float: left;"*/
</style>
<div class="panel panel-default panel-intro">
    <?php echo build_heading(); ?>

    <div class="panel-body">
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade active in" id="one">
                <div class="widget-body no-padding" >

                    <div class="commonsearch-table ">
                        <form class="form-inline form-commonsearch nice-validator n-default" action="" novalidate="novalidate" id="f-commonsearch" role="form" method="post">
                            <fieldset>
                            

                               <div class="form-group dislocationAll">
                                    <label for="customer_id" class="control-label labelLocation">顾客</label>
                                    
                                    <a href="javascript:;" id="a-search-customer">
                                        <input type="text" style="display: none" name="customer_id" class="form-control" id="field_ctm_id" />
                                        <input type="text" readonly id="field_ctm_name" class="form-control" />
                                    </a>
                                    <a href="javascript:;" class="btn btn-danger btn-del" id="btn-customer-clear">
                                        <i class="fa fa-trash"></i>清除
                                    </a>
                                </div>
                                <div class="form-group dislocationAll">
                                    <label for="ctm_id" class="control-label labelLocation">客户卡号</label>
                                    <input type="text" name="c.ctm_id" class="form-control" size="8">
                                </div>
                                
                                <div class="form-group dislocationAll">
                                    <label for="depot_id" class="control-label labelLocation">仓库</label>
                                    <select id="depot_id" class="form-control " name="pro.depot_id">
                                        <option  value='' >--- 请选择 ---</option>
                                        <?php if(is_array($depotList) || $depotList instanceof \think\Collection || $depotList instanceof \think\Paginator): if( count($depotList)==0 ) : echo "" ;else: foreach($depotList as $key=>$vo): ?>
                                        <option value="<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></option>
                                        <?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                </div>

                                <div class="form-group dislocationAll">
                                    <label for="dept_id" class="control-label labelLocation">开单科室</label>
                                    <select id="dept_id" class="selectpicker show-tick form-control " name="a.dept_id"data-live-search="true">
                                        <option value="">---请选择---</option>
                                        <?php if(is_array($deptmentList) || $deptmentList instanceof \think\Collection || $deptmentList instanceof \think\Paginator): if( count($deptmentList)==0 ) : echo "" ;else: foreach($deptmentList as $key=>$vo): ?>
                                        <option  value="<?php echo $vo['dept_id']; ?>" ><?php echo $vo['dept_name']; ?></option>
                                        <?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                </div>

                                
                                <div class="form-group marginZero" style="margin: 0;">
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-success dislocationRight" id="surePost">提交</button>
                                        <button type="reset" class="btn btn-default">重置</button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>

                    <div id="toolbar" class="toolbar">
                        <?php echo build_toolbar('refresh'); ?>
                        <a href="javascript:;" class="btn btn-primary" id="isCusPrint">打印ALL</a>
                        
                    </div>
                    <table id="table" class="table table-striped table-bordered table-hover" 
                           data-operate-edit="" 
                           data-operate-del="" 
                           >
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
        
<script src="__CDN__/assets/js/require.js" data-main="__CDN__/assets/js/require-backend.js?v=<?php echo $site['version']; ?>"></script>
    </body>
</html>