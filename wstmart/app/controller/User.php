<?php

namespace wstmart\app\controller;

use think\Db;
use think\Loader;
use think\Config;
use wstmart\common\model\Users as MUsers;

header('Access-Control-Allow-Origin:*');
class User extends Base{
    //测试
//    public function index(){
//        echo WSTConf('CONF.userLogo');
//    }
    /**
     * 账号-验证登陆
     */
    public function checkLogin(){
        $loginPhone = input("post.loginPhone");
        $loginPwd = input("post.loginPwd");
//        return SZXReturn('','',input("post."));die();
        if(empty($loginPhone&&$loginPwd)){
            return SZXReturn("登陆名或密码不能为空！","-1");
        }
        $rs = Db::name('users')->where("loginName|userEmail|userPhone",$loginPhone)
            ->where(["dataFlag"=>1, "userStatus"=>1])
            ->find();
        $userId = $rs['userId'];
        if($rs['userType']>=1){
            $shop =Db::name('shops')->where(["userId"=>$userId,"dataFlag" =>1])->find();
            if(!empty($shop))$rs = array_merge($shop,$rs);
        }
        if(!empty($rs)){
            $userId = $rs['userId'];
            if ($rs['loginPwd'] != md5($loginPwd . $rs['loginSecret'])) return SZXReturn("密码错误",-1);
            $ip = request()->ip();
            Db::name('users')->where(["userId" => $userId])->update(["lastTime" => date('Y-m-d H:i:s'), "lastIP" => $ip]);
            //记录登录日志
            $data = array();
            $data["userId"] = $userId;
            $data["loginTime"] = date('Y-m-d H:i:s');
            $data["loginIp"] = $ip;
            $data["loginSrc"] = 2;
            Db::name('log_user_logins')->insert($data);
            return SZXReturn("登陆成功","1",$rs);
        }
        return SZXReturn("用户不存在","-1");
    }
    /*
     * 会员注册
     */
    public function toRegist(){
        $data = array();
//        $data['loginName'] = input("post.loginName");
        $data['loginPwd'] = input("post.loginPwd");
//        $data['reUserPwd'] = input("post.reUserPwd");
        $data['userPhone'] = input("post.userPhone");
        $data['mobileCode'] = input("post.mobileCode");
//        $loginName = $data['loginName'];
        foreach ($data as $v){
            if(empty($v)){
                return SZXReturn("注册信息不完整!","-1",$data);
                exit();
            }
        }
        //判断手机号格式是否正确
        $dat=WSTIsPhone($data['userPhone']);
        if(false===$dat){
            return SZXReturn("请您输入正确的手机号！","-1");
        }
        //检测账号是否存在
        $crs = WSTCheckLoginKey($data['userPhone']);
        if($crs['status']!=1) return SZXReturn($crs['msg'],"-1");

        $phone=Db::name('verify_code')->where(['mobile'=>$data['userPhone'],'code'=>$data['mobileCode']])->find();
        if(!empty($phone)){
            $time=time();
            $number=$time-$phone['time'];
            if($number>=300){
                Db::name('verify_code')->where(['mobile'=>$data['userPhone']])->delete();
                return SZXReturn("验证码超过5分钟","-1");
            }
        }else{
            return SZXReturn("请输入正确验证码！","-1");
        }
        $userPhone=$data['userPhone'];
//        unset($data['reUserPwd']);
        unset($data['mobileCode']);
        //检测账号，邮箱，手机是否存在
        $data["loginSecret"] = rand(1000,9999);
        $data['loginPwd'] = md5($data['loginPwd'].$data['loginSecret']);
        $data['userType'] = 0;
        $data['userName'] = input("post.userName");
        $data['userQQ'] = "";
        $data['userScore'] = 0;
        $data['createTime'] = date('Y-m-d H:i:s');
        $data['dataFlag'] = 1;
//        dump($data);die();

        Db::startTrans();
        try{
            $userId = Db::name('users')->insertGetId($data);
            if(false !== $userId){
                $data = array();
                $ip = request()->ip();
                $data['lastTime'] = date('Y-m-d H:i:s');
                $data['lastIP'] = $ip;
//                $userId = Db::name('users')->userId;
                Db::name('users')->where(["userId"=>$userId])->update($data);
                //记录登录日志
                $data = array();
                $data["userId"] = $userId;
                $data["loginTime"] = date('Y-m-d H:i:s');
                $data["loginIp"] = $ip;
                Db::name('log_user_logins')->insert($data);
                Db::name('verify_code')->where(['mobile'=>$userPhone])->delete();
                $logindata=Db::name("users")->where(["userId"=>$userId])->find();
                Db::commit();
                return SZXReturn('恭喜您，注册成功',"1",$logindata);
            }
        }catch (\Exception $e) {
            Db::rollback();
        }
        return SZXReturn("注册失败!","-1");
    }
    /**
     * 注册 获取验证码
     */
    public function getPhoneVerifyCode(){
        $userPhone = input("post.userPhone");
        $rs = array();
        if(!WSTIsPhone($userPhone)){
            return SZXReturn("手机号格式不正确!",-1);
            exit();
        }
        $m = new MUsers();
        $rs = $m->checkUserPhone($userPhone);
        if($rs["status"]!=1){
            return SZXReturn("手机号已存在!",-1);
            exit();
        }
        Loader::import('verifyCode.SendTemplateSMS');
        $code = mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9);
        $data['mobile']=$userPhone;
        $data['code']=$code;
        $data['date']=date("Y-m-d H:i:s");
        $data['time']=time();
        Db::name('verify_code')->insert($data);
        sendTemplateSMS($userPhone,array($code),"270907");//手机号码，替换内容数组，模板ID
    }
    //手机登录-获取验证码
    public function phoneVerifyCode(){
        $userPhone = input("post.userPhone");
        $rs = array();
        if(!WSTIsPhone($userPhone)){
            return SZXReturn("手机号格式不正确!",-1);
            exit();
        }
        $m = new MUsers();
        $rs = $m->checkUserPhone($userPhone);
        if($rs["status"]==1){
            return SZXReturn("手机号不存在!",-1);
        }
//        dump($rs);die();

        Loader::import('verifyCode.SendTemplateSMS');
        $code = mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9);
        $data['mobile']=$userPhone;
        $data['code']=$code;
        $data['date']=date("Y-m-d H:i:s");
        $data['time']=time();
        Db::name('verify_code')->insert($data);
        sendTemplateSMS($userPhone,array($code),"270907");//手机号码，替换内容数组，模板ID
    }
    //手机验证码登录
    public function phoneLogin(){
        $loginPhone = input("post.loginPhone");//手机号
        $verifyCode = input("post.verifyCode");//验证码
//        return SZXReturn('','',input("post."));die();
        if(empty($loginPhone&&$verifyCode)){
            return SZXReturn("手机号或验证码不能为空！","-1");
        }
        $rs = Db::name('users')->where("loginName|userEmail|userPhone",$loginPhone)
            ->where(["dataFlag"=>1, "userStatus"=>1])
            ->find();
        $userId = $rs['userId'];
        if($rs['userType']>=1){
            $shop =Db::name('shops')->where(["userId"=>$userId,"dataFlag" =>1])->find();
            if(!empty($shop))$rs = array_merge($shop,$rs);
        }
//        dump($rs);die();
        if(!empty($rs)){
            $userId = $rs['userId'];
            $phone=Db::name('verify_code')->where(['mobile'=>$loginPhone,'code'=>$verifyCode])->find();
            if(!empty($phone)){
                $time=time();
                $number=$time-$phone['time'];
                if($number>=300){
                    Db::name('verify_code')->where(['mobile'=>$loginPhone])->delete();
                    return SZXReturn("验证码超过5分钟","-1");
                }
            }else{
                return SZXReturn("请输入正确验证码！","-1");
            }
            $ip = request()->ip();
            Db::name('users')->where(["userId" => $userId])->update(["lastTime" => date('Y-m-d H:i:s'), "lastIP" => $ip]);
            //记录登录日志
            $data = array();
            $data["userId"] = $userId;
            $data["loginTime"] = date('Y-m-d H:i:s');
            $data["loginIp"] = $ip;
            $data["loginSrc"] = 2;
            Db::name('log_user_logins')->insert($data);
            return SZXReturn("登陆成功","1",$rs);
        }
        return SZXReturn("登录失败！","-1");
    }
    //忘记密码
    public function forgetPass(){
        $loginPhone = input("post.loginPhone");//手机号
        $verifyCode = input("post.verifyCode");//验证码
        $newPwd = input("post.newPwd");//新密码
//        return SZXReturn('','',input("post."));die();
        if(empty($loginPhone&&$verifyCode)){
            return SZXReturn("手机号或验证码不能为空！","-1");
        }
        if(empty($newPwd))SZXReturn("请输入新密码！","-1");
        $rs = Db::name('users')->where("loginName|userEmail|userPhone",$loginPhone)
            ->where(["dataFlag"=>1, "userStatus"=>1])
            ->find();
        $userId = $rs['userId'];
        if($rs['userType']>=1){
            $shop =Db::name('shops')->where(["userId"=>$userId,"dataFlag" =>1])->find();
            if(!empty($shop))$rs = array_merge($shop,$rs);
        }
        if(!empty($rs)){
            $userId = $rs['userId'];
            $phone=Db::name('verify_code')->where(['mobile'=>$loginPhone,'code'=>$verifyCode])->find();
            if(!empty($phone)){
                $time=time();
                $number=$time-$phone['time'];
                if($number>=300){
                    Db::name('verify_code')->where(['mobile'=>$loginPhone])->delete();
                    return SZXReturn("验证码超过5分钟","-1");
                }
            }else{
                return SZXReturn("请输入正确验证码！","-1");
            }
            $newPwd=md5($newPwd.$rs['loginSecret']);
//            $ip = request()->ip();
            Db::name('users')->where(["userId" => $userId])->update(["lastTime" => date('Y-m-d H:i:s'), 'loginPwd'=>$newPwd]);
            //记录登录日志
//            $data = array();
//            $data["userId"] = $userId;
//            $data["loginTime"] = date('Y-m-d H:i:s');
//            $data["loginIp"] = $ip;
//            $data["loginSrc"] = 2;
//            Db::name('log_user_logins')->insert($data);
            return SZXReturn("登陆成功","1",$rs);
        }
        return SZXReturn("登录失败！","-1");
    }

    //头像上传
    public function userPhoto(){
        //用户ID
        $userId=input('post.userId');
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('userphoto');

        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move(ROOT_PATH . 'upload' . DS . 'userphoto');
            if($info){
                // 成功上传后 获取上传信息
                $userphoto= str_replace('\\','/','upload/userphoto/'.$info->getSaveName());

                Db::name('users')->where(['userId'=>$userId])->update(['userPhoto'=>$userphoto]);

                return SZXReturn($this->message[1],"1",Config::get('site_domain').'/'.$userphoto);
            }else{
                // 上传失败获取错误信息
                $aa=$file->getError();
                return SZXReturn($this->message[2],"2",$aa);
            }
        }
    }

    //用戶身份认证-上传身份证照片
    public function renZhengImage(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('image');
        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move(ROOT_PATH . 'upload' . DS . 'idcard');
            if($info){
                // 成功上传后 获取上传信息
                $userphoto= str_replace('\\','/','upload/idcard/'.$info->getSaveName());
                return SZXReturn($this->message[1],"1",$userphoto);
            }else{
                // 上传失败获取错误信息
                $aa=$file->getError();
                return SZXReturn($this->message[2],"2",$aa);
            }
        }
    }
    //用户身份认证-添加
    public function addRenZheng(){
        //用户ID
        $userId=input('post.userId');
        //正面
        $zhengUrl=input('post.zhengUrl');
        //反面
        $fanUrl=input('post.fanUrl');
        $data=[
            'userId'=>$userId,
            'zhengUrl'=>$zhengUrl,
            'fanUrl'=>$fanUrl,
            'createTime'=>date('Y-m-d H:i:s'),
        ];
        //开启事务
        Db::startTrans();
        try{
            Db::name('user_idcard')->insert($data);
            Db::name('users')->where(['userId'=>$userId])->update(['idCardStatus'=>1]);
            //提交事务
            Db::commit();
            return SZXReturn($this->message[1],"1");
        }catch (\Exception $e) {
            //事务回滚
            Db::rollback();
        }
        return SZXReturn($this->message[2],"2");
    }
    //用户身份证认证-状态 0：未认证  1：正在审核  2：认证成功 3：认证失败
    public function idCardStatus(){
        //用户ID
        $userId=input('post.userId');
        $data=Db::name('users')->where(['userId'=>$userId])->value('idCardStatus');
        return SZXReturn($this->message[1],"1",$data);
    }
    //用户-基本信息-头像、电话、级别
    public function userInfo(){
        //用户ID
        $userId=input('post.userId');
        $rs = Db::name("users")->where("userId|loginName|userEmail|userPhone",$userId)
            ->where(["dataFlag"=>1, "userStatus"=>1])
            ->field("userId,loginName,userPhoto,userPhone,userTotalScore")
            ->find();
        //设置默认名称
        if(empty($rs['loginName']))$rs['loginName']="三只小熊";
        //设置默认头像
        if(empty($rs['userPhoto'])){
            $userLogo=Db::name("sys_configs")->where(['configId'=>53,'fieldCode'=>'userLogo'])->value('fieldValue');
            $rs['userPhoto']=Config::get('site_domain').'/'.$userLogo;
        }else{
            $rs['userPhoto']=Config::get('site_domain').'/'.$rs['userPhoto'];
        }
        if(!empty($rs)) {
            $userId = $rs['userId'];
            //获取用户等级
            $rrs = Db::name('user_ranks')->where('startScore', '<=', $rs['userTotalScore'])->where('endScore', '>=', $rs['userTotalScore'])->field('rankId,rankName,userrankImg')->find();
            $rs['rankId'] = $rrs['rankId'];
            $rs['rankName'] = $rrs['rankName'];
            $rs['userrankImg'] = Config::get('site_domain').'/'.$rrs['userrankImg'];
        }
        return SZXReturn($this->message[1],"1",$rs);
    }







}