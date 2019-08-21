<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:76:"D:\wamp\www\h_two\public/../application/admin\view\deduct\records\index.html";i:1559804939;s:70:"D:\wamp\www\h_two\public/../application/admin\view\layout\default.html";i:1544673823;s:67:"D:\wamp\www\h_two\public/../application/admin\view\common\meta.html";i:1544673823;s:69:"D:\wamp\www\h_two\public/../application/admin\view\common\script.html";i:1544673823;}*/ ?>
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
                                <script type="text/javascript">
    var yjyDeductRoleSets = [];
    // let currentRoleInfo = [];
    roleSets = <?php echo json_encode($roleSets); ?>;
    
    <?php foreach($roleSets as $roleSet): ?>
    // var currentRoleInfo = <?php echo json_encode($roleSet); ?>;
    yjyDeductRoleSets.push({
                            field: 'id',
                            title: '<?php echo $roleSet['name']; ?>',
                            formatter: function(value, row, index) {
                                let operateHtml = '';
                                var currentRoleInfo = <?php echo json_encode($roleSet); ?>;
                                if (row['staff_records']) {
                                    if (row['staff_records'][currentRoleInfo.id]) {
                                        let roleInfo = row['staff_records'][currentRoleInfo.id];
                                        for (var i in roleInfo['role_staffs']) {
                                            let staffInfo = roleInfo['role_staffs'][i];
                                            // (' + staffInfo['final_amount'] + ') 
                                            operateHtml += '[' + staffInfo['admin_name'] + ' ]<br />';
                                        }
                                    }
                                }
                                return operateHtml;
                            }
                        });
    <?php endforeach; ?>
</script>

<div class="panel panel-default panel-intro">
    <?php echo build_heading(); ?>
    <div class="panel-body">
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade active in" id="one">
                <div class="widget-body no-padding">
                    <div class="commonsearch-table hidden">
                        <form action="" class="form-inline form-commonsearch nice-validator n-default" id="f-commonsearch" method="post" novalidate="novalidate" role="form">
                            <fieldset>
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="customer_id">
                                        <?php echo __('customer_id'); ?>
                                    </label>
                                    <input class="form-control" id="customer_id" name="order_items.customer_id" placeholder="ID" type="text" value="">
                                    </input>
                                </div>
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="old_ctm_code">
                                        宏迈卡号
                                    </label>
                                    <input class="form-control" id="old_ctm_code" name="customer.old_ctm_code" placeholder="宏迈卡号" type="text" value="">
                                    </input>
                                </div>
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="customer.ctm_name">
                                        <?php echo __('Ctm_name'); ?>
                                    </label>
                                    <input class="form-control" id="customer.ctm_name" name="customer.ctm_name" placeholder="<?php echo __('Ctm_name'); ?>" type="text" value="">
                                    </input>
                                </div>
                                <!-- 营销渠道 -->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="customer.ctm_explore">
                                        <?php echo __('Ctm_explore'); ?>
                                    </label>
                                    <select class="selectpicker show-tick form-control" data-live-search="true" id="customer.ctm_explore" name="customer.ctm_explore" required="">
                                        <?php foreach($channelList as $key => $pdc): ?>
                                        <option value="<?php echo $key; ?>">
                                            <?php echo $pdc; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- 客户来源 -->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="customer.ctm_source">
                                        <?php echo __('Ctm_source'); ?>
                                    </label>
                                    <select class="selectpicker show-tick form-control" data-live-search="true" id="customer.ctm_source" name="customer.ctm_source" required="">
                                        <?php foreach($ctmSrcList as $key => $pdc): ?>
                                        <option value="<?php echo $key; ?>">
                                            <?php echo $pdc; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- 项目 -->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="order_items.pro_name">
                                        <?php echo __('pro_name'); ?>
                                    </label>
                                    <input class="form-control" id="order_items.pro_name" name="order_items.pro_name" placeholder="<?php echo __('pro_name'); ?>" type="text" value="">
                                    </input>
                                </div>
                                <!-- 规格 -->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="order_items.pro_spec">
                                        <?php echo __('pro_spec'); ?>
                                    </label>
                                    <input class="form-control" id="order_items.pro_spec" name="order_items.pro_spec" placeholder="<?php echo __('pro_spec'); ?>" type="text" value="">
                                    </input>
                                </div>
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="deduct_amount">
                                        <?php echo __('deduct_amount'); ?>
                                    </label>
                                    <input class="form-control" id="deduct_amount_start" name="deduct_amount_start" type="text" value="">
                                        ~
                                        <input class="form-control" id="deduct_amount_end" name="deduct_amount_end" type="text" value="">
                                        </input>
                                    </input>
                                </div>
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="deduct_benefit_amount">
                                        <?php echo __('deduct_benefit_amount'); ?>
                                    </label>
                                    <input class="form-control" id="deduct_benefit_amount_start" name="deduct_benefit_amount_start" type="text" value="">
                                        ~
                                        <input class="form-control" id="deduct_benefit_amount_end" name="deduct_benefit_amount_end" type="text" value="">
                                        </input>
                                    </input>
                                </div>
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding">
                                        <?php echo __('Osconsult staff'); ?>
                                    </label>
                                    <select class="form-control selectpicker show-tick" data-live-search="true" name="order_items.admin_id">
                                        <option value="">
                                            <?php echo __('None'); ?>
                                        </option>
                                        <?php foreach($briefAdminList as $key => $value): ?>
                                        <option value="<?php echo $key; ?>">
                                            <?php echo $value; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="Admin_nickname">
                                        <?php echo __('Admin_nickname'); ?>
                                    </label>
                                    <select class="form-control selectpicker show-tick" data-live-search="true" name="deduct_records.admin_id">
                                        <option value="">
                                            --
                                        </option>
                                        <?php foreach($briefAdminList as $key => $briefAdmin): ?>
                                        <option value="<?php echo $key; ?>">
                                            <?php echo $briefAdmin; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <!-- <div class="input-group">
                                        <input id="c_nickname" class="form-control hidden" type="text" value="" name="deduct_records.admin_id">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12" onmouseleave="$(this).find('.word').addClass('hidden');">
                                                <input type="text" id="project_search" onmouseenter="$(this).siblings().find('.word').removeClass('hidden')" autocomplete="off" value="" style="position: relative;" class="nickname form-control" />
                                                <div style="position: relative;" >
                                                    <ul id="word" data-index="" style="list-style:none;position: absolute;display: none;cursor: pointer;z-index: 999;height: auto;text-align: left;margin-top: 31px;" class="form-control word"></ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                                </div>
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="status">
                                        <?php echo __('status'); ?>
                                    </label>
                                    <select class="form-control" id="status" name="deduct_records.status" required="">
                                        <option value="">
                                            --
                                        </option>
                                        <?php foreach($statusList as $key => $oscStatus): ?>
                                        <option value="<?php echo $key; ?>">
                                            <?php echo $oscStatus; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- 结算科室 -->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="dept_id">
                                        <?php echo __('dept_id'); ?>
                                    </label>
                                     <!-- selectpicker  data-live-search="true" -->
                                    <select class="form-control show-tick" id="dept_id" name="order_items.dept_id" required="">
                                        <option value="">所有</option>
                                        <?php foreach($deptdata as $key => $val): if($val['dept_type'] == 'deduct'): ?>
                                        <option value="<?php echo $val['dept_id']; ?>">
                                            <?php echo $val['name']; ?>
                                        </option>
                                        <?php endif; endforeach; ?>
                                        <option value="0">
                                            无科室
                                        </option>
                                    </select>
                                </div>
                                <!-- 营销部门 -->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="admin.dept_id">
                                        <?php echo __('admin_dept_id'); ?>
                                    </label>
                                    <select class="form-control selectpicker show-tick" data-live-search="true" id="admin.dept_id" name="admin.dept_id" required="">
                                        <option value="">
                                        </option>
                                        <?php foreach($deptdata as $key => $val): ?>
                                        <option value="<?php echo $val['dept_id']; ?>">
                                            <?php echo $val['name']; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- 现场部门 -->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="coc.dept_id">
                                        <?php echo __('coc_dept_id'); ?>
                                    </label>
                                    <select class="form-control selectpicker show-tick" data-live-search="true" id="coc.dept_id" name="coc.dept_id" required="">
                                        <option value="">
                                        </option>
                                        <?php foreach($deptdata as $key => $val): ?>
                                        <option value="<?php echo $val['dept_id']; ?>">
                                            <?php echo $val['name']; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- 划扣次数 -->
                                <!-- <div class="form-group" style="margin:5px">
                                        <label for="deduct_records.deduct_times" class="control-label searchPadding"><?php echo __('Deduct_times'); ?></label>
                                        <input type="text" class="form-control" name="deduct_records.deduct_times_start" value="">
                                        ~<input type="text" class="form-control" name="deduct_records.deduct_times_end" value="">
                                    </div> -->
                                <!-- 划扣次数 -->
                                <div class="form-group" style="margin:5px">
                                    <label class="control-label searchPadding" for="order_items.item_used_times">
                                        总划扣次数
                                    </label>
                                    <input class="form-control" name="order_items.item_used_times_start" type="text" value="">
                                        ~
                                        <input class="form-control" name="order_items.item_used_times_end" type="text" value="">
                                        </input>
                                    </input>
                                </div>
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="order_items.item_paytime">
                                        <?php echo __('item_paytime'); ?>
                                    </label>
                                    <input class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" name="order_items.item_paytime_start" type="text" value="">
                                        ~
                                        <input class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" name="order_items.item_paytime_end" type="text" value="">
                                        </input>
                                    </input>
                                </div>
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="updatetime">
                                        <?php echo __('Deduct time'); ?>
                                    </label>
                                    <input class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" name="deduct_records.createtime_start" type="text" value="">
                                        ~
                                        <input class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" name="deduct_records.createtime_end" type="text" value="">
                                        </input>
                                    </input>
                                </div>
                                <!--首次受理工具-->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding">
                                        <?php echo __('ctm_first_tool'); ?>
                                    </label>
                                    <select class="selectpicker show-tick form-control" data-live-search="true" name="customer.ctm_first_tool_id">
                                        <?php foreach($toolList as $key => $value): ?>
                                        <option value="<?php echo $key; ?>">
                                            <?php echo $value; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group dislocationAll">
                                        <label for="osc_type" class="control-label searchPadding"><?php echo __('Osc_type'); ?></label>
                                        <select class="form-control" required="" name="coc.osc_type" id="osc_type">
                                            <option value=""><?php echo __('All'); ?></option>
                                            <?php foreach($ocsTypeArr as $key => $oscType): ?>
                                            <option value="<?php echo $key; ?>"><?php echo $oscType; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                <div class="form-group dislocationAll">
                                    <div class="col-sm-12 text-center">
                                        <button class="btn btn-success dislocationRight" type="submit">
                                            <?php echo __('Submit'); ?>
                                        </button>
                                        <button class="btn btn-default" type="reset">
                                            <?php echo __('Reset'); ?>
                                        </button>
                                        <?php if(($auth->check('deduct/records/downloadprocess'))): ?>
                                        <button class="btn btn-default" id="btn-export" type="button">
                                            <?php echo __('Export'); ?>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <input id="h-order-item-id" type="hidden" value="<?php echo $orderItemId; ?>">
                        <div class="toolbar" id="toolbar">
                            
                            <a class="btn btn-primary btn-refresh" href="javascript:;">
                                <i class="fa fa-refresh">
                                </i>
                            </a>
                            <a class="btn btn-success btn-batchreverse" href="javascript:;">
                                <i class="fa fa-editone">
                                    <?php echo __('batch reverse deduct'); ?>
                                </i>
                            </a>
                            <div class="clearfix">
                            </div>
                            <div id="summary_area" style="padding-top: 10px">
                                <!-- 划扣次数 -->
                                <label class="control-label">
                                    <?php echo __('Deduct_times'); ?>
                                </label>
                                <span class="text-warning" id="sum_ded_times">
                                </span>
                                <label class="control-label">
                                    <?php echo __('Deduct_total'); ?>
                                </label>
                                <span class="text-warning" id="sum_ded_total">
                                </span>
                                <label class="control-label">
                                    <?php echo __('Deduct_benefit_total'); ?>
                                </label>
                                <span class="text-warning" id="sum_ded_benefit_total">
                                </span>
                            </div>
                        </div>
                        <table class="table table-striped table-bordered table-hover" data-operate-del="<?php echo $auth->check('deduct/records/del'); ?>" data-operate-edit="<?php echo $auth->check('deduct/records/edit'); ?>" id="table" width="100%">
                        </table>
                    </input>
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