<?php
namespace wstmart\admin\controller;
use wstmart\admin\model\Userauth as M;
use think\Db;
/**
 * 认证控制器
 */
class Userauth extends Base{
    public function index(){
    	return $this->fetch("list");
    }
    
    /**
     * 获取分页
     */
    public function pageQuery(){
//    	$m = new M();
        $page = Db::name('user_idcard')->alias('c')->join('__USERS__ u','c.userId=u.userId and u.dataFlag=1','left')
//            ->where(['s.dataFlag'=>1])
            ->field('u.loginName,u.userPhone,u.idCardStatus,c.zhengUrl,c.fanUrl,c.createTime,c.idCard_id')
            ->order('c.idCard_id', 'desc')
            ->paginate(input('pagesize/d'))
            ->toArray();
    	return $page;
    }
    /**
     * 获取菜单
     */
    public function get(){        
    	$m = new M();
    	return $m->get((int)Input("post.id"));
    }
    /**
     * 跳去处理页面
     */
    public function toHandle(){
        $id=((int)Input("get.id"));
        $object = Db::name('user_idcard')->alias('c')->join('__USERS__ u','c.userId=u.userId and u.dataFlag=1','left')
            ->where(['c.idCard_id'=>$id])
            ->field('u.loginName,u.userPhone,c.*')->find();
    	$this->assign("object",$object);
    	return $this->fetch("edit");
    }
    /**
     * 编辑菜单--处理申请
     */
    public function handle(){
//        dump(input('post.'));die();
        $id = input('post.idCard_id/d');
        $usersId = input('post.userId/d');
        $data = [];
        $idCardStatus = input('post.idCardStatus/d');
        $handleDesc = input('post.handleDesc');
        if(!in_array($idCardStatus,array(2,3)))return WSTReturn("无效的处理状态", -1);
        if($idCardStatus==3 && $handleDesc=='')return WSTReturn("请输入申请失败原因", -1);
        //启动事务
        Db::startTrans();
        try{
            if($idCardStatus==2){
                Db::name('users')->where('userId',$usersId)->update([
                    'idCardStatus' => 2
                ]);
            }
            if($idCardStatus==3){
                $data['handleDesc']=$handleDesc;
                Db::name('user_idcard')->where(['idCard_id'=>$id])->update($data);
            }
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
     * 删除菜单
     */
    public function del(){
    	$m = new M();
    	return $m->del();
    }
}
