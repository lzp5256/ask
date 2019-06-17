<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/2/19
 * Time: 5:11 PM
 */
namespace app\region\model;

use think\Model;

class Region extends  Model
{
    protected $table='region';

    public function findRegion($where)
    {
        return $this->where($where)->find();
    }

    public function selectRegion($where,$offset,$num,$field='*')
    {
        return $this->where($where)->field($field)->page("$offset,$num")->select();
    }
}