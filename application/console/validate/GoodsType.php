<?php
namespace app\console\validate;

use think\Validate;

class GoodsType extends Validate
{
    protected $rule = [
        "title|名称" => "require",
    ];
}
