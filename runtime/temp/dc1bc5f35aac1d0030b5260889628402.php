<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:64:"E:\demo\sanzhixiaoxiong/wstmart/admin\view\friendlinks\list.html";i:1523179156;s:52:"E:\demo\sanzhixiaoxiong/wstmart/admin\view\base.html";i:1523179156;}*/ ?>
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

<link rel="stylesheet" type="text/css" href="__STATIC__/plugins/webuploader/webuploader.css?v=<?php echo $v; ?>" />

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
<?php if(WSTGrant('YQGL_01')): ?>
<div class="wst-toolbar">
   <button class="btn btn-green f-right" onclick='javascript:toEdit(0)'>新增</button>
   <div style="clear:both"></div>
</div>
<?php endif; ?>
<div id="maingrid"></div>

<div id='friendlinkBox' style='display:none'>
    <form id='friendlinkForm' method="post" autocomplete="off">
    <table class='wst-form wst-box-top'>
       <tr>
          <th width='100'>网站名称<font color='red'>*</font>：</th>
          <td><input type='text' id='friendlinkName' name="friendlinkName"  class='ipt' maxLength='20'/></td>
       </tr>
       <tr>
          <th width='100'>图标：</th>
          <td>
          <div id='adFilePicker'>上传图标</div><span id='uploadMsg'></span>
          <input type='hidden' id='friendlinkIco' class='ipt'/>
          </td>
       </tr>
       <tr>
          <th width='100'>预览图：</th>
          <td>
            <div style="min-height:70px;" id="preview"></div>
          </td>
       </tr>
       <tr>
          <th width='100'>网址<font color='red'>*</font>：</th>
          <td><input type='text' id='friendlinkUrl' name="friendlinkUrl" class='ipt' maxLength='120'/></td>
       </tr>
       <tr>
          <th width='100'>网站排序号：</th>
          <td><input type='text' id='friendlinkSort'  class='ipt' maxLength='20'/></td>
       </tr>
       
    </table>
    </form>

  </div>
<script>
$(function(){initGrid()});
</script>



<script src="__ADMIN__/friendlinks/friendlinks.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script type='text/javascript' src='__STATIC__/plugins/webuploader/webuploader.js?v=<?php echo $v; ?>'></script>

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