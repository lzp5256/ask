<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/1/2
 * Time: 11:54 AM
 */
namespace app\user\controller;

use app\base\controller\Base;
use app\wechat\model\Token;

class User extends Base
{
    /**
     * @desc 获取用户登录状态用于验证用户是否需要重新登录
     * @date 2019.04.17
     * @author lizhipeng
     * @return array
     */
    public function getLoginExpirationForWechat(){
        $Result = [
            'errCode' => '200',
            'errMsg'  => '获取成功!',
            'data'    => [],
        ];
        $code = request()->post('code');

        if(empty($code)){
            $Result['errCode'] ='L10058';
            $Result['errMsg'] ='错误码[L10058]';
        }
        $wechatInfo = getWechatKeyInfo($code);

        if(isset($wechatInfo['errcode']) && !empty($wechatInfo)){
            $Result['errCode'] = 'L10059';
            $Result['errMsg'] = '错误码[L10059]';
            return json($Result);
        }
        $openId = $wechatInfo['openid'];
        $model = new Token();
        $findTokenInfo  = $model->findToken(['openid'=>$openId,'status'=>1]);

        if(!$findTokenInfo){
            $Result['errCode'] = '501';
            return json($Result);
        }
        $Result['data']['expiration_date'] = strtotime($findTokenInfo['etime']);
        $Result['data']['uid'] = $findTokenInfo['uid'];

        return json($Result);
    }
}