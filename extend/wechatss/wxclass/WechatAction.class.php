<?php
$weobj2 = new WechatAction();
if($_GET['a'] == 'index'){
    $weobj2->index();
}

if($_GET['a'] == 'createmenu'){
    $weobj2->createMenu();
}
//回复的xml
$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
if($postStr){
	$weobj2->index();
}
class WechatAction {
    public function init() {
    require 'Wechat.class.php';
    
    $options = array (
        'token' 		 => 'bohanshangcheng', // 填写你设定的key
        'encodingaeskey' => 'Wp6FVjA5LP0sN3ihesjGo1H5GahYb6HApaqlgJMMTyZ', // 填写加密用的EncodingAESKey
        'appid' 		 => 'wx4238c6e26b9c09e2', // 填写高级调用功能的app id
        'appsecret' 	 => '489369ea280291bac96768614f205d20' // 填写高级调用功能的密钥
//         'partnerid' => '', // 财付通商户身份标识
//         'partnerkey' => '', // 财付通商户权限密钥Key
//         'paysignkey' => ''  // 商户签名密钥Key
    );
        $weObj = new Wechat ( $options );
        return $weObj;
    }
    public function index() {
		//$dosql->ExecNoneQuery("update `zz_member` set username=123 WHERE id=475");
        $weObj = $this->init ();
        $weObj->valid ();
        $type = $weObj->getRev ()->getRevType ();
       // include dirname(__FILE__).'/button_config.php';
        //---------------------------//
        define('IN_PHPMYWIND', true);
        //require_once(dirname(dirname(__FILE__)).'../include/conn.inc.php');
       // $conn=mysqli_connect(,,) or die("无法连接数据库".mysql_error());
		
		$conn = new mysqli('localhost', 'webmysql', 'DAxiaoxiejia1234','webmysql');  
			// Check connection  
			if ($conn->connect_error) {  
				die("连接失败: " . $conn->connect_error);  
			}  
		//修改mysqli链接
        //mysqli_select_db($db_name,$conn) or die("无法连接数据库".mysql_error());
        //$conn->query("set names '$db_charset'");
		 //$conn->query("update `zz_member` set cnname=789 WHERE id=475");
        $openid = $weObj->getRevFrom();//openid

        //---------------------------//

        switch ($type) {
            case Wechat::MSGTYPE_TEXT :
  
                $weObj->text ( "亲，如果回复不及时请打客服电话或微信咨询呦，给你带来的不便还望谅解！" )->reply ();
                break;
            case Wechat::MSGTYPE_EVENT :
                $eventype = $weObj->getRev ()->getRevEvent ();
                if ($eventype ['event'] == "CLICK") {
					
					
					    if( $eventype ['key']=='GET_PIC'){
                        $query = $conn->query("SELECT * FROM `zz_member` WHERE openid='$openid'");
                        $usersresult = $query->fetch_array();



                        if($usersresult['member'] ==1){
                            $image = realpath(dirname(dirname(__FILE__)).'/').'/imgpublic/user_'.$usersresult['id'].'.jpg';

                            $data['media'] = "@$image";
                            $res = $weObj->uploadMedia($data, 'image');

                            $weObj->getRev ()->image ( $res['media_id'] )->reply ();
                        }else{
                            $text = '对不起 您还不是经销商 不能为您生成二维码！';
                            $weObj->text ( $text )->reply ();
                        }



                        exit ();
                    }
					
                  
                }elseif ($eventype['event'] == "subscribe") {
					//$conn->query("update `zz_member` set password=123 WHERE id=475");
                    //查询是否有openid

                    $query  =$conn->query("SELECT * FROM `zz_member` WHERE openid='$openid'");
                    $result =$query->fetch_array();
                    $user   = array();
                    
                    //获取用户信息 返回json格式数据
                    $info 	= $weObj->getUserInfo($openid);

                    $wx_info = json_decode($info,true);
                    //变性别
                    if ($wx_info['sex'] == 1) {
                        $sex = 0;
                    } else {
                        $sex = 1;
                    }
                    	
            		/*处理特殊字符开始*/
					$str = $wx_info['nickname'];
                    //$namename = $weObj->userTextEncode($str);
                     $tmpStr = json_encode($str); //暴露出unicode
                     $tmpStr = preg_replace("#(\\\ue[0-9a-f]{3})#ie","addslashes('\\1')",$tmpStr); //将emoji的unicode留下，其他不动
                     //$text = json_decode($tmpStr);
                     $tmpStr = str_replace('"', '', $tmpStr);

                     $json_wx = array('subscribe'=>$wx_info['subscribe'],'openid'=>$openid,'nickname'=>$tmpStr,'sex'=>$wx_info['sex'],'language'=>$wx_info['language'],'city'=>$wx_info['city'],'province'=>$wx_info['province'],'country'=>$wx_info['country'],'headimgurl'=>$wx_info['headimgurl'],'subscribe_time'=>$wx_info['subscribe_time'],'remark'=>$wx_info['remark'],'groupid'=>$wx_info['groupid']);

                     $info111 = json_encode($json_wx);
                     /*处理特殊字符结束*/   

                    
                    if(empty($result['openid'])) {
                        
                        if(!empty($eventype['key']) && empty($result['l_id']) && empty($result['member'])) {
                            $a = explode('_', $eventype['key']);
                            $id = $a['1'];
                            //二级会员
                           
	

                            //新增一个用户(有上线)
                            $regtime  = time();
                           $conn->query("INSERT INTO `zz_member` (
                            username,password,question,answer,cnname,enname,
                            avatar,sex,birthtype,cardnum,intro,email,qqnum,
                            mobile,telephone,address,zipcode,enteruser,expval,
                            integral,regtime,openid,wx_info,l_id,yongjin,yongjin_zong,dz_shouyi,yongjin_fxzong,dianzhang,dz_time,member_lv,dz_ren
                            )
                            VALUES
                            (
                            '','','','','','','',$sex,1,'','','','','','','','','',0,0,'$regtime','$openid','$info','$id',0,0,0,0,0,0,3,0)");
                        } else {
                            //新增一个用户
                            $regtime  = time();
                           $conn->query("INSERT INTO `zz_member` (
                            username,password,question,answer,cnname,enname,
                            avatar,sex,birthtype,cardnum,intro,email,qqnum,
                            mobile,telephone,address,zipcode,enteruser,expval,
                            integral,regtime,openid,wx_info,yongjin,yongjin_zong,dz_shouyi,yongjin_fxzong,dianzhang,dz_time,member_lv,dz_ren
                            )
                            VALUES
                            (
                            '','','','','','','',$sex,1,'','','','','','','','','',0,0,'$regtime','$openid','$info',0,0,0,0,0,0,3,0)");
                        } 
                    }
                    
                    $query   = $conn->query("SELECT * FROM `zz_member` WHERE openid='$openid'");
                    $result1 = $query->fetch_array();
					
					//二级
					
				    $query1  = $conn->query("SELECT * FROM `zz_member` WHERE id='".$result1['l_id']."'");
                    $result2 = $query1->fetch_array();
					
					if(!empty($result2['id'])){
						$lid=$result1['l_id'];
                        $query = $conn->query("SELECT openid,dianzhang,dz_ren FROM `zz_member` WHERE id='".$lid."'");//id=15373
                        $openid_l_id = $query->fetch_array();

                        if($openid_l_id['dianzhang']){
                           $conn->query("UPDATE `zz_member` SET dz_ren='$lid' WHERE id='".$result1['id']."'");
                        }else{
                            if($openid_l_id['dz_ren']){
                               $conn->query("UPDATE `zz_member` SET dz_ren='".$openid_l_id['dz_ren']."' WHERE id='".$result1['id']."'");
                            }
                        }
                        if(!$result1['intro']){
    						$data = array();
    						$data['touser'] = $openid_l_id['openid'];
    						$data['msgtype'] = 'text';
    						$data['text']['content'] = '【'.$wx_info['nickname'].'】通过二维码关注了本公众号，成为您的'.$message_name.'A家族成员！';
    						$weObj->sendCustomMessage($data);
                           $conn->query("UPDATE `zz_member` SET intro=1 WHERE id='".$result1['id']."'");
                        }						
					}else{
						$lid='-1';
					}
					
					$query =$conn->query("SELECT * FROM `zz_member` WHERE id='".$result2['l_id']."'");
                    $result3 =$query->fetch_array();
					if(!empty($result3['id'])){
						$lb=$result2['l_id'];
						
						$query =$conn->query("SELECT openid FROM `zz_member` WHERE id='".$lb."'");//id=15373
						$openid_l_b =$query->fetch_array();
                        if(!$result1['email']){
    						$data = array();
    						$data['touser'] = $openid_l_b['openid'];
    						$data['msgtype'] = 'text';
    						$data['text']['content'] = '【'.$wx_info['nickname'].'】通过二维码关注了本公众号，成为您的'.$message_name.'B家族成员！';
    						$weObj->sendCustomMessage($data);
                           $conn->query("UPDATE `zz_member` SET email=1 WHERE id='".$result1['id']."'");
						}
						
						
					}else{
						$lb='-1';
					}
					
					$query =$conn->query("SELECT * FROM `zz_member` WHERE id='".$result3['l_id']."'");
              $result4 =$query->fetch_array();
					if(!empty($result4['id'])){
						$lc=$result3['l_id'];
						
						$query =$conn->query("SELECT openid FROM `zz_member` WHERE id='".$lc."'");//id=15373
						$openid_l_c =$query->fetch_array();
                        if(!$result1['avatar']){
    						$data = array();
    						$data['touser'] = $openid_l_c['openid'];
    						$data['msgtype'] = 'text';
    						$data['text']['content'] = '【'.$wx_info['nickname'].'】通过二维码关注了本公众号，成为您的'.$message_name.'C家族成员！';
    						//$weObj->sendCustomMessage($data);
                           $conn->query("UPDATE `zz_member` SET avatar=1 WHERE id='".$result1['id']."'");
						}
						
					}else{
						$lc='-1';
					}
					
					

                  
					 

						$text = '您好：欢迎您【'.$wx_info['nickname'].'】关注鹤岗市博翰传媒有限公司。';

						$weObj->text ( $text )->reply ();
					 	$conn->query("UPDATE `zz_member` SET l_id='$lid', l_b='$lb',l_c='$lc' WHERE id='".$result1['id']."'");
						
						 function imagecropper($source_path, $target_width, $target_height, $name,$logo)
                    {
                        $source_info   = getimagesize($source_path);
                        $source_width  = $source_info[0];
                        $source_height = $source_info[1];
                        $source_mime   = $source_info['mime'];
                        $source_ratio  = $source_height / $source_width;
                        $target_ratio  = $target_height / $target_width;

                        // 源图过高
                        if ($source_ratio > $target_ratio)
                            {
                                $cropped_width  = $source_width;
                                $cropped_height = $source_width * $target_ratio;
                                $source_x = 0;
                                $source_y = ($source_height - $cropped_height) / 2;
                            }
                        // 源图过宽
                        elseif ($source_ratio < $target_ratio)
                        {
                            $cropped_width  = $source_height / $target_ratio;
                            $cropped_height = $source_height;
                            $source_x = ($source_width - $cropped_width) / 2;
                            $source_y = 0;
                        }
                        // 源图适中
                        else
                            {
                                $cropped_width  = $source_width;
                                $cropped_height = $source_height;
                                $source_x = 0;
                                $source_y = 0;
                            }

                        switch ($source_mime)
                        {
                            case 'image/gif':
                                $source_image = imagecreatefromgif($source_path);
                                break;

                            case 'image/jpeg':
                                $source_image = imagecreatefromjpeg($source_path);
                                break;

                            case 'image/png':
                                $source_image = imagecreatefrompng($source_path);
                                break;

                            default:
                                return false;
                                break;
                        }

                        $target_image  = imagecreatetruecolor($target_width, $target_height);
                        $cropped_image = imagecreatetruecolor($cropped_width, $cropped_height);

                        // 裁剪
                        imagecopy($cropped_image, $source_image, 0, 0, $source_x, $source_y, $cropped_width, $cropped_height);
                        // 缩放
                        imagecopyresampled($target_image, $cropped_image, 0, 0, 0, 0, $target_width, $target_height, $cropped_width, $cropped_height);

                        $path = '../imgpublic/'.$logo.'_'.$name.'.jpg';
                        imagejpeg($target_image,$path);
                        imagedestroy($source_image);
                        imagedestroy($target_image);
                        imagedestroy($cropped_image);
                    }

                    $name = $result1['id'];
                    $logoname = "../imgpublic/logo_".$name.".jpg";
                    if (file_exists($logoname)) {
                    } else {
                        $headimgurl = $wx_info['headimgurl'];
                        $local_logo_file = fopen($logoname, 'w');
                        fwrite($local_logo_file, file_get_contents($headimgurl));
                        fclose($local_logo_file);
                        imagecropper($logoname,150,150,$name,'logo');
                    }
					
					                    //查询是否有openid
		
						
                }


            

                
                exit ();
                break;
            default :
                $weObj->text ( "亲，如果回复不及时请打客服电话或微信咨询呦，给你带来的不便还望谅解！" )->reply ();
        }
    }
    
    public function createMenu() {
        include dirname(__FILE__).'/button_config.php';
        $weObj = $this->init ();
        $weObj->createMenu ( $newmenu );
        
        echo '<script type="text/javascript">alert("菜单创建成功");history.go(-1);</script>';
//         $this->success ( "重新创建菜单成功!" );
    }
}










?>