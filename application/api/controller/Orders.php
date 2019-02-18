<?php

/**

 * Created by PhpStorm.

 * User: Administrator

 * Date: 2018/1/4

 * Time: 10:28

 */
namespace app\api\controller;
use app\api\model\Evaluate;

use app\api\model\Orders as OrderModel;

use mrmiao\encryption\RSACrypt;

use think\Controller;

use think\Db;

use think\Request;
class Orders extends Controller
{
    public function index(RSACrypt $crypt, OrderModel $orders)
    {
        $request = $crypt->request();
        $where = ['state' => 1];
        Db::name('news_info')->where(['user_id'=>$request['user_id'],'read_state'=>0,'type'=>7])->update(['read_state'=>1]);
        //订单状态
      if($request['status']!=''){
          switch ($request['status']) {
              case 0:
                  $where['status'] = '0';

                  break;  //待付款
              case 1:
                  $where['status'] = ['in', '1'];
                  break;  //待发货
              case 2:
                  $where['status'] = ['in', '2'];
                  break;   //待收货
              case 3:
                  $where['status'] = ['in', '3'];
                  break;   // 待评价
              case 5:
                  $where['status'] = ['in', '5,7,8,9'];
                  break;   //售后
              default:
//                $where['status'] = [];
          }
      }


        if(!empty($request['keyword'])){
            $where['orders.order_sn|store.title']=['like','%'.$request['keyword'].'%'];
        }


        $where['user_id'] = $request['user_id'];

         $data =Db::view("orders", "id,order_sn,total,courier_number,store_id,status,user_id")
             ->view('store','title','store.id=orders.store_id')
             ->where($where)
             ->order('id DESC')
             ->paginate(10, false, ['page' => $request['page']])
             ->toArray();
        foreach($data['data'] as &$v){
            if($request['status']==""){
                $v['consolidated'] = 0;
            }else{
                $v['consolidated'] = 1;
            }

            $v['orderlist'] = Db::view('orders_list',['num', 'goods_attr','goods_picurl', 'goods_price','goods_title'])
                ->view('goods','store_id','orders_list.goods_id=goods.id')
                ->where(['order_sn'=>$v['order_sn'],'goods.store_id'=>$v['store_id']])
                ->select();
            foreach ($v['orderlist']  as &$vv) {
                $vv['goods_attr'] = str_replace("_", ',', $vv['goods_attr']);
            }
        }
        return $crypt->response(['code' => 200, 'message' => '成功', 'data' => $data]);

    }

    public function info(RSACrypt $crypt, OrderModel $orders){
        $request = $crypt->request();
        $data = $orders->where('id',$request['id'])->find();
        if(empty($data)) {
            return $crypt->response(['code' => 200, 'message' => '订单id不存在']);
        }
        $data['store_title'] = Db::name('store')->where('id',$data['store_id'])->value('title');
        $data['goods'] = Db::name('orders_list')->where(['order_sn'=>$data['order_sn']])->select();
        return $crypt->response(['code' => 200, 'message' => '成功', 'data' => $data]);
    }

    /*
     * 线下支付
     * */
     public function online(RSACrypt $crypt, OrderModel $orders){
         $data  = $crypt->request();
         Db::startTrans();
         try {
             if ($data['type'] == '1') {  //  合并支付
                 $order_merga = Db::name('orders_merge')->where('merge_sn', $data['order_sn'])->find();//合并的订单表
                 if ($order_merga['status'] == '0') {//未支付
                     $order_sn = explode(',', $order_merga['order_sn']);
                     foreach ($order_sn as $v) {
                         $orders = Db::name('orders')->where('order_sn', $v)->value('status');
                         if ($orders == '0') {
                             Db::name('orders')->where('order_sn', $v)->update(['status' => 0, 'pay_type' => 3]);
                         }
                     }
                     $order_merga = Db::name('orders_merge')->where('merge_sn', $data['order_sn'])->update(['status' => 1]);
                 }
             } else {
                 // 修改订单支付状态
                 $data = Db::name('orders')->where('order_sn', $data['order_sn'])->select();
                 foreach ($data as $v) {
                     if ($v['status'] == '0') {
                         Db::name('orders')->where('id', $v['id'])->update(['status' => 0, 'pay_type' => 3]);
                     }
                 }
             }
             Db::commit();
             return $crypt->response(['code' => 200, 'message' => '支付成功']);
         } catch (\Exception $e) {
             Db::rollback();
             return $crypt->response(['code' => 400, 'message' => '数据错误等待处理！']);
         }
     }



    /*申请退款(退货)*/

    public function refund(RSACrypt $crypt, OrderModel $orders)
    {
        $request = $crypt->request();
        if($request['type']==1){
            $request['status']=5;
            $money = Db::name('orders')->where('order_sn',$request['order_sn'])->value('total');
            if($request['back_money']>$money){
                return $crypt->response(['code' => 400, 'message' => '请输入正确的退款金额']);
            }
        }else{
            $request['status']=8;
        }
        Db::startTrans();
        try {
            $orders->where(['order_sn' => $request['order_sn']])->setField("status", $request['status']);

            $result = Db::name("cause")->insert(['ordersn'=>$request['order_sn'],'type'=>$request['type'],
               'cause'=>$request['cause'],'back_money'=>$request['back_money'],'name'=>$request['name'],
                'mobile'=>$request['mobile'],'address'=>$request['address'] ]);
            if ($result) {
                Db::commit();
                return $crypt->response(['code' => 200, 'message' => '成功']);
            } else {
                return $crypt->response(['code' => 400, 'message' => '失败']);
            }
       } catch (\Exception $e) {
            Db::rollback();
          return $crypt->response(['code' => 400, 'message' => '数据错误等待处理！']);
       }
    }


    /*删除订单(逻辑删除)*/
    public function order_del(RSACrypt $crypt, OrderModel $orders)
    {
        $request = $crypt->request();
        $result = $orders->where(['order_sn' => $request['order_sn']])->setField("state", 0);
        $id = $orders->where(['order_sn' => $request['order_sn']])->value('id');
        if ($result) {
            Db::name('news_info')->where(['other_id'=>$id,'user_id'=>$request['user_id'],'type'=>7])->delete();

            return $crypt->response(['code' => 200, 'message' => '成功']);
        } else {
            return $crypt->response(['code' => 400, 'message' => '失败']);
        }
    }





    /*去评价*/

    public function go_appraise(RSACrypt $crypt, OrderModel $orders)
    {
        $request = $crypt->request();

        $ordersn = $request['order_sn'];



        $data = Db::name("orders_list")->where("order_sn", $ordersn)->field("id,goods_picurl,goods_id,order_sn")->select();
         foreach($data as &$v){
             $v['store_id'] = Db::name('goods')->where('id',$v['goods_id'])->value('store_id');
         }
        return $crypt->response(['code' => 200, 'message' => '成功', 'data' => $data]);

    }



    /*

     * data = {

    "anonymous":1

    "user_id":1

    "comment":[

    {

    "goods_id":3,

    "store_id":3,

    "ordersn":"2016120805470879579",

    "content":"\/u8bc4\/u4ef73\/u8bc4\/u4ef73",

    "picarr":"",

    "type":2

    "number":图片数量

    },

    {

    "goods_id":3,

    "store_id":3,

    "ordersn":"2016120805470879579",

    "content":"\/u8bc4\/u4ef73\/u8bc4\/u4ef73",

    "picarr":"",

    "type":2

    "number":图片数量

    },

    ],

    }*/


public function appraise(RSACrypt $crypt,Request $request){
        $data = $crypt->request();

        $data = json_decode($data['data'], true);

        $user_id = $data['user_id'];
        $anonymous = $data['anonymous'];
        $gappraise_data = [];
        foreach ($data['comment'] as $k => $v) {
            $arr = array();
            $arr['user_id'] = $user_id;
            $arr['create_time'] = time();
            $arr['goods_id'] = $v['goods_id'];
            $arr['type'] = $v['type'];  //好评1中评2差评3
            $arr['content'] = $v['content'];
            $arr['ordersn'] = $v['order_sn'];
            $arr['anonymous'] = $anonymous;
            if($v['number']>0){
                $pic=[];

                foreach($v['picarr'] as $vv){
                    $pic[]['img']  = $vv;
                }
                $arr['picarr'] = serialize($pic);
            }else{
                $arr['picarr'] = '';
            }

            $gappraise_data[] = $arr;

            Db::name('orders')->where('order_sn',$v['order_sn'])->update(['status'=>4]);//更改订单状态
        }

        if (Db::name('evaluate')->insertAll($gappraise_data)) {
            return ['code' => 200, "message" => "评论成功"];
        } else {
            return ['code' => 400, "message" => "评论失败"];
        }
}
    /*评价*/

    public function appraise111(RSACrypt $crypt)
    {
        $request = $crypt->request();
        $data = json_decode($request['data'], true);
        Db::startTrans();
        try {
            foreach ($data['comment'] as $k => $v) {
                $v['picarr'] = '';
                if (!empty($v['number'])) {
                    //图片个数不为零   做文件上传
                    $v['picarr'] = $this->picurl($v['number'], $k);
                }
                    //添加评价
                    Db::name('evaluate')->insert([
                        'store_id' => $v['store_id'],
                        'goods_id' => $v['goods_id'],
                        'user_id' => $data['user_id'],
                        'ordersn' => $v['order_sn'],
                        'type' => $v['type'],
                        'content' => $v['content'],
                        'picarr' => $v['picarr'],
                        'anonymous' => $data['anonymous'],//2是  1不匿名
                        'create_time' => time(),
                    ]);
                    //设置订单状态   已评价
                    Db::name("orders")
                        ->where("order_sn", $v['order_sn'])
                        ->setField("status", 4);
            }
            // 提交事务
                Db::commit();
                return $crypt->response(['code' => 200, 'message' => '评价成功']);
        } catch (\Exception $e) {
            //回滚事务
            Db::rollback();
            return $crypt->response(['code' => 400, 'message' => '评价失败']);
        }
    }
    //文件流上传

    public function picurl($number, $key)
    {
        if ($number > 0) {
            $arr=[];
            for ($i = 1; $i <= $number; $i++) {
                $files = Request::instance()->file('picarr' . $key . $i);
                $name = date("ymd", time()) . time() . rand(1000, 9999) . $i;
                $info = $files->rule('uniqid')->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . 'test', $name);
                $picarrTmp = 'uploads/test/' . $info->getFilename();
                $arr[]['img']=$picarrTmp;
            }
            $data = serialize($arr);
            // 循环数组保存文件图片
            // 拼装图片数据
        } else {
            $data = '';
        }
        return $data;
    }



    /*修改评价*/

    public function save_appraise(RSACrypt $crypt ,Evaluate $evaluate)

    {

        $request = $crypt->request();

        $result = $evaluate->where(['ordersn'=>$request['order_sn'],'user_id'=>$request['user_id']])->field("type,user_id,goods_id,picarr")->find();

        $result['picarr'] = unserialize($result['picarr']);

        return $crypt->response(['code' => 200, 'message' => '评价成功','data'=>$result]);



    }



    /*查看物流*/

    public function logistic(RSACrypt $crypt,OrderModel $orders)
    {
        $request = $crypt->request();
        $arr = $orders->where("id",$request['id'])->field("courier_name,courier_number,order_sn,courier,address")->find();
        $data = logistics($arr['courier_name'],$arr['courier_number']);

        $data  = json_decode($data,true);
        if($data['status'] !='0'){
            $datas = $data['data'];
        }

        $res1 = Db::name('orders_list')->where('order_sn',$arr['order_sn'])->field('goods_title,goods_picurl')->find();

        $data_arr['goods_picurl'] = $res1['goods_picurl'];
        $data_arr['goods_title'] = $res1['goods_title'];
        $data_arr['courier'] = $arr['courier'];
        $data_arr['order_sn'] = $arr['order_sn'];
        $data_arr['goods_total']  = Db::name('orders_list')->where('order_sn',$arr['order_sn'])->count();
        $data_arr['address'] = $arr['address'];
        $data_arr['logistics'] = !empty($datas)?$datas:[];

        if($data['status']==0){
            return $crypt->response(['code' => 400, 'message' => $data['message']]);
        }else{
            return $crypt->response(['code' => 200, 'message' => '成功','data'=>$data_arr]);
        }

    }



    public function receipt(RSACrypt $crypt ,OrderModel $orders)
    {
        $request = $crypt->request();
        $data['status'] = 3;
        $result  = $orders->where(['order_sn'=>$request['order_sn']])->update($data);
        if ($result) {
            return $crypt->response(['code' => 200, 'message' => '成功']);
        } else {
            return $crypt->response(['code' => 400, 'message' => '失败']);
        }
    }
}