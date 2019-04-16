<?php
namespace app\shop\model;
use think\Model;

class StoreMember extends Base
{
    // 指定表名,不含前缀
    protected $name = 'store_member';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    /**
     * [saveVerify description]模型验证 添加和修改
     * @param  string $id [description]主键id
     * @return [type]     [description]
     */
    public static function saveVerify($data,$id = ''){
        $Model = new StoreMember();
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
    public function getCreateTimeAttr($data,$value){
        return date('Y-m-d H:i:s',$value['create_time']);
    }
    public function getUpdateTimeAttr($data,$value){
        return date('Y-m-d H:i:s',$value['create_time']);
    }
    public function getInvitedShopIdAttr($data,$value){
        $Model = new StoreMember();
        $username = $Model->where('id',$value['invited_shop_id'])->value('username');
        $username = empty($username) ? '无' : $username;
        return $username."_+_".$data;
    }
}
