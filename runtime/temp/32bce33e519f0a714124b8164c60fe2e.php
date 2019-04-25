<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:84:"D:\phpstudy\WWW\20190213zihaimeirong\public/../application/console\view\.\login.html";i:1540438804;}*/ ?>
<!DOCTYPE html>

<html lang="zh_cn">

<head>

  <meta charset="utf-8">

  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

  <meta name="description" content="">

  <meta name="author" content="">

  <link rel="shortcut icon" href="__CONSOLE__/images/favicon.png" type="image/png">

  <title>登录-<?php echo \think\Config::get('web_config.web_name'); ?></title>



  <!-- CSS -->

  <link rel="stylesheet" href="__CONSOLE__/css/style.default.css" >

  <link rel="stylesheet" href="__CONSOLE__/css/login/form-elements.css">

  <link rel="stylesheet" href="__CONSOLE__/css/login/style.css">



  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->

  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

  <!--[if lt IE 9]>

      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>

      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>

  <![endif]-->

</head>



<body>

    <!-- Top content -->

    <div class="top-content">      

        <div class="inner-bg">

            <div class="container">

                <div class="row">

                    <div class="col-sm-8 col-sm-offset-2 text">

                        <h1>&nbsp;</h1>

                    </div>

                </div> 

                <div class="row">

                    <div class="col-sm-6 col-sm-offset-3 form-box">

                      <div class="form-top">

                        <div class="form-top-left">

                          <h3><?php echo \think\Config::get('web_config.web_name'); ?>登录</h3>

                            <div class="login_date" id="login_date"><span><samp>:</samp></span></div>

                        </div>

                        <div class="form-top-right">

                          <i class="fa fa-lock"></i>

                        </div>

                        </div>

                        <div class="form-bottom">

                       <form role="form" action="" method="post" class="login-form">

                        <div class="form-group">

                          <label class="sr-only" for="form-username">用户名</label>

                            <input type="text" name="username" placeholder="用户名..." class="form-username form-control" >

                          </div>

                          <div class="form-group">

                            <label class="sr-only" for="form-password">密码</label>

                            <input type="password" name="password" placeholder="密码..." class="form-password form-control" >

                          </div>

                          <button type="submit" class="btn">登录</button>

                      </form>

                    </div>

                    </div>

                </div>

                <!-- <div class="row">

                    <div class="col-sm-6 col-sm-offset-3 social-login">

                      <h3>...</h3>

                      <div class="social-login-buttons">

                        <a class="btn btn-link-2" href="<?php echo url('home/index/index'); ?>" target="_blank">

                          <i class="fa fa-send"></i> 网站

                        </a>

                        <a class="btn btn-link-2" href="http://www.baidu.com/" target="_blank">

                          <i class="fa fa-paw"></i> 百度

                        </a>

                        <a class="btn btn-link-2" href="http://www.longcai.com/" target="_blank">

                          <i class="fa fa-leaf"></i> 龙采

                        </a>

                      </div>

                    </div>

                </div> -->

            </div>

        </div>

        

    </div>





    <!-- Javascript -->

    <script src="__CONSOLE__/js/jquery-1.11.1.min.js"></script>

    <script src="__CONSOLE__/js/bootstrap.min.js"></script>

    <script src="__CONSOLE__/js/jquery.backstretch.min.js"></script>

    <script type="text/javascript">

                    function sx(val)

                    {

                        var urll="<?php echo url('console/login/get_captcha'); ?>";

                        val.src=urll;

                    }

                  </script>

    <!--[if lt IE 10]>

        <script src="assets/js/placeholder.js"></script>

    <![endif]-->

    <script type="text/javascript">

      jQuery(document).ready(function() {

    /*

        Fullscreen background

    */

     $.backstretch([

                "__CONSOLE__/images/backgrounds/2.jpg"

              , "__CONSOLE__/images/backgrounds/3.jpg"

              , "__CONSOLE__/images/backgrounds/1.jpg"

             ], {duration: 3000, fade: 750});



          /*

              Form validation

          */

          $('.login-form input[type="text"], .login-form input[type="password"]').on('focus', function() {

            $(this).removeClass('input-error');

          });

          

          $('.login-form').on('submit', function(e) {

            

            $(this).find('input[type="text"], input[type="password"]').each(function(){

              if( $(this).val() == "" ) {

                e.preventDefault();

                $(this).addClass('input-error');

              }

              else {

                $(this).removeClass('input-error');

              }

            });

            

          });

           document.getElementById('login_date').innerHTML = ShowDate();

            setInterval(function(){

              document.getElementById('login_date').innerHTML = ShowDate();

              },1000);

          

      });

      function ShowDate(){

           var date = new Date(),

            sWeek = new Array("日","一","二","三","四","五","六"),

            month = date.getMonth()+1,

            day = date.getDate(),

            hours = date.getHours(),

            minutes = date.getMinutes(),

            time = null,

            date_str = null;

            minutes = (minutes < 10) ? '0'+minutes : minutes;

            time = hours+"<samp>:</samp>"+minutes;

            date_str = "<span>"+time+"</span>&nbsp;星期"+sWeek[date.getDay()]+"&nbsp;&nbsp;"+month+" - "+day;

            return date_str;

      }



    </script>



</body>



</html>