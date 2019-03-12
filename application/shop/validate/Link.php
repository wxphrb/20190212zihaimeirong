<?php
namespace app\shop\validate;

use think\Validate;

class Link extends Validate
{
    protected $rule = [
        "title|名称" => "require",
        "url|网址" => "require",
    ];
}
