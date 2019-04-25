<?php
namespace app\shop\model;
use think\Model;

class StoreProject extends Base
{
    // 指定表名,不含前缀
    protected $name = 'store_project';

    /**
     * [saveVerify description]模型验证 添加和修改
     * @param  string $id [description]主键id
     * @return [type]     [description]
     */
    public static function saveVerify($data,$id = ''){
        $Model = new StoreProject();
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

    public function getTypeAttr($value,$data){

        if($value==0){
            $val = '普通项目';
        }else{
            $val = '优惠项目';
        }
        return $val;
    }
    public function getPriceAttr($value,$data){

        if($data['type']==0){
            $val = $value;
        }else{
            $val = $value."(原价：".$data['old_price'].")";
        }
        return $val;
    }
}
