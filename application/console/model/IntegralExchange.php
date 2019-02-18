<?php
namespace app\console\model;
use think\Model;

class IntegralExchange extends Base
{
    // 指定表名,不含前缀
    protected $name = 'integral_exchange';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    /**
     * [saveVerify description]模型验证 添加和修改
     * @param  string $id [description]主键id
     * @return [type]     [description]
     */
    public static function saveVerify($data,$id = ''){
        $Model = new IntegralExchange();
        
        //判断是添加还是修改
        $handle = empty($id)? false : true;

        // 调用验证器类进行数据验证
        $result = $Model->validate(true)->allowField(true)->isUpdate($handle)->save($data);
        if(false === $result){
            // 验证失败 输出错误信息
            return $Model->getError();
        }else{
            return true;
        }
    }

    public function getCreateTimeAttr($data,$value)
    {
        return date("Y-m-d H:i:s",$value['create_time']);
    }

    public function getUpdateTimeAttr($data,$value)
    {
        return date("Y-m-d H:i:s",$value['update_time']);
    }

    public function getStatusAttr($value)
    {
        $status = ['0'=> "待发货",'1'=>"待收货",'2'=>"已完成"];
        return $status[$value];
    }
}
