<?php



//------------------------

// 公共函数

//-------------------------


use think\Session;

use think\Response;

use think\Request;

use think\Url;


/*
$decrypt = 1    加密数据
$decrypt = 0    解密数据
*/
//加密方法
function encryptDecrypt($param, $decrypt = 1){
    if ($param === '' || empty($param)) return '';
    $key = 'param';
    if($decrypt){
        $param = json_encode($param);
        $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $param, MCRYPT_MODE_CBC, md5(md5($key))));
        return $encrypted;
    }else{
        $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($param), MCRYPT_MODE_CBC, md5(md5($key))));
        return json_decode($decrypted,true);
    }
}
/*车型条件*/
function chexing_where($chexing){
    $where = '';
    switch($chexing){
        case 'suv':
            $where = ['like', '%' . $chexing . '%'];
            break;
		case 'SUV':
            $where = ['like', '%' . $chexing . '%'];
            break;
        case '轿车':
            $where = ['like', '%型车%'];
            break;
        case '皮卡':
            $where = ['like', '%' . $chexing . '%'];
            break;
        case '微面':
            $where = ['like', '%' . $chexing . '%'];
            break;
        case '微卡':
            $where = ['like', '%' . $chexing . '%'];
            break;
        case 'mpv':
            $where = ['like', '%' . $chexing . '%'];
            break;
		case 'MPV':
            $where = ['like', '%' . $chexing . '%'];
            break;
    }
    return $where;
}
/*颜色条件*/
function color_where($color)
{
    $where = '';

    $where = ['like', '%' . $color . '%'];

    return $where;
}
/*二手车车型条件*/
function old_chexing_where($chexing){
    $where = '';
    switch($chexing){
        case 'suv':
            $where = ['like', '%' . $chexing . '%'];
            break;
		case 'SUV':
            $where = ['like', '%' . $chexing . '%'];
            break;
        case '轿车':
            $where = ['like', '%' . $chexing . '%'];
            break;
        case '皮卡':
            $where = ['like', '%' . $chexing . '%'];
            break;
        case '微面':
            $where = ['like', '%' . $chexing . '%'];
            break;
        case '微卡':
            $where = ['like', '%' . $chexing . '%'];
            break;
        case 'mpv':
            $where = ['like', '%' . $chexing . '%'];
            break;
		case 'MPV':
            $where = ['like', '%' . $chexing . '%'];
            break;
    }
    return $where;
}
/*排量条件*/
function pailiang_where($param1,$param2){
    $where = '';
        if($param1 == 0){
            $where = ['<',$param2];
        }elseif($param2 == 0){
            $where = ['>',$param1];
        }else {
            $where = ['between', [$param1,$param2]];
        }
    return $where;
}
/*车龄条件*/
function cheling_where($param1,$param2){
    $where = '';
    if($param1 == 0){
        $where = ['<',$param2];
    }elseif($param2 == 0){
        $where = ['>',$param1];
    }else {
        $where = ['between', [$param2.'-00-00',$param1.'-00-00']];
    }
    return $where;
}
/*价格筛选*/
function jiage_where($param1,$param2){
    $where = '';
    if($param1 == 0){
        $where = ['<',$param2];
    }elseif($param2 == 0){
        $where = ['>',$param2];
    }else {
        $where = ['between', [$param1,$param2]];
    }
    return $where;
}
/*二手车排放标准条件*/
function old_paifangbiaozhun_where($param1){
    $where = '';
    switch($param1){
        case 1:
            $where = ['in',['国一','国二','国三','国四','国五']];
            break;
        case 2:
            $where = ['in',['国二','国三','国四','国五']];
            break;
        case 3:
            $where = ['in',['国三','国四','国五']];
            break;
        case 4:
            $where = ['in',['国四','国五']];
            break;
        case 5:
            $where = ['in',['国五']];
            break;

    }
    return $where;
}
/*排放标准条件*/
function paifangbiaozhun_where($param1){
    $where = '';
    switch($param1){
        case 1:
            $where = ['in',['国II','国III','国III+OBD','国IV','国IV(国V)','国IV(国V)+OBD','国IV+OBD','国IV/京V','国IV/国V','国V','国V(国VI)','国V+OBD','国VI','欧II','欧III','欧III+OBD','欧IV','欧IV+OBD','欧V','欧V+OBD','欧VI']];
            break;
        case 2:
            $where = ['in',['国II','国III','国III+OBD','国IV','国IV(国V)','国IV(国V)+OBD','国IV+OBD','国IV/京V','国IV/国V','国V','国V(国VI)','国V+OBD','国VI','欧II','欧III','欧III+OBD','欧IV','欧IV+OBD','欧V','欧V+OBD','欧VI']];
            break;
        case 3:
            $where = ['in',['国III','国III+OBD','国IV','国IV(国V)','国IV(国V)+OBD','国IV+OBD','国IV/京V','国IV/国V','国V','国V(国VI)','国V+OBD','国VI','欧II','欧III','欧III+OBD','欧IV','欧IV+OBD','欧V','欧V+OBD','欧VI']];
            break;
        case 4:
            $where = ['in',['国IV','国IV(国V)','国IV(国V)+OBD','国IV+OBD','国IV/京V','国IV/国V','国V','国V(国VI)','国V+OBD','国VI','欧IV','欧IV+OBD','欧V','欧V+OBD','欧VI']];
            break;
        case 5:
            $where = ['in',['国IV(国V)','国IV(国V)+OBD','国IV/京V','国IV/国V','国V(国VI)','国V+OBD','国VI','欧V','欧V+OBD','欧VI']];
            break;

    }
    return $where;
}

/*变速箱条件*/
function biansuxiang_where($param1)
{
    $where = '';
    switch ($param1) {
        case 1:
            $where = ['not in', ['4挡手动', '5挡手动', '6挡手动']];
            break;
        case 2:
            $where = ['in', ['4挡手动', '5挡手动', '6挡手动']];
            break;

    }
    return $where;
}
/*变速箱条件*/
function old_biansuxiang_where($param1)
{
    $where = '';
    switch ($param1) {
        case 1:
            $where = ['like', '%自动%'];
            break;
        case 2:
            $where = ['like', '%手动%'];
            break;

    }
    return $where;
}
/*调试继续*/
function wxx($var){
	echo "<pre>";
		var_dump($var);
	echo "<pre>";
}

/*前端多余字符输出*/
function wxp_substr($num = 20,$str){
	
	$length = mb_strlen($str);
	if($length > $num){
		$title_one = mb_substr($str,0,$num,'utf-8');
		preg_match_all("/[0-9]|[a-zA-Z]{1}/",$title_one,$arrAl);
		$english_num = floor(count($arrAl[0])/3);
		$title 	     = mb_substr($str,0,$num+$english_num,'utf-8')."...";
	}else{
		$title = $str;
	}
	

	
	return $title;
}
/*查询是否收藏*/
function is_collection_w($id,$type){
	$is_c = db('collection')->where(['collection_id'=>$id,'type'=>$type])->field('id')->find();
	if($is_c){
		return true;
	}else{
		return false;
	}
}
/*获取原有参数 */
function wxp_param($param){
		$str = '';
		$i   = 0;
		foreach($param as $k=>$v){
				if ($i==0) {
						$str .= "?".$k.'='.$v;
				}else{
					  $str .= '&'.$k.'='.$v;
				}
				$i++;
		}
		return $str;
}
/**
 * 防sql注入字符串转义
 * @param $content 要转义内容
 * @return array|string
 */
function escapeString($content) {
	$pattern = "/(select[\s])|(insert[\s])|(update[\s])|(delete[\s])|(from[\s])|(where[\s])|(drop[\s])/i";
	if (is_array($content)) {
		foreach ($content as $key=>$value) {
			$content[$key] = addslashes(trim($value));
			if(preg_match($pattern,$content[$key])) {
				$content[$key] = '';
			}
		}
	} else {
		$content=addslashes(trim($content));
		if(preg_match($pattern,$content)) {
			$content = '';
		}
	}
	return $content;
}

/**
 * 安全过滤函数
 *
 * @param $string
 * @return string
 */
function safe_replace($string) {
    $string = str_replace('%20','',$string);
    $string = str_replace('%27','',$string);
    $string = str_replace('%2527','',$string);
    $string = str_replace('*','',$string);
    $string = str_replace('"','&quot;',$string);
    $string = str_replace("'",'',$string);
    $string = str_replace('"','',$string);
    $string = str_replace(';','',$string);
    $string = str_replace('<','&lt;',$string);
    $string = str_replace('>','&gt;',$string);
    $string = str_replace("{",'',$string);
    $string = str_replace('}','',$string);
    $string = str_replace('\\','',$string);
    return $string;
}
/*去除html标签*/
function cutstr_html($string, $sublen)
{
    $string = strip_tags($string);
    $string = preg_replace ('/\n/is', '', $string);
    $string = preg_replace ('/ |　/is', '', $string);
    $string = preg_replace ('/&nbsp;/is', '', $string);

    preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $string, $t_string);
    if(count($t_string[0]) - 0 > $sublen) $string = join('', array_slice($t_string[0], 0, $sublen))."…";
    else $string = join('', array_slice($t_string[0], 0, $sublen));

    return $string;
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
/*获取客户端真实IP*/
function get_client_ip($type = 0) {
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    $type       =  $type ? 1 : 0;
    static $ip  =   NULL;
    if ($ip !== NULL) return $ip[$type];
    if($_SERVER['HTTP_X_REAL_IP']){//nginx 代理模式下，获取客户端真实IP
        $ip=$_SERVER['HTTP_X_REAL_IP'];
    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {//客户端的ip
        $ip     =   $_SERVER['HTTP_CLIENT_IP'];
    }elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {//浏览当前页面的用户计算机的网关
        $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos    =   array_search('unknown',$arr);
        if(false !== $pos) unset($arr[$pos]);
        $ip     =   trim($arr[0]);
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];//浏览当前页面的用户计算机的ip地址
    }else{
        $ip=$_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u",ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}

/**

 * CURLFILE 兼容性处理 php < 5.5

 * 一定不要修改、删除，否则 curl 可能无法上传文件

 */

if (!function_exists('curl_file_create')) {

    function curl_file_create($filename, $mimetype = '', $postname = '')

    {

        return "@$filename;filename="

            . ($postname ?: basename($filename))

            . ($mimetype ? ";type=$mimetype" : '');

    }

}



//控制器中 获取验证码

function captcha_img()

{

    //Session::init(['prefix'=> 'wll_','type'=> '','auto_start' => true]);

    //$captcha = new \think\Captcha(86,48,4);

    //echo $captcha->showImg();

    //Session::set('code',$captcha->getCaptcha());

//    echo 1;

//    exit;

}



/**

 * 用于图片展示

 * @param int $status 0|1|-1状态

 * @param int $id 对象id

 * @param string $field 字段，默认id

 * @param string $controller 默认当前控制器

 * @return string

 */

function show_img($picurl, $width = 'auto', $height = '30px')

{

    if (empty($picurl)) {

        $ret = '<img src="__CONSOLE__/images/default.jpg" class="img-rounded" width="' . $width . '" height="' . $height . '">';

    } else {

        $ret = '<img src="__ROOT__' . $picurl . '" class="img-rounded" width="' . $width . '" height="' . $height . '">';

    }



    return $ret;

}



function show_toux($picurl, $width = 'auto', $height = '60px')

{

    if (empty($picurl)) {

        $ret = '<img src="__CONSOLE__/images/default.jpg" class="img-rounded" width="' . $width . '" height="' . $height . '">';

    } else {

        $ret = '<img src="__ROOT__' . $picurl . '" class="img-rounded" width="' . $width . '" height="' . $height . '">';

    }



    return $ret;

}







function show_logo($picurl, $width = 'px;', $height = '30px')

{

    if (empty($picurl)) {

        $ret = '<img src="__CONSOLE__/images/default.jpg" class="img-rounded" width="' . $width . '" height="' . $height . '">';

    } else {

        $ret = '<img src="__ROOT__' . $picurl . '" class="img-rounded" width="' . $width . '" height="' . $height . '">';

    }



    return $ret;

}



/**

 * 用于ID 获取名称

 * @param int $status 0|1|-1状态

 * @param int $id 对象id

 * @param string $field 字段，默认id

 * @param string $controller 默认当前控制器

 * @return string

 */

function show_title($id, $name)

{

    $title = db($name)->where('id', $id)->value('title');

    return $title;

}





/*论坛index显示分类名称*/

function show_type_title($id)

{

    $type_title = db("bbc_type")->where('id', $id)->value('type_title');

    return $type_title;

}



function show_typename($id)

{

    $type_title = db("job_type")->where('id', $id)->value('title');

    return $type_title;

}





function show_username($id)

{

    $title = db("user")->where('id', $id)->value('username');

    if (empty($title)) {

        return "后台发布";

    } else {

        return $title;

    }

}







function level($id)

{

    $title = db('certification_type')->where('id', $id)->value('title');

    return $title;

}



function show_idcard($id)

{

    $idcard = db('certification')->where('user_id', $id)->value('idcard');

    return $idcard;

}





/**

 * 用于操作按钮

 * @param int $status 0|1|-1状态

 * @param int $id 对象id

 * @param string $field 字段，默认id

 * @param string $controller 默认当前控制器

 * @return string

 */

function show_operate($id, $operate = 'delete', $describe = '删 除', $controller = '')

{

    $controller === '' && $controller = Request::instance()->controller();

    $module = Request::instance()->module();



    if ($operate == 'delete') {

        $url = Url::build($module . '/' . $controller . '/' . $operate);

        if ($id == 0) {

            $ret = '<a class="label label-danger" onClick="check_delete(\'' . $url . '\',\'' . $id . '\');"><i class="fa fa-trash-o"></i>&nbsp;&nbsp;' . $describe . '</a>';

        } else {

            $ret = '<a style="text-decoration:none;" class="text text-danger"  onClick="check_delete(\'' . $url . '\',\'' . $id . '\');"><i class="fa fa-trash-o"></i>&nbsp;&nbsp;' . $describe . '</a>';

        }

    } else {

        $url = Url::build($module . '/' . $controller . '/' . $operate, ['id' => $id]);

        $ret = '<a style="text-decoration:none;" href="' . $url . '"><i class="fa fa-pencil"></i>&nbsp;&nbsp;' . $describe . '</a>';



    }



    return $ret;

}





function show_mobile($id)

{

    return    db("user")->where('id',$id)->value('mobile');

}





/*查询商家名称*/

function show_store_name($id)

{

    $title  = db("store")->where('id',$id)->value('title');

    if(empty($title)){

        return "后台商品";

    }else{

        return $title;

    }



}



/**

 * 用于获取orderid排序

 * @param int $id 对象id

 * @param string $field 字段，默认id

 * @param string $controller 默认当前控制器

 * @return string

 */

function get_orderid($id, $field = 'orderid', $controller = '')

{

    //控制器一般与表名一致

    $controller === '' && $controller = Request::instance()->controller();

    //添加情况

    if (empty($id)) {

        $maxorderid = db($controller)->max($field);

        $orderid = isset($maxorderid) ? $maxorderid + 1 : 1;

    } else {

        $orderid = db($controller)->where('id', $id)->value($field);

    }



    return $orderid;

}



/**

 * 用于更新字段操作按钮

 * @param int $status 0|1|-1状态

 * @param int $id 对象id

 * @param string $field 字段，默认id

 * @param string $controller 默认当前控制器

 * @return string

 */

function renew_field($val, $id, $field = 'id', $controller = '')

{

    $controller === '' && $controller = Request::instance()->controller();

    $module = Request::instance()->module();

    $url = Url::build($module . '/' . $controller . '/renewfield');



    $ret = '<a onClick="check_field(\'' . $url . '\',\'' . $id . '\',\'' . $field . '\',\'' . $val . '\');">' . $val . '</a>';



    return $ret;

}



/**

 * 用于显示状态操作按钮

 * @param int $status 0|1|-1状态

 * @param int $id 对象id

 * @param string $field 字段，默认id

 * @param string $controller 默认当前控制器

 * @return string

 */

function show_status($status, $id, $field = 'id', $controller = '')

{

    $controller === '' && $controller = Request::instance()->controller();

    $module = Request::instance()->module();

    $url = Url::build($module . '/' . $controller . '/renewfield');



    switch ($status) {

        // 恢复

        case 0 :

            $ret = '<a onClick="check_state(\'' . $url . '\',\'' . $id . '\',\'status\',\'1\');"><i class = "glyphicon glyphicon-remove-sign text-danger"></i></a>';

            break;

        // 禁用

        case 1 :

            $ret = '<a onClick="check_state(\'' . $url . '\',\'' . $id . '\',\'status\',\'0\');"><i class = "glyphicon glyphicon-ok-sign text-success"></i></a>';

            break;

        case -1 :

            $ret = '<a onClick="check_state(\'' . $url . '\',\'' . $id . '\',\'status\',\'1\');"><i class = "glyphicon glyphicon-remove-sign text-danger"></i></a>';

            break;

    }



    return $ret;

}





/*更改商家热推*/



function show_hot($status, $id, $field = 'id', $controller = '')

{

    $controller === '' && $controller = Request::instance()->controller();

    $module = Request::instance()->module();

    $url = Url::build($module . '/' . $controller . '/renewfield');



    switch ($status) {

        // 恢复

        case 0 :

            $ret = '<a onClick="check_state(\'' . $url . '\',\'' . $id . '\',\'hot\',\'1\');"><i class = "glyphicon glyphicon-remove-sign text-danger"></i></a>';

            break;

        // 禁用

        case 1 :

            $ret = '<a onClick="check_state(\'' . $url . '\',\'' . $id . '\',\'hot\',\'0\');"><i class = "glyphicon glyphicon-ok-sign text-success"></i></a>';

            break;



    }



    return $ret;

}



/**

 * 显示状态

 * @param int $status 0|1|-1

 * @param bool $imageShow true只显示图标|false只显示文字

 * @return string

 */

function get_status($status, $imageShow = true)

{

    switch ($status) {

        case 0 :

            $showText = '禁用';

            $showImg = '<i class = "glyphicon glyphicon-remove-sign text-danger"></i>';

            break;

        case 1 :

            $showText = '正常';

            $showImg = '<i class = "glyphicon glyphicon-remove-sign text-success"></i>';

            break;



    }



    return ($imageShow === true) ? $showImg : $showText;

}



/**

 * 框架内部默认ajax返回

 * @param string $msg 提示信息

 * @param string $redirect 重定向类型 current|parent|''

 * @param string $alert 父层弹框信息

 * @param bool $close 是否关闭当前层

 * @param string $url 重定向地址

 * @param string $data 附加数据

 * @param int $code 错误码

 * @param array $extend 扩展数据

 */

function ajax_return_adv($msg = '操作成功', $redirect = 'parent', $alert = '', $close = false, $url = '', $data = '', $code = 0, $extend = [])

{

    $extend['opt'] = [

        'alert' => $alert,

        'close' => $close,

        'redirect' => $redirect,

        'url' => $url,

    ];



    return ajax_return($data, $msg, $code, $extend);

}



/**

 * 返回错误json信息

 */

function ajax_return_adv_error($msg = '', $code = 1, $redirect = '', $alert = '', $close = false, $url = '', $data = '', $extend = [])

{

    return ajax_return_adv($msg, $alert, $close, $redirect, $url, $data, $code, $extend);

}



/**

 * ajax数据返回，规范格式

 * @param array $data 返回的数据，默认空数组

 * @param string $msg 信息

 * @param int $code 错误码，0-未出现错误|其他出现错误

 * @param array $extend 扩展数据

 */

function ajax_return($data = [], $msg = "", $code = 0, $extend = [])

{

    $ret = ["code" => $code, "msg" => $msg, "data" => $data];

    $ret = array_merge($ret, $extend);



    return Response::create($ret, 'json');

}



/**

 * 返回标准错误json信息

 */

function ajax_return_error($msg = "出现错误", $code = 1, $data = [], $extend = [])

{

    return ajax_return($data, $msg, $code, $extend);

}



/**

 * 从二维数组中取出自己要的KEY值

 * @param  array $arrData

 * @param string $key

 * @param $im true 返回逗号分隔

 * @return array

 */

function filter_value($arrData, $key, $im = false)

{

    $re = [];

    foreach ($arrData as $k => $v) {

        if (isset($v[$key])) $re[] = $v[$key];

    }

    if (!empty($re)) {

        $re = array_flip(array_flip($re));

        sort($re);

    }



    return $im ? implode(',', $re) : $re;

}



/**

 * 重设键，转为array(key=>array())

 * @param array $arr

 * @param string $key

 * @return array

 */

function reset_by_key($arr, $key)

{

    $re = [];

    foreach ($arr as $v) {

        $re[$v[$key]] = $v;

    }



    return $re;

}



/**

 * 节点遍历

 * @param $list

 * @param string $pk

 * @param string $pid

 * @param string $child

 * @param int $root

 * @return array

 */

function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0)

{

    // 创建Tree

    $tree = [];

    if (is_array($list)) {

        // 创建基于主键的数组引用

        $refer = [];

        foreach ($list as $key => $data) {

            $refer[$data[$pk]] =& $list[$key];

        }

        foreach ($list as $key => $data) {

            // 判断是否存在parent

            $parentId = $data[$pid];

            if ($root == $parentId) {

                $tree[] =& $list[$key];

            } else {

                if (isset($refer[$parentId])) {

                    $parent =& $refer[$parentId];

                    $parent[$child][] =& $list[$key];

                }

            }

        }

    }



    return $tree;

}



/**

 * 统一密码加密方式，如需变动直接修改此处

 * @param $password

 * @return string

 */

function password_hash_tp($password)

{

    return hash("md5", trim($password));

}



/**

 * 生成随机字符串

 * @param string $prefix

 * @return string

 */

function get_random($prefix = '')

{

    return $prefix . base_convert(time() * 1000, 10, 36) . "_" . base_convert(microtime(), 10, 36) . uniqid();

}



/**

 * 获取自定义配置

 * @param string|int $name 配置项的key或者value，传key返回value，传value返回key

 * @param string $conf

 * @param bool $key 传递的是否是配置键名，默认是，则返回配置信息

 * @return int|string

 */

function get_conf($name, $conf, $key = true)

{

    $arr = config("conf." . $conf);

    if ($key) return $arr[$name];

    foreach ($arr as $k => $v) {

        if ($v == $name) {

            return $k;

        }

    }

}





/**

 * 多维数组合并（支持多数组）

 * @return array

 */

function array_merge_multi()

{

    $args = func_get_args();

    $array = [];

    foreach ($args as $arg) {

        if (is_array($arg)) {

            foreach ($arg as $k => $v) {

                if (is_array($v)) {

                    $array[$k] = isset($array[$k]) ? $array[$k] : [];

                    $array[$k] = array_merge_multi($array[$k], $v);

                } else {

                    $array[$k] = $v;

                }

            }

        }

    }



    return $array;

}





/**

 * 将list_to_tree的树还原成列表

 * @param array $tree

 * @param string $child

 * @param string $order

 * @param int $level

 * @param null $filter

 * @param array $list

 * @return array

 */

function tree_to_list($tree, $filter = null, $child = '_child', $order = 'id', $level = 0, &$list = [])

{

    if (is_array($tree)) {

        if (!is_callable($filter)) {

            $filter = function (&$refer, $level) {

                $refer['level'] = $level;

            };

        }

        foreach ($tree as $key => $value) {

            $refer = $value;

            unset($refer[$child]);

            $filter($refer, $level);

            $list[] = $refer;

            if (isset($value[$child])) {

                tree_to_list($value[$child], $filter, $child, $order, $level + 1, $list);

            }

        }

    }



    return $list;

}



/**

 * 对查询结果集进行排序

 * @access public

 * @param array $list 查询结果

 * @param string $field 排序的字段名

 * @param array $sortBy 排序类型

 *                      asc正向排序 desc逆向排序 nat自然排序

 * @return array|bool

 */

function list_sort_by($list, $field, $sortBy = 'asc')

{

    if (is_array($list)) {

        $refer = $resultSet = [];

        foreach ($list as $i => $data)

            $refer[$i] = &$data[$field];

        switch ($sortBy) {

            case 'asc': // 正向排序

                asort($refer);

                break;

            case 'desc': // 逆向排序

                arsort($refer);

                break;

            case 'nat': // 自然排序

                natcasesort($refer);

                break;

        }

        foreach ($refer as $key => $val)

            $resultSet[] = &$list[$key];



        return $resultSet;

    }



    return false;

}



/**

 * 格式化字节大小

 * @param  number $size 字节数

 * @param  string $delimiter 数字和单位分隔符

 * @return string            格式化后的带单位的大小

 */

function format_bytes($size, $delimiter = '')

{

    $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

    for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;



    return round($size, 2) . $delimiter . $units[$i];

}





/**

 * 使用3DES加密源数据

 * @param string $oriSource 源数

 * @param string $key 密钥

 * @param string $defaultIV 加解密向量

 * @return string $result   密文

 */

function encrypt($oriSource)

{

    $oriSource = addPKCS7Padding($oriSource);

    $td = mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_CBC, '');

    mcrypt_generic_init($td, '123456781234567812345678', '12345678');

    $result = mcrypt_generic($td, $oriSource);

    $result = base64_encode($result);

    mcrypt_generic_deinit($td);

    mcrypt_module_close($td);

    return $result;

}





/**

 * 使用3DES解密密文

 * @param string $encryptedData 密文

 * @param string $key 密钥

 * @param string $defaultIV 加解密向量

 * @return string $result       解密后的原文

 */

function decrypt($encryptedData)

{



    $td = mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_CBC, '');

    mcrypt_generic_init($td, '123456781234567812345678', '12345678');



    $encryptedData = base64_decode($encryptedData);

    $result = mdecrypt_generic($td, $encryptedData);



    mcrypt_generic_deinit($td);

    mcrypt_module_close($td);

    $result = stripPKSC7Padding($result);

    return $result;

}



/**

 * 获取用户真实 IP

 */

function getIP()

{

    static $realip;

    if (isset($_SERVER)) {

        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {

            $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];

        } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {

            $realip = $_SERVER["HTTP_CLIENT_IP"];

        } else {

            $realip = $_SERVER["REMOTE_ADDR"];

        }

    } else {

        if (getenv("HTTP_X_FORWARDED_FOR")) {

            $realip = getenv("HTTP_X_FORWARDED_FOR");

        } else if (getenv("HTTP_CLIENT_IP")) {

            $realip = getenv("HTTP_CLIENT_IP");

        } else {

            $realip = getenv("REMOTE_ADDR");

        }

    }

    return $realip;

}



?>
