<?php
namespace app\console\validate;

use think\Validate;

class kehu extends Validate
{
    protected $rule = [
        "title|标题名称" => "require",
        "status|状态" => "require",
        "picurl|客户图片" => "require",

    ];
}
