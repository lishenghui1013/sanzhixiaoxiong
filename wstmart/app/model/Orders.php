<?php
namespace wstmart\app\model;
use think\Model;
use think\Db;
use think\Config;

class Orders extends Model{
    /**
     * 获取用户订单列表
     */
    public function userOrdersByPage($orderStatus=-10,$isAppraise = -1){
        $userId = (int)input('post.userId');
        $orderNo = input('post.orderNo');
        $shopName = input('post.shopName');
        $isRefund = (int)input('post.isRefund',-1);
        $where = ['o.userId'=>$userId,'o.dataFlag'=>1];
        if($orderStatus!=-10){
            if(is_array($orderStatus)){
                $where['orderStatus'] = ['in',$orderStatus];
            }else{
                $where['orderStatus'] = $orderStatus;
            }
        }
        if($isAppraise!=-1)$where['isAppraise'] = $isAppraise;//是否点评
        if($orderNo!=''){
            $where['o.orderNo'] = ['like',"%$orderNo%"];
        }
        if($shopName != ''){
            $where['s.shopName'] = ['like',"%$shopName%"];
        }
        if(in_array($isRefund,[0,1])){
            $where['isRefund'] = $isRefund;//是否退款
        }
        //普通商品订单
        $page = $this->alias('o')->join('__SHOPS__ s','o.shopId=s.shopId','left')
            ->join('__ORDER_COMPLAINS__ oc','oc.orderId=o.orderId','left')
            ->join('__ORDER_REFUNDS__ orf','orf.orderId=o.orderId and orf.refundStatus!=-1','left')
            ->where($where)
            ->field('o.orderId,o.orderNo,s.shopName,s.shopId,s.shopQQ,s.shopWangWang,o.goodsMoney,o.totalMoney,o.realTotalMoney,
		              o.orderStatus,o.deliverType,deliverMoney,isPay,payType,payFrom,o.orderStatus,needPay,isAppraise,isRefund,orderSrc,o.createTime,oc.complainId,orf.id refundId')
            ->order('o.createTime', 'desc')
//            ->paginate(input('pagesize/d'))->toArray();
            ->select();
        //添加拼团订单标识
        foreach($page as $k=>$v){
            $page[$k]['isSpell']=-1;
        }
        //拼团商品订单
        $pageSpell = Db::name('spell_orders')->alias('o')->join('__SHOPS__ s','o.shopId=s.shopId','left')
            ->join('__ORDER_COMPLAINS__ oc','oc.orderId=o.orderId','left')
            ->join('__ORDER_REFUNDS__ orf','orf.orderId=o.orderId and orf.refundStatus!=-1','left')
            ->where($where)
            ->field('o.orderId,o.orderNo,s.shopName,s.shopId,s.shopQQ,s.shopWangWang,o.goodsMoney,o.totalMoney,o.realTotalMoney,
		              o.orderStatus,o.deliverType,deliverMoney,isPay,payType,payFrom,o.orderStatus,needPay,isAppraise,isRefund,orderSrc,o.createTime,oc.complainId,orf.id refundId')
            ->order('o.createTime', 'desc')
//            ->paginate(input('pagesize/d'))->toArray();
            ->select();
        //添加拼团订单标识
        foreach($pageSpell as $k=>$v){
            $pageSpell[$k]['isSpell']=1;
        }
        $page = array_merge($page, $pageSpell);
//       return $page;die();
        if(count($page)>0){
            $orderIds = [];
            foreach ($page as $v){
                $orderIds[] = $v['orderNo'];
            }
            $goods = Db::name('order_goods')->where('orderNo','in',$orderIds)->select();

            $goodsMap = [];
            foreach ($goods as $v){
                $v['goodsSpecNames'] = str_replace('@@_@@','、',$v['goodsSpecNames']);
                $v['goodsImg'] = Config::get('site_domain').'/'.$v['goodsImg'];
                $goodsMap[$v['orderNo']][] = $v;
            }
//            return $goodsMap;die();

            foreach ($page as $key => $v){
                $page[$key]['list'] = $goodsMap[$v['orderNo']];
                $page[$key]['orderGoodsNum'] = count($goodsMap[$v['orderNo']]);
                $page[$key]['isComplain'] = 1;
                if(($v['complainId']=='') && ($v['payType']==0 || ($v['payType']==1 && $v['orderStatus']!=2))){
                    $$page[$key]['isComplain'] = '';
                }
//                $page[$key]['payTypeName'] = WSTLangPayType($v['payType']);
                $page[$key]['deliverType'] = WSTLangDeliverType($v['deliverType']==1);
                $page[$key]['status'] = WSTLangOrderStatus($v['orderStatus']);
            }
        }
        //按照某个字段降序排列
        $page = json_decode(json_encode($page),true);
        //SORT_DESC SORT_ASC
        array_multisort(array_column($page,'createTime'),SORT_ASC,$page);
        return $page;
    }
}