<?phpnamespace app\home\controller;use think\Controller;use think\Db;use think\Request;use think\Url;use think\Loader;class WeiPay extends Controller{	private $wxpayConfig;	private $wxpay;	protected function _initialize()	{		header("Content-type: text/html; charset=utf-8");		Loader::import('wechatss.jsapi.WxPayPubHelper.WxPaypubconfig',EXTEND_PATH,'.php');		Loader::import('wechatss.jsapi.WxPayPubHelper.WxPayPubHe.lper',EXTEND_PATH,'.php');		Loader::import('wechatss.jsapi.WxPayPubHelper.SDKRuntimeException',EXTEND_PATH,'.php');		Loader::import('wechatss.jsapi.WxPayPubHelper.WxPayPubHelper',EXTEND_PATH,'.php');		Loader::import('wechatss.jsapi.demo/log_',EXTEND_PATH,'.php');//		$this->wxpayConfig['appid'] = "wx9631330f9d92d1ff";     // 微信公众号APPID：绑定支付的APPID（必须配置，开户邮件中可查看）//		$this->wxpayConfig['appsecret'] = "9185cb4f9eb77209dbe056bfbc75bd6f"; // APPSECRET：公众帐号secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置）//		$this->wxpayConfig['mchid'] = "1505354961";            // 受理商ID//		$this->wxpayConfig['key'] = "WanTaoSheBe888888888WanTaoSheBei";          // 商户支付密钥Key		$this->wxpayConfig['appid'] = "wx76f12421c3192e40";     // 微信公众号APPID：绑定支付的APPID（必须配置，开户邮件中可查看）		$this->wxpayConfig['appsecret'] = "d810e41dcc9d3509c0800fd6bd2f1e9b"; // APPSECRET：公众帐号secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置）		$this->wxpayConfig['mchid'] = "1490041892";            // 受理商ID		$this->wxpayConfig['key'] = "WanTaoSheBe888888888WanTaoSheBei";          // 商户支付密钥Key		$this->wxpayConfig['js_api_call_url'] = $this->get_url();//		$this->wxpayConfig['notifyurl'] = "http://app106.app.longcai.net/index.php/api/Wechat/notifyurl";//回调通知地址//		$this->wxpayConfig['returnurl'] = "http://app106.app.longcai.net/home/user/index";    //成功返回页面        $this->wxpayConfig['notifyurl'] = "http://www.wantaozb.com/index.php/api/Wechat/notifyurl";//回调通知地址		$this->wxpayConfig['returnurl'] = "http://www.wantaozb.com/home/orders/index/status/1";    //成功返回页面		$this->wxpayConfig['CURL_TIMEOUT'] = "30";		$this->wxpayConfig['SSLCERT_PATH'] = "";		$this->wxpayConfig['SSLKEY_PATH'] = "";//		dump($this->wxpayConfig);die;		$wxpaypubconfig = new \WxPayConf_pub($this->wxpayConfig);		//wcc($wxpaypubconfig);	}   function index(Request $request) {	   $param = Request::instance()->param();//		dump($param);die;	   //获取数据	   $money = Db::name('orders')->where('order_sn',$param['order_sn'])->value('total');//	  halt($money);	   // 2,使用统一支付接口	   //$unifiedOrder = new \UnifiedOrder_pub();	   $unifiedOrder = new \UnifiedOrder_pub();//               dump($unifiedOrder);die;	   // 设置统一支付接口参数	   // 设置必填参数	   // appid已填,商户无需重复填写	   // mch_id已填,商户无需重复填写	   // noncestr已填,商户无需重复填写	   // spbill_create_ip已填,商户无需重复填写	   // sign已填,商户无需重复填写	   $unifiedOrder->setParameter("body", '万淘支付');         // 商品描述	   $unifiedOrder->setParameter("out_trade_no", $param['order_sn']);   // 商户订单号//        $unifiedOrder->setParameter("total_fee", $param['money']*100);  // 总金额	   $unifiedOrder->setParameter("total_fee", $money*100);              // 总金额	   $unifiedOrder->setParameter("notify_url", \WxPayConf_pub::$NOTIFY_URL);  // 通知地址//	   $unifiedOrder->setParameter("trade_type", "MWEB");  // 交易类型  NATIVE 扫码支付  JSAPI 公众号支付	   $unifiedOrder->setParameter("trade_type", "JSAPI");  // 交易类型  NATIVE 扫码支付  JSAPI 公众号支付	   // 非必填参数,商户可根据实际情况选填	   $unifiedOrder->setParameter("attach",!empty($param['attach']) ? $param['attach'] : '');	   $zong = $unifiedOrder->createXml();//	   dump($zong);die;	   $jsApi = new \JsApi_pub();	   $url = $jsApi->createOauthUrlForCode($zong['mweb_url']);//	   return $zong['code_url'];	   return $url;//	   $a = $this->httpGet($zong['code_url']);   }	function httpGet($url)	{		$curl = curl_init();		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);		curl_setopt($curl, CURLOPT_TIMEOUT, 500);		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);		curl_setopt($curl, CURLOPT_URL, $url);		$res = curl_exec($curl);		curl_close($curl);		return $res;	}	//todo 表单制作	public function create($submitUrl,$data=[])	{//        self::log ( $data );		$inputstr = "";		foreach ($data as $key => $v) {			$inputstr .= '<input type=\'hidden\' id=\'' . $key . '\' name=\'' . $key . '\' value=\'' . $v . '\'/>';		}		$form = '<form action="' . $submitUrl . '" name="pay" id="pay" method="post">';		$form .= $inputstr;		// $form.='<input type="submit" value="submit">';		$form .= '</form>';		$html = '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>请不要关闭页面,跳转中.....</title></head>        <body>';		$html .= $form;		$html .= '<script type="text/javascript">document.getElementById("pay").submit();</script>';		$html .= '</body></html>';		self::Mheader('utf-8');		exit (str_replace(PHP_EOL, '', $html));	}	//todo 编码	function Mheader($type)	{		header("Content-Type:text/html;charset={$type}");	}	private function get_url()	{		$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';		$php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];		$path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';		$relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self . (isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : $path_info);		return $sys_protocal . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $relate_url;	}	function index1() {		$param = Request::instance()->param();//		dump($param);die;		//获取数据		$money = Db::name('orders')->where('order_sn',$param['order_sn'])->value('total');		$unifiedOrder = new \UnifiedOrder_pub();		$unifiedOrder->setParameter("body", '万淘支付');         // 商品描述		$unifiedOrder->setParameter("out_trade_no", $param['order_sn']);              // 商户订单号//        $unifiedOrder->setParameter("total_fee", $param['money']*100);               // 总金额		$unifiedOrder->setParameter("total_fee", $money*100);               // 总金额		$unifiedOrder->setParameter("notify_url", \WxPayConf_pub::$NOTIFY_URL);  // 通知地址		$unifiedOrder->setParameter("trade_type", "NATIVE");  // 交易类型  NATIVE 扫码支付  JSAPI 公众号支付//		// 非必填参数,商户可根据实际情况选填//		$unifiedOrder->setParameter("attach",$param['attach']);		$prepay_id = $unifiedOrder->getPrepayId();		dump($prepay_id);die;		// 3,使用jsapi调起支付		$jsApi = new \JsApi_pub();		$jsApi->setPrepayId($prepay_id);		$jsApiParameters = $jsApi->getParameters();//        dump($jsApiParameters);die;		$returnurl = \WxPayConf_pub::$RETURN_URL;//        dump($returnurl);die;		$path = dirname(__FILE__);		$button = <<<EOT        <html>        <head>        <meta http-equiv="content-type" content="text/html;charset=utf-8" />        <title>微信安全支付</title>        </head>        <body>        <script type="text/javascript">        // 调用微信JS api 支付        function jsApiCall(){            WeixinJSBridge.invoke('getBrandWCPayRequest',{$jsApiParameters},function(res){                    WeixinJSBridge.log(res.err_msg);//                    alert(JSON.stringify({$jsApiParameters}));                    //alert(res.err_code+'调试信息：'+res.err_desc+res.err_msg);                    if(res.err_msg.indexOf('ok')>0){                        window.location.href='{$returnurl}';                    } else {                        window.location.href="http://casmy.cn/home/integral/account";                    }                });        }        window.onload = function()        {            if (typeof WeixinJSBridge == "undefined"){                if( document.addEventListener ){                    document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);                }else if (document.attachEvent){                    document.attachEvent('WeixinJSBridgeReady', jsApiCall);                    document.attachEvent('onWeixinJSBridgeReady', jsApiCall);                }            }else{                jsApiCall();            }        }    </script>    </body>    </html>EOT;		echo $button;	}}