<?php
namespace app\console\controller;
use think\Db;
use think\Request;
use think\Url;

class Dashboard extends Base
{
	public function index()
	{
		$count = [];
		$count['user'] = Db::name('user')->count();
		$count['manager'] = Db::name('manager')->count();
		$count['authgroup'] = Db::name('authgroup')->count();
		$count['goods'] = Db::name('goods')->count();
		$count['message'] = Db::name('feedback')->count();
		$count['supply'] = Db::name('supply')->count();
		$count['posts'] = Db::name('posts')->count();
		$count['store'] = Db::name('store')->count();
		$count['orders'] = Db::name('orders')->count();

        return $this->fetch('index', [
        	'count'=>$count,
        ]);
	}

}