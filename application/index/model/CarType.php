<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/11
 * Time: 10:52
 */

namespace app\index\model;


use think\Model;

class CarType extends Model
{
    protected $table = 'mx_car_type';


    public function type_info ($varname="") {

        $Model = new CarType();

        $info  = $Model->where('id',$varname)->field('title,en_name,param1,param2')->find();

        return $info;
    }
}