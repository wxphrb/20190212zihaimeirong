<?php
namespace app\console\validate;

use think\Validate;

class Job extends Validate
{
    protected $rule = [
        "title|名称" => "require",
    ];
}
