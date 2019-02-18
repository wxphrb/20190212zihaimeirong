<?php

namespace app\console\model;

use think\Db;
use think\Model;

class User extends Base
{
    // 指定表名,不含前缀
    protected $name = 'user';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';


    protected static function init()
    {
        User::event('before_insert',function ($query){
           $query->password = md5(md5(123456));
        });
    }

    /**
     * [saveVerify description]模型验证 添加和修改
     * @param  string $id [description]主键id
     * @return [type]     [description]
     */
    protected function getPicarrAttr($value)
    {
        $data = empty($value)?[]:unserialize($value);
        return $data;
    }

   
    public static function saveVerify($data, $id = '')
    {
        $Model = new User();

//        if (empty($data['password']) && !empty($id)) {
//            unset($data['password']);
//        } else {
//            $data['password'] = mcs($data['password']);
//        }

        //判断是添加还是修改
        $handle = empty($id) ? false : true;

        // 调用验证器类进行数据验证
        $result = $Model->validate(true)->allowField(true)->isUpdate($handle)->save($data);
        if (false === $result) {
            // 验证失败 输出错误信息
            return $Model->getError();
        } else {
            return true;
        }
    }

    public function getCreateTimeAttr($data, $value)
    {
        return date("Y-m-d H:i:s", $value['create_time']);
    }

    public function getUpdateTimeAttr($data, $value)

    {

        return date("Y-m-d H:i:s", $value['update_time']);
    }


}