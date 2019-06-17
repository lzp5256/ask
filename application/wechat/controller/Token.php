<?php
/**
 * 微信相关操作
 * User: lizhipeng
 * Date: 2019/1/2
 * Time: 3:38 PM
 */

namespace app\wechat\controller;

use app\wechat\event\Token as WeTokenEvent;
use app\wechat\event\CheckParams as CheckGetWeTokenEvent;
class Token
{

    /**
     * 登录凭证校验。
     *
     * Author:lizhipeng
     * Date:2019/01/03
     */
    public function getWechatToken()
    {
        $params = request()->param();
        $vevent = new CheckGetWeTokenEvent();
        if(!($res = $vevent->checkGetTokenParams($params)) || $res['errCode'] != 200){
            // TODO 错误返回时应该记一条err日志 -待处理
            return json($res);
        }

        $wevent = new WeTokenEvent();
        $result = $wevent->setData($res['data'])->getWechatToken();

        return json($result);
    }

    public function __construct()
    {
        // 判断是否是post请求
        if(request()->isPost() != true){
            return 'request is not post';
        }
    }
}