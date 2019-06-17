<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/1/9
 * Time: 2:49 PM
 */
namespace app\wechat\model;

use think\Model;

class Token extends Model
{
    protected $table = 'token';

    public function addToken($data)
    {
        $this->data($data);
        return $this->save();
    }

    public function findToken($where)
    {
        return $this->where($where)->order('id desc')->find();
    }
}