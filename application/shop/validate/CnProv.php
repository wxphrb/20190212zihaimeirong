<?php
namespace app\shop\validate;

use think\Validate;

class CnProv extends Validate
{
    protected $rule = [
        "code|编码" => "require",
        "name|名称" => "require",
    ];
}
