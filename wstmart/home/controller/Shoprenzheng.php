<?php

namespace wstmart\home\controller;
use think\Db;
use think\Log;
/**
 * 商户认证控制器
 */
class Shoprenzheng extends Base{
    /**
     * 认证页
     */
    public function index(){
//        echo 555;
        $type=WSTGoodsCats();
        $this->assign('type',$type);
        return $this->fetch('renzheng');
//        return $this->fetch('shop_login');
    }
    /*
     * 卖家中心--上传 营业证 图片
     */
    public function upidentity3(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('images3');
        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move(ROOT_PATH . 'upload' . DS . 'public');
            if($info){
                // 成功上传后 获取上传信息
                echo json_encode('/upload/public/'.$info->getSaveName());
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
    }
    /*
     * 卖家中心--上传 开户许可证 图片
     */
    public function upidentity4(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('images4');
        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move(ROOT_PATH . 'upload' . DS . 'public');
            if($info){
                // 成功上传后 获取上传信息
                echo json_encode('/upload/public/'.$info->getSaveName());
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
    }
    /**
     * 卖家中心--商家认证
     */
    public function companyCertified(){
        $data=input('post.');
//        dump($data);die;

        $userID=(int)session('WST_USER.userId');
//        if(!isset($userID)||!$userID){
//            $this->redirect("Users/login");
//        }

        $isExist = Db::name('company_auth')->where(['ca_users_id'=>$userID,'ca_status'=>1])->find();
//        var_dump($data);die();
        if($isExist['ca_attn']==1){
            return WSTReturn('您的认证，正在审核中！');
        }elseif ($isExist['ca_attn']==2){
            return WSTReturn('恭喜您，企业认证成功，请勿重复提交');
        }elseif ($isExist['ca_attn']==3){
            return WSTReturn('很抱歉，企业认证失败');
        }
        //生鲜 必选收货时间
        if($data['ca_isBrand']==3){
           $pickupTime = input('post.pickupTime');
            if(!isset($pickupTime)){
                return WSTReturn('请选择收货时间',-1);
            }
        }
//        dump($isExist);die;
        foreach ($data as $key=>$value){
            if(empty($value)){
                if($key=='latitude') return WSTReturn('请选择店铺地址',-1);
                return WSTReturn('请填写完整信息',-1);
            }
        }
        $data['ca_users_id']=(int)session('WST_USER.userId');
        $data['ca_CreationTime']=date('Y-m-d H:i:s');
        $data['ca_attn']=1;

        $Result=Db::name('company_auth')->insert($data);
        if($Result){
            return WSTReturn('提交成功，等待审核中！',1);
        }else{
            return WSTReturn('发布失败！请刷新重试！',-1);
        }
    }
    /* 店铺管理页面 */
    public function shopsview(){
        $shopId = (int)session('WST_USER.shopId');
        $object=Db::name('shop_info')->where("shopId=".$shopId)->find();
        if($object != ''){
            //图片
            $object['shopAds'] = ($object['shopAds']!='')?explode(',',$object['shopAds']):null;
            //图片的广告地址
            $object['shopAdsUrl'] = ($object['shopAdsUrl']!='')?explode(',',$object['shopAdsUrl']):null;
            $object['shopName']=session('WST_USER.shopName');
            $object['shopImg']=session('WST_USER.shopImg');
            //获取商店实景
            if($object['shopView']!='')$object['shopView'] = explode(',',$object['shopView']);
            $this->assign('object',$object);
            $this->assign('type','edit');
        }else{
            $this->assign('type','add');}
        //print_r($object);
        return $this->fetch('shops/goods/shop_shezhi');
    }
    //修改店铺信息
    public function editshop(){
        $shopId = (int)session('WST_USER.shopId');
        $data = input('post.');
//        dump($data);die();
        //加载商店信息
        Db::startTrans($data);
        try{
            //$shopcg = $this->where('shopId='.$shopId)->find();
            $scdata = array();
            $scdata["shopId"] =  $shopId;
            $scdata["goodsDesc"] =  Input("goodsDesc");
            $scdata["shopDesc"] =  Input("shopDesc");
            // $scdata["shopAds"] =  Input("shopAds");
            // $scdata["shopAdsUrl"] =  Input("shopAdsUrl");
            $scdata["contact"] =  Input("contact");
            //商家实图
            $scdata["shopView"] =  Input("shopView");
            $data1['shopImg']=input("shopImg");
            $data1['shopName']=input("shopName");
            // WSTUseImages(0, $shopcg['configId'], $scdata['shopBanner'],'shop_configs','shopBanner');
            // WSTUseImages(0, $shopcg['configId'], $scdata['shopAds'],'shop_configs','shopAds');

        $type=input('type');
            $rs = Db::name('shops')->where("shopId=".$shopId)->update($data1);
            if($type=='add'){
                $rs1=Db::name('shop_info')->insert($scdata);
            }else{
                unset($scdata['shopId']);
                $rs1=Db::name('shop_info')->where("shopId=".$shopId)->update($scdata);
            }
//        dump($rs1);die();

        if($rs>0||$rs1>0)
            {
                Db::commit();
                $USER = session('WST_USER');
                $USER['shopImg']=$data1['shopImg'];
                $USER['shopName']=$data1['shopName'];
                session('WST_USER',$USER);
                return WSTReturn('操作成功',1);
            }

        }catch (\Exception $e) {
            Db::rollback();
        }
        return WSTReturn('操作失败',-1);
    }

    /*=============================================================================================================================================*/

    /**
     * 查看店铺设置
     */
    public function shopinfo(){
        $s = model('shops');
        $object = $s->getByView((int)session('WST_USER.shopId'));
        $banks=Db::name('banks')->where('dataFlag',1)->field('bankId,bankName')->select();
        $areaList=model('areas')->listQuery(0);
        $areaname=model('areas')->getParentNames($object['bankAreaId']);
        $areaname=implode('-',$areaname);
        $this->assign('areaname',$areaname);
        $this->assign("areaList",$areaList);
        $this->assign("bankList",$banks);
        $this->assign('object',$object);
        //print_r($object);
        return $this->fetch('shops/goods/shop_ziliao');
    }
    //结算银行卡号更新
    public function editbank(){
        $shopId = (int)session('WST_USER.shopId');
        $data = input('post.');
        //加载商店信息
        Db::startTrans();
        try{
            $scdata = array();
            $scdata["bankId"] =  $data['bankId'];
            $scdata["bankNo"] =  Input("bankNo");
            $scdata["bankUserName"] =  Input("bankUserName");

            if(!empty($data['bankAreaId'])){
                $scdata["bankAreaId"] =  $data['bankAreaId'];
                $areaIds = model('Areas')->getParentIs($data['bankAreaId']);
            }

            if(!empty($areaIds))$scdata['bankAreaIdPath'] = implode('_',$areaIds)."_";
            $rs = Db::name('shops')->where("shopId=".$shopId)->update($scdata);
            if($rs!==false)
            {
                Db::commit();
                return WSTReturn('操作成功',1);
            }

        }catch (\Exception $e) {
            Db::rollback();
        }
        return WSTReturn('操作失败',-1);
    }
    //店铺详细更新
    public function editinfo(){
        $shopId = (int)session('WST_USER.shopId');
        $data = input('post.');
        Log::write($data,666666);
//        dump($data);die();
        //加载商店信息
        Db::startTrans();
        try{
            $scdata = array();
            $scdata["shopQQ"] =  $data['shopQQ'];
            $scdata["shopAddress"] =  Input("shopAddress");
            $scdata["isInvoice"] =  (int)Input("isInvoice")?(int)Input("isInvoice"):(int)Input("isInvoice1");
            $scdata['invoiceRemarks']=Input('invoiceRemarks');
            $scdata['freight']=(int)Input('freight');

            if(!empty($data['AreaId'])){
                $scdata["areaId"] =  Input("post.AreaId");
                $areaIds = model('Areas')->getParentIs($data['AreaId']);
            }
            if(!empty($areaIds))$scdata['areaIdPath'] = implode('_',$areaIds)."_";
             Db::name('shops')->where("shopId=".$shopId)->update($scdata);

                Db::commit();
                return WSTReturn('操作成功',1);

        }catch (\Exception $e) {
            Db::rollback();
        }
        return WSTReturn('操作失败',-1);
    }







}