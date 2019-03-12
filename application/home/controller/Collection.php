<?php
namespace app\home\controller;
use app\home\model\Collection as CollectionModel;
use app\home\model\BbcAnswer;
use app\home\model\Goods;
use app\home\model\Store;
use think\Controller;
use think\Db;
use think\Request;
use think\Session;
use think\Url;
class Collection extends Base
{
	public function all(Request $requests,CollectionModel $collection,BbcAnswer $answer)
	{
		$request = $requests->param();
		$request['user_id'] = Session::get('user_id');
		$data = Db::name('collection')->where(['user_id' => $request['user_id']])->select();
		$res = [];
		foreach ($data as &$v) {
			if ($v['type'] == 1) {
				$res[] = Db::view('Collection', 'id,type,collection_id,user_id')
					->view("Goods", 'title,saleprice,sell,picurl', 'Goods.id = Collection.collection_id')
					->where(['Goods.id' => $v['collection_id'],'Collection.type'=>1])
					->find();
			}
			if ($v['type'] == 2) {
				$res[] = Db::view('Collection', 'id,type,collection_id,user_id')
					->view("Store", 'picurl,title', 'Store.id = Collection.collection_id')
					->where(['Store.id' => $v['collection_id'],'Collection.type'=>2])
					->find();
			}
			if ($v['type'] == 3) {
				$res[] = Db::view('Collection', 'id,type,collection_id,user_id')
					->view("bbc_question", 'title,create_time,answer_num,integral_num', 'bbc_question.id = Collection.collection_id')
					->where(['bbc_question.id' => $v['collection_id'],'Collection.type'=>3])
					->find();
			}
			if ($v['type'] == 4) {
				$res[] = Db::view('Collection', 'id,type,collection_id,user_id')
					->view("Supply", 'title,user_id,address,create_time', 'Supply.id = Collection.collection_id')
					->view("User", 'username,picurl', 'User.id = Supply.user_id')
					->where(['Supply.id' => $v['collection_id'],'Collection.type'=>4])
					->find();
			}
			if ($v['type'] == 5) {
				$res[] = Db::view('Collection', ['id', 'type' => 'type', 'user_id' => 'userid', 'collection_id' => 'collection_id'])
					->view("industry_information", 'title,picarr,user_id,picarr_type,create_time,content', 'industry_information.id = Collection.collection_id')
                    ->view("User", 'id,username,picurl', 'User.id = industry_information.user_id')
                    ->where(['industry_information.id' => $v['collection_id'],'Collection.type'=>5])
					->find();
			}
			if ($v['type'] == 6) {
				$res[] = Db::view('Collection', 'id,type,collection_id,user_id')
					->view("Posts", 'title,picarr,praise,describe,picarr_type,comments_num,share_num,hot_state', 'Posts.id = Collection.collection_id')
					->where(['Posts.id' => $v['collection_id'],'Collection.type'=>6])
					->find();
			}
		}
		foreach($res as &$vv){
            if(is_array($vv)){
                if(array_key_exists("picarr",$vv)){
                    if($vv['picarr']!=''){
                        $vv['picarr'] =unserialize($vv['picarr']);
                    }else{
                        $vv['picarr']=[];
                    }
                }else{
                    $vv['picarr']='';
                }
                if(!array_key_exists('username',$vv)){
                    $vv['username'] = '';
                }
                if($vv['type']==2){
                    $vv['peo_num'] = $collection->where(["collection_id"=> $vv['collection_id'],'type'=>2])->count();
                }
                if($vv['type']==3){
                    $best_answer = $answer->where(['question_id'=>$vv['collection_id'],'status'=>1])->value('content');//问题最佳答案
                    if(empty($best_answer)){//如果没有，就取最新的答案
                        $mew_answer = $answer->where(['question_id'=>$v['id']])->order('id desc')->value('content');
                        $vv['answer'] = empty($mew_answer)?'':$mew_answer;
                    }else{
                        $vv['answer']=$best_answer;
                    }
                }
            }
		}
//		dump($res);
		return $this->fetch(
			'all',[
				'title'=>"我的收藏（全部）",
				'list'=>$res,
			]
		);
	}
    /*
     * 收藏（商品）
     * */
	public function index(){
			$where = ['type' => 1, 'user_id' => Session::get('user_id')];
			$data = Db::view('Collection', 'id,type,collection_id,user_id')
				->view("Goods", 'title,saleprice,sell,picurl', 'Goods.id = Collection.collection_id')
				->where($where)
				->order('id desc')
				->select();
//		dump($data);die;
		return $this->fetch(
			'index',[
				'title'=>"我的收藏（商品）",
				'list'=>$data,
			]
		);
	}
	/*
     * 收藏（店铺）
     * */
	public function index1(){
		$where = ['type' => 2, 'user_id' => Session::get('user_id')];
		$data = Db::view('Collection', 'id,type,collection_id,user_id')
			->view("Store", 'picurl,title', 'Store.id = Collection.collection_id')
			->where($where)
			->order('id desc')
			->select();
			foreach ($data as &$v) {
				$v['peo_num'] = Db::name('collection')->where("collection_id", $v['collection_id'])->count();
			}
		return $this->fetch(
			'index1',[
				'title'=>"我的收藏（店铺）",
				'list'=>$data,
			]
		);
	}
	/*
     * 收藏（论坛）
     * */
	public function index2(){
		$where = ['type' => 3, 'user_id' => Session::get('user_id')];
		$data = Db::view('Collection', 'id,type,collection_id,user_id')
			->view("bbc_question", 'title,create_time,answer_num,integral_num', 'bbc_question.id = Collection.collection_id')
			->order('id desc')
			->where($where)
			->select();
		foreach ($data as &$v) {
			$best_answer = Db::name('bbc_answer')->where(['question_id' => $v['id'], 'status' => 1])->value('content');//问题最佳答案
			if (!empty($request['best_answer'])) {//如果没有，就取最新的答案
				$mew_answer = Db::name('bbc_answer')->where(['question_id' => $v['collection_id']])->order('id desc')->value('content');
				$v['answer'] = empty($mew_answer) ? '' : $mew_answer;
			} else {
				$v['answer'] = $best_answer;
			}
		}
//		dump($data);die;
		return $this->fetch(
			'index2',[
				'title'=>"我的收藏（论坛）",
				'list'=>$data,
			]
		);
	}
	/*
     * 收藏（供求）
     * */
	public function index3(){
		$where['Supply.status']=1;
		$where['Supply.state']=2;
		$where['Collection.type'] = 4;
		$where['Collection.user_id'] =Session::get('user_id');
		$data = Db::view('Collection', 'id,type,collection_id,user_id')
			->view("Supply", 'title,user_id,address,create_time', 'Supply.id = Collection.collection_id')
			->view("User", 'id,username,picurl', 'User.id = Supply.user_id')
			->where($where)
			->order('id desc')
			->select();
//dump($data);die();
		return $this->fetch(
			'index3',[
				'title'=>"我的收藏（供求）",
				'list'=>$data,
			]
		);
	}
	/*
     * 收藏（资讯）
     * */
	public function index4(){
		$where = ['type' => 5, 'userid' => Session::get('user_id')];
		$data = Db::view('Collection', ['id','type' => 'type', 'user_id' => 'userid', 'collection_id' => 'collection_id'])
			->view("industry_information", 'title,picarr,user_id,picarr_type,create_time,content', 'industry_information.id = Collection.collection_id')
                ->view("User", 'id,username,picurl', 'User.id = industry_information.user_id')
			->where($where)
			->order('id desc')
			->select();
		foreach ($data as &$v) {
			$v['picarr'] = unserialize($v['picarr']);
		}
//dump($data);
		return $this->fetch(
			'index4',[
				'title'=>"我的收藏（资讯）",
				'data'=>$data,
			]
		);
	}
	/*
     * 收藏（帖子）
     * */
	public function index5(){
		$where = ['type' => 6, 'user_id' =>Session::get('user_id')];
		$data = Db::view('Collection', 'id,type,collection_id,user_id')
			->view("Posts", 'title,picarr,praise,describe,picarr_type,comments_num,share_num,hot_state', 'Posts.id = Collection.collection_id')
			->where($where)
			->order('id desc')
			->select();
		foreach ($data as &$v) {
			$v['picarr'] = unserialize($v['picarr']);
		}
//		dump($data);die;
		return $this->fetch(
			'index5',[
				'title'=>"我的收藏（帖子）",
				'list'=>$data,
			]
		);
	}
		public function add(Request $requests, CollectionModel $collection)
		{
			$request = $requests->param();
			$request['user_id'] = Session::get('user_id');
			$data = $collection->allowField(true)->isUpdate(false)->save($request);
			if ($data) {
				return['code' => 200, 'message' => '收藏成功'];
			} else {
				return ['code' => 400, 'message' => '收藏失败'];
			}
	}
	public function del(Request $requests, CollectionModel $collection){
		$request=$requests->param();
//		halt($request);
		$delmodel = $collection
			->destroy($request['collection_id']);
		if ($delmodel) {
			return ['code' => 200, 'message' => "成功"];
		}else{
			return ['code' => 400, 'message' => '失败'];
		}
	}
}