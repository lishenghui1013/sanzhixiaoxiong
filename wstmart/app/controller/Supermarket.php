<?php


namespace wstmart\app\controller;
use think\Db;
use think\Config;
use wstmart\home\model\Goods as M;

/*
 * 小熊超市控制器
 */
header('Access-Control-Allow-Origin:*');
class Supermarket extends Base{
    //超市首页
    public function index(){
        $data=[];
        //分类
        $data['type']=WSTGoodsCats();
        //首页广告
        $ads = Db::name('ads')->where(['adPositionId'=>37,'shopId'=>1])->field('	adFile')->select();
        foreach($ads as $k=>$v){
            $data['banner'][$k]['adFile']=Config::get('site_domain').'/'.$v['adFile'];
        }
        //自营小熊超市ID
        $where['m.shopId'] = 1;
        $where['m.isSpell'] = 1;
        $rs = Db::name('goods')->alias('m')
            ->where($where)
            ->join("__SPELL__ s","m.goodsId = s.goods_Id",'inner')
            ->field('m.goodsId,m.goodsName,m.goodsImg,m.goodsSn,m.isRecom,m.saleNum,m.shopPrice,m.isSpec,s.num,s.price')
            ->limit(10)
            ->select();
        foreach($rs as $key=>$value){
            $rs[$key]['goodsImg']=Config::get('site_domain').'/'.$value['goodsImg'];
        }
        //品牌团
        $data['goodstuan']=$rs;
        //优选商品
        $where1['m.shopId'] = 1;
        $where1['m.isSpell'] = 0;
        //推荐
        $where1['m.isRecom'] = 1;
        $Result = Db::name('goods')->alias('m')
            ->where($where1)
//            ->join("__SPELL__ s","m.goodsId = s.goods_Id",'inner')
            ->field('m.goodsId,m.goodsName,m.goodsImg,m.goodsSn,m.isRecom,m.saleNum,m.shopPrice,m.isSpec')
            ->limit(10)
            ->select();
        foreach($Result as $key=>$value){
            $Result[$key]['goodsImg']=Config::get('site_domain').'/'.$value['goodsImg'];
        }
        $data['you_xuan']=$Result;
        return SZXReturn($this->message[1],"1",$data);
    }


    //首页广告
    public function ads(){
        //首页广告
//        $ads = Db::name('ads')->where(['adPositionId'=>37,'shopId'=>1,'dataFlag'=>1])->field('	adFile')->select();

        $arr=Db::name('trusteeship')->where(['shopId'=>'1','trpositionId'=>37,'dataFlag'=>1])->find();
        $time=date('Y-m');
        $isTrusteeship = date('Y-m', strtotime($arr['createTime']));
        //判断是否 在托管期
        if(!empty($arr) && $time<=$arr['endDate'] && $time>$isTrusteeship){
            //首页广告
            $ads = Db::name('ads')
                ->where(['adPositionId'=>37,'dataFlag'=>1,'adName'=>'共享','shopId'=>1])
                ->field('	adFile,adId')
                ->select();
        }else{
            //首页广告
            $ads = Db::name('ads')
                ->where(['adPositionId'=>37,'dataFlag'=>1,'shopId'=>1])
                ->where("IFNULL(adName,'') != '共享'")
                ->field('	adFile,adId')
                ->select();
        }
        if($ads!=''){
            foreach($ads as $k=>$v){
                $ads[$k]['adFile']=Config::get('site_domain').'/'.$v['adFile'];
            }
        }
        return SZXReturn($this->message[1],"1",$ads);
    }

    //品牌团
    public function spellBrand(){
        //自营小熊超市ID
        $where['m.shopId'] = 1;
        $where['m.isSpell'] = 1;
        $rs = Db::name('goods')->alias('m')
            ->where($where)
            ->join("__SPELL__ s","m.goodsId = s.goods_Id",'inner')
            ->field('m.goodsId,m.goodsName,m.goodsImg,m.goodsSn,m.isRecom,m.saleNum,m.shopPrice,m.isSpec,s.num,s.price')
            ->limit(10)
            ->select();
        foreach($rs as $key=>$value){
            $rs[$key]['goodsImg']=Config::get('site_domain').'/'.$value['goodsImg'];
        }
        return SZXReturn($this->message[1],"1",$rs);
    }


    //单个商品-拼团列表X
    public function spellList(){
        $goodsId = input('post.goodId/d',0);
        //页码
        $page = intval(input('post.page',1));
        //每页显示条数
        $pagesize = Config::get('pagesize');
        $startRow = ($page-1)*$pagesize;

        //查询商品拼团数据
        $spell = Db::name('spell')->where(['goods_Id'=>$goodsId])->find();

        //查询所有已开团还未成团的团
        $teams = Db::name('spell_teams')
            ->where('goods_id='.$goodsId.' and status=0 and join_num>0')
            ->order('join_num DESC')
            ->limit($startRow,$pagesize)
            ->select();
//        print_r($teams);die;
        foreach ($teams as $k=>$v){
            $teamuser = Db::name('users')->where('userId='.$v['user_id'])->find();
            $teams[$k]['nickname'] = $teamuser['userName'];
            if (!$teamuser['userName'])$teams[$k]['nickname'] = $teamuser['loginName'];
            $teams[$k]['headimg'] = Config::get('site_domain').'/'.$teamuser['userPhoto'];
            $teams[$k]['nums'] = $spell['num']-$v['join_num'];
        }
        return SZXReturn($this->message[1],"1",$teams);

    }
    //根据商品id取全部评论
    public function allAppraises(){
        //商品id
        $goodsId=input('post.goodId');
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
        //头像路径
        if($list!=''){
            foreach ($list as $k=>$v){
                $list[$k]['userPhoto'] = Config::get('site_domain').'/'.$v['userPhoto'];
            }
        }
        return SZXReturn($this->message[1],"1",$list);
    }
    //商品列表
    public function saleByPage(){
//        $shopId = (int)input('post.shopId');
        //页码
        $page = intval(input('post.page',1));
        //每页显示条数
        $pagesize = Config::get('pagesize');
        $startRow = ($page-1)*$pagesize;

        $where = [];
        $where['shopId'] = 1;
        $where['goodsStatus'] = 1;
        $where['dataFlag'] = 1;
        $where['isSale'] = 1;
        //销量
        $orderBy=(input('post.shopId',0))==1?1:0;
        $orser=[];
        if($orderBy==1){
            $orser['saleNum']='desc';
        }else{
            $orser['saleTime']='desc';
        }
        //价格排序
        $order1=input('post.order');
//        $orser=[];
        if($order1==1){
            $orser['shopPrice']='desc';
        }elseif($order1=='0'){
            $orser['shopPrice']='asc';
        }
        //类别
        $catId = Input('cat/d');
        if(!empty($catId)){
            $goodsCatIds = model('GoodsCats')->getParentIs($catId);
            reset($goodsCatIds);
            if(!empty($goodsCatIds))$where['goodsCatIdPath'] = ['like',implode('_',$goodsCatIds).'_%'];
        }


        //普通商品
//        $isSpell=0;
//        $where['isSpell'] = $isSpell;
//        $goodsName = input('goodsName');
//        if($goodsName != ''){
//            $where['goodsName'] = ['like',"%$goodsName%"];
//        }
//        $shopId = (int)input('post.shopId');
//        $where['m.shopId'] = $shopId;
//                dump($orser);die();
//        if($isSpell>0){
//            $rs = Db::name('goods')->alias('m')
//                ->where($where)
//                ->join("__SPELL__ s","m.goodsId = s.goods_Id",'inner')
//                ->field('m.goodsId,m.goodsName,m.goodsImg,m.goodsSn,m.isRecom,m.saleNum,m.shopPrice,m.isSpec,s.num,s.price')
//                ->order($orser)
//                ->limit($startRow,$pagesize)
//                ->select();
////                ->paginate(input('pagesize/d'))->toArray();
//        }else{
            $rs = Db::name('goods')->alias('m')
                ->where($where)
                ->field('m.goodsId,m.goodsName,m.goodsImg,m.goodsSn,m.isRecom,m.saleNum,m.shopPrice,m.isSpec,m.isSpell')
                ->order($orser)
                ->limit($startRow,$pagesize)
                ->select();
//                ->paginate(input('pagesize/d'))->toArray();
//        }
//        dump($rs);die();

        foreach ($rs as $key => $v){
            $rs[$key]['goodsImg'] = Config::get('site_domain').'/'.$v['goodsImg'];
        }
        return SZXReturn($this->message[1],"1",$rs);
    }
    /**
     * 查看商品详情
     */
    public function detail(){
        $goodsId=input('post.goodsId/d');
        $where=[];
        //上架
        $where['isSale']=1;
        $where['dataFlag']=1;
        $where['goodsId']=$goodsId;
        $data=Db::name('goods')->where($where)->field('goodsId,goodsSn,goodsName,goodsImg,shopId,shopPrice,goodsDesc,gallery,saleNum,isSpell')->find();
        if(!empty($data)){
            $data['goodsImg'] = Config::get('site_domain').'/'.$data['goodsImg'];
            //商品相册
            if($data['gallery']!=''){
                $data['gallery'] = explode(',',$data['gallery']);
                foreach ($data['gallery'] as $key=>$value){
                    $data['gallery'][$key]=Config::get('site_domain').'/'.$value;
                }
            }
            //是否拼团
            if($data['isSpell']==1){
                $spell=Db::name('spell')->where(['goods_Id'=>$goodsId])->find();
                unset($spell['goods_Id'],$spell['id']);
                $data = array_merge($data,$spell);

                //查询所有已开团还未成团的团
                $teams = Db::name('spell_teams')
                    ->where('goods_id='.$goodsId.' and status=0 and join_num>0')
                    ->order('join_num DESC')
                    ->limit(2)
                    ->select();
                foreach ($teams as $k=>$v){
                    $teamuser = Db::name('users')->where('userId='.$v['user_id'])->find();
                    $teams[$k]['nickname'] = $teamuser['userName'];
                    if (!$teamuser['userName'])$teams[$k]['nickname'] = $teamuser['loginName'];
                    $teams[$k]['headimg'] = Config::get('site_domain').'/'.$teamuser['userPhoto'];
                    $teams[$k]['nums'] = $spell['num']-$v['join_num'];
                }
                $data['teamsdata']=$teams;
            }
            //评论
            $where=[];
            $where['g.goodsId'] = $goodsId;
            $list=Db::name('goods_appraises')->alias('g')
                ->where($where)
                ->join("__USERS__ u","g.userId = u.userId",'inner')
                ->field('g.*,u.userName,u.userPhoto')
//                ->limit(1)
                ->order('createTime DESC')
                ->find();
            if($list!=''){
                //头像路径
                foreach ($list as $k=>$v){
                    $list[$k]['userPhoto'] = Config::get('site_domain').'/'.$v['userPhoto'];
                }
                $data['appraises']=$list;
            }
//            dump($data);die();
        }
        return SZXReturn($this->message[1],"1",$data);
    }



}