<?php



namespace app\console\controller;



use think\Db;

use think\Request;

use think\Session;

use think\Url;

use app\console\model\Orders as OrderModel;

use app\console\model\NewsInfo;

class RefundGood extends Base

{

    /**

     * [index description]列表

     * @return [type] [description]

     */

    public function index(Request $request, OrderModel $orders)

    {

        $name = array();



        if (Request::instance()->isPost()) {

            $name = Request::instance()->param('name');

            if ($name) {

                $where = [

                    'order_sn|mobile|username|address' => ['like', '%' . $name . '%'],

                ];

                $this->assign("name", $name);

            }

        }
        $where['store_id'] = 0;
        $where['status'] =  8;
        $data = $orders->where($where)->order('id desc')->paginate(30, false, ['query' => array('name' => $name)]);

        return $this->fetch('index', [

            'list' => $data,

        ]);

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

            $result = OrderModel::saveVerify($data, $id);

            if (true === $result) {

                $this->success('更新成功', 'console/RefundGood/index');

            } else {

                $this->error($result);

            }

        }



        $data = OrderModel::get($id);

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






    /*

      * [delete description]删除方法 多选和单选删除

      * @return [type] [description]

      */

    public function delete()

    {

        if (Request::instance()->isPOST()) {

            $id = Request::instance()->post('id/a'); // (/a)方法 将收到的id转为数组

            $delmodel = OrderModel::destroy($id);

            if ($delmodel) {

                $this->success('删除成功', 'console/RefundGood/index');

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

            $validate = validate('Orders');



            $post = Request::instance()->except(['id'], 'post');

            $post = array_keys($post);



            $validate->scene('edit', $post);

            if (!$validate->scene('edit')->check($data)) {

                $this->error($validate->getError());

            }

            $this_model = new OrderModel();

            if ($this_model->update($data)) {

                $this->success('更新成功', 'console/RefundGood/index');

            } else {

                $this->error($this_model->getError());

            }

        } else {

            $this->error('请求方式出错!');

        }

    }



    /*同意退款*/

    public function achieve(Request $request, OrderModel $orders,NewsInfo $newsInfo)

    {

        $data['id'] = $request->param("id");

        $data['status'] = 4;

        $result = $orders->allowField(true)->isUpdate(true)->save($data);

        if ($result) {
            $res = $orders::get($data['id']);
            $request['title'] = "您的退款申请已通过！";
            $request['type'] = 7;
            $newsInfo->isUpdate(false)->allowField(true)->save([
                'other_id'=>$data['id'], 'user_id' => $res['user_id'],'type' =>7,'title' => $request['title'],'status'=>2,'create_time'=>time()
            ]);
            push_user($request['title'], $res['user_id'],$res['id'], 7, $newsInfo->id);
            return ["msg" => "更新成功"];

        } else {

            return ["msg" => "操作失败"];



        }

    }



    /*拒绝退款*/

    public function reject_tk(Request $request, OrderModel $orders,NewsInfo $newsInfo)

    {

        $data['id'] = $request->param("id");

        $data['status'] = 7;

        $result = $orders->allowField(true)->isUpdate(true)->save($data);

        if ($result) {
            $res = $orders::get($data['id']);
            $request['title'] = "您的退款申请被拒绝！";
            $request['type'] = 7;
            $newsInfo->isUpdate(false)->allowField(true)->save([
                'other_id'=>$data['id'], 'user_id' => $res['user_id'],'type' =>7,'title' => $request['title'],'status'=>2,'create_time'=>time()
            ]);
            push_user($request['title'], $res['user_id'],$res['id'], 7, $newsInfo->id);
            return ["msg" => "更新成功"];

        } else {

            return ["msg" => "操作失败"];



        }

    }



    /*拒绝退货*/

    public function reject_th(Request $request, OrderModel $orders,NewsInfo $newsInfo)

    {

        $data['id'] = $request->param("id");

        $data['status'] = 9;

        $result = $orders->allowField(true)->isUpdate(true)->save($data);

        if ($result) {
            $res = $orders::get($data['id']);
            $request['title'] = "您的退货申请被拒绝！";
            $request['type'] = 7;
            $newsInfo->isUpdate(false)->allowField(true)->save([
                'other_id'=>$data['id'], 'user_id' => $res['user_id'],'type' =>7,'title' => $request['title'],'status'=>2,'create_time'=>time()
            ]);
            push_user($request['title'], $res['user_id'],$res['id'], 7, $newsInfo->id);
            return ["msg" => "更新成功"];

        } else {

            return ["msg" => "操作失败"];



        }

    }



    public function fahuo(Request $request, OrderModel $orders,NewsInfo $newsInfo)

    {

        $data = $request->param();

        $data['status'] = 2;

//        $data['courier_name'] = $request->param("courier_name");

//        $data['courier_number'] = $request->param("courier_member");

        $result = $orders->allowField(true)->isUpdate(true)->save($data);

        if ($result) {
            $res = $orders::get($data['id']);
            $request['title'] = "您的订单已发货！";
            $request['type'] = 7;
            $newsInfo->isUpdate(false)->allowField(true)->save([
                'other_id'=>$data['id'], 'user_id' => $res['user_id'],'type' =>7,'title' => $request['title'],'status'=>2,'create_time'=>time()
            ]);
            push_user($request['title'], $res['user_id'],$res['id'], 7, $newsInfo->id);
            return ["msg" => "更新成功"];

        } else {

            return ["msg" => "操作失败"];



        }

    }



}

