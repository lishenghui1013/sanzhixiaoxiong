<?php if (!defined('THINK_PATH')) exit(); /*a:8:{s:76:"/www/wwwroot/sanzhixiaoxiong/wstmart/home/view/default/shops/goods/edit.html";i:1523179174;s:70:"/www/wwwroot/sanzhixiaoxiong/wstmart/home/view/default/shops/base.html";i:1542789671;s:70:"/www/wwwroot/sanzhixiaoxiong/wstmart/home/view/default/header_top.html";i:1540775116;s:70:"/www/wwwroot/sanzhixiaoxiong/wstmart/home/view/default/shop_apply.html";i:1523179176;s:77:"/www/wwwroot/sanzhixiaoxiong/wstmart/home/view/default/shops/goods/edit0.html";i:1545814688;s:77:"/www/wwwroot/sanzhixiaoxiong/wstmart/home/view/default/shops/goods/edit1.html";i:1523179174;s:77:"/www/wwwroot/sanzhixiaoxiong/wstmart/home/view/default/shops/goods/edit2.html";i:1534214204;s:66:"/www/wwwroot/sanzhixiaoxiong/wstmart/home/view/default/footer.html";i:1540022560;}*/ ?>
<!doctype html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?=($object['goodsId']>0)?"编辑":"新增";?>商品-卖家中心<?php echo WSTConf('CONF.mallTitle'); ?></title>
<link href="__STYLE__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet">
<link href="__STYLE__/css/shop.css?v=<?php echo $v; ?>" rel="stylesheet">

<link rel="stylesheet" type="text/css" href="__STATIC__/plugins/webuploader/webuploader.css?v=<?php echo $v; ?>" />
<link rel="stylesheet" type="text/css" href="__STATIC__/plugins/webuploader/batchupload.css?v=<?php echo $v; ?>" />
<link href="__STATIC__/plugins/validator/jquery.validator.css?v=<?php echo $v; ?>" rel="stylesheet">

<script type="text/javascript" src="__STATIC__/js/jquery.min.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="__STATIC__/plugins/layer/layer.js?v=<?php echo $v; ?>"></script>
	  
<script type='text/javascript' src='__STATIC__/js/common.js?v=<?php echo $v; ?>'></script>

<script type='text/javascript' src='__STYLE__/js/common.js?v=<?php echo $v; ?>'></script>
<script type='text/javascript' src='__ROOT__/static/plugins/lazyload/jquery.lazyload.min.js?v=<?php echo $v; ?>'></script>
<script>
window.conf = {
		"ROOT"      : "__ROOT__", 
		"APP"       : "__APP__", 
		"STATIC"    : "__STATIC__", 
		"SUFFIX"    : "<?php echo config('url_html_suffix'); ?>", 
		"SMS_VERFY" : "<?php echo WSTConf('CONF.smsVerfy'); ?>",
    	"PHONE_VERFY" : "<?php echo WSTConf('CONF.phoneVerfy'); ?>",
    	"GOODS_LOGO"  : "<?php echo WSTConf('CONF.goodsLogo'); ?>",
    	"SHOP_LOGO"   : "<?php echo WSTConf('CONF.shopLogo'); ?>",
    	"MALL_LOGO"   : "<?php echo WSTConf('CONF.mallLogo'); ?>",
    	"USER_LOGO"   : "<?php echo WSTConf('CONF.userLogo'); ?>",
    	"IS_LOGIN"    : "<?php if((int)session('WST_USER.userId')>0): ?>1<?php else: ?>0<?php endif; ?>",
    	"TIME_TASK"   : "1",
      "MESSAGE_BOX": "<?php echo WSTShopMessageBox(); ?>"
	}
	<?php echo WSTLoginTarget(1); ?>
$(function() {
	WST.initShopCenter();
});
</script>
</head>
<body>

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
			<!--<li class="spacer">|</li>-->
			<!--<li class="drop-info">-->
			  <!--<div><a href="<?php echo Url('home/users/regist'); ?>">免费注册</a></div>-->
			<!--</li>-->
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
<div class='wst-lite-bac'>
<div class='wst-lite-container'>
   <div class='wst-logo'><a href='<?php echo \think\Request::instance()->root(true); ?>'><img src="__ROOT__/<?php echo WSTConf('CONF.mallLogo'); ?>" height="80" width='160'></a></div>
   <div class="wst-lite-tit"><span>卖家中心</span><a class="wst-lite-in" href='<?php echo \think\Request::instance()->root(true); ?>'>返回商城首页</a></div>
   <div class="wst-lite-sea">
      <!--<div class='search'>-->
      	  <!--<input type="hidden" id="search-type" value="<?php echo isset($keytype)?1:0; ?>"/>-->

      	  <!--<ul class="j-search-box">-->
            <!--<li class="j-search-type">-->
              <!--搜<span><?php if(isset($keytype)): ?>店铺<?php else: ?>商品<?php endif; ?></span>&nbsp;<i class="arrow"> </i>-->
            <!--</li>-->
            <!--<li class="j-type-list">-->
              <!--<?php if(isset($keytype)): ?>-->
              <!--<div data="0">商品</div>-->
              <!--<?php else: ?>-->
              <!--<div data="1">店铺</div>-->
              <!--<?php endif; ?>-->
            <!--</li>-->
          <!--</ul>-->
          <!---->
	      <!--<input type="text" id='search-ipt' class='search-ipt' value='<?php echo isset($keyword)?$keyword:""; ?>'/>-->
	      <!--<div id='search-btn' class="search-btn" onclick='javascript:WST.search(this.value)'></div>-->
      <!--</div>-->
   </div>
   <div class="wst-clear"></div>
</div>
<div class="wst-clear"></div>
</div>

<div class="wst-wrap">
          <div class='wst-header'>
			<div class="wst-shop-nav">
				<div class="wst-nav-box">
				    <?php $homeMenus = WSTHomeMenus(1); if(is_array($homeMenus['menus']) || $homeMenus['menus'] instanceof \think\Collection): $i = 0; $__LIST__ = $homeMenus['menus'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
						<a href="__ROOT__/<?php echo $vo['menuUrl']; ?>?id=<?php echo $vo['menuId']; ?>"><li class="liselect wst-lfloat <?php if(($vo['menuId'] == $homeMenus['menuId1'])): ?>wst-nav-boxa<?php endif; ?>"><?php echo $vo['menuName']; ?></li></a><?php echo $vo['menuId']; ?>-><?php echo $homeMenus['menuId1']; endforeach; endif; else: echo "" ;endif; ?>
					<div class="wst-clear"></div>
				</div>
			</div>
			<div class="wst-clear"></div>
		</div>
          <div class='wst-nav'></div>
          <div class='wst-main'>
            <div class='wst-menu'>
              <?php if(isset($homeMenus['menus'][$homeMenus['menuId1']]['list'])): if(is_array($homeMenus['menus'][$homeMenus['menuId1']]['list']) || $homeMenus['menus'][$homeMenus['menuId1']]['list'] instanceof \think\Collection): $i = 0; $__LIST__ = $homeMenus['menus'][$homeMenus['menuId1']]['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menus): $mod = ($i % 2 );++$i;?>
              	<span class='wst-menu-title'><?php echo $menus['menuName']; ?><img src="__STYLE__/img/user_icon_sider_zhankai.png"></span>
              	<ul>
                <?php if(isset($menus['list'])): if(is_array($menus['list']) || $menus['list'] instanceof \think\Collection): $k = 0; $__LIST__ = $menus['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($k % 2 );++$k;?>
                  	<li class="<?php if(($homeMenus['menuId3']==$menu['menuId'])): ?>wst-menua<?php endif; ?> wst-menuas" onclick="getMenus('<?php echo $menu['menuId']; ?>','<?php echo $menu['menuUrl']; ?>')">
                  	<?php echo $menu['menuName']; ?>
                  	<span id="mId_<?php echo $menu['menuId']; ?>"></span>
                  	</li>
                	<?php endforeach; endif; else: echo "" ;endif; endif; ?>
              	</ul>
              	<?php endforeach; endif; else: echo "" ;endif; endif; ?>
            </div>
            <div class='wst-content'>
            
<style>
label{margin-right:10px;}
#specsAttrBox .webuploader-container{width:80px;height:25px;line-height:25px;overflow:hidden;}
</style>
<div id='tab' class="wst-tab-box">
	<ul class="wst-tab-nav">
	   <li>商品信息</li>
	   <li>规格属性</li>
	   <li>商品相册</li>
	</ul>
    <div class="wst-tab-content" style='width:99%;margin-bottom: 10px;border:0px;'>
      <form id='editform' autocomplete='off'>
        <div class="wst-tab-item" style="position: relative;">
        <style>
.webuploader-pick {background: #e45050 none repeat scroll 0 0;}
</style>

<link rel="stylesheet" href="__ROOT__/public/statics/webuploader-0.1.5/xb-webuploader.css">
<!--<script src="__ROOT__/Public/statics/webuploader-0.1.5/jquery.min.js"></script>-->
<style type="text/css">
    .box{
        margin-top: 6px;
        padding: 5px;
        width: 600px;
        border: 1px solid red;
    }
    h4{
        padding: 5px 0;
    }
</style>

<input type='hidden' id='goodsId' class='j-ipt' value='<?php echo $object["goodsId"]; ?>'/>
<table class='wst-form'>
  <tr>
     <th width='150'>商品名称<font color='red'>*</font>：</th>
     <td width='300'>
        <input type='text' class='j-ipt' id='goodsName' value='<?php echo $object["goodsName"]; ?>' maxLength='100' data-rule='商品名称:required;'/>
     </td>
     <td rowspan='6'>
        <div id='goodsImgBox'>
        <img src='__ROOT__/<?php echo $object["goodsImg"]; ?>' id='preview' width='150' height='150'>
        </div>
        <div id='goodsImgPicker'>请上传商品图片</div><span id='uploadMsg'></span>
        <input type='hidden' id='goodsImg' class='j-ipt' data-target='#msg_goodsImg' value='<?php if($object["goodsId"]>0): ?><?php echo $object["goodsImg"]; endif; ?>' data-rule="商品图片: required;"/>
        <span class='msg-box' id='msg_goodsImg'></span>
     </td>
  </tr>
  <tr>
     <th>商品编号<font color='red'>*</font>：</th>
     <td><input type='text' class='j-ipt' id='goodsSn' value='<?php echo $object["goodsSn"]; ?>' maxLength='20' data-rule='商品编号:required;'/></td>
  </tr>
  <tr>
  <th width='150'>商品货号<font color='red'>*</font>：</th>
     <td width='300'>
        <input type='text' class='j-ipt' id='productNo' value='<?php echo $object["goodsSn"]; ?>' maxLength='20' data-rule='商品货号:required;'/>
     </td>
  </tr>
  <tr>
     <th>市场价格<font color='red'>*</font>：</th>
     <td><input type='text' class='j-ipt' id='marketPrice' value='<?php echo $object["marketPrice"]; ?>' maxLength='10' data-rule='商场价格:required;'/></td>
  </tr>
  <tr>
     <th>店铺价格<font color='red'>*</font>：</th>
     <td><input type='text' class='j-ipt' id='shopPrice' value='<?php echo $object["shopPrice"]; ?>' maxLength='10' data-rule='店铺价格:required;'/></td>
  </tr>
  <tr>
     <th>商品库存<font color='red'>*</font>：</th>
     <td><input type='text' class='j-ipt' id='goodsStock' value='<?php echo $object["goodsStock"]; ?>' maxLength='10' data-rule='商品库存:required;integer[+]'/></td>
  </tr>
  <tr>
     <th>预警库存<font color='red'>*</font>：</th>
     <td><input type='text' class='j-ipt' id='warnStock' value='<?php echo $object["warnStock"]; ?>' maxLength='10' data-rule='预警库存:required;integer[+]'/></td>
  </tr>
  <tr>
     <th>商品单位<font color='red'>*</font>：</th>
     <td colspan='2'><input type='text' class='j-ipt' id='goodsUnit' value='<?php echo $object["goodsUnit"]; ?>' maxLength='10' data-rule='商品单位:required;'/></td>
  </tr>
    <tr>
        <th>积分抵现比例<font color='red'>*</font>：</th>
        <td colspan='2'><input type='text' class='j-ipt' id='scoresScale' value='<?php echo $object["scoresScale"]; ?>' maxLength='10' data-rule='积分抵现比例:required;range[0.00~1]'/></td>
    </tr>
    <tr>
        <th>消费金抵现比例<font color='red'>*</font>：</th>
        <td colspan='2'><input type='text' class='j-ipt' id='xiaofeiScale' value='<?php echo $object["xiaofeiScale"]; ?>' maxLength='10' data-rule='消费金抵现比例:required;range[0.00~1]'/></td>
    </tr>
  <tr>
     <th>SEO关键字：</th>
     <td ><input type='text' class='j-ipt' id='goodsSeoKeywords' maxLength='100' value='<?php echo $object["goodsSeoKeywords"]; ?>'/></td>
  </tr>
  <tr>
     <th>商品促销信息：</th>
     <td colspan='2'><textarea class='j-ipt' id='goodsTips' maxLength='100' style='width:500px;height:50px'><?php echo $object["goodsTips"]; ?></textarea></td>
  </tr>
  <tr>
     <th>商品状态<font color='red'>*</font>：</th>
     <td colspan='2'>
      <div class="radio-box">
        <label><input type='radio' name='isSale' id="isSale-1" class='j-ipt wst-radio' value='1' <?php if($object['isSale']==1): ?>checked<?php endif; ?>/><label for="isSale-1" class="mt-1"></label>上架</label>
        <label><input type='radio' name='isSale' id="isSale-0" class='j-ipt wst-radio' value='0' <?php if($object['isSale']==0): ?>checked<?php endif; ?>/><label for="isSale-0" class="mt-1"></label>下架</label>
      </div>
     </td>
  </tr>
  <tr>
     <th>商品属性：</th>
     <td colspan='2'>
      <div class="checkbox-box">
        <label>
	        <input id="isRecom" name='isRecom' class="j-ipt wst-checkbox" <?php if($object['isRecom']==1): ?>checked<?php endif; ?> value="1" type="checkbox"/><label class="mt-1" for="isRecom"></label>推荐
	    </label>
	    <label>
	        <input id="isBest" name="isBest" class="j-ipt wst-checkbox" <?php if($object['isBest']==1): ?>checked<?php endif; ?> value="1" type="checkbox"/><label class="mt-1" for="isBest"></label>精品
	    </label>
	    <label>
	        <input id="isNew" name="isNew" class="j-ipt wst-checkbox" <?php if($object['isNew']==1): ?>checked<?php endif; ?> value="1" type="checkbox"/><label class="mt-1" for="isNew"></label>新品
	    </label>
	    <label>
	        <input id="isHot" name="isHot" class="j-ipt wst-checkbox" <?php if($object['isHot']==1): ?>checked<?php endif; ?> value="1" type="checkbox"/><label class="mt-1" for="isHot"></label>热销
	    </label>       
     </td>
   </div>
  </tr>
  <tr>
     <th>商城分类<font color='red'>*</font>：</th>
     <td colspan='2'>
         <select id="cat_0" class='ipt j-goodsCats' level="0" onchange="WST.ITGoodsCats({id:'cat_0',val:this.value,isRequire:true,className:'j-goodsCats',afterFunc:'lastGoodsCatCallback'});getBrands('brandId',this.value)">
	      	<option value="">-请选择-</option>
	      	<?php $_result=WSTGoodsCats(0);if(is_array($_result) || $_result instanceof \think\Collection): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	        <option value="<?php echo $vo['catId']; ?>"><?php echo $vo['catName']; ?></option>
	        <?php endforeach; endif; else: echo "" ;endif; ?>
	     </select>
     </td>
  </tr>
  <tr>
     <th>本店分类：</th>
     <td colspan='2'>
         <select id="shopCatId1" class='j-ipt' onchange="getShopsCats('shopCatId2',this.value,'');">
            <option value="">-请选择-</option>
            <?php $_result=WSTShopCats(0);if(is_array($_result) || $_result instanceof \think\Collection): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <option value="<?php echo $vo['catId']; ?>" <?php if($object['shopCatId1']==$vo['catId']): ?>selected<?php endif; ?>><?php echo $vo['catName']; ?></option>
            <?php endforeach; endif; else: echo "" ;endif; ?>
         </select>
         <select id='shopCatId2' class='j-ipt'>
             <option value=''>请选择</option>
         </select>
     </td>
  </tr>
  <tr>
     <th>品牌：</th>
     <td colspan='2'>
         <select id="brandId" class='j-ipt'>
            <option value="0">-请选择-</option>
         </select>
     </td>
  </tr>

    <tr>
        <th>商品描述<font color='red'>*</font>：</th>
        <td colspan='2'>
            <div class="box">
                <div id="upload-58e7a59ad3bce" class="xb-uploader">
                    <div class="queueList">
                        <div class="placeholder <?php if(!empty($object['goodsDesc'])): ?>element-invisible<?php endif; ?>">
                            <div class="filePicker"></div>
                            <p>或将照片拖到这里，单次最多可选50张,每张最大不超过5M</p>
                        </div>
                        <ul class="filelist" >
                            <?php if(is_array($object['goodsDesc']) || $object['goodsDesc'] instanceof \think\Collection): $i = 0; $__LIST__ = $object['goodsDesc'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                            <li  class="state-complete" style="border: 1px solid rgb(59, 114, 165);">
                                <p class="title"></p>
                                <p class="imgWrap">
                                    <img src="__ROOT__/<?php echo $vo; ?>">
                                </p>
                                <input class="bjy-filename" type="hidden" name="image[]" value="<?php echo $vo; ?>">
                                <span class="btn-del">删除</span>
                            </li>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                    </div>
                    <div class="statusBar" <?php if(empty($object['goodsDesc'])): ?>style="display: none;"<?php endif; ?>">
                        <div class="progress" style="display: none;">
                            <span class="text">0%</span>
                            <span class="percentage"></span>
                        </div>
                        <div class="info"></div>
                        <div class="btns">
                            <div class="webuploader-container filePicker2">
                                <div class="webuploader-pick">继续添加</div>
                                <div style="position: absolute; top: 0px; left: 0px; width: 1px; height: 1px; overflow: hidden;" id="rt_rt_1armv2159g1o1i9c2a313hadij6">
                                </div>
                            </div>
                            <div class="uploadBtn">开始上传</div>
                        </div>
                    </div>
                </div>
            </div>
        </td>
    </tr>

  <tr>
     <td colspan='3' align='center' style='text-align:center'>
        <button class="s-btn" onclick='javascript:save()' type='button'>保存</button>
        <button type="reset" class="s-btn">重置</button>
     </td>
  </tr>
</table>
<script type="text/javascript" src="__STATIC__/plugins/layer/laydate.js?v=<?php echo $v; ?>"></script>


<script>
    jQuery(function() {
        var $ = jQuery,    // just in case. Make sure it's not an other libaray.

            $wrap = $("#upload-58e7a59ad3bce"),

            // 图片容器
            $queue = $('<ul class="filelist"></ul>')
                .appendTo( $wrap.find('.queueList') ),

            // 状态栏，包括进度和控制按钮
            $statusBar = $wrap.find('.statusBar'),

            // 文件总体选择信息。
            $info = $statusBar.find('.info'),

            // 上传按钮
            $upload = $wrap.find('.uploadBtn'),

            // 没选择文件之前的内容。
            $placeHolder = $wrap.find('.placeholder'),

            // 总体进度条
            $progress = $statusBar.find('.progress').hide(),

            // 添加的文件数量
            fileCount = 0,

            // 添加的文件总大小
            fileSize = 0,

            // 优化retina, 在retina下这个值是2
            ratio = window.devicePixelRatio || 1,

            // 缩略图大小
            thumbnailWidth = 110 * ratio,
            thumbnailHeight = 110 * ratio,

            // 可能有pedding, ready, uploading, confirm, done.
            state = 'pedding',

            // 所有文件的进度信息，key为file id
            percentages = {},

            supportTransition = (function(){
                var s = document.createElement('p').style,
                    r = 'transition' in s ||
                        'WebkitTransition' in s ||
                        'MozTransition' in s ||
                        'msTransition' in s ||
                        'OTransition' in s;
                s = null;
                return r;
            })(),
            thisSuccess,
            // WebUploader实例
            uploader;

        if ( !WebUploader.Uploader.support() ) {
            alert( 'Web Uploader 不支持您的浏览器！如果你使用的是IE浏览器，请尝试升级 flash 播放器');
            throw new Error( 'WebUploader does not support the browser you are using.' );
        }

        // 实例化
        uploader = WebUploader.create({
            pick: {
                id: "#upload-58e7a59ad3bce .filePicker",
                label: '点击选择文件',
                multiple : true
            },
            dnd: "#upload-58e7a59ad3bce .queueList",
            paste: document.body,
            // accept: {
            //     title: 'Images',
            //     extensions: 'gif,jpg,jpeg,bmp,png',
            //     mimeTypes: 'image/*'
            // },

            // swf文件路径
            swf: BASE_URL + '/Uploader.swf',

            disableGlobalDnd: true,

            chunked: true,
            server: "<?php echo url('home/Goods/ajax_upload'); ?>",
            fileNumLimit: 300,
            fileSizeLimit: 200 * 1024 * 1024,    // 200 M
            fileSingleSizeLimit: 50 * 1024 * 1024    // 50 M
        });

        // 添加“添加文件”的按钮，
        uploader.addButton({
            id: "#upload-58e7a59ad3bce .filePicker2",
            label: '继续添加'
        });

        // 当有文件添加进来时执行，负责view的创建
        function addFile( file ) {
            var $li = $( '<li id="' + file.id + '">' +
                    '<p class="title">' + file.name + '</p>' +
                    '<p class="imgWrap"></p>'+
                    '<p class="progress"><span></span></p>' +
                    '<input class="bjy-filename" type="hidden" name="image[]">'+
                    '</li>' ),

                $btns = $('<div class="file-panel">' +
                    '<span class="cancel">删除</span>' +
                    '<span class="rotateRight">向右旋转</span>' +
                    '<span class="rotateLeft">向左旋转</span></div>').appendTo( $li ),
                $prgress = $li.find('p.progress span'),
                $wrap = $li.find( 'p.imgWrap' ),
                $info = $('<p class="error"></p>'),

                showError = function( code ) {
                    switch( code ) {
                        case 'exceed_size':
                            text = '文件大小超出';
                            break;

                        case 'interrupt':
                            text = '上传暂停';
                            break;

                        default:
                            text = '上传失败，请重试';
                            break;
                    }

                    $info.text( text ).appendTo( $li );
                };

            if ( file.getStatus() === 'invalid' ) {
                showError( file.statusText );
            } else {
                // @todo lazyload
                $wrap.text( '预览中' );
                uploader.makeThumb( file, function( error, src ) {
                    if ( error ) {
                        $wrap.text( '不能预览' );
                        return;
                    }

                    var img = $('<img src="'+src+'">');
                    $wrap.empty().append( img );
                }, thumbnailWidth, thumbnailHeight );

                percentages[ file.id ] = [ file.size, 0 ];
                file.rotation = 0;
            }

            file.on('statuschange', function( cur, prev ) {
                if ( prev === 'progress' ) {
                    $prgress.hide().width(0);
                } else if ( prev === 'queued' ) {
                    $li.off( 'mouseenter mouseleave' );
                    $btns.remove();
                }

                // 成功
                if ( cur === 'error' || cur === 'invalid' ) {
                    showError( file.statusText );
                    percentages[ file.id ][ 1 ] = 1;
                } else if ( cur === 'interrupt' ) {
                    showError( 'interrupt' );
                } else if ( cur === 'queued' ) {
                    percentages[ file.id ][ 1 ] = 0;
                } else if ( cur === 'progress' ) {
                    $info.remove();
                    $prgress.css('display', 'block');
                } else if ( cur === 'complete' ) {
                    $li.append( '<span class="success"></span>' );
                }

                $li.removeClass( 'state-' + prev ).addClass( 'state-' + cur );
            });

            $li.on( 'mouseenter', function() {
                $btns.stop().animate({height: 30});
            });

            $li.on( 'mouseleave', function() {
                $btns.stop().animate({height: 0});
            });

            $btns.on( 'click', 'span', function() {
                var index = $(this).index(),
                    deg;

                switch ( index ) {
                    case 0:
                        uploader.removeFile( file );
                        return;

                    case 1:
                        file.rotation += 90;
                        break;

                    case 2:
                        file.rotation -= 90;
                        break;
                }

                if ( supportTransition ) {
                    deg = 'rotate(' + file.rotation + 'deg)';
                    $wrap.css({
                        '-webkit-transform': deg,
                        '-mos-transform': deg,
                        '-o-transform': deg,
                        'transform': deg
                    });
                } else {
                    $wrap.css( 'filter', 'progid:DXImageTransform.Microsoft.BasicImage(rotation='+ (~~((file.rotation/90)%4 + 4)%4) +')');
                    // use jquery animate to rotation
                    // $({
                    //     rotation: rotation
                    // }).animate({
                    //     rotation: file.rotation
                    // }, {
                    //     easing: 'linear',
                    //     step: function( now ) {
                    //         now = now * Math.PI / 180;

                    //         var cos = Math.cos( now ),
                    //             sin = Math.sin( now );

                    //         $wrap.css( 'filter', "progid:DXImageTransform.Microsoft.Matrix(M11=" + cos + ",M12=" + (-sin) + ",M21=" + sin + ",M22=" + cos + ",SizingMethod='auto expand')");
                    //     }
                    // });
                }


            });

            $li.appendTo( $queue );
        }

        // 负责view的销毁
        function removeFile( file ) {
            var $li = $('#'+file.id);

            delete percentages[ file.id ];
            updateTotalProgress();
            $li.off().find('.file-panel').off().end().remove();
        }

        function updateTotalProgress() {
            var loaded = 0,
                total = 0,
                spans = $progress.children(),
                percent;

            $.each( percentages, function( k, v ) {
                total += v[ 0 ];
                loaded += v[ 0 ] * v[ 1 ];
            } );

            percent = total ? loaded / total : 0;

            spans.eq( 0 ).text( Math.round( percent * 100 ) + '%' );
            spans.eq( 1 ).css( 'width', Math.round( percent * 100 ) + '%' );
            updateStatus();
        }

        function updateStatus() {
            var text = '', stats;

            if ( state === 'ready' ) {
                text = '选中' + fileCount + '个文件，共' +
                    WebUploader.formatSize( fileSize ) + '。';
            } else if ( state === 'confirm' ) {
                stats = uploader.getStats();
                if ( stats.uploadFailNum ) {
                    text = '已成功上传' + stats.successNum+ '个文件，'+
                        stats.uploadFailNum + '个上传失败，<a class="retry" href="#">重新上传</a>失败文件或<a class="ignore" href="#">忽略</a>'
                }

            } else {
                stats = uploader.getStats();
                text = '共' + fileCount + '个（' +
                    WebUploader.formatSize( fileSize )  +
                    '），已上传' + stats.successNum + '个';

                if ( stats.uploadFailNum ) {
                    text += '，失败' + stats.uploadFailNum + '个';
                }
                if (fileCount==stats.successNum && stats.successNum!=0) {
                    $('#upload-58e7a59ad3bce .webuploader-element-invisible').remove();
                }
            }

            $info.html( text );
        }

        uploader.onUploadAccept=function(object ,ret){
            if(ret.error_info){
                fileError=ret.error_info;
                return false;
            }
        }

        uploader.onUploadSuccess=function(file ,response){
            $('#'+file.id +' .bjy-filename').val(response.name)
        }
        uploader.onUploadError=function(file){
            alert(fileError);
        }

        function setState( val ) {
            var file, stats;
            if ( val === state ) {
                return;
            }

            $upload.removeClass( 'state-' + state );
            $upload.addClass( 'state-' + val );
            state = val;

            switch ( state ) {
                case 'pedding':
                    $placeHolder.removeClass( 'element-invisible' );
                    $queue.parent().removeClass('filled');
                    $queue.hide();
                    $statusBar.addClass( 'element-invisible' );
                    uploader.refresh();
                    break;

                case 'ready':
                    $placeHolder.addClass( 'element-invisible' );
                    $( "#upload-58e7a59ad3bce .filePicker2" ).removeClass( 'element-invisible');
                    $queue.parent().addClass('filled');
                    $queue.show();
                    $statusBar.removeClass('element-invisible');
                    uploader.refresh();
                    break;

                case 'uploading':
                    $( "#upload-58e7a59ad3bce .filePicker2" ).addClass( 'element-invisible' );
                    $progress.show();
                    $upload.text( '暂停上传' );
                    break;

                case 'paused':
                    $progress.show();
                    $upload.text( '继续上传' );
                    break;

                case 'confirm':
                    $progress.hide();
                    $upload.text( '开始上传' ).addClass( 'disabled' );

                    stats = uploader.getStats();
                    if ( stats.successNum && !stats.uploadFailNum ) {
                        setState( 'finish' );
                        return;
                    }
                    break;
                case 'finish':
                    stats = uploader.getStats();
                    if ( stats.successNum ) {

                    } else {
                        // 没有成功的图片，重设
                        state = 'done';
                        location.reload();
                    }
                    break;
            }
            updateStatus();
        }

        uploader.onUploadProgress = function( file, percentage ) {
            var $li = $('#'+file.id),
                $percent = $li.find('.progress span');

            $percent.css( 'width', percentage * 100 + '%' );
            percentages[ file.id ][ 1 ] = percentage;
            updateTotalProgress();
        };

        uploader.onFileQueued = function( file ) {
            fileCount++;
            fileSize += file.size;

            if ( fileCount === 1 ) {
                $placeHolder.addClass( 'element-invisible' );
                $statusBar.show();
            }

            addFile( file );
            setState( 'ready' );
            updateTotalProgress();
        };

        uploader.onFileDequeued = function( file ) {
            fileCount--;
            fileSize -= file.size;

            if ( !fileCount ) {
                setState( 'pedding' );
            }

            removeFile( file );
            updateTotalProgress();

        };

        uploader.on( 'all', function( type ) {
            var stats;
            switch( type ) {
                case 'uploadFinished':
                    setState( 'confirm' );
                    break;

                case 'startUpload':
                    setState( 'uploading' );
                    break;

                case 'stopUpload':
                    setState( 'paused' );
                    break;

            }
        });

        uploader.onError = function( code ) {
            alert( 'Eroor: ' + code );
        };

        $upload.on('click', function() {
            if ( $(this).hasClass( 'disabled' ) ) {
                return false;
            }

            if ( state === 'ready' ) {
                uploader.upload();
            } else if ( state === 'paused' ) {
                uploader.upload();
            } else if ( state === 'uploading' ) {
                uploader.stop();
            }
        });

        $info.on( 'click', '.retry', function() {
            uploader.retry();
        } );

        $info.on( 'click', '.ignore', function() {
            alert( 'todo' );
        } );

        $upload.addClass( 'state-' + state );
        updateTotalProgress();
    });
</script>
<script>
    var BASE_URL = '/Public/statics/webuploader-0.1.5';
</script>
<script src="//cdn.staticfile.org/webuploader/0.1.5/webuploader.min.js"></script>
        </div>
        <div class="wst-tab-item" style="position: relative;">
        <div id='specsAttrBox'></div>
<div style='margin:0px auto;text-align:center;'>
<button class="s-btn" onclick='javascript:save()' type='button'>保存</button>
<button type="reset" class="s-btn">重置</button>
</div>
        </div>
        <div class="wst-tab-item" style="position: relative;">
        <style>
.wst-batchupload .placeholder .webuploader-pick {
    background: #e45050 none repeat scroll 0 0;
}
.wst-batchupload .statusBar .btns .uploadBtn {
    background: #e45050 none repeat scroll 0 0;
}
.wst-batchupload .statusBar .btns .uploadBtn:hover{
    background: #e42525;
}
</style>
<div id="batchUpload" class="wst-batchupload">
    <div class="queueList filled">
        <div id="dndArea" class="placeholder <?php if(!empty($object['gallery'])): ?>element-invisible<?php endif; ?>">
            <div id="filePicker"></div>
            <p>或将照片拖到这里，单次最多可选50张，每张最大不超过5M</p>
        </div>
        <ul class="filelist" >
            <?php if(is_array($object['gallery']) || $object['gallery'] instanceof \think\Collection): $i = 0; $__LIST__ = $object['gallery'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
		    <li  class="state-complete" style="border: 1px solid rgb(59, 114, 165);">
		       <p class="title"></p>
		       <p class="imgWrap">
		          <img src="__ROOT__/<?php echo $vo; ?>">
		       </p>
		       <input type="hidden" v="<?php echo $vo; ?>" iv="<?php echo $vo; ?>" class="j-gallery-img">
		       <span class="btn-del">删除</span>
		    </li>
		    <?php endforeach; endif; else: echo "" ;endif; ?>
	    </ul>
    </div>
    <div class="statusBar" <?php if(empty($object['gallery'])): ?>style="display: none;"<?php endif; ?>>
        <div class="progress" style="display: none;">
            <span class="text">0%</span>
            <span class="percentage" style="width: 0%;"></span>
        </div>
        <div class="info"></div>
        <div class="btns">
            <div id="filePicker2"></div><div class="uploadBtn">开始上传</div>
        </div>
    </div>
</div>
<div style='margin:0px auto;text-align:center;border-top:1px solid #cccccc;'>
<button class="s-btn" onclick='javascript:save()' type='button'>保存</button>
<button type="reset" class="s-btn">重置</button>
</div>

        </div>
     </form>
    </div>
</div>

            </div>
          </div>
          <div style='clear:both;'></div>
          <br/>
        </div>

	


<script type='text/javascript' src='__STATIC__/plugins/webuploader/webuploader.js?v=<?php echo $v; ?>'></script>
<script src="__STATIC__/plugins/kindeditor/kindeditor.js?v=<?php echo $v; ?>" type="text/javascript" ></script>
<script type="text/javascript" src="__STATIC__/plugins/validator/jquery.validator.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="__STATIC__/plugins/validator/local/zh-CN.js?v=<?php echo $v; ?>"></script>
<script type='text/javascript' src='__STATIC__/plugins/webuploader/batchupload.js?v=<?php echo $v; ?>'></script>
<script type='text/javascript' src='__STYLE__/shops/goods/goods.js?v=<?php echo $v; ?>'></script>
<script>
var initBatchUpload = false,editor1 = null,specNum = 0,src='<?php echo $src; ?>';
<?php unset($object['goodsDesc']); ?>
var OBJ = <?=json_encode($object)?>;
$(function(){initEdit()});
</script>

<script>
function getMenus(menuId,menuUrl){
    $.post(WST.U('home/index/getMenuSession'), {menuId:menuId}, function(data){
    	location.href=WST.U(menuUrl);
    });
}
</script>
</body>
</html>