<?php
namespace app\shop\validate;

use think\Validate;

class Store extends Validate
{
    protected $rule = [
        "title|店铺名称" => "require|max:30",
        "manned|掌柜的" => "require",
//        "mobile|手机号" => ['unique'=>'store','regex'=>'/^1(3[0-9]|4[57]|5[0-35-9]|7[0135678]|8[0-9])\\d{8}$/i'],
       // "picurl|logo" => "require",
        "address|地址" => "require",
    ];
}