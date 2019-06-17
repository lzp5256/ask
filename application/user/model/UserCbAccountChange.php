<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/3/23
 * Time: 4:45 PM
 */
namespace app\user\model;

use think\model;

class UserCbAccountChange extends Model
{
    protected $table ='user_cb_account_change';

    /**
     * @desc 查询兑换列表
     *
     * @param array $where 查询条件
     * @param string $field 查询字段 默认为全部
     * @param string $order 排序方式 默认id倒序
     * @param int $offset 查询页数
     * @param int $num 查询条
     *
     * @return array
     */
    public function selectUserCbAccountChange($where,$offset=0,$num=1,$field='*',$order='id desc')
    {
        return $this->where($where)->field($field)->order($order)->limit("$offset,$num")->select();
    }
}