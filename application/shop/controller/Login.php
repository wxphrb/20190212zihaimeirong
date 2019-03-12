<?php
namespace app\shop\controller;
use think\Db;
use think\Request;
use think\Url;
use think\Session;
use think\Controller;
use app\shop\model\Manager;
class Login extends Controller
{

	public function index(){		
		if (Request::instance()->isPost()) {
            if (Manager::login()) {
				$this->success('登录成功', 'shop/dashboard/index');
            } else {
                $this->error('登录失败');
            }
        }

        /**
         * 检测用户是否登录
         */
        if (true === Session::has('shop_id')) {
            $this->redirect('shop/dashboard/index');
        }

        return $this->fetch('./login', [
            "mate_title" => '管理员登录'
        ]);
	}




    public function logout(){
        Session::delete('shop_id');
        $this->redirect('shop/login/index');
    }

    /**
     * [update description]更新方法
     * @param  [type] $id [description]主键id
     * @return [type]     [description]
     */
    public function myinfo($id)
    {
        if (Request::instance()->isPOST())
        {
            $post_data = Request::instance()->post();
            $result = Manager::saveVerify($post_data,$id);
            if (true === $result) {
                $this->success('更新成功');
            } else {
                $this->error($result);
            }
        }
    }

     //控制器中 获取验证码
    public function get_captcha(){    
        //使用memcheck 设置session    
        //Session::init(['prefix'=> 'wll_','type'=> '','auto_start' => true]);
        $captcha = new \think\Captcha(86,48,4);
        echo $captcha->showImg();        
       //Session::set('code',$captcha->getCaptcha());
        exit;    
    }
}