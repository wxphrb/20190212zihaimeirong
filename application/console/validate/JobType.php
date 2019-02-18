<?php
namespace app\console\validate;

use think\Validate;

class JobType extends Validate
{
    protected $rule = [
        "title|名称" => "require",
        "num|人数" => "require",
    ];
}
