<?php

namespace wstmart\app\controller;
use think\Db;
use think\Config;

/*
 * 用戶地址控制器
 */
header('Access-Control-Allow-Origin:*');
class Useraddress extends Base {
    //地址列表
    public function listQuery(){
        $userId=input('post.userId');
        $where = ['userId'=>(int)$userId,'dataFlag'=>1];
        $rs = Db::name('user_address')->order('isDefault desc')->where($where)->select();
        return SZXReturn($this->message[1],"1",$rs);
    }
    /**
     * 新增
     */
    public function add(){
        $info=input('post.');
        $data=[
            'userId'=>$info['userId'],
            'userName'=>$info['userName'],
            'userPhone'=>$info['userPhone'],
            'userAddress'=>$info['userAddress'],
            'createTime'=>date('Y-m-d H:i:s'),
        ];
        //判断是否第一次添加
        $isDefault=Db::name('user_address')->where(['userId'=>$info['userId'],'dataFlag'=>1])->find();
        //第一次为默认地址
        if(empty($isDefault))$data['isDefault']=1;
        $result=Db::name('user_address')->insert($data);
        if($result){
            return SZXReturn($this->message[1],"1",$result);
        }else{
            return SZXReturn($this->message[2],"2",$result);
        }
    }
    /**
     * 删除
     */
    public function del(){
        $id = input('post.id/d');
        $userId=input('post.userId/d');
        $data = [];
        $data['dataFlag'] = -1;
        $result = Db::name('user_address')->where(['addressId'=>$id,'userId'=>$userId])->update($data);
        $res = Db::name('user_address')->where(['addressId'=>$id,'userId'=>$userId])->find();
        if(false !== $result){
            //当删除默认地址
            if($res['isDefault']==1){
                //当删除默认地址 时设置新默认
                $re=Db::name('user_address')->where(['userId'=>$userId,'dataFlag'=>1])->find();
                if(!empty($re)){
                    $rs = Db::name('user_address')->where(['addressId'=>$re['addressId'],'userId'=>$userId,'dataFlag'=>1])->setField('isDefault',1);
                }
            }
            return SZXReturn($this->message[1],"1",$result);
        }else{
            return SZXReturn($this->message[2],"2",$result);
        }
    }
    /**
     * 设置为默认地址
     */
    public function setDefault(){
        $id = (int)input('post.id');
        $userId = (int)input('post.userId');
        Db::name('user_address')->where(["addressId"=>['<>',$id],'userId'=>$userId] )->setField('isDefault',0);
        $rs = Db::name('user_address')->where("addressId = $id and userId=".$userId)->setField('isDefault',1);
        if(false !== $rs){
            return SZXReturn($this->message[1],"1");
        }else{
            return SZXReturn($this->message[2],"2",$rs);
        }
    }
}