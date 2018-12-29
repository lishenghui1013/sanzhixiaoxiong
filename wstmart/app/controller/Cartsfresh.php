<?php


namespace wstmart\app\controller;
use think\Db;
use think\Config;

/*
 * 新鲜到家-购物车控制器
 */

header('Access-Control-Allow-Origin:*');
class Cartsfresh extends Base {
    /**
     * 加入购物车
     */
    public function addCart(){
        $userId = (int)input('post.userId');
        $goodsId = (int)input('post.goodsId');
        $cartNum = (int)input('post.buyNum',1);//商品数量
        $cartNum = ($cartNum>0)?$cartNum:1;
        if($userId==0 || $goodsId==0)
            return SZXReturn($this->message[2],"2");
        //验证传过来的商品是否合法
        $chk = $this->checkGoodsSaleSpec($goodsId);
        if($chk['status']==-1) return SZXReturn($this->message[5],"5");

        $goods = Db::name('carts_fresh')->where(['userId'=>$userId,'goodsId'=>$goodsId])->find();
        //检测库存是否足够
        if($chk['data']['stock']<($goods?(int)$goods['cartNum']+$cartNum:0+$cartNum)) return SZXReturn($this->message[4],"4");
        $shopId=Db::name('goods')->where(['goodsId'=>$goodsId])->value('shopId');
        if(empty($goods)){
            $data = array();
            $data['userId'] = $userId;
            $data['goodsId'] = $goodsId;
            $data['shopId'] = $shopId;
            $data['isCheck'] = 1;
            $data['cartNum'] = $cartNum;
            $rs = Db::name('carts_fresh')->insert($data);
            if(false !==$rs){
                return SZXReturn($this->message[1],"1");
            }
        }else{
            $rs = Db::name('carts_fresh')->where(['userId'=>$userId,'goodsId'=>$goodsId])->setInc('cartNum',$cartNum);
            if(false !==$rs){
                return SZXReturn($this->message[1],"1");
            }
        }
        return SZXReturn($this->message[2],"2");
    }
    /**
     * 验证商品是否合法
     */
    public function checkGoodsSaleSpec($goodsId){
        $goods = model('Goods')->where(['goodsStatus'=>1,'dataFlag'=>1,'isSale'=>1,'goodsId'=>$goodsId])->field('goodsId,isSpec,goodsStock')->find();
        if(empty($goods))

            return WSTReturn("添加失败，无效的商品信息", -1);

        $goodsStock = (int)$goods['goodsStock'];//商品总库存
        return WSTReturn("", 1,['goodsSpecId'=>0,'stock'=>$goodsStock]);

    }


    /**
     * 获取购物车商品列表
     */
    public function getCartInfo($isSettlement = false){
        $userId = (int)input('post.userId');
        $shopId = (int)input('post.shopId');

        $where = [];
        $where['c.userId'] = $userId;
        $where['c.shopId'] = $shopId;
        if($isSettlement)$where['c.isCheck'] = 1;
        $rs = Db::name('carts_fresh')->alias('c')
            ->join('__GOODS__ g','c.goodsId=g.goodsId','inner')
            ->join('__GOODS_SPECS__ gs','c.goodsSpecId=gs.id','left')
            ->where($where)
//            ->field('c.goodsSpecId,c.cartId,g.goodsId,g.shopId,g.goodsName,g.shopPrice,g.goodsStock,g.isSpec,gs.specPrice,gs.specStock,g.goodsImg,c.isCheck,gs.specIds,c.cartNum')
            ->field('c.cartId,g.goodsId,g.shopId,g.goodsName,g.shopPrice,c.isCheck,gs.specIds,c.cartNum')
            ->select();

        $goodsIds = [];
        //总钱数
        $goodsTotalMoney = 0;
        //商品数
        $goodsTotalNum = 0;
        foreach ($rs as $key =>$v){
            if(!in_array($v['goodsId'],$goodsIds))$goodsIds[] = $v['goodsId'];
            $goodsTotalMoney = $goodsTotalMoney + $v['shopPrice'] * $v['cartNum'];
        }
        $goodsTotalNum = count($rs);

        $data=[
            'list'=>$rs,
            'goodsTotalMoney'=>sprintf("%.2f", $goodsTotalMoney),
            'goodsTotalNum'=>$goodsTotalNum
        ];
//        dump($rs);die();
//        return ['list'=>$rs,'goodsTotalMoney'=>sprintf("%.2f", $goodsTotalMoney),'goodsTotalNum'=>$goodsTotalNum];
        return SZXReturn($this->message[1],"1",$data);

    }

    //购物车商品数量修改--判断库存
    public  function upCartsNum(){
//        $cartId=input('post.cartId');
        $userId=input('post.userId');
        $goodsId=input('post.goodsId');
        $buyNum=input('post.buyNum');
        //验证传过来的商品是否合法
        $chk = $this->checkGoodsSaleSpec($goodsId);
        if($chk['status']==-1) return SZXReturn($this->message[5],"5");
        //检测库存是否足够
        if($chk['data']['stock']<$buyNum){
            return SZXReturn($this->message[6],"6");
        }else{
            //判断为零 删掉
            if($buyNum==0){
                $Result=Db::name('carts_fresh')->where(['userId'=>$userId,'goodsId'=>$goodsId])->delete();
                if($Result){
                    return SZXReturn($this->message[1],"1");
                }else{
                    return SZXReturn($this->message[2],"2");
                }
            }
            $rs = Db::name('carts_fresh')->where(['userId'=>$userId,'goodsId'=>$goodsId])->update(['cartNum'=>$buyNum]);
            if(false !==$rs){
                return SZXReturn($this->message[1],"1");
            }
        }
    }

    //订单结算页
    public function settlementList(){
//        $cartId = input('post.cartId');
        $userId = (int)input('post.userId');
        $shopId = (int)input('post.shopId');

        $where = [];
        $where['c.userId'] = $userId;
        $where['c.isCheck'] = 1;
        $where['c.shopId'] = $shopId;
        $rs = Db::name('carts_fresh')->alias('c')
//            ->where("userId = ".$userId." and cartId in(".$cartId.")")
            ->join('__GOODS__ g','c.goodsId=g.goodsId','inner')
            ->join('__GOODS_SPECS__ gs','c.goodsSpecId=gs.id','left')
            ->where($where)
            ->field('c.goodsSpecId,c.cartId,g.goodsId,g.shopId,g.goodsName,g.shopPrice,g.goodsStock,g.isSpec,gs.specPrice,gs.specStock,g.goodsImg,c.isCheck,gs.specIds,c.cartNum,g.scoresScale,g.xiaofeiScale')
            ->select();

        $goodsIds = [];
        //总钱数
        $goodsTotalMoney = 0;
        //商品数
        $goodsTotalNum = 0;
        //最多抵现积分
        $mostScores=0;
        //最多抵现消费金
        $mostXiaofei=0;
        foreach ($rs as $key =>$v){
            if(!in_array($v['goodsId'],$goodsIds))$goodsIds[] = $v['goodsId'];
            $goodsTotalMoney = $goodsTotalMoney + $v['shopPrice'] * $v['cartNum'];
            //商品最多可抵现积分
            $mostScores = $mostScores + floor($v['shopPrice'] * $v['scoresScale']* $v['cartNum']);
            //商品最多可抵现消费金
            $mostXiaofei = $mostXiaofei + floor($v['shopPrice'] * $v['xiaofeiScale']* $v['cartNum']);
            $rs[$key]['goodsImg'] = Config::get('site_domain').'/'.WSTImg($v['goodsImg'],3);
        }
        //积分
        $userScore=Db::name('users')->where(['userId'=>$userId])->find();
        $goodsTotalNum = count($rs);
        $shop=Db::name('shops')->where(['shopId'=>$shopId])->find();
//        dump($shop);die;
        $goodsTotalMoney = $goodsTotalMoney + $shop['freight'];
        $data=[
            'shopId'=>$shopId,
            'shopName'=>$shop['shopName'],
            'goodsTotalMoney'=>sprintf("%.2f", $goodsTotalMoney),
            'goodsTotalNum'=>$goodsTotalNum,//商品数
            'userScore'=>$mostScores>=$userScore['userScore']?$userScore['userScore']:$mostScores,//积分
            'userXiaofeiMoney'=>$mostXiaofei>=$userScore['userXiaofeiMoney']?$userScore['userXiaofeiMoney']:$mostXiaofei,//消费金
            'list'=>$rs,
        ];


//        return ['list'=>$rs,'goodsTotalMoney'=>sprintf("%.2f", $goodsTotalMoney),'goodsTotalNum'=>$goodsTotalNum];
        return SZXReturn($this->message[1],"1",$data);

    }

    //自取时间
    public function ziquTime(){
        $shopId=input('post.shopId');//店铺ID
        $data=Db::name('shops')->where(['shopId'=>$shopId])->value('pickupTime');
        return SZXReturn($this->message[1],"1",$data);
    }

    //购物车结算
    public function cartsSettlement(){
        $userId=input('post.userId');//用户ID
        $shopId=input('post.shopId');//店铺ID
        $addressId = (int)input('post.addressId');//地址id
        $orderRemarks=input('post.orderRemarks');//订单备注
        $isScore=input('post.isScore',-1);//积分是否开启 （1开启，-1关闭）
        $isXiaofei=input('post.isXiaofei',-1);//消费金是否开启 （1开启，-1关闭）
        $payFrom=input('post.payFrom');//支付方式  1:支付宝，2：微信
        //检测购物车
        $where = [];
        $where['c.userId'] = $userId;
        $where['c.shopId'] = $shopId;
        $carts = Db::name('carts_fresh')->alias('c')->join('__GOODS__ g','c.goodsId=g.goodsId','inner')
            ->join('__GOODS_SPECS__ gs','c.goodsSpecId=gs.id','left')
            ->where($where)
            ->field('c.goodsSpecId,c.cartId,g.goodsId,g.shopId,g.goodsName,g.shopPrice,g.goodsStock,g.isSpec,gs.specPrice,gs.specStock,g.goodsImg,c.isCheck,gs.specIds,c.cartNum')
            ->select();
        if(empty($carts))return SZXReturn($this->message[7],"7");
        //检测地址是否有效
        $address = Db::name('user_address')->where(['userId'=>$userId,'addressId'=>$addressId,'dataFlag'=>1])->find();
        if(empty($address)){
            return SZXReturn($this->message[8],"8");
        }

        //商品列表 转 店铺对应商品
        $store_group = array();
//        foreach($carts as $k=>$v){
            $shopName=Db::name('shops')->where(['shopId'=>$shopId])->find();
            $store_group['shopId']=$shopName['shopId'];
            $store_group['shopName']=$shopName['shopName'];
            $store_group['freight']=$shopName['freight'];//默认运费
            $store_group['shopTotalMoney']='';//店铺商品总价
            $store_group['list']=$carts;
//        }
//        $store_group=array_values($store_group);
//        dump($store_group);die();
        $goodsTotalMoney=0;
        $shopTotalMoney=0;
        //最多抵现积分
        $mostScores=0;
        //最多抵现消费金
        $mostXiaofei=0;
        //无店铺运费 计算店铺商品总价
        foreach ($store_group['list'] as $key=>$value){
            $shopTotalMoney = $shopTotalMoney + $value['shopPrice'] * $value['cartNum'];
            //            商品最多可抵现积分
            $mostScores = $mostScores + floor($value['shopPrice'] * $value['scoresScale']* $value['cartNum']);
            //商品最多可抵现消费金
            $mostXiaofei = $mostXiaofei + floor($value['shopPrice'] * $value['xiaofeiScale']* $value['cartNum']);
        }
        $store_group["shopTotalMoney"] = sprintf("%.2f", $shopTotalMoney);//店铺商品总价

//        dump($store_group);die();


        //生成订单
        Db::startTrans();
        try{
            $orderunique = WSTOrderQnique();

//            foreach ($store_group as $ckey =>$shopOrder){
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

                //积分
                $userScore=Db::name('users')->where(['userId'=>$userId])->find();
                //商品总钱数-抵现积分
                if($isScore==1){
                    $order['scoreTotal']=$mostScores>=$userScore['userScore']?$userScore['userScore']:$mostScores;
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
                //商品总钱数-抵现消费金
                if($isScore==1){
                    $order['totalMoney']=$mostXiaofei>=$userScore['userXiaofeiMoney']?$userScore['userXiaofeiMoney']:$mostXiaofei;
                    $order['totalMoney']=$order['totalMoney']-$order['totalMoney'];
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
//                $order['isInvoice'] = $isInvoice; //是否发票
//                $order['invoiceClient'] = $invoiceClient; //发票抬头
//                $order['orderRemarks'] = input('post.remark_'.$shopOrder['shopId']);
                $order['orderRemarks'] = $orderRemarks;//备注
                $order['orderunique'] = $orderunique;//唯一流水号
                $order['orderSrc'] = 0;
                $order['dataFlag'] = 1;
                $order['createTime'] = date('Y-m-d H:i:s');

                $result = Db::name('orders')->insertGetId($order);
                if(false !== $result){
                    $orderTotalGoods = [];
                    foreach ($store_group['list'] as $gkey =>$goods){
                        //创建订单商品记录
                        $orderGgoods = [];
                        $orderGoods['orderId'] = $result;
                        $orderGoods['goodsId'] = $goods['goodsId'];
                        $orderGoods['goodsNum'] = $goods['cartNum'];
                        $orderGoods['goodsPrice'] = $goods['shopPrice'];
                        $orderGoods['goodsSpecId'] = $goods['goodsSpecId'];
                        $orderGoods['goodsName'] = $goods['goodsName'];
                        $orderGoods['goodsImg'] = $goods['goodsImg'];
//                        $orderGoods['commissionRate'] = WSTGoodsCommissionRate($goods['goodsCatId']);
                        $orderTotalGoods[] = $orderGoods;
                        //修改库存
                        Db::name('goods')->where('goodsId',$goods['goodsId'])->setDec('goodsStock',$goods['cartNum']);
                    }
                    $orderId= Db::name('order_goods')->insertAll($orderTotalGoods);
                    //建立订单记录
                    $logOrder = [];
                    $logOrder['orderId'] = $orderId;
                    $logOrder['orderStatus'] = $order['orderStatus'];
                    $logOrder['logContent'] = "下单成功，等待用户支付";
                    $logOrder['logUserId'] = $userId;
                    $logOrder['logType'] = 0;
                    $logOrder['logTime'] = date('Y-m-d H:i:s');
                    Db::name('log_orders')->insert($logOrder);
                    //给店铺增加提示消息
                    WSTSendMsg($userId,"您有一笔新的订单【".$orderNo."】待处理。",['from'=>1,'dataId'=>$orderId]);
                }
//            }
            //删除已选的购物车商品
            Db::name('carts_fresh')->where(['userId'=>$userId,'shopId'=>$shopId])->delete();
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