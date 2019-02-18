<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/2
 * Time: 17:01
 */
namespace app\api\validate;

use app\api\model\User as User;
use app\api\model\BbcQuestion as BbcQuestion;
use app\api\model\BbcAnswer as BbcAnswer;
use think\Validate;
use think\Cache;

class Bbc  extends Validate
{
    protected $rule = [
        'question_id' => 'require',
        'user_id' => 'require',
        'content' => 'require',
        'anonymous' => 'require',
    ];

    protected $message = [
        'question_id.require' => '问题参数异常',
        'user_id.require' => '用户参数异常',
        'content.require' => '回答内容异常',
        'anonymous.require' => '匿名状态异常',
        'answer_id.require'=>'回答参数异常'
    ];


    // 检测问题存不存在
    protected function checkId($value, $rule, $data)
    {
        $BbcQuestion_model_data = BbcQuestion::where(['id' => $data['question_id']])->find();

        return (!empty($BbcQuestion_model_data)) ? true : '问题不存在！';

    }
    // 检测问题的回答存不存在
    protected function checkAnswer($value, $rule, $data)
    {
        $BbcQuestion_model_data = BbcAnswer::where(['id' => $data['answer_id']])->find();

        return (!empty($BbcQuestion_model_data)) ? true : '回答不存在！';

    }
    //用户存不存在
    protected function checkUser($value, $rule, $data)
    {
        $user_model_data = User::where(['id' => $data['user_id'],'status'=>1])->find();

        return (!empty($user_model_data)) ? true : '用户身份异常！';

    }

    protected $scene = [

        'answerAdd' => [
            'user_id' => 'require|checkUser',
            'question_id'=> 'require|checkId',
            'content'=>'require',
            'anonymous'=>'require',
        ],
        'replyAdd'=>[
            'user_id' => 'require|checkUser',
            'question_id'=> 'require|checkId',
            'content'=>'require',
            'answer_id'=>'require|checkAnswer'
        ],
    ];
}