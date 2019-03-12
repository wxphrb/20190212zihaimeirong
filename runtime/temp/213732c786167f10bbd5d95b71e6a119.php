<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:91:"D:\phpstudy\WWW\20190213zihaimeirong\public/../application/home\view\user\money_detail.html";i:1551253256;s:87:"D:\phpstudy\WWW\20190213zihaimeirong\public/../application/home\view\public\header.html";i:1551084136;s:87:"D:\phpstudy\WWW\20190213zihaimeirong\public/../application/home\view\public\footer.html";i:1551231545;}*/ ?>
<!--顶部导航-->

<!DOCTYPE html>    <html>    <head>        <meta charset="utf-8">        <meta http-equiv="X-UA-Compatible" content="IE=edge">        <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />        <title><?php echo $title; ?></title>        <link rel="stylesheet" type="text/css" href="__HOME__/css/base.css"/>        <link rel="stylesheet" href="__HOME__/css/swiper.min.css">        <script type="text/javascript" src="__HOME__/js/jquery-1.8.3.min.js"></script>        <script src="__HOME__/js/swiper.min.js"></script>        <script src="__HOME__/js/rem.js"></script>        <script src="__HOME__/js/jquery.validate.js"></script>        <script src="__HOME__/js/jquery.citys.js"></script>        <script src="__HOME__/js/layer/layer.js"></script>        <script src="__PLUGIN__/Fileinput/js/fileinput.js" type="text/javascript"></script>        <script>            $(function(){                var mySwiper = new Swiper('.swiper-container1',{                    pagination: '.swiper-pagination1',                    autoplay:3000,                    loop:true,                    grabCursor: true,                    paginationClickable: true                })            });            function tips(data) {                /*layer.open({                 content: data                 });*/                layer.msg(data);            }            // $(function(){            //     $(".back_btn").click(function(e){            //         //代码段            //         window.history.go(-1);            //         // window.location.href = document.referrer;            //     });            // });        </script>    </head>

<!--顶部导航-->

<body>

<div id="page">

<div class="xian"></div>

<div class="tx_gong">
总佣金<p><?php echo $money; ?>元</p>

</div>
<div class="xian"></div>

<!--一组-->
    <?php if(is_array($data) || $data instanceof \think\Collection): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
        <div class="tx_list">
            <div class="tx_list1"><span>+<?php echo $vo['money']; ?></span></div>
            <div class="tx_list2"><?php echo $vo['create_time']; ?><span>交易成功</span></div>
        </div>
    <?php endforeach; endif; else: echo "" ;endif; ?>












<div class="pb100"></div><!--底部nav开始--><div class="footer">    <ul>        <?php if($mo_foot == 1): ?>            <li id="mo_foot"><a href="<?php echo url('index/index'); ?>"><img  src="__HOME__/images/ft11.png" ><p>首页</p></a></li>        <?php else: ?>            <li><a href="<?php echo url('index/index'); ?>"><img  src="__HOME__/images/ft1.png" ><p>首页</p></a></li>        <?php endif; if($mo_foot == 2): ?>            <li id="mo_foot"><a href="<?php echo url('car/index'); ?>"><img src="__HOME__/images/ft22.jpg"><p>购物车</p></a></li>        <?php else: ?>            <li><a href="<?php echo url('car/index'); ?>"><img src="__HOME__/images/ft2.png"><p>购物车</p></a></li>        <?php endif; if($mo_foot == 3): ?>            <li id="mo_foot"><a href="<?php echo url('user/index'); ?>"><img src="__HOME__/images/ft33.jpg"><p>我的</p></a></li>        <?php else: ?>            <li><a href="<?php echo url('user/index'); ?>"><img src="__HOME__/images/ft3.png"><p>我的</p></a></li>        <?php endif; ?>    </ul>    <div class="clear"></div></div><!--底部nav结束--></div></body></html><script>    function tips(data) {        /*layer.open({         content: data         });*/        layer.msg(data);    }</script>