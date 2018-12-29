<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:56:"E:\demo\sanzhixiaoxiong/wstmart/admin\view\ads\list.html";i:1532055909;s:52:"E:\demo\sanzhixiaoxiong/wstmart/admin\view\base.html";i:1523179156;}*/ ?>
<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>后台管理中心 - <?php echo WSTConf('CONF.mallName'); ?></title>
<meta name="Keywords" content=""/>
<meta name="Description" content=""/> 
<link href="__ADMIN__/js/ligerui/skins/Aqua/css/ligerui-all.css?v=<?php echo $v; ?>" rel="stylesheet" type="text/css" /> 
<link href="__STATIC__/plugins/validator/jquery.validator.css?v=<?php echo $v; ?>" rel="stylesheet">

<link href="__ADMIN__/css/style.css?v=<?php echo $v; ?>" rel="stylesheet" type="text/css" />   
<script src="__STATIC__/js/jquery.min.js?v=<?php echo $v; ?>"></script>  
<script src="__ADMIN__/js/ligerui/js/ligerui.all.js?v=<?php echo $v; ?>" type="text/javascript"></script> 
<script type='text/javascript' src='__STATIC__/plugins/layer/layer.js?v=<?php echo $v; ?>'></script> 
<script src="__STATIC__/js/common.js?v=<?php echo $v; ?>"></script>
<script>
window.conf = {"ROOT":"__ROOT__","APP":"__APP__","STATIC":"__STATIC__","SUFFIX":"<?php echo config('url_html_suffix'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>",'GRANT':'<?php echo implode(",",session("WST_STAFF.privileges")); ?>'}
</script>
<script src="__ADMIN__/js/common.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="__STATIC__/plugins/validator/jquery.validator.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="__STATIC__/plugins/validator/local/zh-CN.js?v=<?php echo $v; ?>"></script>
</head>
<body>

<div class="l-loading" style="display: block" id="wst-loading"></div>
<div class="wst-toolbar">
	<div id="query" style="float:left;">
		<p style="float:left;height:25px;line-height:25px;">类型:</p>
			<select style="float:left;" name="positionType" id="positionType" class="query" onChange="addPosition(this.value)">
				<option value="">请选择</option>
                <?php $_result=WSTDatas(5);if(is_array($_result) || $_result instanceof \think\Collection): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;if($vo['dataVal'] == 1): ?>
                <option value="<?php echo $vo['dataVal']; ?>"><?php echo $vo['dataName']; ?></option>
				<?php endif; endforeach; endif; else: echo "" ;endif; ?>
			</select>
		<p style="float:left;height:25px;line-height:25px;">广告位置:</p>
			<select style="float:left;" name="adPositionId" id="adPositionId" class="query" >
				<option value="">请选择</option>
			</select>
		

		<input type="button" class="btn btn-blue" onclick="javascript:adsQuery()" value="查询">
	</div>
   <?php if(WSTGrant('GGGL_01')): ?> 
   <button class="btn btn-green f-right" onclick="javascript:location.href='<?=url("Ads/toEdit")?>'">新增</button>
   <?php endif; ?>
   <div style="clear:both"></div>
</div>
<div id="maingrid"></div>
<script>
$(function(){initGrid()});
</script>



<script src="__ADMIN__/ads/ads.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<script>
function showImg(opt){
	layer.photos(opt);
}
function showBox(opts){
	return WST.open(opts);
}
</script>
</body>
</html>