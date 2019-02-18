<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/4
 * Time: 10:28
 */

namespace app\api\controller;
use app\api\model\Address as AddressModel;
use app\api\model\User;
use mrmiao\encryption\RSACrypt;
use think\Controller;
use think\Db;
use think\Request;

class Address extends Controller
{
   /*
    * 我的--1、我的地址--列表
    * */
    public function index(AddressModel $address, RSACrypt $crypt)
    {
//        try {
            $post_data = $crypt->request();
            $data =$address->where(['user_id'=>$post_data['user_id']])->order('status desc')->field('id,username,mobile,prov,address,status')->select();
//            if ($data) {
                return $crypt->response(['code' => 200, 'message' => '数据返回成功！','data'=>$data]);
//            }
//            return $crypt->response(['code' => 400, 'message' => '数据返回失败！']);
//        } catch (\Exception $e) {
//            return $crypt->response(['code' => 400, 'message' => '数据错误等待处理！']);
//        }
    }


    /*
     * 添加收货地址
     * user_id  name phone prov address status
     * */
    public function add(AddressModel $address, RSACrypt $crypt){
        try {
            $post_data = $crypt->request();
            $result = $this->validate($post_data, 'Address.add');  //验证参数
            if ($result !== true) {
                return $crypt->response(['code' => 400, 'message' => $result]);
            }
            if($post_data['status']==1){
                $address->where('user_id',$post_data['user_id'])->update(['status'=>0]);
            }
            $data = $address->allowField(true)->save($post_data);
            if ($data) {
                return $crypt->response(['code' => 200, 'message' => '添加成功！']);
            }
            return $crypt->response(['code' => 400, 'message' => '添加失败！']);
        } catch (\Exception $e) {
            return $crypt->response(['code' => 400, 'message' => '数据错误等待处理！']);
        }

    }

    /*
     * 地址详情
     * */
    public function info(AddressModel $address, RSACrypt $crypt){
        $post_data = $crypt->request();
        $data = $address->where('id',$post_data['id'])->find();
        return $crypt->response(['code' => 200, 'message' => '成功！','data'=>$data]);
    }
    /*
     * 编辑收货地址
     * id  user_id
     * */
    public function update(AddressModel $address, RSACrypt $crypt){
        try {
            $post_data = $crypt->request();
            $result = $this->validate($post_data, 'Address.update');  //验证参数
            if ($result !== true) {
                return $crypt->response(['code' => 400, 'message' => $result]);
            }
            if($post_data['status']==1){
                $address->where('user_id',$post_data['user_id'])->update(['status'=>0]);
            }
            $data = $address->isUpdate(true)->allowField(true)->save($post_data,['id'=>$post_data['id']]);
            if ($data) {
                return $crypt->response(['code' => 200, 'message' => '修改成功！']);
            }
            return $crypt->response(['code' => 400, 'message' => '修改失败！']);
        } catch (\Exception $e) {
            return $crypt->response(['code' => 400, 'message' => '数据错误等待处理！']);
        }
    }



    /*
      * 删除收货地址
      * id  user_id
      * */

    public function del(AddressModel $address, RSACrypt $crypt){
        try {
            $post_data = $crypt->request();
            $result = $this->validate($post_data, 'Address.del');  //验证参数
            if ($result !== true) {
                return $crypt->response(['code' => 400, 'message' => $result]);
            }
            $data = $address->where(['id'=>$post_data['id'],'user_id'=>$post_data['user_id']])->delete();
            if ($data) {
                return $crypt->response(['code' => 200, 'message' => '删除成功！']);
            }
            return $crypt->response(['code' => 400, 'message' => '删除失败！']);
        } catch (\Exception $e) {
            return $crypt->response(['code' => 400, 'message' => '数据错误等待处理！']);
        }
    }





    /*
     * 设置默认
     * id  user_id
    * */
    public function SetUpDefault(AddressModel $address, RSACrypt $crypt){
            $post_data = $crypt->request();
            $result = $this->validate($post_data, 'Address.SetUpDefault');  //验证参数
            if ($result !== true) {
                return $crypt->response(['code' => 400, 'message' => $result]);
            }

            $address->where(['user_id'=>$post_data['user_id']])->update(['status'=>0]);
            $data =$address->where(['id'=>$post_data['id'],'user_id'=>$post_data['user_id']])->update(['status'=>1]);

            if ($data) {
                return $crypt->response(['code' => 200, 'message' => '设置成功！']);
            }
            return $crypt->response(['code' => 400, 'message' => '设置失败！']);

    }
}