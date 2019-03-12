<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/4
 * Time: 10:28
 */

namespace app\home\controller;
use app\home\model\GoodsType as GoodsTypeModel;
use app\home\model\User;
use think\Controller;
use think\Db;
use think\Request;

class Goodstype extends Controller
{
    /*
     * 分类列表
     * */
  public function index(Request $requests,GoodsTypeModel $goodsType){
      $request = $requests->param();
      $where='';
      if(!empty($request['id'])){
          $where['id'] = $request['id'];
      }
      $data = $goodsType->where('parentid',0)->where($where)->order('orderid desc')->field('id,title')->select();
      foreach($data as &$v){
          $v['son'] =$goodsType->where('parentid',$v['id'])->field('id,title,picurl')->select();
      }
      return $this->fetch(
          'index',[
              'title'=>"商品列表",
              'data'=>$data
          ]
      );
  }
}