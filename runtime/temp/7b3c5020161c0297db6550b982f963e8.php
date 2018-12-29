<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:58:"E:\demo\sanzhixiaoxiong/wstmart/admin\view\areas\list.html";i:1523179154;s:52:"E:\demo\sanzhixiaoxiong/wstmart/admin\view\base.html";i:1523179156;}*/ ?>
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

<input type='hidden' id='h_areaId' value='<?php echo $pArea["areaId"]; ?>'/>
<input type='hidden' id='h_parentId' value='<?php echo $pArea["parentId"]; ?>'/>
<div class="l-loading" style="display: block" id="wst-loading"></div>
<div class="wst-toolbar">
  <?php if(($pArea['areaId'] != 0)): ?>
      上级地区：<?php echo $pArea['areaName']; ?>
  <button class="btn f-right" onclick='javascript:toReturn(0)'>返回</button>
  <?php endif; if(WSTGrant('DQGL_01')): ?>
  <button class="btn btn-green f-right" onclick='javascript:toEdit(0,<?php echo $pArea["areaId"]; ?>)'>新增</button>
  <?php endif; ?>
  <div style='clear:both'></div>
</div>
<div id="maingrid"></div>
<div id='areasBox' style='display:none'>
  <form id='areaForm' autocomplete="off">
  <input type='hidden' class='ipt' id='areaId' />
  <input type='hidden' class='ipt' id='parentId' />
  <table class='wst-form wst-box-top'>
     <tr>
        <th width='100'>地区名称<font color='red'>*</font>：</th>
        <td><input type='text' id='areaName' name="areaName" class='ipt' maxLength='20' style='width:200px;' onblur='javascript:letterOnblur(this)'/></td>
     </tr>
     <tr>
        <th width='100'>是否显示<font color='red'>*</font>：</th>
        <td height='24'>
           <label>
              <input type="radio" id="isShow1" name="isShow" class="ipt" value="1" checked>显示
           </label>
           <label>
              <input type="radio" id="isShow1" name="isShow" class="ipt" value="0">隐藏
           </label>
        </td>
     </tr>
     <tr>
        <th width='100'>排序字母<font color='red'>*</font>：</th>
        <td><input type='text' id='areaKey' name='areaKey' class='ipt' style='width:60px;'  maxLength='1'/></td>
     </tr>
     <tr>
        <th width='100'>排序号<font color='red'>*</font>：</th>
        <td><input type='text' id='areaSort' name='areaSort' class='ipt' style='width:60px;' onkeypress='return WST.isNumberKey(event);' onkeyup="javascript:WST.isChinese(this,1)" maxLength='10' value='0'/></td>
     </tr>
  </table>
  </form>
</div>


<script src="__ADMIN__/areas/areas.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script>
$(function(){initGrid();})
</script>

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