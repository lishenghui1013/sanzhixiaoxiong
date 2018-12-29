<?php

namespace wstmart\home\controller;
use think\Db;

/**
 *拼团模块控制器
 */
class Spell extends Base{

    //跳到团长列表 （团长必支付完）
    public function index(){
        $goodsId=input('get.id');
        $where['g.goods_id'] = ["=",(int)$goodsId];
        $where['o.isPay'] = 1;
        $teamsData = Db::name("spell_teams")->alias('g')
            ->join("__USERS__ s","g.user_id = s.userId",'inner')
            ->join('__SPELL_ORDERS__ o','g.id = o.team_id and g.user_id=o.userId')
            ->where($where)
            ->field('g.*,s.loginName,s.userId')->select();
        $this->assign('teamsData',$teamsData);
//        var_dump($teamsData);die();
        return $this->fetch('shops/spell/list_spell');
    }
    //查看参团人员信息
    public function detail(){
        $team_id=input('get.team_id/d');
        $goods_id=input('get.goodsId/d');
        $type=Db::name('spell_teams')->value('status');
        $this->assign('type',$type);
//        var_dump($team_id);die();
        $where['o.goods_id'] = ["=",(int)$goods_id];
        $where['o.team_id'] = ["=",(int)$team_id];
//        $where['orderStatus']=0;//已支付
        $teamsData = Db::name("spell_orders")->alias('o')->join("__USERS__ s","o.userId = s.userId",'inner')
            ->where($where)
            ->field('o.*,s.loginName,s.userId')->select();
        $this->assign('teamsData',$teamsData);
        return $this->fetch('shops/spell/list_spell_detail');
    }
    //过时，未成团的修改状态
    public function noSpell(){
        $data=Db::name('spell_teams')->where(['status'=>0])->select();
        $time=time();
        foreach($data as $key=>$value){
            $num=$time-($value['addtime']+(60*24));
            if($num>=0){
                Db::name('spell_teams')->where(['id'=>$value['id']])->setField('status', '2');
            }
        }
    }
    //成团失败，退款（手动退消费金）
    public function refund(){
        $order_id=input('post.refund/d');
        $team_id=input('post.team_id/d');
        $where['orderId']=$order_id;
        $where['team_id']=$team_id;
//        $where['orderStatus']=0;//已支付
        $money=Db::name('spell_orders')->where($where)->find();
        //启动事务
        Db::startTrans();
        try{
            Db::name('users')->where(['userId'=>$money['userId']])->setInc('userXiaofeiMoney', $money['totalMoney']);
            $data=[
                'userId'=>$money['userId'],
                'money'=>$money['totalMoney'],
                'dataSrc'=>5,
                'dataId'=>$money['orderId'],
                'dataRemarks'=>"拼团失败订单【".$money['orderNo']."】退款获得消费金".$money['totalMoney']."个",
                'moneyType'=>1,
                'createTime'=>date("Y-m-d H:i:s")
            ];
                Db::name('user_xiaofeimoney')->insert($data);
                Db::name('spell_orders')->where($where)->setField('isRefund', '1');
                //提交事务
                Db::commit();
                return WSTReturn("退款成功", 1);
        }catch (\Exception $e) {
            //回滚事务
            Db::rollback();
            return WSTReturn('退款失败',-1);
        }
    }
}