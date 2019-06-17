<?php
namespace app\wechat\controller;

use app\base\controller\Base;
use app\wechat\event\Token;
use app\helper\helper;

class Message extends Base
{
    /**
     * @desc 获取发送微信模版消息通知结果
     *
     * @return json
     */
    public function getSendTemplateMessageRes(){
        $Result = [
            'errCode' => '200',
            'errMsg'  => '发送成功',
            'data'    => [],
        ];
        $post = request()->post();
        $event = new Token();
        if(!($res = $event->getWechatAccessToken())){
            $Result['errCode'] = '0';
            $Result['errMsg'] = '获取ACCESS_TOKEN失败!';
            return $Result;
        }
        $ACCESS_TOKEN = json_decode($res,true)['access_token'];
        $data = $this->_getSendMessageData($post);
        if($data == 'false'){
            $Result['errCode'] = '0';
            $Result['errMsg'] = '获取参数失败!';
            return $Result;
        }
        $data['data']['access_token'] = $ACCESS_TOKEN;
        $handle_res = $event->setData($data)->getSendMessageRes();
        if($handle_res['errCode']!='200'){
            return json($handle_res);
        }

        return json($handle_res);
    }


    protected function _getSendMessageData($post){
        // 获取用户信息
        $helper = new helper();
        $data = [];
        $getDynamicInfo = $helper->setData(['did'=>$post['did']])->GetDynamicById();
        $getDUserInfo = $helper->setData(['uid'=>$getDynamicInfo['uid']])->GetUserStatusById();
//        $getUserInfo = $helper->setData(['uid'=> $post['uid']])->GetUserStatusById();
        if(empty($getUserInfo)){
            return false;
        }

        $data['data']['open_id'] = $getDUserInfo['openid'];
        $data['data']['template_id'] = config('wechat')['template_id_arr']['leavingMessageToTemplateID'];

        if(!isset($post['redirect']) || empty($post['redirect'])){
            return false;
        }
        $data['data']['redirect'] = $post['redirect'];

        if(!isset($post['form_id']) || empty($post['form_id'])){
            return false;
        }
        $data['data']['form_id'] = $post['form_id'];

        if( !$post['content_value']){
            return false;
        }
        $data['data']['keyWordValue'] = [
            'keyword1' => ['value'=>base64_decode($getUserInfo['name'])],
            'keyword2' => ['value'=>$post['content_value']],
            'keyword3' => ['value'=>date('Y-m-d H:i:s')],
        ];

        return $data;


    }
}