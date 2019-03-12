<?php

namespace app\home\controller;

use think\Controller;

use think\Db;
use app\common\service\Osss;
use think\Request;



//公共ajax调用控制器

class Upload extends Controller
{
//array(5) {
//["name"] =&gt; string(37) "u=490020744,1716715155&amp;fm=11&amp;gp=0.png"
//["type"] =&gt; string(9) "image/png"
//["tmp_name"] =&gt; string(14) "/tmp/phpDEh0Mc"
//["error"] =&gt; int(0)
//["size"] =&gt; int(135629)
//}
          public function ossUpload()
          {
              $a = request()->file('file');
                    $file = $a->getInfo();

                    // 获取表单上传文件 例如上传了001.jpg
                    ini_set("memory_limit", "1024M"); // 不够继续加大
                    set_time_limit(0);
                    $oss = new Osss();

                    $result = $oss->max_upload_mobile($file);

                    if ($result['object']) {
                              $data['json'] = 200;
                              $data['picUrl'] = $result['object'];
                    } else {
                              $data['json'] = 400;
                              $data['picUrl'] = $result['error'];
                    }
                    return json_encode($data);
          }

          public function uploadImgDestroy()
          {
                    $picUrl = Request::instance()->param('picUrl');
                    if (!empty($picUrl)) {
//                              unlink($picUrl);
                              return ['code' => 200, 'message' => '取消成功'];
                    }
                    return ['code' => 400, 'message' => '网络繁忙'];
          }
}