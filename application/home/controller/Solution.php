<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
use think\Request;
use think\Url;

class Solution extends Base
{
	public function index(){

		$where['status'] = '1';
		$list = DB::name('solution')->field('id,title,intro,picurl')->where($where)->order('orderid')->select();
	
        return $this->fetch('index',[
        	  'list'=>$list,
        	]);
	}

	public function show($id){
		$voo = DB::name('solution')->field('id,title,content')->where('id',$id)->find();

		return $this->fetch('show',[
        	  'voo'=>$voo,
        	]);
	}
}