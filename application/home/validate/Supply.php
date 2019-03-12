<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/2
 * Time: 17:01
 */
namespace app\home\validate;

use app\home\model\User as User;
use app\api\model\Supply as SupplyModel;
use think\Validate;
use think\Cache;

class Supply  extends Validate
{
    protected $rule = [
        'user_id' => 'require',
        'title' => 'require',
        'content' => 'require',
        'address' => 'require',
        'state'=>'require'
    ];

    protected $message = [
        'title.require' => '标题参数异常',
        'user_id.require' => '用户参数异常',
        'content.require' => '发布内容异常',
        'address.require'=>'定位参数异常',
        'state.require'=>'供求类别参数异常'

    ];

    //用户存不存在
    protected function checkUser($value, $rule, $data)
    {
        $user_model_data = User::where(['id' => $data['user_id'],'status'=>1])->find();

        return (!empty($user_model_data)) ? true : '用户身份异常！';

    }

    protected $scene = [

        'supplyAdd' => [
            'user_id' => 'require|checkUser',
            'title'=> 'require',
            'content'=>'require',
            'address'=>'require',
            'state'=>'require',
        ],

    ];
}