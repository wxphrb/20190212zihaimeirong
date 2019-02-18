<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/11
 * Time: 10:52
 */

namespace app\api\model;


use think\Model;

class Posts extends Model
{
    protected static function init()
    {
        Posts::event('before_insert', function ($query) {
            $query->create_time = time();
        });

    }
    public function getPicarrAttr($value)
    {
        return !empty($value)?unserialize($value):[];
    }
}