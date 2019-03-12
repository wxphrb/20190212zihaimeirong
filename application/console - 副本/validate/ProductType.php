<?php
namespace app\console\validate;

use think\Validate;

class ProductType extends Validate
{
    protected $rule = [
        "title|分类名称" => "require",
    ];
}
