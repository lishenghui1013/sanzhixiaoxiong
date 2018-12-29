<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/30
 * Time: 9:30
 */

namespace wstmart\admin\controller;
/*
 * 发红包扣除比例设置 控制器
 */

use think\Db;

class Hbconfigs extends Base{
    public function index(){
        $object=Db::name('sys_configs')->where(['fieldCode'=>"Hbconfigs"])->field('fieldCode,fieldValue')->find();
        $this->assign("object",$object);
        return $this->fetch("edit");
    }
    /**
     * 编辑
     */
    public function edit(){
       $Hbconfigs=input('post.Hbconfigs');
        Db::startTrans();
        try{
            Db::name('sys_configs')->where(['fieldCode'=>"Hbconfigs"])->update(['fieldValue'=>$Hbconfigs]);
            Db::commit();
            return WSTReturn("操作成功", 1);
        }catch (\Exception $e) {
            Db::rollback();
        }
        return WSTReturn("操作失败", 1);
    }
    //削减消费股
    public function stocksIndex(){
        $object=Db::name('sys_configs')->where(['fieldCode'=>"isReduceStocks"])->field('fieldCode,fieldValue')->find();
        $this->assign("object",$object);
        return $this->fetch("edit_stocks");
    }
    /**
     * 消费股编辑
     */
    public function stocksEdit(){
        $isReduceStocks=input('post.isReduceStocks');
        Db::startTrans();
        try{
            Db::name('sys_configs')->where(['fieldCode'=>"isReduceStocks"])->update(['fieldValue'=>$isReduceStocks]);
            Db::commit();
            return WSTReturn("操作成功", 1);
        }catch (\Exception $e) {
            Db::rollback();
        }
        return WSTReturn("操作失败", 1);
    }



}