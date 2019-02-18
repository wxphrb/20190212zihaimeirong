<?php

namespace app\index\controller;

use app\index\model\SellCar as ThisModel;

use think\Controller;

use think\Db;

use think\Request;

use think\Url;



class SellCar extends Base

{
	public function _initialize() {
		parent::_initialize();
	}


	public function index( Request $request){

        $this->assign('bottom',4);
		$url = request()->domain().Request::instance()->url();
		$this->assign('url',$url);
		$this->assign('share_title', '宝泉岭尊业汽车 - 低价收车');
        return $this->fetch('index');


	}
    public function consult( Request $request){

        if (Request::instance()->isPost()) {
            if(!empty($_POST['bl_img'])){
                $bl_img   = $_POST['bl_img'];
                foreach ($bl_img as $key => $v) {
                    $row['img'] = '/'.$v;
                    $row['info'] = [];
                    $row['show'] = [];
                    $arr[] = $row;
                }
                $data['picarr']     =  serialize($arr);
            }
            $data['mobile']     = Request::instance()->post('mobile');
            $data['username']   = Request::instance()->post('username');
            $data['content']    = escapeString(Request::instance()->post('content'));

            $find = db('sell_car')->where(['mobile'=>$data['mobile'],'content'=>$data['content']])->limit(10)->find();

            if ($find) {
                //已提交过该车型
                return ['data'=>2];

            }else{

                $data['create_time'] = time();
                $data['ip']          = getIP();
                $data['status']      = 0;

                $result = ThisModel::saveVerify($data);

                if (true === $result) {

                    return ['data'=>1];

                } else {

                    return ['data'=>0];

                }

            }

        }
        return ['data'=>0];

    }
    public function mbupload()
    {
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");


// Support CORS
// header("Access-Control-Allow-Origin: *");
// other CORS headers if any...
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit; // finish preflight CORS requests here
        }


        if ( !empty($_REQUEST[ 'debug' ]) ) {
            $random = rand(0, intval($_REQUEST[ 'debug' ]) );
            if ( $random === 0 ) {
                header("HTTP/1.0 500 Internal Server Error");
                exit;
            }
        }

// header("HTTP/1.0 500 Internal Server Error");
// exit;


// 5 minutes execution time
        @set_time_limit(5 * 60);

// Uncomment this one to fake upload time
        usleep(5000);

// Settings
// $targetDir = ini_get("upload_tmp_dir") . '/' . "plupload";
        $targetDir = 'sellcar_tmp';
        $uploadDir = 'sellcar';

        $cleanupTargetDir = true; // Remove old files
        $maxFileAge = 5 * 3600; // Temp file age in seconds


// Create target dir
        if (!file_exists($targetDir)) {
            @mkdir($targetDir);
        }

// Create target dir
        if (!file_exists($uploadDir)) {
            @mkdir($uploadDir);
        }

// Get a file name
        if (isset($_REQUEST["name"])) {
            $fileName = $_REQUEST["name"];
        } elseif (!empty($_FILES)) {
            $fileName = $_FILES["file"]["name"];
        } else {
            $fileName = uniqid("file_");
        }

        $md5File = @file('md5list.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $md5File = $md5File ? $md5File : array();

        if (isset($_REQUEST["md5"]) && array_search($_REQUEST["md5"], $md5File ) !== FALSE ) {
            die('{"jsonrpc" : "2.0", "result" : null, "id" : "id", "exist": 1}');
        }
        $randname = rand(100,999);
        $filePath = $targetDir . '/' . $fileName;
        $uploadPath = $uploadDir . '/' . time().$randname.$fileName;

// Chunking might be enabled
        $chunk  = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;
// Remove old temp files
        if ($cleanupTargetDir) {
            if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
            }

            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $targetDir . '/' . $file;

                // If temp file is current file proceed to the next
                if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp") {
                    continue;
                }

                // Remove temp file if it is older than the max age and is not the current file
                if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $maxFileAge)) {
                    @unlink($tmpfilePath);
                }
            }
            closedir($dir);
        }


// Open temp file
        if (!$out = @fopen("{$filePath}_{$chunk}.parttmp", "wb")) {
            die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }

        if (!empty($_FILES)) {
            if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
            }

            // Read binary input stream and append it to temp file
            if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        } else {
            if (!$in = @fopen("php://input", "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        }

        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }

        @fclose($out);
        @fclose($in);

        rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part");

        $index = 0;
        $done = true;
        for( $index = 0; $index < $chunks; $index++ ) {
            if ( !file_exists("{$filePath}_{$index}.part") ) {
                $done = false;
                break;
            }
        }
        if ( $done ) {
            if (!$out = @fopen($uploadPath, "wb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
            }

            if ( flock($out, LOCK_EX) ) {
                for( $index = 0; $index < $chunks; $index++ ) {
                    if (!$in = @fopen("{$filePath}_{$index}.part", "rb")) {
                        break;
                    }

                    while ($buff = fread($in, 4096)) {
                        fwrite($out, $buff);
                    }

                    @fclose($in);
                    @unlink("{$filePath}_{$index}.part");
                }

                flock($out, LOCK_UN);
            }
            @fclose($out);
            echo $uploadPath;
            exit;
        }
    }


}
