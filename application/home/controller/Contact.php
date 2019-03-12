<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
use think\Request;
use think\Url;

class Contact extends Base
{
	public function index(){

		$request = Request::instance();

		if ($request->isPost()) {

            $data['truename'] = $request->post('truename');
            $data['mobile'] = $request->post('mobile');
            $data['content'] = $request->post('content');
            $data['ip'] = $request->ip();
            $data['create_time'] = time();

            Db::name('message')->insert($data);

            $this->success('提交成功');
        }
	
        return $this->fetch();
	}
}