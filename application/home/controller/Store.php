<?php
namespace app\home\controller;
use app\home\model\Banner;
use app\home\model\Goods;
use app\home\model\GoodsType as GoodsTypeModel;
use app\home\model\User;
use app\home\model\Goods as GoodsModel;
use app\home\model\Store as StoreModel;
use think\Controller;
use think\Db;
use think\Request;
use think\Session;
use think\Url;
class Store extends Controller
{
    /*
     * 我要开店
     * */
                    public function openStore(Request $requests ,Goods $goods , StoreModel $store , Banner $banner){
                        $request = $requests->param();
                        if($requests->isPost()){
                        
                            $result = $this->validate($request, 'Store.add');  //验证参数
                            if ($result !== true) {
                                return ['code' => 400, 'message' => $result];
                            }
                            $data = $store->where('mobile',$request['mobile'])->find();
                            if(!empty($data)){
                                return ['code' => 400, 'message' => '手机号码请勿重复使用！'];
                            }
                            $result = $store->allowField(true)->isUpdate(false)->save($request);
                            if ($result) {
                                return['code' => 200, 'message' => "成功"];
                            }else{
                                return['code' => 400, 'message' => "失败"];
                            }
                        }else{
                            return $this->fetch(
                                'open',[
                                    'title'=>"我要开店",
                                ]
                            );
                        }
                    }
	/*
     * 商家店铺
     * */
                public function index(Request $requests,GoodsTypeModel $goodsType,StoreModel $store,GoodsModel $goods){
                          $request = $requests->param();
//                          halt($request);
                          $request['user_id'] = Session::get('user_id');
                          $where['status']=1;
                          $where['id'] = $request['store_id'];
                          $where1['status']=1;
                          $where1['store_id']=$request['store_id'];
                          if(!empty($request['keyword'])){
                                    $where1['title']=['like','%'.$request['keyword'].'%'];
                          }else{
                                    $request['keyword']='';
                          }
                          $map = '';
                          if(!empty($request['sell'])){
                                    switch ($request['sell']) {
                                              case 1:
                                                        $map['sell'] = "asc";
                                                        break;   //销量正序
                                              case 2:
                                                        $map['sell'] = "desc";
                                                        break;
                                    }}else{
                                    $request['sell']='';
                          }
                          if(!empty($request['saleprice'])){
                                    switch ($request['saleprice']) {
                                              case 1:
                                                        $map['saleprice'] = "asc";
                                                        break;   //销量正序
                                              case 2:
                                                        $map['saleprice'] = "desc";
                                                        break;
                                    }}else{
                                    $request['saleprice']='';
                          }
                          if(!empty($request['comprehensive'])){
                                    switch ($request['comprehensive']) {
                                              case 1:
                                                        $map['saleprice'] = "asc";
                                                        $map['sell'] = "asc";
                                                        break;   //销量正序
                                              case 2:
                                                        $map['saleprice'] = "desc";
                                                        $map['sell'] = "desc";
                                                        break;
                                    }}else{
                                    $request['comprehensive']='';
                          }
                          if(!empty($request['screen'])){
                                    if($request['screen']==1){  //推荐
                                              $where1['store_hot']=1;
                                    }
                                    if($request['screen']==2){//新品
                                              $map='create_time desc';
                                    }
                                    if($request['screen']==3){//新品
                                    }
                          }else{
                                    $request['screen']='';
                          }
                          $data = $store->where($where)->field('id,title,picurl,manned,pic,mobile')->find();
                          if($request['user_id']!=''){
                                    $collect = Db::name("collection")
                                              ->where([
                                                        'type' => 2,
                                                        "collection_id" => $request['store_id'],
                                                        'user_id'=>$request['user_id']
                                              ])
                                              ->find();
                                    $data['collection_id'] = empty($collect['id']) ? "" :$collect['id'] ;
                                    $data['collection'] = empty($collect) ? 0 : 1;
                          }else{
                                    $data['collection_id'] = "";
                                    $data['collection'] =0;
                          }
//                              halt($where1);
                          $data['goods'] = $goods->where($where1)->order($map)->field('id,picurl,sell,saleprice,title')->select();
                          $data['collect_num'] = Db::name("collection")
                                    ->where([
                                              'type' => 2,
                                              "collection_id" => $request['store_id'],
                                    ])->count();

                          $phone_on = Db::name('webconfig')->where('id',8)->field('varvalue')->value('varvalue');
                          if($phone_on == 1){
                              $phone = Db::name('webconfig')->where('id',5)->field('varvalue')->value('varvalue');
                              $qq = Db::name('webconfig')->where('id',36)->field('varvalue')->value('varvalue');
                          }else{
                              $phone = Db::name('webconfig')->where('id',7)->field('varvalue')->value('varvalue');
                              $qq = Db::name('webconfig')->where('id',32)->field('varvalue')->value('varvalue');
                          }
                          return $this->fetch(
                                    'index',[
                                              'title'=>"商家店铺",
                                              'data'=>$data,
                                              'sell'=>$request['sell'],
                                              'saleprice'=>$request['saleprice'],
                                              'store_id'=>$request['store_id'],
                                              'comprehensive'=>$request['comprehensive'],
                                              'keyword'=>$request['keyword'],
                                                'phone'=>$phone,
                                                'qq'=>$qq,
                                    ]
                          );
                }
	/*
    * 商家下的商品分类
* */
          public function storeType(Request $requests,StoreModel $store,GoodsTypeModel $goodsType){
                    $request = $requests->param();
                    $store_type = $store->where('id',$request['store_id'])->value('type_id');
                    $data=[];
                    if(!empty($store_type)){
                              $data =$goodsType->where(['parentid'=>0,'id'=>['in',$store_type]])->field('id,title')
                                        ->select();
                              foreach($data as &$v){
                                        $v['store_id'] = $request['store_id'];
                                        $v['son'] = $goodsType->where('parentid',$v['id'])->field('id,title,picurl')->select();
                              }
                    }
                    return $this->fetch(
                              'type',[
                                        'title'=>"商品分类",
                                        'data'=>$data,
                                        'store_id'=>$request['store_id']
                              ]
                    );
          }
	/*
     * 商家简介
     * */
          public function introduce(Request $requests,StoreModel $store)
          {
                    $request = $requests->param();
                    $request['user_id']=Session::get('user_id');
                    $data = $store->where('id',$request['store_id'])->field('id,title,pic,picurl,create_time,manned,mobile,pro')->find();
                    if($request['user_id']!=''){
                              $collect = Db::name("collection")
                                        ->where([
                                                  'type' => 2,
                                                  "collection_id" => $request['store_id'],
                                                  'user_id'=>$request['user_id']
                                        ])
                                        ->find();
                              $data['collection_id'] = empty($collect['id']) ? "" :$collect['id'] ;
                              $data['collection'] = empty($collect) ? 0 : 1;
                    }else{
                              $data['collection_id'] = "";
                              $data['collection'] =0;
                    }
                    $data['collect_num'] =  Db::name("collection")
                              ->where([
                                        'type' => 2,
                                        "collection_id" => $request['store_id'],
                              ])->count();
                    return $this->fetch(
                              'introduce',[
                                        'title'=>"店铺简介",
                                        'data'=>$data
                              ]
                    );
          }
          public function search(Request $requests){
                    $request =$requests->param();
                    return $this->fetch(
                              'search',[
                                        'title'=>"搜索",
                                        'store_id'=>$request['store_id']
                              ]
                    );
          }

          public function jj(){
              return $this->fetch('jj');
          }
}