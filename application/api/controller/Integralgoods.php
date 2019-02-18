<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/4
 * Time: 10:28
 */

namespace app\api\controller;
use app\api\model\IntegralGoods as IntegralGoodsModel;
use app\api\model\Integral as Integral;
use app\api\model\IntegralExchange as IntegralExchange;
use app\api\model\IntegralInfo as IntegralInfo;
use app\api\model\Address as Address;
use app\api\model\User;
use mrmiao\encryption\RSACrypt;
use think\Controller;
use think\Db;
use think\Request;

class Integralgoods extends Controller
{
    /*
     * 积分商城---1、列表
     */
    public function index(RSACrypt $crypt,IntegralGoodsModel $integralGoods,User $user,Integral $integral){
        $request = $crypt->request();
        //列表
        $list = $integralGoods->field('id,title,picurl,create_time,price')->order('create_time desc')->paginate(10,false,['page'=>$request['page']]);
        $jifen =$user->where('id',$request['user_id'])->value('integral');
        foreach($list as &$v){
            $v['jifen'] = !empty($jifen)?$jifen:0;
        }
        $data = [
            'list'=>$list,
            'jifen'=>$jifen,
        ];
        return $crypt->response([
            'code' =>200,
            'message' => "成功",
            'data' =>  $data,
        ]);
    }

    /*
    * 积分商城---2、商品详情
     * 参数：供求的id
    */
    public function info(RSACrypt $crypt,User $user,IntegralGoodsModel $integralGoods){
        $request = $crypt->request();
        $result = $this->validate($request, 'Integralgoods.info');
        if ($result !== true) {
            return $crypt->response([
                'code' => 400,
                'message' => $result
            ]);
        }
        $data = $integralGoods->where('id',$request['id'])->field('title,price,picarr')->find();
        $data['content'] = "http://www.wantaozb.com/index.php/api/Integralgoods/infoweb?id=".$request['id'];
        $data['my_integral'] = $user->where('id',$request['user_id'])->value('integral');
        
        return $crypt->response([
            'code' =>200,
            'message' => "成功",
            'data' =>  $data,
        ]);
    }
    /*
       * 积分商城---商品详情web
        * 参数：供求的id
       */
     public function infoWeb(Request $request,IntegralGoodsModel $integralGoods){
         $post_data = $request->param();
         $content = $integralGoods->where("id", $post_data['id'])->value("content");
         return make_show($content);
     }

    /*
      * 积分商城---3、兑换
       * 参数：商品的id 用户id
      */
   public function exchange(RSACrypt $crypt,IntegralGoodsModel $integralGoods,Address $address){
       $request = $crypt->request();
       $result = $this->validate($request, 'Integralgoods.info');
       if ($result !== true) {
           return $crypt->response([
               'code' => 400,
               'message' => $result
           ]);
       }
       $data =$integralGoods->where('id',$request['id'])->field('picurl,title,price')->find();
       $address_res = $address->where(['user_id'=>$request['user_id'],'status'=>1])->find();
             $address_data= empty($address_res)?[]:$address_res;
       $data['address'] = empty($address_data) ? ['id'=>'','username'=>'','mobile'=>'','prov'=>'','address'=>'','status'=>'',] : $address_data;

             return $crypt->response([
           'code' =>200,
           'message' => "成功",
           'data' =>  $data,
       ]);
   }
    /*
     * 积分商城---4、确认兑换
      * 参数：商品的id 用户id
     */
    public function exchangeAdd(RSACrypt $crypt,IntegralGoodsModel $integralGoods,IntegralExchange $exchange,User $user){
        $request = $crypt->request();
        $result = $this->validate($request, 'Integralgoods.exchange');
        if ($result !== true) {
            return $crypt->response([
                'code' => 400,
                'message' => $result
            ]);
        }
        $goods_info =$integralGoods->where('id',$request['goods_id'])->find();
        $request['goods_title']  = $goods_info['title'];
        $request['address'] = $request['prov'].$request['address'];
        $request['total'] = $goods_info['price'];
        $data = $exchange->allowField(true)->isUpdate(false)->save($request);
        if($data){
            $user->where('id',$request['user_id'])->setDec('integral',$goods_info['price']);
            Db::name('integral_info')->insert(['user_id'=>$request['user_id'],'num'=>$goods_info['price'],'type'=>3,'create_time'=>time()]);
            return $crypt->response([
                'code' =>200,
                'message' => "成功",
            ]);
        }else{
            return $crypt->response([
                'code' =>400,
                'message' => "失败",
            ]);
        }
    }
    /*
     * 兑换记录
     * 参数type 1进行中 2已完成
     * */
    public function exchangeInfo(RSACrypt $crypt,IntegralExchange $exchange){
        $request = $crypt->request();
        $where['user_id'] =$request['user_id'];
        if($request['type']==1){
            $where['status']=['in','0,1'];
        }else{
             $where['status']='2';
        }
        $data = $exchange->where($where)->field('id,create_time,num,goods_title,status')->order('create_time desc')->paginate(10,false,['page'=>$request['page']]);
            return $crypt->response([
                'code' =>200,
                'message' => "成功",
                'data'=>$data
            ]);

    }


    public function takeGoods(RSACrypt $crypt,IntegralExchange $exchange){
        $request = $crypt->request();
        $data = $exchange->where('id',$request['id'])->update(['status'=>2]);
        return $crypt->response([
            'code' =>200,
            'message' => "成功",
        ]);
    }
}