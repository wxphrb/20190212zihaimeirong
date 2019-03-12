<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
use think\Request;
use think\Url;
use think\Session;

class Product extends Base
{

	public function index(){
		
		$list = DB::name('product_type')->field('id,title')->order('orderid')->select();
		foreach($list as &$v){
			$where['type_id'] = $v['id'];
			$where['status'] = '1';
			$v['list'] = DB::name('product')->field('id,title,picurl')->where($where)->order('orderid')->select();

		}

        return $this->fetch('index',[
        	  'list'=>$list,
        	]);
	}

	public function show($id){

		$voo = DB::name('product')->field('id,title,content')->where('id',$id)->find();

		return $this->fetch('show',[
        	  'voo'=>$voo,
        	]);


	}
}