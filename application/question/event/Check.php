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

    public function checkQlParams($param){
        $res = [
            'errCode' => '200',
            'errMsg'  => '校验成功',
            'data'    => [],
        ];
        if (empty($param['p']) || !isset($param['p']) || $param['p'] <= 0){
            return $this->setReturnMsg('00004');
        }
        $this->data['param_list']['p'] = (int)$param['p'];

        $res['data'] = $this->data;
        return $res;
    }

    public function checkQbParams($param){
        $res = [
            'errCode' => '200',
            'errMsg'  => '校验成功',
            'data'    => [],
        ];
        if (empty($param['qid']) || !isset($param['qid']) || $param['qid'] <= 0){
            return $this->setReturnMsg('00001');
        }
        $this->data['param_list']['qid'] = (int)$param['qid'];

        $res['data'] = $this->data;
        return $res;
    }

    public function checkQiParams($param){
        $res = [
            'errCode' => '200',
            'errMsg'  => '校验成功',
            'data'    => [],
        ];
        if (empty($param['qid']) || !isset($param['qid']) || $param['qid'] <= 0){
            return $this->setReturnMsg('00001');
        }
        $this->data['param_list']['qid'] = (int)$param['qid'];

        if (empty($param['uid']) || !isset($param['uid']) || $param['uid'] <= 0){
            return $this->setReturnMsg('00001');
        }

        $res['data'] = $this->data;
        return $res;
    }
}