<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:61:"E:\demo\sanzhixiaoxiong/wstmart/home\view\default\regist.html";i:1523179170;s:59:"E:\demo\sanzhixiaoxiong/wstmart/home\view\default\base.html";i:1540022586;s:66:"E:\demo\sanzhixiaoxiong/wstmart/home\view\default\header_lite.html";i:1540023350;s:65:"E:\demo\sanzhixiaoxiong/wstmart/home\view\default\header_top.html";i:1540022971;s:65:"E:\demo\sanzhixiaoxiong/wstmart/home\view\default\shop_apply.html";i:1523179176;s:61:"E:\demo\sanzhixiaoxiong/wstmart/home\view\default\footer.html";i:1540022560;}*/ ?>
<!doctype html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>用户注册-<?php echo WSTConf('CONF.mallName'); ?><?php echo WSTConf('CONF.mallTitle'); ?></title>
<meta name="auther" content="WSTMart,www.wstmart.net" />
<meta name="copyright" content="Copyright©2016-2066 Powered By WSTMart" />

<link href="__STYLE__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet">

<link href="__STATIC__/plugins/validator/jquery.validator.css?v=<?php echo $v; ?>" rel="stylesheet">
<link href="__STYLE__/css/login.css?v=<?php echo $v; ?>" rel="stylesheet">

<script type="text/javascript" src="__STATIC__/js/jquery.min.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="__STATIC__/plugins/layer/layer.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="__STATIC__/plugins/lazyload/jquery.lazyload.min.js?v=<?php echo $v; ?>"></script>
<script type='text/javascript' src='__STATIC__/js/common.js?v=<?php echo $v; ?>'></script>

<script type='text/javascript' src='__STYLE__/js/common.js?v=<?php echo $v; ?>'></script>


<?php if(((int)session('WST_USER.userId')<=0)): ?>
<link href="__STATIC__/plugins/validator/jquery.validator.css?v=<?php echo $v; ?>" rel="stylesheet">
<link href="__STYLE__/css/login.css?v=<?php echo $v; ?>" rel="stylesheet">
<script type="text/javascript" src="__STATIC__/plugins/validator/jquery.validator.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="__STATIC__/plugins/validator/local/zh-CN.js?v=<?php echo $v; ?>"></script>
<script type='text/javascript' src='__STYLE__/js/login.js?v=<?php echo $v; ?>'></script>
<?php endif; ?>

<script>
window.conf = {
		"ROOT"      : "__ROOT__", 
		"APP"       : "__APP__", 
		"STATIC"    : "__STATIC__", 
		"SUFFIX"    : "<?php echo config('url_html_suffix'); ?>", 
		"SMS_VERFY" : "<?php echo WSTConf('CONF.smsVerfy'); ?>",
    	"SMS_OPEN"  : "<?php echo WSTConf('CONF.smsOpen'); ?>",
    	"GOODS_LOGO": "<?php echo WSTConf('CONF.goodsLogo'); ?>",
    	"SHOP_LOGO" : "<?php echo WSTConf('CONF.shopLogo'); ?>",
    	"MALL_LOGO" : "<?php echo WSTConf('CONF.mallLogo'); ?>",
    	"USER_LOGO" : "<?php echo WSTConf('CONF.userLogo'); ?>",
    	"IS_LOGIN"  : "<?php if((int)session('WST_USER.userId')>0): ?>1<?php else: ?>0<?php endif; ?>",
    	"TIME_TASK" : "1"
	}
$(function() {
	WST.initVisitor();
});
</script>
</head>
<body>

    <?php $liteTitle = "欢迎注册".WSTConf('CONF.mallName'); ?>
	<div class="wst-header">
    <div class="wst-nav">
		<ul class="headlf" style='float:left;width:500px;'>
		<?php if(session('WST_USER.userId') >0): ?>
		   <li class="drop-info">
			  <div class="drop-infos">
			  <!--<a href="<?php echo Url('home/users/index'); ?>">-->
				  欢迎您，<?php echo session('WST_USER.userName')?session('WST_USER.userName'):session('WST_USER.loginName'); ?>
			  <!--</a>-->
			  </div>
			  <div class="wst-tag dorpdown-user">
			  	<div class="wst-tagt">
			  	   <div class="userImg" >
				  	<img class='usersImg' data-original="__ROOT__/<?php echo session('WST_USER.userPhoto'); ?>"/>
				   </div>	
				  <div class="wst-tagt-n">
				    <div>
					  	<span class="wst-tagt-na"><?php echo session('WST_USER.userName')?session('WST_USER.userName'):session('WST_USER.loginName'); ?></span>
					  	<?php if((int)session('WST_USER.rankId') > 0): ?>
					  		<img src="__ROOT__/<?php echo session('WST_USER.userrankImg'); ?>" title="{:<?php echo session('WST_USER.rankName'); ?>"/>
					  	<?php endif; ?>
				  	</div>
				  	<!--<div class='wst-tags'>-->
			  	     <!--<span class="w-lfloat"><a onclick='WST.position(15,0)' href='<?php echo Url("home/users/edit"); ?>'>用户资料</a></span>-->
			  	     <!--<span class="w-lfloat" style="margin-left:10px;"><a onclick='WST.position(16,0)' href='<?php echo Url("home/users/security"); ?>'>安全设置</a></span>-->
			  	    <!--</div>-->
				  </div>
			  	  <div class="wst-tagb" style='display:none'>
			  		<a>待处理订单</a>
			  		<a>我的余额</a>
			  		<a>我的消息</a>
			  		<a>我的积分</a>
			  		<a>我的关注</a>
			  		<a>咨询回复</a>
			  	  </div>
			  	<div class="wst-clear"></div>
			  	</div>
			  </div>
			</li>
			<!--<li class="spacer">|</li>-->
			<!--<li class="drop-info">-->
			<!--<a href='<?php echo Url("home/messages/index"); ?>' target='_blank' onclick='WST.position(49,0)'>消息（<span id='wst-user-messages'>0</span>）</a>-->
			<!--</li>-->
			<li class="spacer">|</li>
			<li class="drop-info">
			  <div><a href="javascript:WST.logout();">退出</a></div>
			</li>
			<?php else: ?>
			<li class="drop-info">
			  <div>欢迎来到<?php echo WSTMSubstr(WSTConf('CONF.mallName'),0,13); ?><a href="<?php echo Url('home/users/login'); ?>">&nbsp;&nbsp;请&nbsp;登录</a></div>
			</li>
			<li class="spacer">|</li>
			<li class="drop-info">
			  <div><a href="<?php echo Url('home/users/regist'); ?>">免费注册</a></div>
			</li>
			<?php endif; ?>
		</ul>
		<!--<ul class="headrf" style='float:right;'>-->
		    <!--<li class="j-dorpdown">-->
				<!--<div class="drop-down" style="padding-left:0px;">-->
					<!--<a href="<?php echo Url('home/users/index'); ?>" target="_blank">我的订单<i class="di-right"><s>◇</s></i></a>-->
				<!--</div>-->
				<!--<div class='j-dorpdown-layer order-list'>-->
				   <!--<div><a href='<?php echo Url("home/orders/waitPay"); ?>' onclick='WST.position(3,0)'>待付款订单</a></div>-->
				   <!--<div><a href='<?php echo Url("home/orders/waitReceive"); ?>' onclick='WST.position(5,0)'>待发货订单</a></div>-->
				   <!--<div><a href='<?php echo Url("home/orders/waitAppraise"); ?>' onclick='WST.position(6,0)'>待评价订单</a></div>-->
				<!--</div>-->
			<!--</li>	-->
			<!---->
			<!--<li class="spacer">|</li>-->
			<!--<li class="j-dorpdown">-->
				<!--<div class="drop-down drop-down2 pdr5"><i class="di-left"></i><a href="#" target="_blank">手机商城</a></div>-->
				<!--<div class='j-dorpdown-layer sweep-list'>-->
				   <!--<div class="qrcodea">-->
					   <!--<div id='qrcodea' class="qrcodeal"></div>-->
					   <!--<div class="qrcodear">-->
					   	<!--<p>扫描二维码</p><span>下载手机客户端</span><br/><a style="margin-bottom:-2px;">Android</a><br/><a>iPhone</a>-->
					   <!--</div>-->
				   <!--</div>-->
				<!--</div>-->
			<!--</li>-->
			<!--<li class="spacer">|</li>-->
			<!--<li class="j-dorpdown">-->
				<!--<div class="drop-down" style="padding:0 5px;"><a href="#" target="_blank">关注我们</a></div>-->
				<!--<div class='j-dorpdown-layer des-list' style="width:78px;">-->
					<!--<div style="height:78px;"><img src="__STYLE__/img/wst_qr_code.jpg" style="height:78px;"></div>-->
					<!--<div>关注我们</div>-->
				<!--</div>-->
			<!--</li>-->
			<!--<li class="spacer">|</li>-->
			<!--<li class="j-dorpdown">-->
				<!--<div class="drop-down drop-down4 pdr5"><a href="#" target="_blank">我的收藏</a></div>-->
				<!--<div class='j-dorpdown-layer foucs-list'>-->
				   <!--<div><a href="<?php echo Url('home/favorites/goods'); ?>" onclick='WST.position(41,0)'>商品收藏</a></div>-->
				   <!--<div><a href="<?php echo Url('home/favorites/shops'); ?>" onclick='WST.position(46,0)'>店铺收藏</a></div>-->
				<!--</div>-->
			<!--</li>-->
			<!--<li class="spacer">|</li>-->
			<!--<li class="j-dorpdown">-->
				<!--<div class="drop-down drop-down5 pdr5" ><a href="#" target="_blank">客户服务</a></div>-->
				<!--<div class='j-dorpdown-layer des-list'>-->
				   <!--<div><a href='<?php echo Url("home/helpcenter/view","id=1"); ?>' target='_blank'>帮助中心</a></div>-->
				   <!--<div><a href='<?php echo Url("home/helpcenter/view","id=8"); ?>' target='_blank'>售后服务</a></div>-->
				   <!--<div><a href='<?php echo Url("home/helpcenter/view","id=3"); ?>' target='_blank'>常见问题</a></div>-->
				<!--</div>-->
			<!--</li>-->
			<!--<li class="spacer">|</li>-->
			<!--<?php if(session('WST_USER.userId') > 0): ?>-->
				<!--<?php if(session('WST_USER.userType') == 0): ?>-->
				<!--<li class="j-dorpdown">-->
				<!--<div class="drop-down pdl5" ><a href="#" target="_blank">商家管理<i class="di-right"><s>◇</s></i></a></div>-->
				<!--<div class='j-dorpdown-layer foucs-list'>-->
				   <!--<div><a href="<?php echo url('home/shops/login'); ?>">商家登录</a></div>-->
				   <!--<div><a href="javascript:shopApply();" rel="nofollow">我要开店</a></div>-->
				<!--</div>-->
				<!--</li>-->
				<!--<link href="__STATIC__/plugins/validator/jquery.validator.css?v=<?php echo $v; ?>" rel="stylesheet">
<link href="__STYLE__/css/shopapply.css?v=<?php echo $v; ?>" rel="stylesheet">
<div id="wst-shopapp" class="wst-shopapp" style="display:none;">
	<div class="wst-shopapp-fo">
	<a href="javascript:void(0)" onclick="javascript:getClose()" class="wst-shopapp-close"></a>
	<form id="apply_form"  method="post" autocomplete="off">
	<table class="wst-table" style="margin:15px;margin-left:35px;">
		<tr>
			<td class="wst-shopapp-td">手机号</td>
			<td><input id="userPhone2" name="userPhone" class="wst_ipt2 wst-shopapp-input" tabindex="1" maxlength="30" autocomplete="off" onpaste="return false;" style="ime-mode:disabled;" placeholder="手机号"  type="text"data-rule="手机号 required;mobile;remote(post:<?php echo url('home/users/checkLoginKey'); ?>)" data-msg-mobile="请输入有效的手机号" data-msg-required="请输入手机号" data-tip="请输入手机号" data-target="#userPhone2"/></td>
		</tr>
		<?php if((WSTConf('CONF.smsOpen')==1)): ?>
		<tr>
			<td class="wst-shopapp-td">短信验证码</td>
			<td>
				<input maxlength="6" autocomplete="off" tabindex="6" class="wst_ipt2 wst-shopapp-input2" name="mobileCode" style="ime-mode:disabled" id="mobileCode" type="text" placeholder="短信验证码" />
				<button type="button"  onclick="javascript:getShopCode();" class="wst-shopapp-obtain">获取短信验证码</button>
				<span id="mobileCodeTips"></span>
			</td>
		</tr>
		<?php else: ?>
		<tr>
			<td class="wst-shopapp-td">验证码</td>
			<td>
				<div class="wst-apply-code">
				<input id="verifyCodea" style="ime-mode:disabled" name="verifyCodea"  class="wst_ipt2 wst-apply-codein" tabindex="6" autocomplete="off" maxlength="6" type="text" placeholder="验证码"/>
				<img id='verifyImga' class="wst-apply-codeim" src="" onclick='javascript:WST.getVerify("#verifyImga")' style="width:101px;border-top-right-radius:2px;border-bottom-right-radius:2px;"><span id="verifya"></span>    	
			   	</div>
			</td>
		</tr>
		<?php endif; ?>
		<tr>
			<td class="wst-shopapp-td">备注</td>
			<td>
				<textarea id="remark" name="remark" class="wst_ipt2 wst-remark" id="rejectionRemarks" autocomplete="off" style="width: 350px;height:170px;"></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="padding-left:80px;">
				<label>
					<input id="protocol" name="protocol" type="checkbox" class="wst_ipt2" value="1"/>我已阅读并同意
	           	</label>
	           	<a href="javascript:;" style="color:#69b7b5;" id="protocolInfo" onclick="showProtocol();">《商家注册协议》</a>
			</td>
		</tr>
		<tr style="height:45px;">
			<td colspan="2" style="padding-left:165px;">
				<input id="reg_butt" class="wst-shopapp-but" type="submit" value="注册" style="width: 100px;height:30px;"/>
			</td>
		</tr>
	</table>
	</form>
	</div>
</div>
     <form method="post" id="shopVerifys" autocomplete="off" style="display:none;">
      <input type='hidden' id='VerifyId' value='' autocomplete="off"/>
      <table class='wst-table' style="width:400x;margin:15px;margin-left:35px;">
      	<tr>
			<td class="wst-shopapp-td">验证码</td>
			<td>
				<input id="smsVerfy"  name="smsVerfy"  class="wst_ipt2 wst-shopapp-input2" tabindex="6" autocomplete="off" maxlength="6" type="text" data-target="#smsVerfyTips" placeholder="验证码" data-rule="验证码: required;" data-msg-required="请输入验证码" data-tip="请输入验证码"/>
				<span id="smsVerfyTips" style="float:right;"></span>      	
			   	<label style="float:right;margin-top:5px;"><a href="javascript:WST.getVerify('#verifyImg3')">&nbsp;换一张</a></label>
				<label style="float:right;">
					<img id='verifyImg3' src="" onclick='javascript:WST.getVerify("#verifyImg3")' style="width:100px;"> 
				</label>
			</td>
		</tr>
         <tr>
           <td colspan='2' style='padding-left:170px;height:50px;'>
               <button class="wst-shopapp-but" type="submit" style="width:100px;height: 30px;">确认</button>
           </td>
         </tr>
        </table>
      </form>
<script type="text/javascript" src="__STATIC__/plugins/validator/jquery.validator.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="__STATIC__/plugins/validator/local/zh-CN.js?v=<?php echo $v; ?>"></script>
<script type='text/javascript' src='__STYLE__/js/shop_applys.js?v=<?php echo $v; ?>'></script>-->
				<!--<?php else: ?>-->
				<!--<li class="j-dorpdown">-->
				    <!--<div class="drop-down pdl5" >-->
				       <!--<a href="<?php echo Url('home/shops/index'); ?>" rel="nofollow" target="_blank">卖家中心<i class="di-right"><s>◇</s></i></a>-->
				    <!--</div>-->
				    <!--<div class='j-dorpdown-layer product-list last-menu'>-->
					   <!--<div><a href='<?php echo Url("home/orders/waitdelivery"); ?>' onclick='WST.position(24,1)'>待发货订单</a></div>-->
					   <!--<div><a href='<?php echo Url("home/orders/waitdelivery"); ?>' onclick='WST.position(25,1)'>投诉订单</a></div>-->
					   <!--<div><a href='<?php echo Url("home/home/goods/sale"); ?>' onclick='WST.position(32,1)'>商品管理</a></div>-->
					   <!--<div><a href='<?php echo Url("home/shopcats/index"); ?>' onclick='WST.position(30,1)'>商品分类</a></div>-->
					<!--</div>-->
				<!--</li>-->
				<!--<?php endif; ?>-->
			<!--<?php else: ?>-->
				<!--<li class="j-dorpdown">-->
				<!--<div class="drop-down pdl5" ><a href="#" target="_blank">商家管理<i class="di-right"><s>◇</s></i></a></div>-->
				<!--<div class='j-dorpdown-layer foucs-list'>-->
				   <!--<div><a href="<?php echo url('home/shops/login'); ?>">商家登录</a></div>-->
				   <!--<div><a href="javascript:shopApply();" rel="nofollow">我要开店</a></div>-->
				<!--</div>-->
				<!--</li>-->
				<!--<link href="__STATIC__/plugins/validator/jquery.validator.css?v=<?php echo $v; ?>" rel="stylesheet">
<link href="__STYLE__/css/shopapply.css?v=<?php echo $v; ?>" rel="stylesheet">
<div id="wst-shopapp" class="wst-shopapp" style="display:none;">
	<div class="wst-shopapp-fo">
	<a href="javascript:void(0)" onclick="javascript:getClose()" class="wst-shopapp-close"></a>
	<form id="apply_form"  method="post" autocomplete="off">
	<table class="wst-table" style="margin:15px;margin-left:35px;">
		<tr>
			<td class="wst-shopapp-td">手机号</td>
			<td><input id="userPhone2" name="userPhone" class="wst_ipt2 wst-shopapp-input" tabindex="1" maxlength="30" autocomplete="off" onpaste="return false;" style="ime-mode:disabled;" placeholder="手机号"  type="text"data-rule="手机号 required;mobile;remote(post:<?php echo url('home/users/checkLoginKey'); ?>)" data-msg-mobile="请输入有效的手机号" data-msg-required="请输入手机号" data-tip="请输入手机号" data-target="#userPhone2"/></td>
		</tr>
		<?php if((WSTConf('CONF.smsOpen')==1)): ?>
		<tr>
			<td class="wst-shopapp-td">短信验证码</td>
			<td>
				<input maxlength="6" autocomplete="off" tabindex="6" class="wst_ipt2 wst-shopapp-input2" name="mobileCode" style="ime-mode:disabled" id="mobileCode" type="text" placeholder="短信验证码" />
				<button type="button"  onclick="javascript:getShopCode();" class="wst-shopapp-obtain">获取短信验证码</button>
				<span id="mobileCodeTips"></span>
			</td>
		</tr>
		<?php else: ?>
		<tr>
			<td class="wst-shopapp-td">验证码</td>
			<td>
				<div class="wst-apply-code">
				<input id="verifyCodea" style="ime-mode:disabled" name="verifyCodea"  class="wst_ipt2 wst-apply-codein" tabindex="6" autocomplete="off" maxlength="6" type="text" placeholder="验证码"/>
				<img id='verifyImga' class="wst-apply-codeim" src="" onclick='javascript:WST.getVerify("#verifyImga")' style="width:101px;border-top-right-radius:2px;border-bottom-right-radius:2px;"><span id="verifya"></span>    	
			   	</div>
			</td>
		</tr>
		<?php endif; ?>
		<tr>
			<td class="wst-shopapp-td">备注</td>
			<td>
				<textarea id="remark" name="remark" class="wst_ipt2 wst-remark" id="rejectionRemarks" autocomplete="off" style="width: 350px;height:170px;"></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="padding-left:80px;">
				<label>
					<input id="protocol" name="protocol" type="checkbox" class="wst_ipt2" value="1"/>我已阅读并同意
	           	</label>
	           	<a href="javascript:;" style="color:#69b7b5;" id="protocolInfo" onclick="showProtocol();">《商家注册协议》</a>
			</td>
		</tr>
		<tr style="height:45px;">
			<td colspan="2" style="padding-left:165px;">
				<input id="reg_butt" class="wst-shopapp-but" type="submit" value="注册" style="width: 100px;height:30px;"/>
			</td>
		</tr>
	</table>
	</form>
	</div>
</div>
     <form method="post" id="shopVerifys" autocomplete="off" style="display:none;">
      <input type='hidden' id='VerifyId' value='' autocomplete="off"/>
      <table class='wst-table' style="width:400x;margin:15px;margin-left:35px;">
      	<tr>
			<td class="wst-shopapp-td">验证码</td>
			<td>
				<input id="smsVerfy"  name="smsVerfy"  class="wst_ipt2 wst-shopapp-input2" tabindex="6" autocomplete="off" maxlength="6" type="text" data-target="#smsVerfyTips" placeholder="验证码" data-rule="验证码: required;" data-msg-required="请输入验证码" data-tip="请输入验证码"/>
				<span id="smsVerfyTips" style="float:right;"></span>      	
			   	<label style="float:right;margin-top:5px;"><a href="javascript:WST.getVerify('#verifyImg3')">&nbsp;换一张</a></label>
				<label style="float:right;">
					<img id='verifyImg3' src="" onclick='javascript:WST.getVerify("#verifyImg3")' style="width:100px;"> 
				</label>
			</td>
		</tr>
         <tr>
           <td colspan='2' style='padding-left:170px;height:50px;'>
               <button class="wst-shopapp-but" type="submit" style="width:100px;height: 30px;">确认</button>
           </td>
         </tr>
        </table>
      </form>
<script type="text/javascript" src="__STATIC__/plugins/validator/jquery.validator.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="__STATIC__/plugins/validator/local/zh-CN.js?v=<?php echo $v; ?>"></script>
<script type='text/javascript' src='__STYLE__/js/shop_applys.js?v=<?php echo $v; ?>'></script>-->
			<!--<?php endif; ?>-->
			<!--</li>-->
		<!--</ul>-->
		<div class="wst-clear"></div>
  </div>
</div>
<script>
$(function(){
	//二维码
	//参数1表示图像大小，取值范围1-10；参数2表示质量，取值范围'L','M','Q','H'
	var a = qrcode(8, 'M');
	var url = window.location.host+window.conf.APP;
	a.addData(url);
	a.make();
	$('#qrcodea').html(a.createImgTag());
});
function goShop(id){
  location.href=WST.U('home/shops/home','shopId='+id);
}
</script>
<script type='text/javascript' src='__STYLE__/js/qrcode.js?v=<?php echo $v; ?>'></script>
<div class='wst-lite-container'>
   <div class='wst-logo'>
    <a href='<?php echo \think\Request::instance()->root(true); ?>' title="<?php echo WSTConf('CONF.mallName'); ?>" >
      <img src="__ROOT__/<?php echo WSTConf('CONF.mallLogo'); ?>" height="120" width='240' title="<?php echo WSTConf('CONF.mallName'); ?>" alt="<?php echo WSTConf('CONF.mallName'); ?>">
    </a>
   </div>
   <div class="wst-lite-title"><?php echo $liteTitle; ?></div>
   <div class="wst-lite-index"><a href='<?php echo \think\Request::instance()->root(true); ?>'>返回首页</a></div>
   <div class="wst-clear"></div>
</div>
<div style="border-bottom: 2px solid #df2003;"></div>
<div class="wst-clear"></div>


<div class="wst-regist-b">
	<div class="wst-container">
	<div class="wst-regist">
	<div class="wst-regist-c">
	<input type="hidden" id="nameType" class="wst_ipt nameType" value='2' autocomplete="off"/>
	<form id="reg_form"  method="post" autocomplete="off">
	<div class="wst-regist-head">Hi～欢迎来注册</div>
	<table class="wst-table">
		<tr class="wst-login-tr">
			<td class="wst-regist-td"><font color='red'>*</font>用户名</td>
			<td><input id="loginName" name="loginName" class="wst_ipt wst-regist-input" tabindex="1" maxlength="30" autocomplete="off" onpaste="return false;" style="ime-mode:disabled;" placeholder="邮箱/用户名/手机号"  type="text" onkeyup="javascript:WST.isChinese(this,1)"/></td>
		</tr>
		<tr class="wst-login-tr">
			<td class="wst-regist-td"><font color='red'>*</font>密码</td>
			<td><input id="loginPwd" name="loginPwd" class="wst_ipt wst-regist-input" tabindex="2" style="ime-mode:disabled;" autocomplete="off" type="password" placeholder="6-16位字符"/></td>
		</tr>
		<tr class="wst-login-tr">
			<td class="wst-regist-td"><font color='red'>*</font>确认密码</td>
			<td><input id="reUserPwd" name="reUserPwd" class="wst_ipt wst-regist-input" tabindex="3" autocomplete="off" type="password" placeholder="6-16位字符"/></td>
		</tr>
		<tr id="mobileCodeDiv" class="wst-login-tr hide">
			<td class="wst-regist-td"><font color='red'>*</font>短信验证码</td>
			<td>
				<input maxlength="6" autocomplete="off" tabindex="6" class="wst_ipt wst-regist-codemo" name="mobileCode" style="ime-mode:disabled" id="mobileCode" type="text" data-target="#mobileCodeTips" placeholder="短信验证码"/>
				<button id="timeTips" onclick="getVerifyCode();" class="wst-regist-obtain">获取短信验证码</button>
				<span id="mobileCodeTips"></span>
			</td>
		</tr>
		<tr id="authCodeDiv" class="wst-login-tr">
			<td class="wst-regist-td"><font color='red'>*</font>验证码</td>
			<td>
				<div class="wst-regist-code">
				<input id="verifyCode" style="ime-mode:disabled" name="verifyCode"  class="wst_ipt wst-regist-codein" tabindex="6" autocomplete="off" maxlength="6" type="text"  data-target="#verifyCodeTips" placeholder="验证码"/>
				<img id='verifyImg' class="wst-regist-codeim" src="<?php echo url('home/users/getVerify'); ?>" onclick='javascript:WST.getVerify("#verifyImg")' style="width:116px;border-top-right-radius:6px;border-bottom-right-radius:6px;"><span id="verifyCodeTips"></span>    	
			   	</div>
			</td>
		</tr>
		<tr class="wst-login-tr">
			<td colspan="2" style="padding-left:180px;">
				<label>
					<input id="protocol" name="protocol" type="checkbox" class="wst_ipt" value="1" data-rule="checked"/>我已阅读并同意
	           		<a href="javascript:;" style="color:#69b7b5;" id="protocolInfo" onclick="showProtocol();">《用户注册协议》</a>
	           	</label>                                  
				<label style="float:right;margin-top:3px;">看不清？<a style="color:#69b7b5;" href="javascript:WST.getVerify('#verifyImg')">换一张</a></label>
			</td>
		</tr>
		<tr style="height:80px;">
			<td colspan="2" style="padding-left:260px;">
				<input id="reg_butt" class="wst-regist-but" type="submit" value="注册" style="width: 180px;height:30px;"/>
			</td>
		</tr>
	</table>
	</form>
	</div>
	</div>
	</div>
</div>


	


	<script type="text/javascript" src="__STATIC__/plugins/validator/jquery.validator.js?v=<?php echo $v; ?>"></script>
	<script type="text/javascript" src="__STATIC__/plugins/validator/local/zh-CN.js?v=<?php echo $v; ?>"></script>
	<script type='text/javascript' src='__STYLE__/js/login.js?v=<?php echo $v; ?>'></script>
	<Script>$(function(){initRegist();})</Script>

</body>
</html>