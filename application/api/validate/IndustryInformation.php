<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/2
 * Time: 17:01
 */
namespace app\api\validate;

use app\api\model\User as User;
use app\api\model\Supply as SupplyModel;
use think\Validate;
use think\Cache;

class IndustryInformation  extends Validate
{
    protected $rule = [
        'user_id' => 'require',
        'title' => 'require',
        'content' => 'require',
        'picarr_type' => 'require',
    ];

    protected $message = [
        'title.require' => '标题参数异常',
        'user_id.require' => '用户参数异常',
        'content.require' => '资讯内容异常',
        'picarr_type.require'=>'上传类型参数异常',
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
            'picarr_type'=>'require',
        ],

    ];
}