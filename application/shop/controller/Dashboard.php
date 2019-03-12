<?php
namespace app\shop\controller;
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

        return $this->fetch('index', [
        	'count'=>$count,
        ]);
	}

}