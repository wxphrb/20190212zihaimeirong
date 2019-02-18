<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/2
 * Time: 17:01
 */
namespace app\api\validate;

use app\api\model\User as User;
use app\api\model\IntegralGoods as IntegralGoodsModel;
use think\Validate;
use think\Cache;

class Integralgoods  extends Validate
{
    protected $rule = [
        'id'=>'require',
        'goods_id'=>'require',
        'user_id' => 'require',
        'title' => 'require',
        'content' => 'require',
        'address' => 'require',
        'state'=>'require'
    ];

    protected $message = [
        'id.require'=>'商品参数异常',
        'goods_id.require'=>'商品参数异常',
        'title.require' => '标题参数异常',
        'user_id.require' => '用户参数异常',
        'content.require' => '发布内容异常',
        'address.require'=>'定位参数异常',
        'state.require'=>'供求类别参数异常'

    ];
    // 检测问题存不存在
    protected function checkId($value, $rule, $data)
    {
        $Integralgoods_model_data = IntegralGoodsModel::where(['id' => $data['id']])->find();

        return (!empty($Integralgoods_model_data)) ? true : '商品不存在！';

    }
    // 检测问题存不存在
    protected function checkGoodsId($value, $rule, $data)
    {
        $Integralgoods_model_data = IntegralGoodsModel::where(['id' => $data['goods_id']])->find();

        return (!empty($Integralgoods_model_data)) ? true : '商品不存在！';

    }
    //用户存不存在
    protected function checkUser($value, $rule, $data)
    {
        $user_model_data = User::where(['id' => $data['user_id'],'status'=>1])->find();

        return (!empty($user_model_data)) ? true : '用户身份异常！';

    }

    protected $scene = [
        'info' => [
            'id' => 'require|checkId',
        ],
        'exchange' => [
            'goods_id' => 'require|checkGoodsId',
            'user_id' => 'require|checkUser',
        ],
    ];
}