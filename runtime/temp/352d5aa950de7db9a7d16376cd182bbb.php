<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:88:"D:\phpstudy\WWW\20190213zihaimeirong\public/../application/home\view\login\register.html";i:1554188615;s:87:"D:\phpstudy\WWW\20190213zihaimeirong\public/../application/home\view\public\header.html";i:1551084136;}*/ ?>
<!DOCTYPE html>    <html>    <head>        <meta charset="utf-8">        <meta http-equiv="X-UA-Compatible" content="IE=edge">        <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />        <title><?php echo $title; ?></title>        <link rel="stylesheet" type="text/css" href="__HOME__/css/base.css"/>        <link rel="stylesheet" href="__HOME__/css/swiper.min.css">        <script type="text/javascript" src="__HOME__/js/jquery-1.8.3.min.js"></script>        <script src="__HOME__/js/swiper.min.js"></script>        <script src="__HOME__/js/rem.js"></script>        <script src="__HOME__/js/jquery.validate.js"></script>        <script src="__HOME__/js/jquery.citys.js"></script>        <script src="__HOME__/js/layer/layer.js"></script>        <script src="__PLUGIN__/Fileinput/js/fileinput.js" type="text/javascript"></script>        <script>            $(function(){                var mySwiper = new Swiper('.swiper-container1',{                    pagination: '.swiper-pagination1',                    autoplay:3000,                    loop:true,                    grabCursor: true,                    paginationClickable: true                })            });            function tips(data) {                /*layer.open({                 content: data                 });*/                layer.msg(data);            }            // $(function(){            //     $(".back_btn").click(function(e){            //         //代码段            //         window.history.go(-1);            //         // window.location.href = document.referrer;            //     });            // });        </script>    </head>

<body>

<div id="page">
<div class="logo"><img src="__HOME__/images/logo.jpg"></div>


<div class="p50">

<div class="zhu_fen">
<ul>
    <!--
    <li id="zhu_fen"><a href="javascript:void(0);">手机号绑定</a></li>
    <li><a href="<?php echo url('login/login'); ?>">手机号登陆</a></li>
    -->
</ul>
<div class="clear"></div>
</div>

<div class="biao">
<div class="biao_tu"><img src="__HOME__/images/zhu1.png"></div>
<div class="biao_shu"><input name="mobile" id="mobile" type="text" placeholder="请输入您的手机号"></div>
</div>


<div class="biao">
<div class="biao_tu"><img src="__HOME__/images/zhu2.png"></div>
<div class="biao_shu"><input id="code" type="text" placeholder="请输入动态密码"></div>
    <div class="biao_an"><div style="color: #AF0002; font-size: 0.28rem;" onclick="send();" id='sms'>免费获取验证码</div></div>
</div>

    <div class="biao_tj"><a href="javascript:void(0);" id="submit" onClick="CheckReg();">确定</a></div>

<input type="hidden" id="realvalidate" value="">
</div>

</div>
</body>
</html>
<script>
    var sendable = 0; //全局变量 防止多次点击事件
    function count() {
        $("#sms").attr("disabled", true);
        var sec = 60;
        var intt = setInterval(function jishi() {
                if (sec > 0) {
                    sec--;
                    var str = sec + "秒后重发";
                    $("#sms").html(str);
                }
                else {
                    $("#validate").css("background-color", '#DCDCDC');
                    $("#sms").attr("disabled", false);
                    $('#validate').removeClass('validate1').addClass('validate');
                    $("#sms").html("点击重新发送");
                    sendable = 0;
                    clearInterval(intt);
                }
            }
            , 1000);
    }
    function send() {
        var ckmobile = /^[1][0-9]{10}/;
        var mobile = $("#mobile").val();
        if (!mobile) {
            tips("手机号码为空！");
            $("#mobile").focus();
            return false;
        } else {
            if(!ckmobile.test(mobile)){
                tips("手机号码格式错误！");
                $("#mobile").focus();
                return false;
            }
            if (sendable == 0) {
                sendable = 1;
                $.ajax({
                    type: 'post',
                    url: "<?php echo url('login/getcode'); ?>",
                    data: "mobile=" + mobile + "&type=reg",
                    dataType: 'json',
                    success: function (data) {
                        if (data.code == 200) {
                            console.log(data);
                            $('#validate').removeClass('validate').addClass('validate1');
                            $("#mobile").attr("readonly", true);
                            $("#realvalidate").val(data.codes);
                            count();
                            tips("发送成功。");
                        }
                        if (data['code'] == 400) {
                            sendable = 0;
                            tips(data.message);
                            $("#mobile").val("");
                        }
                    },
                    error: function (err) { //如果执行不成功，那么执行此方法
                        sendable = 0;
                        tips("请求失败，请重试！");
                    }
                });
            }
        }
    }
    var chickable = 0; //全局变量 防止多次点击事件
    function CheckReg() {
        var ckmobile = /^[1][0-9]{10}/;
        var user_phone = $("#mobile").val();
        var code = $("#code").val();
        if(!user_phone){
            tips('手机号码不能为空!');
            $("#mobile").focus();
            return false;
        }
        if(!ckmobile.test(user_phone)){
            tips("手机号码格式错误！");
            $("#mobile").focus();
            return false;
        }
        if(!code){
            tips('短信验证码不能为空!');
            $("#code").focus();
            return false;
        }
        if (chickable == 0) {
            chickable = 1;
            $.ajax({
                type: "POST",     //提交方式
                dataType: "JSON",     //类型
                url: "<?php echo url('Login/register'); ?>",    //提交的页面，方法名
                data:
                "mobile=" + user_phone +"&sms_code="+code,
                success: function (data) { //如果执行成功，那么执行此方法
                    if (data.code == "200") {
                        tips("注册成功");
                        setInterval(function () {
                            window.location.href = "<?php echo url('index/index'); ?>";
                        }, 1000);
                    }
                    if (data.code  == "400") {
                        chickable = 0;
                        tips(data.message);
                    }
                    if (data.code  == "500") {
                        chickable = 0;
                        tips(data.message);
                        setInterval(function () {
                            window.location.href = "<?php echo url('index/index'); ?>";
                        }, 1000);
                    }
                    if (data.data  == "3") {
                        chickable = 0;
                        tips("账号已存在！");
                    }
                },
                error: function (err) { //如果执行不成功，那么执行此方法
                    chickable = 0;
                    tips("注册失败，请重试！");
                }
            });
        }
    }
    </script>