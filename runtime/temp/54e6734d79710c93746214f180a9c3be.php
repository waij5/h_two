<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:77:"D:\wamp\www\h_two\public/../application/admin\view\cash\order\deductview.html";i:1559804940;s:70:"D:\wamp\www\h_two\public/../application/admin\view\layout\default.html";i:1544673823;s:67:"D:\wamp\www\h_two\public/../application/admin\view\common\meta.html";i:1544673823;s:69:"D:\wamp\www\h_two\public/../application/admin\view\common\script.html";i:1544673823;}*/ ?>
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
                                <form id="view-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action="">
    <ul id="myTab" class="nav nav-tabs">
        <li class="active">
            <a href="#basic" data-toggle="tab"><?php echo __('Basic info'); ?></a>
        </li>
        <li>
            <a href="#extra" data-toggle="tab"><?php echo __('Extra info'); ?></a>
        </li>
        <li>
            <a href="#conHistory" data-toggle="tab"><?php echo __('Consult history'); ?></a>
        </li>
        <li>
            <a href="#osconHistory" data-toggle="tab"><?php echo __('Osconsult history'); ?></a>
        </li>
        <li>
            <a href="#orderHistory" data-toggle="tab"><?php echo __('Order history'); ?></a>
        </li>
        <li>
            <a href="#rvinfoHistory" data-toggle="tab"><?php echo __('Rvinfo history'); ?></a>
        </li>
        <li>
            <a data-toggle="tab" href="#hmOrderHistory"><?php echo __('Hm order history'); ?></a>
        </li>
    </ul>
    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade in active" id="basic">
            <div class="panel-body">
                <input type="hidden" name="row[ctm_id]" value="<?php echo $row['ctm_id']; ?>" />
                <div class="form-group col-sm-6 col-xs-6">
                    <label for="c-ctm_name" class="control-label col-xs-4 col-sm-4 col-style dislocationTop"><?php echo __('Ctm_name'); ?>:</label>
                    <div class="col-xs-8 col-sm-8 col-style">
                        <input id="c-ctm_name" class="form-control" name="row[ctm_name]" type="text" value="<?php echo $row['ctm_name']; ?>" readonly="readonly">
                    </div>
                </div>
                <div class="form-group col-sm-6 col-xs-6">
                    <label for="c-ctm_sex" class="control-label col-xs-4 col-sm-4 col-style dislocationTop"><?php echo __('Ctm_sex'); ?>:</label>
                    <div class="col-xs-8 col-sm-8 col-style">
                    	<!--<label class="control-label "><?php if($row['ctm_sex'] == 1): ?>女 <?php else: ?>男<?php endif; ?></label>-->
                       <input id="c-ctm_name" class="form-control" name="row[ctm_name]" type="text" value="<?php if($row['ctm_sex'] == 1): ?>女 <?php else: ?>男<?php endif; ?>" readonly="readonly">
                    </div>
                </div>
                <div class="form-group col-sm-6 col-xs-6">
                    <label class="control-label col-xs-4 col-sm-4 col-style dislocationTop" for="c-ctm_birthdate">
                        <?php echo __('Ctm age'); ?>:
                    </label>
                    <div class="col-xs-8 col-sm-8 col-style">
                        <input type="text" readonly value="<?php if($row['ctm_birthdate']): ?><?php echo calcAge($row['ctm_birthdate']); endif; ?>" class="form-control">
                    </div>
                </div>
                <div class="form-group col-sm-6 col-xs-6">
                    <label for="c-ctm_birthdate" class="control-label col-xs-4 col-sm-4 col-style dislocationTop"><?php echo __('Ctm_birthdate'); ?>:</label>
                    <div class="col-xs-8 col-sm-8 col-style">
                        <input id="c-ctm_birthdate" readonly="readonly" class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" name="row[ctm_birthdate]" type="text" value="<?php echo $row['ctm_birthdate']; ?>">                    	
                    </div>
                </div>
                
                <div class="form-group col-sm-6 col-xs-6">
                    <label for="c-ctm_tel" class="control-label col-xs-4 col-sm-4 col-style dislocationTop"><?php echo __('Ctm_tel'); ?>:</label>
                    <div class="col-xs-8 col-sm-8 col-style">
                    <?php if($admin['showMobile'] == '1'): ?>
                        <input id="c-ctm_tel" class="form-control" name="row[ctm_tel]" type="text" value="<?php echo $row['ctm_tel']; ?>" readonly="readonly">
                    <?php else: ?>
                        <input id="c-ctm_tel" class="form-control" name="row[ctm_tel]" type="text" value="<?php echo getMaskString($row['ctm_tel']); ?>" readonly="readonly">
                    <?php endif; ?>
                    </div>
                </div>
                <div class="form-group col-sm-6 col-xs-6">
                    <label for="c-ctm_mobile" class="control-label col-xs-4 col-sm-4 col-style dislocationTop"><?php echo __('Ctm_mobile'); ?>:</label>
                    <div class="col-xs-8 col-sm-8 col-style">
                    <?php if($admin['showMobile'] == '1'): ?>
                        <input class="form-control" id="c-ctm_mobile" name="row[ctm_mobile]" type="text" value="<?php echo $row['ctm_mobile']; ?>" readonly="readonly"/>
                    <?php else: ?>
                        <input class="form-control" id="c-ctm_mobile" name="row[ctm_mobile]" type="text" value="<?php echo getMaskString($row['ctm_mobile']); ?>" readonly="readonly" />
                    <?php endif; ?>
                    </div>
                </div>
               <div class="form-group col-sm-6 col-xs-6">
                    <label for="c-ctm_source" class="control-label col-xs-4 col-sm-4 col-style dislocationTop"><?php echo __('Ctm_source'); ?>:</label>
                    <div class="col-xs-8 col-sm-8 col-style">
                        <?php echo build_select('row[ctm_source]', $ctmSrcList, $row['ctm_source'], ['class'=>'form-control selectpicker', 'data-rule'=>'required','disabled'=>'disabled']); ?>
                    </div>
                </div>

                 <div class="form-group col-sm-6 col-xs-6">
                    <label for="c-ctm_first_search" class="control-label col-xs-4 col-sm-4 col-style dislocationTop"><?php echo __('ctm_first_search'); ?>:</label>
                    <div class="col-xs-8 col-sm-8 col-style">
                        <input id="c-ctm_first_search" class="form-control" name="row[ctm_first_search]" type="text" value="<?php echo $row['ctm_first_search']; ?>" readonly="readonly">
                    </div>
                </div>
                <div class="form-group col-sm-6 col-xs-6">
                    <label for="c-ctm_source" class="control-label col-xs-4 col-sm-4 col-style dislocationTop"><?php echo __('Ctm_explore'); ?>:</label>
                    <div class="col-xs-8 col-sm-8 col-style">
                        <?php echo build_select('row[ctm_explore]', $channelList, $row['ctm_explore'], ['class'=>'form-control selectpicker', 'required'=>'','disabled'=>'disabled']); ?>
                    </div>
                </div>
                 <div class="form-group col-sm-6 col-xs-6">
                    <label for="c-ctm_type" class="control-label col-xs-4 col-sm-4 col-style dislocationTop"><?php echo __('ctm_type'); ?>:</label>
                    <div class="col-xs-8 col-sm-8 col-style">
                        <?php echo build_select('row[ctm_type]', $ctmtypeList, $row['ctm_type'], ['class'=>'form-control selectpicker', 'required'=>'','disabled'=>'disabled']); ?>
                    </div>
                </div>
                <!-- 录入时间 -->
                <div class="form-group col-xs-6 col-sm-6">
                    <label for="c-ctm_depositamt" class="control-label col-xs-4 col-sm-4 col-style dislocationTop">录入时间:</label>
                        <div class="col-xs-8 col-sm-8 col-style">
                        <label class="control-label dislocationTop"><?php echo date('Y-m-d H:i:s', $row['createtime']); ?></label>
                        </div>
                </div>
                 <div class="form-group col-sm-6 col-xs-6">
                    <label for="c-rec_customer_id" class="control-label col-xs-4 col-sm-4 " ><?php echo __('rec_customer_id'); ?></label>
                    <div class="col-xs-8 col-sm-8 col-style">
                        <!--<label class="control-label" id="c-rec_customer_id" name="customer[rec_customer_id]"><?php echo $recCustomerName; ?></label>-->
                         <input id="c-ctm_mobile" class="form-control" name="row[ctm_mobile]" type="text" value="<?php echo $recCustomerName; ?>" readonly="readonly">
                    </div>
                </div>
                

                <div class="clearfix">
                </div>
                 <div class="form-group col-sm-6 col-xs-6">
                    <label class="control-label col-xs-4 col-sm-4 col-style"><?php echo __('potential_cpdt1'); ?>:</label>
                    <div class="col-xs-8 col-sm-8 col-style">
                    <select class="selectpicker form-control selectSearch-min" disabled="disabled" data-live-search="true" name="row[potential_cpdt1]" required="">
                        <?php foreach($cpdtList as $key => $value): ?>
                        <option value="<?php echo $key; ?>"<?php if(($key == $row['potential_cpdt1'])): ?> selected="" <?php endif; ?>><?php echo $value; ?></option>
                        <?php endforeach; ?>
                    </select>
                    </div>
                </div>
                 <div class="form-group col-sm-6 col-xs-6">
                        <label class="control-label col-xs-4 col-sm-4 col-style"><?php echo __('potential_cpdt2'); ?>:</label>
                        <div class="col-xs-8 col-sm-8 col-style">
                        <select class="selectpicker form-control selectSearch-min" disabled="disabled" data-live-search="true" name="row[potential_cpdt2]" required="">
                            <?php foreach($cpdtList as $ke => $val): ?>
                            <option value="<?php echo $ke; ?>"<?php if(($ke == $row['potential_cpdt2'])): ?> selected="" <?php endif; ?>><?php echo $val; ?></option>
                            <?php endforeach; ?>
                        </select>
                        </div>
                     </div>
                     <div class="form-group col-sm-6 col-xs-6">
                        <label class="control-label col-xs-4 col-sm-4 col-style"><?php echo __('potential_cpdt3'); ?>:</label>
                        <div class="col-xs-8 col-sm-8 col-style">
                        <select class="selectpicker form-control selectSearch-min" disabled="disabled" data-live-search="true" name="row[potential_cpdt3]" required="">
                             <?php foreach($cpdtList as $k => $v): ?>
                            <option value="<?php echo $k; ?>"<?php if(($k == $row['potential_cpdt3'])): ?> selected="" <?php endif; ?>><?php echo $v; ?></option>
                            <?php endforeach; ?>
                        </select>
                        </div>
                     </div>


                <div class="clearfix">
                </div>
                 <div class="form-group col-sm-6 col-xs-6">
                    <label for="c-developStaff" class="control-label col-xs-4 col-sm-4 col-style dislocationTop"><?php echo __('developStaff'); ?>:</label>
                    <div class="col-xs-8 col-sm-8 col-style">
                        <label class="control-label dislocationTop"><?php echo $developStaffName; ?></label>
                    </div>
                </div>
                <div class="form-group col-sm-12 col-xs-12">
                    <label for="c-ctm_addr" class="control-label col-xs-3 col-sm-2 col-style"><?php echo __('Ctm_addr'); ?>:</label>
                    <div class="col-xs-8 col-sm-8 col-style">
                        <!-- <input id="c-ctm_addr" class="form-control" name="row[ctm_addr]" type="text" value="<?php echo $row['ctm_addr']; ?>"> -->
                        <?php 
                            $addrArr = explode('-', $row['ctm_addr']);
                         ?>
                        
                        <!--<input id="c-ctm_mobile" class="form-control" name="row[ctm_mobile]" type="text" value="<?php echo (isset($addrArr[0] ) && ($addrArr[0]  !== '')?$addrArr[0] :''); ?><?php echo (isset($addrArr[1] ) && ($addrArr[1]  !== '')?$addrArr[1] :''); ?><?php echo (isset($addrArr[2] ) && ($addrArr[2]  !== '')?$addrArr[2] :''); ?>" readonly="readonly">-->
                        <label class="control-label "><?php echo (isset($addrArr[0] ) && ($addrArr[0]  !== '')?$addrArr[0] :''); ?><?php echo (isset($addrArr[1] ) && ($addrArr[1]  !== '')?$addrArr[1] :''); ?><?php echo (isset($addrArr[2] ) && ($addrArr[2]  !== '')?$addrArr[2] :''); ?></label>
                        <!--<div class="form-inline" data-toggle="cxselect" data-url="assets/libs/jquery-cxselect/js/cityData.min.json?v=1.01" data-selects="province,city,area" data-json-space="" data-json-name="n" data-json-value="" data-required="true">
                            <select class="province form-control"  disabled="disabled" name="row[province]" data-value="<?php echo (isset($addrArr[0] ) && ($addrArr[0]  !== '')?$addrArr[0] :''); ?>"></select>
                            <select class="city form-control" disabled="disabled" name="row[city]" data-value="<?php echo (isset($addrArr[1] ) && ($addrArr[1]  !== '')?$addrArr[1] :''); ?>"></select>
                            <select class="area form-control" disabled="disabled" name="row[area]" data-value="<?php echo (isset($addrArr[2] ) && ($addrArr[2]  !== '')?$addrArr[2] :''); ?>"></select>
                        </div>-->
                    </div>
                </div>

                <div class="clearfix"></div>
<!--                 <div class="form-group col-sm-6 col-xs-6">
                    <label for="c-ctm_pass" class="control-label col-xs-4 col-sm-4 col-style dislocationTop"><?php echo __('Ctm_pass'); ?>:</label>
                    <div class="col-xs-8 col-sm-8 col-style">
                        <input id="c-ctm_pass" class="form-control" name="row[ctm_pass]" type="text" value="">
                    </div>
                </div> -->
                <div class="clearfix"></div>
                
                <div class="form-group col-sm-12 col-xs-12">
                    <label for="c-ctm_remark" class="control-label col-xs-3 col-sm-2 col-style"><?php echo __('ctm_remark'); ?>:</label>
                    <div class="col-xs-8 col-sm-8 col-style">
                    <textarea readonly="readonly" id="c-ctm_remark" class="form-control summernote" rows="3" name="row[ctm_remark]"><?php echo htmlspecialchars($row['ctm_remark']); ?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-2">
                        参考照
                    </label>
                    <div class="col-xs-12 col-sm-8">
                        <ul class="list-group" id="customer-img-list">
                            <?php foreach($customerImgs as $customerImg): ?>
                            <li class="list-group-item">
                                <img src="<?php echo $customerImg->url; ?>" title="<?php echo $customerImg->label; ?>" class="img-responsive" id="customer-img-<?php echo $customerImg->id; ?>" />
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="clearfix">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="extra">
            <div class="panel-body">
                <div class="form-group col-sm-6 col-xs-6">
                    <label for="c-ctm_company" class="control-label col-xs-4 col-sm-4 col-style"><?php echo __('Ctm_company'); ?>:</label>
                    <div class="col-xs-8 col-sm-8 col-style">
                        <input id="c-ctm_company" class="form-control" name="row[ctm_company]" type="text" value="<?php echo $row['ctm_company']; ?>" readonly="readonly">
                    </div>
                </div>
                <div class="form-group col-sm-6 col-xs-6">
                    <label for="c-ctm_job" class="control-label col-xs-4 col-sm-4 col-style"><?php echo __('Ctm_job'); ?>:</label>
                    <div class="col-xs-8 col-sm-8 col-style">
                        <!-- <input id="c-ctm_job" class="form-control" name="row[ctm_job]" type="text" value="<?php echo $row['ctm_job']; ?>"> -->
                        <!-- jobList -->
                        <?php echo build_select('row[ctm_job]', $jobList, $row['ctm_job'], ['class'=>'form-control selectpicker', 'required'=>'','disabled'=>'disabled']); ?>
                    </div>
                </div>
                <div class="form-group col-sm-6 col-xs-6">
                    <label for="c-ctm_zip" class="control-label col-xs-4 col-sm-4 col-style"><?php echo __('Ctm_zip'); ?>:</label>
                    <div class="col-xs-8 col-sm-8 col-style">
                        <input id="c-ctm_zip" class="form-control" name="row[ctm_zip]" type="text" value="<?php echo $row['ctm_zip']; ?>" readonly="readonly">
                    </div>
                </div>
                <div class="form-group col-sm-6 col-xs-6">
                    <label for="c-ctm_email" class="control-label col-xs-4 col-sm-4 col-style"><?php echo __('Ctm_email'); ?>:</label>
                    <div class="col-xs-8 col-sm-8 col-style">
                        <input id="c-ctm_email" class="form-control" name="row[ctm_email]" type="text" value="<?php echo $row['ctm_email']; ?>" readonly="readonly">
                    </div>
                </div>
                <div class="form-group col-sm-6 hidden">
                    <label for="c-ctm_qq" class="control-label col-xs-4 col-sm-4 col-style"><?php echo __('Ctm_qq'); ?>:</label>
                    <div class="col-xs-8 col-sm-8 col-style">
                        <input id="c-ctm_qq" class="form-control" readonly name="row[ctm_qq]" type="text" value="<?php echo $row['ctm_qq']; ?>" readonly="readonly">
                    </div>
                </div>
                <div class="form-group col-sm-6 hidden">
                    <label for="c-ctm_wxid" class="control-label col-xs-4 col-sm-4 col-style"><?php echo __('Ctm_wxid'); ?>:</label>
                    <div class="col-xs-8 col-sm-8 col-style">
                        <input id="c-ctm_wxid" class="form-control" readonly name="row[ctm_wxid]" type="text" value="<?php echo $row['ctm_wxid']; ?>" readonly="readonly">
                    </div>
                </div>
                <!-- 定金 -->
                <div class="clearfix"></div>
                <div class="form-group col-xs-6 col-sm-6">
                    <label for="c-ctm_depositamt" class="control-label col-xs-4 col-sm-4 col-style"><?php echo __('Ctm_depositamt'); ?>:</label>
                    <div class="col-xs-8 col-sm-8 col-style">
                        <label class="control-label" ><?php echo $row['ctm_depositamt']; ?></label>
                       </div>
                </div>
                <div class="form-group col-xs-12 col-sm-6">
                    <label for="c-ctm_coupamt" class="control-label col-xs-3 col-sm-4"><?php echo __('ctm_coupamt'); ?>:</label>
                        <label class="control-label"><?php echo $row['ctm_coupamt']; ?></label>
                </div>
                <div class="form-group col-xs-6 col-sm-6">
                    <label for="c-ctm_salamt" class="control-label col-xs-4 col-sm-4 col-style"><?php echo __('Ctm_salamt'); ?>:</label>
                    <div class="col-xs-8 col-sm-8 col-style">
                        <label class="control-label" ><?php echo $row['ctm_salamt']; ?></label>
                       </div>
                </div>
                <div class="form-group col-xs-6 col-sm-6">
                    <label for="c-ctm_rank_points" class="control-label col-xs-4 col-sm-4 col-style"><?php echo __('ctm_rank_points'); ?>:</label>
                    <div class="col-xs-8 col-sm-8 col-style">
                        <label class="control-label" ><?php echo $row['ctm_rank_points']; ?></label>
                       </div>
                </div>
                <div class="form-group col-xs-6 col-sm-6">
                    <label for="c-ctm_pay_points" class="control-label col-xs-4 col-sm-4 col-style"><?php echo __('ctm_pay_points'); ?>:</label>
                    <div class="col-xs-8 col-sm-8 col-style">
                        <label class="control-label" ><?php echo $row['ctm_pay_points']; ?></label>
                       </div>
                </div>
               <div class="form-group col-sm-6 col-xs-12">
                <label for="c-ctm_ifrevmail" class="control-label col-xs-4 col-sm-4 col-style"><?php echo __('Ctm_ifrevmail'); ?>:</label>
                    <div class="col-xs-8 col-sm-8 col-style">
                        <input id="c-ctm_ifrevmail" class="form-control" name="row[ctm_ifrevmail]" readonly type="hidden" value="<?php echo $row['ctm_ifrevmail']; ?>">
                        <label class="control-label ">
                            <?php if(($row['ctm_ifrevmail'])): ?>
                                <?php echo __('Status_yes'); else: ?>
                                <?php echo __('Status_no'); endif; ?>
                        </label>
                    </div>
                </div>
               <div class="form-group col-sm-6 col-xs-12">
                    <label for="c-ctm-ifbirth" class="control-label col-xs-4 col-sm-4 col-style"><?php echo __('Ctm_ifbirth'); ?>:</label>
                    <div class="col-xs-8 col-sm-8 col-style">
                        <input id="c-ctm_ifbirth" class="form-control" name="row[ctm_ifbirth]" readonly type="hidden" value="<?php echo $row['ctm_ifbirth']; ?>">
                        <label class="control-label ">
                        <?php if(($row['ctm_ifbirth'])): ?>
                            <?php echo __('Status_yes'); else: ?>
                            <?php echo __('Status_no'); endif; ?>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <!-- consult history -->
        <div class="tab-pane fade" id="conHistory">
            <input type="hidden" id="conHistory-ids" value="<?php echo $row['ctm_id']; ?>" />
            <?php if(empty($bool) && $row['ctm_first_tool_id'] == 0 && $auth->check('customer/customer/firstToolIdApply')): ?>
            <a href="javascript:;" class="btn btn-success btn-add" style="float: left;margin: 5px 0;" id="firstToolId" value="<?php echo $row['ctm_id']; ?>"><i class="fa fa-plus"></i>首次受理工具更改</a>
            <?php endif; ?>
            <div class="widget-body no-padding">
                <table id="conHistory-table" class="table table-striped table-bordered table-hover" 
                       width="100%">
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="osconHistory">
            <input type="hidden" id="osconHistory-ids" value="<?php echo $row['ctm_id']; ?>" />
            <div class="widget-body no-padding">
                <table id="osconHistory-table" class="table table-striped table-bordered table-hover" 
                       width="100%">
                </table>
            </div>
        </div>
        <!-- 订单显示 -->
        <div class="tab-pane fade" id="orderHistory">
            <input type="hidden" id="orderHistory-ids" value="<?php echo $row['ctm_id']; ?>" />
                <div class="widget-body no-padding">
                  <a href="javascript:;" class="btn btn-primary btn-refresh btnRefresh" id="btn-refresh-order"><i class="fa fa-refresh"></i></a>
                <div style="line-height: 24px;font-size: 14px;float: left;padding-left: 5px; width: calc(100% - 140px);">
                    <h4>
                        <label class="control-label">
                            <?php echo __('item_pay_total'); ?>&nbsp;
                        </label>
                        <span class="his-item_pay_total text-success">
                            0
                        </span>
                        +
                        <label class="control-label">
                            <?php echo __('item_coupon_total'); ?>&nbsp;
                        </label>
                        <span class="text-warning his-item_coupon_total">
                            0
                        </span>
                        =
                        <label class="control-label">
                            <?php echo __('Item_total'); ?>&nbsp;
                        </label>
                        <span class="text-warning his-item_total">
                            0
                        </span>
                    </h4>
                    <!-- <h3>
                        <?php echo __('balance_f_total'); ?>&nbsp;
                        <span class="text-success his-balance_f_total">
                    </span>
                    </h3>
                    <label class="control-label">
                        <?php echo __('balance_pay_total'); ?>&nbsp;
                    </label>
                    <span class="text-warning his-balance_pay_total">
                    </span>
                    <label class="control-label">
                        <?php echo __('balance_deposit_total'); ?>&nbsp;
                    </label>
                    <span class="text-warning his-balance_deposit_total">
                    </span>
                    <label class="control-label">
                        <?php echo __('deposit_change_total'); ?>&nbsp;
                    </label>
                    <span class="text-warning his-deposit_change_total">
                    </span> -->
                </div>
                  	
                  <!-- <a href="javascript:;" class="btn btn-success btn-add btnRefresh" id="btn-createprojectorder" value="<?php echo $row['ctm_id']; ?>"><i class="fa fa-plus"></i><?php echo __('Create order'); ?></a> -->
                    <table id="orderHistory-table" class="table ordertable table-bordered" width="100%">
                    </table>
                     <h4 title="双击订购项目显示相应划扣记录">划扣记录:  <i class="fa fa-question-circle-o text-success"></i></h4>
                <div class="bootstrap-table">
                    <div class="fixed-table-container">
                        <div class="fixed-table-body">
                <table class="table table-bordered" id="h-deducted-table" width="100%">
                                    </table>
                                   </div>
                                  </div>
                                 </div>
                </div>
        </div>
        <div class="tab-pane fade" id="rvinfoHistory">
            <input type="hidden" id="rvinfoHistory-ids" value="<?php echo $row['ctm_id']; ?>" />
            <div class="widget-body no-padding">
                <a href="javascript:;" class="btn btn-primary btn-refresh btnRefresh" id="btn-refresh-rvinfo"><i class="fa fa-refresh"></i></a>
                <a href="javascript:;" class="btn btn-success btn-add" style="float: left;margin: 5px 0;" id="addRvinfoHistory" value="<?php echo $row['ctm_id']; ?>"><i class="fa fa-plus"></i> 添加</a>
                <!-- 回访计划 -->
               
                     <!-- 回访计划 -->
                    <select id="h_rvinfo_by_plan" class="form-control" style="">
                    <?php foreach($definedRvPlans as $key => $definedRvPlan): ?>
                        <option value="<?php echo $key; ?>"><?php echo $definedRvPlan; ?></option>
                    <?php endforeach; ?>
                    </select>
                    <a class="btn btn-success btn-add" href="javascript:;" id="add_rvinfo_by_plan" style="" data-customer_id="<?php echo $row->ctm_id; ?>">
                        <i class="fa fa-plus">
                        </i>
                        快速回访计划
                    </a>
                
                <a class="btn btn-default btn-add" href="javascript:;" id="add_rvtype" style="margin: 5px;" value="<?php echo $row['ctm_id']; ?>">
                    <i class="fa fa-plus">
                    </i>
                    添加回访计划
                </a>
                <?php if($row['ctm_status'] == 1  && $auth->check('customer/customer/invalidCustomer')): ?>
                <a class="btn btn-danger" href="javascript:;" id="ctmStatus" style="margin: 5px;" value="<?php echo $row['ctm_id']; ?>">
                    <i class="fa fa-plus">
                    </i>
                    废弃客户
                </a>
                <?php endif; ?>
                
               <!-- width="100%" -->
               <div class="bs-bars pull-left" style="margin-top: 8px;font-size: 14px;">
				  	<label class="control-label">顾客:</label>
				  	<label class="control-label text-success"><?php echo $row['ctm_name']; ?></label>
				  	<label class="control-label" style="margin-left: 10px;">联系电话:</label>
				  	<!-- <label class="control-label text-success"><?php echo $row['ctm_tel']; ?></label> -->
                    <?php if($admin['showMobile'] == '1'): ?>
                    <label class="control-label text-success"><?php echo $row['ctm_tel']; ?></label>
                    <?php else: ?>
                    <label class="control-label text-success"><?php echo getMaskString($row['ctm_tel']); ?></label>
                    <?php endif; ?>
				  	<label class="control-label" style="margin-left: 10px;">手机号码:</label>
				  	<!-- <label class="control-label text-success"><?php echo $row['ctm_mobile']; ?></label> -->
                    <?php if($admin['showMobile'] == '1'): ?>
                    <label class="control-label text-success"><?php echo $row['ctm_mobile']; ?></label>
                    <?php else: ?>
                    <label class="control-label text-success"><?php echo getMaskString($row['ctm_mobile']); ?></label>
                    <?php endif; ?>

				  </div>
                    <table id="rvinfoHistory-table" class="table table-striped table-bordered table-hover">
                    </table>
            </div>
        </div>
        <div class="tab-pane fade" id="hmOrderHistory">
            <div class="clearfix">
                <h3>
                    <?php echo __('hm_cpy_pay_total'); ?>:&nbsp;
                    <span class="hm_cpy_pay_total text-success">
                        0
                    </span>
                    &nbsp;&nbsp;
                    <small>
                        <?php echo __('hm_cpy_account_total'); ?>:&nbsp;
                        <span class="hm_cpy_account_total">
                            0
                        </span>
                    </small>
                </h3>
            </div>
            <div class="widget-body no-padding">
                <table class="table table-striped table-bordered table-hover" id="hmOrderHistory-table" width="100%">
                </table>
            </div>
        </div>
    </div>
    <div class="form-group layer-footer">
        <label class="control-label col-xs-3 col-sm-4 col-style"></label>
        <div class="col-xs-6 col-sm-8 col-style">
            <!-- <button type="submit" class="btn btn-success btn-embossed disabled"><?php echo __('OK'); ?></button>
            <button type="reset" class="btn btn-default btn-close"><?php echo __('Close'); ?></button> -->
            <button type="button" class="btn btn-default btn-embossed btn-close"><?php echo __('Close'); ?></button>
        </div>
    </div>
        <div class="form-group iframeFoot">

    </div>
</form>
<style>
	body{background-color: #fff;}
	#ribbon{display: none;}
</style>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
<script src="__CDN__/assets/js/require.js" data-main="__CDN__/assets/js/require-backend.js?v=<?php echo $site['version']; ?>"></script>
    </body>
</html>