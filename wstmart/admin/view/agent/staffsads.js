var grid;
function initGrid(){
	grid = $("#maingrid").ligerGrid({
		url:WST.U('admin/agent/adspageQuery'),
		pageSize:WST.pageSize,
		pageSizeOptions:WST.pageSizeOptions,
		height:'99%',
        width:'100%',
        minColToggle:6,
        rownumbers:true,
        columns: [
            { display: 'ID', name: 'trId', isSort: false},
	        { display: '位置名称', name: 'positionName', isSort: false ,width:'50%',heightAlign:'left',align:'left'},
	        { display: '宽度', name: 'positionWidth', isSort: false},
	        { display: '高度', name: 'positionHeight', isSort: false},
	        { display: '位置类型', name: 'positionType', isSort: false,render:function(rowdata, rowindex, value){
	        	return (rowdata['positionType']==1)?'平台':'商家';
	        }},
          { display: '位置代码', name: 'positionCode', isSort: false},
	        { display: '排序号', name: 'apSort', isSort: false},
	        { display: '操作', name: 'op',isSort: false,render: function (rowdata, rowindex, value){
	        	var h = "";
	            if(WST.GRANT.DLGLA_02){
	                if(rowdata['type']==2){
                        h += "<a href='javascript:toShared(" + rowdata['trId'] +")'>共享</a> ";
                    }else{
                        h += "<a href='#'>已共享</a> ";
                    }
                }
				if(WST.GRANT.DLGLA_02)h += "<a href='javascript:uploadpic(" + rowdata['trpositionId'] + "," +rowdata['shopId'] +")'>上传图片</a> ";
	            return h;
	        }}
        ]
    });
}
function uploadpic(id,shopId){
	location.href=WST.U('admin/agent/adsupload','id='+id+'&shopId='+shopId);
}
function toShared(id){
	var w = WST.open({type: 1,title:"共享广告位",shade: [0.6, '#000'],border: [0],content:$('#editPassBox'),area: ['450px', '250px'],
	    btn: ['确定', '取消'],yes: function(index, layero){
	    	$('#editPassFrom').isValid(function(v){
	    		if(v){
		        	var params = WST.getParams('.ipt');
		        	params.id = id;
		        	var ll = WST.msg('数据处理中，请稍候...');
				    $.post(WST.U('admin/agent/editshare'),params,function(data){
				    	layer.close(ll);
                        console.log(data);
                        var json = WST.toAdminJson(data);
						if(json.status==1){
							WST.msg(json.msg, {icon: 1});
							layer.close(w);
						}else{
							WST.msg(json.msg, {icon: 2});
						}
				   });
	    		}
	    	})
        }
	});
}
function toDel(id){
	var box = WST.confirm({content:"您确定要删除该记录吗?",yes:function(){
	           var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
	           	$.post(WST.U('admin/AdPositions/del'),{id:id},function(data,textStatus){
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



function editInit(){
	 /* 表单验证 */
    $('#adPositionsForm').validator({
            fields: {
                positionType: {
                  rule:"required",
                  msg:{required:"请选择位置类型"},
                  tip:"请选择位置类型",
                  ok:"",
                },
                positionName: {
                  rule:"required;",
                  msg:{required:"请输入位置名称"},
                  tip:"请输入位置名称",
                  ok:"",
                },
                positionCode: {
                    rule:"required;",
                    msg:{required:"请输入位置代码"},
                    tip:"请输入位置代码",
                    ok:"",
                  },
                positionWidth: {
                  rule:"required;",
                  msg:{required:"请输入建议宽度"},
                  ok:"",
                },
                positionHeight: {
                  rule:"required",
                  msg:{required:"请输入建议高度"},
                  ok:"",
                }
            },
          valid: function(form){
            var params = WST.getParams('.ipt');
            var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
            $.post(WST.U('admin/Adpositions/'+((params.positionId==0)?"add":"edit")),params,function(data,textStatus){
              layer.close(loading);
              var json = WST.toAdminJson(data);
              if(json.status=='1'){
                  WST.msg("操作成功",{icon:1});
                  location.href=WST.U('Admin/Adpositions/index');
              }else{
                    WST.msg(json.msg,{icon:2});
              }
            });
      }
    });
}