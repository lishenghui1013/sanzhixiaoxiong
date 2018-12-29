<?php
namespace wstmart\admin\model;
use Think\Db;
use wstmart\admin\model\Shops as M;
/**
 * 企业业务处理
 */
class ShopApplys extends Base{
    /**
	 * 分页
	 */
	public function pageQuery(){
//		$page = Db::name('shop_applys')->alias('s')->join('__USERS__ u','s.userId=u.userId and u.dataFlag=1','left')
//			->where(['s.dataFlag'=>1])
//			->field('u.loginName,s.userId,s.shopId,s.linkman,s.phoneNo,applyStatus,s.createTime,applyDesc,applyId')
//			->order('s.applyId', 'desc')
//			->paginate(input('pagesize/d'))->toArray();
        $page = Db::name('company_auth')->where(['ca_status'=>1])->alias('c')->join('__USERS__ u','c.ca_users_id=u.userId and u.dataFlag=1','left')
//            ->where(['s.dataFlag'=>1])
            ->field('u.loginName,c.ca_company_name,c.ca_Identity_selection,c.ca_Main_type,c.ca_Main_type_subclass,c.ca_Auxiliary_type,c.ca_Auxiliary_type_subclass,c.ca_Business_license,c.ca_Open_an_account,c.ca_CreationTime,c.ca_attn,c.ca_id')
            ->order('c.ca_id', 'desc')
            ->paginate(input('pagesize/d'))
            ->toArray();
		return $page;
	}
	
	/**
	 * 获取信息
	 */
	public function getById($id){
//		return Db::name('shop_applys')->alias('s')->join('__USERS__ u','s.userId=u.userId and u.dataFlag=1 and s.dataFlag=1','left')
//			->where(['s.dataFlag'=>1,'s.applyId'=>$id])
//			->field('u.loginName,s.*')->find();
		return Db::name('company_auth')->alias('c')->join('__USERS__ u','c.ca_users_id=u.userId and u.dataFlag=1','left')
			->where(['c.ca_id'=>$id])
			->field('u.loginName,c.*')->find();
	}
	
	/**
	 * 删除菜单
	 */
	public function del(){
	    $id = input('post.id/d');
		$data = [];
		$data['ca_status'] = 2;
	    $result = Db::name('company_auth')->where(['ca_id'=>$id])->update($data);
        if(false !== $result){
        	return WSTReturn("删除成功", 1);
        }else{
        	return WSTReturn($this->getError(),-1);
        }
	}
	
	/**
	 * 处理申请
	 */
	public function handle(){
//	    dump(input('post.'));die();
        $m = new M();
		$id = input('post.applyId/d');
		$usersId = input('post.usersId/d');
		$isBrand = input('post.isBrand/d');
		$data = [];
		$data['ca_attn'] = input('post.ca_attn/d');
		$data['ca_handleDesc'] = input('post.ca_handleDesc');
//        $auth_status=Db::name('users')->where('userId',$usersId)->value('userType');
//        dump($auth_status);die();
		if(!in_array($data['ca_attn'],array(2,3)))return WSTReturn("无效的处理状态", -1);
        if($data['ca_attn']==3 && $data['ca_handleDesc']=='')return WSTReturn("请输入申请失败原因", -1);
        //启动事务
        Db::startTrans();
        try{
            if($data['ca_attn']==2){
                unset($data['ca_handleDesc']);
//                if($auth_status==1){
//                    Db::name('users')->where('userId',$usersId)->update(['auth_status' => 3]);
//                }else{
                    $data1['shopSn'] = $m->getShopSn('S');
                    $data1['userId'] = $usersId;
                    $data1['createTime'] = date('Y-m-d H:i:s');
                    $data1['latitude'] = input('post.latitude');
                    $data1['longitude'] = input('post.longitude');
                    $data1['mapLevel'] = input('post.mapLevel');
                    $pickupTime=input('post.pickupTime');
                    if (!empty($pickupTime)) $data1['pickupTime'] = input('post.pickupTime');
                   Db::name('users')->where('userId',$usersId)->update([
                        'isBrand' => $isBrand,
                        'userType' => 1
                    ]);
                $shopId=Db::name('shops')->insertGetId($data1);
                $catId=input('post.goods_cats/d');
                Db::name('cat_shops')->insert(['shopId'=>$shopId,'catId'=>$catId]);
//                }
            }
            Db::name('company_auth')->where(['ca_id'=>$id])->update($data);
            //提交事务
            Db::commit();
            return WSTReturn("审核成功", 1);
        }catch (\Exception $e) {
            //回滚事务
            Db::rollback();
            return WSTReturn('审核失败',-1);
        }
        return WSTReturn($this->getError(),-1);
	}
	
	/**
	 * 检测该开店申请是否开店
	 */
	public function checkOpenShop($id){
		return Db::name('shop_applys')->alias('s')
			->where(['s.dataFlag'=>1,'applyId'=>$id])
			->field('s.userId,s.shopId')
			->find();
	}
	/**
	 * 修改开店状态
	 */
	public function editApplyOpenStatus($id,$shopId){
		$this->where(['applyId'=>$id,'shopId'=>0])->update(['shopId'=>$shopId]);
	}
}
