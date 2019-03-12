<?php
namespace app\console\validate;

use think\Validate;

class BbcType extends Validate
{
    protected $rule = [
        "type_title|分类标题" => "require",
    ];
}
