<?php
namespace app\question\controller;

use app\question\event\Check;
use app\question\event\Handles;
use app\question\event\Browse;

class Question
{
    /**
     * 获取问题列表
     *
     * @author lizhipeng
     * @date   2019.06.25
     * @return \think\response\Json
     */
    public function ql(){
        $res = [
            'errCode' => '200',
            'errMsg'  => '获取成功',
            'data'    => [],
        ];
        $param = request()->post();
        $check_event   = new Check();
        $handles_event = new Handles();

        if(($check_res = $check_event->checkQlParams($param)) && $check_res['errCode'] != '200'){
            return json($check_res);
        }

        if(($handles_res = $handles_event->setData($check_res['data'])->handleQlRes()) && $handles_res['errCode'] != '200'){
            return json($handles_res);
        }
        $res['data'] = $handles_res['data'];
        return json($res);
    }

    /**
     * 问题浏览
     *
     * @author lizhipeng
     * @date   2019.06.25
     * @return \think\response\Json
     */
    public function qb(){
        $res = [
            'errCode' => '200',
            'errMsg'  => '添加成功',
            'data'    => [],
        ];
        $param = request()->post();
        $check_event  = new Check();
        $browse_event = new Browse();

        if(($check_res = $check_event->checkQbParams($param)) && $check_res['errCode'] != '200'){
            return json($check_res);
        }
        if(($addRes = $browse_event->setData($check_res['data'])->addBrowse()) && $addRes['errCode'] != '200'){
            return json($addRes);
        }

        return json($res);
    }

    /**
     * 获取问题详情
     *
     * @author lizhipeng
     * @date   2019.06.25
     * @return \think\response\Json
     */
    public function qi(){
        $res = [
            'errCode' => '200',
            'errMsg'  => '查询成功',
            'data'    => [],
        ];
        $param = request()->post();
        $check_event   = new Check();
        $handles_event = new Handles();

        if(($check_res = $check_event->checkQiParams($param)) && $check_res['errCode'] != '200'){
            return json($check_res);
        }
        if(($handles_res = $handles_event->setData($check_res['data'])->handlesQiRes()) && $handles_res['errCode'] != '200'){
            return json($handles_res);
        }
        $res['data'] = $handles_res;
        return json($res);
    }
}