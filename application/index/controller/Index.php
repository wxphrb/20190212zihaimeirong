<?php

namespace app\index\controller;

use app\index\model\Banner;
use app\index\model\Nav;
use think\Controller;

use think\Db;

use think\Request;

use think\Url;



class Index extends Base

{
	public function _initialize() {
		parent::_initialize();
		 //wcc($data);

	}

	public function index(Banner $banner){
        //轮播图

        $data['banner']   = $banner->where(['status'=>1])->field('id,picurl,target,content')->order(['orderid'=>'desc','create_time'=>'desc'])->select();
        $data['brand']    = db('car_brand')->where(['status'=>1,'recommend'=>1])->field('id,p_pinpai_id,p_pinpai,p_pinpai_logo')->order('id desc')->limit(8)->select();
        $data['chexing']  = db('car_type')->where(['parentid'=>1])->field('id,title,picurl')->order('id desc')->limit(6)->select();
        $jiage_type 	  = db('car_type')->field('id,title,param1,param2')->where(['parentid'=>43])->select();
        $car_list   	  = db('old_car')->where(['recommend'=>1])->limit(8)->select();
        $nav        	  = new Nav;
        $where['status']      = 1;
        $where['hot_state']   = ['<>',1];
        $nav_list   = $nav->where($where)->limit(8)->select();
        $where_hot['status']  = 1;
        $where_hot['hot_state'] = ['=',1];
        $nav_hot    = $nav->field('id,picurl,title')->where($where_hot)->limit(8)->select();
        $nav_hot_count  = $nav->field('id,picurl,title')->where($where_hot)->limit(8)->count();
        /*
		$nav_banner = '';
        if(!empty($nav_hot)){
            $nav_banner[] = $nav_hot[0];
           // $nav_hot    = array_splice($nav_hot,0);
        }*/
        if($nav_hot_count<=1 ){
            $nav_hot = [];
        }
        $wherek['status']  = 1;
        $wherek['picurl'] = ['<>',''];
        $kehu_list   = db('kehu')->where($wherek)->limit(5)->order('id','desc')->select();
        $this->assign('nav_list',$nav_list);
        $this->assign('kehu_list',$kehu_list);
        //$this->assign('nav_banner',$nav_banner);
        $this->assign('nav_hot',$nav_hot);
        $this->assign('data',$data);
        $this->assign('car_list',$car_list);
        $this->assign('jiage_type',$jiage_type);
        $this->assign('bottom',1);
	    return $this->fetch('index');

	}

	public function banner(Request $Request){
	   	$id     					= $Request->param('id');
			$banner_con       = db('banner')->where('id',$id)->field('title,content')->find();
		/*  $goods   = db('supply')
				    ->alias('s')
					->join('job j', 'g.id=c.goods_id', 'LEFT')
					->where('title','like','%'.$keyword.'%')
					->paginate(6);*/

		  $this->assign('title',"搜索结果");
		  $this->assign('banner_con',$banner_con);
		  $this->assign('active','');
			return $this->fetch();
	}

}
