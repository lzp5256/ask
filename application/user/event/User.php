<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/1/8
 * Time: 5:34 PM
 */
namespace app\user\event;

use app\base\controller\Base;
use app\user\model\User as UserModel;

class User extends Base {

    public function getAllUserList(){
        $uid = $this->data['uid'];
        $where['state'] = 1;
        $where['uid'] = ['IN',$uid];
        $model = new UserModel();
        $data = $model->selectUser($where,0,count($uid));
        if(empty($data)){
            return [];
        }
        $arr = [];
        foreach ($data as $k => $v){
            $arr[$v['uid']] = [
                'name' => $v['name'],
            ];
        }
        return $arr;
    }

}













