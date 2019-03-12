<?php

namespace app\api\validate;

use think\Validate;

use app\api\model\User as UserModel;

class User extends Validate{

    protected  $rule = [

        'password'=>"require",

        'repassword' => 'confirm:newpassword',

        'newpassword' => 'require',

    ];



    protected $message = [

        'password.require'=>"原密码不能为空",

        'repassword.confirm'=>"两次密码不一致",

    ];



    protected $scene = [

        'save'=>[

            'require|checkpassword','password','repassword','newpassword'

        ]

    ];



    protected function checkpassword($value, $rules, $data)

    {

        $user_data = UserModel::where(['mobile' => $data['user_id'],'password'=>md5(md5($data['password']))])->find();

        return (!empty($user_data)) ? true : '原密码不正确';

    }

}