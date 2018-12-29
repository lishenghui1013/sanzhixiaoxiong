<?php


namespace wstmart\app\controller;
use think\Db;
use think\Config;

/*
 * 购物车控制器
 */

header('Access-Control-Allow-Origin:*');
class Carts extends Base {
    /**
     * 加入购物车
     */
    public function addCart(){
        $userId = (int)input('post.userId');
        $goodsId = (int)input('post.goodsId');
        $cartNum = (int)input('post.buyNum',1);//商品数量
        $cartNum = ($cartNum>0)?$cartNum:1;
        if($userId==0 || $goodsId==0) return SZXReturn($this->message[2],"2");
        //验证传过来的商品是否合法
        $chk = $this->checkGoodsSaleSpec($goodsId);
        if($chk['status']==-1) return SZXReturn($this->message[5],"5");
        $goods = Db::name('carts')->where(['userId'=>$userId,'goodsId'=>$goodsId])->find();
        //检测库存是否足够
        if($chk['data']['stock']<($goods?(int)$goods['cartNum']:0+$cartNum)) return SZXReturn($this->message[4],"4");
        if(empty($goods)){
            $data = array();
            $data['userId'] = $userId;
            $data['goodsId'] = $goodsId;
//            $data['goodsSpecId'] = $goodsSpecId;
            $data['isCheck'] = 1;
            $data['cartNum'] = $cartNum;
            $rs = Db::name('carts')->insert($data);
            if(false !==$rs){
                return SZXReturn($this->message[1],"1");
            }
        }else{
            $rs = Db::name('carts')->where(['userId'=>$userId,'goodsId'=>$goodsId])->setInc('cartNum',$cartNum);
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
        $where = [];
        $where['c.userId'] = $userId;
        if($isSettlement)$where['c.isCheck'] = 1;
        $rs = Db::name('carts')->alias('c')->join('__GOODS__ g','c.goodsId=g.goodsId','inner')
            ->join('__GOODS_SPECS__ gs','c.goodsSpecId=gs.id','left')
            ->where($where)
            ->field('c.goodsSpecId,c.cartId,g.goodsId,g.shopId,g.goodsName,g.shopPrice,g.goodsStock,g.isSpec,gs.specPrice,gs.specStock,g.goodsImg,c.isCheck,gs.specIds,c.cartNum')
            ->select();

        $goodsIds = [];
        //总钱数
        $goodsTotalMoney = 0;
        //商品数
        $goodsTotalNum = 0;
        foreach ($rs as $key =>$v){
            if(!in_array($v['goodsId'],$goodsIds))$goodsIds[] = $v['goodsId'];
            $goodsTotalMoney = $goodsTotalMoney + $v['shopPrice'] * $v['cartNum'];
            $rs[$key]['goodsImg'] = Config::get('site_domain').'/'.WSTImg($v['goodsImg'],3);
        }
        $goodsTotalNum = count($rs);
        //测试商品列表 转 店铺对应商品
        $store_group = array();
        foreach($rs as $k=>$v){
            $shopName=Db::name('shops')->where(['shopId'=>$v['shopId']])->find();
            $store_group[$v['shopId']]['shopId']=$shopName['shopId'];
            $store_group[$v['shopId']]['shopName']=$shopName['shopName'];
            $store_group[$v['shopId']]['list'][]=$v;
        }

        $data=[
            'list'=>array_values($store_group),
            'goodsTotalMoney'=>sprintf("%.2f", $goodsTotalMoney),
            'goodsTotalNum'=>$goodsTotalNum
        ];

//        return ['list'=>$rs,'goodsTotalMoney'=>sprintf("%.2f", $goodsTotalMoney),'goodsTotalNum'=>$goodsTotalNum];
        return SZXReturn($this->message[1],"1",$data);

    }

    /**
     * 删除购物车里的商品
     */
    public function delCart(){
        $userId = (int)input('post.userId');
        //购物车ID
        $id = input('post.id');
        $id = explode(',',WSTFormatIn(",",$id));
        $id = array_filter($id);
        $Result=Db::name('carts')->where("userId = ".$userId." and cartId in(".implode(',', $id).")")->delete();
        if($Result){
            return SZXReturn($this->message[1],"1");
        }else{
            return SZXReturn($this->message[2],"2");
        }
    }
    /**
     * 取消购物车商品选中状态
     */
    public function disChkGoods(){
//        $goodsId = input('post.goodsId');
        $userId = (int)input('post.userId');
//        $isCheck = (int)input('post.isCheck');
        //购物车ID
        $id = input('post.id');
        $id = explode(',',WSTFormatIn(",",$id));
        $id = array_filter($id);
        $Result=Db::name('carts')->where("userId = ".$userId." and cartId in(".implode(',', $id).")")->update(['isCheck'=>0]);
        if(false !==$Result){
            return SZXReturn($this->message[1],"1");
        }else{
            return SZXReturn($this->message[2],"2");
        }
    }
    /**
     * 选定购物车商品选中状态
     */
    public function chkGoods(){
//        $goodsId = input('post.goodsId');
        $userId = (int)input('post.userId');
        $isCheck = (int)input('post.isCheck');
        //购物车ID
        $id = input('post.id');
        $id = explode(',',WSTFormatIn(",",$id));
        $id = array_filter($id);
        $Result=Db::name('carts')->where("userId = ".$userId." and cartId in(".implode(',', $id).")")->update(['isCheck'=>1]);
        if(false !==$Result){
            return SZXReturn($this->message[1],"1");
        }else{
            return SZXReturn($this->message[2],"2");
        }
    }

    //订单结算页
    public function settlementList(){
//        $cartId = input('post.cartId');
        $userId = (int)input('post.userId');
        $where = [];
        $where['c.userId'] = $userId;
        $where['c.isCheck'] = 1;
        $rs = Db::name('carts')->alias('c')
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
            $mostScores = $mostScores + floor($v['shopPrice'] * $v['scoresScale']* $v['cartNum']);
            $mostXiaofei = $mostXiaofei + floor($v['shopPrice'] * $v['xiaofeiScale']* $v['cartNum']);
            $rs[$key]['goodsImg'] = Config::get('site_domain').'/'.WSTImg($v['goodsImg'],3);
        }
        $goodsTotalNum = count($rs);
        //商品列表 转 店铺对应商品
        $store_group = array();
        foreach($rs as $k=>$v){
            $shopName=Db::name('shops')->where(['shopId'=>$v['shopId']])->find();
            $store_group[$v['shopId']]['shopId']=$shopName['shopId'];
            $store_group[$v['shopId']]['shopName']=$shopName['shopName'];
            $store_group[$v['shopId']]['freight']=$shopName['freight'];//默认运费
            $store_group[$v['shopId']]['shopTotalMoney']='';//店铺商品总价
            $store_group[$v['shopId']]['scoreTotal']='';//店铺商品可抵现积分
            $store_group[$v['shopId']]['xiaofeiTotal']='';//店铺商品可抵现消费金
            $store_group[$v['shopId']]['list'][]=$v;
        }
        $shopTotalMoney=0;
        //店铺最多可用积分
        $scoreTotal=0;
        $xiaofeiTotal=0;
        //积分  消费金 查询
        $userScore=Db::name('users')->where(['userId'=>$userId])->find();
        $userScoreNum=$mostScores>=$userScore['userScore']?$userScore['userScore']:$mostScores;//积分
        $userXiaofeiMoney=$mostXiaofei>=$userScore['userXiaofeiMoney']?$userScore['userXiaofeiMoney']:$mostXiaofei;//消费金
        //加店铺运费 计算店铺商品总价

        //计数
        $i=0;
        foreach($store_group as $k=>$v){
            foreach ($v['list'] as $key=>$value){
                $shopTotalMoney = $shopTotalMoney + $value['shopPrice'] * $value['cartNum'];
                $scoreTotal = $scoreTotal + floor($value['shopPrice'] * $value['scoresScale']);
                $xiaofeiTotal = $xiaofeiTotal + floor($value['shopPrice'] * $value['xiaofeiScale']);
            }
            $shopTotalMoney=$shopTotalMoney+$v['freight'];
            $store_group[$k]["shopTotalMoney"] = sprintf("%.2f", $shopTotalMoney);//店铺商品总价+运费
            //积分 消费金 判断第一次
            if(++$i==1){
                //积分
                if($userScoreNum>=$scoreTotal){
                    $store_group[$k]["scoreTotal"] = $scoreTotal;//店铺商品可抵现积分
                    $userScoreNum=$userScoreNum-$scoreTotal;
                }
                //消费金
                if($userXiaofeiMoney>=$xiaofeiTotal){
                    $store_group[$k]["xiaofeiTotal"] = $xiaofeiTotal;//店铺商品可抵现消费金
                    $userXiaofeiMoney=$userXiaofeiMoney-$xiaofeiTotal;
                }
            }else{
                //积分
                if($userScoreNum>0){
                    if($userScoreNum>=$scoreTotal){
                        $store_group[$k]["scoreTotal"] = $scoreTotal;//店铺商品可抵现积分
                        $userScoreNum=$userScoreNum-$scoreTotal;
                    }else{
                        $store_group[$k]["scoreTotal"] = $userScoreNum;//店铺商品可抵现积分
                        $userScoreNum=$userScoreNum-$scoreTotal;
                    }

                }else{
                    $store_group[$k]["scoreTotal"] = 0;
                }
                //消费金
                if($userXiaofeiMoney>0){
                    if($userXiaofeiMoney>=$xiaofeiTotal){
                        $store_group[$k]["xiaofeiTotal"] = $xiaofeiTotal;//店铺商品可抵现积分
                        $userXiaofeiMoney=$userXiaofeiMoney-$xiaofeiTotal;
                    }else{
                        $store_group[$k]["xiaofeiTotal"] = $userXiaofeiMoney;//店铺商品可抵现积分
                        $userXiaofeiMoney=$userXiaofeiMoney-$xiaofeiTotal;
                    }

                }else{
                    $store_group[$k]["xiaofeiTotal"] = 0;
                }
            }
//            $userScoreNum=$userScoreNum-$scoreTotal;
//            if($userScoreNum>0){
//                $store_group[$k]["scoreTotal"] = $scoreTotal;//店铺商品可抵现积分
//            }else{
//                $store_group[$k]["scoreTotal"] = 0;
//            }
//            $userXiaofeiMoney=$userXiaofeiMoney-$xiaofeiTotal;
//            if($userXiaofeiMoney>0){
//                $store_group[$k]["xiaofeiTotal"] = $xiaofeiTotal;//店铺商品可抵现消费金
//            }else{
//                $store_group[$k]["xiaofeiTotal"] = 0;
//            }


            $shopTotalMoney=0;//清除上次计算价格
            $scoreTotal=0;//清除上次计算积分
            $xiaofeiTotal=0;//清除上次计算消费金
            $goodsTotalMoney = $goodsTotalMoney + $v['freight'];
        }

//        dump($userScore);die;

        $data=[
            'list'=>array_values($store_group),
            'goodsTotalMoney'=>sprintf("%.2f", $goodsTotalMoney),
            'goodsTotalNum'=>$goodsTotalNum,
            'userScore'=>$mostScores>=$userScore['userScore']?$userScore['userScore']:$mostScores,//积分
            'userXiaofeiMoney'=>$mostXiaofei>=$userScore['userXiaofeiMoney']?$userScore['userXiaofeiMoney']:$mostXiaofei,//消费金
        ];


//        return ['list'=>$rs,'goodsTotalMoney'=>sprintf("%.2f", $goodsTotalMoney),'goodsTotalNum'=>$goodsTotalNum];
        return SZXReturn($this->message[1],"1",$data);

    }
    //购物车商品数量修改-判断库存
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
            $rs = Db::name('carts')->where(['userId'=>$userId,'goodsId'=>$goodsId])->update(['cartNum'=>$buyNum]);
            if(false !==$rs){
                return SZXReturn($this->message[1],"1");
            }
        }
    }


    //购物车结算
    public function cartsSettlement(){
        $userId=input('post.userId');//用户ID
        $addressId = (int)input('post.addressId');//地址id
        $isScore=input('post.isScore',-1);//积分是否开启 （1开启，-1关闭）
        $isXiaofei=input('post.isXiaofei',-1);//消费金是否开启 （1开启，-1关闭）
        $orderRemarks=input('post.orderRemarks');//订单备注
        $payFrom=input('post.payFrom');//支付方式  1:支付宝，2：微信
        //检测购物车
        $where = [];
        $where['c.userId'] = $userId;
        $where['c.isCheck'] = 1;
        $carts = Db::name('carts')->alias('c')->join('__GOODS__ g','c.goodsId=g.goodsId','inner')
            ->join('__GOODS_SPECS__ gs','c.goodsSpecId=gs.id','left')
            ->where($where)
            ->field('c.goodsSpecId,c.cartId,g.goodsId,g.shopId,g.goodsName,g.shopPrice,g.goodsStock,g.isSpec,gs.specPrice,gs.specStock,g.goodsImg,c.isCheck,gs.specIds,c.cartNum,g.scoresScale,g.xiaofeiScale')
            ->select();
        if(empty($carts))return SZXReturn($this->message[7],"7");
        //检测地址是否有效
        $address = Db::name('user_address')->where(['userId'=>$userId,'addressId'=>$addressId,'dataFlag'=>1])->find();
        if(empty($address)){
            return SZXReturn($this->message[8],"8");
        }
        //商品列表 转 店铺对应商品
        $store_group = array();
        //最多抵现积分
        $mostScores=0;
        //最多抵现消费金
        $mostXiaofei=0;
        //商品总钱数
        $goodsTotalMoney=0;
        foreach($carts as $k=>$v){
            $shopName=Db::name('shops')->where(['shopId'=>$v['shopId']])->find();
            $store_group[$v['shopId']]['shopId']=$shopName['shopId'];
            $store_group[$v['shopId']]['shopName']=$shopName['shopName'];
            $store_group[$v['shopId']]['freight']=$shopName['freight'];//默认运费
            $store_group[$v['shopId']]['shopTotalMoney']='';//店铺商品总价
            $store_group[$v['shopId']]['scoreTotal']='';//店铺商品可抵现积分
            $store_group[$v['shopId']]['xiaofeiTotal']='';//店铺商品可抵现消费金
            $store_group[$v['shopId']]['list'][]=$v;
            $goodsTotalMoney = $goodsTotalMoney + $v['shopPrice'] * $v['cartNum'];
//            商品最多可抵现积分
            $mostScores = $mostScores + floor($v['shopPrice'] * $v['scoresScale']* $v['cartNum']);
            //商品最多可抵现消费金
            $mostXiaofei = $mostXiaofei + floor($v['shopPrice'] * $v['xiaofeiScale']* $v['cartNum']);
        }

        //积分 消费金
        $userScore=Db::name('users')->where(['userId'=>$userId])->find();
        //商品总钱数-最多抵现积分
        if($isScore==1){
            $goodsTotalMoney=$goodsTotalMoney-($mostScores>=$userScore['userScore']?$userScore['userScore']:$mostScores);
        }
        //商品总钱数-最多抵现消费金
        if($isScore==1){
            $goodsTotalMoney=$goodsTotalMoney-($mostXiaofei>=$userScore['userXiaofeiMoney']?$userScore['userXiaofeiMoney']:$mostXiaofei);
        }
        $store_group=array_values($store_group);
        //店铺商品总价
        $shopTotalMoney=0;
        //店铺商品可抵现积分
        $scoreTotal=0;
        //店铺商品可抵现消费金
        $xiaofeiTotal=0;
        //加店铺运费 计算店铺商品总价
        foreach($store_group as $k=>$v){
            foreach ($v['list'] as $key=>$value){
                $shopTotalMoney = $shopTotalMoney + $value['shopPrice'] * $value['cartNum'];
                $scoreTotal = $scoreTotal + floor($value['shopPrice'] * $value['scoresScale']* $value['cartNum']);
                $xiaofeiTotal = $xiaofeiTotal + floor($value['shopPrice'] * $value['xiaofeiScale']* $value['cartNum']);
            }
            $store_group[$k]["shopTotalMoney"] = sprintf("%.2f", $shopTotalMoney);//店铺商品总价
            $store_group[$k]["scoreTotal"] = $scoreTotal;//店铺商品可抵现积分
            $store_group[$k]["xiaofeiTotal"] = $xiaofeiTotal;//店铺商品可抵现消费金
            $shopTotalMoney=0;//清除上次计算价格
            $scoreTotal=0;
            $xiaofeiTotal=0;
            //商品总价+店铺运费
            $goodsTotalMoney = $goodsTotalMoney + $v['freight'];
        }

//        dump($store_group);die();

        //生成订单
        Db::startTrans();
        try{
            $orderunique = WSTOrderQnique();
            //计算次数
            $i=0;
            $a=0;
            foreach ($store_group as $ckey =>$shopOrder){
                $orderNo = WSTOrderNo();
                $orderScore = 0;
                //创建订单
                $order = [];
//                $order = array_merge($order,$address);

                $order['userName'] = $address['userName'];
                $order['userPhone'] = $address['userPhone'];
                $order['userAddress'] = $address['userAddress'];

                $order['orderNo'] = $orderNo;
                $order['userId'] = $userId;
                $order['shopId'] = $shopOrder['shopId'];
                $order['orderStatus'] = -2;//待付款
                $order['isPay'] = 0;//未支付
                $order['goodsMoney'] = $shopOrder['shopTotalMoney'];//商品总金额

//                $order['deliverType'] = $deliverType;
                $order['deliverMoney'] = $shopOrder['freight'];//运费

                $order['totalMoney'] = $order['goodsMoney']+$order['deliverMoney'];//订单总金额 + 运费
                //是否抵 积分
                if($isScore==1){
                    //积分 判断第一次
                    if(++$i==1){
                        //积分
                        if($mostScores>=$order['scoreTotal']){
//                            $order['scoreTotal'] = $scoreTotal;//店铺商品可抵现积分
                            $mostScores=$mostScores-$order['scoreTotal'];
                        }
                    }else{
                        //积分
                        if($mostScores>0){
                            if($mostScores>=$order['scoreTotal']){
//                                $order['scoreTotal'] = $scoreTotal;//店铺商品可抵现积分
                                $mostScores=$mostScores-$order['scoreTotal'];
                            }else{
                                $order['scoreTotal'] = $mostScores;//店铺商品可抵现积分
                                $mostScores=$mostScores-$order['scoreTotal'];
                            }

                        }else{
                            $order['scoreTotal'] = 0;
                        }
                    }
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
                //是否抵 消费金
                if($isXiaofei==1){
                    //消费金 判断第一次
                    if(++$i==1){
                        //积分
                        if($mostXiaofei>=$order['xiaofeiTotal']){
//                            $order['scoreTotal'] = $scoreTotal;//店铺商品可抵现积分
                            $mostXiaofei=$mostXiaofei-$order['xiaofeiTotal'] ;
                        }
                    }else{
                        //消费金
                        if($mostXiaofei>0){
                            if($mostXiaofei>=$order['xiaofeiTotal']){
//                                $order['scoreTotal'] = $scoreTotal;//店铺商品可抵现积分
                                $mostXiaofei=$mostXiaofei-$order['xiaofeiTotal'] ;
                            }else{
                                $order['xiaofeiTotal'] = $mostScores;//店铺商品可抵现积分
                                $mostXiaofei=$mostXiaofei-$order['xiaofeiTotal'] ;
                            }

                        }else{
                            $order['xiaofeiTotal'] = 0;
                        }
                    }
                    $order['totalMoney']=$order['totalMoney']-$order['xiaofeiTotal'];
                    //给用户减消费金
                    $score = [];
                    $score['userId'] = $userId;
                    $score['money'] = $order['xiaofeiTotal'];
                    $score['dataSrc'] = 1;
                    $score['dataId'] = $orderNo;
                    $score['dataRemarks'] = "购买订单【".$orderNo."】抵现消费金".$order['totalMoney']."个";
//                    $scoreTotal1=$order['scoreTotal'];
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
                $order['orderSrc'] = 3;
                $order['dataFlag'] = 1;
                $order['createTime'] = date('Y-m-d H:i:s');

                $result = Db::name('orders')->insertGetId($order);
//                dump($result);die();

                if(false !== $result){
//                    $orderId = $this->orderId;
                    $orderTotalGoods = [];
                    foreach ($shopOrder['list'] as $gkey =>$goods){
                        //创建订单商品记录
                        $orderGgoods = [];
                        $orderGoods['orderId'] = $result;
                        $orderGoods['orderNo'] = $orderNo;//订单号 存订单商品表
                        $orderGoods['goodsId'] = $goods['goodsId'];
                        $orderGoods['goodsNum'] = $goods['cartNum'];
                        $orderGoods['goodsPrice'] = $goods['shopPrice'];
                        $orderGoods['goodsSpecId'] = $goods['goodsSpecId'];
//                        if(!empty($goods['specNames'])){
//                            $specNams = [];
//                            foreach ($goods['specNames'] as $pkey =>$spec){
//                                $specNams[] = $spec['catName'].'：'.$spec['itemName'];
//                            }
//                            $orderGoods['goodsSpecNames'] = implode('@@_@@',$specNams);
//                        }else{
//                            $orderGoods['goodsSpecNames'] = '';
//                        }
                        $orderGoods['goodsName'] = $goods['goodsName'];
                        $orderGoods['goodsImg'] = $goods['goodsImg'];
//                        $orderGoods['commissionRate'] = WSTGoodsCommissionRate($goods['goodsCatId']);
                        $orderTotalGoods[] = $orderGoods;
                        //修改库存
//                        if($goods['goodsSpecId']>0){
//                            Db::name('goods_specs')->where('id',$goods['goodsSpecId'])->setDec('specStock',$goods['cartNum']);
//                        }
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
            }
            //删除已选的购物车商品
            Db::name('carts')->where(['userId'=>$userId,'isCheck'=>1])->delete();
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

    /**
     * 立即购买页
     */
    public function orderNowShow(){
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
            ->where($where)
            ->field('g.goodsId,g.shopId,g.goodsName,g.shopPrice,g.goodsStock,g.isSpec,g.goodsImg,g.scoresScale,g.xiaofeiScale')
            ->find();
        //购买商品数
        $rs['buyNum']=$buyNum;
        //总钱数
        $goodsTotalMoney = 0;
        $goodsTotalMoney = $rs['shopPrice'] * $rs['buyNum'];
        //最多抵现积分
        $mostScores = floor($rs['shopPrice'] * $rs['scoresScale']* $rs['buyNum']);
        //最多抵现消费金
        $mostXiaofei =  floor($rs['shopPrice'] * $rs['xiaofeiScale']* $rs['buyNum']);
        $rs['goodsImg'] = Config::get('site_domain').'/'.WSTImg($rs['goodsImg'],3);
        //商品列表 转 店铺对应商品
        $store_group=[];
        $shopName=Db::name('shops')->where(['shopId'=>$rs['shopId']])->find();
        //积分
        $userScore=Db::name('users')->where(['userId'=>$userId])->find();
        $store_group['shopId']=$shopName['shopId'];
        $store_group['shopName']=$shopName['shopName'];
        $store_group['goodsTotalMoney']=sprintf("%.2f", $goodsTotalMoney);

        $store_group['userScore']=$mostScores>=$userScore['userScore']?$userScore['userScore']:$mostScores;//积分
        $store_group['userXiaofeiMoney']=$mostXiaofei>=$userScore['userXiaofeiMoney']?$userScore['userXiaofeiMoney']:$mostXiaofei;//消费金
        $store_group['list']=$rs;

        return SZXReturn($this->message[1],"1",$store_group);

    }
    //立即购买-结算
    public function orderNow(){
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
            ->where($where)
            ->field('g.goodsId,g.shopId,g.goodsName,g.shopPrice,g.goodsStock,g.isSpec,g.goodsImg,g.scoresScale,g.xiaofeiScale')
            ->find();
//        if(empty($rs))return SZXReturn($this->message[7],"7");

        $store_group = array();
        $shopName=Db::name('shops')->where(['shopId'=>$rs['shopId']])->find();
        $store_group['shopId']=$shopName['shopId'];
        $store_group['shopName']=$shopName['shopName'];
        $store_group['freight']=$shopName['freight'];//默认运费
        $store_group['shopTotalMoney']=$rs['shopPrice'] * $buyNum;//店铺商品总价
        $store_group['list']=$rs;
        //店铺商品可抵现积分
        $scoreTotal=0;
        //店铺商品可抵现消费金
        $xiaofeiTotal=0;
        $scoreTotal =  floor($rs['shopPrice'] * $rs['scoresScale']* $rs['buyNum']);
        $xiaofeiTotal = floor($rs['shopPrice'] * $rs['xiaofeiScale']* $rs['buyNum']);
        //商品总钱数-最多抵现积分
//        if($isScore==1){
//            $goodsTotalMoney=$goodsTotalMoney-$mostScores;
//        }
//        //商品总钱数-最多抵现消费金
//        if($isScore==1){
//            $goodsTotalMoney=$goodsTotalMoney-$mostXiaofei;
//        }
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
            //积分
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
                $score['dataRemarks'] = "购买订单【".$orderNo."】抵现积分".$order['totalMoney']."个";
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
            $result = Db::name('orders')->insertGetId($order);
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