<?php


namespace wstmart\app\controller;
use think\Db;
use think\Config;

/*
 * 拼团类
 */
header('Access-Control-Allow-Origin:*');
class Spell extends Base {
    //拼团购买页
    public function spellBuyShow(){
        $userId = (int)input('post.userId');
        $goodsId = (int)input('post.goodsId');
        $buyNum = (int)input('post.buyNum',1);//商品数量
        //验证传过来的商品是否合法
        $chk = $this->checkGoodsSaleSpec($goodsId);
        if($chk['status']==-1) return SZXReturn($this->message[5],"5");
        //检测库存是否足够
        if($chk['data']['stock']<$buyNum) return SZXReturn($this->message[4],"4");
        $where = [];
        $where['g.goodsId'] = $goodsId;
        $rs = Db::name('goods')->alias('g')
//            ->join('__GOODS__ g','c.goodsId=g.goodsId','inner')
//            ->join('__GOODS_SPECS__ gs','gs.goodsId=g.goodsId','inner')
            ->join('__SPELL__ s','s.goods_Id=g.goodsId','inner')
            ->where($where)
            ->field('g.goodsId,g.shopId,g.goodsName,g.shopPrice,g.goodsStock,g.isSpec,g.goodsImg,num,price,g.scoresScale,g.xiaofeiScale')
            ->find();
        //购买商品数
        $rs['buyNum']=$buyNum;
        //总钱数
        $goodsTotalMoney = 0;
        $goodsTotalMoney = $rs['price'] * $rs['buyNum'];
        //最多抵现积分
        $mostScores = floor($rs['price'] * $rs['scoresScale']* $rs['buyNum']);
        //最多抵现消费金
        $mostXiaofei =  floor($rs['price'] * $rs['xiaofeiScale']* $rs['buyNum']);
        $rs['goodsImg'] = Config::get('site_domain').'/'.WSTImg($rs['goodsImg'],3);
        $store_group=[];
        $shopName=Db::name('shops')->where(['shopId'=>$rs['shopId']])->find();
        $goodsTotalMoney=$goodsTotalMoney+$shopName['freight'];
        //积分
        $userScore=Db::name('users')->where(['userId'=>$userId])->find();

        $store_group['shopId']=$shopName['shopId'];
        $store_group['shopName']=$shopName['shopName'];
        $store_group['freight']=$shopName['freight'];//运费
        $store_group['goodsTotalMoney']=sprintf("%.2f", $goodsTotalMoney);
        $store_group['userScore']=$mostScores>=$userScore['userScore']?$userScore['userScore']:$mostScores;//积分
        $store_group['userXiaofeiMoney']=$mostXiaofei>=$userScore['userXiaofeiMoney']?$userScore['userXiaofeiMoney']:$mostXiaofei;//消费金
        $store_group['list']=$rs;
        return SZXReturn($this->message[1],"1",$store_group);
    }
    /**
     * 验证商品是否合法
     */
    public function checkGoodsSaleSpec($goodsId){
        $goods = model('Goods')->where(['goodsStatus'=>1,'dataFlag'=>1,'isSale'=>1,'goodsId'=>$goodsId])->field('goodsId,isSpec,goodsStock,isSpell')->find();
        if(empty($goods) || $goods['isSpell']!=1)return WSTReturn("添加失败，无效的商品信息",-1);
        $goodsStock = (int)$goods['goodsStock'];//商品总库存
        return WSTReturn("", 1,['goodsSpecId'=>0,'stock'=>$goodsStock]);
    }
    //拼团购买-结算
    public function spellBuy(){
        $userId=input('post.userId');//用户ID
        $goodsId = (int)input('post.goodsId');//商品ID
        $buyNum = (int)input('post.buyNum',1);//商品数量
        $addressId = (int)input('post.addressId');//地址id
        $orderRemarks=input('post.orderRemarks');//订单备注
        $isScore=input('post.isScore',-1);//积分是否开启 （1开启，-1关闭）
        $isXiaofei=input('post.isXiaofei',-1);//消费金是否开启 （1开启，-1关闭）
        $payFrom=input('post.payFrom');//支付方式  1:支付宝，2：微信

        //验证传过来的商品是否合法
        $chk = $this->checkGoodsSaleSpec($goodsId);
        if($chk['status']==-1) return SZXReturn($this->message[5],"5");
        //检测库存是否足够
        if($chk['data']['stock']<$buyNum) return SZXReturn($this->message[4],"4");
        //检测地址是否有效
        $address = Db::name('user_address')->where(['userId'=>$userId,'addressId'=>$addressId,'dataFlag'=>1])->find();
        if(empty($address)) return SZXReturn($this->message[8],"8");

        $where = [];
        $where['g.goodsId'] = $goodsId;
        $rs = Db::name('goods')->alias('g')
//            ->join('__GOODS__ g','c.goodsId=g.goodsId','inner')
//            ->join('__GOODS_SPECS__ gs','gs.goodsId=g.goodsId','inner')
                ->join('__SPELL__ s','s.goods_Id=g.goodsId')
            ->where($where)
            ->field('g.goodsId,g.shopId,g.goodsName,g.shopPrice,g.goodsStock,g.isSpec,g.goodsImg,g.scoresScale,g.xiaofeiScale')
            ->find();
//        if(empty($rs))return SZXReturn($this->message[7],"7");

        $store_group = array();
        $shopName=Db::name('shops')->where(['shopId'=>$rs['shopId']])->find();
        $store_group['shopId']=$shopName['shopId'];
        $store_group['shopName']=$shopName['shopName'];
        $store_group['freight']=$shopName['freight'];//默认运费
        $store_group['shopTotalMoney']=$rs['price'] * $buyNum;//店铺商品总价
        $store_group['list']=$rs;
        //店铺商品可抵现积分
        $scoreTotal=0;
        //店铺商品可抵现消费金
        $xiaofeiTotal=0;
        $scoreTotal =  floor($rs['price'] * $rs['scoresScale']* $rs['buyNum']);
        $xiaofeiTotal = floor($rs['price'] * $rs['xiaofeiScale']* $rs['buyNum']);
        //生成订单
        Db::startTrans();
        try{
            $orderunique = WSTOrderQnique();
            $orderNo = WSTOrderNo();
            $orderScore = 0;
            //创建订单
            $order = [];
            $order['userName'] = $address['userName'];
            $order['userPhone'] = $address['userPhone'];
            $order['userAddress'] = $address['userAddress'];

            $order['orderNo'] = $orderNo;
            $order['userId'] = $userId;
            $order['shopId'] = $store_group['shopId'];
            $order['orderStatus'] = -2;//待付款
            $order['isPay'] = 0;//未支付
            $order['goodsMoney'] = $store_group['shopTotalMoney'];//商品总金额
//                $order['deliverType'] = $deliverType;
            $order['deliverMoney'] = $store_group['freight'];//运费
            $order['totalMoney'] = $order['goodsMoney']+$order['deliverMoney'];//订单总金额 + 运费
            //积分 消费金
            $userScore=Db::name('users')->where(['userId'=>$userId])->find();
            //积分是否开启
            if($isScore==1){
                $order['scoreTotal']=$scoreTotal>=$userScore['userScore']?$userScore['userScore']:$scoreTotal;
                $order['totalMoney']=$order['totalMoney']-$order['scoreTotal'];
                //给用户减积分
                $score = [];
                $score['userId'] = $userId;
                $score['score'] = $order['scoreTotal'];
                $score['dataSrc'] = 1;
                $score['dataId'] = $orderNo;
                $score['dataRemarks'] = "购买订单【".$orderNo."】抵现积分".$order['scoreTotal']."个";
//                    $scoreTotal1=$order['scoreTotal'];
                $score['scoreType'] = 2;
                $score['createTime'] = date('Y-m-d H:i:s');
                Db::name('user_scores')->insert($score);
                // 减小用户积分
                model('Users')->where("userId=".$userId)->setDec('userScore',$order['scoreTotal']);
            }
            //消费金是否开启
            if($isXiaofei==1){
                $order['xiaofeiTotal']=$xiaofeiTotal>=$userScore['userXiaofeiMoney']?$userScore['userXiaofeiMoney']:$xiaofeiTotal;
                $order['totalMoney']=$order['totalMoney']-$order['xiaofeiTotal'];
                //给用户减消费金
                $score = [];
                $score['userId'] = $userId;
                $score['money'] = $order['xiaofeiTotal'];
                $score['dataSrc'] = 1;
                $score['dataId'] = $orderNo;
                $score['dataRemarks'] = "购买订单【".$orderNo."】抵现消费金".$order['totalMoney']."个";
//                $scoreTotal1=$order['scoreTotal'];
                $score['moneyType'] = 2;
                $score['createTime'] = date('Y-m-d H:i:s');
                Db::name('user_scores')->insert($score);
                // 减小用户消费金
                model('Users')->where("userId=".$userId)->setDec('userXiaofeiMoney',$order['xiaofeiTotal']);
            }
            $order['realTotalMoney'] = $order['totalMoney'];//实际订单总金额 - 折扣
            $order['needPay'] = $order['realTotalMoney'];
            //积分
            $orderScore = 0;
            //如果开启下单获取积分则有积分
            if(WSTConf('CONF.isOrderScore')==1){
                $orderScore = round($order['goodsMoney'],0);
            }
            $order['orderScore'] = $orderScore;
            $order['orderRemarks'] = $orderRemarks;//备注
            $order['orderunique'] = $orderunique;//唯一流水号
            $order['orderSrc'] = 0;
            $order['dataFlag'] = 1;
            $order['createTime'] = date('Y-m-d H:i:s');
            $result = Db::name('spell_orders')->insertGetId($order);
            if(false !== $result){
                $orderTotalGoods = [];
                //创建订单商品记录
                $orderGgoods = [];
                $orderGoods['orderId'] = $result;
                $orderGoods['orderNo'] = $orderNo;//订单号 存订单商品表
                $orderGoods['goodsId'] = $rs['goodsId'];
                $orderGoods['goodsNum'] = $buyNum;
                $orderGoods['goodsPrice'] = $rs['shopPrice'];
//                $orderGoods['goodsSpecId'] = $rs['goodsSpecId'];
                $orderGoods['goodsName'] = $rs['goodsName'];
                $orderGoods['goodsImg'] = $rs['goodsImg'];
                $orderTotalGoods[] = $orderGoods;
                //修改库存
                Db::name('goods')->where('goodsId',$rs['goodsId'])->setDec('goodsStock',$buyNum);
                $orderId= Db::name('order_goods')->insertAll($orderTotalGoods);
                //建立订单记录
                $logOrder = [];
                $logOrder['orderId'] = $orderId;
                $logOrder['orderNo'] = $orderNo;
                $logOrder['orderStatus'] = $order['orderStatus'];
                $logOrder['logContent'] = "下单成功，等待用户支付";
                $logOrder['logUserId'] = $userId;
                $logOrder['logType'] = 0;
                $logOrder['logTime'] = date('Y-m-d H:i:s');
                Db::name('log_orders')->insert($logOrder);
                //给店铺增加提示消息
                WSTSendMsg($userId,"您有一笔新的订单【".$orderNo."】待处理。",['from'=>1,'dataId'=>$orderId]);
            }
            //TODO 待加支付
            Db::commit();
            return SZXReturn($this->message[1],"1",$orderunique);
//            return WSTReturn("提交订单成功", 1,$orderunique);
        }catch (\Exception $e) {
            Db::rollback();
            return SZXReturn($this->message[2],"2");
//            return WSTReturn('提交订单失败',-1);
        }
    }
}