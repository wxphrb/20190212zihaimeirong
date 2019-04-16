<?php

namespace app\console\model;

use think\Db;
use think\Model;

class UserTmp extends Base
{
    // 指定表名,不含前缀
    protected $name = 'user_tmp';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';



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


    public function getCreateTimeAttr($data, $value)
    {
        return date("Y-m-d H:i:s", $value['create_time']);
    }


}