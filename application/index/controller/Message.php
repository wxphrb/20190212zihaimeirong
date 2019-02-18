<?php

namespace app\index\controller;


use think\Controller;

use think\Db;

use think\Request;

use think\Url;
use app\index\model\Message as ThisModel;


class Message extends Base

{
	public function _initialize() {
		parent::_initialize();
		 //wcc($data);

	}
    //ajax提交留言咨询
	public function consult( Request $request){
        if (Request::instance()->isPost()) {
            $data['mobile'] = Request::instance()->post('mobile');
            $car_type       = Request::instance()->post('car_type');
            $data['car_id'] = Request::instance()->post('car_id');

            $find = db('feedback')->where(['mobile'=>$data['mobile'],'car_id'=>$data['car_id']])->find();

            if ($find) {
                //已咨询过该车型
                return ['data'=>2];

            }else{
                if($car_type == 'new'){
                    $table      = 'new_car';
                    $filed_n    = 'p_chexingmingcheng';
                    $type_v     = 1;
                }else{
                    $table      = 'old_car';
                    $filed_n    = 'vehicleName';
                    $type_v     = 2;
                }
                $chexing = db($table)->where('id',$data['car_id'])->field($filed_n)->find();
                if($chexing){
                    $data['content'] = $chexing[$filed_n];
                }else{
                    $data['content'] = $data['car_id'];
                }
                $data['type']        = $type_v;
                $data['ip']          = getIP();
                $result = ThisModel::saveVerify($data);

                if (true === $result) {

                    return ['data'=>1];

                } else {

                    return ['data'=>0];

                }

            }

        }

	}

}
