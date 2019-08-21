<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:79:"D:\wamp\www\h_two\public/../application/admin\view\customer\customer\index.html";i:1566207967;s:71:"D:\wamp\www\h_two\public/../application/admin\view\layout\columns2.html";i:1544673823;s:67:"D:\wamp\www\h_two\public/../application/admin\view\common\meta.html";i:1544673823;s:69:"D:\wamp\www\h_two\public/../application/admin\view\common\script.html";i:1544673823;}*/ ?>
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
                            	<div class="contentTable">
                                	<div class="panel panel-default panel-intro">
    <?php echo build_heading(); ?>
    <div class="panel-body">
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade active in" id="one">
                <div class="widget-body no-padding">
                    <div class="offWrap offWrapRight" title="点击弹出搜索框">
                        <!--<i class="fa fa-angle-double-right"></i>-->
                        <div class="searchText">
                            搜索
                        </div>
                    </div>
                    <div class="commonsearch-table zoomInleft hidden">
                        <form action="" class="form-inline form-commonsearch nice-validator n-default" id="f-commonsearch" method="post" novalidate="novalidate" role="form">
                            <fieldset class="">
                                <div class="offWrap offWrapLeft hidden" title="点击收起搜索框">
                                    <i class="fa fa-angle-double-left text-success">
                                    </i>
                                </div>
                                <!-- common -->
                                <div class="clearfix text-center" style="height: 21px;">
                                    <hr style="margin: 10px 0;">
                                    <span class=" text-success" style="line-height: 10px;position: relative;top: -18px;border: 1px solid #18bc9c;padding: 2px;">通用</span>
                                </div>
                                <?php if(strtolower(request()->action()) == 'invalid'): ?>
                                <div class="form-group dislocationAll hidden">
                                    <label class="control-label searchPadding" for="ctm_status">
                                        <?php echo __('ctm_status'); ?>
                                    </label>
                                    <input class="form-control" id="ctm_status" name="ctm_status" type="text" value="0">
                                    </input>
                                </div>
                                <?php else: ?>
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="ctm_status">
                                        <?php echo __('ctm_status'); ?>
                                    </label>
                                    <select class="form-control selectpicker show-tick" name="ctm_status" tabindex="-98">
                                        <option value="">
                                            所有
                                        </option>
                                        <option selected="" value="1">
                                            有效
                                        </option>
                                        <option value="0">
                                            废弃
                                        </option>
                                    </select>
                                </div>
                                <?php endif; ?>
                                <!-- 职业 -->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="ctm_job">
                                        <?php echo __('Ctm_job'); ?>
                                    </label>
                                    <select class="selectpicker show-tick form-control selectSearch-min" data-live-search="true" id="ctm_job" name="ctm_job" required="">
                                        <?php foreach($jobList as $key => $pdc): ?>
                                        <option value="<?php echo $key; ?>">
                                            <?php echo $pdc; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- 客户类型 -->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="ctm_type">
                                        <?php echo __('ctm_type'); ?>
                                    </label>
                                    <select class="form-control" id="ctm_type" name="ctm_type" required="">
                                        <?php foreach($ctmtypeList as $id => $name): ?>
                                        <option value="<?php echo $id; ?>">
                                            <?php echo $name; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="ctm_id">
                                        <?php echo __('ctm_id'); ?>
                                    </label>
                                    <input class="form-control" id="ctm_id" name="ctm_id" placeholder="<?php echo __('ctm_id'); ?>" type="text" value="">
                                    </input>
                                </div>
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="ctm_name">
                                        <?php echo __('Ctm_name'); ?>
                                    </label>
                                    <input class="form-control" id="ctm_name" name="ctm_name" placeholder="姓名" type="text" value="">
                                    </input>
                                </div>
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="ctm_mobile">
                                        <?php echo __('Ctm_mobile'); ?>
                                    </label>
                                    <input class="form-control" id="ctm_mobile" name="ctm_mobile" placeholder="<?php echo __('Ctm_mobile'); ?>" type="text" value="">
                                    </input>
                                </div>
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding">
                                        宏脉卡号
                                    </label>
                                    <input class="form-control" name="old_ctm_code" placeholder="宏脉卡号" type="text" value="">
                                    </input>
                                </div>
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="ctm_birthdate">
                                        <?php echo __('ctm_age'); ?>
                                    </label>
                                    <input class="form-control" id="ctm_birthdate_start" name="ctm_birthdate_start" type="number" value="">
                                        ~
                                        <input class="form-control" id="ctm_birthdate_end" name="ctm_birthdate_end" type="number" value="">
                                        </input>
                                    </input>
                                </div>
                                <!-- 月份 -->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="customer.month">
                                        <?php echo __('month'); ?>
                                    </label>
                                    <input class="form-control" id="customer.month" name="customer.month" placeholder="<?php echo __('month'); ?>" type="number" value="">
                                    </input>
                                </div>
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="Ctm_remark">
                                        <?php echo __('Ctm_remark'); ?>
                                    </label>
                                    <input class="form-control" name="customer.ctm_remark" />
                                </div>
                                <!-- 是否上门 -->
                                <?php if((empty($noArriveStatus))): ?>
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="arrive_status">
                                        <?php echo __('arrive_status'); ?>
                                    </label>
                                    <select class="form-control" id="arrive_status" name="arrive_status" required="">
                                        <option value="">
                                        </option>
                                        <?php foreach($ArriveStatus as $key => $pdc): ?>
                                        <option value="<?php echo $key; ?>">
                                            <?php echo $pdc; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <?php endif; ?>
                                <!-- 定金余额 -->
                                <div class="form-group" style="margin:5px">
                                    <label class="control-label searchPadding" for="customer.ctm_depositamt">
                                        定金余额
                                    </label>
                                    <input class="form-control" name="customer.ctm_depositamt_start" type="text" value="">
                                        ~
                                        <input class="form-control" name="customer.ctm_depositamt_end" type="text" value="">
                                        </input>
                                    </input>
                                </div>
                                <!-- 实际总金额 -->
                                <div class="form-group" style="margin:5px">
                                    <label class="control-label searchPadding" for="ctm_salamt">
                                        <?php echo __('ctm_salamt'); ?>
                                    </label>
                                    <input class="form-control" id="ctm_salamt_start" name="ctm_salamt_start" type="text" value="">
                                        ~
                                        <input class="form-control" id="ctm_salamt_end" name="ctm_salamt_end" type="text" value="">
                                        </input>
                                    </input>
                                </div>
                                <!-- common -->
                                <!-- cst -->
                                <div class="clearfix text-center" style="height: 21px;">
                                    <hr style="margin: 10px 0;">
                                    <span class=" text-success" style="line-height: 10px;position: relative;top: -18px;border: 1px solid #18bc9c;padding: 2px;">网电</span>
                                </div>
                                <!-- 是否网电公有 -->
                                <?php if(((\think\Request::instance()->action() == 'mycstlist' && !empty($site['cstlist_include_public'])) || \think\Request::instance()->action() == 'index')): ?>
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="ctm_is_cst_public">
                                        网电公有
                                    </label>
                                    <select class="form-control" id="ctm_is_cst_public" name="ctm_is_cst_public" required="">
                                        <option value="">
                                            所有
                                        </option>
                                        <option value="1">
                                            是
                                        </option>
                                        <option value="0">
                                            否
                                        </option>
                                    </select>
                                </div>
                                <?php endif; ?>
                                <div class="form-group dislocationAll<?php if((strtolower(request()->action()) == 'mycstlist')): ?> hidden<?php endif; ?>">
                                    <label class="control-label searchPadding" for="admin.dept_id">
                                        <?php echo __('Dept_id'); ?>
                                    </label>
                                    <select class="selectpicker show-tick form-control selectSearch-mid" data-live-search="true" id="admin.dept_id" name="admin.dept_id" required="">
                                        <option value="">
                                            <?php echo __('All'); ?>
                                        </option>
                                        <?php foreach($deptList as $key => $val): ?>
                                        <option value="<?php echo $val['dept_id']; ?>">
                                            <?php echo $val['name']; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <!-- 营销人员 -->
                                <div class="form-group dislocationAll<?php if((strtolower(request()->action()) == 'mycstlist')): ?> hidden<?php endif; ?>">
                                    <label class="control-label searchPadding" for="admin_id">
                                        <?php echo __('developStaff'); ?>
                                    </label>
                                    <select class="selectpicker show-tick form-control" data-live-search="true" name="admin_id">
                                        <option value="">
                                            <?php echo __('None'); ?>
                                        </option>
                                        <option value="0">
                                            自然到诊
                                        </option>
                                        <?php foreach($briefAdminList as $key => $value): ?>
                                        <option value="<?php echo $key; ?>">
                                            <?php echo $value; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- 营销渠道 -->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="ctm_explore">
                                        <?php echo __('Ctm_explore'); ?>
                                    </label>
                                    <select class="selectpicker show-tick form-control" data-live-search="true" id="ctm_explore" name="ctm_explore" required="">
                                        <?php foreach($channelList as $key => $pdc): ?>
                                        <option value="<?php echo $key; ?>">
                                            <?php echo $pdc; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- 客户来源 -->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="ctm_source">
                                        <?php echo __('Ctm_source'); ?>
                                    </label>
                                    <select class="selectpicker show-tick form-control" data-live-search="true" id="ctm_source" name="ctm_source" required="">
                                        <?php foreach($ctmSrcList as $key => $pdc): ?>
                                        <option value="<?php echo $key; ?>">
                                            <?php echo $pdc; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!--首次客服项目-->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding">
                                        <?php echo __('ctm_first_cpdt_id'); ?>
                                    </label>
                                    <select class="selectpicker show-tick form-control selectSearch-min" data-live-search="true" name="ctm_first_cpdt_id" required="">
                                        <?php foreach($cpdtList as $key => $value): ?>
                                        <option value="<?php echo $key; ?>">
                                            <?php echo $value; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding">
                                        <?php echo __('Ctm_first_dept_id'); ?>
                                    </label>
                                    <select class="selectpicker show-tick form-control selectSearch-mid" data-live-search="true" name="ctm_first_dept_id" required="">
                                        <option value="">
                                            <?php echo __('All'); ?>
                                        </option>
                                        <?php foreach($deptList as $key => $val): ?>
                                        <option value="<?php echo $val['dept_id']; ?>">
                                            <?php echo $val['name']; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!--受理工具-->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding">
                                        <?php echo __('ctm_first_tool'); ?>
                                    </label>
                                    <select class="selectpicker show-tick form-control" data-live-search="true" name="ctm_first_tool_id">
                                        <?php foreach($toolList as $key => $value): ?>
                                        <option value="<?php echo $key; ?>">
                                            <?php echo $value; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- cst end -->
                                <!-- osc start -->
                                <div class="clearfix text-center" style="height: 21px;">
                                    <hr style="margin: 10px 0;">
                                    <span class=" text-success" style="line-height: 10px;position: relative;top: -18px;border: 1px solid #18bc9c;padding: 2px;">现场</span>
                                </div>
                                
                                <!-- 是否现场公有 -->
                                <?php if(((\think\Request::instance()->action() == 'listforosconsult' && !empty($site['osclist_include_public'])) || \think\Request::instance()->action() == 'index')): ?>
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="ctm_is_public">
                                        公有
                                    </label>
                                    <select class="form-control" id="ctm_is_public" name="ctm_is_public" required="">
                                        <option value="">
                                            所有
                                        </option>
                                        <option value="1">
                                            是
                                        </option>
                                        <option value="0">
                                            否
                                        </option>
                                    </select>
                                </div>
                                <?php endif; ?>
                                <!-- 首次现场客服 -->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="ctm_first_osc_admin">
                                        <?php echo __('ctm_first_osc_admin'); ?>
                                    </label>
                                    <select class="selectpicker show-tick form-control" data-live-search="true" name="ctm_first_osc_admin">
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
                                <!-- 首次现场客服项目 -->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="ctm_first_osc_cpdt_id">
                                        <?php echo __('ctm_first_osc_cpdt_id'); ?>
                                    </label>
                                    <select class="selectpicker show-tick form-control selectSearch-min" data-live-search="true" name="ctm_first_osc_cpdt_id">
                                        <?php foreach($cpdtList as $key => $pdc): ?>
                                        <option value="<?php echo $key; ?>">
                                            <?php echo $pdc; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- 首次现场客服科室 -->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="ctm_first_osc_dept_id">
                                        <?php echo __('ctm_first_osc_dept_id'); ?>
                                    </label>
                                    <select class="selectpicker show-tick form-control selectSearch-mid" data-live-search="true" name="ctm_first_osc_dept_id" required="">
                                        <option value="">
                                            <?php echo __('All'); ?>
                                        </option>
                                        <?php foreach($deptList as $key => $val): ?>
                                        <option value="<?php echo $val['dept_id']; ?>">
                                            <?php echo $val['name']; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- 最近现场客服 -->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="ctm_last_osc_admin">
                                        <?php echo __('ctm_last_osc_admin'); ?>
                                    </label>
                                    <select class="selectpicker show-tick form-control" data-live-search="true" name="ctm_last_osc_admin">
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
                                <!-- 最近现场客服项目 -->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="ctm_last_osc_cpdt_id">
                                        <?php echo __('ctm_last_osc_cpdt_id'); ?>
                                    </label>
                                    <select class="selectpicker show-tick form-control selectSearch-min" data-live-search="true" name="ctm_last_osc_cpdt_id">
                                        <?php foreach($cpdtList as $key => $pdc): ?>
                                        <option value="<?php echo $key; ?>">
                                            <?php echo $pdc; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- 最近现场客服科室 -->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="ctm_last_osc_dept_id">
                                        <?php echo __('ctm_last_osc_dept_id'); ?>
                                    </label>
                                    <select class="selectpicker show-tick form-control selectSearch-mid" data-live-search="true" name="ctm_last_osc_dept_id" required="">
                                        <option value="">
                                            <?php echo __('All'); ?>
                                        </option>
                                        <?php foreach($deptList as $key => $val): ?>
                                        <option value="<?php echo $val['dept_id']; ?>">
                                            <?php echo $val['name']; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- 潜在需求 -->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="potential_cpdt">
                                        <?php echo __('potential_cpdt'); ?>
                                    </label>
                                    <select class="selectpicker show-tick form-control selectSearch-min" data-live-search="true" name="potential_cpdt">
                                        <?php foreach($cpdtList as $k => $v): ?>
                                        <option value="<?php echo $k; ?>">
                                            <?php echo $v; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- osc end -->

                                <!-- comsumption -->
                                <input type="hidden" name="order_items.item_status" value="" />
                                <div class="clearfix text-center" style="height: 21px;">
                                    <hr style="margin: 10px 0;">
                                    <span class=" text-success" style="line-height: 10px;position: relative;top: -18px;border: 1px solid #18bc9c;padding: 2px;">消费</span>
                                </div>
                                <!-- 付款时间 -->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding">
                                        付款时间
                                    </label>
                                    <br />
                                    <input class="form-control datetimepicker y-consu-con" data-date-format="YYYY-MM-DD" data-use-current="true" name="order_items.item_paytime_start" type="text" value="" style="width: 100px;" />
                                        ~
                                    <input class="form-control datetimepicker y-consu-con" data-date-format="YYYY-MM-DD" data-use-current="true" name="order_items.item_paytime_end" type="text" value="" style="width: 100px;" />
                                </div>
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="ctm_last_osc_dept_id">
                                        <?php echo __('deduct dept'); ?>
                                    </label>
                                    <select class="selectpicker show-tick form-control selectSearch-mid y-consu-con" data-live-search="true" name="order_items.dept_id" required="">
                                        <option value="">
                                            <?php echo __('All'); ?>
                                        </option>
                                        <?php foreach($deductDepts as $key => $val): ?>
                                        <option value="<?php echo $val['dept_id']; ?>">
                                            <?php echo $val['dept_name']; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="clearfix"></div>
                                <!-- 所属类别 -->
                                <div class="form-group">
                                    <label for="c-pro_cat1" class="control-label"><?php echo __('Pro_cat1'); ?>:</label>
                                    <select class="form-control c-pdc1 y-consu-con" name="project.pro_cat1">
                                        <option value=""></option>
                                        <?php foreach($lvl1Pdc as $pdcId => $pdcName): ?>
                                            <option value="<?php echo $pdcId; ?>"><?php echo $pdcName; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="c-pro_cat2" class="control-label"><?php echo __('Pro_cat2'); ?>:</label>
                                    <select name="project.pro_cat2 y-consu-con" class="form-control c-pdc2">
                                        <option></option>
                                    </select>
                                </div>
                                <div class="form-group" style="width: 100%; display: inline-flex; align-content: space-between; margin-top: 2px; margin-bottom: 2px;">
                                    <label for="c-pro_cat2" class="control-label" style="line-height: 31px">项目</label>
                                    <div class="sp_container search-selector-pro-container" style="flex-grow: 1;">
                                        <input id="search-selector-pro" class="form-control" type="text" style="width: 100%; max-width: 100%;" placeholder="项目名">
                                        <input type="hidden" value="" name="order_items.pro_id" id="search-pro_id" class="y-consu-con" />
                                    </div>
                                </div>
                                <!-- comsumption end -->

                                <!-- other -->
                                <div class="clearfix text-center" style="height: 21px;">
                                    <hr style="margin: 10px 0;">
                                    <span class=" text-success" style="line-height: 10px;position: relative;top: -18px;border: 1px solid #18bc9c;padding: 2px;">其它</span>
                                </div>
                                
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="createtime">
                                        <?php echo __('Customer createtime'); ?>
                                    </label>
                                    <br />
                                    <input class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" id="createtime_start" name="customer.createtime_start" type="text" value="" style="width: 100px;" />
                                    ~
                                    <input class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" id="createtime_end" name="customer.createtime_end" type="text" value="" style="width: 100px;" />
                                </div>
                                <!-- 首次到诊时间 -->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="ctm_first_recept_time">
                                        <?php echo __('ctm_first_recept_time'); ?>
                                    </label>
                                    <br />
                                    <input class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" id="ctm_first_recept_time_start" name="ctm_first_recept_time_start" type="text" value="" style="width: 100px;" >
                                    ~
                                    <input class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" id="ctm_first_recept_time_end" name="ctm_first_recept_time_end" type="text" value="" style="width: 100px;" />
                                </div>
                                <!-- <div class="clearfix"></div> -->
                                <!-- 最近到诊时间 -->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="ctm_last_recept_time">
                                        <?php echo __('ctm_last_recept_time'); ?>
                                    </label>
                                    <br />
                                    <input class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" id="ctm_last_recept_time_start" name="ctm_last_recept_time_start" type="text" value="" style="width: 100px;" />
                                    ~
                                    <input class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" id="ctm_last_recept_time_end" name="ctm_last_recept_time_end" type="text" value="" style="width: 100px;" />
                                </div>
                                <!-- 最近回访时间 -->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="ctm_last_rv_time">
                                        最近回访时间
                                    </label>
                                    <br />
                                    <input class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" id="ctm_last_rv_time_start" name="ctm_last_rv_time_start" type="text" value="" style="width: 100px;" />
                                    ~
                                    <input class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" id="ctm_last_rv_time_end" name="ctm_last_rv_time_end" type="text" value="" style="width: 100px;" />
                                </div>
                                <div class="form-group dislocationAll">
                                    <div class="col-sm-12 text-center">
                                        <button class="btn btn-success" style="margin-right: 5px;" type="submit">
                                            提交
                                        </button>
                                        <button class="btn btn-default" type="reset">
                                            重置
                                        </button>
                                        <?php if(($auth->check('customer/customer/downloadprocess') && (strtolower(request()->action()) == 'index' || strtolower(request()->action()) == 'invalid'))): ?>
                                        <button class="btn btn-default" id="btn-export" type="button">
                                            <?php echo __('Export'); ?>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div class="toolbar" id="toolbar">
                        <?php echo build_toolbar(); if($auth->check('customer/customer/batchupdateosc')): ?>
                        <a class="btn btn-success btn-multideduct" href="javascript:;">
                            <i class="fa fa-editone">
                                <?php echo __('batch oscadminid'); ?>
                            </i>
                        </a>
                        <?php endif; ?>
                        <!-- 批量修改营销人员 -->
                        <?php if($auth->check('customer/customer/adminid')): ?>
                        <a class="btn btn-success btn-adminid" href="javascript:;">
                            <i class="fa fa-editone">
                                <?php echo __('batch adminid'); ?>
                            </i>
                        </a>
                        <?php endif; ?>
                        <!-- 批量增加回访计划 -->
                        <?php if($auth->check('customer/customer/batchaddrvtype')): ?>
                        <a class="btn btn-success btn-addrvtype" href="javascript:;">
                            <i class="fa fa-editone">
                                <?php echo __('batch addrvtype'); ?>
                            </i>
                        </a>
                        <?php endif; ?>
                        <!-- 批量移出公有池 -->
                        <?php if(($auth->check('customer/customer/batchpublicOut')  && (strtolower(request()->action()) == 'publist' || strtolower(request()->action()) == 'cstpublist'))): ?>
                        <a class="btn btn-success btn-publicOut" href="javascript:;">
                            <i class="fa fa-editone">
                                <?php echo __('batch publicOut'); ?>
                            </i>
                        </a>
                        <?php endif; ?>
                        <!-- 批量移出废弃池 -->
                        <?php if(($auth->check('customer/customer/batchinvalidOut')  && (strtolower(request()->action()) == 'invalid'))): ?>
                        <a class="btn btn-success btn-invalidOut" href="javascript:;">
                            <i class="fa fa-editone">
                                <?php echo __('batch invalidOut'); ?>
                            </i>
                        </a>
                        <?php endif; ?>
                        <!-- 合并客户 -->
                        <?php if(($auth->check('customer/customer/MergeHisCustomer'))): ?>
                        <a href="javascript:;" class="btn btn-success btn-MergeHisCustomer">
                            <i class="fa fa-editone">
                                合并客户
                            </i>
                        </a>
                        <?php endif; ?>
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">批量操作 
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu" id="ul-batch-operate-customer">
                                <?php if($auth->check('customer/customer/batchupdateosc')): ?>
                                <li><a href="javascript:;" data-url="/customer/customer/batchupdateosc" data-field-name="ids" data-window-name="<?php echo __('batch oscadminid'); ?>"><?php echo __('batch oscadminid'); ?></a></li>
                                <?php endif; if($auth->check('customer/customer/adminid')): ?>
                                <li><a href="javascript:;" data-url="/customer/customer/adminid" data-field-name="id" data-window-name="<?php echo __('batch adminid'); ?>"><?php echo __('batch adminid'); ?></a></li>
                                <?php endif; if($auth->check('customer/customer/batchaddrvtype')): ?>
                                <li><a href="javascript:;" data-url="/customer/customer/batchaddrvtype" data-field-name="id" data-window-name="<?php echo __('batch addrvtype'); ?>"><?php echo __('batch addrvtype'); ?></a></li>
                                <?php endif; if(($auth->check('customer/customer/batchpublicOut')  && (strtolower(request()->action()) == 'publist' || strtolower(request()->action()) == 'cstpublist'))): ?>
                                <li><a href="javascript:;" data-url="/customer/customer/batchpublicOut" data-field-name="id" data-window-name="<?php echo __('batch invalidOut'); ?>"><?php echo __('batch invalidOut'); ?></a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                    <table class="table table-bordered table-hover" data-operate-del="<?php echo $auth->check('customer/customer/del'); ?>" data-operate-edit="<?php echo $auth->check('customer/customer/edit'); ?>" id="table" width="100%">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    /*.bootstrap-table{width: 58%;}*/
    /*html{height: 100%;}body{overflow-y: hidden;}.contentLeft{overflow-y: auto;}*/
    
    .panel{box-shadow: none;}
    .panel-body{padding-bottom: 0;}
    .contentLeft{background-color: #fff;}
    .fixed-table-pagination .pagination-detail, .fixed-table-pagination div.pagination{margin-bottom: 0;}
    .contentTable {
        height: 250px;
        min-height: 250px;
    }
    .search-selector-pro-container .sp_container {
        width: 100% !important;
        padding-left: 15px;
        padding-right: 15px;
    }
</style>
                                </div>
                                <!--<div class="pull-right contentRight">-->    
                                	<!--<iframe id="showIframe" style="width:100%;"></iframe>-->
                                <!--</div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
<script src="__CDN__/assets/js/require.js" data-main="__CDN__/assets/js/require-backend.js?v=<?php echo $site['version']; ?>"></script>
    </body>
</html>