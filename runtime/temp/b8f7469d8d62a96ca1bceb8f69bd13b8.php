<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:79:"D:\wamp\www\h_two\public/../application/admin\view\wm\drugscflist\cf_print.html";i:1555659182;s:70:"D:\wamp\www\h_two\public/../application/admin\view\layout\default.html";i:1544673823;s:67:"D:\wamp\www\h_two\public/../application/admin\view\common\meta.html";i:1544673823;s:69:"D:\wamp\www\h_two\public/../application/admin\view\common\script.html";i:1544673823;}*/ ?>
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
                                <style>
	body{background-color: #fff;}
	.ribbon{display: none;}
	th{text-align: center;}
</style>
<form id="view-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action="">
    
    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade in active" id="basic">
            <div class="panel-body">
            	



				<div class="form-group ">
			        
			        <div class="col-sm-12">
			          </div>
			         </div>
			            <div class="form-group">
			        <div class="col-xs-12 col-sm-12" style="width: 100%;overflow-x: scroll;"><!--startprint-->
			            <table id="selectedDrugs" class="table table-striped table-bordered table-hover">

			                
			                
			                <?php if(!empty($data)): ?>
			                <!--startprint-->
			                <thead style="text-align: center;">
			                <tr >
			                    <td colspan="10"><div style="text-align: center;font-weight: bold;"><?php echo !empty($site['hospital'])?$site['hospital']:''; ?><div style="float: right;font-weight:normal"><?php echo date('Y-m-d');?></div></div></td>
			                </tr>
				                <tr>
				                	
				                    <td style="width: 20px;">划扣ID</td>
				                    <td style="width: 50px;">药品名称</td>
				                    <td style="width: 20px;">数量</td>
				                    <td style="width: 50px;">仓库</td>
				                    <td style="width: 20px;">规格</td>			                    
				                    <td style="width: 10px;">单位</td>
								</tr>
							</thead>
							
							<tbody style="text-align: center;">
								<?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): if( count($data)==0 ) : echo "" ;else: foreach($data as $key=>$vo): ?>
								<tr>
									
				                    <td><?php echo $vo['drid']; ?></td>
				                    <td><?php echo $vo['pro_name']; ?></td>
				                    <td><?php echo $vo['deduct_times']; ?></td>
				                    <td><?php echo $vo['dname']; ?></td>
				                    <td><?php echo $vo['pro_spec']; ?></td>
				                    <td><?php echo $vo['uname']; ?></td>
				                    <!-- <?php echo !empty($vo['status']) && $vo['status']==1?'<td><a href="javascript:;" class="btn btn-xs btn-primary btn-editone" title="发药"><i class="fa fa-mail-forward"></i></a></td>':'<td><a href="javascript:;" class="btn btn-xs btn-primary btn-editone" title="撤药"><i class="fa fa-reply"></i></a></td>'; ?> -->
								</tr>
								<?php endforeach; endif; else: echo "" ;endif; ?>
							</tbody>
							<!--endprint-->
							<?php endif; if(!empty($allPdata)): ?>
							<!--startprint-->
								<thead style="text-align: center;">
				                <tr >
				                    <td colspan="10"><div style="text-align: center;font-weight: bold;"><?php echo !empty($site['hospital'])?$site['hospital']:''; ?><div style="float: right;font-weight:normal"><?php echo date('Y-m-d');?></div></div></td>
				                </tr>
					                <tr>
					                	<td style="width: 10px;">序号</td>
					                    <td style="width: 50px;">药品名称</td>
					                    <td style="width: 20px;">规格</td>	
					                    <td style="width: 20px;">数量</td>		                    
					                    <td style="width: 10px;">单位</td>
					                    <td style="width: 50px;">仓库</td>
									</tr>
								</thead>
								
								<tbody style="text-align: center;">
									<?php if(is_array($allPdata) || $allPdata instanceof \think\Collection || $allPdata instanceof \think\Paginator): if( count($allPdata)==0 ) : echo "" ;else: foreach($allPdata as $key=>$vo): ?>
									<tr>
										<td><?php echo $key+1; ?></td>
					                    <td><?php echo $vo['pro_name']; ?></td>
					                    <td><?php echo $vo['pro_spec']; ?></td>
					                    <td><?php echo $vo['all_deduct_times']; ?></td>
					                    <td><?php echo $vo['uname']; ?></td>
					                    <td><?php echo $vo['dname']; ?></td>
					                    <!-- <?php echo !empty($vo['status']) && $vo['status']==1?'<td><a href="javascript:;" class="btn btn-xs btn-primary btn-editone" title="发药"><i class="fa fa-mail-forward"></i></a></td>':'<td><a href="javascript:;" class="btn btn-xs btn-primary btn-editone" title="撤药"><i class="fa fa-reply"></i></a></td>'; ?> -->
									</tr>
									<?php endforeach; endif; else: echo "" ;endif; ?>
								</tbody>
								<!--endprint-->
							<?php endif; ?>


			            </table>
			        </div>
			    </div>
            </div>
        </div>
        

    </div>
    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-3"></label>
        <div class="col-xs-12 col-sm-8">
            <a href="javascript:;" class="btn " style="background-color: #333!important;border-color: #333!important;" id="surePrint">确定打印</a>
        </div>
    </div>
</form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
<script src="__CDN__/assets/js/require.js" data-main="__CDN__/assets/js/require-backend.js?v=<?php echo $site['version']; ?>"></script>
    </body>
</html>