<?php
namespace wstmart\admin\controller;
use think\Log;
use wstmart\admin\model\Agent as M;
use wstmart\admin\model\Roles as R;
use think\Db;
/**
 * 职员控制器
 */
class Agent extends Base{
    public function index(){
    	return $this->fetch("list");
    }
    /**
     * 获取分页
     */
    public function pageQuery(){
    	$m = new M();
    	return $m->pageQuery();
    }
    /**
     * 获取
     */
    public function get(){
    	$m = new M();
    	return $m->get((int)Input("post.id"));
    }
    /**
     * 跳去新增界面
     */
    public function toAdd(){
    	$id = (int)Input("get.id",0);
    	$m = new M();
    	$this->assign("object",['staffId'=>0,'workStatus'=>1,'staffStatus'=>1]);
    	$roles=Db::name('roles')->where('dataFlag=1 and roleId in(9,10)')->field('roleId,roleName')->select();
    	$this->assign("roles",$roles);
		$areaList = model('areas')->listQuery(0); 
        $this->assign("areaList",$areaList);
    	return $this->fetch("add");
    }
    /**
     * 跳去编辑页面
     */
    public function toEdit(){
    	$id = (int)Input("get.id",0);
    	$m = new M();
    	$rs = $m->getById($id);
    	$this->assign("object",$rs);
    	$roles=Db::name('roles')->where('dataFlag=1 and roleId in(9,10)')->field('roleId,roleName')->select();
    	$this->assign("roles",$roles);
		$areaList = model('areas')->listQuery(0); 
        $this->assign("areaList",$areaList);
    	return $this->fetch("edit");
    }
    /**
     * 新增
     */
    public function add(){
    	$m = new M();
    	return $m->add();
    }
    /**
     * 编辑菜单
     */
    public function edit(){
    	$m = new M();
    	return $m->edit();
    }
    /**
     * 删除菜单
     */
    public function del(){
    	$m = new M();
    	return $m->del();
    }
    /**
     * 检测账号是否重复
     */
    public function checkLoginKey(){
    	$m = new M();
    	return $m->checkLoginKey(input('post.key'));
    }
    /**
     * 编辑自己密码
     */
    public function editMyPass(){
    	$m = new M();
    	return $m->editMyPass((int)session('WST_STAFF.staffId'));
    }
    /**
     * 编辑职员密码
     */
    public function editPass(){
    	$m = new M();
    	return $m->editPass((int)input('post.staffId'));
    }
	//共享广告位管理
	public function adsindex(){
    	return $this->fetch("listads");
    }
    /**
     * 获取托管广告分页
     */
    public function adspageQuery(){
    	$m = new M();
        Log::record(session('WST_STAFF.staffId'),'staffId');
    	return $m->adspageQuery();
    }
	 /**
     * 共享广告位操作
     */
    public function editshare(){
    	$id=(int)input('post.id');
		if(input("post.price")=='')return WSTReturn("单价不能为空");
//		if(input("post.timeunit")=='')return WSTReturn("时间单位不能为空");
		$share = Db::name('trusteeship')->where('trId',$id)->find();
		if(empty($share))return WSTReturn("共享位置不存在");
//		$data['timeunit']=input("post.timeunit");
		$data['timeunit']='月';
		$data['price']=input("post.price");
		$data['proportion']=input("post.proportion");
		$data['type']=1;
        Db::startTrans();
        try{
            Db::name('trusteeship')->where('trId',$id)->update($data);
            $data1=[];
            $x=0;
            for ($i=0;$i<$share['monthNum'];$i++){
                $data1[$i]['tid']=$id;
                $createTime=$share['createTime'];
                $base = strtotime(date('Y-m',strtotime($createTime)).'-01 00:00:01');
                $a=++$x;
                $data1[$i]['shareDate']=date('Y-m',strtotime("+".$a." month", $base));
            }
            Db::name('trusteeship_share')->insertAll($data1);
            Db::commit();
            return WSTReturn("修改成功", 1);
        }catch (\Exception $e) {
            Db::rollback();
            return WSTReturn("修改失败", -1);
        }
    }
	//自用共享广告位管理
	public function adsupload(){
		$positionid = Input("id");
		$shopId = Input("shopId");
//		echo $shopId;die;
		$this->assign('positionid',$positionid);
		$this->assign('shopId',$shopId);
    	return $this->fetch("listupload");
    }
	/**
     * 自用广告位图片列表分页
     */
    public function listupload(){
        $positionid = Input("id");
        $shopId = Input("shopId");
        Log::write($shopId,'notice');
		$this->assign('positionid',$positionid);
//        $this->assign('shopId',$shopId);
        $m = new M();
    	return $m->uploadpageQuery($positionid);
    }
	/**
     * 新增图片
     */
    public function toupload(){
        $m = new M();
		//广告位置
        $positionid = Input("positionid");
        //商店Id
        $shopId = Input("shopId");
//        echo $shopId;die;
		//广告详情
		$data = model("ads")->getById(Input("id/d",0));
		$this->assign('positionid',$positionid);
		$this->assign('shopId',$shopId);
        return $this->fetch("uploadads",['data'=>$data]);
    }
}