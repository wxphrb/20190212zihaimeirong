<?php
namespace think;

class Captcha{
    private $width;
    private $height;
    private $codeNum;
    private $code;
    private $im;
    //初始化
    function __construct($width=140, $height=40, $codeNum=4){
        $this->width = $width;
        $this->height = $height;
        $this->codeNum = $codeNum;
    }
    //显示验证码
    function showImg(){
        //创建图片
        $this->createImg();
        //设置干扰元素
        $this->setDisturb();
        //设置验证码
        $this->setCaptcha();
        //输出图片
        $this->outputImg();
    }
    
    //获取显示的验证码,用来验证验证码是否数据正确
    function getCaptcha(){
        return $this->code;
    }
    
    //创建图片
    private function createImg(){
        $this->im = imagecreatetruecolor(140, 40);
        $bgColor = imagecolorallocate($this->im, 255, 255, 255);//创建的前景为白色
        imagefill($this->im, 0, 0, $bgColor);
    }
    
    //设置干扰元素
    private function setDisturb(){
        $area = (140 * 40) / 20;
        $disturbNum = ($area > 250) ? 250 : $area;
        //加入点干扰
        for ($i = 0; $i < $disturbNum; $i++) {
            $color = imagecolorallocate($this->im, rand(0, 255), rand(0, 255), rand(0, 255));
            imagesetpixel($this->im, rand(1, 140 - 2), rand(1, 40 - 2), $color);
        }
        //加入弧线
        for ($i = 0; $i <= 1; $i++) {//最多两条线
            $color = imagecolorallocate($this->im, rand(128, 255), rand(125, 255), rand(100, 255));
            imagearc($this->im, rand(0, 140), rand(0, 40), rand(30, 300), rand(20, 200), 50, 30, $color);
        }
    }
    //设置验证码随机数
    private function createCode(){
        $str = "23456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKMNPQRSTUVWXYZ";
        for ($i = 0; $i < $this->codeNum; $i++) {
            $this->code .= $str{rand(0, strlen($str) - 1)};
        }
    }
    //设置验证码
    private function setCaptcha(){
          
        //设置验证码随机数        
        $this->createCode();
        
        //文字前景
        $color = imagecolorallocate($this->im, rand(50, 250), rand(100, 250), rand(128, 250));
               
        //因为imagechar最大的文字字体为5,字体太小而不用这个方式了
        //imagechar($this->im, $size, $x, $y, $this->code{$i}, $color);           
               
        //因为imagechar最大的文字字体为5,而这里要显示更大的文字,所以用 imagefttext 
        //$view_replace_str = config('view_replace_str');
        //$url = $view_replace_str['__CONSOLE__'];
        imagefttext($this->im,35,0,37,30,$color,'static/console/fonts/monofont.ttf',$this->code);//图象资源,尺寸,角度,x轴,y轴,颜色,字体路径,文本插入图像
    }
    //输出图片
    private function outputImg(){
        if (imagetypes() & IMG_JPG) {
            header('Content-type:image/jpeg');
            imagejpeg($this->im);
        } elseif (imagetypes() & IMG_GIF) {
            header('Content-type: image/gif');
            imagegif($this->im);
        } elseif (imagetype() & IMG_PNG) {
            header('Content-type: image/png');
            imagepng($this->im);
        } else {
            die("Don't support image type!");
        }
    }//end
 }