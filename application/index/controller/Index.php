<?php
namespace app\index\controller;

use app\base\controller\Base;
use app\index\event\Index as IndexEvent;
use app\user\model\User;


class Index extends Base
{
    public function __construct(){}

    public function getReList()
    {
        $Result = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        $page = request()->post('page');
        $event = new IndexEvent();
        if(($res = $event->getReList($page)) && $res['errCode'] != '200'){
            return json($res);
        }
        $Result['data'] = $res['data'];
        return json($Result);
    }

    /**
     * @desc 获取首页信息
     */
    public function home(){
        $Result = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        $page = request()->post('page');
        $event = new IndexEvent();
        if(($res = $event->setData(['page'=>$page])->getHomeList()) && $res['errCode'] != '200'){
            return json($res);
        }
        $Result['data'] = $res['data'];
        return json($Result);
    }

    public function V2Home(){
        $Result = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        $page = request()->post('page');
        $event = new IndexEvent();
        if(($res = $event->setData(['page'=>$page])->getHomeListVT()) && $res['errCode'] != '200'){
            return json($res);
        }
        $Result['data'] = $res['data'];
        return json($Result);
    }

    /**
     * @desc 获取新注册的用户
     */
    public function getUserList(){
        $Result = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        $model = new User();
        $user  = $model->selectUser(['status'=>1],0,10);
        if(empty($user)){
            $list  = [];
        }
        $list  = selectDataToArray($user);
        foreach ($list as $k => $v){
            $list[$k]['user_name'] = base64_decode($v['name']);
        }
        $Result['data'] = $list;
        return json($Result);
    }


}
