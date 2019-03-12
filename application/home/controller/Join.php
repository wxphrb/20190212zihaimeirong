<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
use think\Request;
use think\Url;

class Join extends Base
{
	public function index(){

		$where['status'] = '1';
		$list = DB::name('job')->field('id,title,content')->where($where)->order('orderid')->select();
	
        return $this->fetch('index',[
        	  'list'=>$list,
        	]);
	}
}