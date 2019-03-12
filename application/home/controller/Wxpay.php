<?php
// +----------------------------------------------------------------------
// | Created by PhpStorm
// +----------------------------------------------------------------------
// | Date: 2018/10/9 0009 15:29
// +----------------------------------------------------------------------
// | Use : 微信支付
// +----------------------------------------------------------------------
// | Author: Freepe
// +----------------------------------------------------------------------
namespace  app\home\controller;

use think\Controller;

use think\Db;
use think\Request;
use think\Session;

require EXTEND_PATH . 'wechatss/jsapi/WxPayPubHelper/WxPaypubconfig.php';
require EXTEND_PATH . 'wechatss/jsapi/WxPayPubHelper/WxPayPubHelper.php';
require EXTEND_PATH . 'wechatss/jsapi/WxPayPubHelper/SDKRuntimeException.php';
require EXTEND_PATH . 'wechatss/jsapi/demo/log_.php';

class  Wxpay extends Controller
{
    public function index(Request $request)
    {
        $data = $request->param();  //获取表单提交的所有参数

        if($data['attach'] == 1){
//            $attach = "1,".$data['order_sn'].",".Session::get('user_id').",".str_replace('￥','',$data['goods_price']);
            $attach = "1,";     //传递到回调方法进行判断处理
        }elseif($data['attach'] == 2){
            //传递到订单进行处理
            $attach = "2,";     //传递到回调方法进行判断处理
        }

        $this->wxpayConfig['returnurl'] = "http://www.wantaozb.com/home/orders/index/status/1";   //成功返回页面
        $error_url = "http://www.wantaozb.com/home/index/index";    //失败地址

        $this->wxpayConfig['appid'] = "wx76f12421c3192e40";     // 微信公众号APPID：绑定支付的APPID（必须配置，开户邮件中可查看）
        $this->wxpayConfig['appsecret'] = "d810e41dcc9d3509c0800fd6bd2f1e9b";    // APPSECRET：公众帐号secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置）
        $this->wxpayConfig['mchid'] = "1490041892";     // 受理商ID
        $this->wxpayConfig['key'] = "WanTaoSheBe888888888WanTaoSheBei";   // 商户支付密钥Key
        $this->wxpayConfig['js_api_call_url'] = $this->get_url();


//        $this->wxpayConfig['notifyurl'] = "http://www.wantaozb.com/home/Wxpaynotify/notifyurl";  //異步回調處理訂單
        $this->wxpayConfig['notifyurl'] = "http://www.wantaozb.com/api/Wechat/notifyurl";  //異步回調處理訂單


        $this->wxpayConfig['CURL_TIMEOUT'] = "30";
        $this->wxpayConfig['SSLCERT_PATH'] = "";
        $this->wxpayConfig['SSLKEY_PATH'] = "";

        new \WxPayConf_pub($this->wxpayConfig);     //实例化参数回传

        $code = $request->param('code');      //获取code，用于获取openid
//        halt($code);
        include_once "../extend/Wxpay/example/WxPay.JsApiPay.php";

        $tools = new \JsApiPay();      //实例化支付类


        $openId = $tools->GetOpenid($code);     //用户openid

        // 2,使用统一支付接口
        $unifiedOrder = new \UnifiedOrder_pub();
        $unifiedOrder->setParameter("openid",$openId);
        $unifiedOrder->setParameter("body", '万淘设备');    // 商品描述
        $unifiedOrder->setParameter("out_trade_no", $data['order_sn']);    // 商户订单号
        $unifiedOrder->setParameter("total_fee", str_replace('￥','',$data['goods_price'])*100);   // 总金额
        $unifiedOrder->setParameter("notify_url", \WxPayConf_pub::$NOTIFY_URL);  // 通知地址
        $unifiedOrder->setParameter("trade_type", "JSAPI");    // 交易类型
        // 非必填参数,商户可根据实际情况选填
        $unifiedOrder->setParameter("attach",$attach);   // 附加数据
        $prepay_id = $unifiedOrder->getPrepayId();
        // 3,使用jsapi调起支付
        $jsApi = new \JsApi_pub();
        $jsApi->setPrepayId($prepay_id);
        $jsApiParameters = $jsApi->getParameters();
//        dump($jsApiParameters);die;
        $returnurl = \WxPayConf_pub::$RETURN_URL;

        $button = <<<EOT
                    <html>
                    <head>
                    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
                    <title>微信安全支付</title>
                    </head>
                    <body>
                    <script type="text/javascript">
                    // 调用微信JS api 支付
                    function jsApiCall(){
                        WeixinJSBridge.invoke('getBrandWCPayRequest',{$jsApiParameters},function(res){
                                //WeixinJSBridge.log(res.err_msg);
            //                    alert(JSON.stringify({$jsApiParameters}));
//                                alert(res.err_code+'调试信息：'+res.err_desc+res.err_msg);
                                if(res.err_msg.indexOf('ok')>0){
                                    window.location.href='{$returnurl}';     //支付成功回跳
                                } else {
                                    window.location.href='{$error_url}';     //失败回跳地址
                                }
                            });
                    }

                    window.onload = function()
                    {
                        if (typeof WeixinJSBridge == "undefined"){
                            if( document.addEventListener ){
                                document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                            }else if (document.attachEvent){
                                document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                                document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                            }
                        }else{
                            jsApiCall();
                        }
                    }

                </script>
                </body>
                </html>
EOT;
        echo $button;
    }
    /**
     * 获取当前页面完整URL地址
     */
    private function get_url()
    {
        $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
        $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
        $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
        $relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self . (isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : $path_info);
        return $sys_protocal . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $relate_url;
    }
}