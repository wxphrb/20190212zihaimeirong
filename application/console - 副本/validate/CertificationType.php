<?php
namespace app\console\validate;

use think\Validate;

class CertificationType extends Validate
{
    protected $rule = [
        "title|标题" => "require",
    ];
}
