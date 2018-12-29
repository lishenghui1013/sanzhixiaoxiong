<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:58:"E:\demo\sanzhixiaoxiong/wstmart/admin\view\users\edit.html";i:1523179156;s:52:"E:\demo\sanzhixiaoxiong/wstmart/admin\view\base.html";i:1523179156;}*/ ?>
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
<form id="userForm" autocomplete="off" >
<table class='wst-form wst-box-top'>
  <tr>
      <th width='150'>账号<font color='red'>*</font>：</th>
          <td width='370'>
            <?php if(($data['userId']>0)): ?>
              <?php echo $data['loginName']; else: ?>
              <input type="text" class="ipt" id="loginName" name="loginName"  />
            <?php endif; ?>
              
          </td>
          <td rowspan="5">
            <div id="preview" >
                <img src="__ROOT__/<?php if($data['userPhoto']==''): ?><?php echo WSTConf('CONF.userLogo'); else: ?><?php echo $data['userPhoto']; endif; ?>"  height="150" />
            </div>
            <div id='adFilePicker' style="margin-left:40px;">上传头像</div>
            <input type="hidden" id="userPhoto" class="ipt" />
            <span id='uploadMsg'></span>

          </td>
       </tr>
       <?php if(((int)$data['userId']==0)): ?>
         <tr>
            <th>密码<font color='red'>*</font>：</th>
            <td><input type="password" id='loginPwd' class='ipt' maxLength='20' value='66666666' data-rule="登录密码: required;length[6~20]" data-target="#msg_loginPwd"/>
               <span id='msg_loginPwd'>(默认为66666666)</span>
             </td>
         </tr>
       <?php endif; ?>
       <tr>
          <th>用户名<font color='red'>*</font>：</th>
          <td>
              <input type="text" class="ipt" id="userName" name="userName" value="<?php echo $data['userName']; ?>" />
          </td>
       </tr>
       <tr>
          <th>性别<font color='red'>*</font>：</th>
          <td>
            <label><input type="radio" class="ipt" id="userSex" name="userSex" <?=($data['userSex']==1)?'checked':'';?> value="1" />男</label>
            <label><input type="radio" class="ipt" id="userSex" name="userSex" <?=($data['userSex']==2)?'checked':'';?> value="2" />女</label>
            <label><input type="radio" class="ipt" id="userSex" name="userSex" <?=($data['userSex']==0)?'checked':'';?> value="0" />保密</label>
          </td>
       </tr>
       <tr>
          <th>手机号码<font color='red'>*</font>：</th>
          <td>
              <input type="text" class="ipt" id="userPhone" name="userPhone" value="<?php echo $data['userPhone']; ?>" />
          </td>
       </tr>
       <tr>
          <th>电子邮箱<font color='red'>*</font>：</th>
          <td>
              <input type="text" class="ipt" id="userEmail" name="userEmail" value="<?php echo $data['userEmail']; ?>" />
          </td>
       </tr>
       <tr>
          <th>会员积分<font color='red'>*</font>：</th>
          <td>
              <input type="text" class="ipt" id="userScore" name="userScore" value="<?php echo $data['userScore']; ?>" />
          </td>
       </tr>
       <tr>
          <th>会员历史积分<font color='red'>*</font>：</th>
          <td>
              <input type="text" class="ipt" id="userTotalScore" name="userTotalScore" value="<?php echo $data['userTotalScore']; ?>" />
          </td>
       </tr>
       <tr>
          <th>QQ<font color='red'>*</font>：</th>
          <td>
              <input type="text" class="ipt" id="userQQ" name="userQQ" value="<?php echo $data['userQQ']; ?>" />
          </td>
       </tr>
       
       <?php if(((int)$data['userId']==0)): ?>
         <tr>
            <th>会员状态<font color='red'>*</font>：</th>
            <td>
                <label><input type="radio" class="ipt" id="userStatus" name="userStatus" <?=($data['userStatus']!==0)?'checked':'';?> value="1" />启用</label>
                <label><input type="radio" class="ipt" id="userStatus" name="userStatus" <?=($data['userStatus']===0)?'checked':'';?> value="0" />停用</label>
            </td>
         </tr>
       <?php endif; ?>
  
  <tr>
     <td colspan='2' align='center'>
       <input type="hidden" name="id" id="userId" class="ipt" value="<?=(int)$data['userId']?>" />
       <input type="submit" value="提交" class='btn btn-blue' />
       <input type="button" onclick="javascript:history.go(-1)" class='btn' value="返回" />
     </td>
  </tr>
</table>
</form>
<script>
$(function(){editInit()});
</script>



<script type='text/javascript' src='__STATIC__/plugins/webuploader/webuploader.js?v=<?php echo $v; ?>'></script>
<script src="__ADMIN__/users/users.js?v=<?php echo $v; ?>" type="text/javascript"></script>

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