<?php

/**

 * Created by PhpStorm.

 * User: Administrator

 * Date: 2018/5/24

 * Time: 17:01

 */



namespace app\api\controller;



use app\api\model\Address;

use app\api\model\Car;

use app\api\model\Goods;

use app\api\model\OrdersList;

use app\api\model\Orders;

use mrmiao\encryption\RSACrypt;

use think\Controller;

use think\Db;

use think\Cache;



class Buy extends Controller

{



    /*--生成订单*/

    public function create_order(RSACrypt $crypt, Address $address, Goods $goods)
    {
        $request = $crypt->request();

        $data['goods_list'] = Db::view('goods','store_id')
            ->view('store','title','goods.store_id=store.id','left')
            ->where('goods.id',$request['goods_id'])
           ->select();
        foreach( $data['goods_list'] as &$v){
            if($v['store_id']==0){
                $v['title'] = '后台自营';
            }
            $v['goods_freight'] = $request['carriage'];
            $v['goods_num']=1;
            $v['goods_total'] = $request['carriage']+$request['price']*$request['num'];
            $v['goods'] = $goods->where("id", $request['goods_id'])->field("id as goods_id,store_id,title,picurl")->select();
        }
        foreach($data['goods_list'] as $v){

            foreach($v['goods'] as $vv){
                $vv['attr'] = $request['attr'];
                $vv['num'] = $request['num'];
                $vv['price'] = $request['price'];
            }
        }
        $data['address'] = $address->where(['user_id' => $request['user_id'], 'status' => 1])->field("id,username,mobile,prov,address")->find();
        if (empty($arr['address'])) {
            $data['address'] = $address->where(['user_id' => $request['user_id']])->order("id desc")->field("id,username,mobile,prov,address")->find();
        }
        if(empty($data['address']))
        {
            $data['address']=[
                'id'=>'',
                'username'=>'',
                'mobile'=>'',
                'prov'=>'',
                'address'=>'',
            ];
        }

        return $crypt->response(['code' => 200, 'message' => "成功", 'data' => $data]);
    }





    /*--提交订单*/

    public function place_order(RSACrypt $crypt)

    {

        $request = $crypt->request();

        $address = Address::get($request['address_id']);

        $goods = Goods::get($request['goods_id']);

        Db::startTrans();

        try {

            $request['order_sn'] = date('Ymd', time()) . rand(1000000,9999999);

            $request['username'] = $address['username'];

            $request['address'] = $address['prov'] . $address['address'];

            $request['mobile'] = $address['mobile'];

            $request['good_name'] = $goods['title'];

            $request['store_id'] = $goods['store_id'];

            $request['freight'] = $request['carriage'];

            $request['create_time'] = time();

            $request['total'] = $request['carriage'] + $request['goods_price'];

            $request['goods_title'] = $goods['title'];

            $request['goods_picurl'] = $goods['picurl'];

            $request['goods_attr'] = $request['attr'];

            $a = new Orders();

            $a->allowField(true)->isUpdate(false)->save($request);

            $b = new OrdersList();

            $b->allowField(true)->isUpdate(false)->save($request);



            Db::commit();

            return ['code' => 200, 'message' => "成功",'data'=> $request['order_sn']];

        } catch (\Exception $e) {

            Db::rollback();

            return ['code' => 400, 'message' => '数据错误等待处理'];

        }

    }





    /*加入购物车*/
    public function caradd(RSACrypt $crypt, Car $car)
    {
        $request = $crypt->request();
        $goods = $car->where(['user_id' => $request['user_id'], 'goods_id' => $request['goods_id'],'attr'=>$request['attr']])->find();
        /*判断商品是否存在*/
        if (empty($goods)) { //如果商品不存在
            if (empty($request)) return $crypt->response(['code' => 200, 'message' => "请求参数有误"]);
            $request['store_id'] = Db::name('goods')->where('id',$request['goods_id'])->value('store_id');
            $result = $car->allowField(true)->isUpdate(false)->save($request);
            if ($result) {
                return $crypt->response(['code' => 200, 'message' => '成功']);
            } else {
                return $crypt->response(['code' => 400, 'message' => '失败']);
            }
        } else {
            $result=  $car->where(['user_id' => $request['user_id'], 'goods_id' => $request['goods_id'],'attr'=>$request['attr']])->setInc('num',$request['num']);

            if ($result) {
                return $crypt->response(['code' => 200, 'message' => '成功']);
            } else {
                return $crypt->response(['code' => 400, 'message' => '失败']);
            }
        }
    }

/*
 * 更改购物车属性和数量
 * */
    /*加入购物车*/
    public function setAttr(RSACrypt $crypt, Car $car)
    {
        $request = $crypt->request();
        if($request['num']==''){
            unset($request['num']);
        }
        if($request['attr']==''){
            unset($request['attr']);
        }
        $result= $car->allowField(true)->isUpdate(true)->save($request);
        return $crypt->response(['code' => 200, 'message' => '成功']);
    }



    /*购物车列表*/



    public function carlist(RSACrypt $crypt, Car $car)
    {
        $request = $crypt->request();
        $data = Db::table('mx_car')->alias('c')
            ->where([
                'user_id'=>$request['user_id'],
            ])
            ->join('store s', 's.id=c.store_id', 'LEFT')
            ->field('c.store_id,s.title,c.goods_id')
            ->group('store_id')
            ->select();

        foreach($data as &$v){
           $v['goods'] = Db::view('car','id,goods_id,num,price,attr,freight,create_time')
               ->view('goods','title,picurl,store_id','car.goods_id=goods.id')
               ->where(['car.store_id'=>$v['store_id'],'car.user_id'=>$request['user_id']])
               ->select();
        }

        foreach ($data as &$v) {
            foreach ($v['goods'] as &$value){
            $attr = explode('_', $value['attr']);
                if(count($attr)>1){
                    $value['attr_one'] = $attr[0];
                    $value['attr_two'] = $attr[1];
                }else{
                    $value['attr_one'] = $attr[0];
                    $value['attr_two'] = '';
                }
            }
        }

        return $crypt->response(['code' => 200, 'message' => '成功', 'data' => $data]);
    }



    /*删除购物车*/

    public function carDel(RSACrypt $crypt)
    {
        $request = $crypt->request();
        if (empty($request)) return $crypt->response(['code' => 400, 'message' => '请求方式错误']);
        $result = Db::name("car")
            ->where([
                'id' =>['in',$request['id']]
            ])
            ->delete();
        if ($result) {
            return $crypt->response(['code' => 200, 'message' => '成功']);
        } else {
            return $crypt->response(['code' => 400, 'message' => '失败']);
        }
    }



    /**
     * 地址：interfaces/Buy/cartClear
     * 方法：[cartClear   清空购物车]
     * 参数：
     * shop_id   用户id
     *
     * @param Request $request
     * @return array
     */



    public function carclear(RSACrypt $crypt)
    {
        $request = $crypt->request();
        if (empty($request)) return ['code' => 400, 'message' => '请求方式错误'];
        $result = Db::name("car")
            ->where([
                'user_id' => $request['user_id']
            ])->delete();
        if ($result) {
            return $crypt->response(['code' => 200, 'message' => '成功']);
        } else {
            return $crypt->response(['code' => 400, 'message' => '失败']);
        }
    }





    /*购物车商品属性列表*/

    public function attrlist(RSACrypt $crypt, Car $car)
    {
        $request = $crypt->request();
        $data = Db::view('car', "id,attr as choose_attr")
            ->view('goods', "id as goods_id,attr,goodsattr,picurl,title", 'goods.id=car.goods_id')
            ->where("id", $request['id'])
            ->find();
        $choose_attr = explode('_', $data['choose_attr']);

        if (!empty($data['attr'])) {
            $data['attr'] = unserialize($data['attr']);
            foreach($data['attr'] as $key =>&$v){
                if(count($choose_attr)>1){
                    $v['choose_attr_one'] = $choose_attr[0];
                    $v['choose_attr_two'] = $choose_attr[1];
                }else{
                    $v['choose_attr_one'] = $choose_attr[0];
                    $v['choose_attr_two'] = '';
                }
            }
        }
        if (!empty($data['goodsattr'])) {
            $test = unserialize($data['goodsattr']);

            foreach ($test as $key => &$item) {
                $item['shuxing'] = $key;
                if($key==$data['choose_attr']){
                    $data['choose_price'] = $item['price'];
                }

            }
            $data['goodsattr'] = array_values($test);
        }


        if (empty($data)) {
            $data = '';
        }
        return $crypt->response(['code' => 200, 'message' => '成功', 'data' => $data]);
    }



    /*购物车生成订单*/
    public function car_order(RSACrypt $crypt, Car $car)
    {
        $result = $crypt->request();

        if (empty($result))
            return ['code' => 400, 'message' => '请求方式错误'];

        $data['goods_list'] = Db::table('mx_car')->alias('c')
            ->where('c.id', 'in', $result['id'])
            ->join('store s', 's.id=c.store_id', 'LEFT')
            ->field('c.store_id,s.title')
            ->group('store_id')
            ->select();

        foreach($data['goods_list'] as &$v){
            $v['goods'] = Db::view('car','id as car_id,goods_id,num,freight,price,attr,create_time')
                ->view('goods','title,picurl,store_id','car.goods_id=goods.id')
                ->where(['car.store_id'=>$v['store_id'],'car.user_id'=>$result['user_id'],'car.id'=>['in',$result['id']]])
                ->select();
        }

        $toprice = 0;

        foreach ($data['goods_list'] as &$v) {
//            $v['ceshi_yun'] += $v['freight'];
            $goods_freight=0;
            $goods_num =0;
            $goods_total = 0;
            foreach($v['goods'] as $vv){
                $toprice += $vv['price'] * $vv['num'] + $vv['freight'];
                $goods_freight += $vv['freight'];
                $goods_num+=1;
                $goods_total += $vv['price'] * $vv['num'] + $vv['freight'];
                $vv['title'] = empty($vv['title']) ? "" : $vv['title'];
                $vv['picurl'] = empty($vv['picurl']) ? "" : $vv['picurl'];
            }
            $v['goods_freight'] = $goods_freight;
            $v['goods_num'] = $goods_num;
            $v['goods_total'] = $goods_total;
        }

        $address_data = Db::name("address")
            ->field("id,username,mobile,prov,address,status")
            ->where(["user_id" => $result['user_id'], 'status' => 1])
            ->find();

        $data['total'] = $toprice;
        $data['address'] = empty($address_data) ? ['id'=>'','username'=>'','mobile'=>'','prov'=>'','address'=>'','status'=>'',] : $address_data;
        return $crypt->response(['code' => 200, 'message' => '成功', 'data' => $data]);


//        //商品详情
//        $cart_list = Db::name('Car')
//            ->alias('c')
//            ->join('Goods g', 'g.id = c.goods_id', 'LEFT')
//            ->join('Store s', 's.id = g.store_id', 'LEFT')
//            ->field('c.id as car_id,c.num,c.goods_id,c.attr,c.freight,g.title,g.picurl,c.price,s.id as store_id,s.title as store_name')
//            ->where('c.id', 'in', $data['id'])
//            ->select();
        //运算价格

    }





    /*--购物车结算*/
    /*
     *
     * {
     * "toprice" : "518.00",   "freigt" : "0.00","content" : "",
     *     "goods" : [

     *              { "picurl" : "\/uploads\/20170704\/a76bca99883cfa28be10e72cd8f3c78c.png",

     *                 "attr" : "数量:2只装",

     *                  "price" : "518.00",

     *                   "title" : "格鲁吉亚原瓶原装进口红酒 肯泽马拉乌利半甜红葡萄酒 双支装",

     *                      "freight" : "",

     *                      "num" : "1",

     *                       "goods_id" : "270"

     *   }

     * ]

     *  }

     * */



    public function closecar_111(RSACrypt $crypt, Car $car)
    {
        $request = $crypt->request();
        $data = json_decode($request['data'], true);
        $goods_data = $data['goods'];
        $address_data = $data['address'];
        Db::startTrans();
        try {
            $temp = [];
            foreach ($goods_data as $value) {
                $temp[$value['store_id']][] = $value;
            }
            foreach ($temp as $value) {
                $price = 0;
                foreach ($value as $val) {
                    $price += $val['price'];
                }
                //根据店铺创建订单主表
                $car_arr = [
                    'order_sn' => date('Ymd', time()) . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8),
                    'username' => $address_data['username'],
                    'address' => $address_data['prov'] . $address_data['address'],
                    'mobile' => $address_data['mobile'],
                    'message' => $val['message'],
                    'goods_price' => $price,
                    'user_id' => $address_data['user_id'],
                    'store_id' => $val['store_id'],
                    'freight' => $val['freight'],
                    'create_time' => time(),
                    'total' => $val['freight'] + $price,
                ];
                //循环当前店铺的购买的商品生成订单商品表
                Db::name("Orders")->insert($car_arr);
                foreach ($value as $val) {
                    //插入订单商品表
                    Db::name("orders_list")->insert([
                        'order_sn' => $car_arr['order_sn'],
                        'goods_title' => $val['title'],
                        'goods_id' => $val['goods_id'],
                        'num' => $val['num'],
                        'goods_picurl' => $val['picurl'],
                        'goods_price' => $val['price'],
                        'goods_attr' => $val['attr'],
                        'create_time' => time(),
                    ]);
                }
            }
            Db::commit();
            return $crypt->response(['code' => 200, 'message' => '成功']);
        } catch (\Exception $e) {
            Db::rollback();
            return $crypt->response(['code' => 400, 'message' => '数据错误等待处理！']);
        }

    }

    public function closecar(RSACrypt $crypt, Car $car)
    {
        $request = $crypt->request();
        $data_message = json_decode($request['message'], true);

        $address_data = Db::name('address')->where('id',$request['address_id'])->find();
        $store_id = Db::table('mx_car')->alias('c')
            ->where('c.id', 'in', $request['car_id'])
            ->join('store s', 's.id=c.store_id', 'LEFT')
            ->field('c.store_id,s.title')
            ->group('store_id')
            ->select();           //49,5
        $data=[];
//        halt((array)$request['message']);
//        Db::startTrans();
//        try {

        $car_order_sn=[];
        foreach($store_id as $v){
            $data= Db::view('car')->view('goods','title as goods_title,picurl','car.goods_id=goods.id')
                ->where('car.id','in',$request['car_id'])->where('car.store_id',$v['store_id'])->select();
            $order_sn = date('Ymd', time()) . rand(1000000,9999999);//订单号
            $price = 0;
            $freight=0;
            foreach($data as $vv){
              $price += $vv['price']*$vv['num'];
              $freight += $vv['freight']*$vv['num'];
            }

            $message = '';
            foreach($data_message['data'] as $key =>$b){
                if($b['store_id']== $v['store_id']){
                    $message = $b['message'];
                }
            }
            $car_arr = [
                'order_sn' => $order_sn,
                'username' => $address_data['username'],
                'address' => $address_data['prov'] . $address_data['address'],
                'mobile' => $address_data['mobile'],
                'message' => $message,
                'goods_price' => $price,
                'user_id' => $address_data['user_id'],
                'store_id' => $v['store_id'],
                'freight' => $freight,
                'create_time' => time(),
                'total' => $freight + $price,
                'pay_type'=>$request['pay_type'],

            ];
            $car_order_sn[] = $order_sn;
            //循环当前店铺的购买的商品生成订单商品表
            Db::name("Orders")->insert($car_arr);
            foreach($data as $vv){
                    //插入订单商品表
                    Db::name("orders_list")->insert([
                        'order_sn' =>  $order_sn,
                        'goods_title' => $vv['goods_title'],
                        'goods_id' => $vv['goods_id'],
                        'num' => $vv['num'],
                        'goods_picurl' => $vv['picurl'],
                        'goods_price' => $vv['price'],
                        'goods_attr' => $vv['attr'],
                        'create_time' => time(),

                    ]);
            }

        }
            Db::commit();
            $merge_sn = date('Ymd', time()) . rand(666666,999999);//合并的订单的总id
             $all_order_sn = implode(',',$car_order_sn);
              Db::name('orders_merge')->insert(['order_sn'=>$all_order_sn,'merge_sn'=>$merge_sn]);
            Db::name('car')->where('id','in',$request['car_id'])->delete();
            return $crypt->response(['code' => 200, 'message' => '成功','data'=>$merge_sn]);
//        } catch (\Exception $e) {
//            Db::rollback();
//            return $crypt->response(['code' => 400, 'message' => '数据错误等待处理！']);
//        }

    }

    /*
     * 合并支付
     * */
    public function  ordersMerge(RSACrypt $crypt){
        $request = $crypt->request();

        $merge_sn = date('Ymd', time()) . rand(666666,999999);//合并的订单的总id
        $data = Db::name('orders_merge')->insert(['order_sn'=>$request['order_sn'],'merge_sn'=>$merge_sn]);
        if($data){
            return $crypt->response(['code' => 200, 'message' => '成功','data'=>$merge_sn]);

        }
    }
}