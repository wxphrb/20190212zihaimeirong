<?php
namespace app\console\validate;

use think\Validate;

class Product extends Validate
{
    protected $rule = [
        "title|标题" => "require",
        "picurl|封面" => "require",
    ];
}
