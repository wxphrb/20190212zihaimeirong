<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/1
 * Time: 13:54
 */

namespace app\api\validate;


use think\Validate;

class Job extends  Validate
{
    protected $rule = [
        'type_id'=>'require',
        'title'=>'require',
        'content'=>'require',
        'address'=>'require',
        'user_id'=>'require',
    ];

    protected $message = [
        'type_id.require' => '发布类型不能为空',
        'user_id.require' => '用户id不能为空',
        'content.require' => '发布内容异常',
        'title.require' => '标题状态异常',
        'address.require'=>'地址参数异常'
    ];
}