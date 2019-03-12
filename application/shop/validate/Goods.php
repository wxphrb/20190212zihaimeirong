<?php
namespace app\shop\validate;

use think\Validate;

class Goods extends Validate
{
    protected $rule = [
        "title|名称" => "require",
        "saleprice|销售价格" => "require",
        "typeid|商品分类" => "require",
        "picurl|商品图" => "require",
    ];
}
