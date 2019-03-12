<?php


namespace app\home\controller;

use think\Controller;
use think\Log;
use think\Request;

class Wxpaynotify
{

    //微信支付回调地址
    public function notifyurl(Request $request)
    {
        $data = $request->param();
        include_once "../extend/WeChat2/WxPayApi.php";
        include_once "../extend/WeChat2/WxPayConfig.php";

        include_once "../extend/WeChat2/WxPayData.php";
        include_once "../extend/WeChat2/WxPayNotify.php";
        include_once "../extend/WeChat2/WxPayLog.php";

        // 初始化日志
        $logHandler = new \CLogFileHandler("../ylogs/" .date('Y-m-d') . '.log');
        $log = \Log::Init($logHandler, 15);
        $log->DEBUG("支付回调开始999！");
        $Notify = new \WxPayNotify();

        $Notify->Handle(true);
        $log->DEBUG("支付回调结束！");
    }

}