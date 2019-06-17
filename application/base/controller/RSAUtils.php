<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/3/22
 * Time: 11:44 AM
 */
namespace app\base\controller;

use think\config;

class RSAUtils
{
    protected $public_key  = ''; //加密公钥
    protected $private_key = ''; //加密私钥
    protected $sign_key    = ''; //签名私钥
    protected $verify      = ''; //验签公钥

    /**
     * @desc 公钥加密
     * @param string $source_data 加密数据
     * @date 2019.03.22
     *
     * @return string $data
     */
    public function pubkeyEncrypt($source_data)
    {
        $data = "";
        $dataArray = str_split($source_data, 117);
        foreach ($dataArray as $value) {
            $encryptedTemp = "";
            openssl_public_encrypt($value,$encryptedTemp,config('rsa.public_key'));//公钥加密
            $data .= base64_encode($encryptedTemp);
        }
        return $data;
    }

    /**
     * @desc 私钥解密
     * @param string $eccryptData 需要解密的数据
     * @date 2019.03.22
     *
     * @return string $decrypted
     */
    function pikeyDecrypt($eccryptData) {
        $decrypted = "";
        $decodeStr = base64_decode($eccryptData);
        $enArray = str_split($decodeStr, 256);

        foreach ($enArray as $va) {
            openssl_private_decrypt($va,$decryptedTemp,config('rsa.private_key'));//私钥解密
            $decrypted .= $decryptedTemp;
        }
        return $decrypted;
    }


}