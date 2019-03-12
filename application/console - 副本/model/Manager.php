<?php
namespace app\console\model;
use think\Session;
use think\Request;
use think\Model;

class Manager extends Model
{
    // 指定表名,不含前缀
    protected $name = 'manager';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 登录方法
    public static function login()
    {
        $map = [
            'username' => Request::instance()->post('username'),
            'password' => Request::instance()->post('password', '', 'mcs'),
            'status'   => 1
        ];
        $find = self::where($map)->find();

        // 用户名&密码 验证成功
        if ($find) {
            Session::set('manage_id', $find['id']);
            return true;
        }
        return false;
    }

    /**
     * [saveVerify description]模型验证 添加和修改
     * @param  string $id [description]主键id
     * @return [type]     [description]
     */
    public static function saveVerify($data,$id = ''){
        $Model = new Manager();
        
        if (empty($data['password'])&&!empty($id)) {
            unset($data['password']);
        } else {
            $data['password'] = mcs($data['password']);
        }
        //判断是添加还是修改
        $handle = empty($id)? false : true;

        // 调用验证器类进行数据验证
        $result = $Model->validate(true)->allowField(true)->isUpdate($handle)->save($data);
        if(false === $result){
            // 验证失败 输出错误信息
            return $Model->getError();
        }else{
            return true;
        }
    }

}
