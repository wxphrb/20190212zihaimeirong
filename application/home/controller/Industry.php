<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/4
 * Time: 10:28
 */
namespace app\home\controller;
use app\home\model\IndustryInformation as IndustryInformationModel;
use app\home\model\User;
use think\Controller;
use think\Db;
use think\Request;
use think\Session;
use app\home\model\Share;
class Industry extends Controller
{
    /*
     * 行业资讯---1、列表
     */
    public function index(Request $requests, IndustryInformationModel $industryInformation, User $user)
    {
        $request = $requests->param();
        //列表 筛选条件 关键词
        $where['status'] = 1;
        if (!empty($request['keyword'])) {
            $where['title'] = ['like', '%' . $request['keyword'] . '%'];
        }
        //列表
        $data = $industryInformation->where($where)->field('id,title,user_id,picarr,picarr_type,description,hot_state,create_time,content')->order('id desc')->select();
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
                'title' => "行业资讯",
                'data' => $data
            ]
        );
    }
    /*
   * 行业资讯---2、详情
    * 参数：行业资讯的id
   */
    public function info(Request $requests, User $user, IndustryInformationModel $industryInformation)
    {
        $jssdk = new Share("wx76f12421c3192e40", "d810e41dcc9d3509c0800fd6bd2f1e9b");
        $signPackage = $jssdk->GetSignPackage();
        $request = $requests->param();
        $request['user_id'] = Session::get('user_id');
        $data = $industryInformation->where('id', $request['id'])->field('id,title,user_id,picarr,picarr_type,create_time,content')->find();
        $data['level_id']= Db::name('user')->where('id',$data['user_id'])->value('level_id');
        $data['level_title'] = Db::name('certification_type')->where('id',$data['level_id'])->value('title');
        if ($data['user_id'] != 0) {
            $data['username'] = $user->where('id', $data['user_id'])->value('username');
            $data['picurl'] = $user->where('id', $data['user_id'])->value('picurl');
        } else {
            $data['username'] = '官方发布';
            $data['picurl'] = '__HOME__/img/about_img_03.png';
        }
        if ($request['user_id'] != '') {
            $collect = Db::name("collection")
                ->where([
                    'type' => 5,
                    "collection_id" => $request['id'],
                    'user_id' => $request['user_id']
                ])
                ->find();
            $data['collection_id'] = empty($collect['id']) ? "" : $collect['id'];
            $data['collection'] = empty($collect) ? 0 : 1;
        } else {
            $data['collection_id'] = "";
            $data['collection'] = 0;
        }
        $url = 'http://wantaozb.com/home/industry/info?id='.$data['id']."&jinbao=1";
//        $picarr = unserialize($data['picarr']);
//        $data['img1'] = !empty($data['picarr'][0]['img']) ? $data['picarr'][0]['img'] : '';
//        $data['img2'] = !empty($data['picarr'][1]['img']) ? $data['picarr'][1]['img'] : '';
//        $data['img3'] = !empty($data['picarr'][2]['img']) ? $data['picarr'][2]['img'] : '';
//halt($data);
        return $this->fetch(
            'info', [
                'title' => "行业资讯",
                'data' => $data,
                'share'=>$signPackage,
                'url'=>$url,
                'jinbao'=> empty($request['jinbao']) ? 2:$request['jinbao'],
            ]
        );
    }
    /*
      * 行业资讯---我要发布
      */
    public function add(Request $requests, IndustryInformationModel $industryInformation)
    {
        $request = $requests->param();
        if ($requests->isPost()) {
            $request['user_id'] = Session::get('user_id');
            $result = $this->validate($request, 'IndustryInformation.informationAdd');
            if ($result !== true) {
                return [
                    'code' => 400,
                    'message' => $result
                ];
            }
            $res = toImg($request['picarr']);
            if ($res['picarr_type'] == 2 && $res['number'] > 1) {
                return [
                    'code' => 400,
                    'message' => "只能上传单个视频",
                ];
            }
            $request['picarr_type'] = $res['picarr_type'];
            $request['picarr'] = $res['picarr'];
            $data = $industryInformation->allowField(true)->isUpdate(false)->save($request);
            if ($data) {
                return [
                    'code' => 200,
                    'message' => "成功",
                ];
            } else {
                return [
                    'code' => 400,
                    'message' => "成功",
                ];
            }
        } else {
            return $this->fetch(
                'add', [
                    'title' => "发布行业资讯",
                ]
            );
        }
    }
}