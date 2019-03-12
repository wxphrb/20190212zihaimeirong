<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/9
 * Time: 9:37
 */
namespace app\home\controller;
use think\Loader;
use think\Request;
use think\Db;
use think\Url;
use think\Controller;use think\Response;
class Alipay extends Controller
{
    /*
     * 查看订单信息  调用支付宝
     */
    public function pay(){
        $request = Request::instance()->param();
       // if(Request::instance()->isPost()){
            Loader::import('alipay.wappay.service.AlipayTradeService');
            Loader::import('alipay.wappay.buildermodel.AlipayTradeWapPayContentBuilder');
            Loader::import('alipay.config');
           //require_once EXTEND_PATH . "Alipay" . DS . "wappay" . DS . "service" . DS . "AlipayTradeService.php";			//require_once EXTEND_PATH . "Alipay" . DS . "wappay" . DS . "buildermodel" . DS . "AlipayTradeWapPayContentBuilder.php";			//require_once EXTEND_PATH . "Alipay" . DS . "config.php";
           /* require_once dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'service/AlipayTradeService.php';
            require_once dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'buildermodel/AlipayTradeWapPayContentBuilder.php';
            require dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'./../config.php';*/
			$ordersn = $request['order_sn'];
            if (!empty($ordersn)){
             if($request['type']==1){//合并
                 $order_merga = Db::name('orders_merge')->where('merge_sn', $request['order_sn'])->find();//合并的订单表
                  $total_amount=0;
                 $order = explode(',',$order_merga['order_sn']);
                 foreach($order as $v){
                     $total_amount +=Db::name('orders')->where('order_sn',$v)->value('total');
                 }
             }else{
                 $data = Db::name('orders')
                     ->where('order_sn',$ordersn)
                     ->field('order_sn,total')
                     ->find();
             }
            $data['ordername'] = '购买商品消费';
                //商户订单号，商户网站订单系统中唯一订单号，必填
                //$out_trade_no = $request['WIDout_trade_no'];
                $out_trade_no =  $request['order_sn'];
                //订单名称，必填
                $subject = $data['ordername'];
                //$subject = $request['WIDsubject'];
                //付款金额，必填
//               $total_amount = $data['total'];
                $total_amount = 0.01;
               // $total_amount = $request['WIDtotal_amount'];
				global $config;
                //商品描述，可空
                 $body = $request['type'];
                $config['notify_url'] = "http://app106.app.longcai.net/index.php/api/Alipay/notifyurl";
                //超时时间
                $timeout_express="1m";
                $payRequestBuilder = new \AlipayTradeWapPayContentBuilder();
                $payRequestBuilder->setBody($body);
                $payRequestBuilder->setSubject($subject);
                $payRequestBuilder->setOutTradeNo($out_trade_no);
                $payRequestBuilder->setTotalAmount($total_amount);
                $payRequestBuilder->setTimeExpress($timeout_express);
                $payResponse = new \AlipayTradeService($config);
                $result=$payResponse->wapPay($payRequestBuilder,$config['return_url'],$config['notify_url']);
           }
    }
	
	
	
	
/*
支付成功跳转
*/
    public function returnurl(){
        Loader::import('Alipay.wappay.service.AlipayTradeService');
        Loader::import('Alipay.config');		//require_once EXTEND_PATH . "Alipay" . DS . "wappay" . DS . "service" . DS . "AlipayTradeService.php";		//require_once EXTEND_PATH . "Alipay" . DS . "wappay" . DS . "buildermodel" . DS . "AlipayTradeWapPayContentBuilder.php";		//require_once EXTEND_PATH . "Alipay" . DS . "config.php";
        global $config;
        $data = Request::instance()->param();
        $alipaySevice = new \AlipayTradeService($config);
        $result = $alipaySevice->check($data);
        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
		$a = "支付失败";
		
        if($result) {//验证成功
			
			$out_trade_no =$data['out_trade_no'];
			//支付宝交易号
			//$trade_no = htmlspecialchars($data['trade_no']);
				
			$a = '支付成功'.$out_trade_no;
		
			
        }
		
		return  $this->fetch('',['vo'=>$a]);
    }
}