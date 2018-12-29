<?php
namespace wstmart\app\controller;

use think\Config;
use think\Db;
/*
 * app首页
 */
header('Access-Control-Allow-Origin:*');
class Index extends Base {
    public function index()
    {
       echo  mktime(0, 0, 0, date('m'), date('d'), date('Y')-1);;
//        $ip = request()->ip();
//        echo $ip;die();
//        echo $_SERVER['HTTP_HOST'];die();
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
    }
    //测试数据
    public function Test(){
//        $test=new Test;
//        $a=$test->index();
//        echo $a;die();
/*        //测试（1）随机红包
        $total=1;//红包总金额
        $num=51;// 分成10个红包，支持10人随机领取
        $min=0.01;//每个人最少能收到0.01元
        $data=[];
        for ($i=1;$i<$num;$i++)
        {
            $safe_total=($total-($num-$i)*$min)/($num-$i);//随机安全上限
            $money=mt_rand($min*100,$safe_total*100)/100;
            $total=$total-$money;

            echo '第'.$i.'个红包：'.$money.' 元，余额：'.$total.' 元 '.'<br/>';
            $data[$i]=[
                'money'=>$money,
                'total'=>$total,
            ];
        }
        echo '第'.$num.'个红包：'.$total.' 元，余额：0 元'.'<br/>';
        $data[$num]=['money'=>$total, 'total'=>0,];
        dump($data);*/

//        $i=0;
//        $a=0;
//        $array=array(9,10,11);
//        foreach($array as $k=>$value){
//            if(++$i==0){
//                echo 999;
//            }
//            echo $a++;
//
////            die();
//        }

//        $isOpenScorePay=WSTConf("CONF.isOrderScore");
//        dump($isOpenScorePay);
//        $id=input('post.id',-1);
//        echo $id;
        //取整 舍余
//        echo floor(4.499999999);
//        echo floor(4.999999999);
//        echo time();
//         $data="[{&amp;quot;img&amp;quot;:&amp;quot;upload/pinpai/img/20180926/53143b13b5c1891bb38d09a4d83f37e8.jpg&amp;quot;,&amp;quot;shopPrice&amp;quot;:&amp;quot;6&amp;quot;,&amp;quot;goodsName&amp;quot;:&amp;quot;馒头&amp;quot;,&amp;quot;ptnum&amp;quot;:null},{&amp;quot;img&amp;quot;:&amp;quot;upload/pinpai/img/20180927/c6eb414f0cdb1233ef1e64ff28442f8c.png&amp;quot;,&amp;quot;shopPrice&amp;quot;:&amp;quot;1&amp;quot;,&amp;quot;goodsName&amp;quot;:&amp;quot;1&amp;quot;,&amp;quot;ptnum&amp;quot;:null},{&amp;quot;img&amp;quot;:&amp;quot;upload/pinpai/img/20180927/f53f9713c2168ea5ae4241aa64d80ab8.png&amp;quot;,&amp;quot;shopPrice&amp;quot;:&amp;quot;3&amp;quot;,&amp;quot;goodsName&amp;quot;:&amp;quot;3&amp;quot;,&amp;quot;ptnum&amp;quot;:null},{&amp;quot;img&amp;quot;:&amp;quot;upload/pinpai/img/20180927/大兵49feb619d2ffac4b1612b76c301a6f.png&amp;quot;,&amp;quot;shopPrice&amp;quot;:&amp;quot;11&amp;quot;,&amp;quot;goodsName&amp;quot;:&amp;quot;11&amp;quot;,&amp;quot;ptnum&amp;quot;:null},{&amp;quot;img&amp;quot;:&amp;quot;upload/pinpai/img/20180927/40a567d9012eb7983c605f267283f228.png&amp;quot;,&amp;quot;shopPrice&amp;quot;:&amp;quot;222&amp;quot;,&amp;quot;goodsName&amp;quot;:&amp;quot;222&amp;quot;,&amp;quot;ptnum&amp;quot;:null},{&amp;quot;img&amp;quot;:&amp;quot;upload/pinpai/img/20180927/b3dde61624dee1707c8e0bba64d2fb25.png&amp;quot;,&amp;quot;shopPrice&amp;quot;:&amp;quot;44&amp;quot;,&amp;quot;goodsName&amp;quot;:&amp;quot;44&amp;quot;,&amp;quot;ptnum&amp;quot;:null},{&amp;quot;img&amp;quot;:&amp;quot;upload/pinpai/img/20180927/441764188959ae97f0a57cb8da54012d.png&amp;quot;,&amp;quot;shopPrice&amp;quot;:&amp;quot;666&amp;quot;,&amp;quot;goodsName&amp;quot;:&amp;quot;666&amp;quot;,&amp;quot;ptnum&amp;quot;:null},{&amp;quot;img&amp;quot;:&amp;quot;upload/pinpai/img/20180927/便便af23becc0a5ef7eff44ddbbb5c1f10.png&amp;quot;,&amp;quot;shopPrice&amp;quot;:&amp;quot;77&amp;quot;,&amp;quot;goodsName&amp;quot;:&amp;quot;77&amp;quot;,&amp;quot;ptnum&amp;quot;:null},{&amp;quot;img&amp;quot;:&amp;quot;upload/pinpai/img/20180927/429ccce2adbb026e142e39f6d1e71cc7.png&amp;quot;,&amp;quot;shopPrice&amp;quot;:&amp;quot;48&amp;quot;,&amp;quot;goodsName&amp;quot;:&amp;quot;88&amp;quot;,&amp;quot;ptnum&amp;quot;:null}]";
//        $data=htmlspecialchars_decode(htmlspecialchars_decode($data));
//        $data=htmlspecialchars_decode($data);
//        $data=html_entity_decode($data);

//        $str="<img src='/upload/image/2016-10/58044b6ebf96c.jpg'/>";
//        echo htmlspecialchars($str);
//        echo "<br/><br/>";
//        echo $data;
//        echo strip_tags($data);
//        echo htmlspecialchars($data, ENT_QUOTES);
//        $orderNo = WSTOrderNo();
//        echo $orderNo;
    }
    //商城首页广告
    public function ads(){
        //首页广告
//        $ads = Db::name('ads')->where(['adPositionId'=>35,'dataFlag'=>1])->field('adFile')->select();

        $arr=Db::name('trusteeship')->where(['shopId'=>'-1','trpositionId'=>35,'dataFlag'=>1])->find();
        $time=date('Y-m');
        $isTrusteeship = date('Y-m', strtotime($arr['createTime']));
        //判断是否 在托管期
        if(!empty($arr) && $time<=$arr['endDate'] && $time>$isTrusteeship){
            //首页广告
            $ads = Db::name('ads')
                ->where(['adPositionId'=>35,'dataFlag'=>1,'adName'=>'共享'])
                ->field('	adFile,adId')
                ->select();
        }else{
            //首页广告
            $ads = Db::name('ads')
                ->where(['adPositionId'=>35,'dataFlag'=>1])
                ->where("IFNULL(adName,'') != '共享'")
                ->field('	adFile,adId')
                ->select();
        }
        foreach($ads as $k=>$v){
            $ads[$k]['adFile']=Config::get('site_domain').'/'.$v['adFile'];
        }
//        dump($ads);die();
        return SZXReturn($this->message[1],"1",$ads);
    }
    //商城首页中部广告
    public function adsMiddle(){
        //首页广告
//        $ads = Db::name('ads')->where(['adPositionId'=>42,'dataFlag'=>1])->field('adFile')->select();

        $arr=Db::name('trusteeship')->where(['shopId'=>'-1','trpositionId'=>42,'dataFlag'=>1])->find();
        $time=date('Y-m');
        $isTrusteeship = date('Y-m', strtotime($arr['createTime']));
        //判断是否 在托管期
        if(!empty($arr) && $time<=$arr['endDate'] && $time>$isTrusteeship){
            //首页广告
            $ads = Db::name('ads')
                ->where(['adPositionId'=>42,'dataFlag'=>1,'adName'=>'共享'])
                ->field('	adFile,adId')
                ->select();
        }else{
            //首页广告
            $ads = Db::name('ads')
                ->where(['adPositionId'=>42,'dataFlag'=>1])
                ->where("IFNULL(adName,'') != '共享'")
                ->field('	adFile,adId')
                ->select();
        }
        foreach($ads as $k=>$v){
            $ads[$k]['adFile']=Config::get('site_domain').'/'.$v['adFile'];
        }
//        dump($ads);die();
        return SZXReturn($this->message[1],"1",$ads);
    }



}
