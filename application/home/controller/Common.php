<?php
namespace app\home\controller;
use think\Controller;
use think\Request;
use app\common\service\Osss;

class Common extends Controller
{

    //上传图片
    public function upload()
    {
        // 获取表单上传文件 例如上传了001.jpg
        $file = Request::instance()->file('file_data');

        // 移动到框架应用根目录/public/uploads/ 目录下
//        dump(getimagesize($file));die;

        $path = ROOT_PATH . 'public' . DS . 'uploads';
//            dump($file->validate(['size'=>2097152]));die;
        $info = $file->validate(['size'=>2097152])->rule('date')->move($path);
//        dump($info);die;
        if ($info) {
            $data['code'] = 0;
            $data['img'] =  'uploads' . '/' . date('Ymd') . '/' . $info->getFilename();
        } else {
            // $file->getError(); 输出 上传失败获取错误信息
            $data['code'] = 1;
            $data['error'] = "图片超出限制";
        }
//        dump($data);
        return json_encode($data);

    }


    public function uploadpost()
    {
        // 获取表单上传文件 例如上传了001.jpg
        $file = Request::instance()->file('file_data');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $path = ROOT_PATH . 'public' . DS . 'tempdir';
        $info = $file->rule('date')->move($path);
        if ($info) {
            $data['code'] = 0;
            $data['img'] = '/' . 'posts' . '/' . date('Ymd') . '/' . $info->getFilename();
        } else {
            // $file->getError(); 输出 上传失败获取错误信息
            $data['code'] = -1;
            $data['error'] = $info->getError();
        }
        return json_encode($data);
    }


    //删除图片
    public function updel()
    {
        $filename = Request::instance()->param('filename');
        if (!empty($filename)) {
            unlink(ROOT_PATH . 'public' . $filename);
            $data['code'] = 1;
        } else {
            $data['code'] = 2;
        }
        return json_encode($data);
    }

    protected function format_date($time){
        $t=time()+1-$time;
        $f=array(
            '31536000'=>'年',
            '2592000'=>'个月',
            '604800'=>'星期',
            '86400'=>'天',
            '3600'=>'小时',
            '60'=>'分钟',
            '1'=>'秒'
        );
        foreach ($f as $k=>$v)    {
            if (0 !=$c=floor($t/(int)$k)) {
                return $c.$v.'前';
            }
        }
    }

    // 获取图片地址
    function getmMdia($access_token,$media_id){
        $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=".$access_token."&media_id=".$media_id;
        $date = date('Ymd');
        if (!file_exists(ROOT_PATH . 'public' . DS . 'uploads'.DS.$date.DS)) {
            mkdir(ROOT_PATH . 'public' . DS . 'uploads'.DS.$date.DS, 0777, true);
        }
        $num = rand(1000,9999);
        $targetName = ROOT_PATH . 'public' . DS . 'uploads'.DS.$date.DS.date('YmdHis').$num.'.jpg';
        $targetName1['data'] = 'uploads'.DS.$date.DS.date('YmdHis').$num.'.jpg';
        $targetName1['id']=date('YmdHis').$num;
        $ch = curl_init($url); // 初始化
        $fp = fopen($targetName, 'wb'); // 打开写入
        curl_setopt($ch, CURLOPT_FILE, $fp); // 设置输出文件的位置，值是一个资源类型
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        return $targetName1;
    }
}