<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:85:"D:\wamp\www\h_two\public/../application/admin\view\stat\customerorderitems\index.html";i:1559804939;s:70:"D:\wamp\www\h_two\public/../application/admin\view\layout\default.html";i:1544673823;s:67:"D:\wamp\www\h_two\public/../application/admin\view\common\meta.html";i:1544673823;s:69:"D:\wamp\www\h_two\public/../application/admin\view\common\script.html";i:1544673823;}*/ ?>
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
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade active in" id="one">
                <div class="widget-body no-padding">
                    <div class="commonsearch-table">
                        <form action="" class="form-inline form-commonsearch nice-validator n-default" id="f-commonsearch" method="post" novalidate="novalidate" role="form">
                            <fieldset>
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="customer.ctm_id">
                                        <?php echo __('Ctm_id'); ?>
                                    </label>
                                    <input class="form-control" id="customer.ctm_id" name="customer.ctm_id" placeholder="<?php echo __('Ctm_id'); ?>" type="text" value="">
                                    </input>
                                </div>
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="customer.ctm_name">
                                        <?php echo __('Ctm_name'); ?>
                                    </label>
                                    <input class="form-control" id="customer.ctm_name" name="customer.ctm_name" placeholder="<?php echo __('Ctm_name'); ?>" type="text" value="">
                                    </input>
                                </div>
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="customer.ctm_mobile">
                                        <?php echo __('Ctm_mobile'); ?>
                                    </label>
                                    <input class="form-control" id="customer.ctm_mobile" name="customer.ctm_mobile" placeholder="<?php echo __('Ctm_mobile'); ?>" type="text" value="">
                                    </input>
                                </div>

                                <!-- 营销人员 -->
                                <div class="form-group dislocationAll">
                                    <label for="customer.admin_id" class="control-label searchPadding"><?php echo __('developStaff'); ?></label>
                                    <select class="selectpicker show-tick form-control" name="customer.admin_id" data-live-search="true">
                                        <option value=""><?php echo __('None'); ?></option>
                                        <option value="0">自然到诊</option>
                                        <?php foreach($briefAdminList as $key => $value): ?>
                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group dislocationAll">
                                    <label class="control-label labelLocation" for="createtime">
                                        <?php echo __('Item pay time'); ?>
                                    </label>
                                    <input class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" name="order_items.item_paytime_start" type="text" value="<?php echo $startDate; ?>">
                                        ~
                                        <input class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" name="order_items.item_paytime_end" type="text" value="<?php echo $endDate; ?>">
                                        </input>
                                    </input>
                                </div>
                                <div class="form-group dislocationAll">
                                    <div class="col-sm-12 text-center">
                                        <button class="btn btn-success dislocationRight" type="submit">
                                            <?php echo __('Submit'); ?>
                                        </button>
                                        <button class="btn btn-default" type="reset">
                                            <?php echo __('Reset'); ?>
                                        </button>
                                        <button class="btn btn-default" id="btn-export" type="button">
                                            <?php echo __('Export'); ?>
                                        </button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div class="form-group">
                        <h2 class="text-center">
                            <?php echo __('Customer booked item summary'); ?>
                        </h2>
                    </div>
                    <div class="">
                        <h3 class="h-summary-block">
                            <small>
                                <span>
                                    顾客数:
                                </span>
                                <span class="text-success" id="s-count">
                                    0
                                </span>
                                <span title="含退换产生的订单" data-toggle="tooltip" data-placement="bottom">
                                    订购项目数:
                                </span>
                                <span class="text-success" id="s-item_count">
                                    0
                                </span>
                                <span title="原始支付额(含变动)" data-toggle="tooltip" data-placement="bottom"><?php echo __('item_original_pay_total'); ?><i class="fa fa-question-circle-o"></i></span>
                                <span class="text-success" id="s-item_original_pay_total">
                                    0
                                </span>
                                <span title="本期变动额" data-toggle="tooltip" data-placement="bottom">本期变动额<i class="fa fa-question-circle-o"></i></span>
                                <span class="text-success" id="s-item_switch_total">
                                    0
                                </span>
                                <br />
                                <span title="现实际支付额(与原始支付额不一定相同，因为可能在此段时间后另有退换项目)" data-toggle="tooltip" data-placement="bottom"><?php echo __('item_pay_total'); ?><i class="fa fa-question-circle-o"></i></span>
                                <span class="text-success" id="s-item_total">
                                    0
                                </span>
                                <span>
                                    未划扣额:
                                </span>
                                <span class="text-success" id="s-undeducted_total">
                                    0
                                </span>
                                <span title="使用券额(不包含在支付额内的部分)" data-toggle="tooltip" data-placement="bottom"><?php echo __('item_coupon_total'); ?><i class="fa fa-question-circle-o"></i></span>
                                <span class="text-success" id="s-item_coupon_total">
                                    0
                                </span>
                            </small>
                        </h3>
                    </div>
                    <div class="clearfix">
                    </div>
                    <table class="table table-bordered table-hover" id="table" width="100%">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    yjyBriefAdminList = <?php echo json_encode($briefAdminList); ?>;
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