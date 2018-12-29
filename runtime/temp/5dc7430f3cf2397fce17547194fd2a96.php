<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:73:"E:\demo\sanzhixiaoxiong/wstmart/home\view\default\shops\orders\print.html";i:1523179176;}*/ ?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>订单打印 - <?php echo WSTConf('CONF.mallName'); ?></title>
</head>
<style>
body{font-size:13px;}
td,th{padding:2px;}
</style>
<body>
<table width='100%' border='0'>
  <tr>
    <td colspan='8' style='text-align:center;font-weight:bold;font-size:26px'>订单信息</td>
  </tr>
  <tr>
    <td width='100' align="right">下单时间：</td>
    <td width='250'><?php echo $object['createTime']; ?></td>
    <td width='100' align="right">支付方式：</td>
    <td width='250'><?php echo WSTLangPayType($object['payType']); ?></td>
    <td width='100' align="right">订单编号：</td>
    <td width='250'><?php echo $object['orderNo']; ?></td>
  </tr>
  <tr>
    <td width='100' align="right">发货时间：</td>
    <td><?php echo $object['deliveryTime']; ?></td>
    <td width='100' align="right">配送方式：</td>
    <td><?php echo WSTLangDeliverType($object['deliverType']); ?></td>
    <td width='100' align="right">快递单号：</td>
    <td><?php echo $object['expressNo']; ?></td>
  </tr>
  <?php if($object['invoiceClient'] !=''): ?>
  <tr>
    <td width='100' align="right">发票抬头：</td>
    <td colspan="6"><?php echo $object['invoiceClient']; ?></td>
  </tr>
  <?php endif; ?>
  <tr>
    <td width='100' align="right">收货地址：</td>
    <td colspan="6"><?php echo $object['userName']; ?>&nbsp;|&nbsp;<?php echo $object['userPhone']; ?>&nbsp;|&nbsp;<?php echo $object['userAddress']; ?></td>
  </tr>
  <?php if($object['orderRemarks']!=''): ?>
  <tr>
    <td width='100' align="right">订单备注：</td>
    <td colspan="6"><?php echo $object['orderRemarks']; ?></td>
  </tr>
  <?php endif; ?>
</table>
<table width='100%' border='1' style='border-collapse:collapse;border-color:#000;'>
  <tr style='background:#cccccc;'>
    <th align="left">商品名称</th>
    <th align="left">商品规格</th>
    <th align="left" align="left">商品价格</th>
    <th align="left">商品数量</th>
    <th align="left">小计</th>
  </tr>
  <?php if(is_array($object["goods"]) || $object["goods"] instanceof \think\Collection): $i = 0; $__LIST__ = $object["goods"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?>
  <tr>
    <td><?php echo $vo2["goodsName"]; ?></td>
    <td><?php echo str_replace('@@_@@',';',$vo2["goodsSpecNames"]); ?>&nbsp;</td>
    <td>¥<?php echo $vo2['goodsPrice']; ?></td>
    <td><?php echo $vo2['goodsNum']; ?></td>
    <td>¥<?php echo $vo2['goodsPrice']*$vo2['goodsNum']; ?></td>
  </tr>
  <?php endforeach; endif; else: echo "" ;endif; ?>
  </table>
  <table width='100%' border='0'>
  <tr>
    <td colspan='6' align="right">商品总金额：¥<?php echo $object['goodsMoney']; ?></td>
  </tr>
  <tr>
    <td colspan='6' align="right">运费：¥<?php echo $object['deliverMoney']; ?></td>
  </tr>
  <tr>
    <td colspan='6' align="right">应付金额：¥<?php echo $object['totalMoney']; ?></td>
  </tr>
  <tr>
    <td colspan='6' align="right">实付金额：¥<?php echo $object['realTotalMoney']; ?></td>
  </tr>
</table>
<br/>
<table width='100%'>
   <tr>
     <td>商家：<?php echo $object['shopName']; ?>&nbsp;&nbsp;&nbsp;电话：<?php echo $object['shopTel']; ?></td>
     <td align="right">打印时间：<?php echo date('Y-m-d H:i:s'); ?></td>
   </tr>
</table>
</body>
<script>
window.print();
</script>
</html>
