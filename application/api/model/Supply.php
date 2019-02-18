<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/11
 * Time: 10:52
 */

namespace app\api\model;


use think\Model;

class Supply extends Model
{
    protected static function init()
    {
        Supply::event('before_insert', function ($query) {
            $query->create_time = time();
        });

    }
}