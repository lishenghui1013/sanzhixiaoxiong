<?php


namespace wstmart\app\controller;
use think\Db;
use think\Config;
use wstmart\app\model\Orders as M;
/*
 * 订单控制器
 */
header('Access-Control-Allow-Origin:*');
class Orders extends Base {
    /**
     * 用户-获取待付款列表
     */
    public function waitPayByPage(){
        $m = new M();
        $rs = $m->userOrdersByPage(-2);
//        $rs = json_decode(json_encode($rs),true);
//        //SORT_DESC SORT_ASC
//        array_multisort(array_column($rs,'createTime'),SORT_ASC,$rs);
//        $page = 2;
//        $indexinpage=2;
//        $rs = array_slice($rs, ($page-1)*$indexinpage, $indexinpage);
//        dump($rs);
//        echo json_encode($rs);
//        die();
        return SZXReturn($this->message[1],"1",$rs);

    }
    /**
     * 用户-获取待发货列表
     */
    public function waitDeliveryByPage(){
        $m = new M();
        $rs = $m->userOrdersByPage(0);
        return SZXReturn($this->message[1],"1",$rs);
    }
    /**
     * 用户-获取待收货列表
     */
    public function waitReceiveByPage(){
        $m = new M();
        $rs = $m->userOrdersByPage(1);
        return SZXReturn($this->message[1],"1",$rs);
    }
    /**
     * 用户-待评价
     */
    public function waitAppraiseByPage(){
        $m = new M();
        $rs = $m->userOrdersByPage(2,0);
        return SZXReturn($this->message[1],"1",$rs);
    }
    /*
     * 全部订单
     */
    public function orderList(){
        $m = new M();
        $rs = $m->userOrdersByPage(-10);
        return SZXReturn($this->message[1],"1",$rs);
    }
    /*
     * 删除-已完成订单
     */
    public function finishDel(){
        $userId=input('post.userId');//用户ID
        $orderNo=input('post.orderNo');//订单号
        $isSpell=input('post.isSpell');// 1:拼团订单，-1普通订单
        $where['userId']=$userId;
        $where['orderNo']=$orderNo;
        $where['orderStatus']=2;
        Db::startTrans();
        try{
            if($isSpell==1){
                Db::name('spell_orders')->where($where)->update(['dataFlag'=>-1]);
            }else{
                Db::name('orders')->where($where)->update(['dataFlag'=>-1]);
            }
            Db::commit();
            return SZXReturn($this->message[1],"1");
        }catch (\Exception $e) {
            Db::rollback();
            return SZXReturn($this->message[2],"2");
        }
    }
    /*
     * 确认收货
     */
    public function receive(){
        $orderId=input('post.orderId');//用户ID
        $userId=input('post.userId');  //用户ID
        $orderNo=input('post.orderNo');//订单号
        $isSpell=input('post.isSpell');// 1:拼团订单，-1普通订单
        $where['userId']=$userId;
        $where['orderNo']=$orderNo;
        if($isSpell==1){
            $order = Db::name('spell_orders')->alias('o')->join('__SHOPS__ s','o.shopId=s.shopId','left')
                ->where(['o.userId'=>$userId,'o.orderId'=>$orderId,'o.orderStatus'=>1])
                ->field('o.orderId,o.orderNo,o.payType,s.userId,s.shopId,o.orderScore')->find();
        }else{
            $order = Db::name('orders')->alias('o')->join('__SHOPS__ s','o.shopId=s.shopId','left')
                ->where(['o.userId'=>$userId,'o.orderId'=>$orderId,'o.orderStatus'=>1])
                ->field('o.orderId,o.orderNo,o.payType,s.userId,s.shopId,o.orderScore')->find();
        }
        if(!empty($order)){
            Db::startTrans();
            try{
                //修改订单收货
                $data = ['orderStatus'=>2,'receiveTime'=>date('Y-m-d H:i:s')];
                if($isSpell==1){
                    $result=Db::name('spell_orders')->where($where)->update($data);
                }else{
                    $result=Db::name('orders')->where($where)->update($data);
                }
                if(false != $result){
                    //修改商家未计算订单数
//                $prefix = config('database.prefix');
//                $upSql = 'update '.$prefix.'shops set noSettledOrderNum=noSettledOrderNum+1,noSettledOrderFee=noSettledOrderFee-'.$commissionFee.' where shopId='.$order['shopId'];
//                Db::execute($upSql);
                    //新增订单日志
                    $logOrder = [];
                    $logOrder['orderId'] = $orderId;
                    $logOrder['orderNo'] = $orderNo;
                    $logOrder['orderStatus'] = 2;
                    $logOrder['logContent'] = "用户已收货";
                    $logOrder['logUserId'] = $userId;
                    $logOrder['logType'] = 0;
                    $logOrder['logTime'] = date('Y-m-d H:i:s');
                    Db::name('log_orders')->insert($logOrder);
                    //发送一条商家信息
                    WSTSendMsg($order['userId'],"您的订单【".$order['orderNo']."】，用户已签收",['from'=>1,'dataId'=>$orderId]);
                    //给用户增加积分
                    if(WSTConf("CONF.isOrderScore")==1){
                        $score = [];
                        $score['userId'] = $userId;
                        $score['score'] = $order['orderScore'];
                        $score['dataSrc'] = 1;
                        $score['dataId'] = $orderId;
                        $score['dataRemarks'] = "交易订单【".$order['orderNo']."】获得积分".$order['orderScore']."个";
                        $score['scoreType'] = 1;
                        $score['createTime'] = date('Y-m-d H:i:s');
                        model('UserScores')->save($score);
                        // 增加用户积分
                        model('Users')->where("userId=$userId")->setInc('userScore',$order['orderScore']);
                        // 用户总积分
                        model('Users')->where("userId=$userId")->setInc('userTotalScore',$order['orderScore']);
                    }
                    Db::commit();
                    return SZXReturn($this->message[1],"1");
                }
            }catch (\Exception $e) {
                Db::rollback();
                return SZXReturn($this->message[2],"2");
            }
        }
        return SZXReturn($this->message[9],"9");
//        return WSTReturn('操作失败，请检查订单状态是否已改变');

    }
}