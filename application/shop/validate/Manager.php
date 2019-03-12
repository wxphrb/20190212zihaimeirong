<?php
namespace app\shop\validate;

use think\Validate;

class Manager extends Validate
{
    protected $rule =   [
        'username|用户名'  => 'require|max:30|unique:manager',
        'nickname|昵称'  =>  'require|max:30|unique:manager',
        'mobile|手机号'=>['unique'=>'manager','regex'=>'/^1(3[0-9]|4[57]|5[0-35-9]|7[0135678]|8[0-9])\\d{8}$/i'],    
    ];

}
