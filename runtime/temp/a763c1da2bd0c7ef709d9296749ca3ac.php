<?php if (!defined('THINK_PATH')) exit(); /*a:7:{s:68:"/www/wwwroot/sanzhixiaoxiong/wstmart/admin/view/sysconfigs/edit.html";i:1523179156;s:57:"/www/wwwroot/sanzhixiaoxiong/wstmart/admin/view/base.html";i:1523179156;s:71:"/www/wwwroot/sanzhixiaoxiong/wstmart/admin/view/sysconfigs/config0.html";i:1523179158;s:71:"/www/wwwroot/sanzhixiaoxiong/wstmart/admin/view/sysconfigs/config1.html";i:1523179156;s:71:"/www/wwwroot/sanzhixiaoxiong/wstmart/admin/view/sysconfigs/config2.html";i:1523179158;s:71:"/www/wwwroot/sanzhixiaoxiong/wstmart/admin/view/sysconfigs/config4.html";i:1523179156;s:71:"/www/wwwroot/sanzhixiaoxiong/wstmart/admin/view/sysconfigs/config5.html";i:1523179158;}*/ ?>
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
   <?php $grant = WSTGrant('SCPZ_02');  ?>
<div id="wst-tab-0" title="基础设置" class='wst-tab' lselected="true">
     <table class='wst-form wst-box-top'>
	  <tr>
	     <th width='150'>商城名称：</th>
	     <td><input type="text" id='mallName' class='ipt' value="<?php echo $object['mallName']; ?>" maxLength='100' placeholder='对外的简称'/></td>
	  </tr>
	  <tr>
	     <th width='150'>商城特色介绍：</th>
	     <td><input type="text" id='mallSlogan' class='ipt' value="<?php echo $object['mallSlogan']; ?>" maxLength='50' placeholder='商城特色短语介绍'/></td>
	  </tr>
	  <tr>
	     <th>商品是否需要审核：</th>
	     <td>
	     <label>
	       <input type='radio' id='isGoodsVerify1' name='isGoodsVerify' class='ipt' value='1' <?php if($object['isGoodsVerify']==1): ?>checked<?php endif; ?>>是
	     </label>
	     <label>
	       <input type='radio' id='isGoodsVerify0' name='isGoodsVerify' class='ipt' value='0' <?php if($object['isGoodsVerify']==0): ?>checked<?php endif; ?>>否
	     </label>
	     </td>
	  </tr>
	  <tr>
	     <th>底部设置：</th>
	     <td>
	       <textarea id='mallFooter' class='ipt' placeholder='显示在网站最底部的内容'><?php echo $object['mallFooter']; ?></textarea>
	     </td>
	  </tr>
	  <tr>
	     <th>访问统计：</th>
	     <td><textarea id='visitStatistics' class='ipt' placeholder='用于统计网站访问信息的代码'><?php echo $object['visitStatistics']; ?></textarea></td>
	  </tr>
	  <tr>
	     <th>客服QQ设置：</th>
	     <td><input type="text" id='serviceQQ' class='ipt' value="<?php echo $object['serviceQQ']; ?>" maxLength='200' placeholder='显示在网站的客服QQ好，多个QQ以，号分割'/></td>
	  </tr>
	  <tr>
	     <th>联系电话：</th>
	     <td><input type="text" id='serviceTel' class='ipt' value="<?php echo $object['serviceTel']; ?>" maxLength='200' placeholder="显示在网站的联系电话"/></td>
	  </tr>
	  <tr>
	     <th>联系邮箱：</th>
	     <td><input type="text" id='serviceEmail' class='ipt' value="<?php echo $object['serviceEmail']; ?>" maxLength='200' placeholder="显示在网站的联系邮箱"/></td>
	  </tr>
	  <tr>
	     <th>热搜关键词：</th>
	     <td><input type="text" id='hotWordsSearch' class='ipt' value="<?php echo $object['hotWordsSearch']; ?>" maxLength='100' placeholder='商城搜索栏下的引导搜索词'/></td>
	  </tr>
	  <tr>
	     <th>热搜广告词（商品）：</th>
	     <td><input type="text" id='adsGoodsWordsSearch' class='ipt' value="<?php echo $object['adsGoodsWordsSearch']; ?>" maxLength='100' placeholder='商城搜索栏里的搜索词'/></td>
	  </tr>
	  <tr>
	     <th>热搜广告词（店铺）：</th>
	     <td><input type="text" id='adsShopWordsSearch' class='ipt' value="<?php echo $object['adsShopWordsSearch']; ?>" maxLength='100' placeholder='商城搜索栏里的搜索词'/></td>
	  </tr>
	  <tr>
	     <th>账号禁用关键字：</th>
	     <td><textarea id='registerLimitWords' class='ipt' placeholder='禁止用户注册时的账号内容'><?php echo $object['registerLimitWords']; ?></textarea></td>
	  </tr>
	  <tr>
	  	 <?php if(($grant)): ?>
	     <td colspan='2' align='center'>
	        <input type="button" value="保存" class='btn btn-blue' onclick='javascript:edit()'/>
            <input type="reset" class='btn' value="重置" />
	     </td>
	     <?php endif; ?>
	  </tr>
	 </table>
</div><div id="wst-tab-1" title="服务器设置" class='wst-tab'>
     <table class='wst-form wst-box-top'>
      <tr>
	     <td colspan='2' class='head-ititle'>邮件服务器设置</td>
	  </tr>
	  <tr>
	     <th width='150'>SMTP服务器：</th>
	     <td><input type="text" id='mailSmtp' class='ipt' maxLength='100' value='<?php echo $object["mailSmtp"]; ?>'/></td>
	  </tr>
	  <tr>
	     <th>SMTP端口：</th>
	     <td><input type="text" id='mailPort' class='ipt' maxLength='10' value='<?php echo $object["mailPort"]; ?>'/></td>
	  </tr>
	  <tr>
	     <th>是否验证SMTP：</th>
	     <td>
	     <label>
	         <input type='radio' id='mailAuth1' name='mailAuth' class='ipt' value='1' <?php if($object['mailAuth']==1): ?>checked<?php endif; ?>/>是
	     </label>
	     <label>
	         <input type='radio' id='mailAuth0' name='mailAuth' class='ipt' value='0' <?php if($object['mailAuth']==0): ?>checked<?php endif; ?>/>否
	     </label>
	     </td>
	  </tr>
	  <tr>
	     <th>SMTP发件人邮箱：</th>
	     <td><input type="text" id='mailAddress' class='ipt' value='<?php echo $object["mailAddress"]; ?>' maxLength='100'/></td>
	  </tr>
	  <tr>
	     <th>SMTP登录账号：</th>
	     <td><input type="text" id='mailUserName' class='ipt' value='<?php echo $object["mailUserName"]; ?>' maxLength='100'/></td>
	  </tr>
	  <tr>
	     <th>SMTP登录密码：</th>
	     <td><input type="text" id='mailPassword' class='ipt' value='<?php echo $object["mailPassword"]; ?>' maxLength='100'/></td>
	  </tr>
	  <tr>
	     <th>发件人名称：</th>
	     <td><input type="text" id='mailSendTitle' class='ipt' value='<?php echo $object["mailSendTitle"]; ?>' maxLength='100'/></td>
	  </tr>
	  <tr>
	     <td colspan='2' class='head-ititle'>短信服务器设置</td>
	  </tr>
	  <tr>
	     <th>开启手机验证：</th>
	     <td>
	     <label>
	         <input type='radio' id='smsOpen1' name='smsOpen' class='ipt' value='1' <?php if($object['smsOpen']==1): ?>checked<?php endif; ?>/>是
	     </label>
	     <label>
	         <input type='radio' id='smsOpen0' name='smsOpen' class='ipt' value='0' <?php if($object['smsOpen']==0): ?>checked<?php endif; ?>/>否
	     </label>
	     </td>
	  </tr>
	  <tr>
	     <th>短信账号：</th>
	     <td><input type="text" id='smsKey' class='ipt' value="<?php echo $object['smsKey']; ?>" maxLength='100'/>&nbsp;&nbsp;点击<a target='_blank' href='http://www.smschinese.cn/'>购买</a></td>
	  </tr>
	  <tr>
	     <th>短信密码：</th>
	     <td><input type="text" id='smsPass' class='ipt' value="<?php echo $object['smsPass']; ?>" maxLength='100'/></td>
	  </tr>
	  <tr>
	     <th>号码每日发送数：</th>
	     <td><input type="text" id='smsLimit' class='ipt' value="<?php echo $object['smsLimit']; ?>" maxLength='100'/></td>
	  </tr>
	  <tr>
	     <th>开启短信发送验证码：</th>
	     <td>
	     <label>
	         <input type='radio' id='smsVerfy1' class='ipt' name='smsVerfy' value='1' <?php if($object['smsVerfy']==1): ?>checked<?php endif; ?>/>是
	     </label>
	     <label>
	         <input type='radio' id='smsVerfy0' class='ipt' name='smsVerfy' value='0' <?php if($object['smsVerfy']==0): ?>checked<?php endif; ?>/>否
	     </label>
	     </td>
	  </tr>
	  <tr>
	  	<?php if(($grant)): ?>
	     <td colspan='2' align='center'>
	        <input type="button" value="保存" class='btn btn-blue' onclick='javascript:edit()'/>
            <input type="reset" class='btn' value="重置" />
	     </td>
	     <?php endif; ?>
	  </tr>
	 </table>
</div><div id="wst-tab-2" title="运营设置" class='wst-tab'>
     <table class='wst-form wst-box-top'>
	  <tr>
	     <th width='150'>开启积分支付：</th>
	     <td>
	     <label>
	         <input type='radio' id='isOpenScorePay1' name='isOpenScorePay' class='ipt' value='1' <?php if($object['isOpenScorePay']==1): ?>checked<?php endif; ?>>是
	     </label>
	     <label>
	         <input type='radio' id='isOpenScorePay0' name='isOpenScorePay' class='ipt' value='0' <?php if($object['isOpenScorePay']==0): ?>checked<?php endif; ?>>否
	     </label>
	     </td>
	  </tr>
	  <tr>
	     <th>开启下单获积分：</th>
	     <td>
	     <label>
	         <input type='radio' id='isOrderScore1' name='isOrderScore' class='ipt' value='1' <?php if($object['isOrderScore']==1): ?>checked<?php endif; ?>>是
	     </label>
	     <label>
	         <input type='radio' id='isOrderScore0' name='isOrderScore' class='ipt' value='0' <?php if($object['isOrderScore']==0): ?>checked<?php endif; ?>>否
	     </label>
	     </td>
	  </tr>
	  <tr>
	     <th>开启评价获积分：</th>
	     <td>
	     <label>
	         <input type='radio' id='isAppraisesScore1' name='isAppraisesScore' class='ipt' value='1' <?php if($object['isAppraisesScore']==1): ?>checked<?php endif; ?>>是
	     </label>
	     <label>
	         <input type='radio' id='isAppraisesScore0' name='isAppraisesScore' class='ipt' value='0' <?php if($object['isAppraisesScore']==0): ?>checked<?php endif; ?>>否
	     </label>
	     </td>
	  </tr>
	  <tr style='display:none'>
	     <th>积分与金钱兑换比例：</th>
	     <td><input type="text" id='scoreCashRatio' class='ipt' value="<?php echo $object['scoreCashRatio']; ?>" maxLength='20'/></td>
	  </tr>
	  <tr style='display:none'>
	     <th>结算金额设置：</th>
	     <td><input type="text" id='settlementStartMoney' class='ipt' value="<?php echo $object['settlementStartMoney']; ?>" maxLength='10'/></td>
	  </tr>
	  <tr>
	     <th>未支付订单失效时间：</th>
	     <td>下单后<input type="text" id='autoCancelNoPayDays' class='ipt' value="<?php echo $object['autoCancelNoPayDays']; ?>" maxLength='3' style='width:40px'/>小时</td>
	  </tr>
	  <tr>
	     <th>自动收货期限：</th>
	     <td>发货后<input type="text" id='autoReceiveDays' class='ipt' value="<?php echo $object['autoReceiveDays']; ?>" maxLength='3' style='width:40px'/>天自动收货</td>
	  </tr>
	  <tr>
	     <th>自动评价期限：</th>
	     <td>确认收货后<input type="text" id='autoAppraiseDays' class='ipt' value="<?php echo $object['autoAppraiseDays']; ?>" maxLength='3' style='width:40px'/>天自动好评</td>
	  </tr>
	  <tr>
	  	<?php if(($grant)): ?>
	     <td colspan='2' align='center'>
	        <input type="button" value="保存" class='btn btn-blue' onclick='javascript:edit()'/>
            <input type="reset" class='btn' value="重置" />
	     </td>
	     <?php endif; ?>
	  </tr>
	 </table>
</div><link rel="stylesheet" type="text/css" href="__STATIC__/plugins/colpick/css/colpick.css" />
<script src="__STATIC__/plugins/colpick/js/colpick.js"></script>

<div id="wst-tab-5" title="图片设置" class='wst-tab'>
    <table class='wst-form wst-box-top'>
	<tr>
	 <th>水印位置：</th>
	 <td>
	 	<label><input type="radio" id='watermarkPosition' name='watermarkPosition' class='ipt' value="0" <?php if(($object['watermarkPosition']==0)): ?>checked<?php endif; ?> />无</label>
	 	<label><input type="radio" id='watermarkPosition' name='watermarkPosition' class='ipt' value="1" <?php if(($object['watermarkPosition']==1)): ?>checked<?php endif; ?> />左上</label>
	 	<label><input type="radio" id='watermarkPosition' name='watermarkPosition' class='ipt' value="3" <?php if(($object['watermarkPosition']==3)): ?>checked<?php endif; ?> />右上</label>
	 	<label><input type="radio" id='watermarkPosition' name='watermarkPosition' class='ipt' value="5" <?php if(($object['watermarkPosition']==5)): ?>checked<?php endif; ?> />居中</label>
	 	<label><input type="radio" id='watermarkPosition' name='watermarkPosition' class='ipt' value="7" <?php if(($object['watermarkPosition']==7)): ?>checked<?php endif; ?> />左下</label>
	 	<label><input type="radio" id='watermarkPosition' name='watermarkPosition' class='ipt' value="9" <?php if(($object['watermarkPosition']==9)): ?>checked<?php endif; ?> />右下</label>
	 	<span style="color:gray;">设置为"无"则视为关闭水印</span>
	 </td>
	</tr>
	  <tr>
	     <th width='150'>水印文字：</th>
	     <td>
	     	<input type="text" id='watermarkWord' class='ipt' value="<?php echo $object['watermarkWord']; ?>" maxLength='50' />
	     	<span style="color:gray;">当文字和图片同时存在时以文字为主</span>
	     </td>
	  </tr>
	  <tr>
	     <th>水印文字大小：</th>
	     <td>
	     	<input type="text" id='watermarkSize' class='ipt' value="<?php echo $object['watermarkSize']; ?>" maxLength='2'/>
	     	<span style="color:gray;">建议大小为20</span>
	     </td>
	  </tr>
	  <tr>
	     <th>水印文字颜色：</th>
	     <td>
	     	<input type="text" id='watermarkColor' class='ipt' value="<?php echo $object['watermarkColor']; ?>" />
	     	<span style="color:gray;">仅支持16进制的颜色：如#00FF00</span>
	     </td>
	  </tr>
	  <tr>
	     <th>水印文字字体路径：</th>
	     <td>
	     	<input type="text" id='watermarkTtf' class='ipt' value="<?php echo $object['watermarkTtf']; ?>" placeholder="如：WSTMart/ttf/1.ttf" />
	     	<span style="color:gray;">后缀为.ttf,若留空则使用默认字体</span>
	     </td>
	  </tr>
	  <tr>
	     <th>水印文件：</th>
	     <td>
	     	<div id='watermarkFilePicker'>上传图标</div><span id='watermarkFileMsg'></span>
	     	<input type="hidden" id='watermarkFile' class='ipt' value="<?php echo $object['watermarkFile']; ?>" />
	     </td>
	  </tr>
	  	<tr>
          <th width='100'>水印图预览：</th>
          <td>
          	<div style="min-height:70px;" id="preview">
          	<?php if((isset($object['watermarkFile']))): ?>
          	 <img id='watermarkFilePrevw' src="__ROOT__/<?php echo $object['watermarkFile']; ?>" style="max-height:75px;" /> 
          	<?php endif; ?>
          	</div>
          	<span style="color:gray;">水印图最终大小由上传的图片大小决定</span>
          </td>
       </tr>
	  
	  <tr>
	     <th>水印透明度：</th>
	     <td>
	     	<input type="text" id='watermarkOpacity' class='ipt' value="<?php echo $object['watermarkOpacity']; ?>" />
	     	<br>
	     	<span style="color:gray;">水印的透明度,可选值为0-100。当设置为100时则为不透明</span>
	     </td>
	  </tr>
      <tr>
	     <th>商城Logo：</th>
	     <td>
	     <div id='mallLogoPicker'>请上传商城Logo</div><span id='mallLogoMsg'></span>
	     <img src='__ROOT__/<?php echo $object["mallLogo"]; ?>' width='120' hiegth='120' id='mallLogoPrevw'/>
	     <input type="hidden" id='mallLogo' class='ipt' value='<?php echo $object["mallLogo"]; ?>'/>
	     </td>
	  </tr>
	  <tr>
	     <th>默认店铺头像：</th>
	     <td>
	     <div id='shopLogoPicker'>请上传默认店铺头像</div><span id='shopLogoMsg'></span>
	     <img src='__ROOT__/<?php echo $object["shopLogo"]; ?>' width='120' hiegth='120' id='shopLogoPrevw'/>
	     <input type="hidden" id='shopLogo' class='ipt' value='<?php echo $object["shopLogo"]; ?>'/>
	     </td>
	  </tr>
	  <tr>
	     <th>默认会员头像：</th>
	     <td>
	     <div id='userLogoPicker'>请上传默认会员头像</div><span id='userLogoMsg'></span>
	     <img src='__ROOT__/<?php echo $object["userLogo"]; ?>' width='120' hiegth='120' id='userLogoPrevw'/>
	     <input type="hidden" id='userLogo' class='ipt' value='<?php echo $object["userLogo"]; ?>'/>
	     </td>
	  </tr>
	  <tr>
	     <th>默认商品图片：</th>
	     <td>
	     <div id='goodsLogoPicker'>请上传默认商品图片</div><span id='goodsLogoMsg'></span>
	     <img src='__ROOT__/<?php echo $object["goodsLogo"]; ?>' width='120' hiegth='120' id='goodsLogoPrevw'/>
	     <input type="hidden" id='goodsLogo' class='ipt' value='<?php echo $object["goodsLogo"]; ?>'/>
	     </td>
	  </tr>
	  <tr>
	  	<?php if(($grant)): ?>
	     <td colspan='2' align='center'>
	        <input type="button" value="保存" class='btn btn-blue' onclick='javascript:edit()'/>
            <input type="reset" class='btn' value="重置" />
	     </td>
	     <?php endif; ?>
	  </tr>
	 </table>
</div><div id="wst-tab-5" title="SEO设置" class='wst-tab'>
     <table class='wst-form wst-box-top'>
	  <tr>
	     <th width='150'>商城标题：</th>
	     <td><input type="text" id='seoMallTitle' class='ipt' value="<?php echo $object['seoMallTitle']; ?>" maxLength='100'/></td>
	  </tr>
	  <tr>
	     <th>商城关键字：</th>
	     <td><input type="text" id='seoMallKeywords' class='ipt' value="<?php echo $object['seoMallKeywords']; ?>" maxLength='100'/></td>
	  </tr>
	  <tr>
	     <th>商城描述：</th>
	     <td><input type="text" id='seoMallDesc' class='ipt' value="<?php echo $object['seoMallDesc']; ?>" maxLength='100'/></td>
	  </tr>
	  <tr>
	  	<?php if(($grant)): ?>
	     <td colspan='2' align='center'>
	        <input type="button" value="保存" class='btn btn-blue' onclick='javascript:edit()'/>
            <input type="reset" class='btn' value="重置" />
	     </td>
	     <?php endif; ?>
	  </tr>
	 </table>
</div>
</div>
</form>


<script type='text/javascript' src='__STATIC__/plugins/webuploader/webuploader.js?v=<?php echo $v; ?>' type="text/javascript"></script>
<script src="__ADMIN__/sysconfigs/sysconfigs.js?v=<?php echo $v; ?>" type="text/javascript"></script>

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