<?php
namespace app\index\validate;

use think\Validate;

class SellCar extends Validate
{
    protected $rule = [
        //"truename|姓名" => "require",
        "mobile|电话" => "require",
        "username|姓名" => "require",
    ];
}
