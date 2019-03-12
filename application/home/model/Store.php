<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/11
 * Time: 10:52
 */

namespace app\home\model;


use think\Model;

class Store extends Model
{
    protected $autoWriteTimestamp = 'int';
    /*
         * 关联审核
         */
    public function goods(){
        return $this->hasMany('Goods')->field('picurl,sell,saleprice,title,store_id');
    }

    function goodslist(){
        return $this->hasMany('Goods')->field('id,store_id,picurl,saleprice');
    }



}