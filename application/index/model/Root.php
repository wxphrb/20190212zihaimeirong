<?php
namespace app\index\model;

use think\Model;

abstract class Root extends Model {
    protected $err = [ 'status' => false ];
    //自定义初始化
    protected function initialize() {

    }


    public function setCreatedByAttr(){
          return session('admin_id');
    }

    public function setCreatedTimesAttr(){
          return date('Y-m-d H:i:s');
    }

    // 设置错误并回滚
    public function err2rollback () {

    }

}
