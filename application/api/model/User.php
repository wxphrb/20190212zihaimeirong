<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/11
 * Time: 10:52
 */

namespace app\api\model;


use think\Model;

class User extends Model
{
    protected static function init()
    {
        User::event('before_insert', function ($query) {
            $query->create_time =time();//随机账号
            $query->account = '88'.rand(1000000,9000000);//随机账号
           $query->icode = rand(100000,900000);//邀请码
        });

    }
}