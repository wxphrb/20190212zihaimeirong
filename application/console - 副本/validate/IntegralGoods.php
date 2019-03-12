<?php
namespace app\console\validate;

use think\Validate;

class IntegralGoods extends Validate
{
    protected $rule = [
        "title|名称" => "require",
        "price|销售价格" => "require",
    ];
}
