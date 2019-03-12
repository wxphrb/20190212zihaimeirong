<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:93:"D:\phpstudy\WWW\20190213zihaimeirong\public/../application/home\view\user\extract_record.html";i:1552274752;s:87:"D:\phpstudy\WWW\20190213zihaimeirong\public/../application/home\view\public\header.html";i:1551084136;}*/ ?>
<!--顶部导航-->
<!DOCTYPE html>    <html>    <head>        <meta charset="utf-8">        <meta http-equiv="X-UA-Compatible" content="IE=edge">        <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />        <title><?php echo $title; ?></title>        <link rel="stylesheet" type="text/css" href="__HOME__/css/base.css"/>        <link rel="stylesheet" href="__HOME__/css/swiper.min.css">        <script type="text/javascript" src="__HOME__/js/jquery-1.8.3.min.js"></script>        <script src="__HOME__/js/swiper.min.js"></script>        <script src="__HOME__/js/rem.js"></script>        <script src="__HOME__/js/jquery.validate.js"></script>        <script src="__HOME__/js/jquery.citys.js"></script>        <script src="__HOME__/js/layer/layer.js"></script>        <script src="__PLUGIN__/Fileinput/js/fileinput.js" type="text/javascript"></script>        <script>            $(function(){                var mySwiper = new Swiper('.swiper-container1',{                    pagination: '.swiper-pagination1',                    autoplay:3000,                    loop:true,                    grabCursor: true,                    paginationClickable: true                })            });            function tips(data) {                /*layer.open({                 content: data                 });*/                layer.msg(data);            }            // $(function(){            //     $(".back_btn").click(function(e){            //         //代码段            //         window.history.go(-1);            //         // window.location.href = document.referrer;            //     });            // });        </script>    </head>

<body>

<div id="page">

<div class="xian"></div>

<div class="tx_gong">
共提现<p><?php echo $count; ?>元</p>

</div>
<div class="xian"></div>

    <?php if(is_array($list) || $list instanceof \think\Collection): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
        <!--一组-->
        <div class="tx_list">
            <div class="tx_list1"><?php echo $vo['content']; ?><span>-<?php echo $vo['money']; ?></span></div>
            <div class="tx_list2"><?php echo $vo['creattime']; ?><span>交易成功</span></div>
        </div>
    <?php endforeach; endif; else: echo "" ;endif; ?>

<div class="pb100"></div>

</div>
</body>
</html>