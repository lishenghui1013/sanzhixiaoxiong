<?php

namespace wstmart\app\controller;

use think\Config;
use think\Db;
/*
 * 红包控制器
 */
header('Access-Control-Allow-Origin:*');
class Hongbao extends Base {
    //发红包
    public function faHongBao(){
       $userId=input("post.userId");
       //红包金额
       $originalMoney=input("post.originalMoney");
       //红包数量
       $Num=input('post.Num');
       //经度
       $longitude=input('post.longitude');
       //纬度
       $latitude=input('post.latitude');
       Db::startTrans();
       try{
           //扣红包比例
           $object=Db::name('sys_configs')->where(['fieldCode'=>"Hbconfigs"])->value('fieldValue');
           $data=[
               'userId'=>$userId,
               'originalMoney'=>$originalMoney,
               'payableAmount'=>$originalMoney*(1-$object),
               'deductMoney'=>$originalMoney*$object,
               'Proportion'=>$object,
               'Num'=>$Num,
               'longitude'=>$longitude,
               'latitude'=>$latitude,
               'creationTime'=>date('Y-m-d H:i:s'),
           ];
           $hbid=Db::name('hongbao')->insertGetId($data);
           //生成 红包
           $money = round(@$originalMoney*(1-$object), 2) ?: 0;    // 红包大小
           $num   = intval(@$Num) ?: 1;      // 红包个数
           $min   = round(@0, 2) ?: 0.01; // 单个红包最低金额
           $ret = $this->lottery($money, $num, $min);
           if (!is_array($ret)) {
               exit($ret.PHP_EOL);
           }
           $data=[];
           foreach($ret as $k=>$v){
               $data[$k]['randomMoney']=$v;
               $data[$k]['hbId']=$hbid;
           }
           Db::name('hb_details')->insertAll($data);

           //给用户增加积分
           $score = [];
           $score['userId'] = $userId;
           $score['score'] = $originalMoney*$object;
           $score['dataSrc'] = 5;
           $score['dataId'] = $hbid;
           $score['dataRemarks'] = "发红包【".$originalMoney."元】获得积分".$score['score']."个";
           $score['scoreType'] = 1;
           $score['createTime'] = date('Y-m-d H:i:s');
           model('UserScores')->save($score);
           // 增加用户积分
           model('Users')->where("userId=$userId")->setInc('userScore',$score['score']);
           // 用户总积分
           model('Users')->where("userId=$userId")->setInc('userTotalScore',$score['score']);
           $date=date('Y-m-d');
           //添加当日累计扣除红包金额
           $re=Db::name('hbk_total')->where(['date'=>$date])->find();
           if($re){
               Db::name('hbk_total')->where(['date'=>$date])->setInc('money',$score['score']);
           }else{
               Db::name('hbk_total')->insert(['date'=>$date,'money'=>$score['score']]);
           }
           Db::commit();
           return SZXReturn($this->message[1],"1");
       }catch(\Exception $e){
           Db::rollback();
           return SZXReturn($this->message[2],"2");
       }
    }
    /*第三个 红包算法*/
    // 产生一个随机浮点数
    public function random_float($min = 0, $max = 1)
    {
        return round($min + mt_rand() / mt_getrandmax() * ($max - $min), 2);
    }
    // 微信随机红包模拟算法
    public function lottery($sum_money, $num, $min_money = 0.01)
    {
        if ($sum_money < $num * $min_money) {
            return SZXReturn($this->message[11],"11");
        }

        $list = [];
        for ($i = 1; $i <= $num; $i++) {
            // 剩余的可分配金额，需要确保剩下的人每人都至少可以拿到保底的钱
            $remain = $sum_money - array_sum($list) - ($num - $i + 1) * $min_money;

            if ($i < $num) {  // 前面的人随机获得
                // 每轮抽取的金额范围：0 至 剩余金额平均值的两倍
                $get = $this->random_float(0, $remain / ($num - $i + 1) * 2);
            } else {  // 最后一个人拿全部剩下的
                $get = $remain;
            }

            // 最后再将每个人保底的钱加上
            $list[] = round(round($get, 2) + $min_money, 2);
        }

        return $list;
    }

    /*
     *参数说明：
     *$lng  经度
     *$lat   纬度
     *$distance  周边半径  默认是500米（0.5Km）
     */
    public function returnSquarePoint($lng, $lat,$distance = 0.5) {
        $dlng =  2 * asin(sin($distance / (2 * 6371)) / cos(deg2rad($lat)));
        $dlng = rad2deg($dlng);
        $dlat = $distance/6371;//地球半径，平均半径为6371km
        $dlat = rad2deg($dlat);

        return array(
            'left-top'=>array('lat'=>$lat + $dlat,'lng'=>$lng-$dlng),
            'right-top'=>array('lat'=>$lat + $dlat, 'lng'=>$lng + $dlng),
            'left-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng - $dlng),
            'right-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng + $dlng)
        );
    }
    //附近的红包
    //接收起点经纬度 $longitude, $latitude
    public function nearby() {
        $userId=input('post.userId');
        $longitude=input('post.longitude');
        $latitude=input('post.latitude');
        $array = $this->returnSquarePoint($longitude, $latitude);
        $map = array(
            'latitude' => array(array('EGT',$array['right-bottom']['lat']),array('ELT',$array['left-top']['lat']),'and'),
            'longitude' => array(array('EGT',$array['left-top']['lng']),array('ELT',$array['right-bottom']['lng']),'and'),
        );
        $where['Surplus']=['neq',0];
        $basic_equipment = db("hongbao");
        $data = $basic_equipment->where($map)->select();
        foreach($data as $k=>$v){
            $data1=Db::name('hb_details')->where(['hbId'=>$v['id'],'userId'=>$userId])->find();
            if(!empty($data1)){
                usset($data[$k]);
            }
        }
//        echo $basic_equipment->getLastSql(); die(); //打印thinkPHP中对SQL语句
        return SZXReturn($this->message[1],"1",$data);
    }
    //领红包
    public function Bonus(){
        $userId=input('post.userId');
        //红包id
        $HBId=input('post.HBId');
        $data1=Db::name('hb_details')->where(['hbId'=>$HBId,'userId'=>$userId])->find();
        if($data1)return SZXReturn($this->message[12],"12");
        $data=Db::name('hb_details')->where(['hbId'=>$HBId,'isStatus'=>-1])->find();
        if(empty($data)) return SZXReturn($this->message[13],"13");
        Db::startTrans();
        try{
            $data=Db::name('hb_details')->where(['hbId'=>$HBId,'isStatus'=>-1])->find();
            Db::name('hb_details')->where(['id'=>$data['id']])->update(['isStatus'=>1,'userId'=>$userId]);
            // 可领红包数  字段减 1
            Db::table('hongbao')->where('id', $HBId)->setDec('Surplus');
            //消费股
            Db::name('users')->where(['userId'=>$userId])->setInc('userConsumerStocks', $data['randomMoney']);
            $data2=[
                'userId'=>$userId,
                'money'=>$data['randomMoney'],
                'dataSrc'=>6,
                'dataId'=>$data['id'],
                'dataRemarks'=>"领红包获得消费股".$data['randomMoney']."个",
                'moneyType'=>1,
                'createTime'=>date("Y-m-d H:i:s")
            ];
            Db::name('user_consumer_stocks')->insert($data2);
            Db::commit();
            return SZXReturn($this->message[1],"1",$data);
        }catch(\Exception $e){
            Db::rollback();
            return SZXReturn($this->message[2],"2");
        }
    }



}