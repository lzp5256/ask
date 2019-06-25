<?php
namespace app\question\model;

use think\Model;

class AskQuestion extends Model
{
    protected $table='question';

    /**
     * 获取多条记录
     *
     * @param array $where 查询条件
     * @param string $field 查询字段 默认为全部
     * @param string $order 排序方式 默认id倒序
     * @param int $offset 查询页数
     * @param int $num 查询条
     *
     * @return array
     */
    public function selectAll($where,$offset=0,$num=1,$field='*',$order='qid desc')
    {
        return $this->where($where)->field($field)->order($order)->page("$offset,$num")->select();

    }

    /**
     * 获取一条记录
     *
     * @param $where
     * @param $field
     * @return array|false|\PDOStatement|string|Model
     */
    public function findOne($where,$field='*')
    {
        return $this->where($where)->field($field)->find();

    }

    /**
     * 添加一条记录
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        $this->data($data);
        return $this->save();
    }

    /**
     * 更新
     *
     * @param $where
     * @param $data
     *
     * @return false|int
     */
    public function saveOne($where,$data)
    {
        return $this->where($where)->update($data);
    }

    /**
     * 数据累加/减更新
     */
    public function setUpdate($where,$type,$key,$value = 1){
        switch ($type){
            case 'Inc':
                return $this->where($where)->setInc($key,$value);
                break;
            case 'Dec':
                return $this->where($where)->setDec($key,$value);
                break;
            default:
                return false;
        }
    }
}