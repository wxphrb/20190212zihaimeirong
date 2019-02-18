<?php
namespace app\console\validate;

use think\Validate;

class News extends Validate
{
    protected $rule = [
        "title|名称" => "require",
        "content|内容" => "require",
    ];
}
