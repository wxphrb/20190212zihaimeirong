<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:84:"D:\phpstudy\WWW\20190213zihaimeirong\public/../application/home\view\user\agent.html";i:1552362630;s:87:"D:\phpstudy\WWW\20190213zihaimeirong\public/../application/home\view\public\header.html";i:1551084136;}*/ ?>
<!DOCTYPE html>    <html>    <head>        <meta charset="utf-8">        <meta http-equiv="X-UA-Compatible" content="IE=edge">        <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />        <title><?php echo $title; ?></title>        <link rel="stylesheet" type="text/css" href="__HOME__/css/base.css"/>        <link rel="stylesheet" href="__HOME__/css/swiper.min.css">        <script type="text/javascript" src="__HOME__/js/jquery-1.8.3.min.js"></script>        <script src="__HOME__/js/swiper.min.js"></script>        <script src="__HOME__/js/rem.js"></script>        <script src="__HOME__/js/jquery.validate.js"></script>        <script src="__HOME__/js/jquery.citys.js"></script>        <script src="__HOME__/js/layer/layer.js"></script>        <script src="__PLUGIN__/Fileinput/js/fileinput.js" type="text/javascript"></script>        <script>            $(function(){                var mySwiper = new Swiper('.swiper-container1',{                    pagination: '.swiper-pagination1',                    autoplay:3000,                    loop:true,                    grabCursor: true,                    paginationClickable: true                })            });            function tips(data) {                /*layer.open({                 content: data                 });*/                layer.msg(data);            }            // $(function(){            //     $(".back_btn").click(function(e){            //         //代码段            //         window.history.go(-1);            //         // window.location.href = document.referrer;            //     });            // });        </script>    </head>
<body class="bgfafafa">

<div id="page">
<div class="dai_sou">
<!--搜索开始-->
        <form action="" method="get">
        <div class="soubg">
        <div class="sou11"><input name="" type="button" class="sou1"></div>
        <input name="" type="text" class="sou2" placeholder="请输入关键词">
        <div class="sou33"><input name="" type="button" class="sou3"></div>
        </div>
        </form>
<!--搜索结束-->
</div>
<div class="xian"></div>


<!--代理商一组-->
        <?php if(is_array($list) || $list instanceof \think\Collection): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
        <div class="team_list">

                <div class="team_tu"><img src="<?php echo $vo['picurl']; ?>"></div>
                <div class="team_zi">
                <div class="team_ti"><font><?php echo $vo['username']; ?></font><font class="gong">
                        <?php echo level_name($vo['level_id']); ?>
                        </font>
                </div>
                <div class="clear"></div>
                <div class="team_xx">加盟时间：<?php echo date('Y-m-d H:i:s',$vo['create_time']); ?></div>
        </div>
        <?php endforeach; endif; else: echo "" ;endif; ?>
<!--代理商一组-->



</div>


<div class="pb100"></div>

</div>
</body>
</html>