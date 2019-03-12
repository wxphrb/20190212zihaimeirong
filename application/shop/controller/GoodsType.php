<?php

namespace app\shop\controller;

use app\shop\model\Store;
use think\Db;
use think\Request;
use think\Session;
use think\Url;
use app\shop\model\Store as ThisModel;

class GoodsType extends Base
{
    public $arr_type = [];

    /**
     * [index description]列表
     * @return [type] [description]
     */
    public function index(ThisModel $store)
    {
        $type = $store->where(['id' => Session::get("shop_id")])->value("type_id");
        $type = explode(',', $type);

        if ($type[0] != '') {
            foreach ($type as &$v) {
                $v = Db::name("goods_type")->where("id", $v)->field("id,title,id,picurl")->find();
            }
            foreach ($type as &$vv) {
                $vv['son'] = Db::name("goods_type")->where("parentid", $vv['id'])->field("id,title,id,picurl")->select();
            }
        }else{
            $type = array();
        }
        

        return $this->fetch('index', [
            'list' => $type
        ]);
    }

    /**
     * [create description]添加方法
     * @return [type] [description]
     */
    public function create(Request $request, ThisModel $store)
    {
        if (Request::instance()->isPOST()) {
            $data = Request::instance()->post();
            $data['type_id'] = implode(",", $data['type_id']);
            unset($data['table1_length']);
            $result = $store->where(["id" => Session::get("shop_id")])->update($data);
            if ($result) {
                $this->success('新建成功', 'shop/GoodsType/index');
            } else {
                $this->error($result, 'shop/GoodsType/index');
            }
        }
        $param = Db::name("GoodsType")->where("parentid", 0)->field("id,title")->select();

        return $this->fetch('create', ['goodstype' => $param]);
    }

    /**
     * [update description]更新方法
     * @param  [type] $id [description]主键id
     * @return [type]     [description]
     */
    public function update($id)
    {
        if (Request::instance()->isPOST()) {

            $data = Request::instance()->post();
            $result = ThisModel::saveVerify($data, $id);
            if (true === $result) {
                $this->success('更新成功', 'shop/GoodsType/index');
            } else {
                $this->error($result);
            }
        }

        $data = ThisModel::get($id);

        $param = array_merge(['vo' => $data], $this->appendarg());

        return $this->fetch('create', $param);
    }

    /**
     * 添加修改时候需要传递参数的话，用此方法，只写一遍
     */
    public function appendarg()
    {
        $this->showtype(0);
        return [
            //添加参数
            'goodstype' => $this->arr_type
        ];
    }

    /**
     * [showtype description]无限极分类
     * @param  integer $id [description]
     * @param  integer $i [description]
     * @return [type]      [description]
     */
    public function showtype($id = 0, $i = 0)
    {
//        $type_id = Db::name("store")->where("id",Session::get("shop_id"))->value("type_id");
//        halt(import($type_id,','));
        $res = db::name('goods_type')->field('id,parentid,title,picurl')->where(['parentid' => $id])->order('orderid', 'asc')->select();

        foreach ($res as &$v) {

            $title = '';
            for ($p = 1; $p < $i; $p++) {
                $title .= '&nbsp;&nbsp;&nbsp;';
            }

            if ($v['parentid'] != 0) {
                $title .= '|- ';
            }

            $title .= $v['title'];
            $v['title'] = $title;

            array_push($this->arr_type, $v);

            $this->showtype($v['id'], $i + 2);
        }

    }

    /**
     * [delete description]删除方法 多选和单选删除
     * @return [type] [description]
     */
    public function delete(Request $request, Store $store)
    {
        if (Request::instance()->isGet()) {
            $id = $request->param('id'); // (/a)方法 将收到的id转为数组
            $type_id = $store->where(['id' => Session::get("shop_id")])->value("type_id");
            $type_id = explode(",", $type_id);
            $data['type_id'] = array_diff($type_id, [$id]);
            $arr['type_id'] = implode(",", $data['type_id']);
            $arr['id'] = Session::get("shop_id");
            $delmodel = $store->allowField(true)->isUpdate(true)->save($arr);
            if ($delmodel) {
                $this->success('删除成功', 'shop/GoodsType/index');
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
            $validate = validate('GoodsType');

            $post = Request::instance()->except(['id'], 'post');
            $post = array_keys($post);

            $validate->scene('edit', $post);
            if (!$validate->scene('edit')->check($data)) {
                $this->error($validate->getError());
            }
            $this_model = new ThisModel();
            if ($this_model->update($data)) {
                $this->success('更新成功', 'shop/GoodsType/index');
            } else {
                $this->error($this_model->getError());
            }
        } else {
            $this->error('请求方式出错!');
        }
    }
}
