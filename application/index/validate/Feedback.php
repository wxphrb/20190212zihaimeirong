<?php
namespace app\index\validate;

use think\Validate;

class Feedback extends Validate
{
    protected $rule = [
        //"truename|姓名" => "require",
        "mobile|电话" => "require",
        "car_id|车id" => "require",
    ];
}
