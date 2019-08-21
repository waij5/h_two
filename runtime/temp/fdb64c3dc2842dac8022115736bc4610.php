<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:75:"D:\wamp\www\h_two\public/../application/admin\view\wm\drugscflist\edit.html";i:1556508891;s:70:"D:\wamp\www\h_two\public/../application/admin\view\layout\default.html";i:1544673823;s:67:"D:\wamp\www\h_two\public/../application/admin\view\common\meta.html";i:1544673823;s:69:"D:\wamp\www\h_two\public/../application/admin\view\common\script.html";i:1544673823;}*/ ?>
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
            <a href="#basic" data-toggle="tab">处方列表</a>
        </li>
    </ul>
    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade in active" id="basic">
            <div class="panel-body">
            	



				<div class="form-group ">
			        <div class="col-xs-12 col-sm-8">
			        	<!-- <a href="javascript:;" class="btn btn-primary btn-refresh" id="cfEditRefresh"><i class="fa fa-refresh"></i> </a> -->
			        	<!-- <div class="form-group dislocationAll"> -->
                            <select class="depotData" style="width: 120px;height: 30px">
                            	<option value=""></option>
				        		<?php if(is_array($depotData) || $depotData instanceof \think\Collection || $depotData instanceof \think\Paginator): if( count($depotData)==0 ) : echo "" ;else: foreach($depotData as $key=>$v): ?>
				        		<option value=""><?php echo $v; ?></option>
				        		<?php endforeach; endif; else: echo "" ;endif; ?>
				        	</select>
				        	<select class="deptmentData" style="width: 120px;height: 30px">
				        		<option value=""></option>
				        		<?php if(is_array($deptmentData) || $deptmentData instanceof \think\Collection || $deptmentData instanceof \think\Paginator): if( count($deptmentData)==0 ) : echo "" ;else: foreach($deptmentData as $key=>$v): ?>
				        		<option value=""><?php echo $v; ?></option>
				        		<?php endforeach; endif; else: echo "" ;endif; ?>
				        	</select> 
                        <!-- </div> -->

			            <a href="javascript:;" class="btn btn-primary" id="isPrint">打印</a>
			        </div>
			        <div class="col-sm-12">
			          </div>
			         </div>
			            <div class="form-group">
			        <div class="col-xs-12 col-sm-12" style="width: 100%;overflow-x: scroll;"><!--startprint-->
			            <table id="selectedDrugs" class="table table-striped table-bordered table-hover" style="width: 950px;text-align: center;">

			                <!-- //4.10  打印 -->
			                <tr class="printA" style="display: none;text-align: center;">
			                    <td colspan="10"><?php echo !empty($site['hospital'])?$site['hospital']:''; ?></td>
			                </tr>
			                <!-- //4.10  打印onclick="allChecked('allRow','pRow')" -->
			                <thead>
				                <tr>
				                	<th style="width: 3%;"><input type="checkbox" id="allRow" name="allRow" ></th>
									<th style="width: 15px;">发药状态</th>
				                    <th style="width: 20px;">划扣ID</th>
				                    <th style="width: 20px;">处方单</th>
				                    <th style="width: 55px;">产品</th>
				                    <th style="width: 20px;">仓库</th>
				                    <th style="width: 30px;">开单科室</th>
				                    <th style="width: 20px;">划扣日期</th>			                    
				                    <th style="width: 10px;">数量</th>
				                    <th style="width: 15px;">总次数</th>
				                    <!-- <th " style="width: 10px;">操作</th> -->
								</tr>
							</thead>
							<?php if(!empty($list)): ?>
							<tbody id="printTbody">
								<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $key=>$vo): ?>
								<tr>
									
									<td><?php if($vo['status']==1): ?><input type="checkbox" class="pRow" name="pRow" value="<?php echo $vo['drid']; ?>"><?php else: ?><input type="hidden" class="pRow" value=""><?php endif; ?></td>
									<?php echo !empty($vo['status']) && $vo['status']==1?'<td style="color:#e73caa">待发药</td>':'<td>已发药</td>'; ?>
				                    <td><?php echo $vo['drid']; ?></td>
				                    <td><?php echo $vo['item_id']; ?></td>
				                    <td><?php echo $vo['pro_name']; ?></td>
				                    <td><?php echo $vo['depot_name']; ?></td>
				                    <td><?php echo $vo['dept_name']; ?></td>
				                    <td><?php echo date('Y-m-d H:i',$vo['createtime']); ?></td>
				                    <td><?php echo $vo['deduct_times']; ?></td>
				                    <td><?php echo $vo['item_total_times']; ?></td>
				                    <!-- <?php echo !empty($vo['status']) && $vo['status']==1?'<td><a href="javascript:;" class="btn btn-xs btn-primary btn-editone" title="发药"><i class="fa fa-mail-forward"></i></a></td>':'<td><a href="javascript:;" class="btn btn-xs btn-primary btn-editone" title="撤药"><i class="fa fa-reply"></i></a></td>'; ?> -->
								</tr>
								<?php endforeach; endif; else: echo "" ;endif; ?>
							</tbody>
							<?php endif; ?>


			                <!-- //4.10  打印 -->
			                
			                <!-- //4.10  打印 -->

			            </table><!--endprint-->
			        </div>
			    </div>
            </div>
        </div>
        

    </div>
    <div class="form-group iframeFoot">

    </div>
</form>
<style>
	body{background-color: #fff;}
	#ribbon{display: none;}
	th{text-align: center;}
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