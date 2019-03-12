<?php
namespace app\console\validate;

use think\Validate;

class IndustryInformation extends Validate
{
    protected $rule = [
        "title|导航名称" => "require",
        "status|状态" => "require",
    ];
}
