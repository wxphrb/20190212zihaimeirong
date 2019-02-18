<?php

namespace app\index\controller;

use app\index\model\CarBrand;
use app\index\model\CarType;
use app\index\model\Info;

use think\Controller;

use think\Db;

use think\Request;
use think\Session;
use think\Cache;
use think\Url;



class OldCar extends Base

{
    public function _initialize() {
        parent::_initialize();
    }

    public $arr_type=[];

    public function index( Request $request,CarBrand $CarBrand,CarType $CarType){
        $name       = '';
        $ai         = '';
        $brand      = '';
        $jiage_id   = '';
        $where      = [];
        $order      = [];
        $data_param = [];
        $base_param = '';
		if (!Request::instance()->isAjax()){

			Cache::clear();
		}
        if (Request::instance()->param()) {

            if(Request::instance()->param('base_param')){

                $data_param = Request::instance()->param('base_param');

                $base_param = encryptDecrypt(urldecode($data_param),0);

            }
			//车型筛选
			//关键词筛选
            $p_bodyType = isset($base_param['bodyType'])?$base_param['bodyType'] : '';

            $r_bodyType = Request::instance()->param('bodyType');

            $n_bodyType = isset($r_bodyType) ? Request::instance()->param('bodyType') : $p_bodyType;

            if ($n_bodyType) {

                $where['bodyType' ] =  old_chexing_where($n_bodyType);

            }

			if (Request::instance()->isAjax()){
				if(!empty( Cache::get('bodyType'))){
	
					$where['bodyType'] = Cache::get('bodyType');
	
				}
				if(!empty(Cache::get('displacement'))){
	
					$where['displacement'] = Cache::get('displacement');
	
				}
				if(!empty(Cache::get('effluentStandard'))){
	
					$where['effluentStandard'] = Cache::get('effluentStandard');
	
				}
				if(!empty(Cache::get('gearboxType'))){
	
					$where['gearboxType'] = Cache::get('gearboxType');
	
				}
				if(!empty(Cache::get('uploadDate'))){

					$where['uploadDate'] = Cache::get('uploadDate');

				}
				if(!empty(Cache::get('gearboxType'))){
	
					$where['gearboxType'] = Cache::get('gearboxType');
	
				}
			}
            //关键词筛选
            $p_name = isset($base_param['name'])?$base_param['name'] : '';

            $r_name = Request::instance()->param('name');

            $name   = isset($r_name) ? Request::instance()->param('name') : $p_name;

            if ($name) {

                $where['brandName|groupName|vehicleName' ] =  ['like', '%' . $name . '%'];

            }
            //智能筛选
            $p_ai   = isset($base_param['ai'])?$base_param['ai'] : '';

            $r_ai   = Request::instance()->param('ai');

            $ai     = isset($r_ai) ? Request::instance()->param('ai') :$p_ai;

            $ai_title = "";

            if ($ai) {

                switch ($ai){

                    case 1:
                        $order = [
                            'id' => 'desc',
                        ];
                        $ai_title = "最新上架";
                        break;
                    case 2:
                        $order = [
                            'listPrice' => 'asc',
                        ];
                        $ai_title = "价格最低";
                        break;
                    case 3:
                        $order = [
                            'listPrice' => 'desc',
                        ];
                        $ai_title = "价格最高";
                        break;
                }

                $this->assign('ai_title',$ai_title);
            }
            //车系找车
            $chexi_id = '';

            $chexi_id   = isset($base_param['chexi_id'])?$base_param['chexi_id'] : '';

            $r_chexi_id = Request::instance()->param('chexi_id');

            $chexi_id   = isset($r_chexi_id) ? Request::instance()->param('chexi_id') :$chexi_id;

            if ($chexi_id) {

                $chexi_info = db('car_chexi')->where(['p_chexi_id'=>$chexi_id])->field('p_chexi')->find();

                if($chexi_info){

                    $where['p_chexi_id'] =  $chexi_id;

                }

                $this->assign('brand_title',$chexi_info['p_chexi']);

                $brand = '';

            }else {
                //品牌筛选
                $p_brand = isset($base_param['brand']) ? $base_param['brand'] : '';

                $r_brand = Request::instance()->param('brand');

                $brand = isset($r_brand) ? Request::instance()->param('brand') : $p_brand;


                if ($brand) {

                    $brand_info = db('car_brand')->where(['p_pinpai_id' => $brand])->field('p_pinpai')->find();

                    if ($brand_info) {

                        $brandName = $brand_info['p_pinpai'];

                        $this->assign('brand_title', $brandName);

                    }
                    $where['p_pinpai_id'] = $brand;
                }
            }

            //价格筛选
            if(Request::instance()->param('price1')){

                $between = Request::instance()->param('price1')*10000 . ',' . Request::instance()->param('price2')*10000;

                $where['car_price'] = ['between', $between];

                $jiage_chaxun = 1;

                $this->assign('jiage_title', '自定义价格');

                $this->assign('price1', Request::instance()->param('price1'));

                $this->assign('price2', Request::instance()->param('price2'));

            }else {
                $p_jiage_id = isset($base_param['jiage_id']) ? $base_param['jiage_id'] : '';

                $r_jiage_id = Request::instance()->param('jiage_id');

                $jiage_id = isset($r_jiage_id) ? Request::instance()->param('jiage_id') : $p_jiage_id;

                if (isset($jiage_id)) {

                    $jiage_info = db('car_type')->field('id,title,param1,param2')->where(['parentid' => 43, 'id' => $jiage_id])->find();

                    if ($jiage_info) {

                        $where['car_price'] = jiage_where($jiage_info['param1']*10000,$jiage_info['param2']*10000);;

                        $jiage_chaxun = 1;

                        $this->assign('jiage_title', $jiage_info['title']);
                    }
                }
            }
            $data_param = ['name'=>$name,'ai'=>$ai,'brand'=>$brand,'jiage_id'=>$jiage_id,'chexi_id'=>$chexi_id];
            //wcc($data_param);
            $base_param = urlencode(encryptDecrypt($data_param,1));

        }
        if (Request::instance()->post()) {
            $post_data    = Request::instance()->post();

            foreach ($post_data as $k=>$v){
				
                if($k == 'chexing' && $v!=0){
					
                    $type_res = $CarType->type_info($v);

                    $where['bodyType']           = old_chexing_where($type_res['title']);
					
					Cache::set('bodyType', $where['bodyType']); 
                    //session('bodyType',old_chexing_where($type_res['title']));
                };
                if($k == 'pailiang' && $v!=0 ) {
                    $type_res = $CarType->type_info($v);

                    $where['displacement']     = pailiang_where($type_res['param1'], $type_res['param2']);
					
					Cache::set('displacement', $where['displacement']);
                    //session('displacement',pailiang_where($type_res['param1'], $type_res['param2']));
                }
                if($k == 'paifangbiaozhun' && $v!=0) {
                    $type_res = $CarType->type_info($v);

                    $where['effluentStandard'] = old_paifangbiaozhun_where($type_res['param1']);
					
					Cache::set('effluentStandard', $where['effluentStandard']);
                    //session('effluentStandard',old_paifangbiaozhun_where($type_res['param1']));
                }
                //车龄
                if($k == 'cheling' && $v!=0) {
                    $type_res = $CarType->type_info($v);

                    $year = date('Y');

                    if($type_res['param1'] != 0){
                        $param1 =  $year - $type_res['param1'];
                    }else{
                        $param1 = 0;
                    }
                    if($type_res['param2'] != 0){
                        $param2 =  $year - $type_res['param2'];
                    }else{
                        $param2 = 0;
                    }

                    $where['uploadDate'] = cheling_where($param1,$param2);

                    Cache::set('uploadDate', $where['uploadDate']);
                    //session('effluentStandard',old_paifangbiaozhun_where($type_res['param1']));
                }
                if($k == 'yanse' && $v!=0) {

                     $type_res = $CarType->type_info($v);
                     $where['vehicleColor'] = color_where($type_res['title']);
                    Cache::set('vehicleColor', $where['vehicleColor']);
                }
                if($k == 'biansuxiang' && $v!=0){
                    $type_res = $CarType->type_info($v);

                    $where['gearboxType']    = old_biansuxiang_where($type_res['param1']);

					Cache::set('gearboxType', $where['gearboxType']);
                    //session('gearboxType',old_biansuxiang_where($type_res['param1']));
                }
            }
            $this->assign('more_title','1');

            $this->assign('post_data',$post_data);
        }
        /*
        if(Request::instance()->param('chexing')){

            $type_res = $CarType->type_info(Request::instance()->param('chexing'));

            $where['bodyType']           = old_chexing_where($type_res['title']);

        }*/

        $jiage_type = db('car_type')->field('id,title,param1,param2')->order('orderid','asc')->where(['parentid'=>43])->select();

        $this->showtype(0);
        $car_brand  = $CarBrand->brand_list();

        if(empty($order)){

            $order = ['recommend'=>'desc','id'=>'desc'];
        }
		//$where['status_buy'] = 0;
            $res = db::name('old_car')
                ->field('id,brandName,recommend,car_price,groupName,listPrice,marketDate,vehicleName,picurl,status,status_buy')
                ->where(['status'=>1])
                ->where($where)
                ->order($order)
                ->paginate(10);
        $this->assign('name',$name);
        $this->assign('ai',$ai);
        $this->assign('brand',$brand);
        $this->assign('jiage_id',$jiage_id);
        $this->assign('bottom',3);

        $brand_more =  Request::instance()->param('brand_more');
        $brand_more = !empty($brand_more) ? $brand_more : '';
        $this->assign('brand_more',$brand_more);//首页品牌查看更多
        $che_more   =  Request::instance()->param('che_more');
        $che_more   = !empty($che_more) ? $che_more : '';
        $this->assign('che_more',$che_more);//首页品牌查看更多
        if(Request::instance()->param('aj') == 1){
			
            //ajax分页
            return $res;
        }
		$url = request()->domain().Request::instance()->url();
		$this->assign('url',$url);
		$this->assign('share_title', '宝泉岭尊业汽车 - 二手车列表');
        return $this->fetch('index', [

            'car_list'       => $res,
            'car_brand'      => $car_brand,
            'base_param'     => $base_param,
            'jiage_type'     => $jiage_type,
            'more_type'      => $this->arr_type,

        ]);


    }

    public function show($id){
        $info = new Info;

        $car_info = db('old_car')->where(['id'=>$id])->find();
        if(empty($car_info['picarr'])){
            $banner[] = ['img'=>$car_info['picurl']];
        }else{
            $banner   = unserialize($car_info['picarr']);
        }
        if(!empty($car_info['new_car_id'])){
            $new_car_info = db('new_car')->where(['id'=>$car_info['new_car_id']])->find();
            $car_parma = parent::car_param_list();
        }else{
            $new_car_info = '';
            $car_parma = parent::old_car_param_list();
        }
        /*
        if(empty($banner['img'])){
            unset($banner);
            $banner[] = ['img'=>'/error_small.jpg'];
        }*/
        //金融方案
        //$f_info =  $info->fin_info('old',$id,$car_info['car_price'],2);
		$f_info   = [];
		$f_in     = $info->where('id',4)->find();
		
		$money    = $car_info['car_price'];

		$f_info['title']    = $f_in['title'];
		$f_info['shoufu']   = round(($money-$money*$f_in['param1']* $f_in['param2'])/10000,2);
		$f_info['yuegong']  = round($money*$f_in['param1'] * $f_in['param2'] * $f_in['param3'],2);
		//首付汇率
		$f_info['shoufu_hv'] = 1-$f_in['param1'] * $f_in['param2'];
		$f_info['count_qi']  = 36;

        $this->assign('banner',$banner);
        $this->assign('f_info',$f_info);
        $this->assign('car_parma',$car_parma);
        $this->assign('car_info',$car_info);
        $this->assign('new_car_info',$new_car_info);
        $this->view->engine->layout('layout_show');
		$url = request()->domain().Request::instance()->url();
		$this->assign('share_title', $car_info['vehicleName']);
		$share_desc  = "新车指导价：".round($car_info['listPrice']/10000,2)."万".$car_info['uploadDate']."|".$car_info['licheng']."公里|".$car_info['gearboxType'];
        $this->assign('share_desc', $share_desc);
		$this->assign('share_img', request()->domain().$banner[0]['img']);
		$this->assign('url',$url);
        return $this->fetch('show');
    }

    /**

     * [showtype description]无限极分类

     * @param  integer $id [description]

     * @param  integer $i  [description]

     * @return [type]      [description]

     */

    public function showtype($id=0, $i=0){

        $res = db::name('car_type')->field('id,parentid,title,picurl,param1,param2,en_name')->where(['parentid'=>$id,'id'=>['not in',[3,43]]])->order('orderid','asc')->select();


        foreach($res as &$v){



            if($v['parentid'] != 0){

                $this->arr_type[$v['parentid']]['data'][$v['id']] = $v;

            }else {
                $this->arr_type[$v['id']] = $v;
            }

            $this->showtype($v['id'], $i+2);

        }




    }

}
