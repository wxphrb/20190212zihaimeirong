<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:86:"D:\phpstudy\WWW\20190213zihaimeirong\public/../application/home\view\index\joinin.html";i:1551942893;s:87:"D:\phpstudy\WWW\20190213zihaimeirong\public/../application/home\view\public\header.html";i:1551084136;}*/ ?>
<!DOCTYPE html>    <html>    <head>        <meta charset="utf-8">        <meta http-equiv="X-UA-Compatible" content="IE=edge">        <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />        <title><?php echo $title; ?></title>        <link rel="stylesheet" type="text/css" href="__HOME__/css/base.css"/>        <link rel="stylesheet" href="__HOME__/css/swiper.min.css">        <script type="text/javascript" src="__HOME__/js/jquery-1.8.3.min.js"></script>        <script src="__HOME__/js/swiper.min.js"></script>        <script src="__HOME__/js/rem.js"></script>        <script src="__HOME__/js/jquery.validate.js"></script>        <script src="__HOME__/js/jquery.citys.js"></script>        <script src="__HOME__/js/layer/layer.js"></script>        <script src="__PLUGIN__/Fileinput/js/fileinput.js" type="text/javascript"></script>        <script>            $(function(){                var mySwiper = new Swiper('.swiper-container1',{                    pagination: '.swiper-pagination1',                    autoplay:3000,                    loop:true,                    grabCursor: true,                    paginationClickable: true                })            });            function tips(data) {                /*layer.open({                 content: data                 });*/                layer.msg(data);            }            // $(function(){            //     $(".back_btn").click(function(e){            //         //代码段            //         window.history.go(-1);            //         // window.location.href = document.referrer;            //     });            // });        </script>    </head>

<body>

<div id="page">
<img src="__HOME__/images/datu2.jpg">
<form action="<?php echo url('index/message'); ?>" id="form" method="post" onsubmit="return checkform();">
<div class="meng_biao">
<input name="username" id="username" type="text" placeholder="您的姓名">
</div>

<div class="meng_biao">
<input name="mobile" id="mobile" type="text" placeholder="您的手机号码">
</div>

<div class="meng_biao">
    <select name="level_id" id="type">
        <option value="0">申请加盟身份</option>
        <option value="1">共享天使</option>
        <option value="2">共享店主</option>
        <option value="3">城市合伙人</option>
        <option value="4">联合创始人</option>
    </select>
    </div>
<div class="meng_tj">
    <input name="" type="submit" value="提交表单">
</div>
</form>
<div class="er"><img src="<?php echo $kf_ercode; ?>"><p>客服微信二维码</p></div>
<div class="pb100"></div>
</div>
</body>
</html>
<script>
    /*
    var cnmsg = {
        required: '该内容不允许为空',
        remote: '请修正该字段',
        email: '请输入正确格式的电子邮件',
        url: '请输入合法的网址',
        date: '请输入合法的日期',
        dateISO: '请输入合法的日期 (ISO).',
        number: '请输入合法的数字',
        digits: '只能输入整数',
        creditcard: '请输入合法的信用卡号',
        equalTo: '请再次输入相同的值',
        accept: '请输入拥有合法后缀名的字符串',
        maxlength: jQuery.format('请输入一个长度最多是 {0} 的字符串'),
        minlength: jQuery.format('请输入一个长度最少是 {0} 的字符串'),
        rangelength: jQuery.format('请输入一个长度介于 {0} 和 {1} 之间的字符串'),
        range: jQuery.format('请输入一个介于 {0} 和 {1} 之间的值'),
        max: jQuery.format('请输入一个最大为 {0} 的值'),
        min: jQuery.format('请输入一个最小为 {0} 的值')
    };
    jQuery.extend(jQuery.validator.messages, cnmsg);

    $.validator.addMethod("doubles",function(value,element,params){
        var doubles= /^1[3|4|5|7|8][0-9]{9}$/;
        return this.optional(element)||(doubles.test(value));
    },"请输入一个正确的手机号");

    $("#form").validate({
        rules:{
            username:{
                required:true,

            },
            mobile:{
                required:true,
            },
        },
        messages: {
            username: {
                required: "请填写您的姓名"
            },
            mobile: {
                required: "请填写您的手机号"
            },
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight:function(element, errorClass, validClass) {
            $(element).parents('.control-group').addClass('error');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents('.control-group').removeClass('error');
            $(element).parents('.control-group').addClass('success');
        }
    });*/
    function checkform(){
        var status = false;
        var check  = 0;
        username = $('#username').val();
        mobile   = $('#mobile').val();
        type     = $('#type').val();
        var reg =/^0?1[3|4|5|6|7|8|9][0-9]\d{8}$/;


        if(username==''){
            tips('您的姓名不能为空');
            $('#username').focus();
            return false;
        }
        if(mobile=='' || !reg.test(mobile)){
            tips('您的手机号码输入有误');
            $('#mobile').focus();
            return false;
        }
        if(type==0){
            tips('请选择申请的加盟身份');
            return false;
        }

        /*
        $('.help-inline').each(function(){
            help =  $(this).css('display');
            if(help != 'undefined' && help != 'none'){
                status = false;
                check  = 1;
                return false
            }else{
                check  = 0;
                status = true;
            }

        });

        if(!status){
            return false;
        };


        city_css = $('#city').css('display');
        area_css = $('#area').css('display');
        if(city_css == "inline-block"){
            city_val = $('#city').val();
            if(city_val ==""){
                tips('请选择完整地址信息');
                return false;
            }
        }
        if(area_css == "inline-block"){
            area_val = $('#area').val();
            if(area_val ==""){
                tips('请选择完整地址信息');
                return false;
            }
        }
         */
    }
</script>