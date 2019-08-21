<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:69:"D:\wamp\www\h_two\public/../application/admin\view\wm\drugs\edit.html";i:1565084574;s:70:"D:\wamp\www\h_two\public/../application/admin\view\layout\default.html";i:1544673823;s:67:"D:\wamp\www\h_two\public/../application/admin\view\common\meta.html";i:1544673823;s:69:"D:\wamp\www\h_two\public/../application/admin\view\common\script.html";i:1544673823;}*/ ?>
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
    .red{color: red}
</style>
<script src="__ROOT__assets/js/alphabet.js" type="text/javascript"></script>
<form id="edit-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action="" onkeydown="if(event.keyCode==13){return false;}">

    <div class="form-group col-sm-6">
        <label for="c-pro_code" class="control-label col-xs-12 col-sm-4"><?php echo __('Pro_code'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-pro_code"  value="<?php echo $row['pro_code']; ?>" class="form-control" name="row[pro_code]" type="text">
        </div>
    </div>
    <div class="form-group col-sm-6">
        <label for="c-pro_name" class="control-label col-xs-12 col-sm-4 red "><?php echo __('Pro_name'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-pro_name" data-rule="required" class="form-control" name="row[pro_name]" oninput="setCode('row[pro_name]','row[pro_spell]')" onporpertychange="setCode('row[pro_name]','row[pro_spell]')" type="text" value="<?php echo $row['pro_name']; ?>">
        </div>
    </div>
    <div class="form-group col-sm-6">
        <label for="c-pro_spell" class="control-label col-xs-12 col-sm-4"><?php echo __('Pro_spell'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-pro_spell" readonly="" class="form-control alphanumeric" name="row[pro_spell]"  type="text" value="<?php echo $row['pro_spell']; ?>">
        </div>
    </div>
    <div class="form-group col-sm-6">
        <label for="c-depot_id" class="control-label col-xs-12 col-sm-4 red"><?php echo __('Depot_id'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select id="c-depot_id" data-rule="required" <?php if($lockType > 0): ?> disabled=""<?php endif; ?>  class="form-control " name="row[depot_id]">
                <?php if(is_array($depotList) || $depotList instanceof \think\Collection || $depotList instanceof \think\Paginator): if( count($depotList)==0 ) : echo "" ;else: foreach($depotList as $key=>$vo): ?>
                <option value="<?php echo $vo['id']; ?>" <?php if(in_array(($vo['id']), is_array($row['depot_id'])?$row['depot_id']:explode(',',$row['depot_id']))): ?>selected<?php endif; ?>><?php echo $vo['name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>
    <div class="form-group col-sm-6">
        <label for="c-pro_cost" class="control-label col-xs-12 col-sm-4 red"><?php echo __('Pro_cost'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-pro_cost" class="form-control" step="0.1" name="row[pro_cost]" type="number" value="<?php echo $row['pro_cost']; ?>">
        </div>
    </div>

    <div class="form-group col-sm-6">
        <label for="c-pro_amount" class="control-label col-xs-12 col-sm-4 red"><?php echo __('Pro_amount'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-pro_amount"  class="form-control" step="0.1" name="row[pro_amount]" type="number" value="<?php echo $row['pro_amount']; ?>">
        </div>
    </div>
    
    
    <div class="form-group col-sm-6">
        <label for="c-pro_cat1" class="control-label col-xs-12 col-sm-4 red"><?php echo __('Subject_name'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select id="c-pro_cat1" data-rule="" class="form-control " name="row[pro_cat1]">
                <option value="">--请选择--</option>
                <?php if(is_array($subject) || $subject instanceof \think\Collection || $subject instanceof \think\Paginator): if( count($subject)==0 ) : echo "" ;else: foreach($subject as $key=>$v): ?>
                <option  value="<?php echo $v['pdc_id']; ?>" <?php if(in_array(($v['pdc_id']), is_array($row['pro_cat1'])?$row['pro_cat1']:explode(',',$row['pro_cat1']))): ?>selected<?php endif; ?>><?php echo $v['pdc_name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>
    <div class="form-group col-sm-6">
        <label for="c-pro_cat2" class="control-label col-xs-12 col-sm-4 red"><?php echo __('Pro_cat'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select id="c-pro_cat2" class="form-control " name="row[pro_cat2]">
                <option value=""></option>
                <?php echo $pro_cat2; ?>
            </select>
        </div>
    </div>
    






    <div class="form-group col-sm-6">
        <label for="c-pro_unit" class="control-label col-xs-12 col-sm-4 red"><?php echo __('Pro_unit'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select id="c-pro_unit" <?php if($lockType > 0): ?> disabled=""<?php endif; ?> data-rule="required" class="form-control " name="row[pro_unit]">
                <?php if(is_array($unitList) || $unitList instanceof \think\Collection || $unitList instanceof \think\Paginator): if( count($unitList)==0 ) : echo "" ;else: foreach($unitList as $key=>$vo): ?>
                <option value="<?php echo $vo['id']; ?>" <?php if(in_array(($vo['id']), is_array($row['pro_unit'])?$row['pro_unit']:explode(',',$row['pro_unit']))): ?>selected<?php endif; ?>><?php echo $vo['name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>

    <div class="form-group col-sm-6">
        <label for="c-pro_spec" class="control-label col-xs-12 col-sm-4 red"><?php echo __('Pro_spec'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-pro_spec" <?php if($lockType > 0): ?> readonly=""<?php endif; ?> data-rule="required" class="form-control" name="row[pro_spec]" type="text" value="<?php echo $row['pro_spec']; ?>">
        </div>
    </div>
    
    <div class="form-group col-sm-6">
        <label for="c-lowunit" class="control-label col-xs-12 col-sm-4"><?php echo __('Lowunit'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select id="c-lowunit"  class="form-control " name="row[lowunit]">
            <option></option>
                <?php if(is_array($unitList) || $unitList instanceof \think\Collection || $unitList instanceof \think\Paginator): if( count($unitList)==0 ) : echo "" ;else: foreach($unitList as $key=>$vo): ?>
                <option  value="<?php echo $vo['id']; ?>" <?php if(in_array(($vo['id']), is_array($row['lowunit'])?$row['lowunit']:explode(',',$row['lowunit']))): ?>selected<?php endif; ?>><?php echo $vo['name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>
    <div class="form-group col-sm-6">
        <label for="c-hex" class="control-label col-xs-12 col-sm-4"><?php echo __('Hex'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-hex" data-rule="" class="form-control" name="row[hex]" type="number" value="<?php echo $row['hex']; ?>">
        </div>
    </div>

    <div class="form-group col-sm-6">
        <label for="c-pack_spec" class="control-label col-xs-12 col-sm-4"><?php echo __('pack_spec'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-pack_spec" data-rule="" class="form-control" name="row[pack_spec]" type="text" value="<?php echo $row['pack_spec']; ?>">
        </div>
    </div>

    <div class="form-group col-sm-6">
        <label for="c-pack_unit" class="control-label col-xs-12 col-sm-4"><?php echo __('pack_unit'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select id="c-pack_unit" data-rule="" class="form-control selectpicker" name="row[pack_unit]">
            <option></option>
                <?php if(is_array($unitList) || $unitList instanceof \think\Collection || $unitList instanceof \think\Paginator): if( count($unitList)==0 ) : echo "" ;else: foreach($unitList as $key=>$vo): ?>
                <option value="<?php echo $vo['id']; ?>" <?php if(in_array(($vo['id']), is_array($row['pack_unit'])?$row['pack_unit']:explode(',',$row['pack_unit']))): ?>selected<?php endif; ?>><?php echo $vo['name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>


    
    
    <div class="form-group col-sm-6">
        <label for="c-deduct_switch" class="control-label col-xs-12 col-sm-4 red">划扣设置:</label>
        <div class="col-xs-12 col-sm-5">
            <select  id="c-deduct_switch" data-rule="" class="form-control selectpicker" name="row[deduct_switch]">
                <option value="0" {<?php if(in_array((0), is_array($row['deduct_switch'])?$row['deduct_switch']:explode(',',$row['deduct_switch']))): ?>}selected<?php endif; ?>>手动划扣</option>
                <option value="1" <?php if(in_array((1), is_array($row['deduct_switch'])?$row['deduct_switch']:explode(',',$row['deduct_switch']))): ?>selected<?php endif; ?>>自动划扣</option>
            </select>
        </div>
    </div>

    <div class="form-group col-sm-6">
        <label for="c-dept_id" class="control-label col-xs-12 col-sm-4 red"><?php echo __('dept_id'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select id="c-dept_id" data-rule="" class="form-control selectpicker" name="row[dept_id]" data-live-search="true">
                <option></option>
                <?php if(is_array($deptmentList) || $deptmentList instanceof \think\Collection || $deptmentList instanceof \think\Paginator): if( count($deptmentList)==0 ) : echo "" ;else: foreach($deptmentList as $key=>$vo): ?>
                <option  value="<?php echo $vo['dept_id']; ?>" <?php if(in_array(($vo['dept_id']), is_array($row['dept_id'])?$row['dept_id']:explode(',',$row['dept_id']))): ?>selected<?php endif; ?>><?php echo $vo['dept_name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>

    <div class="form-group col-sm-6">
        <label for="c-approv_num" class="control-label col-xs-12 col-sm-4">批准文号:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-approv_num" data-rule="" class="form-control" name="row[approv_num]" type="text" value="<?php echo $row['approv_num']; ?>">
        </div>
    </div>

    <div class="form-group col-sm-6">
        <label for="c-regist_num" class="control-label col-xs-12 col-sm-4">注册文号:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-regist_num" data-rule="" class="form-control" name="row[regist_num]" type="text" value="<?php echo $row['regist_num']; ?>">
        </div>
    </div>

    <div class="form-group col-sm-6">
        <label for="c-stock_top" class="control-label col-xs-12 col-sm-4"><?php echo __('Stock_top'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-stock_top" data-rule="" class="form-control" name="row[stock_top]" type="number" value="<?php echo $row['stock_top']; ?>">
        </div>
    </div>
    <div class="form-group col-sm-6">
        <label for="c-stock_low" class="control-label col-xs-12 col-sm-4"><?php echo __('Stock_low'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-stock_low" data-rule="" class="form-control" name="row[stock_low]" type="number" value="<?php echo $row['stock_low']; ?>">
        </div>
    </div>
   
   
   
    
    <div class="form-group col-sm-6">
        <label for="c-addr" class="control-label col-xs-12 col-sm-4"><?php echo __('Addr'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-addr" class="form-control" name="row[addr]" type="text" value="<?php echo $row['addr']; ?>">
        </div>
    </div>

    <div class="form-group col-sm-6">
        <label for="c-producer" class="control-label col-xs-12 col-sm-4"><?php echo __('Producer'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-producer" class="form-control" name="row[producer]" type="text" value="<?php echo $row['producer']; ?>">
        </div>
    </div>

    
    <div class="form-group col-sm-6">
        <label for="c-pro_fee_type" class="control-label col-xs-12 col-sm-4"><?php echo __('pro_fee_type'); ?>:</label>
        <div class="col-xs-12 col-sm-5">
            
            <?php echo build_select('row[pro_fee_type]', $proFeeType, $row['pro_fee_type'], ['class'=>'form-control ', 'data-rule'=>'']); ?>
        </div>
    </div>
    
    <div class="form-group col-sm-6">
        <label for="c-ptype" class="control-label col-xs-12 col-sm-4 "><?php echo __('ptype'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
                        
            <select  id="c-ptype" class="form-control " name="row[ptype]">
                <?php if(is_array($ptypeList) || $ptypeList instanceof \think\Collection || $ptypeList instanceof \think\Paginator): if( count($ptypeList)==0 ) : echo "" ;else: foreach($ptypeList as $key=>$vo): ?>
                    <option value="<?php echo $key; ?>" <?php if(in_array(($key), is_array($row['ptype'])?$row['ptype']:explode(',',$row['ptype']))): ?>selected<?php endif; ?>><?php echo $vo; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>

        </div>
    </div>

    <input type="hidden" name="row[pro_type]" value="1"><!--  设置默认产品类别 1，药品 -->

    <div class="form-group col-sm-6">
        <label for="c-pro_status" class="control-label col-xs-12 col-sm-4"><?php echo __('Pro_status'); ?>:</label>
        <div class="col-xs-12 col-sm-4">
                        
            <?php if(is_array($statusList) || $statusList instanceof \think\Collection || $statusList instanceof \think\Paginator): if( count($statusList)==0 ) : echo "" ;else: foreach($statusList as $key=>$vo): ?>
            <label ><input name="row[pro_status]" type="radio" value="<?php echo $key; ?>" <?php if(in_array(($key), is_array($row['pro_status'])?$row['pro_status']:explode(',',$row['pro_status']))): ?>checked<?php endif; ?> /> <?php echo $vo; ?></label> 
            <?php endforeach; endif; else: echo "" ;endif; ?>

        </div>
    </div>

    <div class="form-group col-sm-12">
        <label for="c-pro_remark" class="control-label col-xs-12 col-sm-2"><?php echo __('pro_remark'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-pro_remark" class="form-control" name="row[pro_remark]" type="text" value="<?php echo $row['pro_remark']; ?>">
        </div>
    </div>
    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-success btn-embossed disabled"><?php echo __('OK'); ?></button>
            <button type="reset" class="btn btn-default btn-embossed"><?php echo __('Reset'); ?></button>
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