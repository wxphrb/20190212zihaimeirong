<?php
namespace app\console\validate;

use think\Validate;

class UserTmp extends Validate
{
    protected $rule = [
        //"username|用户名" => "require|max:30|unique:user",
        "mobile|手机号" => ['unique'=>'user|user_tmp'],
    ];
}