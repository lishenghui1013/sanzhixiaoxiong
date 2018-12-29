<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:58:"E:\demo\sanzhixiaoxiong/wstmart/admin\view\roles\edit.html";i:1523179154;s:52:"E:\demo\sanzhixiaoxiong/wstmart/admin\view\base.html";i:1523179156;}*/ ?>
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

<link href="__ADMIN__/js/ztree/css/zTreeStyle/zTreeStyle.css?v=<?php echo $v; ?>" rel="stylesheet" type="text/css" />

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
<form autocomplete='off'>
<input type='hidden' id='roleId' class='ipt' value="<?php echo $object['roleId']; ?>"/>
<table class='wst-form wst-box-top'>
  <tr>
     <th width='120'>角色名称<font color='red'>*</font></th>
     <td><input type="text" id='roleName' class='ipt' value="<?php echo $object['roleName']; ?>" maxLength='20' data-rule="角色名称: required;"/></td>
  </tr>
  <tr>
     <th>角色备注</th>
     <td><input type="text" id='roleDesc' class='ipt' value="<?php echo $object['roleDesc']; ?>" style='width:70%' maxLength='100'/></td>
  </tr>
  <tr>
     <th valign='top'>权限</th>
     <td>
       <ul id="menuTree" class="ztree"></ul>
     </td>
  </tr>
  <tr>
     <td colspan='2' align='center'>
       <input type='button' value='保存' class='btn btn-blue' onclick='javascript:save()'>
       <input type='button' value='返回' class='btn' onclick='javascript:history.go(-1)'>
     </td>
  </tr>
</table>
</form>
<script>
var zTree,rolePrivileges = '<?php echo $object['privileges']; ?>'.split(',');
$(function(){
	var roleId = $('#roleId').val();
	var setting = {
		    check: {
				enable: true
			},
		    async: {
		        enable: true,
		        url:WST.U('admin/privileges/listQueryByRole'),
		        autoParam:["id", "name=n", "level=lv"],
		        otherParam:["roleId",roleId]
		    },
		    callback:{
		    	onNodeCreated:getNodes
		    }
	};
	$.fn.zTree.init($("#menuTree"), setting);
	zTree = $.fn.zTree.getZTreeObj("menuTree");
})
</script>


<script src="__ADMIN__/js/ztree/jquery.ztree.all-3.5.js?v=<?php echo $v; ?>"></script>
<script src="__ADMIN__/roles/roles.js?v=<?php echo $v; ?>" type="text/javascript"></script>

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