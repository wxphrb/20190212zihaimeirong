<?php
namespace app\console\model;
use think\Model;

class GoodsType extends Base
{
    // 指定表名,不含前缀
    protected $name = 'goods_type';

    
    protected function getAttrsAttr($value)
    {
        $data = empty($value)?[]:unserialize($value);
        return $data; 
    }

    /**
     * [saveVerify description]模型验证 添加和修改
     * @param  string $id [description]主键id
     * @return [type]     [description]
     */
    public static function saveVerify($data,$id = ''){
        $Model = new GoodsType();

        $data['attrs'] = '';
        if(isset($data['attrs_group'])){
            $list = $data['attrs_group'];
            $attr_num = count($list);
            if($attr_num != 0){
                $titles=array();
                for($i=0;$i<$attr_num;$i++){
                    if(isset($data['attrs'.$i])){
                        $title['title'] = $data['attrs_group'][$i];
                        $title['attrs'] = $data['attrs'.$i]; 
                        $titles[]=$title;
                    }
                }
                $data['attrs'] = empty($titles)?'':serialize($titles);
            }
        }
        
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
}
