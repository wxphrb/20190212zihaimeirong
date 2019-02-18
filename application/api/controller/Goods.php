<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/4
 * Time: 10:28
 */
namespace app\api\controller;
use app\api\model\GoodsType as GoodsTypeModel;
use app\api\model\Goods as GoodsModel;
use app\api\model\Evaluate as Evaluate;
use app\api\model\Store;
use mrmiao\encryption\RSACrypt;
use think\Config;
use think\Controller;
use think\Db;
use think\Request;
class Goods extends Controller
{
    /*
     * 商品列表
     * */
    public function index(RSACrypt $crypt, GoodsTypeModel $goodsType, Store $store, GoodsModel $goods)
    {
        $request = $crypt->request();
        $where['status'] = 1;
        if (!empty($request['keyword'])) {
            $where['title'] = ['like', '%' . $request['keyword'] . '%'];
        }
        if(!empty($request['goodstype_id'])){
            $where['typeid'] = $request['goodstype_id'];
        }
        if(!empty($request['store_id'])){
            $where['store_id'] = $request['store_id'];
        }
//        dump($where);die;
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
        if(!empty($request['evaluate_num'])){
            switch ($request['evaluate_num']) {
                case 1:
                    $map['evaluate_num'] = "asc";
                    break;   //销量正序
                case 2:
                    $map['evaluate_num'] = "desc";
                    break;
            }}
        if (!empty($request['pro'])) {
            $ids = [];
            $id = $store->where('pro', $request['pro'])->field('id')->select();
            if (!empty($id)) {
                foreach ($id as $v) {
                    $ids[] = $v['id'];
                }
            }
            $where['store_id'] = ['in', implode(',', $ids)];
        }
        if($map==''){
            $map['id'] = "desc";
        }
//        dump($where);die;
        $data = $goods->where($where)->field('id,sell,title,picurl,saleprice')->order($map)->paginate(10, false, ['page' => $request['page']]);
        return $crypt->response(['code' => 200, 'message' => '成功', 'data' => $data]);
    }
    /*
     * 商品详情
     * */
    public function info(RSACrypt $crypt, GoodsModel $goods, Evaluate $evaluate)
    {
        $request = $crypt->request();
        $where='';
        if(!empty($request['store_id'])){
            $where['store_id'] = $request['store_id'];
        }
        //商品信息
        $data = $goods->where('id', $request['id'])->where($where)->field('id,picarr,store_id,title,picurl,typeid,saleprice,specifications,sell,content,attr,goodsattr')->find();
        //删掉之前的重复浏览数据 避免猜你喜欢中有重复数据
        $del =  Db::name('favorite')->where(['user_id'=>$request['user_id'],'typeid'=>$data['typeid']])->delete();
        ////添加到最近浏览
        Db::name('favorite')->insert(['user_id'=>$request['user_id'],'typeid'=>$data['typeid']]);
        $data['picarr'] =!empty($data['picarr']) ? unserialize($data['picarr']) :[];
        if(!empty($data['attr'])){
            $data['attr'] = !empty($data['attr']) ? unserialize($data['attr']) :[];
        }else{
            $data['attr'] = [];
        }
        if(!empty($data['goodsattr'])){
            $test = unserialize($data['goodsattr']);
            foreach ($test as $key => &$item) {
                $item['shuxing'] = $key;
            }
            $data['goodsattr'] = array_values($test);
        }else{
            $data['goodsattr'] = [];
        }
        if(!empty($data['goodsattr'][0])){
            $data['carriage'] =  $data['goodsattr'][0]['carriage'];
        }
        $data['appraise_count']= Db::name('evaluate')->where(['goods_id'=>$request['id']])->count('id');
        if($request['user_id']!=''){
            $collect = Db::name("collection")
                ->where([
                    'type' => 1,
                    "collection_id" => $request['id'],
                    'user_id'=>$request['user_id']
                ])
                ->find();
            $data['collection_id'] = empty($collect['id']) ? "" :$collect['id'] ;
            $data['collection'] = empty($collect) ? 0 : 1;
        }else{
            $data['collection_id'] = "";
            $data['collection'] =0;
        }
        $mobile = Db::name('store')->where('id',$data['store_id'])->field('mobile,qq')->find();
        //开关  电话(后加的)
        $config = Db::name('webconfig')->where('id',8)->value('varvalue');

        if($config == 1) {
            $data['store_mobile'] = Db::name('webconfig')->where('id',5)->value('varvalue');;
            $data['store_qq'] = Db::name('webconfig')->where('id',36)->value('varvalue');;
        } else {
            $data['store_mobile'] = $mobile['mobile'];
            $data['store_qq'] = $mobile['qq'];
        }
        return $crypt->response(['code' => 200, 'message' => '成功', 'data' => $data]);
    }
    public function good_detail(GoodsModel $goods , RSACrypt $crypt)
    {
        $request = $crypt->request();
        $content = $goods->where("id", $request['id'])->value("content");
        $view = new \think\View();
        Config::set("default_return_type", "html");
        $css = "<style>
        html, div, dl, dt, dd, ol, ul, li, h1, h2, h3, h4, h5, h6, pre, form, fieldset, input, textarea, p, blockquote, th, td, p {
            margin: 0;
            padding: 0;
        }
        body{
        margin: 2%;
            padding: 2%;
        }
        img {
            border: 0;
            width: 96%;
            margin: 2px 2%;
            vertical-align: bottom;
        }
    </style>
";
        return $view->display($css . $content);
    }
    public function good_size(GoodsModel $goods , RSACrypt $crypt)
    {
        $request = $crypt->request();
        $content = $goods->where("id", $request['id'])->value("specifications");
        $view = new \think\View();
        Config::set("default_return_type", "html");
        $css = "<style>
        html, div, dl, dt, dd, ol, ul, li, h1, h2, h3, h4, h5, h6, pre, form, fieldset, input, textarea, p, blockquote, th, td, p {
            margin: 0;
            padding: 0;
        }
        body{
        margin: 2%;
            padding: 2%;
        }
        img {
            border: 0;
            width: 96%;
            margin: 2px 2%;
            vertical-align: bottom;
        }
    </style>
";
        return $view->display($css . $content);
    }
    public function good_appraise(RSACrypt $crypt )
    {
        $request = $crypt->request();
        //商品评价
        $evaluates = Db::view('evaluate', 'picarr,type,content,user_id,anonymous,create_time')
//            ->view('user', 'username,picurl', 'user.id=evaluate.user_id')
            ->where(['evaluate.goods_id' => $request['id'], 'evaluate.status' => 1])
            ->select();
        foreach ($evaluates as &$v) {
            if(!empty($v['picarr'])){
                $picarr = unserialize($v['picarr']);
                sort($picarr);
            }else{
                $picarr = [];
            }
            $v['picarr'] = $picarr;
            $v['username'] = Db::name("user")->where("id",$v['user_id'])->value("username");
            $v['picurl'] = Db::name("user")->where("id",$v['user_id'])->value("picurl");
        }
        return $crypt->response(['code' => 200, 'message' => '成功', 'data' => $evaluates]);
    }
}