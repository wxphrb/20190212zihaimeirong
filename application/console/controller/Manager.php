<?php
namespace app\console\controller;
use think\Db;
use think\Request;
use think\Url;
use app\console\model\Manager as ThisModel;

class Manager extends Base
{
	/**
	 * [index description]列表
	 * @return [type] [description]
	 */
	public function index()
	{
		$data = ThisModel::order('id', 'desc')->select();

        return $this->fetch('index', [
            'data'       => $data
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
			$post_data = Request::instance()->post();
			$result = ThisModel::saveVerify($post_data);
			if (true === $result) {
                $this->success('新建成功', 'console/manager/index');
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
			$post_data = Request::instance()->post();
			$result = ThisModel::saveVerify($post_data,$id);
			if (true === $result) {
                $this->success('更新成功', 'console/manager/index');
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
		$authgroup = db::name('authgroup')->field('id,title')->select();
		return [
		   'authgroup' =>$authgroup,
		];
	}
    /**
     * [delete description]删除方法 多选和单选删除
     * @return [type] [description]
     */
	public function delete(){
		if (Request::instance()->isPOST())
		{
			//$id = Request::instance()->post('id/a');  /a 将收到的id转为数组
			$delmodel = ThisModel::destroy(Request::instance()->post('id/a'));
			if($delmodel){
			    $this->success('删除成功', 'console/manager/index');
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
			$validate = validate('Manager');

			$post = Request::instance()->except(['id'],'post');
			$post = array_keys($post);

            $validate->scene('edit', $post);
			if(!$validate->scene('edit')->check($data)){
			    $this->error($validate->getError());
			}else{
		        $myown_model = new ThisModel();
		        if($myown_model->update($data))
	            {
				    $this->success('更新成功', 'console/manager/index');
				}
				else{
					$this->error($myown_model->getError());
				}
			}
		}
	    else{
	    	$this->error('请求方式出错!');
	    }
	}

}