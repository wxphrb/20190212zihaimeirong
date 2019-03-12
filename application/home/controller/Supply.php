<?php
namespace app\home\controller;
use app\home\model\Supply as SupplyModel;
use app\home\model\User;
use think\Controller;
use think\Db;
use think\Request;
use think\Session;
use think\Url;
use app\home\model\Share;
class Supply extends Controller
{
    /*
* 供求平台---1、列表
*/
    public function index(Request $requests, SupplyModel $supply, User $user)
    {
        $request = $requests->request();
        $request['user_id'] = Session::get('user_id');
        //帖子列表 筛选条件 分类id 关键词
        $where['status'] = 1;
        $where['state'] = 2;
        if (!empty($request['keyword'])) {
            $where['title'] = ['like', '%' . $request['keyword'] . '%'];
        }
        //列表
        $data = $supply->where($where)->field('id,title,address,user_id,create_time')->order('id desc')->select();
        foreach ($data as &$v) {
            $level_id = Db::name('user')->where('id',$v['user_id'])->value('level_id');
            $level_title = Db::name('certification_type')->where('id',$level_id)->value('title');
            $v['level_id'] = $level_id;
            $v['level_title'] = $level_title;
            if ($v['user_id'] != 0) {
                $v['username'] = $user->where('id', $v['user_id'])->value('username');
                $v['picurl'] = $user->where('id', $v['user_id'])->value('picurl');
            } else {
                $v['username'] = '官方发布';
                $v['picurl'] = '__HOME__/img/about_img_03.png';
            }
        }
        return $this->fetch(
            'index', [
                'title' => "供求平台",
                'data' => $data
            ]
        );
    }
    /*
* 供求平台---2、供求详情
* 参数：供求的id
*/
    public function info(Request $requests, User $user, SupplyModel $supply)
    {
        $jssdk = new Share("wx76f12421c3192e40", "d810e41dcc9d3509c0800fd6bd2f1e9b");
        $signPackage = $jssdk->GetSignPackage();
        $request = $requests->param();
        $request['user_id'] = Session::get('user_id');
        Db::name('news_info')->where(['user_id' => $request['user_id'], 'other_id' => $request['id'], 'read_state' => 0, 'type' => 3])->update(['read_state' => 1]);
        $data = $supply->where('id', $request['id'])->field('id,title,user_id,content,create_time,address')->find();
        $data['level_id']= Db::name('user')->where('id',$data['user_id'])->value('level_id');
        $data['level_title'] = Db::name('certification_type')->where('id',$data['level_id'])->value('title');
        if ($data['user_id'] != 0) {
            $data['username'] = $user->where('id', $data['user_id'])->value('username');
            $data['picurl'] = $user->where('id', $data['user_id'])->value('picurl');
        } else {
            $data['username'] = '后台发布';
            $data['picurl'] = '__HOME__/img/about_img_03.png';
        }
        if ($request['user_id'] != '') {
            $collect = Db::name("collection")->where(['type' => 4, "collection_id" => $request['id'], 'user_id' => $request['user_id']])->find();
            $data['collection_id'] = empty($collect['id']) ? "" : $collect['id'];
            $data['collection'] = empty($collect) ? 0 : 1;
        } else {
            $data['collection_id'] = "";
            $data['collection'] = 0;
        }
        $url = 'http://wantaozb.com/home/supply/info?id='.$data['id']."&jinbao=1";
        $user_id = Db::name('supply')->field('user_id')->where('id',$requests->param('id'))->value('user_id');
//        echo "<pre>";
//        print_r($data);
//        echo "</pre>";
        return $this->fetch(
            'info', [
                'title' => "供求详情",
                'data' => $data,
                'share'=>$signPackage,
                'url'=>$url,
                'jinbao'=> empty($request['jinbao']) ? 2:$request['jinbao'],
                'id'=>$request['user_id'],
                'user_id'=>$user_id
            ]
        );
    }
    /*
* 供求平台---我要供应/供求
*/
    public function supplyAdd(Request $requests, SupplyModel $supply)
    {
        $request = $requests->param();
        $request['user_id'] = Session::get('user_id');
        if ($requests->isPost()) {
            $result = $this->validate($request, 'Supply.supplyAdd');
            if ($result !== true) {
                return [
                    'code' => 400,
                    'message' => $result
                ];
            }
            $data = $supply->allowField(true)->isUpdate(false)->save($request);
            if ($data) {
                return [
                    'code' => 200,
                    'message' => "成功",
                ];
            }
        } else {
            return $this->fetch(
                'add', [
                    'title' => "我要发布",
                ]
            );
        }
    }
    /**
     * 搜索
     */
    public function search(){
        return $this->fetch(
            'search', ['title' => '搜索']
        );
    }
//ajax删除
    public function dele(Request $request){
        $id = $request->param('id');
        Db::name('supply')->where(['id'=>$id])->delete();
    }

    public function tudou(){
        return $this->fetch('address');
    }
}