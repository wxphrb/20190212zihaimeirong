<?php

/**

 * Created by PhpStorm.

 * User: Administrator

 * Date: 2018/5/22

 * Time: 16:10

 */



namespace app\api\controller;





use app\api\model\BbcAnswer;

use app\api\model\BbcQuestion;

use app\api\model\Certification;

use app\api\model\Feedback;

use app\api\model\Info;

use app\api\model\Integral;

use app\api\model\IntegralExchange;

use app\api\model\IntegralInfo;

use app\api\model\Job;

use app\api\model\User;

use app\api\model\Store;

use \app\api\model\Supply;

use \app\api\model\Posts;

use app\api\model\Collection;

use mrmiao\encryption\RSACrypt;

use think\Cache;

use think\Config;

use think\Controller;

use think\Db;

use think\Request;

use think\Session;





class My extends Controller

{

    /*--个人资料--*/

    public function index(RSACrypt $crypt, User $user)

    {

        try {

            $request = $crypt->request();

            $data = $user->where("id", $request['user_id'])->field("id,picurl,username,account,icode")->find();

            if ($data) {

                return $crypt->response(['code' => 200, 'message' => "成功", 'data' => $data]);

            }

            return $crypt->response(['code' => 400, 'message' => '数据返回失败！']);

        } catch (\Exception $e) {

            return $crypt->response(['code' => 400, 'message' => '数据错误等待处理！']);

        }



    }





    /*--修改头像--*/

    public function save_pic(RSACrypt $crypt, User $user)

    {

        try {

            $request = $crypt->request();

            $picurl = Request::instance()->file('picurl');

            if (empty($picurl)) return $crypt->response(['code' => 400, 'message' => "请求失败"]);

            if ($picurl) {

                $info = $picurl->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . "avatar");
                $date = date('Ymd');
                $Arr['picurl'] = 'http://'.$_SERVER['SERVER_NAME']."/uploads/avatar/"  .$date.'/'.$info->getFilename();

                if ($info) {

                    $Arr['id'] = $request['user_id'];

                    $result = $user->allowField(true)->isUpdate(true)->save($Arr);

                    if ($result) {

                        return $crypt->response(['code' => 200, 'message' => "修改成功"]);

                    } else {

                        return $crypt->response(['code' => 400, 'message' => "修改失败"]);

                    }

                } else {

                    return $crypt->response(['code' => 400, "message" => $picurl->getError()]);

                }

            }

            return $crypt->response(['code' => 400, 'message' => '数据返回失败！']);

        } catch (\Exception $e) {

            return $crypt->response(['code' => 400, 'message' => '数据错误等待处理！']);

        }

    }



    /*--修改用户名*/



    public function save_username(RSACrypt $crypt, User $user)

    {

        try {

            $request = $crypt->request();

            if (empty($request['username'])) return $crypt->response(['code' => 400, 'message' => "请求失败"]);

            $arr['id'] = $request['user_id'];

            $arr['username'] = $request['username'];

            $result = $user->allowField(true)->isUpdate(true)->save($arr);

            if ($result) {

                return $crypt->response(['code' => 200, 'message' => "成功"]);

            }

            return $crypt->response(['code' => 400, 'message' => "失败"]);

        } catch (\Exception $e) {

            return $crypt->response(['code' => 400, 'message' => '数据错误等待处理！']);

        }

    }



    /*--推出登陆--*/

    public function logout(RSACrypt $crypt)

    {

        Session::clear();

        return $crypt->response(['code' => 200, 'message' => "成功"]);



    }





    /*--修改密码*/

    public function save_pwd(RSACrypt $crypt, User $user)

    {

        try {

            $request = $crypt->request();

            $result = $this->validate($request, 'User.save');  //验证参数

            if ($result !== true) {

                return $crypt->response(['code' => 400, 'message' => $result]);

            }

            $arr['password'] = md5(md5($request['newpassword']));

            $arr['id'] = $request['user_id'];

            $result = $user->allowField(true)->isUpdate(true)->save($arr);

            if ($result) {

                return $crypt->response(['code' => 200, 'message' => "成功"]);

            }

            return $crypt->response(['code' => 400, 'message' => "失败"]);

        } catch (\Exception $e) {

            return $crypt->response(['code' => 400, 'message' => '数据错误等待处理！']);

        }

    }





    /*关于我们*/



    public function about(Info $web)



    {



        $content = $web->where("id", 2)->value("content");



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





    /*--我要开店*/



    public function openshop(RSACrypt $crypt, Store $store)
    {
        try {
            $request = $crypt->request();
            $code = Cache::get("smsupshop_code" . $request['mobile']);
            if ($request['code'] != $code) {
                return $crypt->response(['code' => 400, 'message' => "验证码有误"]);
            }
            $data = $store->where('mobile',$request['mobile'])->find();
            if(!empty($data)){
                return $crypt->response(['code' => 400, 'message' => '手机号码请勿重复使用！']);
            }
            $request['password'] = md5(md5(123456));
            $result = $store->allowField(true)->isUpdate(false)->save($request);
            if ($result) {
                return $crypt->response(['code' => 200, 'message' => "成功"]);
            }
            return $crypt->response(['code' => 400, 'message' => "失败"]);
        } catch (\Exception $e) {
            return $crypt->response(['code' => 400, 'message' => '数据错误等待处理！']);
        }
    }



    /*--我的积分*/

    public function integral(RSACrypt $crypt, Integral $integral, User $user,IntegralInfo $info)

    {

        try {

            $request = $crypt->request();

            $list['integral'] = $user
                ->where("id", $request['user_id'])
                ->value("integral");

            $list['integral'] = $list['integral'] ? $list['integral'] : "";

            $list['integral_info'] = Db::view('integral_info i')
                ->view('integral_type it','title as type_title','i.type=it.id')
                ->where("user_id", $request['user_id'])
                ->paginate(20);

            return $crypt->response(['code' => 200, 'message' => "成功", 'data' => $list]);

        } catch (\Exception $e) {

            return $crypt->response(['code' => 400, 'message' => '数据错误等待处理！']);

        }

    }



    /*--兑换记录*/



    public function integral_exchange(RSACrypt $crypt, IntegralExchange $exchange)

    {

        try {

            $request = $crypt->request();

            $where = ['user_id' => $request['user_id']];

            if ($request['status'] == 2) {

                $where = ['status' => 2];

            }

            $list = $exchange

                ->where($where)

                ->order("create_time desc")

                ->field("create_time,goods_title,num,status")

                ->paginate(20);

            return $crypt->response(['code' => 200, 'message' => "成功", 'data' => $list]);

        } catch (\Exception $e) {

            return $crypt->response(['code' => 400, 'message' => '数据错误等待处理！']);

        }

    }



    /*--认证类型*/

    public function approve_type(RSACrypt $crypt)

    {

        try {

            return $crypt->response(['code' => 200, 'message' => "成功", 'data' => Db::name("certification_type")->where('id','<>',0)->where('status',0)->select()]);

        } catch (\Exception $e) {

            return $crypt->response(['code' => 400, 'message' => '数据错误等待处理！']);

        }

    }





    /*--认证数据*/

    public function authen(RSACrypt $crypt, Certification $certification)

    {

        try {

            $request = $crypt->request();
            Db::name('news_info')->where(['user_id'=>$request['user_id'],'read_state'=>0,'type'=>6])->update(['read_state'=>1]);

            $data = $certification
                ->where("user_id", $request['user_id'])
                ->field("id,user_id,certification_type,truename,idcard,picarr,picurl,status")
                ->find();
          if(!empty($data)){
              $data['picarr'] = unserialize($data['picarr']);
              if(!$data['picarr']){
                  $data['picarr']=[];
              }
              $data['certification_title'] = Db::name('certification_type')->where('id',$data['certification_type'])->value('title');

          }else{
              $data=[];
          }
            return $crypt->response(['code' => 200, 'message' => "成功", 'data' => $data]);

        } catch (\Exception $e) {

            return $crypt->response(['code' => 400, 'message' => '数据错误等待处理！']);

        }

    }



    /*--认证更改或重新认证*/



    public function approve_save(RSACrypt $crypt, Certification $certification)

    {

//        Db::startTrans();
//
//        try {

            $request = $crypt->request();

//            $card = Request::instance()->file();//手持身份证
        $picarr = json_decode($request['card'],true);

        if (!empty($picarr)) {
            $arr = [];
            $i=0;
            foreach ($picarr as $file) {
                // 移动到框架应用根目录/public/uploads/ 目录下
                if($i<=2){
                        $arr[]['img'] =  $file;
                }elseif($i=3){
                        $data['picurl'] = $file;
                }
                $i++;
            }
            $data['picarr'] = serialize($arr);
        } else {
            $data['picarr'] = '';
        }

            $data['user_id'] = $request['user_id'];
            $data['certification_type'] = $request['type'];
            $data['truename'] = $request['truename'];
            $data['idcard'] = $request['idcard'];
            $data['create_time'] = time();
            $data['status'] = 1;

            $handle = empty($request['id']) ? false : true;
            if($handle){
                $data['id']=$request['id'];
            }
            $arr = $certification->allowField(true)->isUpdate($handle)->save($data);
            if ($arr) {
                Db::commit();
                return $crypt->response(['code' => 200, 'message' => "成功"]);
            }
            return $crypt->response(['code' => 400, 'message' => "失败"]);
//        } catch (\Exception $e) {
//
//            Db::rollback();
//
//            return $crypt->response(['code' => 400, 'message' => '数据错误等待处理！']);
//
//        }

    }





    /*--我的*/

    public function my(RSACrypt $crypt, User $user)

    {

//        try {

        $request = $crypt->request();

        $data = Db::view('User', 'id,username,account,icode,integral,level_id,picurl')
                ->view('certificationType',['title'=>'level_title'],'certificationType.id=User.level_id','left')
                ->where("id", $request['user_id'])
                ->find();
        $data['certification_status'] = Db::name('certification')->where('user_id',$request['user_id'])->value('status');
        $data['certification_status']=empty($data['certification_status'])?0:$data['certification_status'];
        $data['level_title'] = $data['level_title']? $data['level_title']:'';
        $data['num'] = Db::name('news_info')->where(['user_id'=>$request['user_id'],'read_state'=>0])->count('id');

        return $crypt->response(['code' => 200, 'message' => "成功", 'data' => $data]);

//        } catch (\Exception $e) {
//
//            return $crypt->response(['code' => 400, 'message' => '数据错误等待处理！']);
//
//        }

    }





    /*我的论坛（提问）*/

    public function my_question(RSACrypt $crypt, BbcQuestion $bbcQuestion,BbcAnswer $answer)

    {

        try {

            $request = $crypt->request();

            $where['status'] = 1;

            $where["user_id"] = $request['user_id'];
            //列表
            $data= $bbcQuestion->where($where)->field('id,title,answer_num,integral_num,hot_state,create_time')->paginate(10,false,['page'=>$request['page']]);
            //回答
            foreach($data as $v){
                $best_answer = $answer->where(['question_id'=>$v['id'],'status'=>1])->value('content');//问题最佳答案
                if(empty($best_answer)){//如果没有，就取最新的答案
                    $mew_answer = $answer->where(['question_id'=>$v['id']])->order('id desc')->value('content');
                    $v['answer'] = empty($mew_answer)?'':$mew_answer;
                }else{
                    $v['answer']=$best_answer;
                }
            }
            return $crypt->response(['code' => 200, 'message' => "成功", 'data' => $data]);

        } catch (\Exception $e) {

            return $crypt->response(['code' => 400, 'message' => '数据错误等待处理！']);

        }

    }



    public function del_question(RSACrypt $crypt, BbcQuestion $bbcQuestion)

    {

        $request = $crypt->request();

        if (empty($request['id'])) {

            return $crypt->response(['code' => 400, 'message' => "参数有误"]);

        }

        $result = $bbcQuestion->where(['id' => $request['id'], 'user_id' => $request['user_id']])->delete();

        if ($result) {
            Db::name('news_info')->where(['other_id'=>$request['id'],'user_id'=>$request['user_id'],'type'=>1])->delete();

            return $crypt->response(['code' => 200, 'message' => "删除成功"]);

        } else {

            return $crypt->response(['code' => 400, 'message' => "删除失败"]);

        }

    }





    public function my_answer(RSACrypt $crypt, BbcAnswer $answer, BbcQuestion $bbcQuestion)

    {

//        try {

            $request = $crypt->request();

            $where["user_id"] = $request['user_id'];

            $question_id = Db::name('bbc_answer')->where($where)->field("question_id")->group('question_id')->select();//查出所有我回答的问题的id
            $question_id = array_column($question_id, 'question_id');//归档
            if(!empty($question_id)){
                //查询所有符合规则的问题
                $where = 'id in ('.implode(',',$question_id ).')';
                $data=$bbcQuestion->where(['status' => 1])->where($where)->field("id,title,answer_num,integral_num,hot_state,create_time")->paginate(10,false,['page'=>$request['page']]);;
                foreach($data as $v){
                    $v['answer']  = $answer->where(['question_id'=>$v['id'],'user_id'=>$request['user_id']])->value('content');
                }
            }else{
                $data=[];
            }


            return $crypt->response(['code' => 200, 'message' => "成功", 'data' => $data]);

//        } catch (\Exception $e) {
//
//            return $crypt->response(['code' => 400, 'message' => '数据错误等待处理！']);
//
//        }

    }



//    /*--我的提问详情*/

//    public function question_detail(RSACrypt $crypt ,BbcQuestion $bbcQuestion)

//    {

//        $request = $crypt->request();

//        //问题详情

//        $data=Db::view('bbcQuestion',"id,title,create_time,picarr_type,answer_num,integral_num,content,picarr")

//            ->view('user',"username,picurl",'user.id=bbcQuestion.user_id')

//            ->where('bbcQuestion.id',$request['id'])->find();

//        $data['picarr'] = unserialize($data['picarr']);

//        //问题下的所有一级回复

//        $data['answer']= Db::view('bbcAnswer','id,status,anonymous,content,reply_num,praise,create_time')

//            ->view('user',"username,picurl,level_id",'user.id=bbcAnswer.user_id')

//            ->view('certificationType',['title'=>'level_title'],'certificationType.id=user.level_id')

//            ->where(['bbcAnswer.question_id'=>$request['id'],'parent_id'=>'0'])->order('bbcAnswer.status desc,bbcAnswer.id desc')->paginate(10,false,['page'=>$request['page']]);

//        return $crypt->response([

//            'code' =>200,

//            'message' => "成功",

//            'data' =>  $data,

//        ]);

//    }





    /*我的帖子*/

    public function my_posts(RSACrypt $crypt, Posts $posts)

    {

        try {

            $request = $crypt->request();

            $where['status'] = 1;

            $where["user_id"] = $request['user_id'];

            $data = $posts->where($where)->field('id,title,picarr,praise,picarr_type,hot_state,comments_num,share_num')->paginate(10, false, ['page' => $request['page']]);

            return $crypt->response(['code' => 200, 'message' => "成功", 'data' => $data]);

        } catch (\Exception $e) {

            return $crypt->response(['code' => 400, 'message' => '数据错误等待处理！']);

        }

    }



    public function del_posts(RSACrypt $crypt, Posts $posts)

    {

        $request = $crypt->request();

        if (empty($request['id'])) {

            return $crypt->response(['code' => 400, 'message' => "参数有误"]);

        }

        $result = $posts->where(['id' => $request['id'], 'user_id' => $request['user_id']])->delete();

        if ($result) {
            Db::name('news_info')->where(['other_id'=>$request['id'],'user_id'=>$request['user_id'],'type'=>2])->delete();

            return $crypt->response(['code' => 200, 'message' => "删除成功"]);

        } else {

            return $crypt->response(['code' => 400, 'message' => "删除失败"]);

        }

    }





    /*--我的供求*/



    public function my_supply(RSACrypt $crypt, Supply $supply, User $user)
    {
        try {
            $request = $crypt->request();
            $where['status'] = 1;
            $where['state'] = 2;
            $where['user_id'] = $request['user_id'];
            $data = $supply->where($where)->field('id,title,address,state,user_id,create_time')->paginate(10, false, ['page' => $request['page']]);
            foreach ($data as &$v) {
                if ($v['user_id'] != 0) {
                    $v['username'] = $user->where('id', $v['user_id'])->value('username');
                    $v['picurl'] = $user->where('id', $v['user_id'])->value('picurl');
                } else {
                    $v['username'] = '后台发布';
                    $v['picurl'] = '';
                }
            }

            return $crypt->response(['code' => 200, 'message' => "成功", 'data' => $data]);

        } catch (\Exception $e) {

            return $crypt->response(['code' => 400, 'message' => '数据错误等待处理！']);

        }

    }





    /*删除我的供求*/

    public function del_supply(RSACrypt $crypt, Supply $supply)

    {

        $request = $crypt->request();

        if (empty($request['id'])) {

            return $crypt->response(['code' => 400, 'message' => "参数有误"]);

        }

        $result = $supply->where(['id' => $request['id'], 'user_id' => $request['user_id']])->delete();

        if ($result) {
            Db::name('news_info')->where(['other_id'=>$request['id'],'user_id'=>$request['user_id'],'type'=>3])->delete();

            return $crypt->response(['code' => 200, 'message' => "删除成功"]);

        } else {

            return $crypt->response(['code' => 400, 'message' => "删除失败"]);

        }

    }



    /*客服中心*/

    public function faq(RSACrypt $crypt)
    {
        $data['tel'] = Db::name("webconfig")->where("varname", 'web_hotline')->field("varvalue")->select();


        $data['faq'] = Db::name("faq")->where("status", 1)->field("id,title")->select();

        $data['phone'] = Db::name("webconfig")->where("id", 7)->value('varvalue');

        return $crypt->response(['code' => 2100, 'message' => "成功", 'data' => $data]);



    }



    /*常见问题详情*/

    public function faq_detail(RSACrypt $crypt)

    {

        $request = $crypt->request();





        $content = Db::name("faq")->where("id", $request['id'])->value("content");



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





    /*意见反馈*/

    public function feedback(RSACrypt $crypt, Feedback $feedback)
    {
        $request = $crypt->request();
        $files = Request::instance()->file();
        if ($request['number'] > 0) {
            $request['picarr'] = toImgApi($request['picarr']);
        }
        $result = $feedback->allowField(true)->isUpdate(false)->save($request);
        if ($result) {
            return $crypt->response(['code' => 200, 'message' => "反馈成功"]);
        } else {
            return $crypt->response(['code' => 400, 'message' => "反馈失败"]);
        }
    }



    public function my_join(RSACrypt $crypt, Job $job)

    {

        $request = $crypt->request();

        if (empty($request['user_id']) || empty($request['type_id'])) {

            return $crypt->response(['code' => 400, 'message' => "参数有误"]);

        }
        $result = Db::view('job')
            ->view('user','picurl,username','user.id=job.user_id')
            ->where(["user_id" => $request['user_id'], 'type_id' => $request['type_id'],'job.status'=>1])
            ->paginate(10, false, ['page' => $request['page']])
            ->toArray();

        foreach($result['data'] as &$v){
            $certification = Db::name('certification')->where(['user_id'=>$v['user_id']])->value('certification_type');
            if(empty($certification)) {
                $v['level'] = 0;
                $v['level_title'] = "普通";
            } else {
                $v['level'] = $certification;
                $v['level_title'] = Db::name('certification_type')->where('id',$certification)->value('title');
            }
        }

        return $crypt->response(['code' => 200, 'message' => "成功", 'data' => $result]);

    }



    public function del_join(RSACrypt $crypt, Job $job)
    {
        $request = $crypt->request();
        if (empty($request['id'])) {
            return $crypt->response(['code' => 400, 'message' => "参数有误"]);
        }
        $result = $job->where(['id' => $request['id'], 'user_id' => $request['user_id']])->delete();
        if ($result) {
            Db::name('news_info')->where(['other_id'=>$request['id'],'user_id'=>$request['user_id'],'type'=>5])->delete();
            return $crypt->response(['code' => 200, 'message' => "删除成功"]);
        } else {
            return $crypt->response(['code' => 400, 'message' => "删除失败"]);
        }
    }


    public function favorite(RSACrypt $crypt){
        $request = $crypt->request();
        Db::name('favorite')->where(['user_id'=>$request['user_id']])->delete();
        $result= Db::name('favorite')->insert(['user_id'=>$request['user_id'],'typeid'=>$request['typeid']]);
        if ($result) {
            return $crypt->response(['code' => 200, 'message' => "成功"]);
        } else {
            return $crypt->response(['code' => 400, 'message' => "失败"]);
        }
    }
}

