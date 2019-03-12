<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:92:"D:\phpstudy\WWW\20190213zihaimeirong\public/../application/home\view\user\personal_data.html";i:1550715848;s:87:"D:\phpstudy\WWW\20190213zihaimeirong\public/../application/home\view\public\header.html";i:1551084136;}*/ ?>
<!--顶部导航-->
<!DOCTYPE html>    <html>    <head>        <meta charset="utf-8">        <meta http-equiv="X-UA-Compatible" content="IE=edge">        <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />        <title><?php echo $title; ?></title>        <link rel="stylesheet" type="text/css" href="__HOME__/css/base.css"/>        <link rel="stylesheet" href="__HOME__/css/swiper.min.css">        <script type="text/javascript" src="__HOME__/js/jquery-1.8.3.min.js"></script>        <script src="__HOME__/js/swiper.min.js"></script>        <script src="__HOME__/js/rem.js"></script>        <script src="__HOME__/js/jquery.validate.js"></script>        <script src="__HOME__/js/jquery.citys.js"></script>        <script src="__HOME__/js/layer/layer.js"></script>        <script src="__PLUGIN__/Fileinput/js/fileinput.js" type="text/javascript"></script>        <script>            $(function(){                var mySwiper = new Swiper('.swiper-container1',{                    pagination: '.swiper-pagination1',                    autoplay:3000,                    loop:true,                    grabCursor: true,                    paginationClickable: true                })            });            function tips(data) {                /*layer.open({                 content: data                 });*/                layer.msg(data);            }            // $(function(){            //     $(".back_btn").click(function(e){            //         //代码段            //         window.history.go(-1);            //         // window.location.href = document.referrer;            //     });            // });        </script>    </head>
<!--顶部导航-->
<body>
<div class="wrap">
    <header>
        <img src="__HOME__/img/back_btn_03.png" class="back_btn" onclick="user_index()">
        <script>
            function user_index() {
                window.location.href="<?php echo url('home/user/index'); ?>";
            }
        </script>
        <p class="title">个人资料</p>
    </header>
    <div style="display:none;">
        <input type="file" id="filepic" accept="image/*" class="inputPic">
    </div>
    <div class="center_content">
        <ul class="bottom_li">
            <li style="height:1.2rem;">
                <span style="line-height: 1.2rem">修改头像</span>
                <?php if($vo['picurl'] != ''): ?>
                <img src="<?php echo $vo['picurl']; ?>" alt="" onerror="this.src='__HOME__/img/tab/touzw.png'" onclick="$('#filepic').click();" style="float: right;width: 0.8rem;height: 0.8rem;border-radius: 0.4rem;margin-top: 0.2rem">
                <?php else: ?>
                <img src="__HOME__/img/tab/touzw.png" onclick="$('#filepic').click();" style="float: right;width: 0.8rem;height: 0.8rem;border-radius: 0.4rem;margin-top: 0.2rem">
                <?php endif; ?>
            </li>
            <li>
                <span>昵称</span>
                <img src="__HOME__/img/right_img_03.png" alt="" class="right_logo" style="margin-left: 0.15rem" onclick="update_username('<?php echo $vo['username']; ?>')">
                <p class="right_p" onclick="update_username('<?php echo $vo['username']; ?>')"><?php echo $vo['username']; ?></p>
            </li>
            <script>
                function update_username(username) {
                    window.location.href = "<?php echo url('home/user/update_username'); ?>?username="+username;
                }
            </script>
            <li>
                <span>账号 </span>
                <p class="right_p"><?php echo $vo['account']; ?></p>
            </li>
            <li>
                <span>邀请</span>
                <p class="right_p"><?php echo $vo['icode']; ?></p>
            </li>
        </ul>
    </div>
</div>
</body>
</html>
<script>
    function fileinputOne(fileid) {
        var maxFileSize=150*1024;
        //单图上传
        var $input = $("#" + fileid);
//        alert($input);return false;
        $input.fileinput({
            uploadUrl: "<?php echo url('home/common/upload'); ?>",
            overwriteInitial: true,
        }).on("filebatchselected", function (event, files) {
            var files = Array.prototype.slice.call(this.files);
            files.forEach(function (file, i) {
                if(file.size > 3145728) {
                    layer.msg("图片超出限制3M");return false;
                }
            });
//            return false;
            $input.fileinput("upload");//选择后自动上传
        }).on('fileuploaded', function (event, data, previewId, index) {
//            console.log(data);
            var dataObj = eval("(" + data.response + ")");
//            alert(dataObj['code']);
            if (dataObj['code'] == 0) {
                var picurl = dataObj['img'];
                $.ajax({
                    type: "post",
                    url: "<?php echo url('home/user/touxiang'); ?>",
                    data: {avatar: picurl},
                    dataType: 'json',
                    success: function (data) {
                        $('#avatar').attr('src', '/' + data['src']);
                        layer.msg("修改成功");
                        location.reload();
                    },
                    error: function (err) {
                        layer.msg(JSON.stringify(err));
                        layer.msg(JSON.stringify(err));
                    }
                });
            } else {
                layer.msg(dataObj['error']);
//                alert();
            }
        });
    }
    fileinputOne('filepic');
</script>