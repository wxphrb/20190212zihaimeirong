<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
use think\Request;
use think\Url;

class About extends Base
{
	public function index(){

		$list = DB::name('info')->field('id,title,content')->find(1);
	
        return $this->fetch('index',[
        	  'list'=>$list,
        	]);
	}
}