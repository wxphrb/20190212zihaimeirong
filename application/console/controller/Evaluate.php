<?php

namespace app\console\controller;

use think\Db;
use think\Request;
use think\Session;
use think\Url;
use app\console\model\Evaluate as ThisModel;

class Evaluate extends Base
{


    /*商家后台查看商品评价*/

    public function index(Request $request, ThisModel $evaluate)
    {
        $id = $request->param("goods_id");
        $name = array();
        $where = ['goods_id' => $id, 'status' => 1];
        if (Request::instance()->isPost()) {
            $name = Request::instance()->param('name');
            if ($name) {
                $where = [
                    'ordersn' => ['like', '%' . $name . '%'],
                ];
            }
        }
        $list = $evaluate
            ->where($where)
            ->paginate(10, false, ['query' => array('name' => $name)]);

        return $this->fetch("index", [
            'list' => $list
        ]);
    }

    /*查看评价内容*/

    public function create(Request $request, Evaluate $evaluate)
    {
        $id = $request->param("id");
        $detail = $evaluate
            ->where("id", $id)
            ->find();
        return $this->fetch("appraise_create", [
            'vo' => $detail
        ]);
    }

    /**
     * [update description]更新方法
     * @param  [type] $id [description]主键id
     * @return [type]     [description]
     */
    public function update($id)
    {
        if (Request::instance()->isPOST())
        {
            $data = Request::instance()->post();
            $result = ThisModel::saveVerify($data,$id);
            if (true === $result) {
                $this->success('更新成功', 'console/goods/index');
            } else {
                $this->error($result);
            }
        }

        $data = ThisModel::get($id);

        $param = array_merge(['vo'=>$data]);
        return $this->fetch('create', $param);

    }

    /**
     * [delete description]删除方法 多选和单选删除
     * @return [type] [description]
     */
    public function delete()
    {
        if (Request::instance()->isPOST()) {
            $id = Request::instance()->post('id/a'); // (/a)方法 将收到的id转为数组
            $delmodel = ThisModel::destroy($id);
            if ($delmodel) {
                $this->success('删除成功', 'console/Authgroup/index');
            } else {
                $this->error($delmodel->getError());
            }
        } else {
            $this->error('请求方式出错!');
        }
    }

    /**
     * [renewfield description]列表更新字段
     * @return [type] [description]
     */
    public function renewfield()
    {
        if (Request::instance()->isPOST()) {
            $data = Request::instance()->post();
            $validate = validate('Authgroup');

            $post = Request::instance()->except(['id'], 'post');
            $post = array_keys($post);

            $validate->scene('edit', $post);
            if (!$validate->scene('edit')->check($data)) {
                $this->error($validate->getError());
            }
            $this_model = new ThisModel();
            if ($this_model->update($data)) {
                $this->success('更新成功', 'console/Authgroup/index');
            } else {
                $this->error($this_model->getError());
            }
        } else {
            $this->error('请求方式出错!');
        }
    }

    /**
     * [setup description]权限管理
     * @return [type] [description]
     */
    public function setup($id)
    {

        if (Request::instance()->isPost()) {
            $data['id'] = intval($id);
            $model = Request::instance()->post('model/a');
            $data['rules'] = empty($model) ? '' : serialize($model);

            $this_model = new ThisModel();
            if ($this_model->update($data)) {
                cache("ACL_" . $id, NULL);
                cache("ACL_all", NULL);
                $this->success('设置成功!', 'console/Authgroup/index');

            } else {
                $this->error($this_model->getError());
            }

        }
        require APP_PATH . "common/common/auth1.inc.php";

        $data = ThisModel::get($id);
        $data['rules'] = unserialize($data['rules']);

        return $this->fetch('setup', [
            'acldata' => $data,
            'acl_list' => $acl_inc
        ]);
    }

    /*加密连接*/
    public function base(Request $request )
    {
        $id = base64_encode(json_encode($request['id']));
        $data = $_SERVER['SERVER_NAME'] . '/index.php/console/evaluate/update?evaluate=' . $id . '';
        return $data;
    }

}
