<?php
namespace app\console\validate;

use think\Validate;

class Solution extends Validate
{
    protected $rule = [
        "title|名称" => "require",
        "picurl|图片" => "require",
        "intro|简介" => "require",
        "status|状态" => "require",
    ];
}
