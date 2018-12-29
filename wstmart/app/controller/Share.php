<?php
namespace wstmart\app\controller;
use think\Db;
use think\log;
use think\Config;
/*
 * 共享便利
 */
header('Access-Control-Allow-Origin:*');
class Share extends Base{
	//平台共享列表
//    public function index()
//    {
//      $list=Db::name('trusteeship')->alias('t')
//	                    ->join('szx_ad_positions p','t.trpositionId=p.positionId')
//	                    ->field('t.trId,t.expiryTime,t.price,p.positionName')
//	                    ->where(['t.shopId'=>'-1'])->order('trId', 'desc')->paginate(2)->toArray();
//	  $rs['code']=1;
//	  $rs['msg']=$this->message[1];
//	  $rs['data']=$list;
//	  return json($rs);
//    }
	//商家共享列表
//    public function businessindex(){
//	$where=[];
//	$where['shopId']=array('neq','-1');
//      $list=Db::name('trusteeship')->alias('t')
//	                    ->join('szx_ad_positions p','t.trpositionId=p.positionId')
//	                    ->field('t.trId,t.expiryTime,t.price,p.positionName')
//	                    ->where($where)->order('trId', 'desc')->paginate(2)->toArray();
//	  $rs['code']=1;
//	  $rs['msg']=$this->message[1];
//	  $rs['data']=$list;
//	  return json($rs);
//    }
	//共享购买
	public function buy(){
        //共享详情表 id
		$id=input('id');
		$tid=Db::name('trusteeship_share')->where(['id'=>$id])->value('tid');
		$userid=input('userid');
		$amount=input('amount');
		$data['tsid']=$id;
		$data['tid']=$tid;
		$data['userid']=$userid;
		$data['amount']=$amount;
		$data['createTime']=time();
		Db::name('trusteeship_buy')->insert($data);
		$rs['code']=1;
	    $rs['msg']=$this->message[1];
		return json($rs);
	}
	//我的共享广告位
	public function myshare(){
        $userid=input('post.userId');
        if(request()->isPost()){
            $data=Db::name('trusteeship_buy')->alias('tb')
                ->join('trusteeship t','t.trId=tb.tid')
                ->join('szx_ad_positions p','t.trpositionId=p.positionId')
                ->join('trusteeship_share ts','tb.tsId=ts.id')
                ->field('tb.id,ts.shareDate,p.positionName')
                ->where(['tb.userid'=>$userid])->select();
            if($data){
                return SZXReturn($this->message[1],"1",$data);
            }else{
                return SZXReturn($this->message[2],"2");
            }
        }else{
            return SZXReturn($this->message[10],"10");
        }

	}
	//广告 上传页
    public function uploadShow(){
        //共享购买表 id
        $id=input('post.id');
        $userid=input('post.userId');
        if(request()->isPost()){
            $data=Db::name('trusteeship_buy')->alias('tb')
                ->join('trusteeship t','t.trId=tb.tid')
                ->join('szx_ad_positions p','t.trpositionId=p.positionId')
                ->join('trusteeship_share ts','tb.tsId=ts.id')
                ->field('tb.id,ts.shareDate,p.positionName')
                ->where(['tb.userid'=>$userid,'tb.id'=>$id])->find();
            if($data){
                return SZXReturn($this->message[1],"1",$data);
            }else{
                return SZXReturn($this->message[2],"2");
            }
        }else{
            return SZXReturn($this->message[10],"10");
        }

    }
	//广告上传
	public function uploadAds(){
        //共享购买表 id
        $id=input('post.id');
        //用户id
        $userid=input('post.userId');
        //图片URL
        $image=input('post.image');
        $info=Db::name('trusteeship_buy')->alias('tb')
            ->join('trusteeship t','t.trId=tb.tid')
            ->where(['tb.userid'=>$userid,'tb.id'=>$id])->find();
//		$data = input('post.');
        $data['shopId']=$info['shopId'];
        $data['positionType']=2;
        $data['adName']='共享';
        $data['adFile']=$image;
        $data['adPositionId']=$info['trpositionId'];
		$data['createTime'] = date('Y-m-d H:i:s');
		Db::startTrans();
		try{
			Db::name('ads')->insert($data);
            Db::commit();
            return SZXReturn($this->message[1],"1",$data);
		}catch (\Exception $e) {
            Db::rollback();
            return SZXReturn($this->message[2],"2");
        }
	}
	//图片上传(单图上传)
    public function upload(){
		// 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('image');
        if($file){
            $info = $file->move(ROOT_PATH . 'upload' . DS . 'shareads');
            if($info){
                // 成功上传后 获取上传信息
                $rs['code']=1;
                $rs['msg']=$this->message[1];
                $rs['data']=str_replace(DS,'/', '/upload/shareads/'.$info->getSaveName());
//                $rs['data']=str_replace(DS,'/',$_SERVER['HTTP_HOST']. '/upload/shareads/'.$info->getSaveName());
                return json($rs);
            }else{
                // 上传失败获取错误信息
                $a=$file->getError();
                $rs['code']=2;
                $rs['msg']=$this->message[2];
                $rs['data']=$a;
                return json($rs);
            }
        }
    }
    //托管到期定时器
    public function isTrusteeshipDate(){
        $date=date('Y-m');
        $where['endDate']=['<',$date];
        $where['dataFlag']=['=',1];
        $data=Db::name('trusteeship')->where($where)->select();
        if(!empty($data)){
            foreach($data as $k=>$v){
                Db::name('trusteeship')->where(['trId'=>$data['trId']])->update(['dataFlag'=>-1]);
                $arr=Db::name('trusteeship_share')->where(['tid'=>$data['trId']])->select();
                if(!empty($arr)){
                    Db::name('trusteeship_share')->where(['tid'=>$data['trId']])->update(['dataFlag'=>-1]);
                }
            }
        }
    }

    //官方广告位 时间
	public function platformDate(){
        $time=date('Y-m');
        $where['ts.shareDate']=['>',$time];
        $where['t.shopId']=['eq',-1];
        $where['t.agentId']=['eq',-1];
        $where['t.type']=['eq',1];
        $data=Db::name('trusteeship')->alias('t')
            ->join('trusteeship_share ts','t.trId=ts.tid')
            ->field('ts.shareDate')
            ->where($where)->group('shareDate')
            ->select();
//        dump($data);die();
        if($data){
            return SZXReturn($this->message[1],"1",$data);
        }else{
            return SZXReturn($this->message[2],"2");
        }
    }
    //市场广告位  时间
    public function marketDate(){
        $time=date('Y-m');
        $where['ts.shareDate']=['>',$time];
        $where['t.shopId']=['neq',-1];
        $where['t.type']=['eq',1];
        $data=Db::name('trusteeship')->alias('t')
            ->join('trusteeship_share ts','t.trId=ts.tid')
            ->field('ts.shareDate')
            ->where($where)
            ->group('shareDate')
            ->select();
//        echo Db::name('trusteeship_share')->getLastSql();die();
//        dump($data);die();
        if($data !== false){
            return SZXReturn($this->message[1],"1",$data);
        }else{
            return SZXReturn($this->message[2],"2");
        }
    }
    //官方广告位 时间 搜索（平台共享列表）
    public function platformAdSearch(){
        $date=input('post.shareDate');
        $where=[];
        //没有条件 搜索全部
        if(!empty($date)) $where['ts.shareDate']=$date;
        $where['t.shopId']=['eq',-1];
        $where['t.agentId']=['eq',-1];
        $where['t.type']=['eq',1];
        if(request()->isPost()){
            $data=Db::name('trusteeship')->alias('t')
                ->join('trusteeship_share ts','t.trId=ts.tid')
                ->join('ad_positions p','t.trpositionId=p.positionId')
                ->field('p.positionName,t.price,ts.shareDate,ts.id')
                ->where($where)
                ->select();
//        dump($data);die();
            if($data){
                return SZXReturn($this->message[1],"1",$data);
            }else{
                return SZXReturn($this->message[2],"2");
            }
        }else{
            return SZXReturn($this->message[10],"10");
        }
    }
    //市场广告位 时间 搜索（市场共享列表）
    public function marketAdSearch(){
        $date=input('post.shareDate');
        $where=[];
        //没有条件 搜索全部
        if(!empty($date)) $where['ts.shareDate']=$date;
        $where['t.shopId']=['neq',-1];
        $where['t.type']=['eq',1];
        if(request()->isPost()){
            $data=Db::name('trusteeship')->alias('t')
                ->join('trusteeship_share ts','t.trId=ts.tid')
                ->join('ad_positions p','t.trpositionId=p.positionId')
                ->field('p.positionName,t.price,ts.shareDate,ts.id')
                ->where($where)
                ->select();
            if($data){
                return SZXReturn($this->message[1],"1",$data);
            }else{
                return SZXReturn($this->message[2],"2");
            }
        }else{
            return SZXReturn($this->message[10],"10");
        }
    }

}
