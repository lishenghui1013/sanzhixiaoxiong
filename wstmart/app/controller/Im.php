<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/20
 * Time: 9:41
 */

namespace wstmart\app\controller;
/*
 *
 * 好友聊天 测试控制器
 */
use think\Log;

header('Access-Control-Allow-Origin:*');
class Im extends Base {
      //请求接口
     public function post_data($data_string,$url){
         $date=date('YmdHis');
         //Authorization是包头验证信息
         $Authorization=base64_encode('8a216da86541f9fd01655a0eb24c0b6b:'.$date);
          //header数据
         $headers = array(
            'Accept:application/json',
            'Content-Type:application/json;charset=utf-8',
            'Content-Length: '.strlen($data_string),
            'Authorization:'.$Authorization,
         );
         //sig参数
         $SigParameter=md5('8a216da86541f9fd01655a0eb24c0b6b'.'daf045db58f1561aad2942e521e44160'.$date);
         //Base URL
         $IMurl="https://imapp.yuntongxun.com:8883/2013-12-26/Application/8a216da86541f9fd01655a0eb24c0b6b/IM/$url?sig=$SigParameter";
         //初始化
         $ch = curl_init();
         //设置post方式提交
         curl_setopt($ch, CURLOPT_POST, 1);
         //设置抓取的url
         curl_setopt($ch, CURLOPT_URL, $IMurl);
         //设置post数据
         curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
         //设置头文件的信息作为数据流输出
         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
         ob_start();
         //执行命令
         curl_exec($ch);
         $return_content = ob_get_contents();
         ob_end_clean();
         $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
         return array('code'=>$return_code, 'result'=>$return_content);
     }
     //测试数据
      public function urlTest(){
         $arr=[1,2,3,4,5,6];
         $result=$this->post_data(json_encode($arr),"getFriends");
         print_r($result);
      }

}