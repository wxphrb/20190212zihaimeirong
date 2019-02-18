<?php

namespace app\index\controller;

use app\index\model\Info as ThisModel;

use think\Controller;

use think\Db;

use think\Request;

use think\Url;


class Financial extends Base

{
	public function _initialize() {
		parent::_initialize();
	}

	public function index( Request $request){

        $data = ThisModel::where('status',1)->order(['orderid'=>'desc','id'=>'desc'])->paginate(20);
        $this->assign('bottom',5);
        $this->assign('data',$data);
		$url = request()->domain().Request::instance()->url();
		$this->assign('url',$url);
		$this->assign('share_title', '宝泉岭尊业汽车 - 金融方案');
        return $this->fetch('index');


	}
    public function show($id){

        $data = ThisModel::get($id);
        if(empty($data)){
            $this->error('文章不见了');
        }
		$url = request()->domain().Request::instance()->url();
		$this->assign('url',$url);
		$this->assign('share_title', '金融方案 - '.$data['title']);
		$share_img = request()->domain().$data['picurl'];
		$this->assign('share_img', $share_img);
        $data['create_time'] = date('Y-m-d H:i:s',$data['create_time']);
        $this->assign('bottom',5);
        $this->assign('data',$data);
        return $this->fetch();


    }

    public function info($type,$id){
        $infoModel = new ThisModel;
        $count_qi  = 0;
        if($type=='old'){
            $table  =   'old_car';
            $field  =   'vehicleName';
            $field2 =   'picurl';
        }else{
            $table  =   'new_car';
            $field  =   'p_chexingmingcheng';
            $field2 =   'p_chexizhanshitu';
        }
        $c_info = db($table)->field($field.','.$field2.',car_price')->where(['id'=>$id])->find();
        $car_info['car_price'] = round($c_info['car_price']/10000,2);
        $car_info['title']     = $c_info[$field];
        $car_info['picurl']    = $c_info[$field2];
        $info =   db($table)->field('car_price')->where(['id'=>$id])->find();
        $data = $infoModel->fin_info($type,$id,$info['car_price']);
        $this->assign('car_info',$car_info);
        $this->assign('data',$data);
        $this->assign('id',$id);

        $this->view->engine->layout('layout_show');

        return $this->fetch();
    }



}
