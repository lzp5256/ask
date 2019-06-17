<?php
namespace app\other\model;

use think\Model;

class OtherFlag extends Model
{
    protected $table = 'other_flag';

    public function getOtherFlagList($where,$field='*',$order='id desc'){
        return $this->where($where)->field($field)->order($order)->select();
    }

    public function getOtherFlagPageList($where,$offset=0,$num=1,$field='*',$order='id desc'){
        return $this->where($where)->field($field)->order($order)->page("$offset,$num")->select();
    }

    public function getOneOtherFlagInfo($where,$field='*'){
        return $this->where($where)->field($field)->find();
    }

    public function getAddOtherFlagRes($data){
        $this->data($data);
        return $this->save();
    }

    public function getSaveOtherFlagRes($where,$data){
        return $this->where($where)->update($data);
    }
}

