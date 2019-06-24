<?php
namespace app\release\controller;

use app\question\event\Check;
use app\question\event\Handles;

class Question
{

    public function qr(){
        $res = [
            'errCode' => '200',
            'errMsg'  => '发布成功',
            'data'    => [],
        ];
        $params = request()->post('');
        $check_event   = new Check();
        $handles_event = new Handles();

        if(($check_res = $check_event->checkQrParams($params)) && $check_res['errCode'] != 200 ){
            return json($check_res);
        }

        if(($handles_res = $handles_event->setData($check_res['data'])->handleQrRes()) && $handles_res['errCode'] != 200){
            return json($handles_res);
        }

        return json($res);
    }
}