<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/2
 * Time: 17:01
 */
namespace app\api\validate;

use app\api\model\User as User;
use app\api\model\Posts as PostsModel;
use app\api\model\PostsComments as PostsComments;
use think\Validate;
use think\Cache;

class Posts  extends Validate
{
    protected $rule = [
        'posts_id' => 'require',
        'user_id' => 'require',
        'content' => 'require',
    ];

    protected $message = [
        'posts_id.require' => '帖子参数异常',
        'user_id.require' => '用户参数异常',
        'content.require' => '跟帖内容异常',
        'comments_id.require'=>'回答参数异常'
    ];


    // 检测问题存不存在
    protected function checkId($value, $rule, $data)
    {
        $BbcQuestion_model_data = PostsModel::where(['id' => $data['posts_id']])->find();

        return (!empty($BbcQuestion_model_data)) ? true : '帖子不存在！';

    }
    // 检测问题的回答存不存在
    protected function checkComments($value, $rule, $data)
    {
        $BbcQuestion_model_data = PostsComments::where(['id' => $data['reply_id']])->find();

        return (!empty($BbcQuestion_model_data)) ? true : '跟帖不存在！';

    }
    //用户存不存在
    protected function checkUser($value, $rule, $data)
    {
        $user_model_data = User::where(['id' => $data['user_id'],'status'=>1])->find();

        return (!empty($user_model_data)) ? true : '用户身份异常！';

    }

    protected $scene = [

        'commentsAdd' => [
            'user_id' => 'require|checkUser',
            'posts_id'=> 'require|checkId',
            'content'=>'require',
        ],

        'replyAdd'=>[
            'user_id' => 'require|checkUser',
            'posts_id'=> 'require|checkId',
            'content'=>'require',
            'reply_id'=>'require|checkComments'
        ],
    ];
}