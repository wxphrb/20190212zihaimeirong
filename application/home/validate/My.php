<?php
namespace app\home\validate;

use think\Validate;

class My extends Validate
{
    protected $rule = [
        "username|用户名" => "require",
        "mobile|手机号" =>[ 'require', 'regex'=> '/^1(3[0-9]|4[57]|5[0-35-9]|7[0135678]|8[0-9])\d{8}$/i',]
    ];

    protected $message = [
        'username.require' => '必须填写用户名',
        'mobile.require' => '必须填写手机号',
        'mobile' => '手机号码格式错误',
    ];
    protected $scene = [
        'edit_address' => [
            'username','mobile',
        ],
        'add_address' => [
            'username','mobile',
        ],
    ];

}