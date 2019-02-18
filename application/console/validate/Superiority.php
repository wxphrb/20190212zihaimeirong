<?php
namespace app\console\validate;

use think\Validate;

class Superiority extends Validate
{
    protected $rule = [
        "title|名称" => "require",
//        "type_id|分类" => "require",
    ];
}
