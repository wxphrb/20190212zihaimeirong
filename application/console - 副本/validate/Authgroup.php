<?php
namespace app\console\validate;

use think\Validate;

class Authgroup extends Validate
{
	protected $rule =   [
        'title|管理组名称'  => 'require|max:30|unique:authgroup',   
    ];
    
}
