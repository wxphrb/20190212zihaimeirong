<?php

namespace app\shop\controller;

use think\Db;

use think\Request;

use think\response\Json;

use think\Session;

use think\Url;

use app\shop\model\Goods as ThisModel;

use app\shop\model\Store;

use app\shop\model\Evaluate;



class Goods extends Base

{

	public $arr_type=[];

    /**

	 * [index description]列表

	 * @return [type] [description]

	 */

	public function index()

	{

        $name = array();

        $where = ['store_id'=>Session::get("shop_id")];

        if (Request::instance()->isPost()) {

            $name = Request::instance()->param('name');

            if ($name) {

                $where = [

                    'username|mobile' => ['like', '%' . $name . '%'],

                ];

            }

        }

		$data = ThisModel::order('id', 'desc')->where($where)->paginate(30, false, ['query' => array('name' => $name)]);;



        return $this->fetch('index', [

            'list'       => $data

        ]);

	}



	public function show($id)

	{

		$data =ThisModel::get($id);

		

        return $this->fetch('show', [

            'vo'       => $data

        ]);

	}

	/**

     * [create description]添加方法

     * @return [type] [description]

     */

	public function create()
	{
		if (Request::instance()->isPOST())
		{
			$data = Request::instance()->post();
			$data['store_id'] = Session::get("shop_id");
			$result = ThisModel::saveVerify($data);
			if (true === $result) {
                $this->success('新建成功', 'shop/Goods/index');
            } else {
                $this->error($result);
            }
		}
		$param = $this->appendarg();

		return $this->fetch('create', $param);
	}



    /**

     * [update description]更新方法

     * @param  [type] $id [description]主键id

     * @return [type]     [description]

     */

	public function update($id)

	{

		if (Request::instance()->isPOST())

		{

			$data = Request::instance()->post();

			$result = ThisModel::saveVerify($data,$id);

			if (true === $result) {

                $this->success('更新成功', 'shop/Goods/index');

            } else {

                $this->error($result);

            }

		}
		$data = ThisModel::get($id);
		$param = array_merge(['vo'=>$data], $this->appendarg());
		return $this->fetch('create', $param);

	}


	public function showtype($id =0, $i=0){
		$type = Db::name("store")->where(['id' => Session::get("shop_id")])->value("type_id");

//		$res = db::name('goods_type')->field('id,parentid,title')->where('id','in',$type)->order('orderid','asc')->select();
		if($id==0){
			$res = db::name('goods_type')->field('id,parentid,title')->where('parentid',$id)->where('id','in',$type)->order('orderid','asc')->select();
		}else{
			$res = db::name('goods_type')->field('id,parentid,title')->where('parentid',$id)->order('orderid','asc')->select();

		}


		foreach($res as &$v){
			$title='';
			for($p=1; $p<$i; $p++){
				$title.='&nbsp;&nbsp;&nbsp;';
			}
			if($v['parentid'] != 0){
				$title.='|- ';
			}
			$title.=$v['title'];
			$v['title'] = $title;
			array_push($this->arr_type, $v);
			$this->showtype($v['id'], $i+2);
		}
	}




    /**

     * 添加修改时候需要传递参数的话，用此方法，只写一遍

     */

	public function appendarg(){


		$this->showtype(0);
//        $type = explode(',', $type);
//        if ($type[0] != '') {
//            foreach ($type as &$v) {
//                $v = Db::name("goods_type")->where("id", $v)->field("title,id,picurl")->find();
//            }
//        }else{
//            $type = array();
//        }
        return [
            'goodstype' => $this->arr_type
        ];

	}





	/*获取二级分类*/
    public function getTwoLevelGoodsType(Request $request)
    {
        $jump = $request->param("jump");
        $data = Db::name("GoodsType")
            ->where("parentid", $jump)
            ->field("id,title")
            ->select();
        return Json::create($data);
    }

    /**

     * [showtype description]无限极分类

     * @param  integer $id [description]

     * @param  integer $i  [description]

     * @return [type]      [description]

     */

//    public function showtype($id =0, $i=0){
//
//    	$res = db::name('goods_type')->field('id,parentid,title')->where('parentid',$id)->order('orderid','asc')->select();
//
////        halt($res);
//
//
//		foreach($res as &$v){
//
//			$title='';
//
//			for($p=1; $p<$i; $p++){
//
//			  $title.='&nbsp;&nbsp;&nbsp;';
//
//			}
//
//			if($v['parentid'] != 0){
//
//				$title.='|- ';
//
//			}
//
//			$title.=$v['title'];
//
//			$v['title'] = $title;
//
//			array_push($this->arr_type, $v);
//
//			$this->showtype($v['id'], $i+2);
//	    }
//    }

    /**

     * [delete description]删除方法 多选和单选删除

     * @return [type] [description]

     */

	public function delete(){

		if (Request::instance()->isPOST())

		{

			$id = Request::instance()->post('id/a'); // (/a)方法 将收到的id转为数组

			$delmodel = ThisModel::destroy($id);

			if($delmodel){

			    $this->success('删除成功', 'shop/Goods/index');

			}

			else{

				$this->error($delmodel->getError());

			}

	    }

	    else{

	    	$this->error('请求方式出错!');

	    }

	}

    /**

     * [renewfield description]列表更新字段

     * @return [type] [description]

     */

	public function renewfield(){

		if (Request::instance()->isPOST())

		{

            $data = Request::instance()->post();

			$validate = validate('Goods');



			$post = Request::instance()->except(['id'],'post');

			$post = array_keys($post);



            $validate->scene('edit', $post);

			if(!$validate->scene('edit')->check($data)){

			    $this->error($validate->getError());

			}

	        $this_model = new ThisModel();

	        if($this_model->update($data))

            {

			    $this->success('更新成功', 'shop/Goods/index');

			}

			else{

				$this->error($this_model->getError());

			}

		}

	    else{

	    	$this->error('请求方式出错!');

	    }

	}



	public function getattr(){

		if (Request::instance()->isPOST())

		{

            $typeid = Request::instance()->post('typeid');



            $attrs = db::name('goods_type')->where('id',$typeid)->value('attrs');



            $attrs = unserialize($attrs);



			return json_encode($attrs);

		}

	    else{

	    	$this->error('请求方式出错!');

	    }

	}





}

