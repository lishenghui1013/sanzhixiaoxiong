<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:66:"E:\demo\sanzhixiaoxiong/wstmart/admin\view\users\account_list.html";i:1523179158;s:52:"E:\demo\sanzhixiaoxiong/wstmart/admin\view\base.html";i:1523179156;}*/ ?>
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
	<div id="query" >
        会员账号:<input type="text" name="loginName1" placeholder='账号'  id="loginName1" class="query" />
        <p style="float:left;height:25px;line-height:25px;">会员类型:</p>
        <select style="float:left;" name="userType" id="userType" class="query">
          <option value="">全部</option>
          <option value="0">普通会员</option>
          <option value="1">商家</option>
        </select>

	   		<p style="float:left;height:25px;line-height:25px;">账号状态:</p>
        <select style="float:left;" name="userStatus1" id="userStatus1" class="query">
          <option value="">全部</option>  
          <option value="1">启用</option>  
          <option value="0">停用</option>  
        </select>
	   		<input type="button" class="btn btn-blue" onclick="javascript:accountQuery()" value="查询">
	</div>
   <div style="clear:both"></div>
</div>
<div id="maingrid"></div>

<div id='accountBox' style='display:none'>
    <form id='accountForm' autocomplete="off">
    <table class='wst-form wst-box-top'>
       <tr>
          <th width='100'>账号<font color='red'>*</font>：</th>
          <td>
            <input type="hidden" name="userId" id="userId"> 
            <input type='text' id='loginName' name="loginName"  class='ipt' maxLength='20'
            data-rule="登录账号: required;length[6~20];remote(<?php echo url('admin/users/checkLoginKey'); ?>, userId)"
            /></td>
       </tr>
       <tr>
          <th>密码<font color='red'>*</font>：</th>
          <td><input type='password' id='loginPwd' data-rule="length[6~20];" placeholder="为空则说明不修改密码" class='ipt' maxLength='20'/></td>
       </tr>
       <tr>
          <th>会员状态<font color='red'>*</font>：</th>
          <td>
          		<label><input type='radio' id='userStatus' name="userStatus" class='ipt' value='1' >启用</label>
          		<label><input type='radio' id='userStatus' name="userStatus" checked class='ipt' value='0' >停用</label>
          </td>
          
       </tr>
    </table>
    </form>
  </div>
<script>
  $(function(){
    initGrid()

  });
</script>



<script src="__ADMIN__/users/account.js?v=<?php echo $v; ?>" type="text/javascript"></script>

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