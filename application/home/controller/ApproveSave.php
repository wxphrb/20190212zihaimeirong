<?php
namespace app\home\controller;
use app\home\model\Banner;
use app\home\model\Certification;
use app\home\model\Goods;
use app\home\model\Store;
use think\Controller;
use think\Db;
use think\Request;
use think\Session;

class ApproveSave extends Controller
{
    /*
     * 认证
     * */
	public function save(Request $request,Certification $certification){
	    if($request->isPost()){
            $data = $request->param();

            $data['picurl'] = ossUpload($_FILES['picurl'])['img'];

            $picarr[0]['img'] = ossUpload($_FILES['picarr1'])['img'];
            $picarr[1]['img'] = ossUpload($_FILES['picarr2'])['img'];
            $picarr[2]['img'] = ossUpload($_FILES['picarr3'])['img'];

            $data['picarr'] = serialize($picarr);

            $data['user_id'] = Session::get('user_id');
            $data['create_time'] = time();
            $where = ['user_id'=>Session::get('user_id')];

            $res = $certification->where($where)->find();
            if($res){
                if($certification->where($where)->update($data)){
                    return ['code' => 200,];
                }else{
                    return ['code' => 400];
                }
            }else{
                if($certification->insert($data)){
                    return ['code' => 200];
                }else{
                    return ['code' => 400];
                }
            }
        }else{
            $type =   Db::name("certification_type")->where('id','<>',0)->where('status',0)->select();
            return $this->fetch(
                'save',[
                    'title'=>"我的认证",
                    'type' => $type,
                ]
            );
        }
	}


    public function index(Request $request){
        $type =   Db::name("certification_type")->where('id','<>',0)->where('status',0)->select();
        $list = Db::name('certification')->where(['user_id'=>Session::get('user_id')])->find();
        $list['title'] = Db::name('certification_type')->where(['id'=>$list['certification_type']])->value('title');
       $parr = unserialize ($list['picarr']);
       $list['img1'] = $parr[0]['img'];
       $list['img2'] = $parr[1]['img'];
       $list['img3'] = $parr[2]['img'];

        return $this->fetch('index',[
               'title'=>"我的认证",
               'type' => $type,
               'list' => $list,
               'status' => $request->param('status'),
           ]
       );
    }
}