<?php
namespace app\console\validate;

use think\Validate;

class Nav extends Validate
{
    protected $rule = [
        "title|新闻名称" => "require",
        "status|状态" => "require",
    ];
}
