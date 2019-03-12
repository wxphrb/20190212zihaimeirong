<?php
namespace app\console\model;
use think\Model;

class Base extends Model
{   
    //单图字段
    protected static $picurl = ['picurl','qrcode'];
    //组图字段
    protected static $picarr = ['picarr'];
    /**
     * [删除事件前处理图片]
     * @return [type] [description]
     */
    protected static function init()
    {
        self::beforeDelete(function ($data) {
            $vo = $data->getData();
            $picurl = self::$picurl;
            $picarr = self::$picarr;
            //单图处理
            foreach($picurl as $v){
                if(isset($vo[$v])&&!empty($vo[$v])){
                     @unlink(ROOT_PATH . 'public'. DS .$vo['picurl']);
                }
            }
            //组图处理
            foreach($picarr as $v){
                if(isset($vo[$v])&&!empty($vo[$v])){
                    $arr = unserialize($vo[$v]);
                    foreach($arr as $va){
                        @unlink(ROOT_PATH . 'public'. DS .$va['img']);
                    }
                }
            }                       
        });
        //包含beforeInsert beforeUpdate
        self::beforeWrite(function ($data) {
            $vo = $data->getData();
            $picurl = self::$picurl;
            $picarr = self::$picarr;
            //单图处理
            foreach($picurl as $v){
                if(isset($vo[$v])&&!empty($vo[$v])){
                    if(strstr($vo[$v],"tempdir")){
                        $path = ROOT_PATH . 'public' . DS . $vo[$v];
                        $file = \think\Image::open($path);

                        $newpath = str_replace("tempdir","uploads",$path);
                        $file->save($newpath);

                        @unlink(ROOT_PATH . 'public'. DS .$vo[$v]);
                        $data->data[$v] = str_replace("tempdir","uploads",$vo[$v]);
                    }
                    
                }
            }

            //组图处理
            foreach($picarr as $v){
                if(isset($vo[$v])&&!empty($vo[$v])){
                    if(strstr($vo[$v],"tempdir")){
                        $arr = unserialize($vo[$v]);
                        foreach($arr as $va){
                            if(strstr($va['img'],"tempdir")){
                                $path = ROOT_PATH . 'public' . DS . $va['img'];
                                $file = \think\Image::open($path);

                                $newpath = str_replace("tempdir","uploads",$path);
                                $file->save($newpath);
                                @unlink(ROOT_PATH . 'public'. DS .$va['img']);
                            }
                        }
                        $data->data[$v] = str_replace("tempdir","uploads",$vo[$v]);
                    }
                }
            }            
        });
    }
}