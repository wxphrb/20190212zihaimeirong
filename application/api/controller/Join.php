<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/4
 * Time: 10:28
 */

namespace app\api\controller;
use app\api\model\Job;
use app\api\model\User;
use mrmiao\encryption\RSACrypt;
use think\Controller;
use think\Db;
use think\Request;

class Join extends Controller
{
    public function index(RSACrypt $crypt)
    {
        $list = Db::name("job_type")->select();
        return $crypt->response(['code'=>200,'message'=>"成功",'data'=>$list]);
    }

    public function join_list(RSACrypt $crypt)
    {
        $request = $crypt->request();
        $where = ['j.status'=>1,'j.type_id'=>$request['type']];
        if(!empty($request['keyword'])){
            $where['j.title'] = ['LIKE',"%".$request['keyword']."%"];
        }
       $data = Db::name("job")
           ->alias('j')
           ->join("User u",'u.id = j.user_id')
           ->where($where)
           ->field("j.id,j.user_id,j.address,j.title,j.create_time,u.username,u.picurl")
           ->order('j.id desc')
           ->paginate(20,false,['page' => $request['page']])->toArray();

        foreach($data['data'] as &$v) {
            $certification = Db::name('certification')->where(['user_id'=>$v['user_id']])->value('certification_type');
            if(empty($certification)) {
                $v['level'] = 0;
                $v['level_title'] = "普通";
            } else {
                $v['level'] = $certification;
                $v['level_title'] = Db::name('certification_type')->where('id',$certification)->value('title');
            }
        }
        return $crypt->response(['code'=>200,'message'=>"成功",'data'=>$data]);
    }

    public function detail(RSACrypt $crypt)
    {
         $request = $crypt->request();
        Db::name('news_info')->where(['user_id'=>$request['user_id'],'other_id'=>$request['id'],'read_state'=>0,'type'=>5])->update(['read_state'=>1]);

        $data = Db::name("job")
            ->alias('j')
            ->join("User u",'u.id = j.user_id')
            ->where("j.id",$request['id'])
            ->field("j.user_id,j.title,j.address,j.content,j.create_time,u.username,u.picurl,j.type_id")
            ->find();
        //判断是否收藏过
        $collection_status = 0;
        $collection_id = "";
        if(isset($request['user_id'])){
            $map = [
                'user_id' =>$request['user_id'],
                'collection_id' => $request['id'],
                'type' => 7
            ];
            $collection_data = Db::name('collection')->where($map)->find();
            if($collection_data){
                $collection_status = 1;
                $collection_id = $collection_data['id'];
            }
        }
        $data['collection_status'] = $collection_status;
        $data['collection_id'] = $collection_id;
        //身份标识
        $data['level_id'] = Db::name('user')->where('id', $data['user_id'])->value('level_id');
        $data['level_title'] = Db::name('certification_type')->where('id', $data['level_id'])->value('title');

        $data['content'] ="http://www.wantaozb.com/index.php/api/Join/infoweb?id=".$request['id'];
        return $crypt->response(['code'=>200,'message'=>"成功",'data'=>$data]);
    }

    /*
          * 供求平台---供求详情web
           * 参数：供求的id
          */
    public function infoWeb(Request $request){
        $post_data = $request->param();
        $content = Db::name("job")->where("id", $post_data['id'])->value("content");
        return make_show($content);
    }

    public function add_join(RSACrypt $crypt ,Job $job)
    {
        $request = $crypt->request();
        $result = $this->validate($request, 'Job');
        if ($result !== true) {
            return $crypt->response(['code' => 400, 'message' => $result]);
        }
        $data = $job->allowField(true)->isUpdate(false)->save($request);
        if($data){
            return $crypt->response(['code' =>200, 'message' => "发布成功"]);
        }else{
            return $crypt->response(['code' =>400, 'message' => "发布失败"]);
        }
    }
    /*删除订单(逻辑删除)*/

    public function job_del(RSACrypt $crypt, Job $job)
    {
        $request = $crypt->request();
        $result = $job->where(['id' => $request['id'],'user_id'=>$request['user_id']])->delete();
        if ($result) {
            return $crypt->response(['code' => 200, 'message' => '成功']);
        } else {
            return $crypt->response(['code' => 400, 'message' => '失败']);
        }
    }
}