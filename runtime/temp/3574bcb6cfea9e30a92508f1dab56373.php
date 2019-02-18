<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:67:"D:\phpstudy\WWW\20190213zihaimeirong\thinkphp\tpl\dispatch_jump.tpl";i:1550202224;}*/ ?>

<!DOCTYPE html>
<html lang="zh_cn">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="__CONSOLE__/images/favicon.png" type="image/png">

  <title>跳转提示</title>
  <!--   sweetalert弹出样式 -->
  <link href="__PLUGIN__/sweetalert/css/sweetalert.css" rel="stylesheet">
  <script src="__CONSOLE__/js/jquery-1.11.1.min.js"></script>
  <script src="__PLUGIN__/sweetalert/js/sweetalert.min.js"></script>
  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="__CONSOLE__/js/html5shiv.js"></script>
  <script src="__CONSOLE__/js/respond.min.js"></script>
  <![endif]-->
  
</head>
<body>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            swal({   
                title: "<?php echo(strip_tags($msg));?>",   
                text: "页面自动跳转!等待时间：<?php echo($wait);?>s",   
                type: "<?php switch ($code) { case 1:?>success<?php break;case 0:?>error<?php break; } ?>",  
                confirmButtonText: "确认", 
                closeOnConfirm: false
            }, function(){   
                top.location.href= "<?php echo($url);?>";
            });

            setTimeout('wait()',<?php echo($wait*1000);?>);

        });

        function wait(){
           top.location.href= "<?php echo($url);?>";
        }
    </script>
</body>
</html>
