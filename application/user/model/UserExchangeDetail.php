<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/4/1
 * Time: 2:55 PM
 */
namespace app\user\model;

use think\Model;

class UserExchangeDetail extends Model
{
    protected $table = 'user_exchange_detail';

    /**
     * 查询一条兑换明细信息
     *
     * @param $where
     * @param $field
     * @return array|false|\PDOStatement|string|Model
     */
    public function findUserExchangeDetail($where,$field='*')
    {
        return $this->where($where)->field($field)->find();
    }

    /**
     * 查找所有兑换明细信息
     *
     * @param array $where 查询条件
     * @param string $field 查询字段 默认为全部
     * @param string $order 排序方式 默认id倒序
     * @param int $offset 查询页数
     * @param int $num 查询条
     *
     * @return array
     */
    public function selectUserExchangeDetailList($where,$offset=0,$num=1,$field='*',$order='id desc')
    {
        return $this->where($where)->field($field)->order($order)->limit("$offset,$num")->select();
    }

    /**
     * 更新兑换明细信息
     *
     * @param $where
     * @param $data
     *
     * @return false|int
     */
    public function updateUserExchangeDetail($where,$data)
    {
        return $this->where($where)->update($data);
    }

    /**
     * 添加明细
     *
     * @param $data
     *
     * @return mixed
     */
    public function addUserExchangeDetail($data)
    {
        $this->data($data);
        return $this->save();
    }

}