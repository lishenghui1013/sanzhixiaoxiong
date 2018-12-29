<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:59:"E:\demo\sanzhixiaoxiong/wstmart/admin\view\staffs\edit.html";i:1532141470;s:52:"E:\demo\sanzhixiaoxiong/wstmart/admin\view\base.html";i:1523179156;}*/ ?>
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
<form autocomplete='off'> 
<input type='hidden' id='staffId' class='ipt' value="<?php echo $object['staffId']; ?>"/>
<table class='wst-form wst-box-top'>
  <tr>
     <th width='150'>登录账号：</th>
     <td width='290'><?php echo $object['loginName']; ?></td>
     <td rowspan='5'>
       <div style='border:1px solid #ccc;width:130px;height:130px;margin-bottom:5px;'>
           <img id='prevwPhoto' height='130' width='130' src='__ROOT__/<?php if($object["staffPhoto"] != ''): ?><?php echo $object["staffPhoto"]; else: ?>__ADMIN__/img/img_mrtx_gly.png<?php endif; ?>'/>
       </div>
       <div id='photoPicker' style='margin-left:32px;'>上传头像<span id='uploadMsg'></span></div>
       <input type='hidden' id='staffPhoto' class='ipt' value='<?php echo $object["staffPhoto"]; ?>'/>
     </td>
  </tr>
  <tr>
     <th>职员名称<font color='red'>*</font>：</th>
     <td><input type="text" id='staffName' class='ipt' value="<?php echo $object['staffName']; ?>" maxLength='20' data-rule="职员名称: required;"/></td>
  </tr>
  <tr>
     <th>职员编号：</th>
     <td><input type="text" id='staffNo' class='ipt' value="<?php echo $object['staffNo']; ?>" maxLength='20'/></td>
  </tr>
  <tr>
     <th>角色：</th>
     <td>
     <select id='staffRoleId' class='ipt'>
        <option value='0'>请选择</option>
        <?php if(is_array($roles) || $roles instanceof \think\Collection): $i = 0; $__LIST__ = $roles;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
		<option value="<?php echo $vo['roleId']; ?>" <?php if($object['staffRoleId'] == $vo['roleId']): ?>selected<?php endif; ?>><?php echo $vo['roleName']; ?></option>
		<?php endforeach; endif; else: echo "" ;endif; ?>
     </select>
     </td>
  </tr>
  <tr>
     <th>所在地：</th>
     <td>
     <select id="areaId1" class='ipt j-ipt' level="0" data-rule="代理地区: required;" onchange="WST.ITAreas({id:'areaId1',val:this.value,className:'ipt j-ipt'});">
  <option value="-1">-请选择-</option>
  <?php if(is_array($areaList) || $areaList instanceof \think\Collection): $i = 0; $__LIST__ = $areaList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
  <option <?php if($object['pro'] == $vo['areaId']): ?>selected<?php endif; ?> value="<?php echo $vo['areaId']; ?>"><?php echo $vo['areaName']; ?></option>
  <?php endforeach; endif; else: echo "" ;endif; ?>
</select>
     </td>
  </tr>
  <tr>
     <th>工作状态：</th>
     <td>
       <label>
           <input id="workStatus1" name="workStatus" value="1" class='ipt' <?php if($object['workStatus'] == 1): ?>checked<?php endif; ?> type="radio">在职
       </label>
       <label>
           <input id="workStatus0" name="workStatus" value="0" class='ipt' <?php if($object['workStatus'] == 0): ?>checked<?php endif; ?> type="radio">离职
       </label>
             
     </td>
  </tr>
  <tr>
     <th>账号状态：</th>
     <td colspan='2'>
       <label>
          <input type='radio' id='staffStatus1' class='ipt' name='staffStatus' <?php if($object['staffStatus'] == 1): ?>checked<?php endif; ?> value='1'>开启
       </label>
       <label>
          <input type='radio' id='staffStatus0' class='ipt' name='staffStatus' <?php if($object['staffStatus'] == 0): ?>checked<?php endif; ?> value='0'>停用
       </label>
     </td>
  </tr>
  <tr>
     <td colspan='3' align='center'>
       <input type='button' value='保存' class='btn btn-blue' onclick='javascript:save()'>
       <input type='button' value='返回' class='btn' onclick='javascript:history.go(-1)'>
     </td>
  </tr>
</table>
</form>
<script>
$(function(){
	WST.upload({
  	  pick:'#photoPicker',
  	  formData: {dir:'staffs'},
  	  accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
  	  callback:function(f){
  		  var json = WST.toAdminJson(f);
  		  if(json.status==1){
  			$('#uploadMsg').empty().hide();
  			$('#prevwPhoto').attr('src',WST.conf.ROOT+'/'+json.savePath+json.name);
  			$('#staffPhoto').val(json.savePath+json.name);
  		  }
	  },
	  progress:function(rate){
	      $('#uploadMsg').show().html('已上传'+rate+"%");
	  }
    });
});
</script>


<script type='text/javascript' src='__STATIC__/plugins/webuploader/webuploader.js?v=<?php echo $v; ?>' type="text/javascript"></script>
<script src="__ADMIN__/staffs/staffs.js?v=<?php echo $v; ?>" type="text/javascript"></script>

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