<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/11
 * Time: 10:52
 */

namespace app\home\model;


use think\Model;

class BbcQuestion extends Model
{
//    protected $resultSetType = 'collection';
    protected static function init()
    {
        BbcQuestion::event('before_insert', function ($query) {
            $query->create_time = time();
        });

    }
}