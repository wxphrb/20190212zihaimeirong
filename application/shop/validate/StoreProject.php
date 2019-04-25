<?php
namespace app\shop\validate;

use think\Validate;

class StoreProject extends Validate
{
    protected $rule = [
        "title|项目名称" => "require|max:20",
       // "truename|真实姓名" => "require",
        //"mobile|手机号" => ['unique'=>'user','regex'=>'/^1(3[0-9]|4[57]|5[0-35-9]|7[0135678]|8[0-9])\\d{8}$/i'],
       // "email|邮箱" => "require",
    ];
}