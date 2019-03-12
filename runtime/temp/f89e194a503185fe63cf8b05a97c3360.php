<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:92:"D:\phpstudy\WWW\20190213zihaimeirong\public/../application/home\view\user\extract_money.html";i:1552273286;s:87:"D:\phpstudy\WWW\20190213zihaimeirong\public/../application/home\view\public\header.html";i:1551084136;}*/ ?>
<!--顶部导航-->
<!DOCTYPE html>    <html>    <head>        <meta charset="utf-8">        <meta http-equiv="X-UA-Compatible" content="IE=edge">        <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />        <title><?php echo $title; ?></title>        <link rel="stylesheet" type="text/css" href="__HOME__/css/base.css"/>        <link rel="stylesheet" href="__HOME__/css/swiper.min.css">        <script type="text/javascript" src="__HOME__/js/jquery-1.8.3.min.js"></script>        <script src="__HOME__/js/swiper.min.js"></script>        <script src="__HOME__/js/rem.js"></script>        <script src="__HOME__/js/jquery.validate.js"></script>        <script src="__HOME__/js/jquery.citys.js"></script>        <script src="__HOME__/js/layer/layer.js"></script>        <script src="__PLUGIN__/Fileinput/js/fileinput.js" type="text/javascript"></script>        <script>            $(function(){                var mySwiper = new Swiper('.swiper-container1',{                    pagination: '.swiper-pagination1',                    autoplay:3000,                    loop:true,                    grabCursor: true,                    paginationClickable: true                })            });            function tips(data) {                /*layer.open({                 content: data                 });*/                layer.msg(data);            }            // $(function(){            //     $(".back_btn").click(function(e){            //         //代码段            //         window.history.go(-1);            //         // window.location.href = document.referrer;            //     });            // });        </script>    </head>

<body>

<div id="page">

<div class="xian"></div>

<div class="tx_gong">
余额<p id="acc"><?php echo $account; ?>元</p>


<div class="tx_ti"><a href="<?php echo url('user/extract_record'); ?>">提现记录</a></div>
</div>
<div class="xian"></div>


<div class="biao1">
    <div class="biao1_zi">提现金额</div>
        <div class="biao1_sr">
            <input id="t_money" type="number" placeholder="输入金额">
        </div>
    </div>
<div class="tx_tx1">提现到微信钱包</div>

<div class="tx_tj"><input id="take" type="button" value="立即提现"></div>
<div class="tx_tx2"><a href="<?php echo url('user/take_info'); ?>">提现说明</a></div>
<div class="pb100"></div>

</div>
</body>
</html>
<script>
    $('#take').click(function(){
        var acc   = '<?php echo $account; ?>';
        var max   = parseInt('<?php echo $take_money_restrict; ?>');
        var money = parseFloat($('#t_money').val());

        if(money<=0 || isNaN(money)){
            layer.msg('请输入有效提现金额');
            return false;
        }

        if(max<money){
            layer.msg('提现金额不能大于'+max+'元');
            return false;
        }

        $.ajax({
            url:'<?php echo url("user/take_money"); ?>',
            type:'post',
            dataType:'json',
            data:{money:money},
            success:function(data){
                if(data.code == 200){
                    layer.msg('申请提现成功');
                    $('#acc').text(acc-money)
                }else{
                    layer.msg('申请提现失败');
                }
            }
        })
    })
</script>