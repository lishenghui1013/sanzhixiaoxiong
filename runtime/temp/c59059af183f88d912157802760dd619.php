<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:61:"E:\demo\sanzhixiaoxiong/wstmart/admin\view\agent\listads.html";i:1540967442;s:52:"E:\demo\sanzhixiaoxiong/wstmart/admin\view\base.html";i:1523179156;}*/ ?>
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
<?php if(WSTGrant('GGWZ_01')): ?>
<div class="wst-toolbar">
   
   <div style="clear:both"></div>
</div>
<?php endif; ?>

<div id="maingrid"></div>
<div id='editPassBox' style='display:none;padding-top:5px;'>
  <form id='editPassFrom' autocomplete="off">
   <table class='wst-form'>
      
      <tr>
         <th>时间单位：</th>
         <td>月
		 <!--<select id='timeunit' class='ipt'>-->
             <!--<option value="年">年<option/>-->
             <!--<option value="月">月<option/>-->
             <!--<option value="日">日<option/>-->
		 <!--</select>-->
		 <!-- <input type='password' id='buy_time' name='newPass2' placeholder="年/月/日" class='ipt' data-rule="时间" /> -->
         </td>
      </tr>
	  <tr>
         <th>广告位价单价：</th>
         <td><input type='text' id='price' name='newPass'  class='ipt' /></td>
      </tr>
	  <tr>
         <th>广告位收益发放百分比：</th>
         <td><input type='text' id='proportion' name='newPass'  placeholder="填写范围0~1"  class='ipt' /></td>
      </tr>
   </table>
  </form>
</div>
<script>
  $(function(){initGrid()});
</script>


<script src="__ADMIN__/agent/staffsads.js?v=<?php echo $v; ?>" type="text/javascript"></script>

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