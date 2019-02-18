<?php
namespace app\console\validate;

use think\Validate;

class CnCity extends Validate
{
    protected $rule = [
        "code|编码" => "require",
        "name|名称" => "require",
        "pcode|父编码" => "require",
    ];
}
