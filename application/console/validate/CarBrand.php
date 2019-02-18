<?php
namespace app\console\validate;

use think\Validate;

class CarBrand extends Validate
{
    protected $rule = [
        "p_pinpai|品牌名称" => "require",
    ];
}
