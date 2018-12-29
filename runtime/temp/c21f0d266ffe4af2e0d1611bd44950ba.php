<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:59:"E:\demo\sanzhixiaoxiong/wstmart/admin\view\orders\view.html";i:1545008377;s:52:"E:\demo\sanzhixiaoxiong/wstmart/admin\view\base.html";i:1523179156;}*/ ?>
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

<div style='margin:10px;'>
  <div class='order-box'>
    <div class='box-head'>日志信息</div>
    <?php if(in_array($object['orderStatus'],[-2,0,1,2])): ?>
	<div class='log-box'>
<div class="state">
<?php if($object['payType']==1): ?>
<div class="icon">
	<span class="icons <?php if(($object['orderStatus']==-2)OR($object['orderStatus']==0)OR($object['orderStatus']==1)OR($object['orderStatus']==2)): ?>icon12 <?php else: ?>icon11 <?php endif; if(($object['orderStatus']==-2)): ?>icon13 <?php endif; ?>"></span>
</div>
<div class="arrow <?php if(($object['orderStatus']==0) OR ($object['orderStatus']==1) OR ($object['orderStatus']==2)): ?>arrow2<?php endif; ?>">··················></div>
	<div class="icon"><span class="icons <?php if(($object['orderStatus']==0)OR($object['orderStatus']==1)OR($object['orderStatus']==2)): ?>icon22 <?php else: ?>icon21<?php endif; if(($object['orderStatus']==0)): ?>icon23 <?php endif; ?>"></span></div>
	<div class="arrow <?php if(($object['orderStatus']==1) OR ($object['orderStatus']==2)): ?>arrow2<?php endif; ?>">··················></div>
<?php else: ?>
<div class="icon">
	<span class="icons <?php if(($object['orderStatus']==-2)OR($object['orderStatus']==0)OR($object['orderStatus']==1)OR($object['orderStatus']==2)): ?>icon12 <?php else: ?>icon11 <?php endif; if(($object['orderStatus']==0)): ?>icon13 <?php endif; ?>"></span>
</div>
<div class="arrow <?php if(($object['orderStatus']==1) OR ($object['orderStatus']==2)): ?>arrow2<?php endif; ?>">··················></div>
<?php endif; ?>
<div class="icon">
	<span class="icons <?php if(($object['orderStatus']==1)OR($object['orderStatus']==2)OR($object['orderStatus']==1)): ?>icon32 <?php else: ?>icon31 <?php endif; if(($object['orderStatus']==1)): ?>icon33 <?php endif; ?>"></span>
</div>
<div class="arrow <?php if(($object['orderStatus']==2)): ?>arrow2<?php endif; ?>">··················></div>
<div class="icon"><span class="icons  <?php if(($object['orderStatus']==2)AND($object['isAppraise']==1)): ?>icon42 <?php else: ?>icon41 <?php endif; if(($object['orderStatus']==2)AND($object['isAppraise']==0)): ?>icon43 <?php endif; ?>"></span></div>
<div class="arrow <?php if(($object['isAppraise']==1)): ?>arrow2<?php endif; ?>">··················></div>
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
         <?php if(($object['payType']==1 && $object['tradeNo'] !='')): ?>
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
		        <div class='f-clear'></div>
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
   <div class='wst-footer'><input type='button' value='返回' class='btn' onclick='javascript:history.go(-1)'></div>
<div>


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