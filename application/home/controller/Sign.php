<?php
namespace app\home\controller;
use app\home\model\IntegralInfo;
use app\home\model\User;
use app\home\model\Sign as SignModel;
use think\Controller;
use think\Db;
use think\Request;
use think\Session;
use think\Url;
class Sign extends Base
{
	//签到页面
	public function index(SignModel $signModel){
		$user_id  = Session::get('user_id');
		$month = $signModel->where('user_id', $user_id)->whereTime('create_time', 'm')->field('create_time')->select();
		$is_sign    = "";
		if(empty($month)){

		}else{
			foreach($month as $v){
				$is_sign  .=  ','.date('d',$v['create_time']).',';
			}

		}
        $integral= Db::name('user')->where('id',$user_id)->value('integral');//总积分
		$month_integral = Db::name('integralInfo')->whereTime('create_time', 'm')->where(['type'=>1,'user_id'=>$user_id])->sum('num');//当月签到所获得的积分
		return $this->fetch("index",[
			'is_sign'    => json_encode($is_sign),
			'integral'   =>$integral,
			'month_integral'=>$month_integral,
			'title'	     => '积分签到',
		]);
	}


	//签到历史查询
	public function signInHistory(Request $request,SignModel $signModel){
		$user_id  = Session::get('user_id');

		if (Request::instance()->isPost()) {

			$last    = $request->param('last');
			$year    = $request->param('year');
			if($last){
				$month   = $request->param('month')+1;
			}else{
				$month   = $request->param('month')-1;
			}

			$between = [$year."-".$month."-"."1",$year."-".$month."-"."31"];
			$month_sign  = $signModel->where('user_id', $user_id)->whereTime('create_time','between',$between)->field('create_time')->select();
			$is_sign    = '';
			if(empty($month_sign)){
				$is_sign    = ',';
			}else{
				foreach($month_sign as $v){
					$is_sign  .=  ','.date('d',$v['create_time']).',';
				}
			}
		}

		return ['code'=>200,'sign_date'=>$is_sign];
	}

	//确认签到
	public function signUp(Request $request,User $user,SignModel $signModel,IntegralInfo $info){
		$user_id  = Session::get('user_id');

		//当前有没有签到
		$sign  = $signModel->where('user_id',$user_id)->whereTime('create_time', 'today')->select();

		if(!empty($sign)){
			return ['code'=>400,'message'=>'今日已签到'];
		}
		//昨天签到情况
		$yesterdaysign = $signModel->where('user_id',$user_id)->whereTime('create_time', 'yesterday')->find();
		if(empty($yesterdaysign)){//昨天没有签到
			$web_sign_up = Db::name('webconfig')->where('varname','web_sign')->value('varvalue');
			$res['integral'] = $web_sign_up;
		}else{//昨天已签到
			$web_sign_up = Db::name('webconfig')->where('varname','web_signup')->value('varvalue');
			$res['integral'] = $web_sign_up+$yesterdaysign['sign_integral'];
		}
		$res['sign_time']     = date('Y-m-d',time());
		$res['create_time']   = time();
		$res['type']	        = 1;
		$res['num']           = $res['integral'];
		$res['user_id'] 	    = $user_id;
		$res['sign_integral'] = $res['integral'];
		try{
			$user->where('id',$user_id)->setInc('integral',$res['integral']);
			$signModel->allowField(true)->isUpdate(false)->save($res);
			$info->allowField(true)->isUpdate(false)->save($res);
			Db::commit();
			return ['code'=>200,'message'=>'签到成功','up_num'=>$res['integral']];
		}catch (Exception $exception){
			Db::rollback();
			return ['code'=>400,'message'=>$exception->getMessage()];
		}
	}
}