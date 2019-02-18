<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/4
 * Time: 10:28
 */

namespace app\api\controller;

use app\api\model\Supply as SupplyModel;
use app\api\model\User;
use app\api\model\Sign as SignModel;
use app\api\model\Integral;
use app\api\model\IntegralInfo;
use mrmiao\encryption\RSACrypt;
use think\Cache;
use think\Controller;
use think\Db;
use think\Request;

class Sign extends Controller
{
    public function index(RSACrypt $crypt,User $user,SignModel $signModel){
        $param = $crypt->request();

        //用户当前积分
        $score = $user->where('id',$param['user_id'])->field('integral')->find();
        //当前签到状态
        $sign = $signModel->where('user_id',$param['user_id'])->whereTime('create_time', 'd')->select();

        //本月已签到日期
        $month = $signModel->where('user_id', $param['user_id'])->whereTime('create_time', 'm')->field('sign_time')->select();
        $sign_rule = "http://www.wantaozb.com/index.php/api/Sign/sign_web";
        if(array_key_exists('month',$param) &&  !empty($param['month'])) {
            $day = explode('-', $param['month']);//接到的月份转换

            //$day_num = cal_days_in_month(CAL_GREGORIAN, $day[1], $day[0]);//接到的月份共有天数
            $day_num = date('t', strtotime($param['month']));//接到的月份共有天数
            $day_num = $day_num - 1;
            $end_day = date("Y-m-d", strtotime("+$day_num day", strtotime($param['month'])));

            $month = $signModel->where('user_id', $param['user_id'])->whereTime('create_time', 'between', [$param['month'], $end_day])->select();
        }

        $months = [];
        if($month){
            foreach ($month as $key=>$value){
                $months[] = $month[$key]['sign_time'];
            }
        }
        $month_total = Db::name('sign')->where('user_id',$param['user_id'])->whereTime('create_time','m')->sum('sign_integral');
        $month_total = empty($month_total)?0:$month_total;
        if(!$sign){
            $message = '今日未签到';
            $flag = '0';
        }else{
            $message = '今日已签到';
            $flag = '1';
        }
        return $crypt->response(['code' => 200, 'message' => $message, 'data' => ['month_total'=>$month_total,'status'=>$flag,'score'=>$score['integral'],'month'=>$months,'sign_rule'=>$sign_rule]], true);
    }


        public function sign_web(Request $request){
            $post_data = $request->param();
            $content = Db::name('webconfig')->where("varname",'web_rule')->value("varvalue");
            return make_show($content);
        }

    /**
     * 今日签到
     * 参数：uid  用户id
     * @param RSACrypt $crypt
     * @param MemberSign $memberSign
     * @param Member $member
     * @param MemberScoreLog $memberScoreLog
     * @return mixed
     */
    public function signUp(RSACrypt $crypt,User $user,SignModel $signModel,IntegralInfo $info){
        $param = $crypt->request();

         //当前有没有签到
        $sign = $signModel->where('user_id',$param['user_id'])->whereTime('create_time', 'today')->select();

        if(!empty($sign)){
            return $crypt->response(['code'=>400,'message'=>'今日已签到'],true);
        }
        //昨天签到情况
        $yesterdaysign = $signModel->where('user_id',$param['user_id'])->whereTime('create_time', 'yesterday')->find();
        if(empty($yesterdaysign)){//昨天没有签到
            $web_sign_up = Db::name('webconfig')->where('varname','web_sign')->value('varvalue');
            $res['integral']=$web_sign_up;
        }else{//昨天已签到
            $web_sign_up = Db::name('webconfig')->where('varname','web_signup')->value('varvalue');
            $res['integral'] = $web_sign_up+$yesterdaysign['sign_integral'];
        }
        $res['sign_time'] = date('Y-m-d',time());
        $res['create_time']=time();
        $res['type']=1;
        $res['num']=$res['integral'];
        $res['user_id'] = $param['user_id'];
        $res['sign_integral'] = $res['integral'];
        try{
        $user->where('id',$param['user_id'])->setInc('integral',$res['integral']);
        $signModel->allowField(true)->isUpdate(false)->save($res);
        $info->allowField(true)->isUpdate(false)->save($res);
            Db::commit();
            return $crypt->response(['code'=>200,'message'=>'签到成功'],true);
        }catch (Exception $exception){
            Db::rollback();
            return $crypt->response(['code'=>400,'message'=>$exception->getMessage()],true);
        }
    }
}