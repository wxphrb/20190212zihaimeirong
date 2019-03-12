<?php
namespace app\console\validate;

use think\Validate;

class Chehang extends Validate
{
    protected $rule = [
        "title|车行名称" => "require",
    ];
}
