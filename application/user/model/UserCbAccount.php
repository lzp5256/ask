<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/3/23
 * Time: 4:43 PM
 */
namespace app\user\model;

use think\model;

class UserCbAccount extends  Model
{
    protected $table='user_cb_account';

    /**
     * 查询一条账户信息
     *
     * @param $where
     * @param $field
     * @return array|false|\PDOStatement|string|Model
     */
    public function findUserCbAccount($where,$field='*')
    {
        return $this->where($where)->field($field)->find();
    }

    /**
     * 更新账户信息
     *
     * @param $where
     * @param $data
     *
     * @return false|int
     */
    public function updateUserCbAccount($where,$data)
    {
        return $this->where($where)->update($data);
    }
}