<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/2
 * Time: 17:01
 */
namespace app\api\validate;

use app\api\model\User as User;
use app\api\model\Supply as SupplyModel;
use think\Validate;
use think\Cache;

class Address  extends Validate
{
    protected $rule = [
        'user_id' => 'require',
        'id'=>'require',
        'status'=>'require',
        'username'=>'require',
        'address'=>'require',
        'mobile'=>'require',
        'prov'=>'require',
    ];

    protected $message = [
        'user_id.require' => '请先登录',
        'id.require'=>'缺少产品参数',
        'status.require'=>'地址状态异常'

    ];
    protected $scene = [
        'index' => [
            'page','user_id','status'
        ],
        'update'=>[
            'id','user_id','name','address','prov','phone','status'
        ],
        'del'=>[
            'id','user_id'
        ],
        'add'=>[
            'user_id','name','address','prov','phone','status'
        ],
        'SetUpDefault'=>[
            'id','user_id'
        ],
    ];
}