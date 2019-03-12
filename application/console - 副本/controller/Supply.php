<?php



namespace app\console\controller;



use think\Db;

use think\Request;

use think\Url;

use app\console\model\Supply as ThisModel;

use app\console\model\NewsInfo;

class Supply extends Base

{

    /**

     * [index description]列表

     * @return [type] [description]

     */

    public function index(Request $request, ThisModel $supply)

    {

        $name = array();

        $where = ['state' => 1];

        if (Request::instance()->isPost()) {

            $name = Request::instance()->param('name');

            if ($name) {

                $where = [

                    'title' => ['like', '%' . $name . '%'],

                ];

                $this->assign("name", $name);

            }

        }

        $data = $supply->where($where)->order('id desc')->paginate(30, false, ['query' => array('name' => $name)]);

        return $this->fetch('index', [

            'list' => $data,

        ]);

    }
   /*我要买*/
    public function wantbuy(){

    }

    /**

     * [create description]添加方法

     * @return [type] [description]

     */

    public function create(Request $request, ThisModel $supply)

    {

        if (Request::instance()->isPOST()) {

            $data = Request::instance()->post();

            $result = ThisModel::saveVerify($data);

            if (true === $result) {

                $this->success('新建成功', 'console/Supply/index');

            } else {

                $this->error($result);

            }

        }

        return $this->fetch('create');





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

                $this->success('更新成功', 'console/Supply/index');

            } else {

                $this->error($result);

            }

        }



        $data = ThisModel::get($id);

        $param = array_merge(['vo' => $data]);

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

                $this->success('更新成功', 'console/Supply/index');

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

                $this->success('删除成功', 'console/Supply/index');

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

    public function renewfield(NewsInfo $newsInfo)

    {

        if (Request::instance()->isPOST()) {
            $data = Request::instance()->post();
            $validate = validate('Supply');
            $post = Request::instance()->except(['id'], 'post');
            $post = array_keys($post);
            $validate->scene('edit', $post);
            if (!$validate->scene('edit')->check($data)) {
                $this->error($validate->getError());
            }
            $this_model = new ThisModel();
            if ($this_model->where('id',$data['id'])->update(['status'=>$data['status']])) { //0未审核1已审核
                $res = ThisModel::get($data['id']);
                if($data['status']=='1'){
                    $request['title'] = "您的供求已通过审核！";
                    $request['type'] = 6;
                    $newsInfo->isUpdate(false)->allowField(true)->save([
                        'other_id'=>$data['id'], 'user_id' => $res['user_id'],'type' =>3,'title' => $request['title'],'status'=>2,'create_time'=>time()
                    ]);
                    push_user($request['title'], $res['user_id'],$res['id'], 3, $newsInfo->id);
                }

                $this->success('更新成功', 'console/Supply/index');
            } else {
                $this->error($this_model->getError());
            }

        } else {

            $this->error('请求方式出错!');

        }

    }

}

