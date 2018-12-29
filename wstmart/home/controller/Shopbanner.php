<?php

namespace wstmart\home\controller;
use think\Db;
use wstmart\home\model\Goods as M;
/*
 * 商家广告管理
 *
 */
class Shopbanner extends Base{
    //广告列表
    public function index(){
        $where['shopId'] = ["=",(int)session('WST_USER.shopId')];
        $where['dataFlag'] = ["=",1];
//        $where['adName'] = ['neq','共享'];
//        $where['adName'] = array('neq','共享');
        $teamsData = Db::name("ads")
            ->where($where)
            ->where("IFNULL(adName,'') != '共享'")
            ->select();
//        echo DB::name('ads')->getlastsql();die();
        $tuoguan=session('tuoguan');
        $this->assign('tuoguan',$tuoguan);
        $this->assign('teamsData',$teamsData);
        return $this->fetch('shops/shopbanner/list_banner');
    }
    //跳到编辑
    public function Edit(){
        $where['shopId'] = ["=",(int)session('WST_USER.shopId')];
        $where['dataFlag'] = ["=",1];
        $where['adId'] = ["=",(int)input('get.id')];
        $object = Db::name("ads")
            ->where($where)
            ->find();
        $type=Db::name('ad_positions')->where(['positionType'=>2])->select();
        $this->assign('type',$type);
        $this->assign('object',$object);
        return $this->fetch('shops/shopbanner/edit');
    }
    //跳到添加页
    public function addBanner(){
        $m = new M();
        $object = $m->getEModel('ads');
        $object['adFile'] = WSTConf('CONF.goodsLogo');
        $type=Db::name('ad_positions')->where(['positionType'=>2])->select();
        $this->assign('type',$type);
        $this->assign('object',$object);
        $tuoguan=session('tuoguan');
        $this->assign('tuoguan',$tuoguan);
        return $this->fetch('shops/shopbanner/edit');
    }
    //添加
    public function addToBanner(){
        $data=input('post.');
        $data['shopId']=(int)session('WST_USER.shopId');
        $data['createTime']=date('Y-m-d H:i:s');
        $data['positionType']=2;
        if($data['adName']=='共享'){
            return WSTReturn("此标题不可添加", -1);
        }
        //默认是商家首页 （待后续扩展）
        $data['adPositionId']=37;
        $result=Db::name('ads')->insert($data);
        if($result){
            return WSTReturn("新增成功", 1);
        }else{
            return WSTReturn("新增失败", -1);
        }
    }
    //编辑
    public function bannerToEdit(){
        $data=input('post.');
        if($data['adName']=='共享'){
            return WSTReturn("此标题不可添加", -1);
        }
        $where['shopId'] = ["=",(int)session('WST_USER.shopId')];
        $where['adId'] = ["=",(int)$data['adId']];
        $data['updateTime']=date('Y-m-d H:i:s');
        $result=Db::name('ads')->where($where)->update($data);
        if($result !== false){
            return WSTReturn("修改成功", 1);
        }else{
            return WSTReturn("修改失败", -1);
        }
    }
    //删除
    public function del(){
//        $where['adId'] = ["=",(int)input('post.id/d')];
        $data['updateTime']=date('Y-m-d H:i:s');
        $data['dataFlag']= -1;
        $data['adId']= (int)input('post.id/d');
        $result=Db::name('ads')
//            ->where($where)
            ->update($data);
        if($result !== false){
            return WSTReturn("删除成功", 1);
        }else{
            return WSTReturn("删除失败", -1);
        }
    }
    //商家 托管广告
    public function Trusteeship(){
//        dump(input('post.'));die();
        //托管月数
        $expiryTime=input('post.monthNum');
        if(empty($expiryTime))return WSTReturn("请选择托管期限", -1);
        $shopId = (int)session('WST_USER.shopId');
        $address=Db::name('shops')->where(['shopId'=>$shopId])->field('areaIdPath,areaId')->find();
        if(empty($address['areaIdPath']) || empty($address['areaId'])){
            return WSTReturn("请选择地址", -2);
        }
        //判断是否托管
        $isTrusteeship=Db::name('trusteeship')->where(['shopId'=>$shopId,'trpositionId'=>37,'dataFlag'=>1])->order('trId desc')->value('endDate');
//        echo date('Y-m', strtotime($isTrusteeship));die;
        if(!empty($isTrusteeship)){
            $time=date('Y-m');
            $isTrusteeship = date('Y-m', strtotime($isTrusteeship));
            if($time<=$isTrusteeship)return WSTReturn("您的托管还未到期！", -1);
        }
//        echo 111;die();
        //获取 省 市 区
        $areas = model('Areas')->getParentIs($address['areaId']);
        $data=[];
        if(!empty($areas)){
            //查区代理
            $areaAgent=Db::name('staffs')->where(['area'=>$areas['0'],'staffRoleId'=>10])->field('staffId')->find();
            if(empty($areaAgent)){
                //查市代理
                $cityAgent=Db::name('staffs')->where(['area'=>$areas['1'],'staffRoleId'=>9])->field('staffId')->find();
                if(empty($cityAgent)){
                    //查省代理
                    $proAgent=Db::name('staffs')->where(['area'=>$areas['2'],'staffRoleId'=>8])->field('staffId')->find();
                    if(!empty($proAgent)){
                        //代理id
                        $data['agentId']=$proAgent['staffId'];
                    }else{
                        //没有三级代理 托管给平台 -1
                        $data['agentId']=-1;
                    }
                }else{
                    //代理id
                    $data['agentId']=$cityAgent['staffId'];
                }
            }else{
                //代理id
                $data['agentId']=$areaAgent['staffId'];
            }
        }
        $base = strtotime(date('Y-m',time()).'-01 00:00:01');
        $data=[
            'shopId'=>$shopId,
            'agentId'=>$data['agentId'],
            'trpositionId'=>37,
//            'expiryTime'=>$expiryTime,
            'monthNum'=>$expiryTime,

            'endDate'=>date("Y-m",strtotime("+$expiryTime month",$base)),
            'createTime'=>date('Y-m-d H:i:s'),
            'areapath'=>$address['areaIdPath'],
            'area'=>$address['areaId'],
        ];
//        dump($data);die();
        $result=Db::name('trusteeship')->insert($data);
        if($result){
            return WSTReturn("托管成功", 1);
        }else{
            return WSTReturn("托管失败", -1);
        }
    }
}