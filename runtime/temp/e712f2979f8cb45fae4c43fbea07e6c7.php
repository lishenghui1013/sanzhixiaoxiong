<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:67:"E:\demo\sanzhixiaoxiong/wstmart/admin\view\adpositions\hosting.html";i:1541139794;s:52:"E:\demo\sanzhixiaoxiong/wstmart/admin\view\base.html";i:1523179156;}*/ ?>
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

<style>
#preview img{
  max-width: 600px;
  max-height:150px;
}
</style>


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
<form id="hostForm">
<table class='wst-form wst-box-top'>
 
       

<tr>
          <th>价格<font color='red'>*</font>：</th>
          <td>
            <input type="text" style="margin:0px;vertical-align:baseline;" id="price" name="price" class="ipt" maxLength="20" value='' />
          </td>
       </tr>
<tr>
          <th>单位<font color='red'>*</font>：</th>
          <td>
            <input type="text" style="margin:0px;vertical-align:baseline;" id="timeunit" name="timeunit" class="ipt" maxLength="20" value='月' readonly="readonly" />
          </td>
       </tr>
	   <tr>
          <th>下发收益比例<font color='red'>*</font>：</th>
          <td>
            <input type="text" style="margin:0px;vertical-align:baseline;" id="proportion" name="proportion" class="ipt" maxLength="20" value='' />
          </td>
       </tr>
       <tr>
          <!--<th>托管截止时间<font color='red'>*</font>：</th>-->
          <th>托管月份<font color='red'>*</font>：</th>
          <!--<td>-->
            <!--<input type="text" style="margin:0px;vertical-align:baseline;" id="expiryTime" name="expiryTime" class="ipt" maxLength="20" value='' />-->
          <!--</td>-->
           <td>
           <select id="monthNum"  name="monthNum" class="ipt">
               <option value="1">1个月</option>
               <option value="2">2个月</option>
               <option value="3">3个月</option>
               <option value="4">4个月</option>
               <option value="5">5个月</option>
               <option value="6">6个月</option>
               <!--<option value="7">7</option>-->
               <!--<option value="8">8</option>-->
               <!--<option value="9">9</option>-->
               <!--<option value="10">10</option>-->
               <!--<option value="11">11</option>-->
               <!--<option value="12">12</option>-->
           </select>
           </td>
       </tr>
      
  
  <tr>
     <td colspan='2' align='center'>
       <input type="hidden" name="id" id="trpositionId" class="ipt" value="<?php echo $apid; ?>" />
       <input type="submit" value="提交" class='btn btn-blue' />
       <input type="button" onclick="javascript:history.go(-1)" class='btn' value="返回" />
     </td>
  </tr>
</table>
</form>
<script>
$(function(){
editInit1();

//时间插件
$("#adStartDate").ligerDateEditor().setValue('2018-08-09');
$("#adEndDate").ligerDateEditor().setValue('2018-08-09');
});

</script>



<script src="__ADMIN__/adpositions/adpositions.js?v=<?php echo $v; ?>" type="text/javascript"></script>

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