<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/4
 * Time: 10:28
 */

namespace app\api\controller;
use app\api\model\GoodsType as GoodsTypeModel;
use app\api\model\User;
use app\api\model\Goods as GoodsModel;
use app\api\model\Store as StoreModel;
use mrmiao\encryption\RSACrypt;
use think\Controller;
use think\Db;
use think\Request;

class Store extends Controller
{
    /*
     * 商家店铺
     * */
  public function index(RSACrypt $crypt,GoodsTypeModel $goodsType,StoreModel $store,GoodsModel $goods){
      $request = $crypt->request();
      $where['status']=1;
      $where['id'] = $request['store_id'];
      $where1['status']=1;
      $where1['store_id']=$request['store_id'];
      if(!empty($request['keyword'])){
          $where1['title']=['like','%'.$request['keyword'].'%'];
      }
      if(!empty($request['goodstype_id'])){
          $where1['typeid'] = $request['goodstype_id'];
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
          }}
      if(!empty($request['saleprice'])){
          switch ($request['saleprice']) {
              case 1:
                  $map['saleprice'] = "asc";
                  break;   //销量正序
              case 2:
                  $map['saleprice'] = "desc";
                  break;
          }}
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
          }}
    
     if(!empty($request['screen'])){
         if($request['screen']==1){  //推荐
           $where1['store_hot']=1;
         }
         if($request['screen']==2){//新品
             $map='create_time desc';
         }
     }
      $data = $store->where($where)->field('id,title,picurl,qq,manned,pic,mobile')->find();
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


      $data['goods'] = $goods->where($where1)->order($map)->field('id,picurl,sell,saleprice,title')->paginate(10,false,['page'=>$request['page']]);
      $data['collect_num'] = Db::name("collection")
          ->where([
              'type' => 2,
              "collection_id" => $request['store_id'],
          ])->count();
      return $crypt->response(['code' => 200, 'message' => '成功','data'=>$data]);
  }

    /*
     * 商家下的商品分类
     * */
    public function storeType(RSACrypt $crypt,StoreModel $store,GoodsTypeModel $goodsType){
        $request = $crypt->request();

        $store_type = $store->where('id',$request['store_id'])->value('type_id');
//        dump($store_type);die;
        if(!empty($store_type)){
            $data =$goodsType->where(['parentid'=>0,'id'=>['in',$store_type]])->field('id,title')->paginate(10,false,['page'=>$request['page']]);
            foreach($data as &$v){
                $v['son'] = $goodsType->where('parentid',$v['id'])->field('id,title,picurl')->select();
                $v['store_id'] = $request['store_id'];
            }

        }
        return $crypt->response(['code' => 200, 'message' => '成功','data'=>$data]);
    }

    // 地址筛选
    public function pro(RSACrypt $crypt,StoreModel $store){
              $data = $store->where(['status'=>1,'pro'=>['neq','']])->field('pro')->group('pro')->select();
              return $crypt->response(['code' => 200, 'message' => '成功','data'=>$data]);

    }
    /*
     * 商家简介
     * */
    public function introduce(RSACrypt $crypt,StoreModel $store)
    {
        $request = $crypt->request();
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
        return $crypt->response(['code' => 200, 'message' => '成功','data'=>$data]);
    }

    public function storeAddress(RSACrypt $crypt,StoreModel $store){
        $request = $crypt->request();
        $data = $store->where('id',$request['store_id'])->field('consignee,consignee_mobile,address')->find();
        return $crypt->response(['code' => 200, 'message' => '成功','data'=>$data]);
    }

    public function storeProv(RSACrypt $crypt){
        $data  = Db::name('cn_prov')->field('name')->select();
        return $crypt->response(['code' => 200, 'message' => '成功','data'=>$data]);
    }
}