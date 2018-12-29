var grid;
function initGrid(){
	// console.log(88888);
	grid = $("#maingrid").ligerGrid({
		url:WST.U('admin/userauth/pageQuery'),
		pageSize:WST.pageSize,
		pageSizeOptions:WST.pageSizeOptions,
		height:'99%',
        width:'100%',
        minColToggle:6,
        rownumbers:true,
        columns: [
            { display: '认证名字', name: 'loginName',Sort: false
				// render: function (rowdata, rowindex, value){return rowdata['linkman']+WST.blank(rowdata['loginName']);}
				},
            { display: '身份证正面', name: 'zhengUrl', isSort: false,render:function(rowdata, rowindex, value){
                return '<img src="'+WST.conf.ROOT+'/'+rowdata['zhengUrl']+'" height="28px" />';
            }},
            { display: '身份证反面', name: 'fanUrl', isSort: false,render:function(rowdata, rowindex, value){
                return '<img src="'+WST.conf.ROOT+'/'+rowdata['fanUrl']+'" height="28px" />';
            }},
            { display: '联系人手机', name: 'userPhone',Sort: false},
            // { display: '联系人身份证', name: '',Sort: false},
            { display: '申请时间', name: 'createTime',Sort: false},
            { display: '状态', name: 'idCardStatus',Sort: false,render: function (rowdata, rowindex, value){
                return (rowdata['idCardStatus']==2)?"认证成功":((rowdata['idCardStatus']==3)?"认证失败":"审核中");
            }},
            { display: '操作', name: 'op',isSort: false,render: function (rowdata, rowindex, value){
                var h = "";
                if(rowdata['idCardStatus']==1 && WST.GRANT.DPSQ_04)h += "<a href='javascript:toEdit(" + rowdata['idCard_id'] + ")'>处理</a> ";
                if(rowdata['idCardStatus']!=1 && WST.GRANT.DPSQ_04)h += "<a href='javascript:;'>已处理</a> ";
                // if(WST.GRANT.DPSQ_03)h += "<a href='javascript:toDel(" + rowdata['ca_id'] + ")'>删除</a> ";
                if(WST.GRANT.DPGL_01 && !rowdata['shopId'] && rowdata['applyStatus']==1)h += "<a href='javascript:toAddShop(" + rowdata['idCard_id'] + ")'>开店</a> ";
                return h;
            }}
        ]
    });
}
function toEdit(id){
	location.href=WST.U('admin/userauth/toHandle','id='+id);
}
function toAddShop(id){
	location.href=WST.U('admin/shops/toAddByApply','id='+id);
}
function toDel(id){
	var box = WST.confirm({content:"您确定要删除该企业认证吗?",yes:function(){
	           var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
	           $.post(WST.U('admin/shopapplys/del'),{id:id},function(data,textStatus){
	           			  layer.close(loading);
	           			  var json = WST.toAdminJson(data);
	           			  if(json.status=='1'){
	           			    	WST.msg("操作成功",{icon:1});
	           			    	layer.close(box);
	           		            grid.reload();
	           			  }else{
	           			    	WST.msg(json.msg,{icon:2});
	           			  }
	           		});
	            }});
}
function save(){
	if(!$('input[name="idCardStatus"]').isValid())return;
	if($('input[name="idCardStatus"]:checked').val()==3 && !$('#handleDesc').isValid())return;
	var params = WST.getParams('.ipt');
	var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
    $.post(WST.U('admin/userauth/handle'),params,function(data,textStatus){
        console.log(data);
        layer.close(loading);
    	var json = WST.toAdminJson(data);
    	if(json.status=='1'){
    		WST.msg("操作成功",{icon:1});
    		if(WST.GRANT.DPGL_01 && params.ca_attn==2){
    			// toAddShop(params.applyId);
                location.href=WST.U('admin/userauth/index');
            }else{
    		    location.href=WST.U('admin/userauth/index');
    		}
    	}else{
    		WST.msg(json.msg,{icon:2});
    	}
    });
}
