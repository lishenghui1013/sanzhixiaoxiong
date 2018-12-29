<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:56:"E:\demo\sanzhixiaoxiong/wstmart/admin\view\ads\edit.html";i:1532054485;s:52:"E:\demo\sanzhixiaoxiong/wstmart/admin\view\base.html";i:1523179156;}*/ ?>
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
<form id="adsForm">
<table class='wst-form wst-box-top'>
  <tr>
      <th width='150'>位置类型<font color='red'>*</font>：</th>
          <td>
            <select id="positionType" name="positionType" class='ipt' maxLength='20' onchange="addPosition(this.value)">
              <option value=''>请选择</option>
              <?php $_result=WSTDatas(5);if(is_array($_result) || $_result instanceof \think\Collection): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;if($vo['dataVal'] == 1): ?>
              <option <?php if($data['positionType'] == $vo['dataVal']): ?>selected<?php endif; ?> value="<?php echo $vo['dataVal']; ?>"><?php echo $vo['dataName']; ?></option>
                <?php endif; endforeach; endif; else: echo "" ;endif; ?>
            </select>
          </td>
       </tr>
       <tr>
          <th>广告位置<font color='red'>*</font>：</th>
          <td>
            <select id="adPositionId" name="adPositionId" class='ipt' maxLength='20' onchange="getPhotoSize(this.value)">
              <option value="">-请选择-</option>
            </select>
          </td>
       </tr>
       <tr>
          <th>广告标题<font color='red'>*</font>：</th>
          <td><input type='text' id='adName' name="adName" value='<?php echo $data['adName']; ?>' class='ipt' maxLength='20'/></td>
       </tr>
       <tr>
          <th>广告图片<font color='red'>*</font>：</th>
          <td><div id='adFilePicker'>上传广告图</div><span id='uploadMsg'></span>
              <div>
                图片大小:<span id="img_size">300x300</span>(px)，格式为 gif, jpg, jpeg, png
              </div>
          </td>

       </tr>
       <tr>
          <th>预览图<font color='red'>  </font>：</th>
          <td>
            <div id="preview" style="min-height:30px;">
              <?php if(($data['adFile']!='')): ?>
              <img src="__ROOT__/<?php echo $data['adFile']; ?>">
              <?php endif; ?>
            </div>
            <input type="hidden" name="adFile" id="adFile" class="ipt" value="<?php echo $data['adFile']; ?>" />
          </td>


       </tr>
       <tr>
          <th>广告网址<font color='red'>  </font>：</th>
          <td>
            <input type="text" id="adURL" class="ipt" maxLength="20" value='<?php echo $data['adURL']; ?>' />
          </td>
       </tr>
       <tr>
          <th >广告开始时间<font color='red'>*</font>：</th>
          <td>
            <input type="text" style="margin:0px;vertical-align:baseline;" id="adStartDate" name="adStartDate" class="ipt" maxLength="20"  />
          </td>
       </tr>
       <tr>
          <th>广告结束时间<font color='red'>*</font>：</th>
          <td>
            <input type="text" style="margin:0px;vertical-align:baseline;" id="adEndDate" name="adEndDate" class="ipt" maxLength="20" value='<?php echo $data['adEndDate']; ?>' />
          </td>
       </tr>
       <tr>
          <th>广告排序号：</th>
          <td>
            <input type="text" id="adSort" class="ipt" maxLength="20"  value='<?php echo $data['adSort']; ?>' />
          </td>
       </tr>
  
  <tr>
     <td colspan='2' align='center'>
       <input type="hidden" name="id" id="adId" class="ipt" value="<?php echo $data['adId']+0; ?>" />
       <input type="submit" value="提交" class='btn btn-blue' />
       <input type="button" onclick="javascript:history.go(-1)" class='btn' value="返回" />
     </td>
  </tr>
</table>
</form>
<script>
$(function(){
editInit();
//初始化位置类型
addPosition("<?php echo $data['positionType']; ?>","<?php echo $data['adPositionId']; ?>");
//时间插件
$("#adStartDate").ligerDateEditor().setValue('<?php echo $data['adStartDate']; ?>');
$("#adEndDate").ligerDateEditor().setValue('<?php echo $data['adEndDate']; ?>');
});

</script>


<script type='text/javascript' src='__STATIC__/plugins/webuploader/webuploader.js?v=<?php echo $v; ?>'></script>
<script src="__ADMIN__/ads/ads.js?v=<?php echo $v; ?>" type="text/javascript"></script>

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