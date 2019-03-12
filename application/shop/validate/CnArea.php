<?php
namespace app\shop\validate;

use think\Validate;

class CnArea extends Validate
{
    protected $rule = [
        "code|编码" => "require",
        "name|名称" => "require",
        "pcode|父编码" => "require",
    ];
}
