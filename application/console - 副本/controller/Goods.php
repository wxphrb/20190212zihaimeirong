<?php
namespace app\console\controller;
use think\Db;
use think\Request;
use think\Url;
use app\console\model\Goods as ThisModel;
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
        $where = array();
        if (Request::instance()->isPost()) {
            $name = Request::instance()->param('name');
            if ($name) {
                $where = [
                    'saleprice|title' => ['like', '%' . $name . '%'],
                ];
                $this->assign("name", $name);
            }
        }
		$data = ThisModel::order('id', 'desc')->where($where)->paginate(10, false, ['query' => array('name' => $name)]);
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
		
			$result = ThisModel::saveVerify($data);
			if (true === $result) {
                $this->success('新建成功', 'console/Goods/index');
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
                $this->success('更新成功', 'console/Goods/index');
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
		$this->showtype(0);
		return [
		   //添加参数
		   'goodstype'=>$this->arr_type
		];
	}
    /**
     * [showtype description]无限极分类
     * @param  integer $id [description]
     * @param  integer $i  [description]
     * @return [type]      [description]
     */
    public function showtype($id =0, $i=0){
    	$res = db::name('goods_type')->field('id,parentid,title')->where('parentid',$id)->order('orderid','asc')->select();
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
			    $this->success('删除成功', 'console/Goods/index');
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
			    $this->success('更新成功', 'console/Goods/index');
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
