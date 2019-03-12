<?php

namespace app\home\controller;

use app\api\model\Address;
use app\api\model\Job;
use app\api\model\Posts;
use app\home\model\BbcAnswer;
use app\home\model\BbcQuestion;
use app\home\model\Certification;
use app\home\model\Collection;
use app\home\model\Orders;
use app\home\model\Supply;
use app\home\model\User;
use app\home\model\Faq;
use app\home\model\Store;
use mrmiao\encryption\RSACrypt;
use think\Controller;
use think\Db;
use think\Request;
use think\Url;
use think\Session;

class My extends Base
{
    public function index(Request $request, User $user)
    {
        $data = $user->where("id", Session::get("user_id"))->find();
        if (empty($data['picurl'])) {
            $data['picurl'] = "img/wd_tx.png";
        }
        $status = Db::name("certification")->where("user_id", $data['id'])->value("status");
        return $this->fetch('index', [
            'title' => "我的",
            'user' => $data,
            'status' => $status,
        ]);
    }

    //我要推广
    public function expand(){

    }
    /*我的认证*/
    public function approve_save(Request $request, Certification $certification)
    {
        if ($request->isPost()) {
            $request = $request->param();
            $a = $request['pic'] . "," . $request['pic2'];
            $arr = explode(",", $a);
            foreach ($arr as $key => $v) {
                $row['img'] = $v;
                $arrs[] = $row;
            }
            $request['picarr'] = serialize($arrs);
            $request['time'] = time();
            $request['user_id'] = Session::get("user_id");
            $handle = empty($request['id']) ? false : true;
            $request['status'] = empty($request['id']) ? 1 : 1;
            $certification->allowField(true)->isUpdate($handle)->save($request);
            $this->redirect("my/index");
        }
        $data['c_type'] = Db::name("certification_type")->select();
        $info = Db::name("certification")->where("user_id", Session::get("user_id"))->find();
        $info['picarr'] = unserialize($info['picarr']);
        $info['status'] = empty($info['status']) ? '0' : $info['status'];
        return $this->fetch("renzheng", [
            'title' => "我的认证",
            'data' => $data,
            'info' => $info,
        ]);
    }

    public function my_order(Request $request, Orders $orders)
    {
        $data = Db::name("orders")
            ->alias("o")
            ->join("store s", 's.id = o.store_id')
//            ->join("orders_list os",'os.order_sn = o.order_sn')
            ->field("o.id,o.order_sn,o.status,s.title")
            ->where("o.user_id", Session::get("user_id"))
            ->order("o.status asc")
            ->select();
        foreach ($data as &$v) {
            $v['order_list'] = Db::name("orders_list")->where("order_sn", $v['order_sn'])->field("id,order_sn,goods_title,num,goods_picurl,goods_price,goods_attr")->select();
            foreach ($v['order_list'] as &$value) {
                $value['goods_attr'] = explode("_", $value['goods_attr']);
            }
        }
//        halt($data);
        return $this->fetch("order", [
            'title' => "我的订单",
            'data' => $data,

        ]);
    }

    /*取消订单*/
    public function cancel(Request $request, Orders $orders)
    {

        $ordersn = $request->param('order_sn');
        Db::startTrans();
        try {
            $orders->where("order_sn", $ordersn)->delete();
            Db::name("orders_list")->where("order_sn", $ordersn)->delete();
            Db::commit();
            return ['code' => 200];
        } catch (\Exception $e) {
            Db::rollback();
            return ['code' => 400];
        }
    }

    /*确认收货*/
    public function receipt(Request $request, Orders $orders)
    {
        $request = $request->param();
        $data['status'] = 3;
        $result = $orders->where(['order_sn' => $request['order_sn']])->update($data);
        if ($result) {
            return ['code' => 200, 'message' => '成功'];
        } else {
            return ['code' => 400, 'message' => '失败'];
        }
    }

    public function appraise(Request $request, Orders $orders)
    {
        $order_sn = $request->param("order_sn");
        $result = Db::name("orders_list")->where("order_sn", $order_sn)->field("id,goods_picurl")->select();
        return $this->fetch("appraise", [
            'title' => "评价",
            'data' => $result,
        ]);
    }

    /*收藏*/

    public function favorite(Request $request, Collection $collection, BbcAnswer $answer, BbcQuestion $bbcQuestion, Supply $supply, User $user)
    {

        $where = ['type' => 1, 'user_id' => Session::get("user_id")];

        /*商品收藏*/
        $data['goods'] = Db::view('Collection', 'type,collection_id,user_id')
            ->view("Goods", 'id,title,saleprice,sell,picurl', 'Goods.id = Collection.collection_id')
            ->where($where)
            ->select();
        /*商家收藏*/

        $wheres = ['type' => 2, 'user_id' => Session::get("user_id")];
        $data['store'] = Db::view('Collection', 'type,collection_id,user_id')
            ->view("Store", 'id,picurl,title', 'Store.id = Collection.collection_id')
            ->where($wheres)
            ->select();
        if (!empty($data['store'])) {
            foreach ($data['store'] as &$v) {
                $v['peo_num'] = $collection->where("collection_id", $v['collection_id'])->count();
            }
        }

        /*论坛*/
        $wheress = ['type' => 3, 'user_id' => Session::get("user_id")];
        $data['question'] = Db::view('Collection', 'type,collection_id,user_id')
            ->view("bbc_question", 'id,title,create_time,answer_num,integral_num', 'bbc_question.id = Collection.collection_id')
            ->where($wheress)
            ->select();

        foreach ($data['question'] as &$v) {
            $best_answer = $answer->where(['question_id' => $v['id'], 'status' => 1])->value('content');//问题最佳答案
            if (empty($best_answer)) {//如果没有，就取最新的答案
                $mew_answer = $answer->where(['question_id' => $v['id']])->order('id desc')->value('content');
                $v['answer'] = empty($mew_answer) ? '' : $mew_answer;
            } else {
                $v['answer'] = $best_answer;
            }
        }

        /*供求*/
        $where['status'] = 1;
        $where['state'] = 2;
        $where = ['type' => 4, 'user_id' => Session::get('user_id')];
        $data['supply'] = Db::view('Collection', 'type,collection_id,user_id')
            ->view("Supply", 'id,title,user_id,address,create_time', 'Supply.id = Collection.collection_id')
            ->view("User", 'id,username,picurl', 'User.id = Supply.user_id')
            ->where($where)
            ->select();


        /*行业资讯*/

        $where = ['type' => 5, 'userid' => Session::get('user_id')];
        $data['zixun'] = Db::view('Collection', ['type' => 'type', 'user_id' => 'userid', 'collection_id' => 'collection_id'])
            ->view("industry_information", 'id,title,picarr,user_id,picarr_type,create_time,content', 'industry_information.id = Collection.collection_id')
//                ->view("User", 'id,username,picurl', 'User.id = industry_information.user_id')
            ->where($where)
            ->select();
        if (!empty($data['zixun'])) {
            foreach ($data['zixun'] as &$v) {
                $v['picarr'] = unserialize($v['picarr']);
                if ($v['user_id'] != 0) {
                    $v['username'] = $user->where('id', $v['user_id'])->value('username');
                    $v['picurl'] = $user->where('id', $v['user_id'])->value('picurl');

                } else {
                    $v['username'] = '后台发布';
                    $v['picurl'] = '';
                }
            }
        }

        /*帖子*/
        $wheret = ['type' => 6, 'user_id' => Session::get('user_id')];
        $data['tiezi'] = Db::view('Collection', 'type,collection_id,user_id')
            ->view("Posts", 'id,title,picarr,praise,comments_num,content,share_num,hot_state', 'Posts.id = Collection.collection_id')
            ->where($wheret)
            ->select();
        foreach ($data['tiezi'] as &$v) {
            $v['picarr'] = unserialize($v['picarr']);
        }
        return $this->fetch("favorite", [
            'title' => "我的收藏",
            'data' => $data
        ]);
    }

    /*我的论坛（提问）*/
    public function question(Request $request, BbcQuestion $bbcQuestion, BbcAnswer $answer)
    {
        $where['status'] = 1;
        $where["user_id"] = Session::get('user_id');
        $data['list'] = $bbcQuestion->where($where)->field('id,title,answer_num,integral_num,create_time')->paginate(10);
        //回答
        foreach ($data['list'] as $v) {
            $best_answer = $answer->where(['question_id' => $v['id'], 'status' => 1])->value('content');//问题最佳答案
            if (empty($best_answer)) {//如果没有，就取最新的答案
                $mew_answer = $answer->where(['question_id' => $v['id']])->order('id desc')->value('content');
                $v['answer'] = empty($mew_answer) ? '' : $mew_answer;
            } else {
                $v['answer'] = $best_answer;
            }
        }
        return $this->fetch("question", [
            'title' => "我的论坛",
            'data' => $data
        ]);
    }


    /*我的帖子*/
    public function posts(Request $request, Posts $posts)
    {
        $where['status'] = 1;
        $where["user_id"] = Session::get('user_id');
        $data = $posts->where($where)->field('id,title,picarr,praise,comments_num,share_num')->paginate(10);
        return $this->fetch("posts", [
            'title' => "我的帖子",
            'data' => $data
        ]);
    }

    public function joins(Request $request, Job $job)
    {
        $request = $request->param();
        $where = ['j.status'=>1];
        $where = ['j.user_id'=>Session::get("user_id")];
        if(!empty($request['type_id'])){
            $where = ['j.type_id'=>$request['type_id']];
        }else{
            $request['type_id'] = 1;
        }
        if(!empty($request['keyword'])){
            $where['j.title'] = ['LIKE',"%".$request['keyword']."%"];
        }
        $data = Db::name("job")
            ->alias('j')
            ->join("User u",'u.id = j.user_id')
            ->where($where)
            ->field("j.user_id,j.address,j.title,j.create_time,u.username,u.picurl")
            ->paginate(20);
        return $this->fetch("join",[
            'title'=>"我的招聘",
            'data'=>$data,
            'type_id'=>$request['type_id'],
        ]);
    }


    /*我的供求*/
    public function supply(Request $request ,Supply $supply,User $user)
    {
        $where['status'] = 1;
        $where['state'] = 2;
        $where['user_id'] = Session::get('user_id');

        $data = $supply->where($where)->field('id,title,address,user_id,create_time')->paginate(10);
        foreach ($data as &$v) {
            if ($v['user_id'] != 0) {
                $v['username'] = $user->where('id', $v['user_id'])->value('username');
                $v['picurl'] = $user->where('id', $v['user_id'])->value('picurl');

            } else {
                $v['username'] = '后台发布';
                $v['picurl'] = '';
            }
        }
        return $this->fetch('supply',[
            'title'=>"我的供求",
            'data'=>$data
        ]);
    }


    /*我的地址*/
    public function address(Request $request ,Address $address)
    {
        $data =$address
            ->where(['user_id'=>Session::get('user_id')])
            ->order('status desc')
            ->field('id,username,mobile,prov,address,status')
            ->select();
        return $this->fetch("address",[
            'title'=>"我的地址",
            'data'=>$data
        ]);
    }

    /*设为默认*/
    public function SetUpDefault(Address $address, Request $request){
        $address->where(['user_id'=>Session::get('user_id')])->update(['status'=>0]);
        $data =$address->where(['id'=>$request->param('id'),'user_id'=>Session::get('user_id')])->update(['status'=>1]);
        if ($data) {
            return ['code' => 200];
        }
        return ['code' => 400];
    }

    /*添加地址*/
    public function add_address(Request $request,Address $address)
    {
        if($request->isPost()){
            $request = $request->param();
            //验证参数
            $results = $this->validate($request,'My.add_address');
            if(true !== $results){
                return ['code'=>400,'message' => $results];
            }

            if($request['defaults'] == 1){
                $address->where(['user_id'=>Session::get("user_id")])->update(['status'=>0]);
                $request['status'] = 1;
            }
            $request['user_id']=Session::get("user_id");
            $result = $address->allowField(true)->isUpdate(false)->save($request);
            if($result){
                    Db::name("addrlog")->insert([
                        'user_id'=>Session::get("user_id"),
                        'time'=>time(),
                    ]);
                return ['code'=>200];
            }else{
                return ['code'=>400];
            }
        }
        return $this->fetch("add_address",[
            'title'=>'添加地址',
        ]);
    }

    /*修改地址 */
    public function edit_address(Request $request,Address $address){
        if($request->isPost()){
            $request = $request->param();
            //验证参数
            $results = $this->validate($request,'My.edit_address');
            if(true !== $results){
                return ['code'=>400,'message' => $results];
            }
            $result = $address->allowField(true)->isUpdate(true)->save($request);
            if($result){
                return ['code'=>200];
            }else{
                return ['code'=>400];
            }
        }
        $request = $request->param();
        $data =$address->where(['id'=>$request['id']])->find();
        return $this->fetch("edit_address",[
            'title'=>"修改地址",
            'data'=>$data,
        ]);
    }
    /*删除地址*/
    public function del(Request $request,Address $address){
        $request = $request->param();
        $data =$address->where(['id'=>$request['id']])->delete();
        if($data){
            $this->success('删除成功', 'My/address');
        }else{
            $this->error('删除失败', 'My/address');
        }
    }
    /*客服中心*/
    public function customservice(Request $request,Faq $faq){
        $tel['tel'] = Db::name("webconfig")->where("varname", 'web_hotline')->field("varvalue")->find();
        $data = $faq->where(['status'=>1])->field("id,title,picurl")->select();
        return $this->fetch("customservice",[
            'title'=>'客服中心',
            'data' =>$data,
            'tel'=>$tel['tel'],
        ]);
    }
    /*常见问题*/
    public function custom(Request $request,Faq $faq){
        $request = $request->param();
        $data = $faq->where(['id' => $request['id'],'status'=>1])->field("id,title,content")->find();
        return $this->fetch('wenti',[
            'title'=>'常见问题',
            'data'=>$data,
        ]);
    }
    /*意见反馈*/
    public function speak(Request $request,Address $address){
        return $this->fetch("speak",[
            'title'=>'意见反馈',
        ]);
    }
    /*分享好友*/
    public function share(User $user){
        //查询用户
        $data = $user
            ->where("id", Session::get("user_id"))
            ->find();
        //查询用户是否认证
        $cer=Db::name("certification")->where("user_id", $data['id'])->find();
        //认证的用户 查询认证类别
        if($cer){
            $data = $user
                ->alias('u')
                ->join('mx_certification c','u.id = c.user_id')
                ->join('mx_certification_type ct','c.certification_type = ct.id')
                ->where("user_id", Session::get("user_id"))
                ->find();
        }
        //无头像为默认头像
        if (empty($data['picurl'])) {
            $data['picurl'] = "wd_tx.png";
        }
        return $this->fetch("share",[
            'title'=>'分享好友',
            'user' => $data,
        ]);
    }
    //消息中心
    public function message(){
        return $this->fetch("message",[
            'title'=>'消息中心',
        ]);
    }
    //我要开店
    public function upshop(Store $store ,Request $request){
        if (Request::instance()->isPost()) {
            $data['mobile'] = Request::instance()->post('mobile');
            $data['title'] = Request::instance()->post('title');
            $find = $store->where('mobile',$data['mobile'])->find();
            $title = $store->where('title',$data['title'])->find();
            // 店铺名
            if($title){
                return ['data'=>4];
            }
            if ($find) {
                return ['data'=>3];
            }else{
//                $data['password'] = md5(md5($request->param('password')));
                $data['create_time'] = time();
                $data['mobile'] = $request->param('mobile');
                $data['title'] = $request->param('title');
                $data['manned'] = $request->param('manned');
                $data['address'] = $request->param('address');
                if ($store->allowField(true)->isUpdate(false)->save($data)) {
                    return ['data'=>1];
                }else{
                    return ['data'=>2];
                }
            }
        }
        return $this->fetch("upshop",[
            'title'=>'我要开店',
        ]);
    }
}