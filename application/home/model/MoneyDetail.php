<?php/** * Created by PhpStorm. * User: Administrator * Date: 2018/1/11 * Time: 10:52 */namespace app\home\model;use think\Model;class MoneyDetail extends Model{    protected static function init()    {    }    public function getCreateTimeAttr($value)    {        return date("Y-m-d H:i:s",$value);    }}