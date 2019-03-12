<?php
namespace app\shop\controller;
use think\Db;
use think\Request;
use think\Url;

class Clear extends Base
{
	public function index(){
		/*检测清理垃圾文件*/
		$temp_dir = ROOT_PATH . 'public' . DS . 'tempdir';//需要检测的目录

		$dh = opendir($temp_dir);
		while (($file = readdir($dh)) !== false) {
            //不清除今天的图片缓存 以免出现错误
			if($file != '.' && $file != '..'&&$file != date('Ymd')){
			//dump($file);
			   $this->deldir($temp_dir . DS . $file);
		    }

		}
		$this->success('清理垃圾文件成功');
	}

	public function deldir($dir) {
		//先删除目录下的文件：
		$dh=opendir($dir);
		while ($file=readdir($dh)) {
		    if($file!="." && $file!="..") {
		      $fullpath=$dir.DS.$file;
		      if(!is_dir($fullpath)) {
		          unlink($fullpath);
		      } else {
		          $this->deldir($fullpath);
		      }
		    }
		}
		  
		closedir($dh);
		//删除当前文件夹：
		if(rmdir($dir)) {
		    return true;
		} else {
		    return false;
		}
	}
 
}