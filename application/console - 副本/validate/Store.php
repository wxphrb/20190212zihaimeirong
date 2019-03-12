<?php
namespace app\console\validate;

use think\Validate;

class Store extends Validate
{
    protected $rule = [
//        "username|用户名" => "require|max:30|unique:user",
//        "mobile|手机号" => ['unique'=>'user','regex'=>'/^1(3[0-9]|4[57]|5[0-35-9]|7[0135678]|8[0-9])\\d{8}$/i'],
//        "email|邮箱" => "require",
    ];
}