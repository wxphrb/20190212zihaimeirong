<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:83:"D:\phpstudy\WWW\20190213zihaimeirong\public/../application/home\view\user\info.html";i:1552273260;s:87:"D:\phpstudy\WWW\20190213zihaimeirong\public/../application/home\view\public\header.html";i:1551084136;s:87:"D:\phpstudy\WWW\20190213zihaimeirong\public/../application/home\view\public\footer.html";i:1551231545;}*/ ?>
<!--顶部导航-->
<!DOCTYPE html>    <html>    <head>        <meta charset="utf-8">        <meta http-equiv="X-UA-Compatible" content="IE=edge">        <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />        <title><?php echo $title; ?></title>        <link rel="stylesheet" type="text/css" href="__HOME__/css/base.css"/>        <link rel="stylesheet" href="__HOME__/css/swiper.min.css">        <script type="text/javascript" src="__HOME__/js/jquery-1.8.3.min.js"></script>        <script src="__HOME__/js/swiper.min.js"></script>        <script src="__HOME__/js/rem.js"></script>        <script src="__HOME__/js/jquery.validate.js"></script>        <script src="__HOME__/js/jquery.citys.js"></script>        <script src="__HOME__/js/layer/layer.js"></script>        <script src="__PLUGIN__/Fileinput/js/fileinput.js" type="text/javascript"></script>        <script>            $(function(){                var mySwiper = new Swiper('.swiper-container1',{                    pagination: '.swiper-pagination1',                    autoplay:3000,                    loop:true,                    grabCursor: true,                    paginationClickable: true                })            });            function tips(data) {                /*layer.open({                 content: data                 });*/                layer.msg(data);            }            // $(function(){            //     $(".back_btn").click(function(e){            //         //代码段            //         window.history.go(-1);            //         // window.location.href = document.referrer;            //     });            // });        </script>    </head>

<body>

<div id="page">




<div class="danxx_ti">提现说明</div>
<div class="danxx">

<?php echo $take_money_explain; ?>

</div>
</div>



<div class="pb100"></div><!--底部nav开始--><div class="footer">    <ul>        <?php if($mo_foot == 1): ?>            <li id="mo_foot"><a href="<?php echo url('index/index'); ?>"><img  src="__HOME__/images/ft11.png" ><p>首页</p></a></li>        <?php else: ?>            <li><a href="<?php echo url('index/index'); ?>"><img  src="__HOME__/images/ft1.png" ><p>首页</p></a></li>        <?php endif; if($mo_foot == 2): ?>            <li id="mo_foot"><a href="<?php echo url('car/index'); ?>"><img src="__HOME__/images/ft22.jpg"><p>购物车</p></a></li>        <?php else: ?>            <li><a href="<?php echo url('car/index'); ?>"><img src="__HOME__/images/ft2.png"><p>购物车</p></a></li>        <?php endif; if($mo_foot == 3): ?>            <li id="mo_foot"><a href="<?php echo url('user/index'); ?>"><img src="__HOME__/images/ft33.jpg"><p>我的</p></a></li>        <?php else: ?>            <li><a href="<?php echo url('user/index'); ?>"><img src="__HOME__/images/ft3.png"><p>我的</p></a></li>        <?php endif; ?>    </ul>    <div class="clear"></div></div><!--底部nav结束--></div></body></html><script>    function tips(data) {        /*layer.open({         content: data         });*/        layer.msg(data);    }</script></html>