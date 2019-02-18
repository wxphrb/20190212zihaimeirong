<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/11
 * Time: 10:52
 */

namespace app\index\model;


use think\Model;

class CarBrand extends Model
{
    protected $table = 'mx_car_brand';


    public function brand_list ($varname="") {

        $Model = new CarBrand();
        $data['recommend']  = $Model->where(['status'=>1,'recommend'=>1])->order(['p_shouzimu'=>'asc'])->limit(8)->select();
        $list       = $Model->where('status',1)->order(['p_shouzimu'=>'asc'])->select();

        for($i=ord('A'); $i<=ord('Z'); $i++)
        {

            foreach($list as $k=>$v){
                if(chr($i) == $v['p_shouzimu']){
                    $data['list'][$i]['shouzimu'] = $v['p_shouzimu'];
                    $data['list'][$i]['list'][$k] = $v;
                }
            }
        }


        return   $data;
    }

}