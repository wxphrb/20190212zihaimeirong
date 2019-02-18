<?php
namespace app\console\validate;

use think\Validate;

class Feedback extends Validate
{
    protected $rule = [
        "username|姓名" => "require",
        "mobile|电话" => "require",
    ];
}
