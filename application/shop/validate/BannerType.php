<?php
namespace app\shop\validate;

use think\Validate;

class BannerType extends Validate
{
    protected $rule = [
        "title|名称" => "require",
    ];
}
