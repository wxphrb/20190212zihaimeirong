<?php
namespace app\index\model;
use think\Model;

class SellCar extends Model
{
    // 指定表名,不含前缀
    protected $name = 'sell_car';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    //protected $auto = ['picarr'];

    protected $updateTime = false;
    /**
     * [saveVerify description]模型验证 添加和修改
     * @param  string $id [description]主键id
     * @return [type]     [description]
     */
    public static function saveVerify($data,$id = ''){
        $Model = new SellCar();
        
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


    public function getTypeAttr($value)
    {
        $type = [0 => '未处理', 1=> '已处理'];
        return $type[$value];
    }


}
