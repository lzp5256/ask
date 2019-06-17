<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/3/23
 * Time: 4:42 PM
 */
namespace app\user\model;

use think\model;

class UserSign extends Model
{
    protected $table='user_sign';

    /**
     * 查询一条签到信息
     *
     * @param $where
     * @param $field
     * @return array|false|\PDOStatement|string|Model
     */
    public function findSign($where,$field='*')
    {
        return $this->where($where)->field($field)->order('id desc')->find();
    }

    /**
     * 更新签到信息
     *
     * @param $where
     * @param $data
     *
     * @return false|int
     */
    public function updateUser($where,$data)
    {
        return $this->where($where)->update($data);
    }
}

