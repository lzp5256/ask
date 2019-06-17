<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * @desc 封装var_dump
 */
function vp($data){
    echo '<pre>';
    var_dump($data);
    echo '<pre/>';
    die;
}

/**
 * @desc 封装 curl 的调用接口，post的请求方式
 * @param string $url 访问地址
 * @param array|string $request 请求参数
 * @param int $timeout 时间
 * @param string $method 请求方式 不填为GET
 * @return mixed
 */
function sendCurlRequest($url,$request='',$method='',$timeout = 5 ){
    $log = [];
    $curl_index=time().rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
    $log['curl_arr'][$curl_index]['url'] = $url;
    $log['curl_arr'][$curl_index]['request_data'] = $request;
    $log['curl_arr'][$curl_index]['header'] = [];
    $log['curl_arr'][$curl_index]['method'] = empty($method) ? 'GET' : 'POST';
    $log['curl_arr'][$curl_index]['time_start']=date('Y-m-d H:i:s');
    $con = curl_init();
    curl_setopt($con, CURLOPT_URL,$url);
    curl_setopt($con, CURLOPT_HEADER, false);
    // 默认为Get请求Post请求为true
    if($method){
        curl_setopt($con, CURLOPT_POSTFIELDS, $request);
        curl_setopt($con, CURLOPT_POST,true);
    }

    curl_setopt($con, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($con, CURLOPT_TIMEOUT,(int)$timeout);
    $output = curl_exec($con);
    $log['curl_arr'][$curl_index]['time_end']=date('Y-m-d H:i:s');
    if($error=curl_error($con)){
        $log['curl_arr'][$curl_index]['error']=$error;
        die($error);
    }
    curl_close($con);
    $log['curl_arr'][$curl_index]['result']=$output;
    \think\Log::write(json($log),'info');
    return $output;
}

/**
 * @desc 验证手机号是否正确
 * @param string $phone 手机号
 * @return bool
 */
function isMobile($phone){
    $search = '/^0?1[3|4|5|6|7|8|9][0-9]\d{8}$/';
    if ( preg_match( $search, $phone ) ) {
        return ( true );
    } else {
        return ( false );
    }
}

/**
 * @desc 类型转换
 * @param $str 类型编号
 * @return mixed
 */
function strToType($str){
    $data = ['1'=>'狗', '2'=>'猫', '3'=>'其他'];
    return $data[$str];
}

/**
 * @desc  json转换数组
 * @param array $strArr 需要转换的json字符串
 * @return array
 */
function strToJson($strArr){
    $data = [];
    foreach ($strArr as $k => $v){
        if(empty($v)){
            $data[$k] = [];
        }else{
            $data[$k] = json_decode($v,true);
        }
    }
    return $data;
}


function generateTree($array){
    //第一步 构造数据
    $items = array();
    foreach($array as $value){
        $items[$value['rg_id']] = $value;
    }
    //第二部 遍历数据 生成树状结构
    $tree = array();
    foreach($items as $key => $value){
        if(isset($items[$value['parent_id']])){
            $items[$value['parent_id']]['son'][] = &$items[$key];
        }else{
            $tree[] = &$items[$key];
        }
    }
    return $tree;
}

function getWechatKeyInfo($code){
    $wechatData = sendCurlRequest("https://api.weixin.qq.com/sns/jscode2session?appid=".config('wechat')['appid']."&secret=".
        config('wechat')['secret']."&js_code=".$code. "&grant_type=".config('wechat')['grant_type']);

    $result = json_decode($wechatData,true);
    return $result;
}

/**
 * @desc 对象转化数组 | 一维数组
 * @param $data
 * @return array
 */
function findDataToArray($data){
    return $data->toArray();
}
/**
 * @desc 对象转化数组 | 多维数组
 * @param $data
 * @return array
 */
function selectDataToArray($data){
    return collection($data)->toArray();
}

/**
 * @desc 记录日志方法
 * @param string $content 日志内容,格式为json
 * @param string $type    日志级别，log ,error,debug
 */
function writeLog($content='',$type ='log' ){
    \think\Log::record($content,$type);
}

/**
 * 格式化日志参数
 * @param $title
 * @param $param
 * @param $content
 * @return array
 */
function getWriteLogInfo($title,$param,$content){
    $data = [
        'title'    => $title,
        'param'    => $param,
        'function' => getFunctionName(),
        'content'  => $content,
        'date'     => date('Y-m-d H:i:s'),
    ];
    return $data;
}

/**
 * 获取方法名
 * @return string
 */
function getFunctionName(){
    return $str = '['.\think\Request::instance()->module().'-'.\think\Request::instance()->controller().']';
}


