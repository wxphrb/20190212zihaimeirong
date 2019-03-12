<?php

namespace app\console\controller;

use think\Db;

use think\Request;

use think\Url;

use app\console\model\Nav as ThisModel;

use app\console\model\NewsInfo;

class Nav extends Base

{

	public $arr_type=[];

    /**

	 * [index description]列表

	 * @return [type] [description]

	 */

	public function index(ThisModel $nav)
	{
        $name = array();
        $where = array();
        if (Request::instance()->isPost()) {
            $name = Request::instance()->param('name');
            if ($name) {
                $where = [
                    'title|content' => ['like', '%' . $name . '%'],
                ];
            }
        }
        $data = $nav->where($where)->order('id desc')->paginate(30, false, ['query' => array('name' => $name)]);
//        dump($data);die;
		return $this->fetch('index', [
            'list'       => $data
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

                $this->success('新建成功', 'console/Nav/index');

            } else {

                $this->error($result);

            }

		}

//		$param = $this->appendarg();



		return $this->fetch('create');

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
                $this->success('更新成功', 'console/Nav/index');
            } else {
                $this->error($result);
            }
		}
		$data = ThisModel::get($id);
//		$param = array_merge(['vo'=>$data], $this->appendarg());
		return $this->fetch('create', [
		    'vo'=>$data
        ]);
	}
    /**
     * 添加修改时候需要传递参数的话，用此方法，只写一遍
     */
	public function appendarg(){
		$this->showtype(0);
		return [
		   //添加参数
		   'nav'=>$this->arr_type
		];
	}

	/**

     * [showtype description]无限极分类

     * @param  integer $id [description]

     * @param  integer $i  [description]

     * @return [type]      [description]

     */

    public function showtype($id=0, $i=0){

    	$res = db::name('nav')->field('id,parentid,title,url,status,orderid')->where('parentid',$id)->order('orderid','asc')->select();



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

     * [delete description]删除方法 多选和单选删除

     * @return [type] [description]

     */

	public function delete(){

		if (Request::instance()->isPOST())

		{

			$id = Request::instance()->post('id/a'); // (/a)方法 将收到的id转为数组

			$delmodel = ThisModel::destroy($id);

			if($delmodel){

			    $this->success('删除成功', 'console/Nav/index');

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

	public function renewfield(NewsInfo $newsInfo){

		if (Request::instance()->isPOST())

		{

            $data = Request::instance()->post();

			$validate = validate('Nav');



			$post = Request::instance()->except(['id'],'post');

			$post = array_keys($post);



            $validate->scene('edit', $post);

			if(!$validate->scene('edit')->check($data)){

			    $this->error($validate->getError());

			}

	        $this_model = new ThisModel();

	        if($this_model->update($data))

            {
				if($data['status']=='1'){
					$request['title'] = "您的资讯已通过审核！";
					$request['type'] = 4;
					$res = $this_model::get($data['id']);
					$newsInfo->isUpdate(false)->allowField(true)->save([
						'other_id'=>$data['id'], 'user_id' => $res['user_id'],'type' =>4,'title' => $request['title'],'status'=>2,'create_time'=>time()
					]);
					push_user($request['title'], $res['user_id'],$res['id'], 4, $newsInfo->id);
				}
			    $this->success('更新成功', 'console/Nav/index');

			}

			else{

				$this->error($this_model->getError());

			}

		}

	    else{
	    	$this->error('请求方式出错!');
	    }

	}
	public  function hotspot() {
		$param = Request::instance()->param();
		if($param['hotspot'] == 1) {
			$a = Db::name('industry_information')->where('id',$param['id'])->update(['hot_state'=>1]);
			$this->success('更新成功', 'console/nav/index');
		} else {
			$a = Db::name('industry_information')->where('id',$param['id'])->update(['hot_state'=>0]);
			$this->success('更新成功', 'console/nav/index');
		}
	}

    public function updateSort()
    {
        $param = request()->param();
        $res = Db::name('industry_information')
            ->where('id',$param['id'])
            ->setField($param['field'],$param['value']);
        if($res)
            return 1;
        return 2;
	}

}

