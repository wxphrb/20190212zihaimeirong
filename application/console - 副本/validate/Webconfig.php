<?php
namespace app\console\validate;

use think\Validate;

class Webconfig extends Validate
{
    protected $rule =   [
        'varname|变量名称'  => 'require|max:30|unique:webconfig',
        'varinfo|参数说明'  =>  'require|max:30|unique:webconfig',
    ];

}
