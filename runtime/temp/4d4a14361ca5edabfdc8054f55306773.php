<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:72:"E:\demo\sanzhixiaoxiong/wstmart/home\view\default\shops\orders\view.html";i:1545012032;s:65:"E:\demo\sanzhixiaoxiong/wstmart/home\view\default\shops\base.html";i:1542789671;s:65:"E:\demo\sanzhixiaoxiong/wstmart/home\view\default\header_top.html";i:1540775116;s:65:"E:\demo\sanzhixiaoxiong/wstmart/home\view\default\shop_apply.html";i:1523179176;s:61:"E:\demo\sanzhixiaoxiong/wstmart/home\view\default\footer.html";i:1540022560;}*/ ?>
<!doctype html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>订单详情 - 卖家中心<?php echo WSTConf('CONF.mallTitle'); ?></title>
<link href="__STYLE__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet">
<link href="__STYLE__/css/shop.css?v=<?php echo $v; ?>" rel="stylesheet">


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
            
<div class="wst-shop-head"><span>订单详情</span></div>
<div class='wst-shop-content'>
   <div class='order-box'>
    <div class='box-head'>日志信息</div>
    <?php if(in_array($object['orderStatus'],[-2,0,1,2])): ?>
	<div class='log-box'>
<div class="state">
<?php if($object['payType']==1): ?>
<div class="icon">
	<span class="icons <?php if(($object['orderStatus']==-2)OR($object['orderStatus']==0)OR($object['orderStatus']==1)OR($object['orderStatus']==2)): ?>icon12 <?php else: ?>icon11 <?php endif; if(($object['orderStatus']==-2)): ?>icon13 <?php endif; ?>"></span>
</div>
<div class="arrow <?php if(($object['orderStatus']==0) OR ($object['orderStatus']==1) OR ($object['orderStatus']==2)): ?>arrow2<?php endif; ?>">························></div>
	<div class="icon"><span class="icons <?php if(($object['orderStatus']==0)OR($object['orderStatus']==1)OR($object['orderStatus']==2)): ?>icon22 <?php else: ?>icon21<?php endif; if(($object['orderStatus']==0)): ?>icon23 <?php endif; ?>"></span></div>
	<div class="arrow <?php if(($object['orderStatus']==1) OR ($object['orderStatus']==2)): ?>arrow2<?php endif; ?>">························></div>
<?php else: ?>
<div class="icon">
	<span class="icons <?php if(($object['orderStatus']==-2)OR($object['orderStatus']==0)OR($object['orderStatus']==1)OR($object['orderStatus']==2)): ?>icon12 <?php else: ?>icon11 <?php endif; if(($object['orderStatus']==0)): ?>icon13 <?php endif; ?>"></span>
</div>
<div class="arrow <?php if(($object['orderStatus']==1) OR ($object['orderStatus']==2)): ?>arrow2<?php endif; ?>">························></div>
<?php endif; ?>
<div class="icon">
	<span class="icons <?php if(($object['orderStatus']==1)OR($object['orderStatus']==2)OR($object['orderStatus']==1)): ?>icon32 <?php else: ?>icon31 <?php endif; if(($object['orderStatus']==1)): ?>icon33 <?php endif; ?>"></span>
</div>
<div class="arrow <?php if(($object['orderStatus']==2)): ?>arrow2<?php endif; ?>">························></div>
<div class="icon"><span class="icons  <?php if(($object['orderStatus']==2)AND($object['isAppraise']==1)): ?>icon42 <?php else: ?>icon41 <?php endif; if(($object['orderStatus']==2)AND($object['isAppraise']==0)): ?>icon43 <?php endif; ?>"></span></div>
<div class="arrow <?php if(($object['isAppraise']==1)): ?>arrow2<?php endif; ?>">························></div>
<div class="icon"><span class="icons <?php if(($object['isAppraise']==1)): ?>icon53 <?php else: ?>icon51 <?php endif; ?>"></span></div>
</div>
   <div class="state2">
   <div class="path">
   <?php if(is_array($object['log']) || $object['log'] instanceof \think\Collection): $i = 0; $__LIST__ = $object['log'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$lo): $mod = ($i % 2 );++$i;?>
   	<span><?php echo $lo['logContent']; ?><br/><?php echo $lo['logTime']; ?></span>
   <?php endforeach; endif; else: echo "" ;endif; ?>
   </div>
   <p>下单</p><?php if($object['payType']==1): ?><p>等待支付</p><?php endif; ?><p>商家发货</p><p>确认收货</p><p>订单结束<br/>双方互评</p>
   </div>
   <div class="wst-clear"></div>
   </div>
    <?php else: ?>
        <div>
          <table class='log'>
            <?php if(is_array($object["log"]) || $object["log"] instanceof \think\Collection): $i = 0; $__LIST__ = $object["log"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
             <tr>
               <td><?php echo $vo['logTime']; ?></td>
               <td><?php echo $vo['logContent']; ?></td>
             </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
          </table>
        </div>                 
    <?php endif; ?>
   </div>
   <!-- 订单信息 -->
   <div class='order-box'>
      <div class='box-head'>订单信息</div>
      <table class='wst-form'>
         <tr>
           <th width='100'>订单编号：</th>
           <td><?php echo $object['orderNo']; ?></td>
         </tr>
         <tr>
           <th>支付方式：</th>
           <td><?php echo WSTLangPayType($object['payType']); ?></td>
         </tr>
         <?php if(($object['payType']==1 && $object['tradeNo']!='')): ?>
         <tr>
           <th>交易流水：</th>
           <td>【<?php echo WSTLangPayFrom($object['payFrom']); ?>】<?php echo $object['tradeNo']; ?></td>
         </tr>
         <?php endif; ?>
         <tr>
            <th>配送方式：</th>
            <td><?php echo WSTLangDeliverType($object['deliverType']); ?></td>
         </tr>
         <?php if($object['expressNo']!=''): ?>
         <tr>
            <th>快递公司：</th>
            <td><?php echo $object['expressName']; ?></td>
         </tr>
         <tr>
            <th>快递号：</th>
            <td><?php echo $object['expressNo']; ?></td>
         </tr>
         <?php endif; ?>
         <tr>
            <th>买家留言：</th>
            <td><?php echo $object['orderRemarks']; ?></td>
         </tr>
      </table>
   </div>
    <?php if($object['isRefund']==1): ?>
   <!-- 退款信息 -->
   <div class='order-box'>
      <div class='box-head'>退款信息</div>
      <table class='wst-form'>
         <tr>
            <th width='100'>退款金额：</th>
            <td>¥<?php echo $object['backMoney']; ?></td>
         </tr>
         <tr>
            <th width='100'>退款备注：</th>
            <td><?php echo $object['refundRemark']; ?></td>
         </tr>
         <tr>
            <th>退款时间：</th>
            <td><?php echo $object['refundTime']; ?></td>
         </tr>
      </table>
   </div>
   <?php endif; ?>
   <!-- 发票信息 -->
   <div class='order-box'>
      <div class='box-head'>发票信息</div>
      <table class='wst-form'>
         <tr>
           <th width='100'>是否需要发票：</th>
           <td><?php if($object['isInvoice']==1): ?>需要<?php else: ?>不需要<?php endif; ?></td>
         </tr>
         <tr>
           <th>发票抬头：</th>
           <td><?php echo $object['invoiceClient']; ?></td>
         </tr>
      </table>
   </div>
   <!-- 收货人信息 -->
   <div class='order-box'>
      <div class='box-head'>收货人信息</div>
      <table class='wst-form'>
         <tr>
           <th width='100'>收货人：</th>
           <td><?php echo $object['userName']; ?></td>
         </tr>
         <tr>
           <th>收货地址：</th>
           <td><?php echo $object['userAddress']; ?></td>
         </tr>
         <tr>
            <th>联系方式：</th>
            <td><?php echo $object['userPhone']; ?></td>
         </tr>
      </table>
   </div>
   <!-- 商品信息 -->
   <div class='order-box'>
       <div class='box-head'>商品清单</div>
       <div class='goods-head'>
          <div class='goods'>商品</div>
          <div class='price'>单价</div>
          <div class='num'>数量</div>
          <div class='t-price'>总价</div>
       </div>
       <div class='goods-item'>
          <div class='shop'>
          <?php echo $object['shopName']; if($object['shopQQ'] !=''): ?>
          <a href="tencent://message/?uin=<?php echo $object['shopQQ']; ?>&Site=QQ交谈&Menu=yes">
			  <img border="0" src="http://wpa.qq.com/pa?p=1:<?php echo $object['shopQQ']; ?>:7" alt="QQ交谈" width="71" height="24" />
		  </a>
          <?php endif; if($object['shopWangWang'] !=''): ?>
          <a target="_blank" href="http://www.taobao.com/webww/ww.php?ver=3&touid=<?php echo $object['shopWangWang']; ?>&siteid=cntaobao&status=1&charset=utf-8">
			  <img border="0" src="http://amos.alicdn.com/realonline.aw?v=2&uid=<?php echo $object['shopWangWang']; ?>&site=cntaobao&s=1&charset=utf-8" alt="和我联系" />
		  </a>
          <?php endif; ?>
          </div>
          <div class='goods-list'>
             <?php if(is_array($object["goods"]) || $object["goods"] instanceof \think\Collection): $i = 0; $__LIST__ = $object["goods"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?>
             <div class='item'>
		        <div class='goods'>
		            <div class='img'>
		                <a href='#'>
		                <!--<a href='<?php echo Url("home/goods/detail","id=".$vo2["goodsId"]); ?>' target='_blank'>-->
			            <img src='__ROOT__/<?php echo $vo2["goodsImg"]; ?>' width='80' height='80' title='<?php echo $vo2["goodsName"]; ?>'/>
			            </a>
		            </div>
		            <div class='name'><?php echo $vo2["goodsName"]; ?></div>
		            <div class='spec'><?php echo str_replace('@@_@@','<br/>',$vo2["goodsSpecNames"]); ?></div>
		        </div>
		        <div class='price'>¥<?php echo $vo2['goodsPrice']; ?></div>
		        <div class='num'><?php echo $vo2['goodsNum']; ?></div>
		        <div class='t-price'>¥<?php echo $vo2['goodsPrice']*$vo2['goodsNum']; ?></div>
		        <div class='wst-clear'></div>
             </div>
             <?php endforeach; endif; else: echo "" ;endif; ?>
          </div>
       </div>
       <div class='goods-footer'>
          <div class='goods-summary' style='text-align:right'>
             <div class='summary'>商品总金额：¥<span><?php echo $object['goodsMoney']; ?></span></div>
             <div class='summary'>运费：¥<span><?php echo $object['deliverMoney']; ?></span></div>
             <div class='summary line'>应付总金额：¥<span><?php echo $object['totalMoney']; ?></span></div>
             <div class='summary'>实付总金额：¥<span><?php echo $object['realTotalMoney']; ?></span></div>
             <div>可获得积分：<span class='orderScore'><?php echo $object["orderScore"]; ?></span>个</div>
          </div>
       </div>
   </div>
</div>

            </div>
          </div>
          <div style='clear:both;'></div>
          <br/>
        </div>

	


<script type='text/javascript' src='__STYLE__/shops/orders/orders.js?v=<?php echo $v; ?>'></script>

<script>
function getMenus(menuId,menuUrl){
    $.post(WST.U('home/index/getMenuSession'), {menuId:menuId}, function(data){
    	location.href=WST.U(menuUrl);
    });
}
</script>
</body>
</html>