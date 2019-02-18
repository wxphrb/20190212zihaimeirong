<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/9
 * Time: 14:49
 */
namespace app\api\controller;
use Think\Controller;

class Tool extends Controller{

    static public $treeList = array(); //存放无限分类结果如果一页面有多个无限分类可以使用 Tool::$treeList = array(); 清空
    /**
     * 无限级分类
     * @access public
     * @param Array $data     //数据库里获取的结果集
     * @param Int $pid
     * @param Int $count       //第几级分类
     * @return Array $treeList
     */
    static public function tree(&$data,$pid,$count = 1) {
        foreach ($data as $key => $value){
            if($value['parent_id'] == $pid ){
                $value['count'] = $count;
                self::$treeList [] = $value;
                unset($data[$key]);
                self::tree($data,$value['id'],$count+1);
            }
        }
        return self::$treeList ;
    }

    static public function trees(&$data,$pid = '0',$count = 1) {
        foreach ($data as $key => $value){
            //echo $value['parentid'];
            if($value['parent_id'] == $pid){
                $value['count'] = $count;
                self::$treeList [] = $value;
                unset($data[$key]);
                self::trees($data,$value['id'],$count+1);
            }
        }
        return self::$treeList ;
    }

static public function getSubTree($data,$pid='0',$count = 1){
        $tmp=array();
        foreach($data as $key =>$value){
            if($value['parent_id']==$pid){
                $value['count'] = $count;
//                if($count>=3){
//                    $value=self::getSubTree($data,$value['id'],$count+1);
//                    $value=self::getSubTree($data,$value['id'],$count+1);
//                    array_push($value,$data[''.$key.'']);
//                }else{
                    $value['child']= self::getSubTree($data,$value['id'],$count+1);

//                }
                $tmp[]=$value;
            }
        }
        return $tmp;
    }

}