<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:77:"D:\wamp\www\h_two\public/../application/admin\view\wm\apparatus\index_al.html";i:1561101474;s:70:"D:\wamp\www\h_two\public/../application/admin\view\layout\default.html";i:1544673823;s:67:"D:\wamp\www\h_two\public/../application/admin\view\common\meta.html";i:1544673823;s:69:"D:\wamp\www\h_two\public/../application/admin\view\common\script.html";i:1544673823;}*/ ?>
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
            <a href="#basic" data-toggle="tab">器械明细</a>
        </li>
    </ul>
    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade in active" id="basic">
            <div class="panel-body">
            	


            <input type="hidden" name="" value="<?php echo $apId; ?>" id="apId">
				<div class="form-group ">
			        <div class="col-xs-12 col-sm-8">
                        <a href="javascript:;" class="btn bg-blue btn-add " id="addAl"> 进库</a>
                        <a href="javascript:;" class="btn btn-danger btn-del" id="scrapAl"> 报废</a>
			            <a href="javascript:;" class="btn btn-primary" id="join_num">在库：<?php echo !empty($joinNum)?$joinNum:'0'; ?></a>
			            <a href="javascript:;" class="btn btn-primary" id="out_num">报废：<?php echo !empty($outNum)?$outNum:'0'; ?></a>
			        </div>
			        <div class="col-sm-12">
			          </div>
			         </div>
			            <div class="form-group">
			        <div class="col-xs-12 col-sm-12" style="width: 100%;overflow-x: scroll;"><!--startprint-->
			            <table id="apparatusTable" class="table table-striped table-bordered table-hover" style="width: 1000px;text-align: center;">

			                <!-- //4.10  打印 -->
			                <tr class="printA" style="display: none;text-align: center;">
			                    <td colspan="10"><?php echo !empty($site['hospital'])?$site['hospital']:''; ?></td>
			                </tr>
			                <!-- //4.10  打印onclick="allChecked('allRow','pRow')" -->
			                <thead>
				                <tr>
									<th style="width: 15px;">状态</th>
									<th style="width: 30px;">id</th>
				                    <th style="width: 20px;">名称</th>
				                    <th style="width: 20px;">批号</th>
				                    <th style="width: 35px;">变动数量</th>
				                    <th style="width: 20px;">价格</th>	
				                    <th style="width: 10px;">有效期</th>		                    
				                    <th style="width: 10px;">供应商</th>
				                    <th style="width: 30px;">使用人</th>
				                    <th style="width: 20px;">使用科室</th>
				                    <th style="width: 20px;">购买日期</th>
				                    <th style="width: 20px;">操作日期</th>
								</tr>
							</thead>
							<?php if(!empty($list)): ?>
							<tbody id="printTbody" >
								<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $key=>$vo): ?>
								<tr>
									
									<?php echo !empty($vo['alstatus']) && $vo['alstatus']==2?'<td style="color: #e74c3c;font-weight: bold;">报废</td>':'<td  style="color: #0073b7;font-weight: bold;" style="">进库</td>'; ?>
									<td><?php echo $vo['al_id']; ?></td>
				                    <td><?php echo $vo['a_name']; ?></td>
				                    <td><?php echo $vo['alotnum']; ?></td>
				                    <td><?php echo $vo['alnum']; ?></td>
				                    <td><?php echo $vo['alcost']; ?></td>
				                    <td><?php echo date('Y-m-d',$vo['aletime']); ?></td>
				                    <td><?php echo $vo['sup_name']; ?></td>
				                    <td><?php echo $vo['nickname']; ?></td>
				                    <td><?php echo $vo['dept_name']; ?></td>
				                    <td><?php echo date('Y-m-d',$vo['alshop_time']); ?></td>
				                    <td><?php echo date('Y-m-d',$vo['aloperate_time']); ?></td>
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