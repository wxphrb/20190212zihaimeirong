<?php
namespace app\shop\validate;

use think\Validate;

class StoreMember extends Validate
{
    protected $rule = [
        "username|用户名" => "require|max:10|unique:user",
       // "truename|真实姓名" => "require",
        "mobile|手机号" => ['unique'=>'user','regex'=>'/^1(3[0-9]|4[57]|5[0-35-9]|7[0135678]|8[0-9])\\d{8}$/i'],
       // "email|邮箱" => "require",
    ];
}