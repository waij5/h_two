<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:91:"D:\wamp\www\h_two\public/../application/admin\view\customer\rvinfo\todayrevisitnotices.html";i:1565158000;s:70:"D:\wamp\www\h_two\public/../application/admin\view\layout\default.html";i:1544673823;s:67:"D:\wamp\www\h_two\public/../application/admin\view\common\meta.html";i:1544673823;s:69:"D:\wamp\www\h_two\public/../application/admin\view\common\script.html";i:1544673823;}*/ ?>
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
                    <div class="commonsearch-table">
                        <form class="form-inline form-commonsearch nice-validator n-default" action="" novalidate="novalidate" id="f-commonsearch" role="form" method="post">
                            <fieldset>
                                <!--  <div class="form-group dislocationAll">
                                    <label for="customer_id" class="control-label labelLocation"><?php echo __('Customer_id'); ?></label>
                                    <input type="hidden" name="customer_id" class="form-control" value="" id="field_ctm_id" />
                                    <a href="javascript:;" id="a-search-customer">
                                        <input type="text" readonly id="field_ctm_name" class="form-control" />
                                    </a>
                                    <a href="javascript:;" class="btn btn-danger btn-del" id="btn-customer-clear">
                                        <i class="fa fa-trash"></i>清除
                                    </a>    
                                </div> -->
                                <input type="hidden" name="notOnlyUseToday" value="1" />
                                 <div class="form-group dislocationAll">
                                    <label for="customer.ctm_id" class="control-label searchPadding"><?php echo __('Ctm_id'); ?></label>
                                    <input type="text" class="form-control" name="customer.ctm_id" value="" placeholder="<?php echo __('Ctm_id'); ?>" id="customer.ctm_id">
                                </div>
                                <div class="form-group dislocationAll">
                                    <label for="customer.ctm_name" class="control-label searchPadding"><?php echo __('Ctm_name'); ?></label>
                                    <input type="text" class="form-control" name="customer.ctm_name" value="" placeholder="<?php echo __('Ctm_name'); ?>" id="customer.ctm_name">
                                </div>
                                <div class="form-group dislocationAll">
                                    <label for="customer.ctm_mobile" class="control-label searchPadding"><?php echo __('Ctm_mobile'); ?></label>
                                    <input type="text" class="form-control" name="customer.ctm_mobile" value="" placeholder="<?php echo __('Ctm_mobile'); ?>" id="customer.ctm_mobile">
                                </div>

                                <!-- 首次受理工具 -->
                                    <div class="form-group dislocationAll">
                                        <label for="cst_chntype" class="control-label searchPadding"><?php echo __('customer_ctm_first_tool_id'); ?></label>
                                        <select class="selectpicker show-tick form-control selectSearch-min" required="" name="customer.ctm_first_tool_id" data-live-search="true">
                                            <?php foreach($toolList as $key => $value): ?>
                                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <!-- 客户来源 -->
                                    <div class="form-group dislocationAll">
                                        <label for="customer.ctm_source"><?php echo __('ctm_source'); ?></label>
                                        <select  class="selectpicker show-tick form-control" data-live-search="true" name="customer.ctm_source">
                                            <?php foreach($ctmSrcList as $key => $value): ?>
                                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <!-- 营销渠道 -->
                                    <div class="form-group dislocationAll">
                                        <label for="customer.ctm_explore"><?php echo __('ctm_explore'); ?></label>
                                        <select  class="selectpicker show-tick form-control" data-live-search="true" name="customer.ctm_explore">
                                            <?php foreach($channelList as $key => $cha): ?>
                                            <option value="<?php echo $key; ?>"><?php echo $cha; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>


                                <!-- 是否上门 -->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="customer.arrive_status"><?php echo __('arrive_status'); ?></label>
                                    <select class=" form-control" id="customer.arrive_status" name="customer.arrive_status" required="">
                                        <option value="">
                                        </option>
                                        <?php foreach($ArriveStatus as $key => $pdc): ?>
                                        <option value="<?php echo $key; ?>">
                                            <?php echo $pdc; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- 首次回访项目 -->
                                <div class="form-group dislocationAll">
                                    <label for="customer.ctm_first_cpdt_id" class="control-label searchPadding"><?php echo __('ctm_first_cpdt_id'); ?></label>
                                    <select class="form-control" required="" name="customer.ctm_first_cpdt_id">
                                        <?php foreach($cpdtList as $key => $pdc): ?>
                                            <option value="<?php echo $key; ?>"><?php echo $pdc; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- 职员科室 -->
                                <div class="form-group dislocationAll">
                                    <label for="admin.dept_id" class="control-label searchPadding"><?php echo __('admin_dept_id'); ?></label>
                                    <select class="form-control selectpicker show-tick" required="" name="admin.dept_id" id="admin.dept_id" data-live-search="true">
                                        <option value=""><?php echo __('All'); ?></option>
                                        <?php foreach($deptList as $key => $dept): ?>
                                        <option value="<?php echo $dept['dept_id']; ?>"><?php echo $dept['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- 顾虑点 -->
                                 <div class="form-group dislocationAll">
                                    <label for="rvinfo.fat_id" class="control-label searchPadding"><?php echo __('filter_id'); ?></label>
                                    <select class="form-control" required="" name="rvinfo.fat_id">
                                        <?php foreach($fatList as $key => $dept): ?>
                                            <option value="<?php echo $key; ?>"><?php echo $dept; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- 定金余额 -->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="customer.ctm_depositamt">
                                        定金余额
                                    </label>
                                    <input class="form-control" name="customer.ctm_depositamt_start" type="text" value="">
                                        ~
                                        <input class="form-control" name="customer.ctm_depositamt_end" type="text" value="">
                                        </input>
                                    </input>
                                </div>
                                 <!-- 积分 -->
                                <div class="form-group dislocationAll">
                                    <label for="customer.ctm_rank_points" class="control-label searchPadding"><?php echo __('ctm_rank_points'); ?></label>
                                    <input class="form-control "  name="customer.ctm_rank_points_start" type="text" value="" id="customer.ctm_rank_points_start"> ~ <input  class="form-control "  name="customer.ctm_rank_points_end" type="text" value="" id="customer.ctm_rank_points_end">
                                </div>
                                <div class="form-group dislocationAll">
                                    <label for="customer.ctm_pay_points" class="control-label searchPadding"><?php echo __('ctm_pay_points'); ?></label>
                                    <input class="form-control " name="customer.ctm_pay_points_start" type="text" value="" id="customer.ctm_pay_points_start"> ~ <input  class="form-control " name="customer.ctm_pay_points_end" type="text" value="" id="customer.ctm_pay_points_end">
                                </div>
                                <!-- 实际总金额 -->
                                <div class="form-group" style="margin:5px">
                                    <label for="customer.ctm_salamt" class="control-label searchPadding"><?php echo __('ctm_salamt'); ?></label>
                                    <input type="text" class="form-control" name="customer.ctm_salamt_start" value=""  id="customer.ctm_salamt_start">~<input type="text" class="form-control" name="customer.ctm_salamt_end" value=""  id="customer.ctm_salamt_end">
                                </div>
                                <div class="form-group dislocationAll">
                                    <label for="rvinfo.rv_date" class="control-label searchPadding"><?php echo __('rv_date'); ?></label>
                                    <input class="form-control datetimepicker forbid-timestamp" data-date-format="YYYY-MM-DD" data-use-current="true" name="rvinfo.rv_date_start" type="text" value="<?php echo $startDate; ?>" id="rvinfo.rv_date_start"> ~ <input  class="form-control datetimepicker forbid-timestamp" data-date-format="YYYY-MM-DD" data-use-current="true" name="rvinfo.rv_date_end" type="text" value="<?php echo $endDate; ?>" id="rvinfo.rv_date_end">
                                </div>
                                 <!-- 实际回访时间 -->
                              <div class="form-group dislocationAll">
                                    <label for="rvinfo.rv_time" class="control-label searchPadding"><?php echo __('rv_time'); ?></label>
                                    <input class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" name="rvinfo.rv_time_start" type="text" value="" id="rvinfo.rv_time_start"> ~ <input  class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" name="rvinfo.rv_time_end" type="text" value="" id="rvinfo.rv_time_end">
                                </div>
                                <!-- 回访人 -->
                                <div class="form-group dislocationAll">
                                    <label for="rvinfo.admin_id" class="control-label searchPadding"><?php echo __('Rv_admin_id'); ?></label>
                                    <select class="form-control selectpicker show-tick" name="rvinfo.admin_id" data-live-search="true">
                                        <option value=""><?php echo __('None'); ?></option>
                                        <?php foreach($briefAdminList as $key => $value): ?>
                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- 回访类型 -->
                                <div class="form-group dislocationAll">
                                    <label for="rvinfo.rvt_type" class="control-label searchPadding">回访类型:</label>
                                    <select class="form-control selectpicker show-tick" name="rvinfo.rvt_type" data-live-search="true">
                                        <option value=""><?php echo __('None'); ?></option>
                                        <?php foreach($typeList as $key => $value): ?>
                                        <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>


                                <div class="form-group dislocationAll">
                                    <label for="rvinfo.rvi_content" class="control-label searchPadding"><?php echo __('rvi_content'); ?></label>
                                    <input type="text" class="form-control" name="rvinfo.rvi_content" value="" placeholder="<?php echo __('rvi_content'); ?>" id="rvinfo.rvi_content">
                                </div>
                                <div class="form-group dislocationAll">
                                    <div class="btn-group" data-toggle="buttons">
                                        <label class="btn btn-default">
                                            <input type="radio" name="onlyNoneRevisit" checked value="0"><?php echo __('All'); ?>
                                        </label>
                                        <label class="btn btn-default">
                                            <input type="radio" name="onlyNoneRevisit" value="1">未回访
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group dislocationAll">
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-success" style="margin-right: 5px;">提交</button>
                                        <button type="reset" class="btn btn-default">重置</button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div id="toolbar" class="toolbar">
                        
                        <a href="javascript:;" class="btn btn-primary btn-refresh"><i class="fa fa-refresh"></i></a>
                        <!-- <button type="reset" id = "rvhistory" class="btn btn-success btn-embossed">历史回访</button> -->
                    </div>
                    <table id="table" class="table table-striped table-bordered table-hover" 
                           data-operate-edit="<?php echo $auth->check('customer/rvinfo/edit'); ?>" 
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