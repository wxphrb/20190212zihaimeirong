<?php
namespace app\shop\validate;

use think\Validate;

class Nav extends Validate
{
    protected $rule = [
        "title|导航名称" => "require",
        "parentid|上级导航" => "require",
        "url|导航链接" => "require",
        "status|状态" => "require",
    ];
}
