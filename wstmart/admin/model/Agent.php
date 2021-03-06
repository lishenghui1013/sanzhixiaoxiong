<?php
namespace wstmart\admin\model;
use wstmart\admin\model\Roles;
use wstmart\admin\model\LogStaffLogins;
use think\Db;
/**
 * 职员业务处理
 */
class Agent extends Base{
	protected $table = 'szx_staffs';
	/**
	 * 判断用户登录帐号密码
	 */
	public function checkLogin(){
		$loginName = input("post.loginName");
		$loginPwd = input("post.loginPwd");
		$code = input("post.verifyCode");
		if(!WSTVerifyCheck($code)){
			return WSTReturn('验证码错误!');
		}
		$staff = $this->where(['loginName'=>$loginName,'staffStatus'=>1,'dataFlag'=>1])->find();
		if(empty($staff))return WSTReturn('账号或密码错误!');
		if($staff['loginPwd']==md5($loginPwd.$staff['secretKey'])){
	 		$staff->lastTime = date('Y-m-d H:i:s');
	 		$staff->lastIP = request()->ip();
	 		$staff->save();
	 		//记录登录日志
		 	LogStaffLogins::create([
		 	     'staffId'=>$staff['staffId'],
		 	     'loginTime'=> date('Y-m-d H:i:s'),
		 	     'loginIp'=>request()->ip()
		 	]);
	 		//获取角色权限
	 		$role = Roles::get(['dataFlag'=>1,'roleId'=>$staff['staffRoleId']]);
	 		$staff['roleName'] = $role['roleName'];
	 		if($staff['staffId']==1){
	 			$staff['privileges'] = Db::name('privileges')->where(['dataFlag'=>1])->column('privilegeCode');
	 			$staff['menuIds'] = Db::name('menus')->where('dataFlag',1)->column('menuId');
	 		}else{
		 		$staff['privileges'] = explode(',',$role['privileges']);
		 		$staff['menuIds'] = [];
		 		//获取管理员拥有的菜单
		 		if(!empty($staff['privileges'])){
		 		     $menus = Db::name('menus')->alias('m')->join('__PRIVILEGES__ p','m.menuId=p.menuId and p.dataFlag=1','inner')
		 		                ->where(['p.privilegeCode'=>['in',$staff['privileges']]])->field('m.menuId')->select();
		 		     $menuIds = [];
		 		     if(!empty($menus)){
		 		     	foreach ($menus as $key => $v){
		 		     		$menuIds[] = $v['menuId'];
		 		     	}
		 		     	$staff['menuIds'] = $menuIds;
		 		     }
		 		}
	 		}
	 		session("WST_STAFF",$staff);

			return WSTReturn("",1,$staff);
		}
		return WSTReturn('账号或密码错误!');
	}
	
    /**
	 * 代理分页
	 */
	public function pageQuery(){
		$key = input('get.key');
		$staffRoleId=session('WST_STAFF.staffRoleId');
		if($staffRoleId==8){
			$where['pro']=session('WST_STAFF.pro');
			$where['s.staffRoleId']=9;
		}elseif($staffRoleId==9){
			$where['area']=session('WST_STAFF.area');
			$where['s.staffRoleId']=10;
		}else{
			$where['s.staffRoleId']=['in','8,9,10'];
		}
		$where['s.dataFlag'] = 1;
		if($key!='')$where['loginName|staffName|staffNo'] = ['like','%'.$key.'%'];
		return Db::name('staffs')->alias('s')->join('__ROLES__ r','s.staffRoleId=r.roleId and r.dataFlag=1','left')
		       ->where($where)->field('staffId,staffName,loginName,workStatus,staffNo,lastTime,lastIP,roleName')
		       ->order('staffId', 'desc')->paginate(input('pagesize/d'));
	}
	/**
	 * 广告位置分页
	 */
	public function adspageQuery(){
//		$key = input('get.key');
//        职员id （代理id）
		$staffId=session('WST_STAFF.staffId');
//        职员角色ID 8 9 10 ,省 市 区
        $staffRoleId=session('WST_STAFF.staffRoleId');

        $where['t.dataFlag'] = 1;
        //平台登录
		if($staffId==1){
            $where['t.agentId'] = -1;
            //代理登录
        }elseif ($staffRoleId==8||$staffRoleId==9||$staffRoleId==10) {
//            if($staffRoleId==8||$staffRoleId==9||$staffRoleId==10){
                $where['t.agentId'] = $staffId;
//            }
        }else{
            return null;die();
        }

		//if($key!='')$where['loginName|staffName|staffNo'] = ['like','%'.$key.'%'];
		return Db::name('trusteeship')->alias('t')
		       ->join('ad_positions p','t.trpositionId=p.positionId and p.dataFlag=1')
		       ->where($where)->field('t.*,p.*')
		       ->order('trId', 'desc')
			   ->paginate(input('pagesize/d'));
	}
	/**
	 * 删除
	 */
    public function del(){
    	$staffId = session('WST_STAFF.staffId');
	    $id = input('post.id/d');
	    if($staffId==$id)return WSTReturn('不允许删除自己',-1);
	    if($id==1)return WSTReturn('不允许删除最高管理员',-1);
		$data = [];
		$data['dataFlag'] = -1;
		Db::startTrans();
		try{
		    $result = $this->update($data,['staffId'=>$id]);
	        if(false !== $result){
	        	WSTUnuseImage('staffs','staffPhoto',$id);
	        	Db::commit();
	        	return WSTReturn("删除成功", 1);
	        }
		}catch (\Exception $e) {
            Db::rollback();
            return WSTReturn('删除失败',-1);
        }
	}
	
	/**
	 * 获取角色权限
	 */
	public function getById($id){
		return $this->get(['dataFlag'=>1,'staffId'=>$id]);
	}
	
	/**
	 * 新增
	 */
	public function add(){
		$data = input('post.');
		//return $data;die;
		$data['secretKey'] = rand(1000,9999);
		$data["loginPwd"] = md5(input("post.loginPwd").$data["secretKey"]);
		$data["staffFlag"] = 1;
		$data["createTime"] = date('Y-m-d H:i:s');
		
		$areaId1 = isset($data['areaId1'])?$data['areaId1']:'';
		$areaId2 = isset($data["areaId1_".$areaId1])?$data["areaId1_".$areaId1]:'';		
		$areaId3 = isset($data["areaId1_".$areaId1."_".$areaId2])?$data["areaId1_".$areaId1."_".$areaId2]:'';
		
		$data['pro']=$areaId1?$areaId1:'';
		$data['city']=$areaId2?$areaId2:'';
		$data['area']=$areaId3?$areaId3:'';
		//return $data;die;
		Db::startTrans();
		try{
		   $result = $this->validate('Staffs.add')->allowField(true)->save($data);
		   if(false !== $result){
		      WSTUseImages(1, $this->staffId,$data['staffPhoto']);
		      Db::commit();
              return WSTReturn("新增成功", 1);
		   }
		}catch (\Exception $e) {
            Db::rollback();
            return WSTReturn('新增失败',-1);
        }
	}
    /**
	 * 编辑
	 */
	public function edit(){
		$id = input('post.staffId/d');
		$data = input('post.');
		WSTUnset($data, 'staffId,loginPwd,secretKey,dataFlag,createTime,lastTime,lastIP');
		Db::startTrans();
		try{
			WSTUseImages(1, $id,$data['staffPhoto'],'staffs','staffPhoto');
		    $result = $table->validate('Staffs.edit')->allowField(true)->save($data,['staffId'=>$id]);
	        if(false !== $result){
		        Db::commit();
	        	return WSTReturn("编辑成功", 1);
	        }
		}catch (\Exception $e) {
            Db::rollback();
            return WSTReturn('编辑失败',-1);
        }
	}
	
	/**
	 * 检测账号是否重复
	 */
	public function checkLoginKey($key){
		$rs = $this->where(['loginName'=>$key,'dataFlag'=>1])->count();
		return ($rs==0)?WSTReturn('该账号可用', 1):WSTReturn("对不起，该账号已存在");
	}
	/**
	 * 修改自己密码
	 */
	public function editMyPass($staffId){
		if(input("post.newPass")=='')WSTReturn("密码不能为空");
		$staff = $this->where('staffId',$staffId)->field('secretKey,loginPwd')->find();
		if(empty($staff))return WSTReturn("修改失败");
		$srcPass = md5(input("post.srcPass").$staff["secretKey"]);
		if($srcPass!=$staff['loginPwd'])return WSTReturn("原密码错误");
		$staff->loginPwd = md5(input("post.newPass").$staff["secretKey"]);
		$result = $staff->save();
        if(false !== $result){
        	return WSTReturn("修改成功", 1);
        }else{
        	return WSTReturn($this->getError(),-1);
        }
	}
   /**
	 * 修改职员密码
	 */
	public function editPass($staffId){
		if(input("post.newPass")=='')WSTReturn("密码不能为空");
		$staff = $this->where('staffId',$staffId)->field('secretKey')->find();
		if(empty($staff))return WSTReturn("修改失败");
		$staff->loginPwd = md5(input("post.newPass").$staff["secretKey"]);
		$result = $staff->save();
        if(false !== $result){
        	return WSTReturn("修改成功", 1);
        }else{
        	return WSTReturn($this->getError(),-1);
        }
	}
	/**
	 * 分页
	 */
	public function uploadpageQuery($id){
		$where = [];
		$where['a.dataFlag'] = 1;
		//$pt = (int)input('positionType');
		//$apId = (int)input('adPositionId');
		//if($pt>0)$where['a.positionType'] = $pt;
        $shopId = Input("shopId");
		$where['a.adPositionId'] = $id;
		$where['a.shopId'] = $shopId;
        $where['adName']='共享';
		return Db::name('ads')->alias('a')
		            ->join('ad_positions ap','a.positionType=ap.positionType AND a.adPositionId=ap.positionId AND ap.dataFlag=1','left')
					->field('adId,adName,adPositionId,adURL,adStartDate,adEndDate,adPositionId,adFile,adClickNum,ap.positionName,a.adSort,a.shopId')
		            ->where($where)->order('adId desc')
		            ->order('adSort','asc')
		            ->paginate(input('pagesize/d'));
	}
}
