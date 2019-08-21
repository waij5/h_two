<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:80:"D:\wamp\www\h_two\public/../application/admin\view\stat\osconsultrate\index.html";i:1559804939;s:70:"D:\wamp\www\h_two\public/../application/admin\view\layout\default.html";i:1544673823;s:67:"D:\wamp\www\h_two\public/../application/admin\view\common\meta.html";i:1544673823;s:69:"D:\wamp\www\h_two\public/../application/admin\view\common\script.html";i:1544673823;}*/ ?>
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
                                <!-- <div class="form-group dislocationAll">
                                    <label for="customer_id" class="control-label labelLocation"><?php echo __('Customer'); ?></label>
                                    <input type="hidden" name="customer_id" class="form-control" value="" id="field_ctm_id" />
                                    <a href="javascript:;" id="a-search-customer">
                                        <input type="text" readonly id="field_ctm_name" class="form-control" />
                                    </a>
                                    <a href="javascript:;" class="btn btn-danger btn-del" id="btn-customer-clear">
                                        <i class="fa fa-trash"></i>清除
                                    </a>
                                </div> -->
                                <div class="form-group dislocationAll">
                                    <label for="osc.admin_id" class="control-label" style="float: left; padding: 7px 10px;">
                                        <?php echo __('Osconsult_admin'); ?>
                                    </label>
                                    <select name="osc.admin_id" class="selectpicker show-tick form-control" data-live-search="true">
                                        <option value="">--</option>
                                        <?php foreach($briefAdminList as $key => $briefAdmin): ?>
                                        <option value="<?php echo $key; ?>"><?php echo $briefAdmin; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group dislocationAll">
                                    <label for="osc.cpdt_id" class="control-label searchPadding"><?php echo __('Cpdt_id'); ?></label>
                               
                                    <select class="selectpicker show-tick form-control" required="" name="osc.cpdt_id" id="osc.cpdt_id" data-live-search="true">
                                        <?php foreach($cpdtList as $key => $pdc): ?>
                                            <option value="<?php echo $key; ?>"><?php echo $pdc; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group dislocationAll">
                                    <label for="Department_id" class="control-label labelLocation"><?php echo __('Osconsult Department'); ?></label>
                                    <select name="osc.dept_id" class="selectpicker show-tick form-control" data-live-search="true">
                                        <option value=""><?php echo __('None'); ?></option>
                                        <?php foreach($deptList as $key => $val): ?>
                                        <option value="<?php echo $val['dept_id']; ?>"><?php echo $val['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group dislocationAll">
                                    <label for="osc.osc_type" class="control-label searchPadding"><?php echo __('Osc_type'); ?></label>
                                    <select class="form-control" required="" name="osc.osc_type" id="osc.osc_type">
                                        <option value=""><?php echo __('All'); ?></option>
                                        <?php foreach($ocsTypeArr as $key => $oscType): ?>
                                        <option value="<?php echo $key; ?>"><?php echo $oscType; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group dislocationAll">
                                    <label for="osc.createtime" class="control-label labelLocation"><?php echo __('Recept date'); ?></label>
                                    <input class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" name="osc.createtime_start" type="text" value="<?php echo $startDate; ?>" id="osc.createtime_start"> ~ <input  class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" name="osc.createtime_end" type="text" value="<?php echo $endDate; ?>" id="osc.createtime_end">
                                </div>
                                <div class="form-group dislocationAll">
                                    <div class="col-sm-12 text-center">
                                        <button type="button" class="btn btn-success dislocationRight" id="btn-submit-1"><?php echo __('Submit'); ?></button>
                                        <button type="reset" class="btn btn-default"><?php echo __('Reset'); ?></button>
                                        <button type="button" class="btn btn-default" id="btn-export"><?php echo __('Export'); ?></button>
                                    </div>
                                </div> 
                            </fieldset>
                        </form>
                    </div>
                    <div id="toolbar" class="table table-striped table-bordered table-hover" width="100%">
                    </div>
                    <div class="form-group">
                        <h2 class="text-center">
                            <?php echo __('All business osconsult statistic'); ?>
                        </h2>
                    </div>
                    <table id="table" class="table table-striped table-bordered table-hover scrolltable" width="100%">
                        <thead>
                            <tr>
                                <th><?php echo __('No.'); ?></th>
                                <th><?php echo __('Osconsult_admin'); ?></th>
                                <th><?php echo __('First visit count'); ?></th>
                                <th><?php echo __('Success count'); ?></th>
                                <th><?php echo __('Success rate'); ?></th>
                                <th><?php echo __('First visit total'); ?></th>

                                <th><?php echo __('Return visit count'); ?></th>
                                <th><?php echo __('Success count'); ?></th>
                                <th><?php echo __('Success rate'); ?></th>
                                <th><?php echo __('Return visit total'); ?></th>

                                <th><?php echo __('Reconsume count'); ?></th>
                                <th><?php echo __('Success count'); ?></th>
                                <th><?php echo __('Success rate'); ?></th>
                                <th><?php echo __('Reconsume visit total'); ?></th>
                                <!-- 复查 -->
                                <th><?php echo __('Review count'); ?></th>
                                <th><?php echo __('Success count'); ?></th>
                                <th><?php echo __('Success rate'); ?></th>
                                <th><?php echo __('Review total'); ?></th>
                                <!-- 其他 -->
                                <th><?php echo __('other count'); ?></th>
                                <th><?php echo __('Success count'); ?></th>
                                <th><?php echo __('Success rate'); ?></th>
                                <th><?php echo __('other total'); ?></th>

                                <th><?php echo __('Reception total'); ?></th>
                                <th><?php echo __('Success total'); ?></th>
                                <th><?php echo __('Success total rate'); ?></th>
                                <th><?php echo __('Reception total rate'); ?></th>

                                <th><?php echo __('Consumption total'); ?></th>
                                <th><?php echo __('Percent'); ?></th>
                                <th><?php echo __('Consumption per person'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        </tfoot>
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