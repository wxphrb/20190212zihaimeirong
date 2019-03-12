<?php
namespace app\shop\validate;

use think\Validate;

class Infolist extends Validate
{
    protected $rule = [
        "title|标题" => "require",
    ];
}
