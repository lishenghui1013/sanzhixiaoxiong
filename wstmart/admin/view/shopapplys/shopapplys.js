var grid;
function initGrid(){
	// console.log(88888);
	grid = $("#maingrid").ligerGrid({
		url:WST.U('admin/shopapplys/pageQuery'),
		pageSize:WST.pageSize,
		pageSizeOptions:WST.pageSizeOptions,
		height:'99%',
        width:'100%',
        minColToggle:6,
        rownumbers:true,
        columns: [
            { display: '申请人', name: 'loginName',Sort: false
				// render: function (rowdata, rowindex, value){return rowdata['linkman']+WST.blank(rowdata['loginName']);}
				},
            { display: '公司名称', name: 'ca_company_name',Sort: false},
            { display: '联系人姓名', name: 'ca_Identity_selection',Sort: false},
            { display: '联系人手机', name: 'ca_Main_type',Sort: false},
            { display: '联系人身份证', name: 'ca_Main_type_subclass',Sort: false},
            // { display: '辅营身份类型', name: 'ca_Auxiliary_type',Sort: false},
            // { display: '辅营身份子类', name: 'ca_Auxiliary_type_subclass',Sort: false},
            // { display: '营业证 ', name: 'ca_Business_license',Sort: false},
            // { display: '开户许可证', name: 'ca_Open_an_account',Sort: false},
            { display: '申请时间', name: 'ca_CreationTime',Sort: false},
            { display: '状态', name: 'ca_attn',Sort: false,render: function (rowdata, rowindex, value){
                return (rowdata['ca_attn']==2)?"认证成功":((rowdata['ca_attn']==3)?"认证失败":"审核中");
            }},
            { display: '操作', name: 'op',isSort: false,render: function (rowdata, rowindex, value){
                var h = "";
                if(rowdata['ca_attn']==1 && WST.GRANT.DPSQ_04)h += "<a href='javascript:toEdit(" + rowdata['ca_id'] + ")'>处理</a> ";
                if(rowdata['ca_attn']!=1 && WST.GRANT.DPSQ_04)h += "<a href='javascript:;'>已处理</a> ";
                // if(WST.GRANT.DPSQ_03)h += "<a href='javascript:toDel(" + rowdata['ca_id'] + ")'>删除</a> ";
                if(WST.GRANT.DPGL_01 && !rowdata['shopId'] && rowdata['applyStatus']==1)h += "<a href='javascript:toAddShop(" + rowdata['ca_id'] + ")'>开店</a> ";
                return h;
            }}
        ]
    });
}
function toEdit(id){
	location.href=WST.U('admin/shopapplys/toHandle','id='+id);
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
	if(!$('input[name="ca_attn"]').isValid())return;
	if($('input[name="ca_attn"]:checked').val()==3 && !$('#ca_handleDesc').isValid())return;
	var params = WST.getParams('.ipt');
	var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
    $.post(WST.U('admin/shopapplys/handle'),params,function(data,textStatus){
        console.log(data);
        layer.close(loading);
    	var json = WST.toAdminJson(data);
    	if(json.status=='1'){
    		WST.msg("操作成功",{icon:1});
    		if(WST.GRANT.DPGL_01 && params.ca_attn==2){
    			// toAddShop(params.applyId);
                location.href=WST.U('admin/shopapplys/index');
            }else{
    		    location.href=WST.U('admin/shopapplys/index');
    		}
    	}else{
    		WST.msg(json.msg,{icon:2});
    	}
    });
}
