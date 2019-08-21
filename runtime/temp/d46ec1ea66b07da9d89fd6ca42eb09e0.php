<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:79:"D:\wamp\www\h_two\public/../application/admin\view\stat\rvinforeport\index.html";i:1559804939;s:70:"D:\wamp\www\h_two\public/../application/admin\view\layout\default.html";i:1544673823;s:67:"D:\wamp\www\h_two\public/../application/admin\view\common\meta.html";i:1544673823;s:69:"D:\wamp\www\h_two\public/../application/admin\view\common\script.html";i:1544673823;}*/ ?>
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
                                <!-- <div class="form-group dislocationAll">
                                    <label class="control-label labelLocation" for="customer_id">
                                        <?php echo __('Customer'); ?>
                                    </label>
                                    <input class="form-control" id="field_ctm_id" name="customer_id" type="hidden" value=""/>
                                    <a href="javascript:;" id="a-search-customer">
                                        <input class="form-control" id="field_ctm_name" readonly="" type="text"/>
                                    </a>
                                    <a class="btn btn-danger btn-del" href="javascript:;" id="btn-customer-clear">
                                        <i class="fa fa-trash">
                                        </i>
                                        清除
                                    </a>
                                </div> -->
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
                                <!--录入顾客时 网电客服-->
                                <div class="form-group dislocationAll">
                                    <label for="customer.ctm_first_dept_id" class="control-label searchPadding"><?php echo __('ctm_first_dept_id'); ?></label>
                                    <select class="selectpicker show-tick form-control" name="customer.ctm_first_dept_id"  data-live-search="true">
                                        <option value><?php echo __('All'); ?></option>
                                        <?php foreach($deptList as $key => $val): ?>
                                        <option value="<?php echo $val['dept_id']; ?>"><?php echo $val['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding"><?php echo __('ctm_first_cpdt_id'); ?></label>
                                    <select class="selectpicker show-tick form-control" required="" name="customer.ctm_first_cpdt_id" data-live-search="true">
                                        <option value=""><?php echo __('All'); ?></option>
                                        <?php foreach($cProjectList as $cProject): ?>
                                        <option value="<?php echo $cProject->id; ?>"><?php echo $cProject->cpdt_name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!--录入顾客时 现场客服，-->
                                <div class="form-group dislocationAll">
                                    <label for="customer.ctm_first_osc_dept_id" class="control-label searchPadding"><?php echo __('ctm_first_osc_dept_name'); ?></label>
                                    <select class="selectpicker show-tick form-control" name="customer.ctm_first_osc_dept_id"  data-live-search="true">
                                        <option value><?php echo __('All'); ?></option>
                                        <?php foreach($deptList as $k => $v): ?>
                                        <option value="<?php echo $v['dept_id']; ?>"><?php echo $v['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding"><?php echo __('ctm_first_osc_cpdt_name'); ?></label>
                                    <select class="selectpicker show-tick form-control" required="" name="customer.ctm_first_osc_cpdt_id" data-live-search="true">
                                        <option value=""><?php echo __('All'); ?></option>
                                        <?php foreach($cProjectList as $cProject): ?>
                                        <option value="<?php echo $cProject->id; ?>"><?php echo $cProject->cpdt_name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>





                                <!--受理工具-->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding"><?php echo __('ctm_first_tool'); ?></label>
                                    <select class="selectpicker show-tick form-control" name="customer.ctm_first_tool_id" data-live-search="true">
                                        <?php foreach($toolList as $key => $value): ?>
                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="form-group dislocationAll">
                                    <label class="control-label labelLocation" for="createtime">
                                        <?php echo __('Revisit type'); ?>
                                    </label>
                                    <select class="form-control" name="rvt_type">
                                        <option value="">
                                            <?php echo __('All'); ?>
                                        </option>
                                        <?php foreach($rvTypeList as $key => $value): ?>
                                        <option value="<?php echo $value; ?>">
                                            <?php echo $value; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group dislocationAll">
                                    <label class="control-label labelLocation" for="Revisit staff">
                                        <?php echo __('Department'); ?>
                                    </label>
                                    <select name="admin.dept_id" class="selectpicker show-tick form-control" data-live-search="true">
                                        <option value=""><?php echo __('All'); ?></option>
                                        <?php foreach($deptList as $key => $val): ?>
                                        <option value="<?php echo $val['dept_id']; ?>"><?php echo $val['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- <div class="form-group" style="margin:5px">
                                    <label class="control-label labelLocation" for="Revisit staff">
                                        <?php echo __('Revisit staff'); ?>
                                    </label>
                                    <input id="field_admin_id" name="admin_id" type="hidden">
                                        <div onmouseleave="$(this).find('.word').addClass('hidden');" style="display: inline-block;">
                                            <input autocomplete="off" class="nickname form-control" onmouseenter="$(this).siblings().find('.word').removeClass('hidden')" style="position: relative;max-width: 130px;" type="text" value=""/>
                                            <div style="position: relative;">
                                                <ul class="form-control word" data-index="" id="word" style="display: none;list-style: none;position: absolute;cursor: pointer;z-index: 999;height: auto;text-align: left; width: 100%; max-height: 130px;padding-bottom: 0;padding-top: 0;overflow-y: auto;">
                                                </ul>
                                            </div>
                                        </div>
                                        <a class="btn btn-danger btn-del" href="javascript:;" id="btn-admin-clear" style="margin-left: 5px;">
                                            <i class="fa fa-trash">
                                            </i>
                                            清除
                                        </a>
                                    </input>
                                </div> -->
                                <!-- 回访人 -->
                                <div class="form-group dislocationAll">
                                    <label for="admin_id" class="control-label searchPadding"><?php echo __('admin_id'); ?></label>
                                    <select class="selectpicker show-tick form-control" name="admin_id" data-live-search="true">
                                        <option value=""><?php echo __('None'); ?></option>
                                        <?php foreach($briefAdminList as $key => $value): ?>
                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group dislocationAll">
                                    <label class="control-label labelLocation" for="createtime">
                                        <?php echo __('Customer arrival status'); ?>
                                    </label>
                                    <select name="customer.arrive_status" class="form-control">
                                    <?php foreach($arriveStatusList as $key => $value): ?>
                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                    <?php endforeach; ?>
                                    </select> 
                                </div>
                                <!--<div class="clearfix"></div>-->
                                <div class="form-group dislocationAll">
                                    <label class="control-label labelLocation" for="createtime">
                                        <?php echo __('Revisit date'); ?>
                                    </label>
                                    <input class="form-control datetimepicker forbid-timestamp" data-date-format="YYYY-MM-DD" data-use-current="true" id="rv_date_start" name="rv_date_start" type="text" value="<?php echo $startDate; ?>">
                                        ~
                                        <input class="form-control datetimepicker forbid-timestamp" data-date-format="YYYY-MM-DD" data-use-current="true" id="rv_date_end" name="rv_date_end" type="text" value="<?php echo $endDate; ?>">
                                        </input>
                                    </input>
                                </div>
                                <div class="form-group dislocationAll">
                                    <label class="control-label labelLocation" for="createtime">
                                        <?php echo __('Revisit time'); ?>
                                    </label>
                                    <input class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" id="rv_time_start" name="rv_time_start" type="text" value="">
                                        ~
                                        <input class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" id="rv_time_end" name="rv_time_end" type="text" value="">
                                        </input>
                                    </input>
                                </div>
                                <div class="form-group dislocationAll">
                                    <label class="control-label labelLocation" for="createtime">
                                        <?php echo __('Customer create time'); ?>
                                    </label>
                                    <input class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" name="customer.createtime_start" type="text" >
                                        ~
                                        <input class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" name="customer.createtime_end" type="text" >
                                        </input>
                                    </input>
                                </div>
                                <div class="form-group dislocationAll">
                                     <label class="control-label labelLocation" for="createtime">
                                        <?php echo __('Revisit content'); ?>
                                    </label>
                                    <input class="form-control" name="rvi_content" />
                                </div>
                                <div class="form-group dislocationAll">
                                     <div class="checkbox">
                                        <label>
                                          <input type="checkbox" id="onlyNoneRevisit" value="true"> <?php echo __('Not revisit'); ?>
                                        </label>
                                      </div>
                                </div>
                                <div class="form-group dislocationAll">
                                    <div class="col-sm-12 text-center">
                                        <button class="btn btn-success dislocationRight" id="btn-submit-1" type="button">
                                            提交
                                        </button>
                                        <button class="btn btn-default" type="reset">
                                            重置
                                        </button>
                                        <button type="button" class="btn btn-default" id="btn-export"><?php echo __('Export'); ?></button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div class="table table-striped table-bordered table-hover table-condensed" id="toolbar" width="100%">
                    </div>
                    <div class="form-group">
                        <h2 class="text-center">
                            <?php echo __('Customer revisit records'); ?>
                        </h2>
                    </div>
                    <div class="clearfix"></div>
                    <div>
                        <label class="control-label"><?php echo __('Rvinfo count'); ?></label>
                        <span class="text-success" id="h-count">0</span>&nbsp;&nbsp;
                        <label class="control-label"><?php echo __('Visited count'); ?></label>
                        <span class="text-success" id="h-visited_count">0</span>&nbsp;&nbsp;
                        <label class="control-label"><?php echo __('Avaiable visited count'); ?></label>
                        <span class="text-success" id="h-avaiable_visited_count">0</span>&nbsp;&nbsp;
                        <label class="control-label"><?php echo __('Rvinfo customer count'); ?></label>
                        <span class="text-success" id="h-customer_count">0</span>&nbsp;&nbsp;
                        <label class="control-label"><?php echo __('Avaiable visited customer count'); ?></label>
                        <span class="text-success" id="h-avaiable_visited_customer_count">0</span>&nbsp;&nbsp;
                    </div>
                    <div id="consumTable" style="position: relative;overflow-y: auto;">
                    <table class="table table-bordered table-condensed table-hover scrolltable" id="table" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    <?php echo __('No.'); ?>
                                </th>
                                <th class="text-center">
                                    <?php echo __('Customer'); ?>
                                </th>
                                <th class="text-center">
                                    <?php echo __('Arrive status'); ?>
                                </th>
                                <th class="text-center">
                                    <?php echo __('Gender'); ?>
                                </th>
                                <th class="text-center">
                                    <?php echo __('Age'); ?>
                                </th>
                                <th class="text-center">
                                    <?php echo __('Cm_mobile'); ?>
                                </th>
                                <th class="text-center">
                                    <?php echo __('ctm_first_cpdt_name'); ?>
                                </th>
                                <th class="text-center">
                                    <?php echo __('ctm_first_dept_name'); ?>
                                </th>
                                <th class="text-center">
                                    <?php echo __('ctm_first_osc_cpdt_name'); ?>
                                </th>
                                <th class="text-center">
                                    <?php echo __('ctm_first_osc_dept_name'); ?>
                                </th>
                                <th>
                                    <?php echo __('Revisit type'); ?>
                                </th>
                                <th class="text-center">
                                    <?php echo __('Revisit plan'); ?>
                                </th>
                                <th class="text-center">
                                    <?php echo __('Revisit staff'); ?>
                                </th>
                                <th class="text-center">
                                    <?php echo __('admin dept'); ?>
                                </th>
                                <th class="text-center">
                                    <?php echo __('Revisit date'); ?>
                                </th>
                                <th class="text-center">
                                    <?php echo __('Revisit time'); ?>
                                </th>
                                <!-- <th class="text-center">
                                    <?php echo __('Revisit status'); ?>
                                </th> -->
                                <th class="text-center">
                                    <?php echo __('Revisit content'); ?>
                                </th>
                                <th class="text-center">
                                    <?php echo __('Revisit fail reason'); ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
                    </div>
                    <div class="text-center" id="div-load-more">
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
        </div>
        
<script src="__CDN__/assets/js/require.js" data-main="__CDN__/assets/js/require-backend.js?v=<?php echo $site['version']; ?>"></script>
    </body>
</html>