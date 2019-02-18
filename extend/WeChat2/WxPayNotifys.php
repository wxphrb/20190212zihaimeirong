<?php
/**
 * 
 * 回调基础类
 * @author widyhu
 *
 */
use Think\Controller;

use think\Db;
class WxPayNotifys extends WxPayNotifyReply
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
		Log::DEBUG('-------------微信支付回调开始-------------------');
		$a = json_encode($data);
		Log::DEBUG('-------------json：'.$a.'-------------------');


		$out_trade_no = $data['out_trade_no']; //订单号
		Log::DEBUG('-------------订单号：'.$out_trade_no.'-------------------');


		$attach = explode(",",$data['attach']);//$attach[0]  是member_id  $attach[1]  num购买数量

		$ad = json_encode($attach);
		Log::DEBUG('-------------查看$attach：'.$ad.'-------------------');
		Log::DEBUG('-------------member_id：'.$attach[0].'-------------------');
		Log::DEBUG('-------------num：'.$attach[1].'-------------------');

		//修改订单的状态
		$ordersn = Db::name("order")->where('ordersn',$out_trade_no)->update(['status' => 2,'paytype'=>3,'refundordernumber'=>$ad['transaction_id']]);
		if($ordersn){
			Log::DEBUG('操作更改订单成功----------------------------------------');
		}else{
			Log::DEBUG('操作更改订单失败----------------------------------------');
		}


		//购买升级
		$role1 = Db::name('role')->where('id',1)->find();//特约代理
		$role2 = Db::name('role')->where('id',2)->find();//区级代理
		$role3 = Db::name('role')->where('id',3)->find();//官方总代
		$role4 = Db::name('role')->where('id',4)->find();//合伙人
		$role5 = Db::name('role')->where('id',5)->find();// 股东

		//查询用户的角色
		$memberrole = Db::name('member')->where('id',$attach[0]['member_id'])->value('role_id');
		if($memberrole == 1 || $memberrole == 2) {  //用户的角色
			if($attach[1]['num'] >= $role3['hoarding'] && $attach[1]['num'] < $role4['hoarding'] ) {
				$info = Db::name('member')->where(['id'=>$attach[0]['member_id']])->update(['role' => 3]);
				if($info) {
					//添加上级记录
					Db::name('upgrade')->insert(['member_id'=>$attach[0]['member_id'],'paystatus'=>2,'role_id'=>3,'times'=>date('Y-m-d H:i:s')]);
				}
			}
		} elseif($memberrole == 3) {
			if($attach[1]['num'] >= $role4['hoarding'] && $attach[1]['num'] < $role5['hoarding'] ) {
				$info = Db::name('member')->where(['id'=>$attach[0]['member_id']])->update(['role' => 4]);
				if($info) {
					//添加上级记录
					Db::name('upgrade')->insert(['member_id'=>$attach[0]['member_id'],'paystatus'=>2,'role_id'=>4,'times'=>date('Y-m-d H:i:s')]);
				}
			}
		} elseif($memberrole == 4) {
			if($attach[1]['num'] >= $role5['hoarding']) {
				$info = Db::name('member')->where(['id'=>$attach[0]['member_id']])->update(['role' => 5]);
				if($info) {
					//添加上级记录
					Db::name('upgrade')->insert(['member_id'=>$attach[0]['member_id'],'paystatus'=>2,'role_id'=>5,'times'=>date('Y-m-d H:i:s')]);
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