<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:76:"D:\wamp\www\h_two\public/../application/admin\view\stat\dailystat\index.html";i:1544673822;s:70:"D:\wamp\www\h_two\public/../application/admin\view\layout\default.html";i:1544673823;s:67:"D:\wamp\www\h_two\public/../application/admin\view\common\meta.html";i:1544673823;s:69:"D:\wamp\www\h_two\public/../application/admin\view\common\script.html";i:1544673823;}*/ ?>
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
                                <div class="form-group dislocationAll">
                                    <label for="createtime" class="control-label labelLocation"><?php echo __('stat_date'); ?></label>
                                    <input class="form-control datetimepicker  forbid-timestamp" data-date-format="YYYY-MM-DD" data-use-current="true" name="stat_date_start" type="text" value="<?php echo $startDate; ?>" id="stat_date_start"> ~ <input  class="form-control datetimepicker  forbid-timestamp" data-date-format="YYYY-MM-DD" data-use-current="true" name="stat_date_end" type="text" value="<?php echo $endDate; ?>" id="stat_date_end">
                                </div>
                                <div class="form-group dislocationAll">
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-success dislocationRight"><?php echo __('Submit'); ?></button>
                                        <button type="reset" class="btn btn-default"><?php echo __('Reset'); ?></button>
                                        <button type="button" class="btn btn-default" id="btn-export"><?php echo __('Export'); ?></button>
                                    </div>
                                </div> 
                            </fieldset>
                        </form>
                    </div>
                    <hr />
                    <table id="table" class="table table-striped table-bordered table-hover scrolltable" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center" colspan="3"><?php echo __('Calculated Records'); ?></th>
                                <th class="text-center" colspan="2"><?php echo __('Total Amount'); ?></th>
                                <th class="text-center" colspan="9"><?php echo __('Classified balance detail'); ?></th>
                            </tr>
                            <tr>
                                <th><?php echo __('stat_date'); ?></th>
                                <th><?php echo __('pay_total'); ?></th>
                                <th><?php echo __('balance_count'); ?></th>
                                <th><?php echo __('in_pay_total'); ?></th>

                                <th><?php echo __('in_cash_pay_total'); ?></th>
                                <th><?php echo __('in_card_pay_total'); ?></th>
                                <th><?php echo __('in_wechatpay_pay_total'); ?></th>
                                <th><?php echo __('in_alipay_pay_total'); ?></th>
                                <th><?php echo __('in_other_pay_total'); ?></th>

                                <th><?php echo __('stat_coupon_cost_total'); ?></th>
                                <!-- <th><?php echo __('stat_coupon_total'); ?></th> -->

                                <th><?php echo __('stat_deposit_total'); ?></th>

                                <th><?php echo __('stat_refund_total'); ?></th>

                                <!-- <th><?php echo __('stat_adjust_income_total'); ?></th> -->
                                <!-- <th><?php echo __('stat_adjust_outpay_total'); ?></th> -->
                            </tr>
                        </thead>
                        <tbody style="max-height: 200px; overflow-y: overlay;">
                        <?php foreach($result['rows'] as $key => $row): ?>
                            <tr>
                                <td><b><?php echo $row['stat_date']; ?></b></td>
                                <td>
                                    <span class="text-warning">
                                        <?php echo $row['pay_total']; ?>
                                    </span>
                                </td>
                                <td><?php echo $row['balance_count']; ?></td>
                                <td>
                                    <span class="text-warning">
                                        <?php echo $row['in_pay_total']; ?>
                                    </span>
                                </td>

                                <td><?php echo $row['in_cash_pay_total']; ?></td>
                                <td><?php echo $row['in_card_pay_total']; ?></td>
                                <td><?php echo $row['in_wechatpay_pay_total']; ?></td>
                                <td><?php echo $row['in_alipay_pay_total']; ?></td>
                                <td><?php echo $row['in_other_pay_total']; ?></td>

                                <td><?php echo $row['coupon_cost']; ?></td>
                                <td><?php echo $row['deposit_total']; ?></td>

                                <td>
                                    <span class="text-warning">
                                        <?php echo $row['out_pay_total']; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                        <tfoot style="position: absolute; bottom: 0; background-color: #5a5557; color: #fff;">
                            <tr>
                                <th>总额合计</th>
                                <th>
                                    <span class="text-warning">
                                        <?php echo $summary['pay_total']; ?>
                                    </span>
                                </th>
                                <th><?php echo $summary['balance_count']; ?></th>
                                <th><?php echo $summary['in_pay_total']; ?></th>

                                <th><?php echo $summary['in_cash_pay_total']; ?></th>
                                <th><?php echo $summary['in_card_pay_total']; ?></th>
                                <th><?php echo $summary['in_wechatpay_pay_total']; ?></th>
                                <th><?php echo $summary['in_alipay_pay_total']; ?></th>
                                <th><?php echo $summary['in_other_pay_total']; ?></th>

                                <th><?php echo $summary['coupon_cost']; ?></th>
                                <th><?php echo $summary['deposit_total']; ?></th>

                                <th><?php echo $summary['out_pay_total']; ?></th>
                            </tr>
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