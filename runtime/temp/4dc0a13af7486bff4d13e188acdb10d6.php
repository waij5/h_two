<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:72:"D:\wamp\www\h_two\public/../application/admin\view\cash\order\index.html";i:1559804940;s:70:"D:\wamp\www\h_two\public/../application/admin\view\layout\default.html";i:1544673823;s:67:"D:\wamp\www\h_two\public/../application/admin\view\common\meta.html";i:1544673823;s:69:"D:\wamp\www\h_two\public/../application/admin\view\common\script.html";i:1544673823;}*/ ?>
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

                    <div class="commonsearch-table hidden">
                        <form class="form-inline form-commonsearch nice-validator n-default" action="" novalidate="novalidate" id="f-commonsearch" role="form" method="post">
                            <fieldset>
                                <input type="hidden" name="sort" value="item_id" />
                                <div class="form-group dislocationAll">
                                    <label for="order_items.item_id" class="control-label labelLocation"><?php echo __('Order_id'); ?></label>
                                    <input type="number" name="order_items.item_id" class="form-control" />
                                </div>
                                <?php if(is_null($orderStatus)): ?>
                                <div class="form-group">
                                    <label for="order_items.item_status" class="control-label labelLocation"><?php echo __('Order_status'); ?></label>
                                </div>
                                 <div class="form-group dislocationAll">
                                    <select class="form-control" name="order_items.item_status">
                                        <?php if(is_array($orderStatusList) || $orderStatusList instanceof \think\Collection || $orderStatusList instanceof \think\Paginator): if( count($orderStatusList)==0 ) : echo "" ;else: foreach($orderStatusList as $key=>$value): ?>
                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                        <?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                </div>
                                <?php else: ?>
                                <input type="hidden" name="order_items.item_status" value="<?php echo $orderStatus; ?>" />
                                <?php endif; if(is_null($orderType)): ?>
                                <div class="form-group">
                                    <label for="order_items.item_type" class="control-label labelLocation"><?php echo __('Order_type'); ?></label>
                                </div>
                                 <div class="form-group dislocationAll">
                                    <select class="form-control" name="order_items.item_type">
                                        <option value=""><?php echo __('All'); ?></option>
                                        <?php foreach($orderTypeList as $orderType => $orderTypeTitle): ?>
                                        <option value="<?php echo $orderType; ?>"><?php echo __('order_type_' . $orderType); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <?php else: ?>
                                <input type="hidden" name="order_items.item_type" value="<?php echo $orderType; ?>" />
                                <?php endif; ?>
                                <!-- <div class="form-group dislocationAll">
                                    <label for="customer_id" class="control-label labelLocation"><?php echo __('Customer_id'); ?></label>
                                    <input type="hidden" name="customer_id" class="form-control" id="field_ctm_id" />
                                    <a href="javascript:;" id="a-search-customer">
                                        <input type="text" readonly id="field_ctm_name" class="form-control" />
                                    </a>
                                    <a href="javascript:;" class="btn btn-danger btn-del" id="btn-customer-clear">
                                        <i class="fa fa-trash"></i>清除
                                    </a>    
                                </div> -->
                                <div class="form-group" style="margin:5px">
                                    <label for="customer.ctm_id" class="control-label searchPadding"><?php echo __('Ctm_id'); ?></label>
                                    <input type="text" class="form-control" name="customer.ctm_id" value="" placeholder="<?php echo __('Ctm_id'); ?>" id="customer.ctm_id">
                                </div>
                                <div class="form-group" style="margin:5px">
                                    <label for="customer.ctm_name" class="control-label searchPadding"><?php echo __('Ctm_name'); ?></label>
                                    <input type="text" class="form-control" name="customer.ctm_name" value="" placeholder="<?php echo __('Ctm_name'); ?>" id="customer.ctm_name">
                                </div>
                                <div class="form-group" style="margin:5px">
                                    <label for="customer.ctm_mobile" class="control-label searchPadding"><?php echo __('Ctm_mobile'); ?></label>
                                    <input type="text" class="form-control" name="customer.ctm_mobile" value="" placeholder="<?php echo __('Ctm_mobile'); ?>" id="customer.ctm_mobile">
                                </div>
                                <!-- 操作人          -->
                                <div class="form-group"  style="margin:5px">
                                     <label for="Develop_admin" class="control-label searchPadding"><?php echo __('admin_id'); ?></label>
                                    <div class="input-group">
                                        <input id="c_nickname" class="form-control hidden" type="text" value="" name="order_items.admin_id">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12" onmouseleave="$(this).find('.word').addClass('hidden');">
                                                <input type="text" id="project_search" onmouseenter="$(this).siblings().find('.word').removeClass('hidden')" autocomplete="off" value="" style="position: relative;" class="nickname form-control" />
                                                <div style="position: relative;" >
                                                    <ul id="word" data-index="" style="list-style:none;position: absolute;display: none;cursor: pointer;z-index: 999;height: auto;text-align: left;margin-top: 31px;" class="form-control word"></ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- 营销人员 -->
                                <div class="form-group"  style="margin:5px">
                                     <label for="Develop_admin" class="control-label searchPadding"><?php echo __('consult_admin_id'); ?></label>
                                    <div class="input-group">
                                        <input id="c_cnickname" class="form-control hidden" type="text" value="" name="order_items.consult_admin_id">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12" onmouseleave="$(this).find('.cword').addClass('hidden');">
                                                <input type="text" id="project_search" onmouseenter="$(this).siblings().find('.cword').removeClass('hidden')" autocomplete="off" value="" style="position: relative;" class="cnickname form-control" />
                                                <div style="position: relative;" >
                                                    <ul id="cword" data-index="" style="list-style:none;position: absolute;display: none;cursor: pointer;z-index: 999;height: auto;text-align: left;margin-top: 31px;" class="form-control cword"></ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group dislocationAll" style="position: relative;">
                                    <label for="createtime" class="control-label labelLocation"><?php echo __('Createtime'); ?></label>
                                    <input class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" name="order_items.item_createtime_start" type="text" value="" id="order_items.item_createtime_start"> ~ <input class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" name="order_items.item_createtime_end" type="text" value="" id="order_items.item_createtime_end">
                                </div>
                                <br />
                                <!-- 暂时隐藏 -->
                                <div class="form-group hidden dislocationAll" style="position: relative;">
                                    <label for="updatetime" class="control-label labelLocation"><?php echo __('Updatetime'); ?></label>
                                    <input class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" name="updatetime_start" type="text" value="" id="updatetime_start"> ~ <input class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" name="updatetime_end" type="text" value="" id="updatetime_end">
                                </div>
                                <br />
                                <div class="form-group dislocationAll">
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-success dislocationRight">提交</button>
                                        <button type="reset" class="btn btn-default">重置</button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>

                    <div id="toolbar" class="toolbar">
                        
                        <a href="javascript:;" class="btn btn-primary btn-refresh"><i class="fa fa-refresh"></i></a>
                        <a href="javascript:;" class="btn btn-success btn-batch-pay" title="只能同时收同一顾客的款项"><i class="fa fa-dollar">收款</i></a>
                        <?php echo $newOrderBtn; ?>
                        <!--
                        <a class="btn btn-success" id="btn-new-order">
                            <i class="fa fa-plus"></i> <?php echo __('New order'); ?>
                        </a>
                        -->
                    </div>
                    <table id="table" class="table table-striped table-bordered table-hover" 
                           data-operate-payorder="<?php echo $auth->check('cash/balance/payorder'); ?>" 
                           data-operate-cancelorder="<?php echo $auth->check('cash/order/cancelorder'); ?>" 
                           data-operate-deduct="<?php echo $auth->check('cash/order/deduct'); ?>" 
                           data-operate-switchitem="<?php echo $auth->check('cash/order/switchitem'); ?>" 
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