<?php

namespace app\api\behavior;

use app\api\model\User;

class CheckAuth
{

    public function run(&$params)
    {
        self::checkForbidden();
    }

    //检查用户是否禁用
    protected function checkForbidden(){
        $user_id = request()->param('user_id');
        if(!empty($user_id)){
            $user_model = User::get($user_id);
            if (!$user_model)
                abort(json(['code'=>401,'message'=>'用户不存在']));
        }
    }
}