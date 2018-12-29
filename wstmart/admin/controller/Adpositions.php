<?php
namespace wstmart\admin\controller;
use wstmart\admin\model\AdPositions as M;
use think\Db;
/**
 * ============================================================================
 * WSTMart多用户商城
 * 版权所有 2016-2066 广州商淘信息科技有限公司，并保留所有权利。
 * 官网地址:http://www.wstmart.net
 * 交流社区:http://bbs.shangtao.net
 * 联系QQ:153289970
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！未经本公司授权您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * 广告位置控制器
 */
class Adpositions extends Base{
	
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
     * 跳去编辑页面
     */
    public function toEdit(){
        $m = new M();
        $assign = ['data'=>$m->getById(Input("get.id/d",0))];
        return $this->fetch("edit",$assign);
    }
    /*
    * 获取数据
    */
    public function get(){
        $m = new M();
        return $m->getById(Input("id/d",0));
    }
    /**
     * 新增
     */
    public function add(){
        $m = new M();
        return $m->add();
    }
    /**
    * 修改
    */
    public function edit(){
        $m = new M();
        return $m->edit();
    }
    /**
     * 删除
     */
    public function del(){
        $m = new M();
        return $m->del();
    }
    /**
    * 获取位置信息（用于广告）
    */
    public function getPositon(){
        $m = new M();
        return $m->getPositon((int)input('post.positionType/d'));
    }  
	/* 托管 */
	public function hosting(){
		  $aid=Input("get.id/d",0);
		  $this->assign('apid',$aid);
		  return $this->fetch("hosting");
	}
	/* 托管广告位置 */
	public function addhosting(){
		  $data = input('post.');
		  //托管月数
          $expiryTime=input('post.monthNum');
		  $data['shopId']='-1';
		  $data['agentId']='-1';
		  $data['createTime']=date('Y-m-d h:i:s');
		  $data['type']=1;
          $base = strtotime(date('Y-m',time()).'-01 00:00:01');
          $data['endDate'] =date("Y-m",strtotime("+$expiryTime month",$base));

		  //Db::name('ad_positions')->where(['positionId'=>$data['trpositionId']])->update(['iftuoguan'=>1]);
        //启动事务
        Db::startTrans();
        try{
            $id=Db::name('trusteeship')->insertGetId($data);
            $data1=[];
            $x=0;
            for ($i=0;$i<$expiryTime;$i++){
                $data1[$i]['tid']=$id;
                $createTime=date('Y-m-d h:i:s');
                $base1 = strtotime(date('Y-m',strtotime($createTime)).'-01 00:00:01');
                $a=++$x;
                $data1[$i]['shareDate']=date('Y-m',strtotime("+".$a." month", $base1));
            }
        //        dump($data1);die;
            Db::name('trusteeship_share')->insertAll($data1);

            //提交事务
            Db::commit();
            return WSTReturn('托管成功',1);
        }catch (\Exception $e) {
            //回滚事务
            Db::rollback();
            return WSTReturn('托管失败',-1);
        }
	}
}
