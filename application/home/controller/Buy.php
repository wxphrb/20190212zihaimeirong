<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/6
 * Time: 13:37
 */

namespace app\home\controller;

use think\Db;
use think\Request;
use think\Session;

class Buy extends Base
{
    public function car_list(Request $request)
    {
        $data = Db::name('car')
            ->alias('c')
            ->where('c.user_id', Session::get("user_id"))
            ->field('c.*,g.title gtl,s.title')
            ->join('mx_goods g','g.id=c.goods_id', 'LEFT')
            ->join('mx_store s','s.id=g.store_id', 'LEFT')
            ->order("c.create_time", "desc")
            ->select();

        $sum = Db::name('car')->where('user_id', Session::get("user_id"))->sum('price*num');
        return $this->fetch("carlist",[
            'title'=>"购物车",
            'data' =>$data,
        ]);
    }
}