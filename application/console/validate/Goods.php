<?php
namespace app\console\validate;

use think\Validate;

class Goods extends Validate
{
    protected $rule = [
        "title|名称" => "require",
        "saleprice|销售价格" => "require",
    ];
}
