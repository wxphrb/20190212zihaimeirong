<?php
//------------------------
// 自动生成代码
//-------------------------

namespace app\shop\controller;

use think\Config;
use think\Controller;
use think\Loader;
use think\Url;
use think\Db;

class Generate extends Base
{
    /**
     * 首页
     * @return mixed
     */
    public function index()
    {
        $tables = Db::getTables();
        $this->view->assign('tables', $tables);
        if ($this->request->param('table')) {
            $table = $this->request->param('table');
            $prefix = Config::get('database.prefix');
            $tableInfo = Db::getTableInfo($table);
            $controller = Loader::parseName(preg_replace('/^(' . $prefix . ')/', '', $table), 1);

            $this->view->assign('table_info', json_encode($tableInfo));
            $this->view->assign('controller', $controller);
        }

        return $this->view->fetch();
    }

    /**
     * 生成代码
     */
    public function run()
    {
        $generate = new \Generate();
        $data = $this->request->post();
        $generate->run($data);
        
        $this->success('生成成功，去看看', $data['module'].'/'.$data['controller'].'/index');
    }
}
