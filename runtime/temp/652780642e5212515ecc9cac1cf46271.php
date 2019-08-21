<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:70:"D:\wamp\www\h_two\public/../application/admin\view\wm\goods\index.html";i:1565084726;s:70:"D:\wamp\www\h_two\public/../application/admin\view\layout\default.html";i:1544673823;s:67:"D:\wamp\www\h_two\public/../application/admin\view\common\meta.html";i:1544673823;s:69:"D:\wamp\www\h_two\public/../application/admin\view\common\script.html";i:1544673823;}*/ ?>
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
<div class="panel panel-default panel-intro">
    <?php echo build_heading(); ?>

    <div class="panel-body">
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade active in" id="one">
                <div class="widget-body no-padding">

                    <div class="commonsearch-table hidden">
                        <form class="form-inline form-commonsearch nice-validator n-default" action="" novalidate="novalidate" id="f-commonsearch" role="form" method="post">
                            <fieldset>
                            
                                <div class="form-group dislocationAll">
                                    <label for="id" class="control-label labelLocation">ID</label>
                                    <input type="text" name="pro_id" class="form-control" size="8" />
                                </div>

                                <div class="form-group dislocationAll">
                                    <label for="num" class="control-label labelLocation"><?php echo __('Pro_code'); ?></label>
                                    <input type="text" name="pro_code" class="form-control" size="8" />
                                </div>
                                <div class="form-group dislocationAll">
                                    <label for="name" class="control-label labelLocation"><?php echo __('Pro_name'); ?></label>
                                    <input type="text" name="pro_name" class="form-control" size="8">
                                </div>
                                <div class="form-group" style="margin:5px">
                                    <label for="code" class="control-label" style="padding:0 10px"><?php echo __('Pro_spell'); ?></label>
                                    <input type="text" name="pro_spell" class="form-control"  size="8">
                                </div>
                                <div class="form-group dislocationAll">
                                    <label for="depot_id" class="control-label labelLocation"><?php echo __('Depot_id'); ?></label>
                                    <select id="depot_id" class="form-control " name="depot_id">
                                        <option data-type="" value='' >--- 请选择 ---</option>
                                        <?php if(is_array($depotList) || $depotList instanceof \think\Collection || $depotList instanceof \think\Paginator): if( count($depotList)==0 ) : echo "" ;else: foreach($depotList as $key=>$vo): ?>
                                        <option data-type="<?php echo $vo['name']; ?>" value="<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></option>
                                        <?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                </div>
                                <div class="form-group dislocationAll">
                                    <label for="c-pro_cat1" class="control-label labelLocation"><?php echo __('Subject_name'); ?>:</label>
                                        <select id="c-pro_cat1" class="form-control " name="pro_cat1">
                                            <option value="">--请选择--</option>
                                            <?php if(is_array($subject) || $subject instanceof \think\Collection || $subject instanceof \think\Paginator): if( count($subject)==0 ) : echo "" ;else: foreach($subject as $key=>$v): ?>
                                            <option  value="<?php echo $v['pdc_id']; ?>"><?php echo $v['pdc_name']; ?></option>
                                            <?php endforeach; endif; else: echo "" ;endif; ?>
                                        </select>
                                </div>
                                <div class="form-group dislocationAll">
                                    <label for="c-pro_cat2" class="control-label labelLocation"><?php echo __('Pro_cat'); ?>:</label>
                                        <select id="c-pro_cat2" class="form-control " name="pro_cat2">
                                            <option value="">--请选择--</option>
                                        </select>
                                </div>

                                <div class="form-group dislocationAll">
                                    <label for="time" class="control-label labelLocation">创建时间</label>
                                    <input type="text" name="stime"  value="<?php echo \think\Request::instance()->param('stime'); ?>" class="form-control datetimepicker clear" data-use-current="true" data-date-format="YYYY-MM-DD" size="8">  ~  
                                    <input type="text" name="etime"   value="<?php echo \think\Request::instance()->param('etime'); ?>"  class="form-control datetimepicker clear" data-use-current="true" data-date-format="YYYY-MM-DD"  size="8">    
                                </div>
                                
                                <!-- <br> -->
                                <div class="form-group marginZero" style="margin: 0;">
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-success dislocationRight">提交</button>
                                        <button type="reset" class="btn btn-default">重置</button>
                                        <button type="button" class="btn btn-default" id="btn-export">导出</button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>

                    <div id="toolbar" class="toolbar">
                        <?php echo build_toolbar('refresh,add,edit'); ?>
                        
                    </div>
                    <table id="table" class="table table-striped table-bordered table-hover" 
                           data-operate-edit="<?php echo $auth->check('wm/goods/edit'); ?>" 
                           data-operate-del="<?php echo $auth->check('wm/goods/del'); ?>" 
                           width="100%">
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