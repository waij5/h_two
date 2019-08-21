<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:78:"D:\wamp\www\h_two\public/../application/admin\view\wmreport\depdraw\index.html";i:1566185070;s:70:"D:\wamp\www\h_two\public/../application/admin\view\layout\default.html";i:1544673823;s:67:"D:\wamp\www\h_two\public/../application/admin\view\common\meta.html";i:1544673823;s:69:"D:\wamp\www\h_two\public/../application/admin\view\common\script.html";i:1544673823;}*/ ?>
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
<style type="text/css">
.red{color: red}
#table tr td{border:1px black solid;word-wrap:break-word;text-align: center;vertical-align:middle}
#table tr th{border:1px black solid;text-align: center;vertical-align:middle}
#two_table tr th{border:2px black solid;text-align: center;word-wrap:break-word;color: red;vertical-align:middle}
</style>
    <div class="panel-body">
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade active in" id="one">
                <div class="widget-body no-padding">

                    <div class="commonsearch-table">
                        <form class="form-inline  nice-validator nice-validator n-default" action="<?php echo url('wmreport/depdraw/index'); ?>" id="f-commonsearch" role="form" method="post">
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

                                
                                <div class="form-group dislocationAll">
                                    <label for="order_num" class="control-label labelLocation">领取单号</label>
                                    <input type="text" name="order_num" class="form-control clear" size="8" value="<?php echo \think\Request::instance()->param('order_num'); ?>">
                                </div>
                                <div class="form-group dislocationAll">
                                    <label for="dept" class="control-label labelLocation">领取科室</label>
                                    <select id="dept" class="form-control clear selectpicker show-tick" name="dept"  data-live-search="true">
                                        <option value='' >--- 请选择 ---</option>
                                        <?php if(is_array($deptList) || $deptList instanceof \think\Collection || $deptList instanceof \think\Paginator): if( count($deptList)==0 ) : echo "" ;else: foreach($deptList as $key=>$vo): ?>
                                        <option  value="<?php echo $vo['dept_id']; ?>" <?php if(in_array(($vo['dept_id']), is_array(\think\Request::instance()->param('dept'))?\think\Request::instance()->param('dept'):explode(',',\think\Request::instance()->param('dept')))): ?>selected<?php endif; ?>><?php echo $vo['dept_name']; ?></option>
                                        <?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                </div>
                                <div class="form-group dislocationAll">
                                    <label for="out_id" class="control-label labelLocation">领取人</label>
                                    <select id="out_id" class="form-control clear selectpicker show-tick" name="out_id" data-live-search="true" >
                                        <option  value='' >--- 请选择 ---</option>
                                        <?php if(is_array($userList) || $userList instanceof \think\Collection || $userList instanceof \think\Paginator): if( count($userList)==0 ) : echo "" ;else: foreach($userList as $key=>$vo): ?>
                                        <option  value="<?php echo $vo['id']; ?>" <?php if(in_array(($vo['id']), is_array(\think\Request::instance()->param('out_id'))?\think\Request::instance()->param('out_id'):explode(',',\think\Request::instance()->param('out_id')))): ?>selected<?php endif; ?>><?php echo $vo['nickname']; ?></option>
                                        <?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                </div>
                                <div class="form-group dislocationAll">
                                    <label for="type" class="control-label labelLocation red">状态</label>
                                    <select id="type" class="form-control " name="type" >
                                    <option value="99" >------ ALL ------</option>
                                        <option value="4" <?php if(in_array((4), is_array(\think\Request::instance()->param('type'))?\think\Request::instance()->param('type'):explode(',',\think\Request::instance()->param('type')))): ?>selected<?php endif; ?>>科室领药</option>
                                        <option value="5" <?php if(in_array((5), is_array(\think\Request::instance()->param('type'))?\think\Request::instance()->param('type'):explode(',',\think\Request::instance()->param('type')))): ?>selected<?php endif; ?>>科室领料</option>
                                    </select>
                                </div>
                                <div class="form-group dislocationAll">
                                    <label for="id" class="control-label labelLocation">领取日期</label>
                                    <input type="text" name="stime" class="form-control datetimepicker " data-use-current="true" data-date-format="YYYY-MM-DD" <?php if(is_null(\think\Request::instance()->param('stime'))): ?> value="<?php echo date('Y-m-01');?>"<?php else: ?> value="<?php echo \think\Request::instance()->param('stime'); ?>"<?php endif; ?> size="8">  ~  <input type="text" name="etime" class="form-control datetimepicker " data-use-current="true" data-date-format="YYYY-MM-DD" <?php if(is_null(\think\Request::instance()->param('etime'))): ?> value="<?php echo date('Y-m-d',strtotime(date('Y-m-01').' +1 month -1 day'));?>"<?php else: ?> value="<?php echo \think\Request::instance()->param('etime'); ?>"<?php endif; ?> size="8">
                                </div>

                                
                                <div class="form-group" style="">
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-success dislocationRight">提交</button>
                                        <a type="reset" class="btn btn-default" id="btn-customer-clear">重置</a>
                                        <button type="button" class="btn btn-default" id="btn-export">导出</button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>

                    <div id="toolbar" class="toolbar">
                        <a href="javascript:;" class="btn btn-primary btn-refresh" onclick="window.location.reload()"><i class="fa fa-refresh"></i> </a> &nbsp;
                        <!-- <a href="javascript:;" class="btn btn-primary" id="isPrint">打印</a> -->
                        
                    </div>
                    
<!--startprint-->
                   
                    <div class="form-group " style="font-family: KaiTi;width: 100%;">
                        <div style="text-align: center;font-weight:bold;font-size: 20px;"> 科室领取产品统计表</div>
					</div>
					<div class="proreportDate" style="font-family: KaiTi;width: 100%;height: 22px;">
                            <?php if(!empty($alls)): ?>
                        <div style="font-weight:normal;font-size: 15px;float: left;" >
                            <b>总数量:</b><span style="color: #18bc9c"><?php echo !empty($alls['mallmpro_num'])?$alls['mallmpro_num']:''; ?></span>&nbsp;&nbsp;
                            <b>总成本:</b><span style="color: #18bc9c"><?php echo !empty($alls['mallcost'])?$alls['mallcost']:''; ?></span>
                        </div>
                        <?php endif; ?>
                            <div style="font-weight:normal;font-size: 13px;float: right;">打印日期：<?php echo date('Y-m-d');?></div>
                    </div>
                    <div id="consumTable" style="position: relative;overflow-y: auto;">
                    <table id="table" class=" table table-striped table-bordered table-hover scrolltable" width="100%"  style="table-layout: fixed;" >             
                         <thead>
                         	<tr>
	                            <th class="proreport-th red">领取科室</th>
	                            <th class="proreport-th">领取日期</th>
	                            <th class="proreport-th">领取单号</th>
                                <th class="proreport-th">所属仓库</th>
                                <th class="proreport-th">类别</th>
	                            <th class="proreport-th red">领取产品</th>
                                <th class="proreport-th red">批号</th>
	                            <th class="proreport-th">规格</th>
	                            <th class="proreport-th">单位</th>
	                            <th class="proreport-th">数量</th>
	                            <th class="proreport-th">总成本</th>
	                            <th class="proreport-th">领取人</th>
	                            <th class="proreport-th">客户</th>
	                            <th class="proreport-th">备注</th>
                        	</tr>
                        </thead>

                        <?php if(!empty($data)): ?>
                         <tbody style="max-height: 200px; overflow-y: overlay;">
                        <?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): if( count($data)==0 ) : echo "" ;else: foreach($data as $k=>$v): if(is_array($v) || $v instanceof \think\Collection || $v instanceof \think\Paginator): if( count($v)==0 ) : echo "" ;else: foreach($v as $kk=>$vo): ?>
                            <tr style="">
                                <td style="color: red"><?php if($counts[$k]-$kk <$counts[$k]): else: ?> <?php echo $vo['dept_name']; endif; ?></td>
                                <td><?php echo datetime($vo['mcreatetime'],"Y-m-d"); ?></td>
                                <td><?php echo $vo['man_num']; ?></td>
                                <td><?php echo $vo['dtname']; ?></td>
                                <td><?php echo $vo['pro_cat1']; ?>*<?php echo $vo['pro_cat2']; ?></td>
                                <td><?php echo $vo['pro_name']; ?></td>
                                <td><?php echo $vo['lotnum']; ?></td>
                                <td><?php echo $vo['pro_spec']; ?></td>
                                <td><?php echo $vo['uname']; ?></td>
                                <td><?php echo $vo['mpro_num']; ?></td>
                                <td><?php echo $vo['mallcost']; ?></td>
                                <td><?php echo $vo['nickname']; ?></td>
                                <td><?php echo $vo['ctm_name']; ?></td>
                                <td><?php echo $vo['mremark']; ?></td>
                            </tr>
                        <?php endforeach; endif; else: echo "" ;endif; if(is_array($datas) || $datas instanceof \think\Collection || $datas instanceof \think\Paginator): if( count($datas)==0 ) : echo "" ;else: foreach($datas as $kk=>$vv): if($kk == $k): ?> 
                            <tr style="border-bottom: 2px solid" class="one_table">                                
                                <th colspan="9" style="text-align: right;color: red;padding-right: 20px;">小计:</th>
                                <th colspan="1" style="color: red;"><?php echo $vv['all_num']; ?></th>
                                <th colspan="1" style="color: red;"><?php echo $vv['all_money']; ?></th>
                                <th colspan="3"></th>
                            </tr>
                            <?php endif; endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                     <!-- <tfoot class="proreport-tfoot"  style="">
                        <tr  style="" class="two_table">
                            <th colspan="8" style="">总合计:</th>
                            <th style="color: red;"><?php echo !empty($alls['mallmpro_num'])?$alls['mallmpro_num']:''; ?></th>
                            <th style="color: red;"><?php echo !empty($alls['mallcost'])?$alls['mallcost']:''; ?></th>
                            <th colspan="3"></th>
                        </tr>
                       </tfoot> -->
                    <?php endif; ?>    
                </table>
                </div>
                  
<!--endprint-->

                </div>
            </div>

        </div>
    </div>
</div>
<span id="h_yjy_where" class="hidden"><?php if((isset($where))): ?><?php echo $where; else: ?>[]<?php endif; ?></span>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
<script src="__CDN__/assets/js/require.js" data-main="__CDN__/assets/js/require-backend.js?v=<?php echo $site['version']; ?>"></script>
    </body>
</html>