<?php

namespace app\shop\controller;

use think\Db;

use think\Request;

use think\Session;
use think\Url;

use app\shop\model\StoreRecord as ThisModel;



class Finance extends Base

{

    /**

	 * [index description]列表

	 * @return [type] [description]

	 */

	public function index()

	{
	    $shop_id = Session::get('shop_id');
        $where   = array();

        if (Request::instance()->isPost()) {

            $name = Request::instance()->param('name');

            if ($name) {

                $where = [

                    'u.title|s.content' => ['like', '%' . $name . '%'],

                ];

                $this->assign("name", $name);

            }

        }
        $date = date('Y-m-d');
        //$par['day_order']        = ThisModel::whereTime('create_time','>=',$date)->where('store_id',$shop_id)->count();
        $par['day_order_price']   = ThisModel::whereTime('create_time','>=',$date)->where(['store_id'=>$shop_id,'type'=>0])->sum('money');

        $par['day_member_price']  = ThisModel::whereTime('create_time','>=',$date)->where(['store_id'=>$shop_id,'type'=>1])->sum('money');

        $par['all_order_price']   = ThisModel::where(['store_id'=>$shop_id,'type'=>0])->sum('money');

        $par['all_member_price']  = ThisModel::where(['store_id'=>$shop_id,'type'=>1])->sum('money');

        /*
		$data = db('store_record')->alias('s')
                ->join('store u','u.id = s.store_id')
                ->field('u.title,u.mobile,u.pro,u.city,u.area,s.id,s.money,s.content,s.create_time')
                ->order('s.id', 'desc')
                ->where($where)
                ->paginate(20);
        */

        return $this->fetch('index', [

            //'list'       => $data,
            'par'        => $par

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

			$result = ThisModel::saveVerify($data);

			if (true === $result) {

                $this->success('新建成功', 'shop/user_message/index');

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

                $this->success('更新成功', 'shop/user_message/index');

            } else {

                $this->error($result);

            }

		}



		$data = ThisModel::get($id);

		$param = array_merge(['vo'=>$data], $this->appendarg());



		return $this->fetch('create', $param);

	}

    /**

     * 添加修改时候需要传递参数的话，用此方法，只写一遍

     */

	public function appendarg(){

		return [

		   //添加参数

		];

	}

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

			    $this->success('删除成功', 'shop/user_message/index');

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

	public function renewfield()
    {

        if (Request::instance()->isPOST()) {

            $data = Request::instance()->post();


            $par['id'] = $data['id'];
            $par['is_read'] = 1;


            $this_model = new ThisModel();

            if ($this_model->update($par)) {

                $this->success('更新成功', 'shop/user_message/index');

            } else {

                $this->error($this_model->getError());

            }

        }

    }

}

