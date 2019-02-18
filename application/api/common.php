<?php


//------------------------

// 公共函数

//-------------------------


use think\Session;

use think\Response;

use think\Request;

use think\Url;

use think\Db;
use think\Cache;
use think\Config;

/**
 * CURLFILE 兼容性处理 php < 5.5
 * 一定不要修改、删除，否则 curl 可能无法上传文件
 */
/*【详情的方法 web页】*/
function make_show($data)
{
    echo '<meta http-equiv="Content-Type" content="textml; charset=utf-8" />';
    echo '<meta http-equiv="Cache-Control" content="no-cache" />';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />';
    echo '<meta name="apple-mobile-web-app-capable" content="yes" />';
    echo '<meta name="apple-mobile-web-app-status-bar-style" content="black" />';
    echo '<meta name="format-detection" content="telephone=yes" />';

    echo '<style type="text/css">  #divs{ word-wrap: break-word; word-break: normal; width: 100%;} img{width: 100% !important;}</style>';
    echo "<div id='divs'>" . htmlspecialchars_decode($data) . "</div>";
}
// 将图片🏠img转数组
function toImgApi($picarr){
          if (!empty($picarr)) {
              $picarr = json_decode($picarr,true);
                    $arr = [];
                    foreach ($picarr as $file) {
                              $arr[]['img']= $file;
                    }
                    $request['picarr'] = serialize($arr);
          } else {
                    $request['picarr'] = '';
          }
    return $request['picarr'];
}

 function get_code($mobile,$type)

{
    $code = Cache::get('sms' . $type . '_time_' . $mobile);
    if ($code) {
        return ['code' => 400, 'message' => '操作频繁，请稍后再发'];
    }
    $smsVerify = Cache::get('sms_verify_' . $type . '_time_' . $mobile);

    if ($smsVerify) return ['code' => 400, 'message' => '60s内只能发送一次'];
    $codes = rand(100000, 999999);
    Cache::set('sms_verify_' . $type . '_time_' . $mobile, $code, 60); // 短信请求有效期
    Cache::set('sms_verify_' . $type . '_' . $mobile, $code, 900);     // 短信保存有效期
    header('Content-Type: text/plain; charset=utf-8');
    $ch = curl_init();
    $post_data = array(
        "account" => "sdk_wantao123",
        "password" => "wantao456",
        "destmobile" => $mobile,
        "msgText" => "尊敬用户,您的验证码为" . $codes . "【万淘设备】" ,
        "sendDateTime" => ""
    );

    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $post_data = http_build_query($post_data);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_URL, 'http://www.jianzhou.sh.cn/JianzhouSMSWSServer/http/sendBatchMessage');

    if (curl_exec($ch) > 0) return ['code' => 200, 'message' => '发送成功', 'data' => ['phone' => $mobile, 'code' => $codes]];
}


function logistics($typeCom,$typeNu){
    $AppKey='29833628d495d7a5';//请将XXXXXX替换成您在http://kuaidi100.com/app/reg.html申请到的KEY
    $url ='http://api.kuaidi100.com/api?id='.$AppKey.'&com='.$typeCom.'&nu='.$typeNu.'&show=0&muti=1&order=desc';

//请勿删除变量$powered 的信息，否者本站将不再为你提供快递接口服务。
    $powered = '查询数据由：<a href="http://kuaidi100.com" target="_blank">KuaiDi100.Com （快递100）</a> 网站提供 ';
//优先使用curl模式发送数据
    $curl = curl_init();
    curl_setopt ($curl, CURLOPT_URL, $url);
    curl_setopt ($curl, CURLOPT_HEADER,0);
    curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($curl, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
    curl_setopt ($curl, CURLOPT_TIMEOUT,5);
    $get_content = curl_exec($curl);
    curl_close ($curl);
    return $get_content;
}






