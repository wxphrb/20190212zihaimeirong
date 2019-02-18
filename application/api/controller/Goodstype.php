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
use mrmiao\encryption\RSACrypt;
use think\Controller;
use think\Db;
use think\Request;

class Goodstype extends Controller
{
    /*
     * 分类列表
     * */
  public function index(RSACrypt $crypt,GoodsTypeModel $goodsType){
      $request = $crypt->request();
      $where='';
      if(!empty($request['id'])){
          $where['id'] = $request['id'];
      }
      $data = $goodsType->where('parentid',0)->where($where)->order('orderid desc')->field('id,title')->paginate(10,false,['page'=>$request['page']]);
      foreach($data as &$v){
          $v['store_id']='';
          $v['son'] =$goodsType->where('parentid',$v['id'])->field('id,title,picurl')->select();

      }
      return $crypt->response(['code' => 200, 'message' => '成功','data'=>$data]);
  }
}