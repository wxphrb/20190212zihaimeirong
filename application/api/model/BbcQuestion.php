<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/11
 * Time: 10:52
 */

namespace app\api\model;


use think\Model;

class BbcQuestion extends Model
{
    protected static function init()
    {
        BbcQuestion::event('before_insert', function ($query) {
            $query->create_time = time();
//            $query->picarr = serialize($query->picarr);
        });


    }

    public function getPicarrAttr($value)
    {
        return !empty($value)?unserialize($value):[];
    }
}

