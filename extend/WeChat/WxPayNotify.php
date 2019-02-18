<?php
/**
 * 
 * 回调基础类
 * @author widyhu
 *
 */
use Think\Controller;
use think\Config;
use think\Db;
use think\Loader;
use think\Request;
class WxPayNotify extends WxPayNotifyReply
{
	/**
	 * 
	 * 回调入口
	 * @param bool $needSign  是否需要签名输出
	 */
	final public function Handle($needSign = true)
	{
		$msg = "OK";
		//当返回false的时候，表示notify中调用NotifyCallBack回调失败获取签名校验失败，此时直接回复失败
		$result = WxpayApi::notify(array($this, 'NotifyCallBack'), $msg);
		if($result == false){
			$this->SetReturn_code("FAIL");
			$this->SetReturn_msg($msg);
			$this->ReplyNotify(false);
			return;
		} else {
			//该分支在成功回调到NotifyCallBack方法，处理完成之后流程
			$this->SetReturn_code("SUCCESS");
			$this->SetReturn_msg("OK");
		}
		$this->ReplyNotify($needSign);
	}
	
	/**
	 * 
	 * 回调方法入口，子类可重写该方法
	 * 注意：
	 * 1、微信回调超时时间为2s，建议用户使用异步处理流程，确认成功之后立刻回复微信服务器
	 * 2、微信服务器在调用失败或者接到回包为非确认包的时候，会发起重试，需确保你的回调是可以重入
	 * @param array $data 回调解释出的参数
	 * @param string $msg 如果回调处理失败，可以将错误信息输出到该方法
	 * @return true回调出来完成不需要继续回调，false回调处理未完成需要继续回调
	 */
	
	public function NotifyProcess($data, &$msg)
	{
		//TODO 用户基础该类之后需要重写该方法，成功的时候返回true，失败返回false
		//业务处理
		$out_trade_no = $data['out_trade_no']; //订单号
		$body = $data['attach'];//购物车支付，和合并订单支付 :1 ,立即购买，和订单里的去支付:2
		Log::DEBUG('-------------订单号：'.$out_trade_no.'-------------------');
		Log::DEBUG('-------------提交类型：'.$body.'-------------------');
		if($body==1){  //合并支付
			$order_merga = Db::name('orders_merge')->where('merge_sn',$out_trade_no)->find();//合并的订单表
			if($order_merga['status']=='0'){//未支付
                $order_sn = explode(',',$order_merga['order_sn']);
				foreach($order_sn as $v){
					$order = Db::name('orders')->where('order_sn',$v)->value('status');
					if($order=='0'){
						Log::DEBUG('订单状态更改成功：'.$v);
					}
				}
				$order_merga = Db::name('orders_merge')->where('merge_sn',$out_trade_no)->update(['status'=>1]);
				if($order_merga){
					Log::DEBUG('合并订单状态修改成功');
				}
			}
		}elseif($body==2){  //单条支付
			$order_data = Db::name('orders')->where('order_sn',$out_trade_no)->select();
			Log::DEBUG('订单信息：'.json_encode($order_data));
			foreach($order_data as $v){
				if($v['status']=='0'){
					Db::name('orders')->where('id',$v['id'])->update(['status'=>1,'pay_type'=>1]);
					Log::DEBUG('订单状态更改成功：'.$v['id']);
				}
			}
		}
		return true;
	}
	
	/**
	 * 
	 * notify回调方法，该方法中需要赋值需要输出的参数,不可重写
	 * @param array $data
	 * @return true回调出来完成不需要继续回调，false回调处理未完成需要继续回调
	 */
	final public function NotifyCallBack($data)
	{
		$msg = "OK";
		$result = $this->NotifyProcess($data, $msg);
		
		if($result == true){
			$this->SetReturn_code("SUCCESS");
			$this->SetReturn_msg("OK");
		} else {
			$this->SetReturn_code("FAIL");
			$this->SetReturn_msg($msg);
		}
		return $result;
	
	}
	
	/*
	 * 
	 * 回复通知
	 * @param bool $needSign 是否需要签名输出
	 */
	final private function ReplyNotify($needSign = true)
	{
		//如果需要签名
		if($needSign == true && $this->GetReturn_code($return_code) == "SUCCESS")
		{
			$this->SetSign();
		}
		WxpayApi::replyNotify($this->ToXml());
	}
}