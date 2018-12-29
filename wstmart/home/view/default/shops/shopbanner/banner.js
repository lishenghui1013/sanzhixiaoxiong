/**删除批量上传的图片**/
function delBatchUploadImg(obj){
    var c = WST.confirm({content:'您确定要删除广告图片吗?',yes:function(){
        $(obj).parent().remove("li");
        layer.close(c);
    }});
}
function lastGoodsCatCallback(opts){
    if(opts.isLast){
        getSpecAttrs(opts.val);
    }else{
        $('#specsAttrBox').empty();
    }
}
/**初始化**/
function initEdit(){
    $('#tab').TabPanel({tab:0,callback:function(no){
        if(no==1){
            $('.j-specImg').children().each(function(){
                if(!$(this).hasClass('webuploader-pick'))$(this).css({width:'80px',height:'25px'});
            });
        }
        if(!initBatchUpload && no==2){
            initBatchUpload = true;
            var uploader = batchUpload({uploadPicker:'#batchUpload',uploadServer:WST.U('home/index/uploadPic'),formData:{dir:'goods',isWatermark:1,isThumb:1},uploadSuccess:function(file,response){
                var json = WST.toJson(response);
                if(json.status==1){
                    $li = $('#adFile');
                    $li.append('<input type="hidden" class="j-gallery-img" iv="'+json.savePath + json.thumb+'" v="' +json.savePath + json.name+'"/>');
                    //$li.append('<span class="btn-setDefault">默认</span>' );
                    var delBtn = $('<span class="btn-del">删除</span>');
                    $li.append(delBtn);
                    delBtn.on('click',function(){
                        delBatchUploadImg($(this),function(){
                            uploader.removeFile(file);
                            uploader.refresh();
                        });
                    });
                    $('.filelist li').css('border','1px solid rgb(59, 114, 165)');
                }else{
                    WST.msg(json.msg,{icon:2});
                }
            }});
        }
        $('.btn-del').click(function(){
            delBatchUploadImg($(this),function(){
                $(this).parent().remove();
            });
        })
    }});
    WST.upload({
        pick:'#goodsImgPicker',
        formData: {dir:'goods',isWatermark:1,isThumb:1},
        accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
        callback:function(f){
            var json = WST.toJson(f);
            if(json.status==1){
                $('#uploadMsg').empty().hide();
                $('#preview').attr('src',WST.conf.ROOT+"/"+json.savePath+json.thumb);
                $('#adFile').val(json.savePath+json.name);
                $('#msg_goodsImg').hide();
            }
        },
        progress:function(rate){
            $('#uploadMsg').show().html('已上传'+rate+"%");
        }
    });
    KindEditor.ready(function(K) {
        editor1 = K.create('textarea[name="goodsDesc"]', {
            height:'350px',
            width:'800px',
            uploadJson : WST.conf.ROOT+'/home/goods/editorUpload',
            allowFileManager : false,
            allowImageUpload : true,
            items:[
                'source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy', 'paste',
                'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
                'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
                'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
                'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
                'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|','image','multiimage','table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
                'anchor', 'link', 'unlink', '|', 'about'
            ],
            afterBlur: function(){ this.sync(); }
        });
    });
    if(OBJ.goodsId>0){
        var goodsCatIds = OBJ.goodsCatIdPath.split('_');
        getBrands('brandId',goodsCatIds[0],OBJ.brandId);
        if(goodsCatIds.length>1){
            var objId = goodsCatIds[0];
            $('#cat_0').val(objId);
            var opts = {id:'cat_0',val:goodsCatIds[0],childIds:goodsCatIds,className:'j-goodsCats',afterFunc:'lastGoodsCatCallback'}
            WST.ITSetGoodsCats(opts);
        }
        getShopsCats('shopCatId2',OBJ.shopCatId1,OBJ.shopCatId2);
    }

}

function toEdit(id,src){
    location.href = WST.U('home/goods/edit','id='+id+'&src='+src);
}

/**保存商品数据**/
function save(){
    $('#editform').isValid(function(v){
        if(v){
            var params = WST.getParams('.j-ipt');
            // params.goodsCatId = WST.ITGetGoodsCatVal('j-goodsCats');
            // params.specNum = specNum;
            // var specsName,specImg;
            // $('.j-speccat').each(function(){
            //     specsName = 'specName_'+$(this).attr('cat')+'_'+$(this).attr('num');
            //     specImg = 'specImg_'+$(this).attr('cat')+'_'+$(this).attr('num');
            //     if($(this)[0].checked){
            //         params[specsName] = $.trim($('#'+specsName).val());
            //         params[specImg] = $.trim($('#'+specImg).attr('v'));
            //     }
            // });
            // var gallery = [];
            // $('.j-gallery-img').each(function(){
            //     gallery.push($(this).attr('v'));
            // });
            // params.gallery = gallery.join(',');
            // var specsIds = [];
            // var specidsmap = [];
            // $('.j-ws').each(function(){
            //     specsIds.push($(this).attr('v'));
            //     specidsmap.push(WST.blank($(this).attr('sid'))+":"+$(this).attr('v'));
            // });
            // var specmap = [];
            // for(var key in id2SepcNumConverter){
            //     specmap.push(key+":"+id2SepcNumConverter[key]);
            // }
            // params.specsIds = specsIds.join(',');
            // params.specidsmap = specidsmap.join(',');
            // params.specmap = specmap.join(',');
            var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
            $.post(WST.U('home/goods/'+((params.goodsId==0)?"toAdd":"toEdit")),params,function(data,textStatus){
                layer.close(loading);
                var json = WST.toJson(data);
                if(json.status=='1'){
                    WST.msg(json.msg,{icon:1});
                    location.href=WST.U('home/goods/'+src);
                }else{
                    WST.msg(json.msg,{icon:2});
                }
            });
        }
    });
}
/**保存广告数据**/
function bannerSave(){
    $('#editform').isValid(function(v){
        if(v){
            var params = WST.getParams('.j-ipt');
            var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
            $.post(WST.U('home/Shopbanner/'+((params.adId==0)?"addToBanner":"bannerToEdit")),params,function(data,textStatus){
                console.log(data);
                layer.close(loading);
                var json = WST.toJson(data);
                if(json.status=='1'){
                    WST.msg(json.msg,{icon:1});
                    location.href=WST.U('home/Shopbanner/index');
                }else{
                    WST.msg(json.msg,{icon:2});
                }
            });
        }
    });
}
//托管
function saleByPage(p){
    var params = {};
    params = WST.getParams('.s-query');
    // console.log(params);
    var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
    // alert(params.expiryTime);
    $.post(WST.U('home/Shopbanner/Trusteeship'),params,function(data,textStatus){
        layer.close(loading);
        console.log(data);
        var json = WST.toJson(data);
        if(json.status=='1'){
            WST.msg(json.msg,{icon:1});
            // location.href=WST.U('home/Shopbanner/index');
        }else if(json.status=='-2'){
            WST.msg(json.msg,{icon:1,time:1000});
            location.href=WST.U('home/Shoprenzheng/shopinfo');
        }else{
            WST.msg(json.msg,{icon:2});
        }

    });
}


//删除广告
function dele(id,func){
    var c = WST.confirm({content:'您确定要删除广告吗?',yes:function(){
        layer.close(c);
        var load = WST.load({msg:'正在删除，请稍后...'});
        $.post(WST.U('home/Shopbanner/del'),{id:id},function(data,textStatus){
            layer.close(load);
            console.log(data);
            var json = WST.toJson(data);
            if(json.status==1){
                WST.msg(json.msg,{icon:1});
                location.href=WST.U('home/Shopbanner/index');
            }else{
                WST.msg(json.msg,{icon:2});
            }
        });
    }});
}

// 批量 上架/下架
function changeSale(i,func){
    var ids = WST.getChks('.chk');
    if(ids==''){
        WST.msg('请先选择商品!', {icon: 5});
        return;
    }
    var params = {};
    params.ids = ids;
    params.isSale = i;
    $.post(WST.U('home/goods/changeSale'), params, function(data,textStatus){
        var json = WST.toJson(data);
        if(json.status=='1'){
            WST.msg('操作成功',{icon:1},(function(){
                $('#all').prop('checked',false);
                switch(func){
                    case 'store':storeByPage(0);break;
                    case 'sale':saleByPage(0);break;
                    case 'spellSale':spellSaleByPage(0);break;
                    case 'audit':auditByPage(0);break;
                }
            }));
        }else if(json.status=='-2'){
            WST.msg(json.msg, {icon: 5});
        }else if(json.status=='2'){
            WST.msg(json.msg, {icon: 5},function(){
                switch(func){
                    case 'store':storeByPage(0);break;
                    case 'sale':saleByPage(0);break;
                    case 'spellSale':spellSaleByPage(0);break;
                    case 'audit':auditByPage(0);break;
                }
            });
        }else if(json.status=='-3'){
            WST.msg(json.msg, {icon: 5,time:3000});
        }else{
            WST.msg('操作失败!', {icon: 5});
        }
    });
}

// 批量设置 精品/新品/推荐/热销
function changeGoodsStatus(isWhat,func){
    var ids = WST.getChks('.chk');
    if(ids==''){
        WST.msg('请先选择商品!', {icon: 5});
        return;
    }
    var params = {};
    params.ids = ids;
    params.is = isWhat;
    $.post(WST.U('home/goods/changeGoodsStatus'),params,function(data,textStatus){
        var json = WST.toJson(data);
        if(json.status=='1'){
            WST.msg('设置成功',{icon:1},function(){
                $('#all').prop('checked',false);
                switch(func){
                    case 'store':storeByPage(0);break;
                    case 'sale':saleByPage(0);break;
                    case 'spellSale':spellSaleByPage(0);break;
                    case 'audit':auditByPage(0);break;
                }
            });
        }else{
            WST.msg('设置失败',{icon:5});
        }
    });
}

// 双击设置
function changSaleStatus(isWhat, obj, id){
    var params = {};
    status = $(obj).attr('status');
    params.status = status;
    params.id = id;
    switch(isWhat){
        case 'r':params.is = "isRecom";break;
        case 'b':params.is = "isBest";break;
        case 'n':params.is = "isNew";break;
        case 'h':params.is = "isHot";break;
    }
    var load = WST.load({msg:'请稍后...'});
    $.post(WST.U('home/goods/changSaleStatus'),params,function(data,textStatus){
        layer.close(load);
        var json = WST.toJson(data);
        if(json.status==1){
            if(status==0){
                $(obj).attr('status',1);
                $(obj).removeClass('wrong').addClass('right');
            }else{
                $(obj).attr('status',0);
                $(obj).removeClass('right').addClass('wrong');
            }
        }else{
            WST.msg('操作失败',{icon:5});
        }
    });
}

//双击修改
function toEditGoodsBase(fv,goodsId,flag){
    if((fv==2 || fv==3) && flag==1){
        WST.msg('该商品存在商品属性，不能直接修改，请进入编辑页修改', {icon: 5});
        return;
    }else{
        $("#ipt_"+fv+"_"+goodsId).show();
        $("#span_"+fv+"_"+goodsId).hide();
        $("#ipt_"+fv+"_"+goodsId).focus();
        $("#ipt_"+fv+"_"+goodsId).val($("#span_"+fv+"_"+goodsId).html());
    }

}
function endEditGoodsBase(fv,goodsId){
    $('#span_'+fv+'_'+goodsId).html($('#ipt_'+fv+'_'+goodsId).val());
    $('#span_'+fv+'_'+goodsId).show();
    $('#ipt_'+fv+'_'+goodsId).hide();
}
function editGoodsBase(fv,goodsId){

    var vtext = $('#ipt_'+fv+'_'+goodsId).val();
    if($.trim(vtext)==''){
        if(fv==2){
            WST.msg('价格不能为空', {icon: 5});
        }else if(fv==3){
            WST.msg('库存不能为空', {icon: 5});
        }
        return;
    }
    var params = {};
    (fv==2)?params.shopPrice=vtext:params.goodsStock=vtext;
    params.goodsId = goodsId;
    $.post(WST.U('Home/Goods/editGoodsBase'),params,function(data,textStatus){
        var json = WST.toJson(data);
        if(json.status>0){
            $('#img_'+fv+'_'+goodsId).fadeTo("fast",100);
            endEditGoodsBase(fv,goodsId);
            $('#img_'+fv+'_'+goodsId).fadeTo("slow",0);
        }else{
            WST.msg('修改失败!', {icon: 5});
        }
    });
}

function benchDel(func,flag){
    if(flag==1){
        var ids = WST.getChks('.chk1');
    }else{
        var ids = WST.getChks('.chk');
    }

    if(ids==''){
        WST.msg('请先选择商品!', {icon: 5});
        return;
    }
    var params = {};
    params.ids = ids;
    var load = WST.load({msg:'请稍后...'});
    $.post(WST.U('home/goods/batchDel'),params,function(data,textStatus){
        layer.close(load);
        var json = WST.toJson(data);
        if(json.status=='1'){
            WST.msg('操作成功',{icon:1},function(){
                $('#all').prop('checked',false);
                switch(func){
                    case 'store':storeByPage(0);break;
                    case 'sale':saleByPage(0);break;
                    case 'spellSale':spellSaleByPage(0);break;
                    case 'audit':auditByPage(0);break;
                }
            });
        }else{
            WST.msg('操作失败',{icon:5});
        }
    });
}

function getCat(val){
    if(val==''){
        $('#cat2').html("<option value='' >-请选择-</option>");
        return;
    }
    $.post(WST.U('home/shopcats/listQuery'),{parentId:val},function(data,textStatus){
        var json = WST.toJson(data);
        var html = [],cat;
        html.push("<option value='' >-请选择-</option>");
        if(json.status==1 && json.list){
            json = json.list;
            for(var i=0;i<json.length;i++){
                cat = json[i];
                html.push("<option value='"+cat.catId+"'>"+cat.catName+"</option>");
            }
        }
        $('#cat2').html(html.join(''));
    });
}