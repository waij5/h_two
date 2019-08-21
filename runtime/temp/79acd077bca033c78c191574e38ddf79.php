<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:69:"D:\wamp\www\h_two\public/../application/admin\view\wm\cmrk\edits.html";i:1561430796;s:70:"D:\wamp\www\h_two\public/../application/admin\view\layout\default.html";i:1544673823;s:67:"D:\wamp\www\h_two\public/../application/admin\view\common\meta.html";i:1544673823;s:69:"D:\wamp\www\h_two\public/../application/admin\view\common\script.html";i:1544673823;}*/ ?>
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
            <a href="#basic" data-toggle="tab">耗材入库单明细</a>
        </li>
    </ul>
    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade in active" id="basic">

        	<div class="form-group col-sm-6">
		        <label for="c-man_num" class="control-label col-xs-12 col-sm-4"><?php echo __('man_num'); ?>:</label>
		        <div class="col-xs-12 col-sm-8">
		            <input id="c-man_num" data-rule="required" readonly="readonly" value="<?php echo $row['man_num']; ?>" class="form-control" name="row[man_num]" type="text">
		        </div>
		        <!--  -->
		    </div>
			<input type="hidden" name="man_id" value="<?php echo $row['man_id']; ?>" id="c-man_id" >
		    <div class="form-group col-sm-6">
		        <label for="c-muid" class="control-label col-xs-12 col-sm-4"><?php echo __('muid'); ?>:</label>
		        <div class="col-xs-12 col-sm-8">
		            <input id="c-muid" data-rule="required" readonly="readonly" class="form-control" name="row[muid]" type="text" value="<?php echo $row['muid']; ?>">
		        </div>
		    </div>

		    <div class="form-group col-sm-6">
		        <label for="c-msecond_type" class="control-label col-xs-12 col-sm-4" style="color: red"><?php echo __('rk_type'); ?>:</label>
		        <div class="col-xs-12 col-sm-8">
		            <select id="c-msecond_type" disabled="" class="form-control " name="row[msecond_type]">
		                <?php if(is_array($rkType) || $rkType instanceof \think\Collection || $rkType instanceof \think\Paginator): if( count($rkType)==0 ) : echo "" ;else: foreach($rkType as $key=>$vo): ?>
		                <option  value="<?php echo $key; ?>" <?php if(in_array(($key), is_array($row['msecond_type'])?$row['msecond_type']:explode(',',$row['msecond_type']))): ?>selected<?php endif; ?>><?php echo $vo; ?></option>
		                <?php endforeach; endif; else: echo "" ;endif; ?>
		            </select>
		        </div>
		    </div>

		    <div class="form-group col-sm-6">
		        <label for="c-mdepot_id" class="control-label col-xs-12 col-sm-4" style="color: red"><?php echo __('mdepot_id'); ?>:</label>
		        <div class="col-xs-12 col-sm-8">
		            <select id="c-mdepot_id" disabled class="form-control " name="row[mdepot_id]">
		                <?php if(is_array($depotList) || $depotList instanceof \think\Collection || $depotList instanceof \think\Paginator): if( count($depotList)==0 ) : echo "" ;else: foreach($depotList as $key=>$vo): ?>
		                <option data-type="<?php echo $vo['name']; ?>" value="<?php echo $vo['id']; ?>" <?php if(in_array(($vo['id']), is_array($row['mdepot_id'])?$row['mdepot_id']:explode(',',$row['mdepot_id']))): ?>selected<?php endif; ?>><?php echo $vo['name']; ?></option>
		                <?php endforeach; endif; else: echo "" ;endif; ?>
		            </select>
		        </div>
		    </div>

		    <div class="form-group col-sm-6">
		        <label for="c-msupplier_id" class="control-label col-xs-12 col-sm-4"><?php echo __('msupplier_id'); ?>:</label>
		        <div class="col-xs-12 col-sm-8">
		            <select id="c-msupplier_id"  class="form-control selectpicker" name="row[msupplier_id]" data-live-search="true">
		                <option value=""></option>
		                <?php if(is_array($supplierList) || $supplierList instanceof \think\Collection || $supplierList instanceof \think\Paginator): if( count($supplierList)==0 ) : echo "" ;else: foreach($supplierList as $key=>$vo): ?>
		                <option  value="<?php echo $vo['sup_id']; ?>" <?php if(in_array(($vo['sup_id']), is_array($row['msupplier_id'])?$row['msupplier_id']:explode(',',$row['msupplier_id']))): ?>selected<?php endif; ?>><?php echo $vo['sup_name']; ?></option>
		                <?php endforeach; endif; else: echo "" ;endif; ?>
		            </select>
		        </div>
		    </div>

		    
		    

		    <div class="form-group col-sm-12">
		        <label for="c-mremark" class="control-label col-xs-12 col-sm-2"><?php echo __('mremark'); ?>:</label>
		        <div class="col-xs-12 col-sm-7">
		            <input id="c-mremark" class="form-control" name="row[mremark]" type="text" value="<?php echo $row['mremark']; ?>">
		        </div>
		        <div class="col-xs-12 col-sm-3">
		            <a href="javascript:;" class="btn btn-primary" id="editRemark" >确认修改</a>
		        </div>
		    </div>



			<div class="form-group ">
		        <label class="control-label col-xs-12 col-sm-2">耗材列表:</label>
		        <div class="col-xs-12 col-sm-8">
		            <?php if($row['mcreatetime'] >= strtotime(date('Y-m-1'))): ?>
		            <a href="javascript:;" class="btn btn-primary" id="alterJhDate" ><i class="fa fa-reply"></i> 反日期</a>
		            <?php endif; ?>
		            <a href="javascript:;" class="btn btn-primary" id="isPrint">打印</a>
		        </div>
		        <div class="col-sm-12">
		          </div>
		         </div>
		            <div class="form-group">
		        <div class="col-xs-12 col-sm-12" style="width: 100%;overflow-x: scroll;"><!--startprint-->

		        	<style>
						body{background-color: #fff;}
						#ribbon{display: none;}
						#selectedDrugs td{border:2px solid !important;}
						.borderNone tr{border:1px solid white !important;}
						.borderNone th{border:1px solid white !important;}
					</style>

		            <table id="selectedDrugs" class="table table-striped table-bordered table-hover" style="width: 1200px;text-align: center;">

		                <!-- //4.10  打印 -->
		                <tr class="printA" style="display: none;text-align: center;">
		                    <td colspan="10"><?php echo $site['hospital']; ?><br/>
			                    耗材入库单</td>
		                </tr>
		                <!-- //4.10  打印 -->

		                <tr>
							<?php if($cjData>0): ?><td class="printB" style="width: 40px;"></td><?php endif; ?>
							<td class="wpmcWidth" style="width: 80px;">耗材名称</td>
		                    <td class="phWidth" style="width: 60px;">批号</td>
		                    <td class="rkWidth" style="width: 40px;">入库数量</td>
		                    <td class="ggWidth" style="width: 60px;">规格</td>
		                    <td class="dwWidth" style="width: 20px;">单位</td>
		                    <td class="jjWidth" style="width: 40px;">进货价</td>
		                    <td class="zjjWidth" style="width: 50px;">总进价</td>
		                    <td class="printB" style="width: 40px;">零售价</td>
		                    <td class="scWidth" style="width: 40px;">生产日期</td>
		                    <td class="dqWidth" style="width: 40px;">到期日期</td>
		                    <td class="printB" style="width: 50px;">生产厂家</td>
		                    <td class="printB" style="width: 50px;">产地</td>
		                    <td class="printB" style="width: 50px;">批准文号</td>
		                    <td class="printB" style="width: 50px;">注册文号</td>
						</tr>
						<?php if(!empty($list)): if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $key=>$vo): ?>
						<tr>
							<?php if($cjData>0): ?><td class="printB"><?php if($vo['cjtype']==1): ?><span style="color:red">冲减过</span><?php endif; ?></td><?php endif; ?>
							<td><?php echo $vo['pro_name']; ?></td>
		                    <td><?php echo $vo['lotnum']; ?></td>
		                    <td><?php echo $vo['mpro_num']; ?></td>
		                    <td><?php echo $vo['pro_spec']; ?></td>
		                    <td><?php echo $vo['uname']; ?></td>
		                    <td><?php echo $vo['mcost']; ?></td>
		                    <td><?php echo $vo['mallcost']; ?></td>
		                    <td class="printB"><?php echo $vo['mprice']; ?></td>
		                    <!-- <td><?php echo $vo['mallprice']; ?></td> -->
		                    <td><?php if($vo['mstime']>0): ?><?php echo date('Y-m-d',$vo['mstime']); endif; ?></td>
		                    <td><?php if($vo['metime']>0): ?><?php echo date('Y-m-d',$vo['metime']); endif; ?></td>
		                    <td class="printB"><?php echo $vo['lproducer']; ?></td>
		                    <td class="printB"><?php echo $vo['laddr']; ?></td>
		                    <td class="printB"><?php echo $vo['lapprov_num']; ?></td>
		                    <td class="printB"><?php echo $vo['lregist_num']; ?></td>
						</tr>
						<?php endforeach; endif; else: echo "" ;endif; endif; ?>


		                <!-- //4.10  打印 -->
		                <tr class="printA" style="display: none;text-align: left">
		                    <td colspan="3">单号：<?php echo $row['man_num']; ?></td>
		                    <td colspan="2" id="depotName"></td>
		                    <td colspan="1" style="text-align: right;">合计：</td>
		                    <td colspan="1" style="text-align: center;"><?php echo $totalCost; ?></td>
		                    <td colspan="2">入库日期：<?php echo date("Y-m-d",$row['mcreatetime']); ?></td>
		                </tr>
		                <tr class="printA" style="display: none;text-align: left">
		                    <td colspan="3" id="supplierName"></td>
		                    <td colspan="6" >备注:<?php echo $row['mremark']; ?></td>
		                </tr>
		                <tr  class="printA borderNone"  style="display: none;text-align: left;">
		                	<th colspan="3" style="border:none">收货人:</th>
		                	<th colspan="4" style="border:none">经手人:</th>
		                	<th colspan="2" style="border:none">审核人:</th>
		                </tr>
		                <!-- //4.10  打印 -->

		            </table><!--endprint-->
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