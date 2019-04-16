<?phpnamespace app\home\controller;use app\home\model\User;use think\Cache;use think\Controller;use app\home\model\IntegralInfo;use think\Db;use think\Request;use think\Url;use think\Session;use app\home\model\Store as StoreModel;use mrmiao\encryption\RSACrypt;class Login extends Controller{     public function login(User $user,Request $requests)    {        if (Request::instance()->isPost()) {            $map = [                'mobile' => $requests->param('mobile'),                'status'   => 1            ];            $request = $requests->param();            $result  = $this->validate($request, 'Login.login');            if($result !== true){                return ['code'=>500,'message'=>$result,'ceshi'=>$request];            }            $find = $user->where($map)->find();            // 用户名&密码 验证成功            if ($find) {                Session::set('user_id', $find['id']);                Session::set('openid', $find['openid']);                Session::set('level_id', $find['level_id']);                return ['code'=>200,'username'=>$find['username']];            }else{                return ['code'=>400];            }        }        return $this->fetch("login",[            'title'=>"登录",        ]);    }     //登陆之后检验用户名为空，要去强制绑定用户名     public function bing_username(User $user,Request $requests)     {               if (Request::instance()->isPost()) {                   $request = $requests->param();                         $request['user_id'] = Session::get('user_id');                         $data = $user->where('id',$request['user_id'])->update(['username'=>$request['username']]);                         // 用户名&密码 验证成功                         if ($data) {                                   return ['code'=>200];                         }else{                                   return ['code'=>400];                         }               }               return $this->fetch("bing_username",[                         'title'=>"绑定用户名",               ]);     }     //微信登录用户绑定手机号   -- 暂未使用    public function bing_tel(Request $request,User $user){         if($request->isPost()){             $insert = $user->where(['mobile'=>$request->param('mobile')])->setField(['openid'=>Session::get('openid')]);             if($insert){                 $id = $user->where(['openid'=>Session::get('openid')])->value('id');                 Session::set('user_id',$id);                 return ['code' => 200];             }else{                 return ['code' => 400];             }         }else{             return $this->fetch('bing_tel',[                 'title' => '信息绑定',             ]);         }    }         /*绑定*/    public function register(User $user ,Request $requests){        $openid = Session::get('openid');        if(empty($openid)){            //$this->error('请返回首页，进行微信授权','index/index');            //return ['code' => 400, 'message' => '请返回首页，进行微信授权！'];        }        if (Request::instance()->isPost()) {                $request = $requests->param();                $user_id = Session::get('user_id');                $ftype = isset($request['ftype']) ? $request['ftype'] : '';                $result = $this->validate($request, 'Login.regist');                if ($result !== true) {                    return [                        'code' => 400,                        'message' => $result                    ];                }                /* 邀请码                if (!empty($request['invited_code'])) {                    $request['invited_id'] = $user->where(['icode' => $request['invited_code'], 'status' => 1])->value('id');                    if (empty($request['invited_id'])) {                        return ['code' => 400, 'message' => '邀请人不存在'];                    }                }                 */                //$request['password'] = md5(md5($request['password']));                //$data    = $user->where('openid',$openid)->update($request);                //获取后台添加的账号                $check_tmp = 0;                $user_tmp  = db('user_tmp')->where(['mobile'=>$request['mobile'],'status'=>0])->field('level_id,password')->find();                if($user_tmp){                    $check_tmp                  = 1;                    $request['temporary_level'] = $user_tmp['level_id'];//添加用户临时登记id                    $request['password']        = md5(md5($user_tmp['password']));                }                $data    = $user->where('id',$user_id)->update($request);                if ($data) {                    /*                    if (!empty($request['invited_id'])) {                        $reward = Db::name('webconfig')->where('varname', 'web_invited')->value('varvalue');//推荐注册奖励积分                        $user->where('id', $request['invited_id'])->setInc('integral', $reward);//给邀请人增加积分                    }*/                    if($check_tmp){                        db('user_tmp')->where(['mobile'=>$request['mobile'],'status'=>0])->update(['status'=>1,'update_time'=>time()]);                    }                    return ['code' => 200, 'message' => '绑定成功！','ftype' => $ftype];                } else {                    return ['code' => 400, 'message' => '绑定失败！'];                }        }        return $this->fetch("register",[            'title'=>"绑定手机号",        ]);	}          /*                    * 忘记密码                    * 参数：用户名 密码                    * */          public function forget(Request $requests, User $user, IntegralInfo $info)          {                    return $this->fetch("forget1",[                              'title'=>"忘记密码",                    ]);          }          /*                  * 忘记密码                  * 参数：用户名 密码                  * */          public function forget2(Request $requests, User $user, IntegralInfo $info)          {                    $request = $requests->param();                    if($requests->isPost()){                              $result = $this->validate($request, 'Login.forgetPassMobile');  //验证参数                              if ($result !== true) {                                        return ['code' => 400, 'message' => $result];                              }                              $request['password'] = md5(md5($request['password']));                              $data = $user->where(['mobile'=>$request['mobile']])->update(['password'=> $request['password']]);                              if ($data) {                                        return ['code' => 200, 'message' => '修改成功！'];                              } else {                                        return ['code' => 400, 'message' => '修改失败！'];                              }                    }else{                              $request = $requests->param();                              return $this->fetch("forget2",[                                        'title'=>"忘记密码",                                        'data'=>$request                              ]);                    }          }    /*获取验证码*/    public function getcode(RSACrypt $crypt)    {        $request = $crypt->request();        $code = Cache::get('sms_verify_' . $request['type'] . '_time_' . $request['mobile']);        if ($code) {            return $crypt->response(['code' => 400, 'message' => '操作频繁，请稍后再发']);        }        switch ($request['type']) {            case 'reg'://绑定获取验证码;                $id = Db::name('user')->where(['mobile' => $request['mobile']])->value('id');                if ($id) return $crypt->response(['code' => 400, 'message' => '该手机号已被绑定']);                break;            case 'find'://找回密码 或 验证码登录   获取验证码；                $id = Db::name('user')->where(['mobile' => $request['mobile']])->value('id');                if (!$id) return $crypt->response(['code' => 400, 'message' => '用户未注册']);                break;            case 'forget'://忘记密码；                $id = Db::name('user')->where(['mobile' => $request['mobile']])->value('id');                if (!$id) return $crypt->response(['code' => 400, 'message' => '用户未注册']);                break;            case 'upshop'://开店                $id = Db::name('user')->where(['mobile' => $request['mobile']])->value('id');                if (empty($id)) return $crypt->response(['code' => 400, 'message' => '用户未注册']);                break;            case 'bind'://绑定获取验证码;                $user = Db::name('user')->where(['mobile' => $request['mobile']])->find();                if($user){                    if(!empty($user['openid'])){                        return $crypt->response(['code' => 400, 'message' => '用户已注册']);                    }                }                break;            default:                return $crypt->response(['code' => 422, 'message' => 'TYPE_ERROR']);                break;        }        $codes = rand(100000, 999999);        Cache::set('sms_verify_' . $request['type'] . '_time_' . $request['mobile'], $codes, 60); // 短信请求有效期        Cache::set('sms' . $request['type'] . '_code' . $request['mobile'], $codes, 900);     // 短信保存有效期        header('Content-Type: text/plain; charset=utf-8');        $ch = curl_init();        $post_data = array(            "account" => "",            "password" => "",            "destmobile" => $request['mobile'],            "msgText" => "尊敬用户,您的验证码为" . $codes . "【资海美容】" ,            "sendDateTime" => ""        );        curl_setopt($ch, CURLOPT_HEADER, false);        curl_setopt($ch, CURLOPT_POST, true);        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);        $post_data = http_build_query($post_data);        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);        curl_setopt($ch, CURLOPT_URL, 'http://www.jianzhou.sh.cn/JianzhouSMSWSServer/http/sendBatchMessage');        if (curl_exec($ch) > 0)            curl_close($ch);        return $crypt->response(['code' => 200, 'message' => '发送成功', 'data' => ['phone' => $request['mobile'], 'code' => $codes]]);    }    public function logout(){        Session::delete('user_id');        $this->redirect('home/login/login');    }    /*     * 微信登录     * */    public function wxLogin(RSACrypt $crypt, User $user)    {        $request = $crypt->request();        $result = $this->validate($request, 'Login.wxLogin');  //验证参数        if ($result !== true) {            return $crypt->response(['code' => 400, 'message' => $result]);        }        $openid = json_decode($this->get_contents('https://api.weixin.qq.com/sns/userinfo?access_token=' . $request['token'] . '&openid=' . $request['openid']), true);//        halt($openid);        if (!empty($openid['errcode'])) return $crypt->response(['code' => 400, 'message' => '登录失败']);        $data = $user->where('openid', $request['openid'])->find();        if (empty($data)) {//第一次登录            $arr['username'] = $openid['nickname'];            $arr['openid'] = $request['openid'];            $arr['picurl'] = $openid['headimgurl'];            $add = $user->allowField(true)->save($arr);            $data = ['id' => $user->id, 'mobile' => ''];            if ($add) {                return $crypt->response(['code' => 200, 'message' => '登录成功！', 'data' => $data]);            } else {                return $crypt->response(['code' => 400, 'message' => '登录失败！', 'data' => $user->id]);            }        } else {//以前登录过            $data = $user->where(["openid" => $request['openid'], 'status' => 1])->field('id,mobile')->find();            if (empty($data)) {                return $crypt->response(['code' => 400, "message" => "账号冻结"]);            }            return $crypt->response(['code' => 200, "message" => "登录成功", "data" => $data]);        }    }    public function get_contents($url)    {        $ch = curl_init();        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);        curl_setopt($ch, CURLOPT_URL, $url);        $response = curl_exec($ch);        curl_close($ch);        return $response;    }}