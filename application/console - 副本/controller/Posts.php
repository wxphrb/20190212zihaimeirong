<?php



namespace app\console\controller;
use think\Db;

use think\Request;

use think\Url;

use app\console\model\Posts as ThisModel;
use app\console\model\NewsInfo;
use app\console\model\User;
class Posts extends Base
{
    /**
     * [index description]列表
     * @return [type] [description]
     */
    public function index(ThisModel $posts, Request $request)
    {
        $type_id = array();
        $name = array();
        $where = '';
        if (Request::instance()->isPost()) {
            $name = $request->param("name");
            if ($name) {
                $where['user_id'] = User::where('username', 'like', '%' . $name . '%')->value("id");
                $this->assign("name",$name);
            }
            $type_id = $request->param("type_id");
            if ($type_id) {
                $where['type_id'] = $type_id;
            }
        }
        $data = $posts
            ->where($where)
            ->order('id', 'desc')
            ->paginate(20);
//        dump($data);die;
//        $param = array_merge(['list' => $data], $this->appendarg());
//        dump($param);die;
        return $this->fetch('index', [
            'list'=>$data
        ]);
    }
    /**
     * [create description]添加方法
     * @return [type] [description]
     */
    public function create()
    {
        if (Request::instance()->isPOST()) {
            $data = Request::instance()->post();
            $data['is_console'] = 2;
            $result = ThisModel::saveVerify($data);
            if (true === $result) {

                $this->success('新建成功', 'console/Posts/index');

            } else {
                $this->error($result);
            }
        }
        $param = $this->appendarg();
        return $this->fetch('create', $param);

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
            $data['is_console'] = 2;
            $result = ThisModel::saveVerify($data, $id);

            if (true === $result) {

                $this->success('更新成功', 'console/Posts/index');

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

        return [

            //添加参数

            'type_title' => Db::name("bbc_type")->where("status", 1)->field("id,type_title")->select(),

        ];

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

                $this->success('删除成功', 'console/Posts/index');

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
            $validate = validate('Posts');
            $post = Request::instance()->except(['id'], 'post');
            $post = array_keys($post);

            $validate->scene('edit', $post);
            if (!$validate->scene('edit')->check($data)) {

                $this->error($validate->getError());
            }
            $this_model = new ThisModel();
//            dump($data);die;
            if (Db::name('posts')->where('id',$data['id'])->update(['status'=>$data['status']])) {
                if($data['status']=='1'){
                    $res = $this_model::get($data['id']);
                    $request['title'] = "您的帖子已通过审核！";
                    $request['type'] = 6;
                    $newsInfo->isUpdate(false)->allowField(true)->save([
                        'other_id'=>$data['id'], 'user_id' => $res['user_id'],'type' =>2,'title' => $request['title'],'status'=>2,'create_time'=>time()
                    ]);
                    push_user($request['title'], $res['user_id'],$res['id'], 2, $newsInfo->id);
                }
                $this->success('更新成功', 'console/Posts/index');

            } else {

                $this->error($this_model->getError());

            }
        } else {
            $this->error('请求方式出错!');

        }

    }
    public  function hot_state() {
        $param = Request::instance()->param();
        if($param['hot_state'] == 1) {
            $a = Db::name('posts')->where('id',$param['id'])->update(['hot_state'=>1]);
            $this->success('更新成功', 'console/Posts/index');
        } else {
            $a = Db::name('posts')->where('id',$param['id'])->update(['hot_state'=>0]);
            $this->success('更新成功', 'console/Posts/index');
        }

    }
}

