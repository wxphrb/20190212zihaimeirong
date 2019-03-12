<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
use think\Request;
use think\Url;
use think\Session;

class Infolist extends Base
{

	public function index(){
		if(Request::instance()->has('classid','get')){
			Session::set('classid', Request::instance()->param('classid'));
		}
		
		$where['classid'] = Session::get('classid');
		$infoclass = DB::name('infoclass')->field('title')->find($where['classid']);
		
        $where['status'] = '1';
		$list = DB::name('infolist')->field('id,title,picurl,intro,create_time')->where($where)->order('orderid')->paginate(3);
	
        return $this->fetch('index',[
        	  'title'=>$infoclass['title'],
        	  'list'=>$list,
        	]);
	}

	public function show($id){
		$voo = DB::name('infolist')->field('id,classid,title,content,create_time')->where('id',$id)->find();

		$where['classid'] = $voo['classid'];
		$where['status'] = '1';

		$list = DB::name('infolist')->field('id,title')->where($where)->order('id desc')->limit(10)->select();

		return $this->fetch('show',[
        	  'voo'=>$voo,
        	  'list'=>$list,
        	]);
	}
}