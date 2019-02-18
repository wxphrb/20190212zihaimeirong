<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/11
 * Time: 10:52
 */

namespace app\api\model;


use think\Model;

class Car extends Model
{
    protected $autoWriteTimestamp = 'int';
    protected $updateTime = false;
}