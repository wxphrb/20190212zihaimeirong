<?php
namespace app\home\controller;
use app\home\model\Banner;
use app\home\model\Goods;
use app\home\model\Store;
use think\Controller;
use think\Db;
use think\Request;
use think\Url;
class Set extends Controller
{

    public function _initialize()
    {
        if(empty(session('user_id'))){
            $this->redirect('home/login/login');
        }

    }

    /*
     * 设置
     * */
	public function index(Request $request ,Goods $goods , Store $store , Banner $banner){
		return $this->fetch(
			'index',[
				'title'=>"设置",
			]
		);
	}

	//修改密码页面
    public function update_pass()
    {
        return $this->fetch(
            'update_pass',[
                'title'=>"修改密码",
            ]
        );
	}

    public function about()
    {
        $content = Db::name('info')->where("id", 1)->value("content");
        return $this->fetch(
            'about',[
                'title'=>"关于我们",
                'content' => $content
            ]
        );
	}

	//修改密码
    public function doUpdatePass()
    {
        try{
            $data = request()->param();
            $user_id = session('user_id');
            //判断密码
            $password = Db::name('user')->where('id',$user_id)->value('password');
            if($password != $data['original_pass']){
                return ['code' => 400,'message'=>'原密码错误'];
            }
            //判断新密码与原密码是否一致
            if($password == $data['password']){
                return ['code' => 400,'message'=>'新密码与原密码一致'];
            }
            $res =  Db::name('user')->where('id',$user_id)->update(['password'=>$data['password']]);
            if($res)
                return ['code'=>200,'message'=>'修改成功'];
            return ['code'=>400,'message'=>'修改失败'];

        }catch (\Exception $e){
            return ['code'=>400,'message'=>'系统繁忙'];
        }
	}
}