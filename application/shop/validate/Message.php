<?php
namespace app\shop\validate;

use think\Validate;

class Message extends Validate
{
    protected $rule = [
        "truename|姓名" => "require",
        "mobile|电话" => "require",
    ];
}
