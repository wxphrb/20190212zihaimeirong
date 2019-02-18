<?php
namespace app\api\Controller;
use think\Db;
use think\Loader;
use think\Request;
use think\Log;
class Alipay extends \think\Controller{
    public function notifyurl()
    {
        Loader::import('alipay.wappay.service.AlipayTradeService');
        Loader::import('alipay.config');
        global $config;
        $alipaySevice = new \AlipayTradeService($config);
        $arr = Request::instance()->param();
        $alipaySevice->writeLog("支付宝回调开始：======================".date('Y-m-d H:i:s'));
        $alipaySevice->writeLog("打印参数：======================".json_encode($arr));
        $result = $alipaySevice->check($arr);
        $alipaySevice->writeLog("验证：====".$result);
        $alipaySevice->writeLog(var_export($result, true));
        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
        if ($result) { // 验证成功
            $alipaySevice->writeLog("验证成功：" . $result);
            $a = json_encode($arr);
            $alipaySevice->writeLog("json：". $a);
            $this->operation_log("jsonziji：". $a);
            $alipaySevice->writeLog("验证成功".date("Y-m-d H:i:s"));
            //订单号
            $order = $arr['out_trade_no'];
            //交易状态
            $trade_status = $arr['trade_status'];
            $this->operation_log('订单号ziji：' . $order);
            $alipaySevice->writeLog('订单号：' . $order);
            $alipaySevice->writeLog('TRADE_SUCCESS：' .$trade_status);
            if ($trade_status == 'TRADE_FINISHED') {
                $alipaySevice->writeLog('TRADE_FINISHED：' .$trade_status);
                // todo 交易成功，且可对该交易做操作
            } else if ($trade_status == 'TRADE_SUCCESS') {
                $alipaySevice->writeLog("进入修改订单支付状态：成功");
                // 查询订单信息
                $alipaySevice->writeLog("订单状态".$arr['body']);
                if($arr['body'] == '1'){
                    $order_merga = Db::name('orders_merge')->where('merge_sn',$order)->find();//合并的订单表
                    if($order_merga['status']=='0'){//未支付
                        $order_sn = explode(',',$order_merga['order_sn']);
                        foreach($order_sn as $v){
                            $orders = Db::name('orders')->where('order_sn',$v)->value('status');
                            if($orders=='0'){
                                Db::name('orders')->where('order_sn',$v)->update(['status'=>1,'pay_type'=>2]);
                                $alipaySevice->writeLog('订单状态更改成功：'.$v);
                            }
                        }
                        $order_merga = Db::name('orders_merge')->where('merge_sn',$order)->update(['status'=>1]);
                        if($order_merga){
                            $alipaySevice->writeLog('合并订单状态修改成功');
                        }
                    }
                }else{
                    // 修改订单支付状态
                    $data = Db::name('orders')->where('order_sn',$order)->select();
                    foreach($data as $v){
                        if($v['status']=='0'){
                            Db::name('orders')->where('id',$v['id'])->update(['status'=>1,'pay_type'=>2]);
                            $alipaySevice->writeLog('修改订单支付状态：' . $v['id']);
                        }
                    }
                }
            }
            echo "success";
            die ();
        } else {
            //验证失败
            $alipaySevice->writeLog("fail");
            echo "fail";    //请不要修改或删除
        }
    }
    protected function make_order()
    {
        return 'YP' . date('His') . str_pad(mt_rand(1, 9999999), 7, '0', STR_PAD_LEFT);
    }
    public function operation_log($string = ""){
        $path="./log";
        //判断目录存在否，存在给出提示，不存在则创建目录
        if(empty(is_dir($path))){
            //第三个参数是“true”表示能创建多级目录，iconv防止中文目录乱码
            $res=mkdir(iconv("UTF-8", "GBK", $path),0777,true);
        }
        //日志
        $url = "./log/" . date('Y-m-d-H-i') . ".txt";
        $string = date('Y-m-d H:i:s').$string;
        file_put_contents($url, $string . PHP_EOL, FILE_APPEND);
    }
}