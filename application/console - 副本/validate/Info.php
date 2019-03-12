<?php
namespace app\console\validate;

use think\Validate;

class Info extends Validate
{
    protected $rule = [
        "title|标题" => "require",
        "content|内容" => "require",
    ];
}
