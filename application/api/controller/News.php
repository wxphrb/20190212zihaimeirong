<?php

/**

 * Created by PhpStorm.

 * User: Administrator

 * Date: 2018/5/22

 * Time: 15:45

 */



namespace app\api\controller;



use mrmiao\encryption\RSACrypt;

use think\Controller;
use think\Request;
use app\api\model\NewsInfo as NewsInfoModel;



class News extends Controller
{
    public function index(RSACrypt $crypt, NewsInfoModel $newsInfo)
    {
        $request = $crypt->request();
        $where = array();
        if (!empty($request['status'])) {
            $where = ['status'=>$request['status']];
        }

        $data['list'] = $newsInfo
            ->where($where)
            ->where('user_id',$request['user_id'])
            ->order("create_time", "desc")
            ->field("id,type,title,other_id,read_state,create_time")
            ->order('id desc')
            ->paginate(10, false, []);
        $data['notice_num'] = $newsInfo->where(['user_id'=>$request['user_id'],'status'=>1,'read_state'=>0])->count();
        $data['news_num'] = $newsInfo->where(['user_id'=>$request['user_id'],'status'=>2,'read_state'=>0])->count();
        return $crypt->response(['code' => 200, 'message' => '成功', 'data' => $data]);

    }
    /*
         * 变为已读
         * */
    public function read(RSACrypt $crypt, NewsInfoModel $newsInfo){
        $request = $crypt->request();
        $data = $newsInfo->where('id','in',$request['id'])->update(['read_state'=>1]);
        if($data){
            return $crypt->response(['code' => 200, 'message' => '成功']);
        }
    }
    /*
     * 删除
     * */
    public function del(RSACrypt $crypt, NewsInfoModel $newsInfo){
        $request = $crypt->request();
        $data = $newsInfo->where('id','in',$request['id'])-> delete();
        if($data){
            return $crypt->response(['code' => 200, 'message' => '成功', 'data' => $data]);
        }
    }
    /*
     * 详情页
     * */
    public function news_web(Request $request, NewsInfoModel $newsInfo){
        $post_data = $request->param();
        $newsInfo->where("id", $post_data['id'])->update(['read_state'=>1]);
        $content = $newsInfo->where("id", $post_data['id'])->value("content");
        return make_show($content);
    }



}