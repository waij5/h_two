<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:73:"D:\wamp\www\h_two\public/../application/admin\view\dashboard\wmindex.html";i:1559806108;s:70:"D:\wamp\www\h_two\public/../application/admin\view\layout\default.html";i:1544673823;s:67:"D:\wamp\www\h_two\public/../application/admin\view\common\meta.html";i:1544673823;s:69:"D:\wamp\www\h_two\public/../application/admin\view\common\script.html";i:1544673823;}*/ ?>
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
            <!-- <em>
                产品过期预警预警
            </em> -->
            未来六个月内产品过期情况
        </div>
        <!-- <ul class="nav nav-tabs">
            
            <li class="active">
                <a data-toggle="tab" href="#expired">
                    产品过期预警(<?php echo $expiredNum; ?>)
                </a>
            </li>
        </ul> -->
    </div>
    <div class="panel-body">

        <div class="commonsearch-table">
            <form class="form-inline  nice-validator nice-validator n-default" action="<?php echo url('Dashboard/wmindex'); ?>" id="f-commonsearch" role="form" method="post">
                <fieldset style="text-align: left;">
                    
                    <div class="form-group dislocationAll">
                        <label for="p_name" class="control-label labelLocation">产品名称</label>
                        <input type="text" name="p_name" class="form-control clear" size="8" value="<?php echo \think\Request::instance()->param('p_name'); ?>">
                    </div>

                    <div class="form-group dislocationAll">
                        <label for="lotnum" class="control-label labelLocation">批号</label>
                        <input type="text" name="lotnum" class="form-control clear" size="8" value="<?php echo \think\Request::instance()->param('lotnum'); ?>">
                    </div>
                    
                    <div class="form-group dislocationAll">
                        <label for="dept" class="control-label labelLocation">所属仓库</label>
                        
                        <select id="depot_id"  class="form-control clear" name="depot_id">
                            <option value='' >--- 请选择 ---</option>
                            <?php if(is_array($depotList) || $depotList instanceof \think\Collection || $depotList instanceof \think\Paginator): if( count($depotList)==0 ) : echo "" ;else: foreach($depotList as $key=>$vo): ?>
                            <option  value="<?php echo $vo['id']; ?>" <?php if(in_array(($vo['id']), is_array(\think\Request::instance()->param('depot_id'))?\think\Request::instance()->param('depot_id'):explode(',',\think\Request::instance()->param('depot_id')))): ?>selected<?php endif; ?>><?php echo $vo['name']; ?></option>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </div>

                    
                    <div class="form-group" style="">
                        <div class="col-sm-12 text-center">
                            <button type="submit" class="btn btn-success dislocationRight">提交</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>

        <div id="toolbar" class="toolbar">
            <a href="javascript:;" class="btn btn-primary btn-refresh" onclick="window.location.reload()"><i class="fa fa-refresh"></i> </a> &nbsp;
            <!-- <a href="javascript:;" class="btn btn-primary" id="isPrint">打印</a> -->
            
        </div>


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