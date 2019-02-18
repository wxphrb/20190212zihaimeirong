<?php
namespace app\console\model;
use think\Model;

class Message extends Model
{
    // 指定表名,不含前缀
    protected $name = 'feedback';
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
        $Model = new Message();
        
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

    protected function setPicarrAttr($value)
    {
        /*
        $picarr = input('picarr/a');
        if (!empty($picarr) && is_array($picarr)) {
            $info = input('info/a');
            $show = input('show/a');

            $arr = array();
            foreach ($picarr as $key => $v) {
                $row['img'] = $v;
                $row['info'] = $info[$key];
                $row['show'] = $show[$key];
                $arr[] = $row;
            }
            return serialize($arr);
        } else {
            return '';
        }*/

    }

    protected function getPicarrAttr($value)
    {
        $data = empty($value) ? [] : unserialize($value);
        return $data;
    }

    protected function getAttrAttr($value)
    {
        $data = empty($value) ? [] : unserialize($value);
        return $data;
    }

    public function getTypeAttr($value)
    {
        $type = [0 => '未处理', 1=> '已处理'];
        return $type[$value];
    }


}
