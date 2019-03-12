<?php



namespace app\console\controller;



use think\Db;

use think\Request;

use think\Url;

use app\console\model\Store as ThisModel;



class Store extends Base

{

    /**

     * [index description]列表

     * @return [type] [description]

     */

    public function index(Request $request, ThisModel $user)
    {
        $name = array();
        $where = array();
        if (Request::instance()->isPost()) {
            $name = Request::instance()->param('name');
            if ($name) {
                $where = ['manned|mobile|title' => ['like', '%' . $name . '%'],

                ];
                $this->assign("name", $name);
            }
        }
        $data = $user->where($where)->where('id','<>',0)->order('id desc')->paginate(30, false, ['query' => array('name' => $name)]);
        return $this->fetch('index', [
            'list' => $data,
        ]);
    }



    /**

     * [create description]添加方法

     * @return [type] [description]

     */

    public function create(Request $request, ThisModel $user)

    {

        if (Request::instance()->isPOST()) {

            $num = range(0, 9);

            for ($i = 0; $i < 6; $i++) {
                $b[] = array_rand($num);
            }
            $data = Request::instance()->post();
            $data['account'] = "888" . join("", $b);
            $data['icode'] = join("", $b);

            $result = $user->allowField(true)->isUpdate(false)->save($data);
            if ($result) {
                $this->success('新建成功', 'console/Store/index');

            } else {

                $this->error($result);

            }

        } else {

            $param = $this->appendarg();

            return $this->fetch('create', $param);

        }



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

                $this->success('更新成功', 'console/Store/index');

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

     * //     */

    public function appendarg()

    {

        $infoclass = db::name('cn_prov')->field('id,name')->select();

        return [

            'pro' => $infoclass,

        ];

    }



    /*认证*/

    public function approve(Request $request, ThisModel $user, Certification $certification)

    {

        if ($request->isPost()) {

            $data = $request->param();

            $handle = empty($id) ? false : true;

            if (!$handle) {

                $data['user_id'] = $data['id'];

                unset($data['id']);

            }

            // 调用验证器类进行数据验证

            $result = $certification->validate(true)->allowField(true)->isUpdate($handle)->save($data);

//            echo $certification->getLastSql();exit;

            if ($result) {

                $arr['level_id'] = $data['certification_type'];

                $arr['id'] = $data['user_id'];

                $user->allowField(true)->isUpdate(true)->save($arr);

                $this->success('更新成功', 'console/Store/index');

            } else {

                $this->error($result);

            }

        }



        $id = $request->param("id");

        $res = Db::name("certification")->where("user_id", $id)->find();

        $res['picarr'] = unserialize($res['picarr']);

        $param = array_merge(['vo' => $res], $this->appendarg());

        return $this->fetch('approve', $param);

    }



    /*

      * [delete description]删除方法 多选和单选删除

      * @return [type] [description]

      */

    public function delete()

    {

        if (Request::instance()->isPOST()) {

            $id = Request::instance()->post('id/a'); // (/a)方法 将收到的id转为数组

            $delmodel = ThisModel::destroy($id);

            if ($delmodel) {

                $this->success('删除成功', 'console/Store/index');

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

            $validate = validate('Store');



            $post = Request::instance()->except(['id'], 'post');

            $post = array_keys($post);



            $validate->scene('edit', $post);

            if (!$validate->scene('edit')->check($data)) {

                $this->error($validate->getError());

            }

            $this_model = new ThisModel();

            if ($this_model->update($data)) {

                $this->success('更新成功', 'console/Store/index');

            } else {

                $this->error($this_model->getError());

            }

        } else {

            $this->error('请求方式出错!');

        }

    }



    public function goodslist(Request $request ,\app\console\model\Goods $goods)

    {

        $store_id = $request->param("store_id");

        $list = $goods->where("store_id",$store_id)->paginate(20);

        return $this->fetch("goods",[

            'list'=>$list

        ]);

    }
    public function orderlist(Request $request ,\app\console\model\Orders $orders)

    {

        $store_id = $request->param("store_id");

        $list = $orders->where(["store_id"=>$store_id])->paginate(20);

        return $this->fetch("order",[

            'list'=>$list

        ]);

    }

}

