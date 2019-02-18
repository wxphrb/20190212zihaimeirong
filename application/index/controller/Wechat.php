<?php

/** 微信分享 功能配置  author Freepe

 * Created by PhpStorm.

 * User: Administrator

 * Date: 18-8-24

 * Time: 下午3:36

 */

namespace app\home\controller;



use think\Controller;

use think\Request;



include_once ROOT_PATH.'/extend/Wexin_share/jssdk.php';

class Wechat extends Controller

{

    /**

     * 微信分享 功能封装

     */

    public function share(Request $request)

    {

        $url = $request->param('url');



        $jssdk = new \JSSDK("wxa1eb42f645e78e0a","2550ac678ec61ed3123cb843990eb915",$url);

        $sdk = $jssdk->getSignPackage();

//        dump($sdk);

        $share['appId'] = $sdk['appId'];//公众号的唯一标识

        $share['timestamp'] = $sdk['timestamp'];//生成签名的时间戳

        $share['nonceStr'] = $sdk['nonceStr'];//生成签名的随机串

        $share['signature'] = $sdk['signature'];//签名

        $share['url'] = $sdk['url'];//分享地址

        return json($share);

    }

}

