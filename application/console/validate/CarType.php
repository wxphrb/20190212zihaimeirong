<?php
namespace app\console\validate;

use think\Validate;

class CarType extends Validate
{
    protected $rule = [
        "title|标题" => "require",
        //"content|内容" => "require",
    ];
}
