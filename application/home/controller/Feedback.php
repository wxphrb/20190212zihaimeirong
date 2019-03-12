<?php

namespace app\home\controller;

use app\home\model\Banner;

use app\home\model\Goods;

use app\home\model\Store;

use think\Controller;

use think\Db;

use think\Request;

use think\Session;
use think\Url;
use think\Response;

class Feedback extends Base
{
    /*
     * 意见反馈
     **/
          public function index(Request $request ,Goods $goods , Store $store , Banner $banner){

//		echo 1;die;
                    return $this->fetch(
                              'index',[
                                        'title'=>"意见反馈",
                              ]
                    );

          }

          function add_feed(){
                    $param = Request::instance()->param();
                    $picarr=toImg($param['picArr']);
                    $data = [
                              'username'=>$param['username'],
                              'mobile'=>$param['mobile'],
                              'content'=>$param['content'],
                              'picarr'=>$picarr['picarr'],
                              'create_time'=>time(),
                              'type'=>$param['type'],
                              'user_id'=>Session::get('user_id'),
                    ];
                    $a = Db::name('feedback')->insert($data);
                    if($a) {
                              return 1;
                    } else {
                              return 2;
                    }
//		dump($picarr);die;
          }
          public function uploadImgDestroy()
          {
                    $picUrl = Request::instance()->param('picUrl');

                    if (!empty($picUrl)) {

                              unlink($picUrl);
                              return ['code' => 200, 'message' => '取消成功'];
                    }
                    return ['code' => 400, 'message' => '网络繁忙'];
          }

}