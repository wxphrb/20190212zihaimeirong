<?php

namespace app\console\controller;

use app\console\model\Webtype as WebtypeModel;
use app\console\model\Webconfig as WebconfigModel;
use think\Cache;
use think\Request;
use think\Url;

class System extends Base
{
    /**
     * [index    配置管理]
     * console/system/index
     *
     * @param WebtypeModel $webtype
     * @return mixed
     */
    public function index(WebtypeModel $webtype)
    {
        $webtype_list = $webtype
            ->relation(['webconfig'])
            ->select();
//        halt($webtype_list);
        return $this->fetch('index', [
            'mate_title' => "配置管理",
            'list' => $webtype_list,
        ]);
    }

    /**
     * [whole    全局管理设置]
     * console/system/whole
     *
    `     * @param Request $request
     * @param WebconfigModel $webconfig
     */
    public function whole(Request $request, WebconfigModel $webconfig)
    {
        if ($request->isPost()) {
            Cache::set('webconfig', $request->post());
            $web = Cache::get('webconfig');
            foreach ($web as $k => $v) {
                $webconfig->where('varname', $k)->setField('varvalue', $v);
            }
            $this->success('操作成功!', Url::build("console/system/index"));
        } else {
            $this->error('操作失败!');
        }
    }
}