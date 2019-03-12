<?php
namespace app\console\validate;

use think\Validate;

class CarTypeList extends Validate
{
    protected $rule = [
        "truename|真实姓名" => "require",
    ];
}
