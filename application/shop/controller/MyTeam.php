<?php

namespace app\shop\controller;

use think\Db;
use think\Request;
use think\Session;
use think\Url;
use app\shop\model\User as ThisModel;
use Workerman\Worker;

class MyTeam extends Base
{
    /**
     * [index description]列表
     * @return [type] [description]
     */
    public function index(Request $request, ThisModel $user)
    {
        $name = '' ;
        $where = array();
        if (Request::instance()->isPost()) {
            $name = Request::instance()->param('name');
            if ($name) {
                $where = [
                    'username|mobile' => ['like', '%' . $name . '%'],
                ];
            }
        }
        $this->user_relation = db('user_relation');
        $shop_id = Session::get('shop_id');

        $data = db('user')->alias('u')
                ->join('store s','u.id=s.user_id','left')
                ->where('u.invited_id',$shop_id)
                ->field('u.id,u.username,u.level_id,s.mobile,u.create_time,u.all_money,u.star_lv,s.title,s.manned')
                ->select();

        foreach($data as $k=>$v){
           $data[$k]['fx_one_count']       =  $this->user_relation->where(['user_id'=>$v['id'],'fx_leve'=>1])->count();

           if($data[$k]['fx_one_count']){

               $data[$k]['fx_two_count']   =  $this->user_relation->where(['user_id'=>$v['id'],'fx_leve'=>2])->count();

           }else{

               $data[$k]['fx_one_count']   = 0;
               $data[$k]['fx_two_count']   = 0;
               $data[$k]['fx_three_count'] = 0;
               continue;
           }


           if($data[$k]['fx_two_count']){

               $data[$k]['fx_three_count'] =  $this->user_relation->where(['user_id'=>$v['id'],'fx_leve'=>3])->count();

           }else{

               $data[$k]['fx_three_count'] = 0;
           }
        }
        /*
        $data = $user->where($where)->where('invited_id',$shop_id)->order('id desc')->paginate(30, false, ['query' => array('name' => $name)]);
        */
        return $this->fetch('index', [
            'list' => $data,
            'name' => $name
        ]);
    }



    /**
     * [renewfield description]列表更新字段
     * @return [type] [description]
     */
    public function renewfield()
    {
        if (Request::instance()->isPOST()) {
            $data = Request::instance()->post();
            $validate = validate('User');

            $post = Request::instance()->except(['id'], 'post');
            $post = array_keys($post);

            $validate->scene('edit', $post);
            if (!$validate->scene('edit')->check($data)) {
                $this->error($validate->getError());
            }
            $this_model = new ThisModel();
            if ($this_model->update($data)) {
                $this->success('更新成功', 'shop/agent_list/index');
            } else {
                $this->error($this_model->getError());
            }
        } else {
            $this->error('请求方式出错!');
        }
    }

    public function fx_system(Request $request)
    {
        $id = $request->param("id");
        $where=[];
        if (Request::instance()->isPost()) {
            $name = Request::instance()->param('name');
            if ($name) {
                $where = [
                    'username|mobile' => ['like', '%' . $name . '%'],
                ];
            }
        }
        $list = Db::name("integral_info")
            ->where("user_id",$id)
            ->where($where)
            ->paginate(20);
        return $this->fetch("fx_system",[
            'list'=>$list,
        ]);
    }
}
