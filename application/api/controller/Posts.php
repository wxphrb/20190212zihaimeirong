<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/4
 * Time: 10:28
 */

namespace app\api\controller;
use app\api\model\BbcType;
use app\api\model\Posts as PostsModel;
use app\api\model\PostsComments;
use app\api\model\User;
use mrmiao\encryption\RSACrypt;
use think\Controller;
use think\Db;
use think\Request;

class Posts extends Controller
{
    /*
     * 帖子---1、列表
     * 参数 ：类别id 关键字
     */
    public function index(RSACrypt $crypt,BbcType $bbcType,PostsModel $posts){
        $request = $crypt->request();
        //帖子列表分类
        $data['bbc_type'] =$bbcType->where(['status'=>1])->field('type_title,id')->order('id desc')->select();
        //帖子列表 筛选条件 分类id 关键词
        $where['status']=1;
        if(!empty($request['type_id'])){
            $where['type_id']=$request['type_id'];
        }
        if(!empty($request['keyword'])){
            $where['title']=['like','%'.$request['keyword'].'%'];
        }
        //列表
        $cc = $posts->where($where)->field('id,title,picarr,user_id,picarr_type,praise,comments_num,hot_state,share_num')->order('id desc')->paginate(10,false,['page'=>$request['page']]);
        foreach($cc as &$v) {
            $certification = Db::name('certification')->where(['user_id'=>$v['user_id']])->value('certification_type');
            if(empty($certification)) {
                $v['level'] = 0;
                $v['level_title'] = "普通";
            } else {
                $v['level'] = $certification;
                $v['level_title'] = Db::name('certification_type')->where('id',$certification)->value('title');
            }
        }
        $data['list'] = $cc;

        return $crypt->response([
            'code' =>200,
            'message' => "成功",
            'data' =>  $data,
        ]);
    }

    /*
    * 帖子---2、帖子详情
     * 参数：帖子的id
    */
    public function info(RSACrypt $crypt,User $user,Posts $posts,PostsComments $comments){
        $request = $crypt->request();
        //消息表标为已读
        Db::name('news_info')->where(['user_id'=>$request['user_id'],'other_id'=>$request['id'],'read_state'=>0,'type'=>2])->update(['read_state'=>1]);
        $data = Db::view('posts','id,title,picarr,content,user_id,picarr_type,describe,praise,comments_num,create_time,hot_state,is_console')->view('user','username,picurl,level_id','user.id=posts.user_id','left')
            ->view('certificationType',['title'=>'level_title'],'certificationType.id=user.level_id','left')
            ->where('id',$request['id'])->find();
        if($data['is_console']==2){
            //后台
            $data['web_content'] = "http://www.wantaozb.com/index.php/api/Posts/postWeb?id=".$request['id'];
        }else{
            //前台
            $data['web_content'] = $data["content"];
        }
        $data['picarr'] = !empty($data['picarr'])?unserialize($data['picarr']):[];

        if($request['user_id']!=''){
            $collect = Db::name("collection")
                ->where([
                    'type' => 6,
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
        $parise_status = Db::name('praise')->where(['praise_id'=>$request['id'],'user_id'=>$request['user_id'],'type'=>1])->find();
        if(!empty($parise_status)){
            $data['parise_status'] = 1;//点过
        }else{
            $data['parise_status'] = 0;//没点过
        }
        $data['answer'] =Db::view('posts_comments','id,content,reply_id,create_time,user_id,parent_id')->view('user','username,picurl,level_id','user.id=posts_comments.user_id','left')
            ->view('certificationType',['title'=>'level_title'],'certificationType.id=user.level_id','left')
            ->where(['posts_id'=>$request['id'],'parent_id'=>'0'])->order(['posts_comments.id'=>'desc'])->select();

        foreach($data['answer'] as &$v){

            $v['reply']=Db::view('posts_comments','id,user_id,reply_user,posts_id,reply_id,content,create_time')
                ->view('user','username','user.id=posts_comments.user_id','left')
                ->view('user u',['username'=>'reply_user'],'u.id=posts_comments.reply_user','left')
                ->where('posts_comments.reply_id',$v['id'])
                ->order(['posts_comments.create_time'=>'asc'])
                ->select();
        }
        return $crypt->response([
            'code' =>200,
            'message' => "成功",
            'data' =>  $data,
        ]);
    }

    public function postWeb(Request $request){
        $post_data = $request->param();
        $content = Db::name("posts")->where("id", $post_data['id'])->value("content");
        return make_show($content);
    }


     /*
      * 杨思涵 2018-05-08
      * 帖子---3、一级跟帖的提交
      * 参数：回复的哪个问题的问题ID
      */
    public function commentsAdd(RSACrypt $crypt,PostsModel $posts,PostsComments $comments){
        $request = $crypt->request();
        $result = $this->validate($request, 'Posts.commentsAdd');
        if ($result !== true) {
            return $crypt->response([
                'code' => 400,
                'message' => $result
            ]);
        }
        $data = $comments->allowField(true)->isUpdate(false)->save($request);//回答
        if($data){
            $posts->where('id',$request['posts_id'])->setInc('comments_num',1);//问题表回答数量+1
            //变成热帖
            //帖子配置评论数量
            $webconfig_wenda = Db::name('webconfig')->where('id',9)->value('varvalue');
            //帖子已评论数量
            $collection3 = Db::name('posts_comments')->where('posts_id',$request['posts_id'])->count('id');
            if($collection3 >= $webconfig_wenda) {
                $bbc = Db::name('posts')->where(['id'=>$request['posts_id'],'hot_state'=>0])->update(['hot_state'=>1]);
                if($bbc) {
                    $user_id = Db::name('posts')->where(['id'=>$request['posts_id']])->value('user_id');
                    if($user_id != 0) {
                        $webconfig_jifen = Db::name('webconfig')->where('id',15)->value('varvalue');
                        Db::name('user')->where('id',$user_id)->setInc('integral',$webconfig_jifen);
                    }
                }
            }
            return $crypt->response([
                'code' =>200,
                'message' => "提交成功",
            ]);
        }
    }
    /*
     * 杨思涵 2018-05-08
     * 热帖---4、回复一级跟帖的提交
     * 参数：回复哪个回答的回答ID
     */
    public function replyAdd(RSACrypt $crypt,PostsComments $comments,PostsModel $posts){
        $request = $crypt->request();
        $result = $this->validate($request, 'Posts.replyAdd');
        if ($result !== true) {
            return $crypt->response([
                'code' => 400,
                'message' => $result
            ]);
        }
        $request['parent_id'] = $request['reply_id'];
        $request['reply_user']= $comments->where('id',$request['reply_id'])->value('user_id');
        $data = $comments->allowField(true)->isUpdate(false)->save($request);
        if($data){
//            $posts->where('id',$request['posts_id'])->setInc('comments_num',1);//问题表回答数量+1
            return $crypt->response([
                'code' =>200,
                'message' => "提交成功",
            ]);
        }

    }
    /*
        * 杨思涵 2018-05-08
        * 万淘论坛---7、回复一级跟帖的回复的提交
        * 参数：回复哪个回答的回答ID
        */
    public function replyAgainAdd(RSACrypt $crypt,PostsComments $comments,PostsModel $posts){
        $request = $crypt->request();
        $result = $this->validate($request, 'Posts.replyAdd');
        if ($result !== true) {
            return $crypt->response([
                'code' => 400,
                'message' => $result
            ]);
        }
        $request['parent_id'] = $request['comments_id'];
        $request['reply_user'] = $comments->where('id',$request['comments_id'])->value('user_id');
        $data = $comments->allowField(true)->isUpdate(false)->save($request);
        if($data){
//            $posts->where('id',$request['posts_id'])->setInc('comments_num',1);//问题表回答数量+1
            return $crypt->response([
                'code' =>200,
                'message' => "提交成功",
            ]);
        }

    }

    /*
     * 帖子---8、我要发帖
     */
    public function PostsAdd(RSACrypt $crypt,PostsModel $posts){
        $request = $crypt->request();
        $request['picarr'] = toImgApi($request['picarr']);
        $data = $posts->allowField(true)->isUpdate(false)->save($request);
        if($data){
            return $crypt->response([
                'code' =>200,
                'message' => "成功",
            ]);
        }
    }

    /*
     * 论坛---9、回答点赞表
     */
    public function praise(RSACrypt $crypt,PostsModel $posts){
        $request = $crypt->request();
        Db::name('praise')->insert(['praise_id'=>$request['id'],'user_id'=>$request['user_id'],'type'=>1]);
        $data = $posts->where('id',$request['id'])->setInc('praise',1);
        //帖子点 变热帖
        $webconfig_wenda = Db::name('webconfig')->where('id',14)->value('varvalue');
        //帖子已收藏数量
        $collection3 = Db::name('praise')->where(['praise_id'=>$request['id'],'type'=>1])->count('id');

        if($collection3 >= $webconfig_wenda) {
            $bbc = Db::name('posts')->where(['id'=>$request['id'],'hot_state'=>0])->update(['hot_state'=>1]);
            if($bbc) {
                $user_id = Db::name('posts')->where(['id'=>$request['collection_id']])->value('user_id');
                if($user_id != 0) {
                    $webconfig_jifen = Db::name('webconfig')->where('id',15)->value('varvalue');
                    Db::name('user')->where('id',$user_id)->setInc('integral',$webconfig_jifen);
                }
            }
        }
        return $crypt->response([
            'code' => 200,
            'message' => "成功",
        ]);

    }
    /*
    * 论坛---9、帖子取消点赞表
    */
    public function praiseDel(RSACrypt $crypt,PostsModel $posts){
        $request = $crypt->request();
        Db::name('praise')->where(['praise_id'=>$request['id'],'user_id'=>$request['user_id'],'type'=>1])->delete();
        $data = $posts->where('id',$request['id'])->setDec('praise',1);
        if($data){
            return $crypt->response([
                'code' =>200,
                'message' => "成功",
            ]);
        }
    }
}