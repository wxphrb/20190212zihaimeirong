<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/4
 * Time: 10:28
 */

namespace app\api\controller;
use app\api\model\Banner;
use app\api\model\Goods;
use app\api\model\Store;
use mrmiao\encryption\RSACrypt;
use think\Controller;
use think\Request;
use think\Db;

class Index extends Controller
{
    /*
     * 首页---1、首页
     */
    public function index(RSACrypt $crypt,Banner $banner,Store $store,Goods $goods){
        $request = $crypt->request();
        //消息未读数量
        if($request['user_id'] !="") {
            $data['num'] = Db::name('news_info')->where(['user_id'=>$request['user_id'],'read_state'=>0])->count('id');
        } else {
            $data['num'] = "";
        }
        //轮播图
        $data['banner'] =$banner->where(['status'=>1,'is_pc'=>0])->field('id,picurl,title,target,content')->order('create_time desc')->select();
        foreach($data['banner'] as $v){
            if($v['target']==0){
                $v['content'] = "http://www.wantaozb.com/index.php/api/Index/bannerWeb?id=".$v['id'];
            }
        }
        //发现好店type_id
        $data['hot_store']=$store->where(['status'=>1,'hot'=>1])->with('goodslist')->limit(2)->field('id,title,pic')->select();


        /*招聘banner*/
        $data['join']=Db::name("webconfig")->where(['varname'=>'share_img'])->limit(1)->value("varvalue");
        //热门推荐
        $data['hot_goods']=$goods->where(['hot'=>1,'status'=>1])->limit(4)->field('id,picurl,title,saleprice,sell')->select();
        //猜你喜欢
        if($request['user_id']!=''){  //登录了
            $favorite = Db::name('favorite')->where(['user_id'=>$request['user_id']])->order('id desc')->find();//看看最近有没有浏览商品
            if(!empty($favorite)){
                $data['favorite'] = Db::view('goods','id,picurl,title,saleprice,sell')->view('favorite','typeid','favorite.typeid=goods.typeid')->where(['status'=>1,'user_id'=>$request['user_id']])->limit(6)->select();
            }else{
                $data['favorite']= $goods->where(['hot'=>1,'status'=>1])->limit(6)->field('id,picurl,title,saleprice,sell')->select();//没有浏览商品 随便展示6个
            }
        }else{
            $data['favorite']=$goods->where(['hot'=>1,'status'=>1])->limit(6)->field('id,picurl,title,saleprice,sell')->select();
        }


//        $data['favorite']=$goods->where(['hot'=>1,'status'=>1])->limit(6)->field('id,picurl,title,saleprice,sell')->select();
//        $data['favorite'] = Db::view('favorite')
        return $crypt->response([
            'code' =>200,
            'message' => "成功",
            'data' =>  $data,
        ]);
    }

     /*
      * 客服电话
      * */
    public function faq(RSACrypt $crypt){
        $request = $crypt->request();
        $data['faq'] = Db::name("faq")->field("id,title,picurl")->select();
        $data['hotline'] = Db::name('webconfig')->where('varname','web_hotline')->value('varvalue');
        $data['phone'] = Db::name('webconfig')->where('id',7)->value('varvalue');
        return $crypt->response([
            'code' =>200,
            'message' => "成功",
            'data' =>  $data,
        ]);
    }

    /*
         * 供求平台---供求详情web
          * 参数：供求的id
         */
    public function faqWeb(Request $request){
        $post_data = $request->param();
        $content = Db::name("faq")->where("id", $post_data['id'])->value("content");
        return make_show($content);
    }
        /*
        * 供求平台---供求详情web
         * 参数：供求的id
        */
    public function otherWeb(Request $request){
        $post_data = $request->param();
        $content = Db::name("info")->where("id", $post_data['id'])->value("content");
        return make_show($content);
    }
    /*
         * 供求平台---供求详情web
          * 参数：供求的id
         */
    public function bannerWeb(Request $request){
        $post_data = $request->param();
        $content = Db::name("banner")->where("id", $post_data['id'])->value("content");
        return make_show($content);
    }
    public function test(){
       push_user('测试',25,0,1,1);
    }

    public function version(RSACrypt $crypt){
        $post_data= $crypt->request();
        $version='';
        if($post_data['status']==1){//ios
            $version = Db::name('webconfig')->where('varname','ios')->value('varvalue');
        }elseif($post_data['status']==2){//anzhuo
            $version = Db::name('webconfig')->where('varname','Android')->value('varvalue');
        }
        if($version !=''){
            if($version>$post_data['version']){
                $data['status'] = 1;//更新
                if($post_data['status']==1){
                    $data['version'] = Db::name('webconfig')->where('varname','ios_url')->value('varvalue');
                }else{
                    $data['version']=Db::name('webconfig')->where('varname','Android_url')->value('varvalue');
                }

            }else{
               $data['status'] =2;
               $data['version']='';
            }
            return $crypt->response(['code' => 200, 'message' => '数据返回成功！', 'data' => $data]);
        }else{
            return $crypt->response(['code' =>400, 'message' => '数据返回失败！']);

        }

    }


}