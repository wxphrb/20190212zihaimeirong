<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/4
 * Time: 10:28
 */

namespace app\api\controller;
use app\api\model\BbcType;
use app\api\model\BbcQuestion;
use app\api\model\BbcAnswer;
use app\api\model\User;
use mrmiao\encryption\RSACrypt;
use think\Controller;
use think\Db;
use think\Request;
use app\api\model\Posts as PostsModel;
use app\api\model\IndustryInformation as IndustryInformationModel;
use app\api\model\PostsComments;


class Hot extends Controller
{
    /*
     * 热门帖子 评论数量 点赞数量 收藏数量
     * 这个是定时任务，要拿走的先放在这
     * */
    public function rrrrrrrr(RSACrypt $crypt ,Posts $posts)
    {
        $posts_comment = Db::name("webconfig")->where("varname","posts_comment")->value("varvalue");      /*评论数*/
        $posts_prise = Db::name("webconfig")->where("varname","posts_prise")->value("varvalue");          /*点赞数*/
        $posts_collection = Db::name("webconfig")->where("varname","posts_collection")->value("varvalue");/*收藏数量*/
        $number=0;
        $subQuery = Db::table('mx_collection')->alias('c')
            ->join('posts p','p.id=c.collection_id')
            ->where([
                'c.type'=>6,
                'p.praise'=>['>=',0],
                'p.comments_num'=>['>=',0]
            ])
            ->field('collection_id')
            ->group('collection_id')
            ->having('count(collection_id)>'.$number)
            ->select();
        $ids = array_column($subQuery, 'collection_id');
        halt($ids);


    }
    public function posts(RSACrypt $crypt,BbcType $bbcType,PostsModel $posts)
    {
        $request = $crypt->request();
        //帖子列表 筛选条件 分类id 关键词
        $where['status']=1;
        $where['hot_state']=1;
        //列表
        $data['list'] = $posts->where($where)->field('id,title,picarr,hot_state,picarr_type,praise,comments_num,share_num')->paginate(10,false,['page'=>$request['page']]);
//        dump($data);die;
        return $crypt->response([
            'code' =>200,
            'message' => "成功",
            'data' =>  $data,
        ]);

    }
    /*
     * 热门问答 回答数量 收藏数量
     * */
    public function bbc(RSACrypt $crypt,BbcType $bbcType,BbcQuestion $bbcQuestion,BbcAnswer $answer)
    {
        $request = $crypt->request();
        //问答列表 筛选条件
        $where['status']=1;
        $where['hot_state']=1;
        //列表
        $data['list'] = $bbcQuestion->where($where)->field('id,title,hot_state,answer_num,integral_num,create_time')->paginate(10,false,['page'=>$request['page']]);
        //回答
        foreach($data['list'] as $v){
            $best_answer = $answer->where(['question_id'=>$v['id'],'status'=>1])->value('content');//问题最佳答案
            if(empty($best_answer)){//如果没有，就取最新的答案
                $mew_answer = $answer->where(['question_id'=>$v['id']])->order('id desc')->value('content');
                $v['answer'] = empty($mew_answer)?'':$mew_answer;
            }else{
                $v['answer']=$best_answer;
            }
        }
        return $crypt->response([
            'code' =>200,
            'message' => "成功",
            'data' =>  $data,
        ]);

    }
    /*
         * 热门资讯 分享数量 收藏数量
         * */
    public function industry(RSACrypt $crypt,IndustryInformationModel $industryInformation,User $user)
    {
        $request = $crypt->request();

        //列表 筛选条件 关键词
        $where['status']=1;
        $where['hot_state']=1;
        //列表
        $data = $industryInformation->where($where)->order('hot_sort asc')->field('id,title,user_id,picarr,picarr_type,hot_state,create_time,content,description')->paginate(10,false,['page'=>$request['page']]);

        foreach($data as &$v){
//            $v['picarr']=!empty($v['picarr'])?unserialize($v['picarr']):[];
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
}