<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/3/28
 * Time: 5:12 PM
 */
namespace app\user\event;

use app\base\controller\Base;
use app\user\model\UserCbAccountChange as UserCbAccountChangeModel;

class UserCbAccountChange extends Base
{
    /**
     * @desc  【公用方法，外部可调用】更新用户宠币账户明细
     * @Author SaltedFish
     * @Date 2019.03.28
     * @return array
     */
    public function updateUserCbAccountChange()
    {
        $Result = [
            'errCode' => '200',
            'errMsg' => 'success',
            'data' => [],
        ];
        $arr  = $this->data;
        $UserCbAccountChangeModel = new UserCbAccountChangeModel();

        // 新增
        $UserCbAccountChangeModel -> uid = (int)$arr['param']['uid'];
        $UserCbAccountChangeModel -> uca_id = (int)$arr['user_cb_account']['id'];
        $UserCbAccountChangeModel -> type = (string)$arr['param']['type'];
        $UserCbAccountChangeModel -> cb_id = (int)$arr['task_list']['id'];
        $UserCbAccountChangeModel -> num = (string)$arr['task_list']['integral'];
        $UserCbAccountChangeModel -> status = '1';
        $UserCbAccountChangeModel -> created_at = date('Y-m-d H:i:s');
        $res = $UserCbAccountChangeModel -> save();

        if(!$res){
            $Result['errCode'] = 'L10056';
            $Result['errMsg'] = '抱歉,账户详情更新失败！';
            return $Result;
        }

        return $Result;
    }

    public function checkCompleteState()
    {
        $Result = [
            'errCode' => '200',
            'errMsg' => 'success',
            'data' => [],
        ];
        $model = new UserCbAccountChangeModel();
        $findUserChangeInfo = $model->selectUserCbAccountChange(
            [
                'uid'=>$this->data['param']['uid'],
                'status'=>'1',
                'cb_id'=>$this->data['param']['tid'],
                'type' => '1',
                'created_at' => ['gt',date('Y-m-d',time())]
            ], 0,100
        );
        if(count($findUserChangeInfo)>0 ){
            $Result['errCode'] = 'L10053';
            $Result['errMsg'] = '抱歉,您今日已完成此任务！';
            return $Result;
        }


        return $Result;

    }

}