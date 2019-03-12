<?php
namespace app\shop\controller;
use think\Db;
use think\Request;
use think\Url;

//表单构建器
class Formbuilder extends Base
{
	public function index(){
		return $this->fetch('index', [
        ]);
	}
}