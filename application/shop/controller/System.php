<?php
namespace app\shop\controller;
use think\Db;
use think\Request;
use think\Url;
use app\shop\model\Webconfig as ThisModel;

class System extends Base
{
	 /*
     * 配置管理
     */
    public function index()
    {
        $data = Db::name('webtype')->order('orderid', 'asc')->select();
        foreach ($data as &$v) {
            $v['info'] = Db::name('webconfig')->where(['vargroup' => $v['id']])->order('orderid', 'asc')->select();
        }
        $where['id']=['not in','5'];
        $type = Db::name('webtype')->where($where)->order('orderid', 'asc')->select();

        return $this->fetch('index', [
            'list'       => $data,
            'type'       => $type
        ]);

    }

    /*
     * 添加新变量
     */
    public function create()
    {

        if (Request::instance()->isPOST())
        {
            $data = Request::instance()->post();
            $data['varname'] = 'web_' . $data['varname'];
            
            $orderid =  Db::name('webconfig')->where(['vargroup' => $data['vargroup']])->max('orderid');
            $data['orderid'] = $orderid + 1;

            $result = ThisModel::saveVerify($data);
            if (true === $result) {
                $value = Db::name('webconfig')->field('varname,varvalue')->select();
                $arr = [];
                foreach($value as $v){
                    $arr[$v['varname']] = $v['varvalue'];
                }

                // 文件路径
                $path = APP_PATH . "common" . DS . "common" . DS . "web.inc.php";

                file_put_contents($path, serialize($arr));
                $this->success('新增成功', 'shop/system/index');
            } else {
                $this->error($result);
            }
        }

    }

}