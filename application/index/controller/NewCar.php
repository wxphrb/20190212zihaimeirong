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



class NewCar extends Base

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

            //关键词筛选
            $p_name = isset($base_param['name'])?$base_param['name'] : '';

            $r_name = Request::instance()->param('name');

            $name   = isset($r_name) ? Request::instance()->param('name') : $p_name;

            if ($name) {

                $where['n.p_pinpai|n.p_chexi|n.p_chexingmingcheng' ] =  ['like', '%' . $name . '%'];

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
                            'n.id' => 'desc',
                        ];
                        $ai_title = "最新上架";
                        break;
                    case 2:
                        $order = [
                            'n.car_price' => 'asc',
                        ];
                        $ai_title = "价格最低";
                        break;
                    case 3:
                        $order = [
                            'n.car_price' => 'desc',
                        ];
                        $ai_title = "价格最高";
                        break;
                }
                $this->assign('ai_title',$ai_title);
            }
            //车系找车
            $chexi_id = '';

            $chexi_id = isset($base_param['chexi_id'])?$base_param['chexi_id'] : '';
            $r_chexi_id = Request::instance()->param('chexi_id');
            $chexi_id = isset($r_chexi_id) ? Request::instance()->param('chexi_id') :$chexi_id;

            if ($chexi_id) {
                $chexi_info = db('car_chexi')->where(['p_chexi_id'=>$chexi_id])->field('p_chexi')->find();

                if($chexi_info){
                    $where['n.p_chexi_id'] =  $chexi_id;
                }

                $this->assign('brand_title',$chexi_info['p_chexi']);
                $brand = '';

            }else {
                //品牌筛选
                $p_brand = isset($base_param['brand']) ? $base_param['brand'] : '';
                $r_brand = Request::instance()->param('brand');
                $brand = isset($r_brand) ? Request::instance()->param('brand') : $p_brand;

                if ($brand) {
                    $brand_info = db('new_car')->where(['p_pinpai_id' => $brand])->field('p_pinpai')->find();

                    if ($brand_info) {
                        $where['n.p_pinpai_id'] = $brand;
                    }

                    $this->assign('brand_title', $brand_info['p_pinpai']);

                }
            }
            //价格筛选

            if(Request::instance()->param('price1')){
                $between = Request::instance()->param('price1')*10000 . ',' . Request::instance()->param('price2')*10000;
                $where['floor(n.car_price)'] = ['between', $between];
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
                        //$between = $jiage_info['param1']*10000 . ',' . $jiage_info['param2']*10000;
                        $where['floor(n.car_price)'] = jiage_where($jiage_info['param1']*10000,$jiage_info['param2']*10000);
                        $this->assign('jiage_title', $jiage_info['title']);
                    }

                }
            }

            $data_param = ['name'=>$name,'ai'=>$ai,'brand'=>$brand,'jiage_id'=>$jiage_id,'chexi_id'=>$chexi_id];

            $base_param = urlencode(encryptDecrypt($data_param,1));

        }
		if (Request::instance()->isAjax()){
				if(!empty( Cache::get('p_jibie'))){
	
					$where['p_jibie'] = Cache::get('p_jibie');
	
				}
				if(!empty(Cache::get('p_pailiang_ml'))){
	
					$where['p_pailiang_ml'] = Cache::get('p_pailiang_ml');
	
				}
				if(!empty(Cache::get('p_huanbaobiaozhun'))){
	
					$where['p_huanbaobiaozhun'] = Cache::get('p_huanbaobiaozhun');
	
				}
				if(!empty(Cache::get('p_biansuxiang'))){
	
					$where['p_biansuxiang'] = Cache::get('p_biansuxiang');
	
				}
				if(!empty(Cache::get('p_waiguanyanse'))){

					$where['p_waiguanyanse'] = Cache::get('p_waiguanyanse');

				}
			}
        /*
         if(!empty(session('p_jibie'))){
             $where['p_jibie'] = session('p_jibie');
         }
         if(!empty(session('p_pailiang_ml'))){
             $where['p_pailiang_ml'] = session('p_pailiang_ml');
         }
         if(!empty(session('p_huanbaobiaozhun'))){
             $where['p_huanbaobiaozhun'] = session('p_huanbaobiaozhun');
         }
         if(!empty(session('p_biansuxiang'))){
             $where['p_biansuxiang'] = session('p_biansuxiang');
         }
        */
        if (Request::instance()->post()) {
            $post_data    = Request::instance()->post();

            foreach ($post_data as $k=>$v){

                if($k == 'chexing' && $v!=0){
                    $type_res = $CarType->type_info($v);
					
                    $where['p_jibie']           = chexing_where($type_res['title']);
					
                    //session('p_jibie',chexing_where($type_res['title']));
					Cache::set('p_jibie', $where['p_jibie']); 
                };
                if($k == 'pailiang' && $v!=0 ) {
                    $type_res = $CarType->type_info($v);
					
                    $where['p_pailiang_ml']     = pailiang_where($type_res['param1'], $type_res['param2']);
					
					Cache::set('p_pailiang_ml', $where['p_pailiang_ml']); 
                    //session('p_pailiang_ml',pailiang_where($type_res['param1'], $type_res['param2']));
                }
                if($k == 'paifangbiaozhun' && $v!=0) {
                    $type_res = $CarType->type_info($v);
					
                    $where['p_huanbaobiaozhun'] = paifangbiaozhun_where($type_res['param1']);
					
					Cache::set('p_huanbaobiaozhun', $where['p_huanbaobiaozhun']); 
                   // session('p_huanbaobiaozhun',paifangbiaozhun_where($type_res['param1']));
                }
                if($k == 'yanse' && $v!=0) {
                    $type_res = $CarType->type_info($v);
                    $where['p_waiguanyanse'] = color_where($type_res['title']);
                    Cache::set('p_waiguanyanse', $where['p_waiguanyanse']);
                    /* $type_res = $CarType->type_info($v);
                     $where['p_waiguanyanse'] = paifangbiaozhun_where($type_res['param1']);*/
                }
                if($k == 'biansuxiang' && $v!=0){
                    $type_res = $CarType->type_info($v);
					
                    $where['p_biansuxiang']    = biansuxiang_where($type_res['param1']);
					
					Cache::set('p_biansuxiang', $where['p_biansuxiang']); 
                    //session('p_biansuxiang',chexing_where($type_res['param1']));
                    //session('p_biansuxiang',biansuxiang_where($type_res['param1']));
                }
            }
            $this->assign('more_title','1');
            $this->assign('post_data',$post_data);
        }

        $jiage_type = db('car_type')->field('id,title,param1,param2')->where(['parentid'=>43])->select();
        //$jiage_type_list = db('car_type')->field('id,title,param1,param2')->where('parentid',"<>",'43')->select();
        $this->showtype(0);
        $car_brand  = $CarBrand->brand_list();
        /*
        if(empty($where)){

            $where = ['n.recommend'=>1];
        }
        */
        if(empty($order)){

            $order = ['n.recommend'=>'desc'];
        }
        $res = db::name('new_car')->alias('n')
            ->join('car_brand b','b.p_pinpai_id = n.p_pinpai_id','left')
            ->field('n.id,b.p_pinpai,n.recommend,n.p_chexi,n.p_changshangzhidaojia_yuan,n.p_shangshishijian,n.p_chexingmingcheng,n.p_chexizhanshitu,n.car_price,n.status')
            ->where(['b.status'=>1,'n.status'=>1])
            ->where($where)
            ->order($order)
            ->paginate(10);
        $this->assign('name',$name);
        $this->assign('ai',$ai);
        $this->assign('brand',$brand);
        $this->assign('jiage_id',$jiage_id);
        $this->assign('bottom',2);

        if(Request::instance()->param('aj') == 1){
            //ajax分页
            return $res;
        }
		$url = request()->domain().Request::instance()->url();
		$this->assign('url',$url);
		$this->assign('share_title', '宝泉岭尊业汽车 - 新车列表');
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
        $car_info = db('new_car')->where(['id'=>$id])->find();
        if(empty($car_info['picarr'])){
            $banner[] = ['img'=>$car_info['p_chexizhanshitu']];
        }else{
            $banner   = unserialize($car_info['picarr']);
        }
        //金融方案
        $f_info =  $info->fin_info('new',$id,$car_info['car_price'],2);
        $car_parma = parent::car_param_list();
        $this->assign('banner',$banner);
        $this->assign('f_info',$f_info);
        $this->assign('car_parma',$car_parma);
        $this->assign('car_info',$car_info);
        $this->view->engine->layout('layout_show');
		
		$url = request()->domain().Request::instance()->url();
		$this->assign('share_title', $car_info['p_chexingmingcheng']);
		$share_desc  = "新车指导价：".$car_info['p_changshangzhidaojia_yuan']."|".$car_info['p_fadongji'];
        $this->assign('share_desc', $share_desc);
		$share_img = request()->domain().$banner[0]['img'];
		//判断url地址是否包含中文 不包含则输出图片
		if (!preg_match("/[\x7f-\xff]/", $share_img)) {
			$this->assign('share_img', $share_img);
		}
		
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

        $res = db::name('car_type')->field('id,parentid,title,picurl,param1,param2,en_name')->where(['parentid'=>$id,'id'=>['not in',[2,3,43]]])->order('orderid','asc')->select();

		
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
