<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/13
 * Time: 9:32
 */

namespace app\api\controller;


use think\Controller;

class Test extends Controller
{
    public function index()
    {
        $a = [1,3,5,45,78,2,45789,125,64,47,33,];
        $b = $this->bubble_sort($a);
        dump($b);
    }
     public function bubble_sort( $array)
    {
        $count = count($array);
        if ($count <= 0 ) return false;
        for($i=0 ; $i<$count; $i ++){
            for($j=$count-1 ; $j>$i; $j--){
                if ($array[$j] < $array [$j-1]){
                    $tmp = $array[$j];
                    $array[$j] = $array[ $j-1];
                    $array [$j-1] = $tmp;
                }
            }
        }
        return $array;
    }
}