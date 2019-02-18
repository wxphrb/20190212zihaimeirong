<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2018/9/7
 * Time: 17:11
 */
namespace app\api\validate;

use think\Validate;

class Share extends Validate{

    protected $rule = [
        'user_id' => 'require',
        'type' => 'require',
        'other_id' => 'require'
    ];

    protected $message = [
        'user_id.require' => '用户id不能为空',
        'type.require' => '类型不能为空',
        'other_id.require' => '其他id不能为空',
    ];

    protected $scene = [
        'shareAdd' => ['user_id','type','other_id']
    ];

}