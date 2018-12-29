<?php
namespace wstmart\home\controller;
use think\Db;
use think\log;
/**
 * ============================================================================
 * 股权众筹
 */
class Raise extends Base{
	/**
	* 新增众筹
	*/
    public function addraise(){
        return $this->fetch('shops/raise/edit');
    }
	/* 
    *众筹列表
	*/
	public function listraise(){
		return $this->fetch('shops/raise/list');
	}
	/**
	 * 获取众筹列表
	 */
	public function getraise(){
		$key = input("param.key");
		$where=array();
		$where['title']=array('like',"%$key%");
		$where['dataflag']=1;
		$rs =Db::name("raise")->where($where)->paginate(20)->toArray();
		return json_encode($rs);
	}
	/**
	 * 删除商品
	 */
	public function del(){
	    $id = 4;
		$data = [];
		$data['dataflag'] = 2;
		Db::startTrans();
		try{
		    	$rs =Db::name("raise")->where(['id'=>$id])->setField('dataflag',2);    
		Db::commit();
	        	//标记删除购物车
	        	return WSTReturn("删除成功", 1);
		}catch (\Exception $e) {
            Db::rollback();
        }
        return WSTReturn('删除失败',-1);
	}
	/**
	  * 批量删除商品
	  */
	 public function batchDel(){
	 	$shopId = (int)session('WST_USER.shopId');
	   	$ids = input('post.ids/a');
	   	Db::startTrans();
		try{
		   	$rs =Db::name("raise")->where(['id'=>['in',$ids],'shopid'=>$shopId])->setField('dataflag',2);
				Db::commit();
	        	return WSTReturn("删除成功", 1);
	        
		}catch (\Exception $e) {
            Db::rollback();
        }
        return WSTReturn('删除失败',-1);
	 }
	 /**
	* 修改商品状态
	*/
	public function changStatus(){
		$shopId = (int)session('WST_USER.shopId');
		$is = input('post.is');
		$status = (input('post.status',1)==1)?0:1;
		$id = (int)input('post.id');
		$rs = Db::name("raise")->where(["shopId"=>$shopId,'id'=>$id])->setField($is,$status);
		if($rs!==false){
			return WSTReturn('设置成功',1);
		}else{
			return WSTReturn($this->getError(),-1);
		}
	}
	/**
	 * 批量修改商品状态
	 */
	public function changeStatus(){
		$shopId = (int)session('WST_USER.shopId');	
		/* $is = input('post.is');
		//设置哪一个状态
		$status = input('post.status',1); */
		$ids = input('post.ids/a');
		$rs = Db::name("raise")->where(['id'=>['in',$ids],'shopid'=>$shopId])->setField('status', 2);
		if($rs!==false){
			return WSTReturn('设置成功',1);
		}else{
			return WSTReturn($this->getError(),-1);
		}

	}
	 //新增
	 public function toAdd(){
		 //Log::record('测试日志信息','log');die;
		$shopId = (int)session('WST_USER.shopId');
		$userId = (int)session('WST_USER.userId');
		$data =input('post.');
		//print_r($data);
		$data['shopid']=$shopId;
		$data['userid']=$userId;
		$data['addtime']=time();
		Db::startTrans();
        try{
        	//$shop = model('shops')->get($shopId);
        	//if($shop['dataFlag']==-1 || $shop['shopStatus']!=1)$data['status'] = 2;
			$result = Db::name("raise")->insert($data);
			if($result){
    	        Db::commit();
				return WSTReturn("新增成功", 1);
			}else{
				return WSTReturn($this->getError(),-1);
			}
        }catch (\Exception $e) {
            Db::rollback();
            return WSTReturn('新增失败'.$e,-1);
        }
	 }
	 //众筹详情
	 public function detail(){
		 $id=input('id');
		 $result = Db::name("raise_buy")->alias('b')
		                            ->join('raise r','b.raiseid=r.id')->where('raiseid='.$id)->select();
		 $this->assign("result",$result);
		 return $this->fetch('shops/raise/detail');
	 }
}
