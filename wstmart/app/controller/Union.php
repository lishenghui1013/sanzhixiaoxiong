<?php


namespace wstmart\app\controller;
use think\Db;
use think\Config;
use wstmart\home\model\Shops;
/*
 * 小熊联盟控制器
 */
header('Access-Control-Allow-Origin:*');
class Union extends Base {
    //首页分类
    public function type(){
        //首页分类分类
//        $data=WSTGoodsCats();
        $data= Db::name('goods_cats')->where(['dataFlag'=>1,'parentId'=>0])->order('catSort asc,catId desc')->field("catName,catId,catImg")->limit(0,7)->select();
        $data[7]= Db::name('goods_cats')->where(['dataFlag'=>1,'catId'=>367])->field("catName,catId,catImg")->find();
        foreach($data as $k=>$v){
            if($v['catImg']!='')$data[$k]['catImg']=Config::get('site_domain').'/'.$v['catImg'];
        }
        return SZXReturn($this->message[1],"1",$data);
    }
    //全部分类
    public function allType(){
        $data= Db::name('goods_cats')->where(['dataFlag'=>1,'parentId'=>input('catId/d',0)])->order('catSort asc,catId desc')->field("catName,catId,catImg")->limit(0,20)->select();
        foreach($data as $k=>$v){
            if($v['catImg']!='')$data[$k]['catImg']=Config::get('site_domain').'/'.$v['catImg'];
        }
        return SZXReturn($this->message[1],"1",$data);
    }
    //小熊联盟-首页广告
    public function ads(){
        //首页广告
//        $ads = Db::name('ads')->where(['adPositionId'=>38])->field('	adFile')->select();
        $arr=Db::name('trusteeship')->where(['shopId'=>'-1','trpositionId'=>38,'dataFlag'=>1])->find();
        $time=date('Y-m');
        $isTrusteeship = date('Y-m', strtotime($arr['createTime']));
        //判断是否 在托管期
        if(!empty($arr) && $time<=$arr['endDate'] && $time>$isTrusteeship){
            //首页广告
            $ads = Db::name('ads')
                ->where(['adPositionId'=>38,'dataFlag'=>1,'adName'=>'共享'])
                ->field('	adFile,adId')
                ->select();
        }else{
            //首页广告
            $ads = Db::name('ads')
                ->where(['adPositionId'=>38,'dataFlag'=>1])
                ->where("IFNULL(adName,'') != '共享'")
                ->field('	adFile,adId')
                ->select();
        }
        foreach($ads as $k=>$v){
            $ads[$k]['adFile']=Config::get('site_domain').'/'.$v['adFile'];
        }
        return SZXReturn($this->message[1],"1",$ads);
    }
    //小熊联盟-天天特价
    public function teJia(){
        //天天特价
        $where1['g.isSpell'] = 0;
        $where1['s.shopStatus'] = 1;
        $where1['s.dataFlag'] = 1;
        $where1['u.isBrand'] = 2;
        $where1['u.dataFlag'] = 1;
        $Result = Db::name('goods')->alias('g')
            ->where($where1)
            ->join("__SHOPS__ s","g.shopId = s.shopId",'inner')
            ->join("__USERS__ u","s.userId = u.userId",'inner')
            ->field('g.goodsId,g.goodsName,g.goodsImg,g.goodsSn,g.isRecom,g.saleNum,g.shopPrice,g.isSpec,u.userId')
            ->limit(10)
            ->select();
        foreach($Result as $key=>$value){
            $Result[$key]['goodsImg']=Config::get('site_domain').'/'.$value['goodsImg'];
        }
        return SZXReturn($this->message[1],"1",$Result);
    }
    //好友推荐
    public function tuiJian(){
        $where2['g.isSpell'] = 0;
        $where2['s.shopStatus'] = 1;
        $where2['s.dataFlag'] = 1;
        $where2['u.isBrand'] = 2;
        $where2['u.dataFlag'] = 1;
        //推荐
        $where2['g.isRecom'] = 1;
        $re = Db::name('goods')->alias('g')
            ->where($where2)
            ->join("__SHOPS__ s","g.shopId = s.shopId",'inner')
            ->join("__USERS__ u","s.userId = u.userId",'inner')
            ->field('g.goodsId,g.goodsName,g.goodsImg,g.goodsSn,g.isRecom,g.saleNum,g.shopPrice,g.isSpec,u.userId')
            ->limit(10)
            ->select();
        foreach($re as $key=>$value){
            $re[$key]['goodsImg']=Config::get('site_domain').'/'.$value['goodsImg'];
        }
        return SZXReturn($this->message[1],"1",$re);
    }
    //热销店铺
    public function sellsShop(){
        $where=[];
        $where['u.isBrand']=2;
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
    //分类搜索-联盟 店铺列表
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
        //非品牌
        $where['u.isBrand']=2;
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
    //店铺详情-头像、店名、简介
    public function shopInfo(){
        $shopId=input('post.shopId');
        //非品牌
        $where['u.isBrand']=2;
        $where['s.shopStatus'] = 1;
        $where['s.shopId']=$shopId;
        $data=Db::name('shops')->alias('s')
            ->join("__USERS__ u","s.userId = u.userId",'inner')
            ->join("__SHOP_INFO__ si","s.shopId = si.shopId",'inner')
            ->field('s.shopId,s.shopImg,s.shopName,si.shopDesc')
            ->where($where)->find();
        if($data['shopImg']!='')$data['shopImg']=Config::get('site_domain').'/'.$data['shopImg'];
        return SZXReturn($this->message[1],"1",$data);
    }
    //店铺详情-广告
    public function shopAds(){
        $shopId=input('post.shopId');
        //首页广告
//        $ads = Db::name('ads')->where(['adPositionId'=>37,'shopId'=>$shopId])->field('	adFile')->select();

        $arr=Db::name('trusteeship')->where(['shopId'=>$shopId,'trpositionId'=>37,'dataFlag'=>1])->find();
        $time=date('Y-m');
        $isTrusteeship = date('Y-m', strtotime($arr['createTime']));
        //判断是否 在托管期
        if(!empty($arr) && $time<=$arr['endDate'] && $time>$isTrusteeship){
            //首页广告
            $ads = Db::name('ads')
                ->where(['adPositionId'=>37,'dataFlag'=>1,'adName'=>'共享','shopId'=>$shopId])
                ->field('	adFile,adId')
                ->select();
        }else{
            //首页广告
            $ads = Db::name('ads')
                ->where(['adPositionId'=>37,'dataFlag'=>1,'shopId'=>$shopId])
                ->where("IFNULL(adName,'') != '共享'")
                ->field('	adFile,adId')
                ->select();
        }
        if($ads != ''){
            foreach($ads as $k=>$v){
                $ads[$k]['adFile']=Config::get('site_domain').'/'.$v['adFile'];
            }
        }
        return SZXReturn($this->message[1],"1",$ads);
    }
    //店铺详情-商品信息
    public function goodsInfo(){
        $shopId=input('post.shopId');
        $where['m.shopId'] = $shopId;
//        $where['m.isSpell'] = 0;
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
        //非品牌
        $where['u.isBrand']=2;
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
        }
        return SZXReturn($this->message[1],"1",$data);
    }
    //商品详情
    public function detail(){
//        $shops = new Shops();
//        $shFreight =  $shops->getShopsFreight(2);//默认运费
//        echo $shFreight['freight'];die();

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
            }else{
                $data['gallery']=array('0'=>'');
            }
            //获取商品描述-图片
            if($data['goodsDesc']!=''){
                $goodsDesc = explode(',',$data['goodsDesc']);
                foreach($goodsDesc as $key=>$v){
                    $goodsDesc[$key]=Config::get('site_domain').'/'.$v;
                }
                $data['goodsDesc']=$goodsDesc;
            }else{
                $data['goodsDesc']=array('0'=>'');
            }
        }
        return SZXReturn($this->message[1],"1",$data);
    }
    //拼团列表
    public function spellList(){
        $goodsId = input('post.goodsId/d',0);
        //页码
        $page = input('post.page',1);
//        echo $page;die();
        //每页显示条数
        $pagesize = Config::get('pagesize');
        $startRow = ($page-1)*$pagesize;

        //上架
        $where['isSale']=1;
        $where['dataFlag']=1;
        $where['goodsId']=$goodsId;
        $data=Db::name('goods')->where($where)->find();
//        $teams=[];
        if(!empty($data)){
            //查询商品拼团数据
            $spell = Db::name('spell')->where(['goods_Id'=>$goodsId])->find();
//            dump($spell);die();
//            $where1['']=[];
            //查询所有已开团还未成团的团
            $teams = Db::name('spell_teams')->alias('s')
                ->where('goods_id='.$goodsId.' and status=0 and join_num>0 and isPay=1')
                ->join('__SPELL_ORDERS__ o','s.id = o.team_id and s.user_id=o.userId')
                ->order('join_num DESC')
                ->limit($startRow,$pagesize)
                ->select();
            foreach ($teams as $k=>$v){
                $teamuser = Db::name('users')->where('userId='.$v['user_id'])->find();
                $teams[$k]['userName'] = $teamuser['userName'];
                if (!$teamuser['userName'])$teams[$k]['userName'] = $teamuser['loginName'];
                //设置默认头像
                if(empty($teamuser['userPhoto'])){
                    $userLogo=Db::name("sys_configs")->where(['configId'=>53,'fieldCode'=>'userLogo'])->value('fieldValue');
                    $teams[$k]['userPhoto']=Config::get('site_domain').'/'.$userLogo;
                }else{
                    $teams[$k]['userPhoto']=Config::get('site_domain').'/'. $teamuser['userPhoto'];
                }
//                $teams[$k]['userPhoto'] = Config::get('site_domain').'/'.$teamuser['userPhoto'];
                $teams[$k]['nums'] = $spell['num']-$v['join_num'];
            }
        }
//        $if($teams);
        return SZXReturn($this->message[1],"1",$teams);
    }
    //根据商品id取全部评论
    public function allAppraises(){
        //商品id
        $goodsId=input('post.goodsId');
        //页码
        $page = intval(input('post.page',1));
        //每页显示条数
        $pagesize = Config::get('pagesize');
        $startRow = ($page-1)*$pagesize;

        $where['g.goodsId'] = $goodsId;
        $list=Db::name('goods_appraises')->alias('g')
            ->where($where)
            ->join("__USERS__ u","g.userId = u.userId",'inner')
            ->field('g.*,u.userName,u.userPhoto')
            ->limit($startRow,$pagesize)
            ->select();
//        dump($list);die();
        //头像路径
        if($list!=''){
            foreach ($list as $k=>$v){
                if(!empty($v['userPhoto'])){
                    $list[$k]['userPhoto'] = Config::get('site_domain').'/'.$v['userPhoto'];
                }else{
                    $userLogo=Db::name("sys_configs")->where(['configId'=>53,'fieldCode'=>'userLogo'])->value('fieldValue');
                    $list[$k]['userPhoto']=Config::get('site_domain').'/'.$userLogo;
                }
            }
        }
        return SZXReturn($this->message[1],"1",$list);
    }

}