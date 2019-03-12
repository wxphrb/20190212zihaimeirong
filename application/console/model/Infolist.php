<?php

namespace app\console\model;

use think\Model;

class Infolist extends Base
{
    // 指定表名,不含前缀
    protected $name = 'certification';
    // 开启自动写入时间戳字段
    protected $createTime= 'int';

    /**
     * [saveVerify description]模型验证 添加和修改
     * @param  string $id [description]主键id
     * @return [type]     [description]
     */
    //开启自动完成
    protected $auto = ['picarr'];

    protected function setPicarrAttr($value)
    {
        $picarr = input('picarr/a');
        if (!empty($picarr) && is_array($picarr)) {

            $arr = array();
            foreach ($picarr as $key => $v) {
                $row['img'] = $v;
                $arr[] = $row;
            }
            return serialize($arr);
        } else {
            return '';
        }

    }

    public function getCreateTimeAttr($data,$value)
    {
        return date("Y-m-d H:i:s",$value['create_time']);
    }


    /*
* 认证类型*/


    public function approve()
    {
        return $this->belongsTo("CertificationType",'certification_type','id')->field("title");
    }

    protected function getPicarrAttr($value)
    {
        $data = empty($value) ? [] : unserialize($value);
        return $data;
    }


    public static function saveVerify($data, $id = '')
    {
        $Model = new Infolist();

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



}
