<?php
namespace app\index\model;
use think\Model;

class Info extends Root
{
    // 指定表名,不含前缀
    protected $name = 'info';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    /**
     * [saveVerify description]模型验证 添加和修改
     * @param  string $id [description]主键id
     * @return [type]     [description]
     */
    public static function saveVerify($data,$id = ''){
        $Model = new Info();
        
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
    /*
     * car_type new新车 old二手车
     * id       车辆id
     * money    车辆金额
     * param    1获取全部 2获取最低
     */
    public function fin_info($car_type,$id,$money,$param=1){
        $Model = new Info();
        $wu    = 0;
        if($car_type=='new'){
            /*    $res  = $Model->where(['use_type'=>['like','%3%']])->select();
            if(empty($res)){
                $res  = $Model->where(['use_type'=>['like','%2%']])->select();
            }*/
			 $res  = $Model->where(['use_type'=>['like','%3%']])->whereOr(['use_type'=>['like','%2%']])->whereOr(['use_type'=>['like','%4%']])->select();
			 ;
        }else{
            $res =  $Model->where(['use_type'=>['like','%1%']])->select();
        }

        if($param == 2){
            if($car_type=='new'){

                //使用首付少的 //五年
                foreach($res as $v) {

					$huilv[$v['id']]  = $v['new_wu_param1'] * $v['new_wu_param2'];
					$param1[$v['id']] = $v['new_wu_param1'];
					$param2[$v['id']] = $v['new_wu_param2'];
                }
            }else{

               foreach($res as $v) {
                   $huilv[$v['id']]  = $v['param1'] * $v['param2'] * $v['param3'];
                   $param1[$v['id']] = $v['param1'];
                   $param2[$v['id']] = $v['param2'];
               }
            }

        }else{
            if($car_type=='new'){
                $res  = $Model->where(['use_type'=>['like','%2%']])->whereOr(['use_type'=>['like','%3%']])->select();
                foreach($res as $k=>$v) {
                    if(in_array(3,json_decode($v['use_type']))){
                        $data[$k.'1']['shoufu']   = round(($money-$money*$v['new_wu_param1'])/10000,2);
                        $data[$k.'1']['yuegong']  = round($money*$v['new_wu_param1'] * $v['new_wu_param2'],2);
                        //首付汇率
                        $data[$k.'1']['shoufu_hv'] = 1-$v['new_wu_param1'];
                        $data[$k.'1']['count_qi']  = 60;
                        $data[$k.'1']['title']    = $v['title'];
                    }
					if(in_array(4,json_decode($v['use_type']))){
                        $data[$k.'3']['shoufu']   = round(($money-$money*$v['new_si_param1'])/10000,2);
                        $data[$k.'3']['yuegong']  = round($money*$v['new_si_param1'] * $v['new_si_param2'],2);
                        //首付汇率
                        $data[$k.'3']['shoufu_hv'] = 1-$v['new_si_param1'];
                        $data[$k.'3']['count_qi']  = 48;
                        $data[$k.'3']['title']    = $v['title'];
                    }
                    if(in_array(2,json_decode($v['use_type']))){
                        $data[$k.'2']['shoufu']   = round(($money-$money*$v['new_san_param1'])/10000,2);
                        $data[$k.'2']['yuegong']  = round($money*$v['new_san_param1'] * $v['new_san_param2'],2);
                        //首付汇率
                        $data[$k.'2']['shoufu_hv'] = 1-$v['new_san_param1'];
                        $data[$k.'2']['count_qi']  = 36;
                        $data[$k.'2']['title']    = $v['title'];
                    }
					
                }
            }else{

                foreach($res as $k=>$v) {

                    $data[$k]['title']    = $v['title'];
                    $data[$k]['shoufu']   = round(($money-$money*$v['param1']* $v['param2'])/10000,2);
                    $data[$k]['yuegong']  = round($money*$v['param1'] * $v['param2'] * $v['param3'],2);
                    //首付汇率
                    $data[$k]['shoufu_hv'] = 1-$v['param1'] * $v['param2'];
                    $data[$k]['count_qi']  = 36;
                }
            }

        }


        if($param == 1){
            return $data;
        }else {
            $old_huilv = $huilv;

            sort($huilv);
            foreach($old_huilv as $key=>$val){
                if($huilv[0]==$val){
                    $huilv_param1 = $param1[$key];
                }
            }
            $shoufu  = round(($money - $money * $huilv_param1) / 10000, 2);
            $yuegong = round($money * $huilv[0], 2);
            return ['shoufu' => $shoufu, 'yuegong' => $yuegong];
        }
    }
}
