<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/2
 * Time: 17:01
 */
namespace app\api\validate;

use app\api\model\User as Usermodel;
use app\api\model\User;
use think\Validate;
use think\Cache;

class Login  extends Validate
{
    protected $rule = [
        'mobile' => 'require|length:11',
        'oldpassword' => 'require|length:6,12',
        'password' => 'require|length:6,12',
        'repassword' => 'confirm:password',
        'username' => 'require',
        'sms_code' => 'require|length:6',
        'user_id' => 'require',
        'openid' => 'require',
        'token' => 'require',
    ];

    protected $message = [
        'mobile.require' => '手机号未填写',
        'oldpassword.require' => '原密码不能为空',
        'password.require' => '密码不能为空',
        'repassword.confirm' => '两次密码不一致',
        'username.require' => '昵称未填写',
        'sms_code.require' => '短信验证码不能为空',
        'member_id.require' => '用户 id 缺失',
        'paypassword.require' => '密码不能为空',

    ];

    protected $scene = [

        'login' => [
            'mobile' => 'require|checkLogin', 'password'
        ],
        'bindUsername' => [
            'username' => 'require'
        ],

        'regist' => [
            'mobile'=>'require|checkRegist', 'password','repassword', 'sms_code' => 'require|checkSMSCodeByMobile:smsreg_code'
        ],

        'getregistcode' => [
            'mobile' => 'require|checkRegister|checkMobile',
        ],
        'binding'=>[
            'sms_code' => 'require|checkSMSCodeByMobile:smsreg_code',
        ],
        'bindingSetPass'=>[
            'password','repassword'
        ],

        'forgetPassMobile' => [
            'mobile' => 'require|checkLogin','repassword', 'password',
            'sms_code' => 'require|checkSMSCodeByMobile:smsforget_code',
        ],

        'forgetPassAdd' => [
            'oldpassword'=>'require|checkoldpassword', 'password' => 'require',
        ],

        'forgetpassword' => [
            'mobile' => 'require|checkLogin', 'password',
           // 'sms_code' => 'require|checkSMSCodeByMobile:sms_forgot_'
        ],

        'wxLogin' => [
            'openid','token'
        ],

        'logout' => [
            'member_id', 'reason',
        ]
    ];

    // 登录手机号验证
    protected function checkLogin($value, $rule, $data)
    {
        $user_model_data = Usermodel::where(['mobile' => $data['mobile'],'status'=>1])->find();
        return (!empty($user_model_data)) ? true : '手机号码不存在！';
    }
    // 注册手机号验证
    protected function checkRegist($value, $rule, $data)
    {
        $user_model_data = Usermodel::where(['mobile' => $data['mobile'],'status'=>1])->find();
        return (empty($user_model_data)) ? true : '手机号码已存在！';
    }
    // 自定义验证短信验证
    protected function checkSMSCodeByMobile($value, $rule, $data)
    {
        $key = $rule.$data['mobile'];
        if (!Cache::has($key)) {
            return '验证码已过期,请重新获取';
        }
        return ((string)$value == Cache::get($key)) ? true : '验证码错误';
    }

    protected  function checkoldpassword($value, $rule, $data){
        $userdata = UserModel::get($data['member_id']);
        if($userdata->password == md5(md5($data['oldpassword']))){
            return true;
        }
        return '原密码不正确';
    }
}