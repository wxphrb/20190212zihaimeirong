<?php
namespace app\shop\validate;

use think\Validate;

class Infoclass extends Validate
{
    protected $rule = [
        "title|标题" => "require",
    ];
}
