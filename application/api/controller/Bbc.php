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

class Bbc extends Controller
{
    /*
     * 论坛---1、问答
     * 参数 ：类别id 关键字
     */
    public function index(RSACrypt $crypt,BbcType $bbcType,BbcQuestion $bbcQuestion,BbcAnswer $answer){
        $request = $crypt->request();
        //论坛问答分类
        $data['bbc_type'] =$bbcType->where(['status'=>1])->field('type_title,id')->order('id desc')->select();
        //问答列表 筛选条件 分类id 关键词
        $where['status']=1;
        if(!empty($request['type_id'])){
            $where['type_id']=$request['type_id'];
        }
        if(!empty($request['keyword'])){
            $where['title']=['like','%'.$request['keyword'].'%'];
        }
        //列表
        $data['list'] = $bbcQuestion->where($where)->field('id,title,answer_num,integral_num,hot_state,create_time')->order('id desc')->paginate(10,false,['page'=>$request['page']]);
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
    * 论坛---2、问答详情
     * 参数：问题的id
    */
    public function info(RSACrypt $crypt,User $user,BbcQuestion $bbcQuestion,BbcAnswer $answer){
        $request = $crypt->request();
        Db::name('news_info')->where(['user_id'=>$request['user_id'],'other_id'=>$request['id'],'read_state'=>0,'type'=>1])->update(['read_state'=>1]);

        //问题详情
        $data=Db::view('bbcQuestion',"id,title,create_time,picarr_type,answer_num,integral_num,hot_state,content,user_id,picarr")
            ->view('user',"username,picurl",'user.id=bbcQuestion.user_id')
            ->where('bbcQuestion.id',$request['id'])->find();
        $data['content'] = strip_tags($data['content']);//去除标签
        $data['picarr'] = !empty(  $data['picarr'])?unserialize($data['picarr']):[];
        //不是我不写递归，是实现不了啊啊啊啊啊
        //问题下的所有一级回复

//        halt($data);
//身份标识
        $data['level_id'] = $user->where('id', $data['user_id'])->value('level_id');
        $data['level_title'] = Db::name('certification_type')->where('id', $data['level_id'])->value('title');

        $data['answer']= Db::view('bbcAnswer','id,user_id,status,question_id,answer_id,parent_id,anonymous,content,reply_num,praise,create_time')
            ->view('user',"username,picurl,level_id",'user.id=bbcAnswer.user_id','left')
            ->view('certificationType',['title'=>'level_title'],'certificationType.id=user.level_id','left')
            ->where(['bbcAnswer.question_id'=>$request['id'],'parent_id'=>0])->order('bbcAnswer.status desc,bbcAnswer.id desc')->select();
//        $data['answer']= Tool::getSubTree($data['answer']);
//        halt($data);

        //一级回复里有没有最佳答案
        $best = Db::name('bbc_answer')->where(['question_id'=>$request['id'],'status'=>1])->find();
        if(!empty($best)){
            $best_status =1;
        }else{
            $best_status =0;
        }
//        halt($data);
        //点没点过赞
        if(!empty($data['answer'])){
        foreach($data['answer'] as &$vv){
            //一级回答点赞详情
            $parise_status = Db::name('praise')->where(['praise_id'=>$vv['id'],'user_id'=>$request['user_id'],'type'=>2])->find();
            if(!empty($parise_status)){
                $vv['parise_status'] = 1;//点过
            }else{
                $vv['parise_status'] = 0;//没点过
            }
            $vv['question_user_id'] = $data['user_id'];
            $vv['best_status'] = $best_status;
        }
        }
        //收没收藏过
        if($request['user_id']!=''){
            $collect = Db::name("collection")
                ->where([
                    'type' => 3,
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
        if(!empty($data['answer'])) {
            // 一级下的无限极回复
            foreach ($data['answer'] as &$v) {
                $v['two'] = Db::view('bbcAnswer', 'id,create_time,question_id,status,answer_id,user_id,content,anonymous,parent_id')
                    ->view('user', "username,picurl,level_id", 'user.id=bbcAnswer.user_id', 'left')
                    ->view('certificationType', ['title' => 'level_title'], 'certificationType.id=user.level_id', 'left')
                    ->where(['bbcAnswer.answer_id' => $v['id'], 'parent_id' => $v['id']])->order('bbcAnswer.status desc,bbcAnswer.id desc')->select();
                $v['two']=!empty($v['two'])?$v['two']:[];
            }

            foreach ($data['answer'] as &$vv) {
                foreach ($vv['two'] as &$vvv) {
                    $vvv['reply'] = Db::view('bbc_answer', 'id,question_id,create_time,content,user_id,answer_id,anonymous,parent_id')
                        ->view('user', 'username,picurl', 'user.id=bbc_answer.user_id', 'left')
                        ->view('user u', ['username' => 'reply_user'], 'u.id=bbc_answer.reply_user', 'left')
                        ->where(['bbc_answer.answer_id' => $vvv['id']])->order([ 'bbc_answer.create_time' => 'asc'])->select();
                    $vvv['reply'] =!empty( $vvv['reply'] )? $vvv['reply'] :[];
                }
            }
        }
        return $crypt->response([
            'code' =>200,
            'message' => "成功",
            'data' =>  $data,
        ]);
    }
      /*
       * 杨思涵 2018-05-08
       * 万淘论坛---3、回答的无限极回复
       * 参数：回答的id
       */
    public function relaply(RSACrypt $crypt,BbcAnswer $answer){
        $request = $crypt->request();

        $data= Db::view('bbcAnswer','id,create_time,question_id,status,answer_id,user_id,content,anonymous,parent_id')
            ->view('user',"username,picurl,level_id",'user.id=bbcAnswer.user_id')
            ->view('certificationType',['title'=>'level_title'],'certificationType.id=user.level_id','left')
            ->where(['bbcAnswer.answer_id'=>$request['id'],'parent_id'=>$request['id']])->order('bbcAnswer.status desc,bbcAnswer.id desc')->select();

        foreach($data as &$v){

        }
        return $crypt->response([
            'code' =>200,
            'message' => "成功",
            'data' =>  $data,
        ]);
    }

    /*
     * 杨思涵 2018-05-08
     * 万淘论坛---4、回答问题的页面
     * 参数：问题ID
     */
    public function answer(RSACrypt $crypt,BbcQuestion $bbcQuestion){
        $request = $crypt->request();
        $data = Db::view('bbc_question','title,create_time,content,answer_num,integral_num')->view('user','username,picurl','user.id=bbc_question.user_id')
            ->where('bbc_question.id',$request['id'])->find();
        if($data){
            return $crypt->response([
                'code' =>200,
                'message' => "提交成功",
                'data'=>$data
            ]);
        }
    }
     /*
      * 杨思涵 2018-05-08
      * 万淘论坛---5、回答提交
      * 参数：回复的哪个问题的问题ID
      */
    public function answerAdd(RSACrypt $crypt,BbcQuestion $bbcQuestion,BbcAnswer $answer,User $user){
        $request = $crypt->request();
        $result = $this->validate($request, 'Bbc.answerAdd');
        if ($result !== true) {
            return $crypt->response([
                'code' => 400,
                'message' => $result
            ]);
        }
        $data = $answer->allowField(true)->isUpdate(false)->save($request);//回答
        if($data){
            $bbcQuestion->where('id',$request['question_id'])->setInc('answer_num',1);//问题表回答数量+1
            //变成热点
            //问答配置收藏数量
            $webconfig_wenda = Db::name('webconfig')->where('id',19)->value('varvalue');
            //已回答数量
            $bbc_answer_num = Db::name('bbc_answer')->where('question_id',$request['question_id'])->count('id');
            if($webconfig_wenda >= $bbc_answer_num) {
                $bbc_question = Db::name('bbc_question')->where(['id'=>$request['question_id'],'hot_state'=>0])->update(['hot_state'=>1]);
                if($bbc_question) {
                    $user_id = Db::name('bbc_question')->where(['id'=>$request['question_id']])->value('user_id');
                    $webconfig_jifen = Db::name('webconfig')->where('id',23)->value('varvalue');
                    Db::name('user')->where('id',$user_id)->setInc('integral',$webconfig_jifen);
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
     * 万淘论坛---6、回复回答的提交
     * 参数：回复哪个回答的回答ID
     */
    public function replyAdd(RSACrypt $crypt,BbcAnswer $answer,BbcQuestion $bbcQuestion){
        $request = $crypt->request();
        $result = $this->validate($request, 'Bbc.replyAdd');
        if ($result !== true) {
            return $crypt->response([
                'code' => 400,
                'message' => $result
            ]);
        }
        $request['parent_id'] = $request['answer_id'];
        $data = $answer->allowField(true)->isUpdate(false)->save($request);
        if($data){
            $answer->where('id',$request['answer_id'])->setInc('reply_num',1);//问题表回答数量+1
//            $bbcQuestion->where('id',$request['question_id'])->setInc('answer_num',1);//问题表回答数量+1
            return $crypt->response([
                'code' =>200,
                'message' => "提交成功",
            ]);
        }

    }
    /*
        * 杨思涵 2018-05-08
        * 万淘论坛---7、回复回答下的回复
        * 参数：回复哪个回答的回答ID
        */
    public function replyAgainAdd(RSACrypt $crypt,BbcAnswer $answer,BbcQuestion $bbcQuestion){
        $request = $crypt->request();
        $result = $this->validate($request, 'Bbc.replyAdd');
        if ($result !== true) {
            return $crypt->response([
                'code' => 400,
                'message' => $result
            ]);
        }
        $request['parent_id'] = $request['reply_id'];
        $request['reply_user'] = $answer->where('id',$request['reply_id'])->value('user_id');
        $data = $answer->allowField(true)->isUpdate(false)->save($request);
        if($data){
            $answer->where('id',$request['answer_id'])->setInc('reply_num',1);//问题表回答数量+1
//            $bbcQuestion->where('id',$request['question_id'])->setInc('answer_num',1);//问题表回答数量+1
            return $crypt->response([
                'code' =>200,
                'message' => "提交成功",
            ]);
        }

    }
    /*
     * 论坛---8、论坛分类列表
     */
    public function bbcType(RSACrypt $crypt,BbcType $bbcType){
        $data = $bbcType->where('status',1)->field('id,type_title')->select();
        return $crypt->response([
            'code' =>200,
            'message' => "成功",
            'data'=>$data
        ]);
    }

    /*
     * 论坛---8、我要提问
     */
    public function questionAdd(RSACrypt $crypt,BbcQuestion $bbcQuestion,User $user){
        $request = $crypt->request();
        $user_integral= $user->where('id',$request['user_id'])->value('integral');
        if($user_integral<$request['integral_num']){
            return $crypt->response([
                'code' =>400,
                'message' => "您的积分不足",
            ]);
        }
        $request['picarr']=toImgApi($request['picarr']);
        $data = $bbcQuestion->allowField(true)->isUpdate(false)->save($request);
        if($data){
            $user->where('id',$request['user_id'])->setDec('integral',$request['integral_num']);
            return $crypt->response([
                'code' =>200,
                'message' => "成功",
            ]);
        }
    }

    /*
     * 论坛---9、回答点赞表
     */
    public function praise(RSACrypt $crypt,BbcAnswer $answer){
        $request = $crypt->request();
        Db::name('praise')->insert(['praise_id'=>$request['answer_id'],'user_id'=>$request['user_id'],'type'=>2]);
        $data = $answer->where('id',$request['answer_id'])->setInc('praise',1);
        if($data){
            return $crypt->response([
                'code' =>200,
                'message' => "成功",
            ]);
        }
    }
    /*
    * 论坛---9、回答取消点赞表
    */
    public function praiseDel(RSACrypt $crypt,BbcAnswer $answer){
        $request = $crypt->request();
        Db::name('praise')->where(['praise_id'=>$request['answer_id'],'user_id'=>$request['user_id'],'type'=>2])->delete();
        $data = $answer->where('id',$request['answer_id'])->setDec('praise',1);
        if($data){
            return $crypt->response([
                'code' =>200,
                'message' => "成功",
            ]);
        }
    }


    public function best( RSACrypt $crypt,BbcAnswer $answer){
        $request = $crypt->request();
        $question =    Db::name('bbc_answer')->where(['id'=>$request['answer_id']])->value('question_id');
        $question_user_id =    Db::name('bbc_answer')->where(['id'=>$request['answer_id']])->value('user_id');
        $data =  Db::name('bbc_answer')->where(['question_id'=>$question,'status'=>1])->find();
        if(empty($data)){
            Db::name('bbc_answer')->where(['id'=>$request['answer_id']])->update(['status'=>1]);
            //问题赏金
            $bbc_question = Db::name('bbc_question')->where('id',$question)->value('integral_num');
            $user_int = Db::name('user')->where('id',$question_user_id)->setInc('integral',$bbc_question);
            if($user_int) {
                //添加记录
                $da = [
                    'user_id'=>$question_user_id,
                    'type'=>2,
                    'num'=>$bbc_question,
                    'create_time'=>time(),
                ];
                Db::name('integral_info')->insert($da);
            }
            return $crypt->response([
                'code' =>200,
                'message' => "成功",
            ]);
        }else{
            return $crypt->response([
                'code' =>400,
                'message' => "已有最佳答案",
            ]);
        }


    }
}