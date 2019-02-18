<?php
namespace app\console\controller;
use think\Db;
use think\Request;
use think\Url;

class Index extends Base
{
    /**
	 * [index description]列表
	 * @return [type] [description]
	 */
	public function index()
	{
		$this->redirect('login');
	}
}
