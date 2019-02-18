<?php
namespace app\api\controller;
use think\Db;
use think\Loader;
use think\Request;
/*
 * User: 李金凤
 * tel: 18845077917
 * 微信支付回调
 */
class Wechat extends  \think\Controller{
    //微信支付回调地址
    public function notifyurl(){

        Loader::import('WeChat.WxPayApi',EXTEND_PATH,'.php');
        Loader::import('WeChat.WxPayConfig',EXTEND_PATH,'.php');
        Loader::import('WeChat.WxPayData',EXTEND_PATH,'.php');
        Loader::import('WeChat.WxPayNotify',EXTEND_PATH,'.php');
        Loader::import('WeChat.log',EXTEND_PATH,'.php');


        // 初始化日志
        $logHandler = new \CLogFileHandler("logs/" . date('Y-m-d') . '.log');
        $log = \Log::Init($logHandler,15);
        $log->DEBUG("支付回调开始！");
        $Notify = new \WxPayNotify();
       
        $Notify->Handle(true);
        //$log->DEBUG("_REQUEST=======".$_REQUEST['attach']);
        $log->DEBUG("支付回调结束！");

    }
}