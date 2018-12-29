<?php
namespace wstmart\app\controller;
use think\Db;
use think\log;
use think\Config;
/*
 * 股权众筹控制器
 */
header('Access-Control-Allow-Origin:*');
class Raise extends Base{
    //股权众筹
    public function ads(){
        $arr=Db::name('trusteeship')->where(['shopId'=>'-1','trpositionId'=>41,'dataFlag'=>1])->find();
        $time=date('Y-m');
        $isTrusteeship = date('Y-m', strtotime($arr['createTime']));
        //判断是否 在托管期
        if(!empty($arr) && $time<=$arr['endDate'] && $time>$isTrusteeship){
            //判断本月是否共享购买
//            $arr=Db::name('trusteeship_share')->where(['tid'=>$arr['trId'],'shareDate'=>$time,'isbuy'=>1])->find();
                //首页广告
                $ads = Db::name('ads')
                    ->where(['adPositionId'=>41,'dataFlag'=>1,'adName'=>'共享'])
                    ->field('	adFile,adId')
                    ->select();
        }else{
            //首页广告
            $ads = Db::name('ads')
                ->where(['adPositionId'=>41,'dataFlag'=>1])
                ->where("IFNULL(adName,'') != '共享'")
                ->field('	adFile,adId')
                ->select();
        }
        foreach($ads as $k=>$v){
            $ads[$k]['adFile']=Config::get('site_domain').'/'.$v['adFile'];
        }
//        dump($ads);die;
        return SZXReturn($this->message[1],"1",$ads);
    }
	//众筹列表页
    public function index(){
        //页码
        $page = input('post.page',1);
//        echo $page;die();
        //每页显示条数
        $pagesize = Config::get('pagesize');
        $startRow = ($page-1)*$pagesize;

        $list=Db::name('raise')->alias('r')->join('szx_shops s','r.shopid=s.shopId')
	                         ->field('r.id,r.title,r.total_money,r.already_money,s.shopName,s.areaId')
	                         ->order('id', 'desc')
            ->limit($startRow,$pagesize)
            ->select();
//            ->paginate(10)
//            ->toArray();
	  foreach($list as &$val){
		  $val['area']=implode('',model('Areas')->getParentNames($val['areaId']));
		  unset($val['areaId']);
	  }
	  $rs['code']=1;
	  $rs['msg']=$this->message[1];
	  $rs['data']=$list;
	  return json($rs);
    }
	//众筹详情页
    public function info()
    {
	  $id=input('id');
	  $info=Db::name('raise')->alias('r')->join('szx_shops s','r.shopid=s.shopId')
	                         ->join('szx_users u','s.userId=u.userId')
	                         ->field('r.id,r.title,r.total_money,r.already_money,r.already_number,r.agreement,r.rules,r.partner_distribution,r.partner_rules,r.issue,u.loginName,s.shopTel,s.areaId,s.shopAddress')
	                         ->where(['r.id'=>$id])->find();
	  $info['area']=implode('',model('Areas')->getParentNames($info['areaId']));
		  unset($info['areaId']);
	 
	  $rs['code']=1;
	  $rs['msg']=$this->message[1];
	  $rs['data']=$info;
	  return json($rs);
    }
	//购买众筹
	public function buy(){
		$id=input('id');
		$userid=input('userid');
		$buy=input('buy');
		$data['raiseid']=$id;
		$data['userid']=$userid;
		$data['buy_money']=$buy;
		$data['addtime']=time();
		Db::startTrans();
        try{
			Db::name('raise_buy')->insert($data);
			Db::name('raise')->where('id', $id)->setInc('already_number');
			Db::name('raise')->where('id', $id)->setInc('already_money',$buy);
			Db::commit();
            $rs['code']=1;
			$rs['msg']=$this->message[1];
			return json($rs);			
        } catch (\Exception $e) {
			// 回滚事务
			Db::rollback();
        }
	}
}
