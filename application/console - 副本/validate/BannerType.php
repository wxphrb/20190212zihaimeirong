<?php
namespace app\console\validate;

use think\Validate;

class BannerType extends Validate
{
    protected $rule = [
        "title|名称" => "require",
    ];
}
