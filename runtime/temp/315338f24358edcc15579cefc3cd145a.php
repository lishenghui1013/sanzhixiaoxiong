<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:69:"E:\demo\sanzhixiaoxiong/wstmart/admin\view\hbconfigs\edit_stocks.html";i:1545015048;s:52:"E:\demo\sanzhixiaoxiong/wstmart/admin\view\base.html";i:1523179156;}*/ ?>
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
<style>
body{overflow:hidden}
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

<style>
input[type="text"]{width:70%}
textarea{width:70%;height:100px;}
#wst-tab-5 input[type="text"]{width:50%}
</style>
<div class="l-loading" style="display: block" id="wst-loading"></div>
<form autocomplete='off'> 
<div id="wst-tabs" style="width:100%; height:100%;overflow: hidden; border: 1px solid #D3D3d3;" class="liger-tab">
   <!--<div id="wst-tab-5" title="SEO设置" class='wst-tab'>-->
      <table class='wst-form wst-box-top'>
         <!--<tr>-->
            <!--<th>开启消费股衰减：</th>-->
            <!--<td><input type="text" id='Hbconfigs' class='ipt' value="<?php echo $object['fieldValue']; ?>" maxLength='100' data-rule='扣除比例:required;range[0.00~1]'/></td>-->
         <!--</tr>-->
         <tr>
            <th>开启消费股衰减：</th>
            <td>
               <label>
                  <input type='radio' id='isReduceStocks1' name='isReduceStocks' class='ipt' value='1' <?php if($object['fieldValue']==1): ?>checked<?php endif; ?>>是
               </label>
               <label>
                  <input type='radio' id='isReduceStocks0' name='isReduceStocks' class='ipt' value='0' <?php if($object['fieldValue']==0): ?>checked<?php endif; ?>>否
               </label>
            </td>
         </tr>
         <tr>
            <td colspan='2' align='center'>
               <input type="button" value="保存" class='btn btn-blue' onclick='javascript:edit()'/>
               <input type="reset" class='btn' value="重置" />
            </td>
         </tr>
      </table>
   <!--</div>-->
</div>
</form>


<script type='text/javascript' src='__STATIC__/plugins/webuploader/webuploader.js?v=<?php echo $v; ?>' type="text/javascript"></script>
<script src="__ADMIN__/hbconfigs/stocks_configs.js?v=<?php echo $v; ?>" type="text/javascript"></script>

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