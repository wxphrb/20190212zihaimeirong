<?php
namespace app\console\validate;

use think\Validate;

class BbcAnswer extends Validate
{
    protected $rule = [
        "title|标题" => "require",
    ];
}
