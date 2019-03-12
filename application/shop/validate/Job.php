<?php
namespace app\shop\validate;

use think\Validate;

class Job extends Validate
{
    protected $rule = [
        "title|名称" => "require",
        "num|人数" => "require",
    ];
}
