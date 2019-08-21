<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:71:"D:\wamp\www\h_two\public/../application/admin\view\dashboard\index.html";i:1559804939;s:70:"D:\wamp\www\h_two\public/../application/admin\view\layout\default.html";i:1544673823;s:67:"D:\wamp\www\h_two\public/../application/admin\view\common\meta.html";i:1544673823;s:69:"D:\wamp\www\h_two\public/../application/admin\view\common\script.html";i:1544673823;}*/ ?>
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
                                <style type="text/css">
    .sm-st {
        background:#fff;
        padding:20px;
        -webkit-border-radius:3px;
        -moz-border-radius:3px;
        border-radius:3px;
        margin-bottom:20px;
        -webkit-box-shadow: 0 1px 0px rgba(0,0,0,0.05);
        box-shadow: 0 1px 0px rgba(0,0,0,0.05);
    }
    .sm-st-icon {
        width:60px;
        height:60px;
        display:inline-block;
        line-height:60px;
        text-align:center;
        font-size:30px;
        background:#eee;
        -webkit-border-radius:5px;
        -moz-border-radius:5px;
        border-radius:5px;
        float:left;
        margin-right:10px;
        color:#fff;
    }
    .sm-st-info {
        font-size:12px;
        padding-top:2px;
    }
    .sm-st-info span {
        display:block;
        font-size:24px;
        font-weight:600;
    }
    .orange {
        background:#fa8564 !important;
    }
    .tar {
        background:#45cf95 !important;
    }
    .sm-st .green {
        background:#86ba41 !important;
    }
    .pink {
        background:#AC75F0 !important;
    }
    .yellow-b {
        background: #fdd752 !important;
    }
    .stat-elem {

        background-color: #fff;
        padding: 18px;
        border-radius: 40px;

    }

    .stat-info {
        text-align: center;
        background-color:#fff;
        border-radius: 5px;
        margin-top: -5px;
        padding: 8px;
        -webkit-box-shadow: 0 1px 0px rgba(0,0,0,0.05);
        box-shadow: 0 1px 0px rgba(0,0,0,0.05);
        font-style: italic;
    }

    .stat-icon {
        text-align: center;
        margin-bottom: 5px;
    }

    .st-red {
        background-color: #F05050;
    }
    .st-green {
        background-color: #27C24C;
    }
    .st-violet {
        background-color: #7266ba;
    }
    .st-blue {
        background-color: #23b7e5;
    }

    .stats .stat-icon {
        color: #28bb9c;
        display: inline-block;
        font-size: 26px;
        text-align: center;
        vertical-align: middle;
        width: 50px;
        float:left;
    }

    .stat {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: inline-block;
        margin-right: 10px; }
    .stat .value {
        font-size: 20px;
        line-height: 24px;
        overflow: hidden;
        text-overflow: ellipsis;
        font-weight: 500; }
    .stat .name {
        overflow: hidden;
        text-overflow: ellipsis; }
    .stat.lg .value {
        font-size: 26px;
        line-height: 28px; }
    .stat.lg .name {
        font-size: 16px; }
    .stat-col .progress {height:2px;}
    .stat-col .progress-bar {line-height:2px;height:2px;}

    .item {
        padding:30px 0;
    }
    .red{color: red}
    thead th{text-align: center}
</style>
<div class="panel panel-default panel-intro">
    <div class="panel-heading">
        <div class="panel-lead">
            <em>
                控制台（Dashboard）
            </em>
            用于展示当前系统中的统计数据、统计报表及重要实时数据
        </div>
        <ul class="nav nav-tabs">
            <li>
                <a data-toggle="tab" href="#dashboard">
                    控制台
                </a>
            </li>
            <li>
                <a data-toggle="tab" href="#msg">
                    消息
                </a>
            </li>
           <!--  <li>
                <a data-toggle="tab" href="#ifbirth">
                    生日提醒
                </a>
            </li> -->
            <li class="active">
                <a data-toggle="tab" href="#expired">
                    产品过期预警(<?php echo $expiredNum; ?>)
                </a>
            </li>
        </ul>
    </div>
    <div class="panel-body">
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade  active in" id="expired">
            <strong style="font-size: 15px">库存产品过期预警列表：</strong>

                <div id="consumTable" style="position: relative;height: 650px;overflow-y: auto;text-align: center;">
                    
                        <table id="table" class=" table table-striped table-bordered table-hover scrolltable" style="width: 100%;table-layout: fixed;" >
                        
                            <thead >
                                <tr style="border:1px solid;">
                                    <th>产品编号</th>
                                    <th>产品名称</th>
                                    <th>单位</th>
                                    <th>规格</th>
                                    <th>所属仓库</th>
                                    <th class="red">批号</th>
                                    <th>库存</th>
                                    <th>生产日期</th>
                                    <th>失效日期</th>
                                    <th>供应商</th>
                                    <th>生产厂家</th>
                                </tr>
                            </thead>
                            <?php if(!empty($data)): if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): if( count($data)==0 ) : echo "" ;else: foreach($data as $ka=>$va): if(is_array($va) || $va instanceof \think\Collection || $va instanceof \think\Paginator): if( count($va)==0 ) : echo "" ;else: foreach($va as $ko=>$vo): ?>
                                        <tr style="border:1px solid;" <?php if($vo['letime']<time()): ?>  class="red"<?php endif; ?>>
                                            <td><?php if($counts[$ka]-$ko <$counts[$ka]): else: ?> <?php echo $vo['pro_code']; endif; ?></td>
                                            <td><?php if($counts[$ka]-$ko <$counts[$ka]): else: ?> <?php echo $vo['pro_name']; endif; ?></td>
                                            <td><?php if($counts[$ka]-$ko <$counts[$ka]): else: ?> <?php echo $vo['uname']; endif; ?></td>
                                            <td><?php if($counts[$ka]-$ko <$counts[$ka]): else: ?> <?php echo $vo['pro_spec']; endif; ?></td>
                                            <td><?php if($counts[$ka]-$ko <$counts[$ka]): else: ?> <?php echo $vo['dname']; endif; ?></td>
                                            <td><?php echo $vo['lotnum']; ?></td>
                                            <td><?php echo $vo['lstock']; ?></td>
                                            <td><?php if($vo['lstime']>0): ?><?php echo date('Y-m-d',$vo['lstime']); endif; ?></td>
                                            <td><?php if($vo['letime']>0): ?><?php echo date('Y-m-d',$vo['letime']); endif; ?></td>
                                            <td><?php echo $vo['sup_name']; ?></td>
                                            <td><?php echo $vo['lproducer']; ?></td>
                                            
                                        </tr>
                                    <?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; endif; ?> 
                        </table>
                              
            </div>
        </div>

            <div class="tab-pane fade hidden" id="ifbirth">
                <table class="table table-striped table-responsive" width="100%" id="customer-table" data-operate-edit="<?php echo $auth->check('customer/customer/edit'); ?>" data-operate-del="<?php echo $auth->check('customer/customer/del'); ?>"></table>
            </div>
            <div class="tab-pane fade" id="msg">
                <div class="commonsearch-table hidden">
                    <form action="" class="form-inline form-commonsearch nice-validator n-default" id="f-commonsearch" method="post" novalidate="novalidate" role="form">
                        <fieldset>
                            <div class="form-group dislocationAll">
                                <label class="control-label labelLocation" for="msg_id">
                                    <?php echo __('Msg_id'); ?>
                                </label>
                                <input class="form-control" id="msg_id" name="msg_id" placeholder="<?php echo __('Msg_id'); ?>" type="text" value="">
                                </input>
                            </div>
                            <div class="form-group dislocationAll">
                                <label class="control-label labelLocation" for="msg_type">
                                    <?php echo __('msg_type'); ?>
                                </label>
                                <select class="form-control" name="msg_type">
                                    <option value="">
                                        <?php echo __('All'); ?>
                                    </option>
                                    <?php foreach($msgTypeList as $key => $mstType): ?>
                                    <option value="<?php echo $key; ?>">
                                        <?php echo $mstType; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group dislocationAll">
                                <label class="control-label labelLocation" for="msg_title">
                                    <?php echo __('msg_title'); ?>
                                </label>
                                <input class="form-control" id="msg_title" name="msg_title" placeholder="<?php echo __('msg_title'); ?>" type="text" value="">
                                </input>
                            </div>
                            <!-- <div class="clearfix"></div> -->
                            <div class="form-group dislocationAll">
                                <label class="control-label labelLocation" for="createtime">
                                    <?php echo __('Createtime'); ?>
                                </label>
                                <input class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" id="createtime_start" name="createtime_start" type="text" value="">
                                    ~
                                    <input class="form-control datetimepicker" data-date-format="YYYY-MM-DD" data-use-current="true" id="createtime_end" name="createtime_end" type="text" value="">
                                    </input>
                                </input>
                            </div>
                            <div class="form-group dislocationAll">
                                <div class="col-sm-12 text-center">
                                    <button class="btn btn-success dislocationRight" type="submit">
                                        提交
                                    </button>
                                    <button class="btn btn-default" type="reset">
                                        重置
                                    </button>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="toolbar" id="toolbar">
                    <a class="btn btn-primary btn-refresh" href="javascript:;">
                        <i class="fa fa-refresh">
                        </i>
                    </a>
                </div>
                <table class="table table-striped table-bordered table-hover" data-operate-del="<?php echo $auth->check('base/msg/del'); ?>" data-operate-edit="<?php echo $auth->check('base/msg/edit'); ?>" id="msg-table" width="100%">
                </table>
            </div>
            <div class="tab-pane fade" id="dashboard">
                <!-- 回访计划 -->
                <div class="col-sm-3 col-xs-6">
                    <div class="sm-st clearfix" id="rvtype">
                        <span class="sm-st-icon st-red">
                            <i class="fa fa-users">
                            </i>
                        </span>
                        <div class="sm-st-info">
                            <div>
                                回访计划
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 col-xs-6">
                    <div class="sm-st clearfix">
                        <span class="sm-st-icon st-green">
                            <i class="fa fa-cny">
                            </i>
                        </span>
                        <div class="sm-st-info">
                            <div>
                                本月营收总额:<?php echo $pay_total; ?>
                            </div>
                            <div>
                                本月订购项目初始营收总额:<?php echo $order_total; ?>
                            </div>
                        </div>
                    </div>
                </div>
               <!--  <div class="col-sm-3 col-xs-6">
                    <div class="sm-st clearfix">
                        <span class="sm-st-icon st-green">
                            <i class="fa fa-cny">
                            </i>
                        </span>
                        <div class="sm-st-info">
                            <div>
                                <?php echo $order_total; ?>
                            </div>
                            本月订购项目初始营收总额
                        </div>
                    </div>
                </div> -->
                <div class="col-sm-3 col-xs-6">
                    <div class="sm-st clearfix">
                        <span class="sm-st-icon st-green">
                            <i class="fa fa-cny">
                            </i>
                        </span>
                        <div class="sm-st-info">
                            <div>
                                <?php echo $deduct_amount; ?>
                            </div>
                            本月划扣总金额
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 col-xs-6">
                    <div class="sm-st clearfix">
                        <span class="sm-st-icon st-green">
                            <i class="fa fa-cny">
                            </i>
                        </span>
                        <div class="sm-st-info">
                            <div>
                                 增加:<?php echo $total; ?>  减少:<?php echo $deposit_total; ?>
                            </div>
                            <div>
                                本月定金变动总额:<?php echo $ctm_depositamt; ?>
                            </div>

                        </div>
                    </div>
                </div>
               <!--  <div class="col-sm-3 col-xs-6">
                    <div class="sm-st clearfix">
                        <span class="sm-st-icon st-green">
                            <i class="fa fa-cny">
                            </i>
                        </span>
                        <div class="sm-st-info">
                            <div>
                                增加:<?php echo $total; ?>  减少:<?php echo $deposit_total; ?>
                            </div>
                            本月定金变动额
                        </div>
                    </div>
                </div> -->
                <div class="col-sm-3 col-xs-6">
                    <div class="sm-st clearfix">
                        <span class="sm-st-icon st-red">
                            <i class="fa fa-users">
                            </i>
                        </span>
                        <div class="sm-st-info">
                            <div>
                                <?php echo $coc; ?>
                            </div>
                            本月分诊总人次
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 col-xs-6">
                    <div class="sm-st clearfix">
                        <span class="sm-st-icon st-red">
                            <i class="fa fa-users">
                            </i>
                        </span>
                        <div class="sm-st-info">
                            <div>
                                <?php echo $cst; ?>
                            </div>
                            本月网电总人次
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 col-xs-6">
                    <div class="sm-st clearfix">
                        <span class="sm-st-icon st-red">
                            <i class="fa fa-users">
                            </i>
                        </span>
                        <div class="sm-st-info">
                            <div>
                                已回访:<?php echo $rvinfo; ?>  未回访:<?php echo $rvinfoNo; ?>
                            </div>
                            本月回访总次数
                        </div>
                    </div>
                </div>
                <div class="clearfix">
                </div>
                <div class="row hidden">
                    <div class="col-lg-8">
                        <div id="echart" style="height:200px;width:100%;">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card sameheight-item stats">
                            <div class="card-block">
                                <div class="row row-sm stats-container">
                                    <div class="col-xs-6 stat-col">
                                        <div class="stat-icon">
                                            <i class="fa fa-rocket">
                                            </i>
                                        </div>
                                        <div class="stat">
                                            <div class="value">
                                                <?php echo $todayusersignup; ?>
                                            </div>
                                            <div class="name">
                                                今日注册
                                            </div>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-success" style="width: 20%">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 stat-col">
                                        <div class="stat-icon">
                                            <i class="fa fa-shopping-cart">
                                            </i>
                                        </div>
                                        <div class="stat">
                                            <div class="value">
                                                <?php echo $todayuserlogin; ?>
                                            </div>
                                            <div class="name">
                                                今日登录
                                            </div>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-success" style="width: 20%">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 stat-col">
                                        <div class="stat-icon">
                                            <i class="fa fa-line-chart">
                                            </i>
                                        </div>
                                        <div class="stat">
                                            <div class="value">
                                                <?php echo $todayorder; ?>
                                            </div>
                                            <div class="name">
                                                今日订单
                                            </div>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-success" style="width: 20%">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 stat-col">
                                        <div class="stat-icon">
                                            <i class="fa fa-users">
                                            </i>
                                        </div>
                                        <div class="stat">
                                            <div class="value">
                                                <?php echo $todayunsettleorder; ?>
                                            </div>
                                            <div class="name">
                                                未处理订单
                                            </div>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-success" style="width: 20%">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 stat-col">
                                        <div class="stat-icon">
                                            <i class="fa fa-list-alt">
                                            </i>
                                        </div>
                                        <div class="stat">
                                            <div class="value">
                                                <?php echo $sevendnu; ?>
                                            </div>
                                            <div class="name">
                                                七日新增
                                            </div>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-success" style="width: 20%">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 stat-col">
                                        <div class="stat-icon">
                                            <i class="fa fa-dollar">
                                            </i>
                                        </div>
                                        <div class="stat">
                                            <div class="value">
                                                <?php echo $sevendau; ?>
                                            </div>
                                            <div class="name">
                                                七日活跃
                                            </div>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-success" style="width: 20%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row hidden">
                    <div class="col-lg-4">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">
                                    温馨设置
                                </h3>
                            </div>
                            <div class="box-body">
                                test test
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="box">
                            <div class="box-header">
                                cccc
                            </div>
                            <div class="box-body">
                                test test
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="box">
                            <div class="box-header">
                                cccc
                            </div>
                            <div class="box-body">
                                test test
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top:15px;">
                    <div class="col-lg-12">
                        <h4>
                            详细统计
                        </h4>
                    </div>
                    <div class="col-xs-6 col-md-3">
                        <div class="panel bg-blue">
                            <div class="panel-body">
                                <div class="panel-title">
                                    <h5>
                                        金额统计
                                    </h5>
                                </div>
                                <div class="ibox-content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h1 class="no-margins">
                                                <?php echo $today_pay_total; ?>
                                            </h1>
                                            <div class="font-bold text-navy">
                                                <i class="fa fa-commenting">
                                                </i>
                                                <small>
                                                    当天营收总额
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h1 class="no-margins">
                                                <?php echo $today_order_total; ?>
                                            </h1>
                                            <div class="font-bold text-navy">
                                                <i class="fa fa-user">
                                                </i>
                                                <small>
                                                    当天订购项目初始营收总额
                                                </small>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-3">
                        <div class="panel bg-aqua-gradient">
                            <div class="panel-body">
                                <div class="ibox-title">
                                    <h5>
                                        金额统计
                                    </h5>
                                </div>
                                <div class="ibox-content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h1 class="no-margins">
                                                <?php if($today_deduct_amount == 0): ?> 0 <?php else: ?><?php echo $today_deduct_amount; endif; ?>
                                            </h1>
                                            <div class="font-bold text-navy">
                                                <i class="fa fa-user">
                                                </i>
                                                <small>
                                                    当天划扣总金额
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h1 class="no-margins">
                                                <?php if($today_ctm_depositamt == 0): ?> 0 <?php else: ?><?php echo $today_ctm_depositamt; endif; ?>
                                            </h1>
                                            <div class="font-bold text-navy">
                                                <i class="fa fa-commenting">
                                                </i>
                                                <small>
                                                    当天定金总变动额
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-3">
                        <div class="panel bg-purple-gradient">
                            <div class="panel-body">
                                <div class="ibox-title">
                                    <h5>
                                        动态统计
                                    </h5>
                                </div>
                                <div class="ibox-content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="no-margins">
                                                增加:<?php if($today_total == 0): ?> 0 <?php else: ?><?php echo $today_total; endif; ?>
                                            </div>
                                            <h1>
                                            </h1>
                                            <div class="no-margins">
                                                减少:<?php if($today_deposit_total == 0): ?> 0 <?php else: ?><?php echo $today_deposit_total; endif; ?>
                                            </div>
                                            <div class="font-bold text-navy">
                                                <i class="fa fa-commenting">
                                                </i>
                                                <small>
                                                    当天定金变动额
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="no-margins">
                                                已回访:<?php echo $today_rvinfo; ?>
                                            </div>
                                            <h1>
                                            </h1>
                                            <div class="no-margins">
                                                未回访:<?php if($today_rvinfoNo == 0): ?> 0 <?php else: ?><?php echo $today_rvinfoNo; endif; ?>
                                            </div>
                                            <div class="font-bold text-navy">
                                                <i class="fa fa-heart">
                                                </i>
                                                <small>
                                                    当天回访统计
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-3">
                        <div class="panel bg-green-gradient">
                            <div class="panel-body">
                                <div class="ibox-title">
                                    <h5>
                                        客户人次统计
                                    </h5>
                                </div>
                                <div class="ibox-content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h1 class="no-margins">
                                                <?php echo $today_coc; ?>
                                            </h1>
                                            <div class="font-bold text-navy">
                                                <i class="fa fa-commenting">
                                                </i>
                                                <small>
                                                    当天分诊人次
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h1 class="no-margins">
                                                <?php echo $today_cst; ?>
                                            </h1>
                                            <div class="font-bold text-navy">
                                                <i class="fa fa-user">
                                                </i>
                                                <small>
                                                    当天网电人次
                                                </small>
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
    <script>
        var Orderdata = {
    column: <?php echo json_encode(array_keys($paylist)); ?>,
            paydata: <?php echo json_encode(array_values($paylist)); ?>,
            createdata: <?php echo json_encode(array_values($createlist)); ?>,
    };
    </script>
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