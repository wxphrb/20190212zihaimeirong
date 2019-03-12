<?php// +----------------------------------------------------------------------// | ThinkPHP [ WE CAN DO IT JUST THINK ]// +----------------------------------------------------------------------// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.// +----------------------------------------------------------------------// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )// +----------------------------------------------------------------------// | Author: 流年 <liu21st@gmail.com>// +----------------------------------------------------------------------function wxp_url_code($cookieHostUrl){	$if = 0;	if(strpos($cookieHostUrl,'%') !== false){ //处理经过urlencode的字符串		$if = 1;	}	return $if;}/*调试打断*/function wcc($var){    echo "<pre>";    var_dump($var);    echo "<pre>";    exit;}/*调试继续*/function wxx($var){    echo "<pre>";    var_dump($var);    echo "<pre>";}/*前端多余字符输出*/function wxp_substr($num = 20,$str){    $length = mb_strlen($str);    if($length > $num){        $title_one = mb_substr($str,0,$num,'utf-8');        preg_match_all("/[0-9]|[a-zA-Z]{1}/",$title_one,$arrAl);        $english_num = floor(count($arrAl[0])/3);        $title 	     = mb_substr($str,0,$num+$english_num,'utf-8')."...";    }else{        $title = $str;    }    return $title;}//参数1：访问的URL，参数2：post数据(不填则为GET)，参数3：提交的$cookies,参数4：是否返回$cookiesfunction wxp_curl_request($url,$post='',$cookie='', $returnCookie=0){    $curl = curl_init();    curl_setopt($curl, CURLOPT_URL, $url);    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);    curl_setopt($curl, CURLOPT_AUTOREFERER, 1);    curl_setopt($curl, CURLOPT_REFERER, "http://XXX");    if($post) {        curl_setopt($curl, CURLOPT_POST, 1);        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));    }    if($cookie) {        curl_setopt($curl, CURLOPT_COOKIE, $cookie);    }    curl_setopt($curl, CURLOPT_HEADER, $returnCookie);    curl_setopt($curl, CURLOPT_TIMEOUT, 10);    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);    $data = curl_exec($curl);    if (curl_errno($curl)) {        return curl_error($curl);    }    curl_close($curl);    if($returnCookie){        list($header, $body) = explode("\r\n\r\n", $data, 2);        preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);        $info['cookie']  = substr($matches[1][0], 1);        $info['content'] = $body;        return $info;    }else{        return $data;    }}// 应用公共文件/** * 极光推送 - 提交 */function request_post($url = "", $param = "", $header = "") {    if (empty($url) || empty($param)) {        return false;    }    $postUrl = $url;    $curlPost = $param;    $ch = curl_init(); // 初始化curl    curl_setopt($ch, CURLOPT_URL, $postUrl); // 抓取指定网页    curl_setopt($ch, CURLOPT_HEADER, 0); // 设置header    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 要求结果为字符串且输出到屏幕上    curl_setopt($ch, CURLOPT_POST, 1); // post提交方式    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);    // 增加 HTTP Header（头）里的字段    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    // 终止从服务端进行验证    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);    $data = curl_exec($ch); // 运行curl    curl_close($ch);    return $data;}function request_get($url = "") {    $ch = curl_init();    $timeout = 5;    curl_setopt ($ch, CURLOPT_URL, $url);    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);    $file_contents = curl_exec($ch);    curl_close($ch);    return $file_contents;}/* *AppKey 6aae4ab2b881340faa522357Master Secret 400a9d7b25f7099b38cd52b3 */// push_user('其他人共享线索给我', $v['user_id'] ,'3');//消息   接受人   发送人   活动id   跳转到哪function push_user($title , $uid  ,$activity_id,$type,$message_id) {    $url = 'https://api.jpush.cn/v3/push';    $base64 = base64_encode("b8d8fa5e9ec3b495a4ec8191:9e9d3b2e8264e11f45c92ca4");    $header = array(        "Authorization:Basic $base64",        "Content-Type:application/json"    );    $param = '{				"platform" : "all",   				"audience" : {					"tag"  : [ "' . $uid . '" ]				},				"notification":{					  "android" : {							"alert" : "'.$title.'",							"extras" : {"activity_id":"'.$activity_id.'","type":"'.$type.'",							"message_id":"'.$message_id.'"}				},					  "ios" : {							"alert" : "'.$title.'",							"sound":"default",                            "badge":"+1",							"content-available":true,							"extras" : {"activity_id":"'.$activity_id.'","type":"'.$type.'",							"message_id":"'.$message_id.'"}					  }				},				"options" : {					"time_to_live" : 60,"apns_production":false			 	}			  }';    $res = request_post($url, $param, $header);    $res_arr = json_decode($res, true);    return true;}function push_all($title , $uid  ,$ordersn ) {    $url = 'https://api.jpush.cn/v3/push';    $base64 = base64_encode("51046b79e2ba327b1f329aee:db554e27e39bf0721ff58435");    $header = array(        "Authorization:Basic $base64",        "Content-Type:application/json"    );    $param = '{				"platform" : "all",   				"audience" : "all",				"notification":{					  "android" : {							"alert" : "'.$title.'",							"extras" : {"ordersn":"'.$ordersn.'"}				        },					  "ios" : {							"alert" : "'.$title.'",							"sound":"default",							"content-available":true,							"extras" : {"ordersn":"'.$ordersn.'"}					  }				},				"options" : {					"time_to_live" : 60,"apns_production":false			 	}			  }';    $res = request_post($url, $param, $header);    $res_arr = json_decode($res, true);}// 应用公共文件/** * 模拟tab产生空格 * @param int $step * @param string $string * @param int $size * @return string */function tab($step = 1, $string = ' ', $size = 4){    return str_repeat($string, $size * $step);}/** * crypt + md5 混淆加密 * @param  string $pass 密码明文 */function mcs($pass){    return md5(md5($pass));}