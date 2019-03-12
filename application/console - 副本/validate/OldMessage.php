<?php
namespace app\console\validate;

use think\Validate;

class OldMessage extends Validate
{
    protected $rule = [
        //"truename|姓名" => "require",
        "mobile|电话" => "require",
    ];
}
