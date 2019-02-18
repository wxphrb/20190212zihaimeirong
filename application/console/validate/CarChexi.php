<?php
namespace app\console\validate;

use think\Validate;

class CarChexi extends Validate
{
    protected $rule = [
        "p_pinpai_id|所属品牌" => "require",
        "p_chexi|车系名称" => "require",
    ];
}
