<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:83:"D:\wamp\www\h_two\public/../application/admin\view\stat\benefit\operatebenefit.html";i:1559804939;s:70:"D:\wamp\www\h_two\public/../application/admin\view\layout\default.html";i:1544673823;s:67:"D:\wamp\www\h_two\public/../application/admin\view\common\meta.html";i:1544673823;s:69:"D:\wamp\www\h_two\public/../application/admin\view\common\script.html";i:1544673823;}*/ ?>
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
                                <input name="item_total_times" type="hidden" value="0"/>
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding" for="createtime">
                                        <?php echo __('deduct time'); ?>
                                    </label>
                                    <input class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" name="rec.createtime_start" type="text" value="">
                                        ~
                                        <input class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" name="rec.createtime_end" type="text" value="">
                                        </input>
                                    </input>
                                </div>
                                 <div class="form-group dislocationAll">
                                    <label for="items.pro_name" class="control-label searchPadding"><?php echo __('pro_name'); ?></label>
                                    <input type="text" class="form-control" name="items.pro_name" value="" placeholder="<?php echo __('pro_name'); ?>" id="pro_name">
                                </div>
                                <?php if($admin['position'] > 0): ?>
                                <!--职员-->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding">
                                        <?php echo __('Staff'); ?>
                                    </label>
                                    <!--                                     <select class="form-control" name="staff_rec.admin_id">
                                        <option value="">--</option>
                                    <?php foreach($briefAdminList as $adminId => $nickname): ?>
                                        <option value="<?php echo $adminId; ?>"><?php echo htmlspecialchars($nickname); ?></option>
                                    <?php endforeach; ?>
                                    </select> -->
                                    <select class="selectpicker show-tick form-control" data-live-search="true" name="staff_rec.admin_id">
                                        <option value="">
                                            --
                                        </option>
                                        <?php if($showAllDepts): foreach($adminList as $adminId => $adminInfo): ?>
                                        <option value="<?php echo $adminId; ?>">
                                            <?php echo htmlspecialchars($adminInfo['username']); ?>-<?php echo htmlspecialchars($adminInfo['nickname']); ?>
                                        </option>
                                        <?php endforeach; else: foreach($adminList as $adminId => $adminInfo): if((in_array($adminInfo['dept_id'], $deptIds))): ?>
                                        <option value="<?php echo $adminId; ?>">
                                            <?php echo htmlspecialchars($adminInfo['username']); ?>-<?php echo htmlspecialchars($adminInfo['nickname']); ?>
                                        </option>
                                        <?php endif; endforeach; endif; ?>
                                    </select>
                                </div>

                                <!--职员部门-->
                                <div class="form-group dislocationAll">
                                    <label class="control-label searchPadding">所属部门</label>
                                    <select class="selectpicker show-tick form-control" data-live-search="true" name="admin.dept_id">
                                        <?php if($showAllDepts): ?>
                                        <option value=""><?php echo __('All'); ?></option>
                                        <?php foreach($deptList as $key => $val): ?>
                                            <option value="<?php echo $val['dept_id']; ?>"><?php echo $val['name']; ?></option>
                                        <?php endforeach; else: foreach($deptList as $key => $val): if((in_array($val['dept_id'], $deptIds))): ?>
                                            <option value="<?php echo $val['dept_id']; ?>"><?php echo $val['name']; ?></option>
                                            <?php endif; endforeach; endif; ?>
                                    </select>
                                </div>
                                <?php else: ?>
                                <div class="hidden">
                                    <input type="hidden"  value="<?php echo $admin['id']; ?>" name="staff_rec.admin_id" class="show-tick form-control" />
                                    <input type="hidden"  value="" name="admin.dept_id" class="show-tick form-control" />
                                </div>
                                <?php endif; ?>

                                <div class="form-group dislocationAll">
                                    <div class="col-sm-12 text-center">
                                        <button class="btn btn-success dislocationRight" type="sumit">
                                            <?php echo __('Submit'); ?>
                                        </button>
                                        <button class="btn btn-default dislocationRight" type="reset">
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
                    <div class="table table-striped table-bordered table-hover table-condensed" id="toolbar" width="100%">
                    </div>
                    <div class="form-group">
                        <h2 class="text-center">
                            <?php echo __('Work detail'); ?>
                        </h2>
                    </div>
                    <div id="consumTable" style="position: relative;overflow-y: auto;">
                        <div>
                            <h3>
                                <small>
                                    <span>
                                        <?php echo __('total_deduct_times'); ?>:
                                    </span>
                                    <span class="text-success" id="h_total_deduct_times">
                                        0
                                    </span>
                                    <span>
                                        <?php echo __('total_deduct_amount'); ?>:
                                    </span>
                                    <span class="text-success" id="h_total_deduct_amount">
                                        0
                                    </span>
                                    <span>
                                        <?php echo __('total_deduct_benefit_amount'); ?>:
                                    </span>
                                    <span class="text-success" id="h_total_deduct_benefit_amount">
                                        0
                                    </span>
                                    <span>
                                        <?php echo __('total_final_amount'); ?>:
                                    </span>
                                    <span class="text-success" id="h_total_final_amount">
                                        0
                                    </span>
                                    <span>
                                        <?php echo __('total_final_benefit_amount'); ?>:
                                    </span>
                                    <span class="text-success" id="h_total_final_benefit_amount">
                                        0
                                    </span>
                                </small>
                            </h3>
                        </div>
                        <table class="table table-striped table-bordered table-hover" id="table" width="100%">
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