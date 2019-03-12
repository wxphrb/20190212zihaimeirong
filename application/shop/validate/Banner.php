<?php
namespace app\shop\validate;

use think\Validate;

class Banner extends Validate
{
    protected $rule = [
        "title|名称" => "require",
        "type_id|分类" => "require",
    ];
}
