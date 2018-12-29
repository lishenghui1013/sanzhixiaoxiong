<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:61:"E:\demo\sanzhixiaoxiong/wstmart/admin\view\articles\edit.html";i:1523179156;s:52:"E:\demo\sanzhixiaoxiong/wstmart/admin\view\base.html";i:1523179156;}*/ ?>
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

<input type='hidden' id='articleId' value='<?php echo $object["articleId"]; ?>'/>
<div class="l-loading" style="display: block" id="wst-loading"></div>
<form id='articleForm' autocomplete="off">
<table class='wst-form wst-box-top'>
  <tr>
     <th width='150'>文章标题<font color='red'>*</font>：</th>
     <td><input type="text" id='articleTitle' name='articleTitle' maxLength='50' style='width:300px;' class='ipt'/></td>
  </tr>
   <tr>
     <th width='150' align='right'>分类类型<font color='red'>*</font>：</th>
   <td>
	   <input style="width: 200px;" class="l-text-field" readonly="" id="catIds" name="catIds" type="text" value="<?php echo $object['catName']; ?>"><span id="catIdt"></span>
	   <input id="catId"  class="text ipt" autocomplete="off" type="hidden" value=""/>
   </td>
   </tr>
      <tr>
      <th width='150'>是否显示<font color='red'>*</font>：</th>
      <td height='24'>
         <label>
            <input type="radio" id="isShow1" name="isShow" class="ipt" value="1" checked>显示
         </label>
         <label>
            <input type="radio" id="isShow1" name="isShow" class="ipt" value="0">隐藏
         </label>
      </td>
   </tr>
  <tr>
     <th width='150'>关键字<font color='red'>*</font>：</th>
     <td><input type="text" id='articleKey' name='articleKey' maxLength='120' style='width:600px;' class='ipt'/></td>
  </tr>
   <tr>
       <th width='150'>文章内容<font color='red'>*</font>：</th>
       <td>
       	<textarea id='articleContent' name='articleContent' class="form-control ipt" style='width:80%;height:400px'></textarea>
       </td>
    </tr>  
     <tr>
       <td colspan='2' align='center'>
           <button type="submit" class="btn btn-blue">保&nbsp;存</button>
           <button type="button" class="btn" onclick="javascript:history.go(-1)">返&nbsp;回</button>
       </td>
     </tr>
</table>
 </form>
  <script>
$(function(){
  //文件上传
	WST.upload({
  	  pick:'#filePicker',
  	  formData: {dir:'adspic'},
  	  accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
  	  callback:function(f){
  		  var json = WST.toAdminJson(f);
  		  if(json.status==1){
        	$('#preview').html('<img src="'+WST.conf.ROOT+'/'+json.savePath+json.thumb+'" height="152" />');
        	$('#articleImg').val(json.savePath+json.thumb);
  		  }
	  }
    });
  //编辑器
    KindEditor.ready(function(K) {
		editor1 = K.create('textarea[name="articleContent"]', {
			height:'350px',
			allowFileManager : false,
			allowImageUpload : true,
			items:[
			        'source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy', 'paste',
			        'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
			        'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
			        'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
			        'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
			        'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|','image','table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
			        'anchor', 'link', 'unlink', '|', 'about'
			],
			afterBlur: function(){ this.sync(); }
		});
	});
});
</script>


<script src="__STATIC__/plugins/webuploader/webuploader.js?v=<?php echo $v; ?>" type="text/javascript" ></script>
<script src="__STATIC__/plugins//kindeditor/kindeditor.js?v=<?php echo $v; ?>" type="text/javascript" ></script>
<script src="__ADMIN__/articles/articles.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script>
$(function () {
	initCombo();
	<?php if($object['articleId'] !=0): ?>
	   WST.setValues(<?php echo $object; ?>);
	<?php endif; ?>
	$('#articleForm').validator({
	    fields: {
	    	articleTitle: {
	    		tip: "请输入文章名称",
	    		rule: '文章名称:required;length[~50];'
	    	},
	    	catIds: {
		        tip: "请选择文章分类",
		    	rule: "文章分类:required;",
		    	target:"#catIdt"
		    },
	    	articleKey: {
	    		tip: "请输入关键字",
	    		rule: '关键字:required;length[~100];'
	    	},
		    articleContent: {
	    		tip: "请输入文章内容",
	    		rule: '文章内容:required;'
	    	}
	    },
	    valid: function(form){
	    	var articleId = $('#articleId').val();
	    	toEdits(articleId);
	    }
	})
});
</script>

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