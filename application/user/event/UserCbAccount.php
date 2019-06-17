<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/3/28
 * Time: 3:45 PM
 */
namespace app\user\event;

use app\base\controller\Base;
use app\user\model\UserCbAccount as UserCbAccountModel;

class UserCbAccount extends Base
{
    /**
     * @desc  【公用方法，外部可调用】更新用户宠币账户
     * @Author SaltedFish
     * @Date 2019.03.28
     * @return array
     */
    public function updateUserCb()
    {
        $Result = [
            'errCode' => '200',
            'errMsg' => 'success',
            'data' => [],
        ];
        $arr  = $this->data;
        // 查询是否存在此用户账户信息，新用户新增，老用户更新
        $userCbAccountModel = new UserCbAccountModel();
        $findUserCbAccountInfo = $userCbAccountModel->findUserCbAccount(['uid'=>(int)$arr['param']['uid'],'status'=>'1']);

        if(empty($findUserCbAccountInfo)){
            // 新增
            $userCbAccountModel -> uid = (int)$arr['param']['uid'];
            $userCbAccountModel -> num = (string)$arr['task_list']['integral'];
            $userCbAccountModel -> created_at = date('Y-m-d H:i:s');
            $userCbAccountModel -> status = '1';
            $userCbAccountModel -> use_num = '0';
            $userCbAccountModel -> save();
            $Result['data']['user_cb_account'] = ['id'=>$userCbAccountModel->id];
        }else{
            // 更新
            $updateRes = $userCbAccountModel->updateUserCbAccount(
                [
                    'id'=>(int)$findUserCbAccountInfo['id'],
                    'status'=>'1',
                    'uid' => (int)$arr['param']['uid'],
                ],
                [
                    'num' => $findUserCbAccountInfo['num'] + $arr['task_list']['integral'],
                ]
            );
            if(!$updateRes){
                $Result['errCode'] = 'L10055';
                $Result['errMsg'] = '抱歉,账户更新失败！';
                return $Result;
            }
            $Result['data']['user_cb_account'] = ['id'=>$findUserCbAccountInfo->id];
        }
        return $Result;
    }
}