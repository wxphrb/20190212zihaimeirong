<?php

namespace app\api\validate;

use think\Validate;

use app\api\model\store as StoreModel;

class Store extends Validate{

    protected  $rule = [

        'manned'=>"require",

        'pro' => 'require',

        'title' => 'require',

        'mobile' => 'require|length:11',

    ];



    protected $message = [

        'manned.require'=>"真实姓名不能为空",

        'pro.require'=>"所在地省份不能为空",

        'title.require'=>"店铺名称不能为空",

        'mobile.require'=>"电话号码不能为空",

        'mobile.length'=>"请正确填写电话号码",

    ];



    protected $scene = [

        'add'=>[

            'mobile'=>'require|checkmobile','manned','pro','title',

        ]

    ];



    protected function checkmobile($value, $rules, $data)

    {

        $user_data = StoreModel::where(['mobile' => $data['mobile']])->find();

        return empty($user_data) ? true : '电话号码已存在';

    }

}