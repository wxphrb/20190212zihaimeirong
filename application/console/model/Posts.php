<?php
namespace app\console\model;
use think\Model;

class Posts extends Model
{
    // 指定表名,不含前缀
    protected $name = 'posts';

    protected $auto = ['picarr'];

    protected function setPicarrAttr($value)
    {
        $picarr = input('picarr/a');
        if (!empty($picarr) && is_array($picarr)) {

            $arr = array();
            foreach ($picarr as $key) {
                $row['img'] = $key;
                $arr[] = $row;
            }
            return serialize($arr);
        } else {
            return '';
        }

    }

    protected function getPicarrAttr($value)
    {
        $data = empty($value) ? [] : unserialize($value);
        return $data;
    }
    /**
     * [saveVerify description]模型验证 添加和修改
     * @param  string $id [description]主键id
     * @return [type]     [description]
     */
    public static function saveVerify($data,$id = ''){
        $Model = new Posts();
        
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

    public function getCreateTimeAttr($value)
    {
        return date("Y-m-d H:i:s",$value);
    }
}
