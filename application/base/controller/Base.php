<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/1/2
 * Time: 3:12 PM
 */
namespace app\base\controller;

use app\wechat\model\Token;
use think\Exception;

class Base
{
    // 储存数据
    protected $data = [];

    public function __construct()
    {
        $result = $this->check();
        return $result;
    }

    public function check()
    {
        $Result = [
            'errCode' => '200',
            'errMsg' => 'success',
            'data' => [],
        ];
        // 判断是否是post请求
        if(request()->isPost() != true){
            $Result['errCode'] = 'L10008';
            $Result['errMsg'] = '抱歉,Method Error！';
            return $Result;
        }

        // 验证token
        $token = request()->post('token');
        $uid = request()->post('uid');

        if(empty($token)){
            $Result['errCode'] = 'L10009';
            $Result['errMsg'] = '抱歉,参数[Token]不能为空！';
            return $Result;
        }
        if(empty($uid)){
            $Result['errCode'] = 'L10010';
            $Result['errMsg'] = '抱歉,参数[Uid]不能为空！';
            return $Result;
        }

        $model = new Token();
        $res = $model->findToken(['token'=>$token,'uid'=>$uid]);
        if(empty($res)){
            $Result['errCode'] = 'L10011';
            $Result['errMsg'] = '抱歉,未查询到有效Token！';
            return $Result;
        }

        return $Result;
    }

    public function setData($setData)
    {
        $this->data = $setData;
        return $this;
    }

    /**
     * 生成用户token
     * @param $data 用户session_key
     * @return string
     */
    public function encryption($data)
    {
        return md5($data.rand(0,9).time());
    }


}