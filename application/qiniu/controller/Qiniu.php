<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/1/16
 * Time: 6:13 PM
 */
namespace app\qiniu\controller;

use Qiniu\Auth;
use think\Config;

class Qiniu
{
    /**
     * 获取七牛储存所需要的token
     * @date 2019/01/16
     * @return json
     */
    public function getQiniuToken()
    {
        import('qiniu.autoload',VENDOR_PATH);
        $config = Config::get('qiniu');
        //用于签名的公钥和私钥
        $AccessKey = $config['AccessKey'];
        $SecretKey = $config['SecretKey'];
        // 初始化签权对象
        $auth = new Auth($AccessKey,$SecretKey);
        // 空间名  https://developer.qiniu.io/kodo/manual/concepts
        $bucket = 'pet-lizhipeng';
        // 生成上传Token
        $token = $auth->uploadToken($bucket);
        return json(['uptoken'=>$token]);
    }

    public function callBack(){
        import('qiniu.autoload',VENDOR_PATH);
        $config = Config::get('qiniu');
        //用于签名的公钥和私钥
        $AccessKey = $config['AccessKey'];
        $SecretKey = $config['SecretKey'];
        // 初始化签权对象
        $auth = new Auth($AccessKey,$SecretKey);
        $bucket = 'pet-lizhipeng';
        //获取回调的body信息
        $callbackBody = file_get_contents('php://input');
        //回调的contentType
        $contentType = 'application/x-www-form-urlencoded';
        //回调的签名信息，可以验证该回调是否来自七牛
        $authorization = $_SERVER['HTTP_AUTHORIZATION'];
        $url = 'test.yipinchongke.com/Qiniu/callBack';
        $isQiniuCallback = $auth->verifyCallback($contentType, $authorization, $url, $callbackBody);
        if ($isQiniuCallback) {
            $resp = array('errCode' => '200','errMsg'=>'success');
        } else {
            $resp = array('errCode' => '0','errMsg'=>'failed');
        }
        echo json_encode($resp);

    }
}