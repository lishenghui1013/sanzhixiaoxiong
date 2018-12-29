<?php


namespace wstmart\app\controller;
/*
 * 分红 + 削减消费金和积分
 */
use think\Db;

header('Access-Control-Allow-Origin:*');
class Distribution extends Base{

    //每日分红   定时任务
    public function profit(){
        //消费股 总数
        $sum=Db::name('users')->sum('userConsumerStocks');
        $Yesterday=date("Y-m-d",strtotime("-1 day"));
        $sumMoney=Db::name('hbk_total')->where(['date'=>$Yesterday])->find();
        $users = Db::name('users')
            ->where(["dataFlag"=>1, "userStatus"=>1])
            ->select();
        if($sumMoney>0){
            foreach($users as $k=>$v){
                if($v['userConsumerStocks']>0){
                    $profit=($v['userConsumerStocks']/$sum)*$sumMoney;
                    //修改用户账户金额
                    Db::name('users')->where('userId',$users['userId'])->setInc('userMoney',$profit);
                    //创建资金流水记录
                    $lm = [];
                    $lm['targetType'] = 0;
                    $lm['targetId'] = $users['userId'];
                    $lm['dataId'] = 0;
                    $lm['dataSrc'] = 4;
                    $lm['remark'] = '用户【'.$Yesterday.'号】消费股分红所得¥'.$profit."。";
                    $lm['moneyType'] = 1;
                    $lm['money'] = $profit;
                    $lm['payType'] = 0;
                    $lm['createTime'] = date('Y-m-d H:i:s');
                    model('LogMoneys')->save($lm);
                }
            }
        }
    }
    //消费金 衰减定时任务
    public function moneyAttenuation(){
        //前 第七天
        $date=date('Y-m-d',strtotime("-7 day"));
        //昨天
        $Yesterday=date("Y-m-d",strtotime("-1 day"));
//        echo $date.'--'.$Yesterday;
        $users = Db::name('users')
            ->where(["dataFlag"=>1, "userStatus"=>1])
            ->select();
        foreach ($users as $key=>$value){
            if($value['userXiaofeiMoney']>0){
                $re= Db::name('user_xiaofeimoney')
                    ->where('createTime','between time',[$date,$Yesterday])
                    ->where(['userId'=>$value['userId'],'moneyType'=>1])
                    ->select();
                if(empty($re)){
                    $userXiaofeiMoney=$value['userXiaofeiMoney']*0.75;
                    if($userXiaofeiMoney>=1){
                        $data=[
                            'userId'=>$value['userId'],
                            'money'=>$value['userXiaofeiMoney']-$userXiaofeiMoney,
                            'dataSrc'=>5,
                            'dataId'=>0,
                            'dataRemarks'=>"您七天未获得消费金，自动衰减消费金".$value['userXiaofeiMoney']-$userXiaofeiMoney."个",
                            'moneyType'=>2,
                            'createTime'=>date("Y-m-d H:i:s")
                        ];
                        Db::name('users')->where(['userId'=>$value['userId']])->setDec('userXiaofeiMoney', $data['money']);
                        Db::name('user_xiaofeimoney')->insert($data);
                    }else{
                        $data=[
                            'userId'=>$value['userId'],
                            'money'=>0,
                            'dataSrc'=>5,
                            'dataId'=>0,
                            'dataRemarks'=>"您七天未获得消费金，自动衰减消费金为".'0'."个",
                            'moneyType'=>2,
                            'createTime'=>date("Y-m-d H:i:s")
                        ];
                        Db::name('users')->where(['userId'=>$value['userId']])->update(['userXiaofeiMoney'=>0]);
                        Db::name('user_xiaofeimoney')->insert($data);
                    }
                }
            }
        }
    }
    //积分 衰减定时任务
    public function scoreAttenuation(){
        //前 第七天
        $date=date('Y-m-d',strtotime("-7 day"));
        //昨天
        $Yesterday=date("Y-m-d",strtotime("-1 day"));
        $users = Db::name('users')
            ->where(["dataFlag"=>1, "userStatus"=>1])
            ->select();
        foreach ($users as $key=>$value){
            if($value['userTotalScore']>0){
                $re= Db::name('user_scores')
                    ->where('createTime','between time',[$date,$Yesterday])
                    ->where(['userId'=>$value['userId'],'scoreType'=>1])
                    ->select();
                if(empty($re)){
                    $userTotalScore=$value['userTotalScore']*0.75;
                    if($userTotalScore>=1){
                        $data=[
                            'userId'=>$value['userId'],
                            'score'=>$value['userTotalScore']-$userTotalScore,
                            'dataSrc'=>6,
                            'dataId'=>0,
                            'dataRemarks'=>"您七天未获得积分，自动衰减积分".$value['userTotalScore']-$userTotalScore."个",
                            'scoreType'=>2,
                            'createTime'=>date("Y-m-d H:i:s")
                        ];
                        Db::name('users')->where(['userId'=>$value['userId']])->setDec('userTotalScore', $data['score']);
                        Db::name('user_scores')->insert($data);
                    }else{
                        $data=[
                            'userId'=>$value['userId'],
                            'score'=>0,
                            'dataSrc'=>6,
                            'dataId'=>0,
                            'dataRemarks'=>"您七天未获得积分，自动衰减积分为".'0'."个",
                            'scoreType'=>2,
                            'createTime'=>date("Y-m-d H:i:s")
                        ];
                        Db::name('users')->where(['userId'=>$value['userId']])->update(['userTotalScore'=>0]);
                        Db::name('user_scores')->insert($data);
                    }
                }
            }
        }
    }
    //消费股 衰减定时任务
    public function stocksAttenuation(){
        //前 第七天
        $date=date('Y-m-d',strtotime("-7 day"));
        //昨天
        $Yesterday=date("Y-m-d",strtotime("-1 day"));
//        echo $date.'--'.$Yesterday;
        $users = Db::name('users')
            ->where(["dataFlag"=>1, "userStatus"=>1])
            ->select();
        $object=Db::name('sys_configs')->where(['fieldCode'=>"isReduceStocks"])->field('fieldCode,fieldValue')->find();
        if($object['fieldValue']==1){
            foreach ($users as $key=>$value){
                if($value['userConsumerStocks']>0){
                    $re= Db::name('user_consumer_stocks')
                        ->where('createTime','between time',[$date,$Yesterday])
                        ->where(['userId'=>$value['userId'],'moneyType'=>1])
                        ->select();
                    if(empty($re)){
                        $uuserConsumerStocks=$value['userConsumerStocks']*0.75;
                        if($uuserConsumerStocks>=1){
                            $data=[
                                'userId'=>$value['userId'],
                                'money'=>$value['userConsumerStocks']-$uuserConsumerStocks,
                                'dataSrc'=>7,
                                'dataId'=>0,
                                'dataRemarks'=>"您七天未获得消费股，自动衰减消费股".$value['userConsumerStocks']-$uuserConsumerStocks."个",
                                'moneyType'=>2,
                                'createTime'=>date("Y-m-d H:i:s")
                            ];
                            Db::name('users')->where(['userId'=>$value['userId']])->setDec('userConsumerStocks', $data['money']);
                            Db::name('user_consumer_stocks')->insert($data);
                        }else{
                            $data=[
                                'userId'=>$value['userId'],
                                'money'=>0,
                                'dataSrc'=>7,
                                'dataId'=>0,
                                'dataRemarks'=>"您七天未获得消费金，自动衰减消费金为".'0'."个",
                                'moneyType'=>2,
                                'createTime'=>date("Y-m-d H:i:s")
                            ];
                            Db::name('users')->where(['userId'=>$value['userId']])->update(['userConsumerStocks'=>0]);
                            Db::name('user_consumer_stocks')->insert($data);
                        }
                    }
                }
            }
        }
    }

}