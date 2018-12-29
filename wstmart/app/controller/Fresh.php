<?php

namespace wstmart\app\controller;
use think\Db;
use think\Config;

/*
 * 新鲜到家控制器
 */
header('Access-Control-Allow-Origin:*');
class Fresh extends Base {
    //新鲜到家-轮播图
    public function ads(){
        $arr=Db::name('trusteeship')->where(['shopId'=>'-1','trpositionId'=>40,'dataFlag'=>1])->find();
        $time=date('Y-m');
        $isTrusteeship = date('Y-m', strtotime($arr['createTime']));
        //判断是否 在托管期
        if(!empty($arr) && $time<=$arr['endDate'] && $time>$isTrusteeship){
            //首页广告
            $ads = Db::name('ads')
                ->where(['adPositionId'=>40,'dataFlag'=>1,'adName'=>'共享'])
                ->field('	adFile,adId')
                ->select();
        }else{
            //首页广告
            $ads = Db::name('ads')
                ->where(['adPositionId'=>40,'dataFlag'=>1])
                ->where("IFNULL(adName,'') != '共享'")
                ->field('	adFile,adId')
                ->select();
        }
        foreach($ads as $k=>$v){
            $ads[$k]['adFile']=Config::get('site_domain').'/'.$v['adFile'];
        }
        return SZXReturn($this->message[1],"1",$ads);
    }
    //热销店铺
    public function sellsShop(){
        $where=[];
        //生鲜
        $where['u.isBrand']=3;
        $where['s.shopStatus']=1;
        $where['s.dataFlag']=1;
        $data=Db::name('shops')->alias('s')
            ->join("__USERS__ u","s.userId = u.userId",'inner')
            ->where($where)
            ->field('s.shopId,s.shopImg,s.shopName,u.isBrand')
            ->select();
        foreach($data as $key=>$value){
            $data[$key]['shopImg']=Config::get('site_domain').'/'.$value['shopImg'];
        }
        return SZXReturn($this->message[1],"1",$data);

    }
    //分类搜索-生鲜 店铺列表
    public function shopsList(){
        $goodsCatatId=input('post.catId/d');
//        $key = input('post.key');
        //页码
        $page = intval(input('post.page',1));
        //每页显示条数
        $pagesize = Config::get('pagesize');
        $startRow = ($page-1)*$pagesize;

        $where = [];
        $where['s.dataFlag'] = 1;
        //生鲜
        $where['u.isBrand']=3;
        $where['shopStatus'] = 1;
        if($goodsCatatId!='')$where['catId'] = $goodsCatatId;
        //去除自营店铺
        $where['s.shopId'] = ['<>',1];
//        dump($where);
//        if($key!='')$where['shopsName|shopSn'] = ['like','%'.$key.'%'];
        $data=Db::name('shops')->alias('s')
            ->join('__CAT_SHOPS__ cs','s.shopId=cs.shopId','inner')
            ->join("__USERS__ u","s.userId = u.userId",'inner')
            ->where($where)->limit($startRow,$pagesize)
            ->field('shopName,s.shopId,shopSn,shopImg')->select();
        foreach($data as $key=>$v){
            if($v['shopImg']!='')$data[$key]['shopImg']=Config::get('site_domain').'/'.$v['shopImg'];
        }
//        dump($data);
        return SZXReturn($this->message[1],"1",$data);
    }



    //店铺详情-头像、店名、简介、默认运费
    public function shopInfo(){
        $shopId=input('post.shopId');
        //生鲜
        $where['u.isBrand']=3;
        $where['s.shopStatus'] = 1;
        $where['s.shopId']=$shopId;
        $data=Db::name('shops')->alias('s')
            ->join("__USERS__ u","s.userId = u.userId",'inner')
            ->join("__SHOP_INFO__ si","s.shopId = si.shopId",'inner')
            ->field('s.shopId,s.shopImg,s.shopName,s.freight,si.shopDesc')
            ->where($where)->find();
        if($data['shopImg']!='')$data['shopImg']=Config::get('site_domain').'/'.$data['shopImg'];
        return SZXReturn($this->message[1],"1",$data);
    }
    //店铺详情-广告
    public function shopAds(){
        $shopId=input('post.shopId');
        //首页广告
        $ads = Db::name('ads')->where(['adPositionId'=>37,'shopId'=>$shopId])->field('	adFile')->select();
        if($ads != ''){
            foreach($ads as $k=>$v){
                $ads[$k]['adFile']=Config::get('site_domain').'/'.$v['adFile'];
            }
        }
        return SZXReturn($this->message[1],"1",$ads);
    }
    //店铺详情-门店分类
    public function shopCats(){
        $shopId=input('post.shopId');
        $cats=Db::name('shop_cats')->where(['shopId'=>$shopId,'dataFlag'=>1,'isShow'=>1])->select();
        return SZXReturn($this->message[1],"1",$cats);
    }
    //店铺详情-分类搜索-商品信息
    public function goodsInfo(){
        $shopId=input('post.shopId');
        $catId=input('post.catId');
        //门店分类
        if($catId!='')$where['m.shopCatId1'] = $catId;

        $where['m.shopId'] = $shopId;
        $where['m.isSpell'] = 0;
        //推荐
//        $where['m.isRecom'] = 1;
        $Result = Db::name('goods')->alias('m')
            ->where($where)
//            ->join("__SPELL__ s","m.goodsId = s.goods_Id",'inner')
            ->field('m.goodsId,m.goodsName,m.goodsImg,m.goodsSn,m.isRecom,m.saleNum,m.shopPrice,m.isSpec')
            ->limit(10)
            ->select();
        foreach($Result as $key=>$value){
            $Result[$key]['goodsImg']=Config::get('site_domain').'/'.$value['goodsImg'];
        }
        return SZXReturn($this->message[1],"1",$Result);
    }
    //店铺详情-点击商家信息
    public function shopData(){
        $shopId=input('post.shopId');
        //生鲜
        $where['u.isBrand']=3;
        $where['s.shopStatus'] = 1;
        $where['s.shopId']=$shopId;
        $data=Db::name('shops')->alias('s')
            ->join("__USERS__ u","s.userId = u.userId",'inner')
            ->join("__SHOP_INFO__ si","s.shopId = si.shopId",'inner')
            ->field('serviceStartTime,serviceEndTime,areaIdPath,areaId,shopAddress,shopTel,shopView')
            ->where($where)->find();
        //商家地址
        if($data['areaId']!=''){
            $areas = model('areas')->getParentNames($data['areaId']);
            $data['areaIdPath']=implode('',$areas);
        }
        //获取商家实景图
        if($data['shopView']!=''){
            $shopView = explode(',',$data['shopView']);
            foreach($shopView as $key=>$v){
                $shopView[$key]=Config::get('site_domain').'/'.$v;
            }
            $data['shopView']=$shopView;
        }else{
            $data['shopView'] = array('0'=>'');
        }
        return SZXReturn($this->message[1],"1",$data);
    }
    //商品详情
    public function detail(){
        $goodsId=input('post.goodsId/d');
        $where=[];
        //上架
        $where['g.isSale']=1;
        $where['g.dataFlag']=1;
        $where['g.goodsId']=$goodsId;
        $data=Db::name('goods')->alias('g')
            ->join("__SHOPS__ s","g.shopId = s.shopId",'inner')
            ->where($where)
            ->field('g.goodsId,g.goodsSn,g.goodsName,g.goodsImg,g.shopId,g.shopPrice,g.goodsDesc,g.gallery,g.saleNum,g.isSpell,s.freight')
            ->find();
        //地址
        if(!empty($data)){
            $data['goodsImg'] = Config::get('site_domain').'/'.$data['goodsImg'];
            $areaId=Db::name('shops')->where(['shopId'=>$data['shopId']])->value('areaId');
            $areas = model('areas')->getParentNames($areaId);
            unset($areas[0]);
            $data['areaList']=implode('',$areas);
            //商品相册
            if($data['gallery']!=''){
                $gallery = explode(',',$data['gallery']);
                foreach($gallery as $key=>$v){
                    $gallery[$key]=Config::get('site_domain').'/'.$v;
                }
                $data['gallery']=$gallery;
            }
            //获取商品描述-图片
            if($data['goodsDesc']!=''){
                $goodsDesc = explode(',',$data['goodsDesc']);
                foreach($goodsDesc as $key=>$v){
                    $goodsDesc[$key]=Config::get('site_domain').'/'.$v;
                }
                $data['goodsDesc']=$goodsDesc;
            }
        }
        return SZXReturn($this->message[1],"1",$data);
    }


}