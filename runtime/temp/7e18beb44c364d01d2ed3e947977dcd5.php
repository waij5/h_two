<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:87:"D:\wamp\www\h_two\public/../application/admin\view\stat\customerorderitems\details.html";i:1559804939;s:70:"D:\wamp\www\h_two\public/../application/admin\view\layout\default.html";i:1544673823;s:67:"D:\wamp\www\h_two\public/../application/admin\view\common\meta.html";i:1544673823;s:69:"D:\wamp\www\h_two\public/../application/admin\view\common\script.html";i:1544673823;}*/ ?>
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
                    <div class="commonsearch-table hidden">
                        <form action="" class="form-inline form-commonsearch nice-validator n-default" id="f-commonsearch" method="post" novalidate="novalidate" role="form">
                            <fieldset>
                                <div class="form-group dislocationAll">
                                    <label for="createtime" class="control-label searchPadding"><?php echo __('Item pay time'); ?></label>
                                    <input class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" name="order_items.item_paytime_start" type="text" value="<?php echo $startDate; ?>"> ~ <input  class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" name="order_items.item_paytime_end" type="text" value="<?php echo $endDate; ?>">
                                </div>
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding">宏脉卡号</label>
                                    <input type="text" class="form-control" name="customer.old_ctm_code" value="" placeholder="宏脉卡号">
                                </div>
                            	 <!--客户卡号，--> 
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding"><?php echo __('customer_id'); ?></label>
                                    <input type="text" class="form-control" name="order_items.customer_id" value="" placeholder="ID">
                                </div>
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding"><?php echo __('ctm_name'); ?></label>
                                    <input type="text" class="form-control" name="customer.ctm_name" value="" placeholder="客户姓名">
                                </div>
                                <!-- 订单分类 -->
                                <div class="form-group dislocationAll">
                                    <label for="c-pro_class" class="control-label searchPadding"><?php echo __('order_type'); ?>:</label>
                                    <select class="form-control" required="" name="order_items.item_type">
                                        <option value=""><?php echo __('All'); ?></option>
                                        <?php foreach($orderTypeList as $orderType => $orderTypeTitle): ?>
                                        <option value="<?php echo $orderType; ?>"><?php echo __('Order_type_' . $orderType); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                 <div class="form-group">
                                    <label for="order_items.item_status" class="control-label labelLocation"><?php echo __('Order_status'); ?></label>
                                </div>
                                <!-- 订单状态 -->
                                 <div class="form-group dislocationAll">
                                    <select class="form-control" name="order_items.item_status">
                                        <?php if(is_array($orderStatusList) || $orderStatusList instanceof \think\Collection || $orderStatusList instanceof \think\Paginator): if( count($orderStatusList)==0 ) : echo "" ;else: foreach($orderStatusList as $key=>$value): ?>
                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                        <?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                </div>
                                <!--初复诊-->
                                 <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding"><?php echo __('Osc_type'); ?></label>
                                    <select class="form-control" required="" name="osc.osc_type">
                                        <option value=""><?php echo __('All'); ?></option>
                                        <?php foreach($oscTypeList as $key => $oscType): ?>
                                        <option value="<?php echo $key; ?>"><?php echo $oscType; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group dislocationAll">
                                    <label for="createtime" class="control-label searchPadding"><?php echo __('ctm_first_recept_time'); ?></label>
                                    <input class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" name="customer.ctm_first_recept_time_start" type="text" value=""> ~ <input  class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" name="customer.ctm_first_recept_time_end" type="text" value="">
                                </div>
                                <!-- 划扣科室 -->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding"><?php echo __('Deduct dept'); ?>:</label>
                                    <select class="selectpicker show-tick form-control" name="order_items.dept_id" data-live-search="true">
                                        <option value=""><?php echo __('All'); ?></option>
                                        <?php foreach($deptList as $key => $dept): ?>
                                        <option value="<?php echo $dept['dept_id']; ?>"><?php echo $dept['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!--项目搜索框 ，-->
                                <div class="form-group dislocationAll">
                                    <label for="item.pro_name" class="control-label searchPadding"><?php echo __('pro_name'); ?></label>
                                    <input type="text" class="form-control" name="order_items.pro_name" value="" placeholder="<?php echo __('pro_name'); ?>" id="pro_name">
                                </div>
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding"><?php echo __('Pro spec'); ?></label>
                                    <input type="text" class="form-control" name="order_items.pro_spec" placeholder="<?php echo __('Full spec'); ?>">
                                </div>
                                <!-- 首次客服科室 网 -->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding"><?php echo __('ctm_first_dept_id'); ?></label>
                                    <select class="selectpicker show-tick form-control" required="" name="customer.ctm_first_dept_id" data-live-search="true">
                                        <option value=""><?php echo __('All'); ?></option>
                                        <?php foreach($deptList as $key => $dept): ?>
                                        <option value="<?php echo $dept['dept_id']; ?>"><?php echo $dept['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- 首次客服项目 网 -->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding"><?php echo __('ctm_first_cpdt_id'); ?></label>
                                    <select class="selectpicker show-tick form-control" required="" name="customer.ctm_first_cpdt_id" data-live-search="true">
                                        <option value=""><?php echo __('All'); ?></option>
                                        <?php foreach($cProjectList as $cProject): ?>
                                        <option value="<?php echo $cProject->id; ?>"><?php echo $cProject->cpdt_name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- 首次科室 现场 -->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding"><?php echo __('ctm_first_osc_dept_id'); ?></label>
                                    <select class="selectpicker show-tick form-control" required="" name="customer.ctm_first_osc_dept_id" data-live-search="true">
                                        <option value=""><?php echo __('All'); ?></option>
                                        <?php foreach($deptList as $key => $dept): ?>
                                        <option value="<?php echo $dept['dept_id']; ?>"><?php echo $dept['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- 首次项目 现场 -->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding"><?php echo __('ctm_first_osc_cpdt_id'); ?></label>
                                    <select class="selectpicker show-tick form-control" required="" name="customer.ctm_first_osc_cpdt_id" data-live-search="true">
                                        <option value=""><?php echo __('All'); ?></option>
                                        <?php foreach($cProjectList as $cProject): ?>
                                        <option value="<?php echo $cProject->id; ?>"><?php echo $cProject->cpdt_name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!--客服科室-->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding"><?php echo __('Dept_id'); ?></label>
                                    <select class="selectpicker show-tick form-control" required="" name="osc.dept_id" data-live-search="true">
                                        <option value=""><?php echo __('All'); ?></option>
                                        <?php foreach($deptList as $key => $dept): ?>
                                        <option value="<?php echo $dept['dept_id']; ?>"><?php echo $dept['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!--现场客服 项目，-->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding"><?php echo __('Cpdt_id'); ?></label>
                                    <select class="selectpicker show-tick form-control" required="" name="osc.cpdt_id" data-live-search="true">
                                        <option value=""><?php echo __('All'); ?></option>
                                        <?php foreach($cProjectList as $cProject): ?>
                                        <option value="<?php echo $cProject->id; ?>"><?php echo $cProject->cpdt_name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!--人员所在科室选择下拉-->
                                <!--  
                                <div class="form-group dislocationAll">
                                    <label for="c-dept" class="control-label searchPadding"><?php echo __('C_dept'); ?>:</label>
                                    <select class="form-control" required="" name="c-dept" id="c-dept">
                                         <option value=""></option>
                               
                                            <option value=""></option>
                                
                                    </select>
                                </div> -->
<!--                                <div class="clearfix"></div>-->
                                <!--营销人员科室-->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding"><?php echo __('develop staff dept'); ?></label>
                                    <select class="selectpicker show-tick form-control" required="" name="customer.develop_dept_id" data-live-search="true">
                                        <option value=""><?php echo __('All'); ?></option>
                                        <?php foreach($deptList as $key => $dept): ?>
                                        <option value="<?php echo $dept['dept_id']; ?>"><?php echo $dept['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding">
                                        网络客服
                                    </label>
                                    <select class="selectpicker show-tick form-control" name="order_items.consult_admin_id" data-live-search="true">
                                        <option value=""><?php echo __('All'); ?></option>
                                        <?php foreach($briefAdminList as $key => $adminTag): ?>
                                        <option value="<?php echo $key; ?>"><?php echo $adminTag; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!--营销人员下拉，-->
                               <!--  <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding"><?php echo __('developStaff'); ?></label>
                                    <select class="selectpicker show-tick form-control" name="customer.admin_id" data-live-search="true">
                                        <option value=""><?php echo __('All'); ?></option>
                                        <?php foreach($briefAdminList as $key => $adminTag): ?>
                                        <option value="<?php echo $key; ?>"><?php echo $adminTag; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div> -->
                                <!--现场客服，-->
                                 <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding"><?php echo __('Osconsult admin name'); ?></label>
                                    <select class="selectpicker show-tick form-control" name="order_items.admin_id" data-live-search="true">
                                        <option value=""><?php echo __('All'); ?></option>
                                        <?php foreach($briefAdminList as $key => $adminTag): ?>
                                        <option value="<?php echo $key; ?>"><?php echo $adminTag; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!--分诊人员-->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding"><?php echo __('Recept admin name'); ?></label>
                                    <select class="selectpicker show-tick form-control" name="order_items.recept_admin_id" data-live-search="true">
                                        <option value=""><?php echo __('All'); ?></option>
                                        <?php foreach($briefAdminList as $key => $adminTag): ?>
                                        <option value="<?php echo $key; ?>"><?php echo $adminTag; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!--开药人-->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding"><?php echo __('prescriber_name'); ?></label>
                                    <select class="selectpicker show-tick form-control" name="order_items.prescriber" data-live-search="true">
                                        <option value=""><?php echo __('All'); ?></option>
                                        <?php foreach($briefAdminList as $key => $adminTag): ?>
                                        <option value="<?php echo $key; ?>"><?php echo $adminTag; ?></option>
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
                                <!-- 客户来源 -->
                                <div class="form-group dislocationAll">
                                    <label for="customer.ctm_source" class="control-label searchPadding"><?php echo __('Ctm_source'); ?></label>
                                    <select class="selectpicker show-tick form-control" data-live-search="true" required="" name="customer.ctm_source" id="customer.ctm_source">
                                        <?php foreach($ctmSrcList as $key => $pdc): ?>
                                            <option value="<?php echo $key; ?>"><?php echo $pdc; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- 营销渠道 -->
                                <div class="form-group dislocationAll">
                                    <label for="customer.ctm_explore" class="control-label searchPadding"><?php echo __('ctm_explore'); ?></label>
                                    <select class="selectpicker show-tick form-control" data-live-search="true" required="" name="customer.ctm_explore" id="customer.ctm_explore">
                                        <?php foreach($channelList as $key => $pdc): ?>
                                            <option value="<?php echo $key; ?>"><?php echo $pdc; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group dislocationAll">
                                    <div class="col-sm-12 text-center">
                                        <button class="btn btn-success dislocationRight" type="sumit"><?php echo __('Submit'); ?></button>
                                        <button class="btn btn-default dislocationRight" type="reset"><?php echo __('Reset'); ?></button>
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
                            <?php echo __('Customer booked item details'); ?>
                        </h2>
                    </div>
                     <!-- style="height: calc(100vh - 120px); -->
                    <div id="consumTable"">
                    	<div>
                            <h3>
                            	<!--count:"344"total:"7121457.78"total_times:"467"unused_total:6252395.78used_total:"869062.00"used_total_times:"29"total:"344"--> 
                            	<!--营收总额-->
                                <?php if(($type == 'develop' or $type == 'details') && $auth->check('stat/customerorderitems/cashdetails')): ?>  
                                <a class="btn btn-default" id="btn-view-cash-total"><i class="fa fa-list-alt" title="查看收款业绩"> 查看收款</i></a>
                                <?php endif; ?>
                                <span><?php echo __('pay_total'); ?>:&nbsp;</span>
                                <span class="text-success" id="total" ></span>
                                &nbsp;&nbsp;
                                <span><?php echo __('Original pay total'); ?>:&nbsp;</span>
                                <span class="text-warning" id="item_original_pay_total" ></span>
                                &nbsp;&nbsp;
                                <small>
                                    <span><?php echo __('count'); ?>:&nbsp;</span>
                                    <span class="text-warning" id="count"></span>
                                    &nbsp;&nbsp; 

                                    <span>顾客数(不重复):&nbsp;</span>
                                    <span class="text-warning" id="uniq_customer_count"></span>
                                    &nbsp;&nbsp; 
                                    
                                    <span><?php echo __('used_total'); ?>:&nbsp;</span>
                                    <span class="text-warning" id="item_used_pay_total"></span>
                                    &nbsp;&nbsp;
                                    
                                    <span><?php echo __('unused_total'); ?>:&nbsp;</span>
                                    <span class="text-warning" id="unused_total"></span>
                                    &nbsp;&nbsp;
                                    
                             		<span><?php echo __('total_times'); ?>:&nbsp;</span>
                                    <span class="text-warning" id="total_times"></span>
                                    &nbsp;&nbsp;
                                    <span><?php echo __('used_total_times'); ?>:&nbsp;</span>
                                    <span class="text-warning" id="used_total_times"></span>
                                    &nbsp;&nbsp;
                                   </small>
                            </h3>
                        </div>
                        <table class="table table-bordered table-condensed table-hover" id="table" width="100%">
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
        </div>
        
<script src="__CDN__/assets/js/require.js" data-main="__CDN__/assets/js/require-backend.js?v=<?php echo $site['version']; ?>"></script>
    </body>
</html>