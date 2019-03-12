<?php
namespace app\console\controller;
use think\Db;
use think\Request;
use think\Url;
use app\console\model\Superiority as ThisModel;

class Superiority extends Base
{
    /**
	 * [index description]列表
	 * @return [type] [description]
	 */
	public function index()
	{		
		if(input('?param.tid')){
			$tid = input('param.tid');
			$where['type_id']=$tid;
		}
		else{
			$tid = 0;
			$where=[];
		}

		$data = ThisModel::order('id', 'desc')->where($where)->paginate(8);
		

        return $this->fetch('index', [
            'list'       => $data,

            'tid'       => $tid,
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
                $this->success('新建成功', 'console/Superiority/index');
            } else {
                $this->error($result);
            }
		}
		//$param = $this->appendarg();

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
                $this->success('更新成功', 'console/Superiority/index');
            } else {
                $this->error($result);
            }
		}

		$data = ThisModel::get($id);
		$param = array_merge(['vo'=>$data]);

		return $this->fetch('create', $param);
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
			    $this->success('删除成功', 'console/Superiority/index');
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
			$validate = validate('Superiority');

			$post = Request::instance()->except(['id'],'post');
			$post = array_keys($post);

            $validate->scene('edit', $post);
			if(!$validate->scene('edit')->check($data)){
			    $this->error($validate->getError());
			}
	        $this_model = new ThisModel();
	        if($this_model->update($data))
            {
			    $this->success('更新成功', 'console/Superiority/index');
			}
			else{
				$this->error($this_model->getError());
			}
		}
	    else{
	    	$this->error('请求方式出错!');
	    }
	}
}
