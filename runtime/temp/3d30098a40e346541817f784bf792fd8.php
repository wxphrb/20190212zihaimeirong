<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:87:"D:\phpstudy\WWW\20190213zihaimeirong\public/../application/shop\view\my_team\index.html";i:1554704165;s:88:"D:\phpstudy\WWW\20190213zihaimeirong\public/../application/shop\view\template\index.html";i:1540438923;s:90:"D:\phpstudy\WWW\20190213zihaimeirong\public/../application/shop\view\template\sidebar.html";i:1540438922;s:89:"D:\phpstudy\WWW\20190213zihaimeirong\public/../application/shop\view\template\topbar.html";i:1540438923;s:89:"D:\phpstudy\WWW\20190213zihaimeirong\public/../application/shop\view\template\footer.html";i:1554692403;}*/ ?>
<!DOCTYPE html>
<html lang="zh_cn">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="__CONSOLE__/images/favicon.png" type="image/png">

  <title><?php echo $mate_title; ?>-<?php echo \think\Config::get('web_config.web_name'); ?></title>
  
  <link href="__CONSOLE__/css/style.default.css" rel="stylesheet">
  <!--   sweetalert弹出样式 -->
  <link href="__PLUGIN__/sweetalert/css/sweetalert.css" rel="stylesheet">
  <link href="__CONSOLE__/css/jquery.datatables.css" rel="stylesheet">
  
  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="__CONSOLE__/js/html5shiv.js"></script>
  <script src="__CONSOLE__/js/respond.min.js"></script>
  <![endif]-->
  
</head>
<body>
<!-- Preloader -->
<div id="preloader">
    <div id="status"><i class="fa fa-spinner fa-spin"></i></div>
</div>
<section>
  

  <div class="leftpanel">

    <div class="logopanel">
        <h1><span>[</span> 管理中心 <span>]</span></h1>
    </div><!-- logopanel -->

    <div class="leftpanelinner">

        <!-- This is only visible to small devices -->
        <div class="visible-xs hidden-sm hidden-md hidden-lg">
            <div class="media userlogged">
                <img alt="" src="__CONSOLE__/images/photos/user<?php echo $user['avatar']; ?>.jpg" class="media-object">
                <div class="media-body">
                    <h4><?php echo $user['username']; ?></h4>
                    <span><?php echo $user['nickname']; ?></span>
                </div>
            </div>
            <ul class="nav nav-pills nav-stacked nav-bracket mb30">
              <li><a href="#" data-toggle="modal" data-target=".bs-myinfo-modal-lg"><i class="fa fa-user"></i> <span>我的信息</span></a></li><!--
              <li><a href=""><i class="fa fa-cog"></i> <span>账号设置</span></a></li>
              <li><a href=""><i class="fa fa-question-circle"></i> <span>帮助</span></a></li> -->
              <li><a onClick="logout('<?php echo url('Shop/login/logout'); ?>');"><i class="fa fa-sign-out"></i> <span>退出</span></a></li>
            </ul>
        </div>
      <ul class="nav nav-pills nav-stacked nav-bracket">
              <?php if(is_array($menu_left) || $menu_left instanceof \think\Collection): $i = 0; $__LIST__ = $menu_left;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;if($menu['set'] == 1): ?>
                     <li class="<?php if($menu['active'] == 1): ?>active<?php endif; ?>">
                        <a href="<?php echo $menu['low']['0']['url']; ?>"><i class="<?php echo $menu['class']; ?>"></i> <span><?php echo $menu['low']['0']['keyname']; ?></span></a>                           
                      </li>
                     <?php else: ?>
                    <li class="nav-parent <?php if($menu['active'] == 1): ?> active<?php endif; ?>">
                        <a ><i class="<?php echo $menu['class']; ?>"></i> <span><?php echo $menu['title']; ?></span></a>
                            <ul class="children" <?php if($menu['active'] == 1): ?>style="display:block;"<?php else: ?>style="display:none;"<?php endif; ?>>
                                 <?php if(is_array($menu['low']) || $menu['low'] instanceof \think\Collection): $i = 0; $__LIST__ = $menu['low'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu_2): $mod = ($i % 2 );++$i;?>   
                                      <li <?php if($menu_2['active'] == 1): ?>class="active"<?php endif; ?>>
                                          <a href="<?php echo $menu_2['url']; ?>"><i class="fa fa-caret-right"></i> <?php echo $menu_2['keyname']; ?></a>
                                      </li>
                                 <?php endforeach; endif; else: echo "" ;endif; ?>
                            </ul>  
                      </li>
                      <?php endif; endforeach; endif; else: echo "" ;endif; ?>
      </ul>
  

    </div><!-- leftpanelinner -->
  </div><!-- leftpanel -->
  
  <div class="mainpanel">
    
     <div class="headerbar">

      <a class="menutoggle"><i class="fa fa-bars"></i></a>

      <div class="header-right">
        <ul class="headermenu">
          <li>
            <div class="btn-group">
            <!--<a href="<?php echo url('Home/index/index'); ?>" target="_blank">-->
              <!--<button class="btn btn-default tp-icon" title="预览网站">-->
                <!--<i class="fa fa-send"></i>-->
              <!--</button>-->
              <!--</a>-->
            </div>
          </li>
          <li>
            <div class="btn-group">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                <img src="__CONSOLE__/images/photos/user<?php echo $user['avatar']; ?>.jpg" alt="">
                <?php echo $user['username']; ?>
                <span class="caret"></span>
              </button>
              <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                <!--<li><a href="#" data-toggle="modal" data-target=".bs-myinfo-modal-lg"><i class="fa fa-user"></i> 我的信息</a></li>-->
                <!--<li><a href="#"><i class="glyphicon glyphicon-cog"></i> 账号设置</a></li>
                <li><a href="#"><i class="glyphicon glyphicon-question-sign"></i> 帮助</a></li> -->
                <li><a onClick="logout('<?php echo url('Shop/login/logout'); ?>');"><i class="fa fa-sign-out"></i> 退出</a></li>
              </ul>
            </div>
          </li>
        </ul>
      </div><!-- header-right -->

    </div><!-- headerbar -->


   
    
    <div class="pageheader">
      <h2><i class="fa fa-list-ul"></i> <?php echo $mate_title; ?> <span><?php echo $mate_operate; ?></span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">现在所在位置:</span>
        <ol class="breadcrumb">
          <li><?php echo $mate_title; ?> </li>
          <li class="active"><?php echo $mate_operate; ?></li>
        </ol>
      </div>
    </div>
    <div class="contentpanel">
         

<div class="panel panel-default">

    <!--<div class="panel-heading" style="border-bottom:none;">-->

        <!--<div>-->

            <!--<a class="btn btn-primary" href="<?php echo url('shop/User/create'); ?>" style="float:right;">新增账号</a>-->

        <!--</div>-->

        <!--<h4 class="panel-title"><?php echo $mate_operate; ?></h4>-->

    <!--</div>-->

    <div class="panel-body">

        <div style="margin-left: -10px;">

            <form action="" method="post" enctype="multipart/form-data">

                <div class="col-lg-3">

                    <input type="text" name="name" value="<?php echo isset($name) ? $name :  ''; ?>" class="form-control" placeholder="名称">

                </div>

                <input type="submit" class="btn btn-primary" value="搜索">

            </form>

        </div>

        <br>

        <div class="table-responsive">

            <table class="table table-striped table-bordered table-hover" id="table1">

                <thead>

                <tr>

                    <th>ID</th>

                    <th>店铺名称</th>

                    <th>老板姓名</th>

                    <th>手机号</th>

                    <th>代理商级别</th>

                    <th>采购货款总额</th>

                    <th>创建时间</th>

                    <!--<th>状态</th>-->

                    <!--<th>操作</th>-->

                </tr>

                </thead>

                <tbody>

                <?php if(is_array($list) || $list instanceof \think\Collection): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>

                <tr>

                    <td><?php echo $vo['id']; ?></td>

                    <td><?php echo $vo['title']; ?></td>

                    <td><?php echo $vo['manned']; ?></td>

                    <td><?php echo $vo['mobile']; ?></td>

                    <td><?php echo level_name($vo['level_id']); if($vo['star_lv'] != 0): ?>（<?php echo $vo['star_lv']; ?>）<?php endif; ?></td>
                    <td><?php echo $vo['all_money']; ?></td>

                    <td><?php echo date('Y-m-d H:i:s',$vo['create_time']); ?></td>


                    </td>

                    <!--<td>-->

                        <!--<?php echo show_operate($vo['id'],'update','修改身份'); ?>-->

                        <!--&nbsp;&nbsp;&nbsp;&nbsp;-->

                        <!--&lt;!&ndash;<?php echo show_operate($vo['id'],'delete','删 除'); ?>&ndash;&gt;-->



                        <!--&lt;!&ndash;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo url('user/approve',['id'=>$vo['id']]); ?>">级别</a>&ndash;&gt;-->

                    <!--</td>-->

                </tr>

                <?php endforeach; endif; else: echo "" ;endif; ?>

                </tbody>

            </table>



        </div>


    </div>

</div>

</div><!-- rightpanel -->




         <div id="renew_submit" style="display: none;"></div>
    </div><!-- contentpanel -->
  </div><!-- mainpanel -->
  
  
</section>

<script src="__CONSOLE__/js/jquery-1.11.1.min.js"></script>
<script src="__CONSOLE__/js/jquery-migrate-1.2.1.min.js"></script>
<script src="__CONSOLE__/js/bootstrap.min.js"></script>
<script src="__CONSOLE__/js/modernizr.min.js"></script>
<script src="__CONSOLE__/js/jquery.sparkline.min.js"></script>
<script src="__CONSOLE__/js/toggles.min.js"></script>
<script src="__CONSOLE__/js/retina.min.js"></script>
<script src="__CONSOLE__/js/jquery.cookies.js"></script>

<script src="__CONSOLE__/js/select2.min.js"></script>
<script src="__CONSOLE__/js/custom.js"></script>
<script src="__PLUGIN__/sweetalert/js/sweetalert.min.js"></script>

<script src="__CONSOLE__/js/jquery.datatables.min.js"></script>
<script src="__CONSOLE__/js/listing.js"></script><!-- 列表页处理js 手写 -->
<!-- 验证 -->
<script src="__CONSOLE__/js/bootstrapValidator.js"></script>




<script>

    jQuery(document).ready(function () {

        "use strict";

        jQuery('#table11').DataTable({

            responsive: true,

            "stateSave": true,

            "language": {

                "lengthMenu": "每页 _MENU_ 条记录<font id='loader' style='margin-left:50px;'></font>",

                "zeroRecords": "<div style='font-size:24px;margin:30px;'>没有找到记录</div>",

                "info": "第 _PAGE_ 页 ( 总共 _PAGES_ 页 )",

                "infoEmpty": "无记录",

                "infoFiltered": "(从 _MAX_ 条记录过滤)",

                "sSearch": "搜索",

                "paginate": {

                    "previous": "上一页",

                    "next": "下一页"

                }

            },

            /* "columnDefs":[{

             orderable:false,//禁用排序

             targets:[0]   //指定的列

             }],*/

        });

        // Select2

        jQuery('select').select2({

            minimumResultsForSearch: -1

        });



    });

</script>



 <!-- 我的信息弹出 -->

    <div class="modal fade bs-myinfo-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

      <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>

                <h4 class="modal-title">我的信息</h4>

            </div>

            <div class="modal-body">

             <form class="form-horizontal form-bordered" id="myinfoForm" action="<?php echo url('shop/login/myinfo'); ?>" method="post" >

                 <div class="form-group">

                  <label class="col-sm-3 control-label">用户名:</label>

                  <div class="col-sm-6">

                      <input type="text" class="form-control" name="username" placeholder="请输入用户名"  value="<?php echo isset($user['username']) ? $user['username'] :  ''; ?>" readonly>

                  </div>

              </div>

              <div class="form-group">

                  <label class="col-sm-3 control-label">密码:</label>

                  <div class="col-sm-6">

                      <input type="password" class="form-control" name="password" placeholder="请输入密码">

                  </div>

              </div>

              <div class="form-group">

                  <label class="col-sm-3 control-label">确认密码:</label>

                  <div class="col-sm-6">

                      <input type="password" class="form-control" name="repassword" placeholder="请输入确认密码">

                  </div>

              </div>

               <div class="form-group">

                  <label class="col-sm-3 control-label">昵称:</label>

                  <div class="col-sm-6">

                      <input type="text" class="form-control" name="nickname" placeholder="请输入昵称"  value="<?php echo isset($user['nickname']) ? $user['nickname'] :  ''; ?>" required>

                  </div>

              </div>

              <div class="form-group">

                  <label class="col-sm-3 control-label">手机号:</label>

                  <div class="col-sm-6">

                      <input type="text" class="form-control" name="mobile" placeholder="请输入手机号"  value="<?php echo isset($user['mobile']) ? $user['mobile'] :  ''; ?>" required>

                  </div>

              </div>

               <div class="form-group">

                    <label class="col-sm-3 control-label">头像选择:</label>

                    <div class="col-sm-6 avatar-size">

                        <input type="hidden" name="avatar" value="<?php echo isset($user['avatar']) ? $user['avatar'] :  1; ?>" id="avatar2">

                        <?php $__FOR_START_3959__=1;$__FOR_END_3959__=9;for($i=$__FOR_START_3959__;$i < $__FOR_END_3959__;$i+=1){ ?>

                        <img src="__CONSOLE__/images/photos/user<?php echo $i; ?>.jpg" class="img-circle" onClick="chooseImg2(<?php echo $i; ?>);" myflagImg="<?php echo $i; ?>">

                        <?php } ?>

                    </div>

                </div>



                    <div class="panel-footer">

                     <div class="row">

                      <div class="col-sm-6 col-sm-offset-3">

                        <input type="hidden" name="id" value="<?php echo isset($user['id']) ? $user['id'] :  ''; ?>">

                        <button type="submit" class="btn btn-primary">提交</button>

                        <button type="button" class="btn btn-default" aria-hidden="true" data-dismiss="modal" class="close">关闭</button>

                      </div>

                     </div>

                    </div><!-- panel-footer -->



                </form>

            </div>

        </div>

      </div>

    </div>

    <!-- 我的信息弹出 --> 

    <script>

         $(document).ready(function() {

            $("[myflagImg='<?php echo isset($user['avatar']) ? $user['avatar'] :  '1'; ?>']").addClass("choosed");

            $('#myinfoForm').bootstrapValidator({

                message: '这个值是无效的',

                feedbackIcons: {

                    valid: 'glyphicon glyphicon-ok',

                    invalid: 'glyphicon glyphicon-remove',

                    validating: 'glyphicon glyphicon-refresh'

                },

                 fields: {

                    password: {

                        validators: {

                           identical: {

                                field: 'repassword',

                                message: '密码和确认密码不一致'

                            }

                        }

                    },

                    repassword: {

                        validators: {

                            identical: {

                                field: 'password',

                                message: '密码和确认密码不一致'

                            }

                        }

                    },

                    nickname: {

                        validators: {

                            stringLength: {

                                min: 5,

                                max: 30,

                                message: '昵称长度5~30之间'

                            },

                            remote: {

                                url: "<?php echo url('shop/common/validonly',['table'=>'manager']); ?>",

                                message: '昵称已经存在'

                            }

                        }

                    },

                    mobile: {

                        validators: {

                            regexp: {

                                regexp: /^1(3[0-9]|4[57]|5[0-35-9]|7[0135678]|8[0-9])\d{8}$/,//手机号正则表达式

                                message: '手机号格式不正确'

                            },

                            remote: {

                                url: "<?php echo url('shop/common/validonly',['table'=>'manager']); ?>",

                                message: '手机号已经存在'

                            }

                        }

                    }

                }

            });

        });

         //选择头像效果

      function chooseImg2(num){

        $('#avatar2').val(num);

        $("[myflagImg='"+num+"']").siblings().removeClass('choosed').end().addClass("choosed");

      }

      function logout(url){

          swal({   

            title: "确定要退出?",   

            text: "你将退出管理后台!",   

            type: "warning",   

            showCancelButton: true,    

            confirmButtonText: "确认", 

            cancelButtonText: '取消',

            closeOnConfirm: false

          }, function(){ 

             top.location=url;   

             return true;

          });

      }

    </script>
</body>
</html>