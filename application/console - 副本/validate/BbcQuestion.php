<?php
namespace app\console\validate;

use think\Validate;

class BbcQuestion extends Validate
{
    protected $rule = [
        "title|标题" => "require",
    ];
}
