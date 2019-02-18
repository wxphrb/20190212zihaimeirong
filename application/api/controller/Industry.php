<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/4
 * Time: 10:28
 */

namespace app\api\controller;
use app\api\model\IndustryInformation as IndustryInformationModel;
use app\api\model\User;
use mrmiao\encryption\RSACrypt;
use think\Controller;
use think\Db;
use think\Request;

class Industry extends Controller
{
    /*
     * 行业资讯---1、列表
     */
    public function index(RSACrypt $crypt,IndustryInformationModel $industryInformation,User $user){
        $request = $crypt->request();

        //列表 筛选条件 关键词
        $where['status']=1;
        if(!empty($request['keyword'])){
            $where['title']=['like','%'.$request['keyword'].'%'];
        }
        //列表
        $data = $industryInformation->where($where)
            ->field('id,title,user_id,picarr,picarr_type,hot_state,create_time,description')
            ->order('sort asc')->paginate(10,false,['page'=>$request['page']]);
        foreach($data as &$v){
            $v['username'] = $user->where('id',$v['user_id'])->value('username');
            $v['picurl'] = $user->where('id',$v['user_id'])->value('picurl');
            $certification = Db::name('certification')->where(['user_id'=>$v['user_id']])->value('certification_type');
            if(empty($certification)) {
                $v['level'] = 0;
                $v['level_title'] = "普通";
            } else {
                $v['level'] = $certification;
                $v['level_title'] = Db::name('certification_type')->where('id',$certification)->value('title');
            }
        }

        return $crypt->response([
            'code' =>200,
            'message' => "成功",
            'data' =>  $data,
        ]);
    }

    /*
    * 行业资讯---2、详情
     * 参数：行业资讯的id
    */
    public function info(RSACrypt $crypt,User $user,IndustryInformationModel $industryInformation){
        $request = $crypt->request();
        $data =$industryInformation->where('id',$request['id'])->field('title,user_id,picarr,picarr_type,create_time')->find();

            $data['username'] = $user->where('id',$data['user_id'])->value('username');
            $data['picurl'] = $user->where('id',$data['user_id'])->value('picurl');
        $data['content'] ="http://www.wantaozb.com/index.php/api/Industry/infoweb?id=".$request['id'];
        if($request['user_id']!=''){
            $collect = Db::name("collection")
                ->where([
                    'type' => 5,
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
        //身份标识
        $data['level_id'] = $user->where('id', $data['user_id'])->value('level_id');
        $data['level_title'] = Db::name('certification_type')->where('id', $data['level_id'])->value('title');
        return $crypt->response([
            'code' =>200,
            'message' => "成功",
            'data' =>  $data,
        ]);
    }

    /*
        * 详情页
        * */
    public function infoweb(Request $request, IndustryInformationModel $industryInformation){
        $post_data = $request->param();
        $content = $industryInformation->where("id", $post_data['id'])->value("content");
        return make_show($content);
    }
    /*
      * 行业资讯---我要发布   不要了，用户不可以自己发布了
      */
    public function informationAdd(RSACrypt $crypt,IndustryInformationModel $industryInformation){
        $request = $crypt->request();
        $result = $this->validate($request, 'IndustryInformation.informationAdd');
        if ($result !== true) {
            return $crypt->response([
                'code' => 400,
                'message' => $result
            ]);
        }
        $request['picarr'] = toImgApi( $request['picarr']);

        $data = $industryInformation->allowField(true)->isUpdate(false)->save($request);
        if($data){
            return $crypt->response([
                'code' =>200,
                'message' => "成功",
            ]);
        }
    }

}