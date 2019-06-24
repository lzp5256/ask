<?php
namespace app\question\event;

use app\base\controller\Base;

class Check extends Base
{
    public function checkQrParams($param){
        $res = [
            'errCode' => '200',
            'errMsg'  => '校验成功',
            'data'    => [],
        ];
        if (empty($param['uid']) || !isset($param['uid']) || $param['uid'] <= 0){
            return $this->setReturnMsg('00001');
        }
        $this->data['param_list']['uid'] = (int)$param['uid'];

        if (empty($param['title'])){
            return $this->setReturnMsg('00002');
        }
        $this->data['param_list']['title'] = (string)$param['title'];

        if (isset($param['describe']) && !empty($param['describe']) ){
            $this->data['param_list']['describe'] = trim($param['describe']);
        }
        $this->data['param_list']['describe'] = '';

        $res['data'] = $this->data;
        return $res;

    }
}