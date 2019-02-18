<?php

/**
 * 支付
 */
require_once (dirname(__FILE__) . '../../include/config.inc.php');
ini_set('date.timezone', 'Asia/Shanghai');
error_reporting(E_ERROR);
require_once "lib/WxPay.Api.php";
require_once 'lib/WxPay.Notify.php';
require_once 'lib/WxPay.Data.php';
require_once 'lib/log.php';
// 初始化日志
$logHandler = new CLogFileHandler("./logs/" . date('Y-m-d') . '.log');
$log = Log::Init($logHandler, 15);

class PayNotifyCallBack extends WxPayNotify {

    // 查询订单
    public function Queryorder($transaction_id) {
        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = WxPayApi::orderQuery($input);
        // Log::DEBUG("query:" . json_encode($result));
        if (array_key_exists("return_code", $result) && array_key_exists("result_code", $result) && $result["return_code"] == "SUCCESS" && $result["result_code"] == "SUCCESS") {
            return true;
        } else {
            return false;
        }
    }

    // 重写回调处理函数
    public function NotifyProcess($data) {
        global $dosql;
        Log::DEBUG("call back to:" . json_encode($data));
        $notfiyOutput = array();
        if (!array_key_exists("transaction_id", $data)) {
            Log::DEBUG("输入参数不正确");
            return false;
        }

        Log::DEBUG($this->Queryorder($data["transaction_id"]));
        // 查询订单，判断订单真实性
        if ($this->Queryorder($data["transaction_id"])) {

            $message = "微信消费";
            $posttime = time();
            $goodsorder = '#@__goodsorder';
            $member = '#@__member';
            $acount = '#@__acount'; // 账户表
            $acount_log = '#@__acount_log';
            $recommend = '#@__recommend'; // 推荐人表
            $new = '#@__news';
            // ------------------------------
            // 处理业务开始
            // ------------------------------
            $ordernumber = $data['out_trade_no'];
            Log::DEBUG('订单号：' . $ordernumber);

            $check = $dosql->GetOne("SELECT `checkinfo`,`uid`,`shopid`,`actualmoney` ,returnprice, `amount`,`return_member`,`return_member_re`,`return_shop_re`,`return_agent`,`return_city`,`return_county` FROM `$goodsorder` WHERE `ordernum` = '$ordernumber' ");

            Log::DEBUG('订单状态：' . $check['checkinfo']);

            if ($check['checkinfo'] == '1') {

                $userid = $check['uid']; // 用户id
                $shopid = $check['shopid']; // 商家id
                $amount = $check['amount']; // 消费金额

                Log::DEBUG('订单用户id：' . $userid . ';商家id:' . $shopid . ';消费金额:' . $amount);

                // 获取用户信息
                $r = $dosql->GetOne("SELECT `id`,`username`,`mobile` FROM `$member` WHERE `id`='$userid'");
                $mobile = $r['mobile'];

                // 获取用户的推荐人
                $r4 = $dosql->GetOne(" SELECT `parent_mobile` FROM `$recommend` WHERE `child_mobile` = '$mobile' AND `type` = '1'");
                $parent_mobile1 = empty($r4['parent_mobile']) ? '' : $r4['parent_mobile'];

                // 商家信息
                $shop = $dosql->GetOne("select `address_prov`,`address_city`,`address_country`,`mobile`,`title` from `#@__shop` where `id` = '$shopid'");
                $shopmobile = $shop['mobile'];

                // 获取商家的推荐人
                $r3 = $dosql->GetOne(" SELECT `parent_mobile` FROM `$recommend` WHERE `child_mobile` = '$shopmobile' AND `type` = '2'");
                $parent_mobile2 = empty($r3['parent_mobile']) ? '' : $r3['parent_mobile'];

                // 添加资金流动记录
                $sql2 = "INSERT INTO `$acount_log` (uid, acount, message, posttime, type, iswork, ordernum) VALUES ('$userid', '$amount', '$message','$posttime', '4','1' ,'$ordernumber')";
                $dosql->ExecNoneQuery($sql2);
                Log::DEBUG('添加资金流动记录成功！');

                // 查询是否首单
                $c = $dosql->GetOne(" SELECT `id` FROM `#@__member` WHERE `id` = '$userid' AND `isfirst` = '0'");
                if (isset($c['id']) && $amount >= 1) {
                    $money = get_rand();
                    $dosql->ExecNoneQuery("update `#@__member` set `isfirst` = '1' and `coin` = '$money' where id = '$userid'");
                }

                // 修改订单状态
                $sql3 = "UPDATE `$goodsorder` SET `checkinfo`='2' WHERE `ordernum` = '$ordernumber' ";
                $dosql->ExecNoneQuery($sql3);
                Log::DEBUG('修改订单状态成功！');

                // 给用户反润
                $return_member = $check['return_member'];
                $dosql->ExecNoneQuery(" update `$acount` set coin = coin + '$return_member' where uid = '$userid'");
                $mmessage = '您刚刚消费反润' . $return_member . '金币';
                $dosql->ExecNoneQuery(" insert into `$acount_log` (uid,acount,message,posttime,type,iswork,ordernum) values('$userid','$return_member','$mmessage','$posttime','5','1','$ordernumber') ");
                Log::DEBUG('给用户反润成功！');

                $returnprice = $check['returnprice'];
                // 给用户推荐人
                 $return_member_re = $returnprice * $cfg_return_member_re / 100;
                if (!empty($parent_mobile1)) {

                    $rmr = $dosql->GetOne("select `id` from `$member` where `mobile` = '$parent_mobile1'");
                    $rmruserid = $rmr['id'];

                    $dosql->ExecNoneQuery(" update `$acount` set coin = coin + '$return_member_re' where uid = '$rmruserid'");

                    $mrmessage = '您的好友为您贡献' . $return_member_re . '金币';
                    $dosql->ExecNoneQuery(" insert into `$acount_log` (uid,acount,message,posttime,type,iswork,ordernum) values('$rmruserid','$return_member_re','$mrmessage','$posttime','5','1','$ordernumber') ");

                    $dosql->ExecNoneQuery(" update `$recommend` set number = number + '$return_member_re' where `parent_mobile` = '$parent_mobile1' and `child_mobile` = '$mobile'");
                    Log::DEBUG('给用户推荐人反润成功！');
                }

                // 给商家推荐人
                 $return_shop_re = $returnprice * $cfg_return_shop_re / 100;
                if (!empty($parent_mobile2)) {
                    $rsmr = $dosql->GetOne("select `id` from `$member` where `mobile` = '$parent_mobile2'");
                    $rsmruserid = $rsmr['id'];

                    $dosql->ExecNoneQuery(" update `$acount` set coin = coin + '$return_shop_re' where uid = '$rsmruserid'");
                    $msrmessage = '您的好友为您贡献' . $return_shop_re . '金币';

                    $dosql->ExecNoneQuery(" insert into `$acount_log` (uid,acount,message,posttime,type,iswork,ordernum) values('$rsmruserid','$return_shop_re','$msrmessage','$posttime','5','1','$ordernumber') ");

                    $dosql->ExecNoneQuery(" update `$recommend` set number = number + '$return_shop_re' where `parent_mobile` = '$parent_mobile2' and `child_mobile` = '$shopmobile'");

                    Log::DEBUG('给商家推荐人反润成功！');
                }

                $return_agent = $returnprice * $cfg_return_agent / 100;
                $return_city = $returnprice * $cfg_return_city / 100;
                $return_county = $returnprice * $cfg_return_county / 100;
                
                
                 // 省
                $address_city = $shop['address_city']; // 市
                $address_country = $shop['address_country']; // 区
                // 给省级代理反润
                $agent1 = $dosql->GetOne(" select `id` from `#@__agent` where `province` = '$address_prov' and `city` = '0' and `area` = '0' and checkinfo = '1' ");
                if (isset($agent1['id'])) {
                    // 如果此商家有省级代理，给市级代理添加金额
                    $agentid1 = $agent1['id'];
                    // agent_acount
                    $dosql->ExecNoneQuery(" update `#@__agent_acount` set `account`= $return_agent + account,`balance` = $return_agent + balance where `agentid`='$agentid1' ");
                    // agentincome_log
                    $dosql->ExecNoneQuery(" insert into `#@__agentincome_log` (`agentid`,`account`,`posttime`,`ordernumber`)values('$agentid1','$return_agent','$posttime','$ordernumber')");
                }

                // 给市级代理反润
                $agent2 = $dosql->GetOne(" select `id` from `#@__agent` where `province` = '$address_prov' and `city` = '$address_city' and `area` = '0' and checkinfo = '1' ");
                if (isset($agent2['id'])) {
                    // 如果此商家有市级代理，给市级代理添加金额
                    $agentid2 = $agent2['id'];
                    // agent_acount
                    $dosql->ExecNoneQuery(" update `#@__agent_acount` set `account`= $return_city + account,`balance` = $return_city + balance where `agentid`='$agentid2' ");
                    // agentincome_log
                    $dosql->ExecNoneQuery(" insert into `#@__agentincome_log` (`agentid`,`account`,`posttime`,`ordernumber`)values('$agentid2','$return_city','$posttime','$ordernumber')");
                }

                // 给区级代理反润
                $agent3 = $dosql->GetOne(" select `id` from `#@__agent` where `province` = '$address_prov' and `city` = '$address_city' and `area` = '$address_country' and checkinfo = '1' ");
                if (isset($agent2['id'])) {
                    // 如果此商家有市级代理，给市级代理添加金额
                    $agentid3 = $agent3['id'];
                    // agent_acount
                    $dosql->ExecNoneQuery(" update `#@__agent_acount` set `account`= $return_county + account,`balance` = $return_county + balance where `agentid`='$agentid3' ");
                    // agentincome_log
                    $dosql->ExecNoneQuery(" insert into `#@__agentincome_log` (`agentid`,`account`,`posttime`,`ordernumber`)values('$agentid3','$return_county','$posttime','$ordernumber')");
                }

                // 添加成长值
                $number = intval($amount);
                $leveltitle = '消费获得成长值';
                $dosql->ExecNoneQuery(" insert into `#@__level` (uid,title,posttime,number) values('$userid','$leveltitle','$posttime','$number')");
                Log::DEBUG('添加成长值成功！');

                // 添加系统消息
                $st = $shop['title'];
                $title = '您在' . $st . '消费了' . $amount . '元';
                $dosql->ExecNoneQuery("INSERT INTO `$new` (uid,title) VALUES('$userid','$title')");

                Log::DEBUG('添加系统消息成功！');

                // 修改商家资金
                $t_shop_acount = '#@__shop_acount';
                $actualmoney = $check['actualmoney'];
                $dosql->ExecNoneQuery(" update `$t_shop_acount` set account = account+'$actualmoney',balance = balance+'$actualmoney'  where shopid = '$shopid'");

                //更新订单分润信息
                $dosql->ExecNoneQuery(" update `$goodsorder` set `return_member_re` = '$return_member_re',return_shop_re = '$return_shop_re',return_agent = '$return_agent',return_city = '$return_city',return_county = '$return_county',type=2 where `ordernum` = '$ordernumber' ");
                
                // 推送
                jiguang1($title, $userid);

                Log::DEBUG('推送成功！');
            }

            // ------------------------------
            // 处理业务完毕
            // ------------------------------

            return true;
        } else {
            Log::INFO("订单查询失败");
            return false;
        }
    }

}

$log->DEBUG("微信回调开始！");
$notify = new PayNotifyCallBack();
$notify->Handle(false);
$notify->NotifyProcess($_REQUEST);
