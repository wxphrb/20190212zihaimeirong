<?php/** * Created by PhpStorm. * User: Administrator * Date: 2018/1/11 * Time: 10:52 */namespace app\home\model;use think\Model;class Goods extends Model{        public function goods_regroup($where,$map,$level_id=0){            $data = $this->where($where)                ->field('id,sell,title,picurl,saleprice,kucun_all,goodsattr')                ->order('id desc')                ->order($map)                ->select();            //对应等级显示对象价格            if(!empty($level_id) && $level_id>0){                foreach($data as &$v){                    $goodsattr = unserialize($v['goodsattr']);                    foreach($goodsattr as $kk=>$vv){                        $ext = 'price'.$level_id;                        $v['saleprice']   = $vv[$ext];                        $v['kucun']       = $vv['kucun'];                        $v['gg']          = $kk;                        break;                    }                }            }            return $data;        }}