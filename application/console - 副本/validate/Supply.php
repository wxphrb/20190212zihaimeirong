<?php
namespace app\console\validate;

use think\Validate;

class Supply extends Validate
{
    protected $rule = [
        "title|标题" => "require|max:20",
        "address|所在地" =>"require|length:3,15",
//            ['unique'=>'user','regex'=>'/^1(3[0-9]|4[57]|5[0-35-9]|7[0135678]|8[0-9])\\d{8}$/i'],
        "content|内容" => "require",
    ];
}