<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2018/9/7
 * Time: 14:36
 */
namespace app\api\controller;

use think\Controller;
use mrmiao\encryption\RSACrypt;
use think\Db;

class Share extends Controller{

    public function shareAdd(RSACrypt $crypt)
    {
        try{
            $data = request()->param();
            //判断今天是否已经分享
            $map = [
                'user_id' => $data['user_id'],
                'type' => $data['type'],
                'other_id' => $data['other_id'],
            ];
            $res =  Db::name('share_log')->where($map)->whereTime('create_time','today')->find();
            if(empty($res)){
                //增加积分 获取增加的积分
                $add_jifen = Db::name('webconfig')->where('varname','web_share_integral')->value('varvalue');
                $max_jifen = Db::name('webconfig')->where('varname','web_max_share_integral')->value('varvalue');
                //
                $map1 = [
                    'user_id' => $data['user_id'],
                    'is_add' => 2,
                    'type' => $data['type'],
                    'other_id' => $data['other_id'],
                ];
                $count =  Db::name('share_log')->where($map1)->whereTime('create_time','today')->find();
                $today_jifen = $count * $add_jifen;
                if($today_jifen<=$max_jifen){
                    //加积分记录
                    $jifen_log = [
                        'user_id' => $data['user_id'],
                        'type' => 5,
                        'num' => $add_jifen,
                        'create_time' => time()
                    ];
                    Db::name('integral_info')->insert($jifen_log);
                    //会员加积分
                    Db::name('user')->where('id',$data['user_id'])->setInc('integral',$add_jifen);
                    $log_data = [
                        'user_id' => $data['user_id'],
                        'type' => $data['type'],
                        'other_id' => $data['other_id'],
                        'create_time' => time(),
                        'is_add' => 2
                    ];
                    Db::name('share_log')->insert($log_data);
                }

            }
            //分享记录增加
            $log = [
                'user_id' => $data['user_id'],
                'type' => $data['type'],
                'other_id' => $data['other_id'],
                'create_time' => time()
            ];
            Db::name('share_log')->insert($log);
            return $crypt->response([
                'code' =>200,
                'message' => "分享成功",
            ]);
        }catch (\Exception $e){
            return $crypt->response([
                'code' =>400,
                'message' => "分享增加积分失败",
            ]);
        }
    }
    
}