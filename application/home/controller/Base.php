<?phpnamespace app\home\controller;use think\Db;use think\Request;use think\Url;use think\Controller;use think\Session;use app\home\model\ConfigMd;class Base extends Controller{                    /**                     * [_initialize description]初始化方法                     * @return [type] [description] 权限和菜单控制                     */          protected function _initialize()          {              //获取公共配置信息              $Config		 = new  ConfigMd;              $config_list = $Config->configList();              foreach ( $config_list as $k => $v ) {                  if($v['varname']=='web_name'){                      $this->assign('title', $v['varvalue']);                  }                  $this->assign($v['varname'], $v['varvalue']);              }              //底部默认选中              $this->assign('mo_foot',0);              if(!empty(Session::get('user_id')) && (empty(Session::get('openid')) || empty(Session::get('level_id')))){                    $map = [                        'id' => Session::get('user_id'),                        'status'   => 1                    ];                    $find = db('user')->where($map)->find();                    Session::set('openid', $find['openid']);                    Session::set('level_id', $find['level_id']);              }               /* if (!Session::has('user_id')) {                          $this->redirect('home/login/login');                }                if(Session::get('user_id')){                          $username = Db::name('user')->where('id',Session::get('user_id'))->value('username');                          if($username==''){                                    $this->redirect('home/login/bing_username');                          }                }*/          }}