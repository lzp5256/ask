<?php
namespace app\release\event;

class Check
{
    protected $data = [];

    protected $log_level = 'log'; //日志级别,记录请求参数只允许使用log级别


    /**
     * @desc 验证参数
     * @param int    index   问题类型索引
     * @param int    uid     用户id
     * @param string title   问题标题
     * @param string upload  图片地址
     * @date 2019.04.16
     * @author lizhipeng
     * @return array
     */
    public function checkQaParam($param){
        $Result = [
            'errCode' => '200',
            'errMsg'  => '验证成功',
            'data'    => [],
        ];
        if(!isset($param['pet_type']) || $param['pet_type'] =='9999'){
            $Result['errCode'] = 'L10060';
            $Result['errMsg'] = '抱歉,请选择问题类型,错误码[L10060]';
            return $Result;
        }
        $this->data['param']['pet_type'] = $param['pet_type'];

        if(!isset($param['qa_type']) || $param['qa_type'] =='9999'){
            $Result['errCode'] = 'L10061';
            $Result['errMsg'] = '抱歉,请选择问题类型,错误码[L10061]';
            return $Result;
        }
        $this->data['param']['QA_type'] = $param['qa_type'];

        if(empty($param['uid'])){
            $Result['errCode'] = 'L10062';
            $Result['errMsg'] = '错误码[L10062]';
            return $Result;
        }
        $this->data['param']['uid'] = $param['uid'];

        if(empty($param['title'])){
            $Result['errCode'] = 'L10063';
            $Result['errMsg'] = '抱歉,请输入问题描述,错误码[L10063]';
            return $Result;
        }
        $this->data['param']['title'] = $param['title'];

        if(isset($param['upload'])){
            $this->data['param']['upload'] = $param['upload'];
        }

        $Result['data'] = $this->data;
        return $Result;
    }

    /**
     * @desc 验证发布文章参数
     * @param string    cover   封面地址
     * @param string    title   文章标题
     * @param string    content 文章正文
     * @param int       uid     用户id
     * @date 2019.04.18
     * @author lizhipeng
     * @return array
     */
    public function checkArticleParam($param){
        $Result = [
            'errCode' => '200',
            'errMsg'  => '验证成功',
            'data'    => [],
        ];

        if(empty($param['uid'])){
            $Result['errCode'] = 'L10067';
            $Result['errMsg'] = '错误码[L10067]';
            return $Result;
        }
        $this->data['param']['uid'] = $param['uid'];

        if(empty($param['title'])){
            $Result['errCode'] = 'L10069';
            $Result['errMsg'] = '抱歉,请输入文章标题';
            return $Result;
        }
        $this->data['param']['title'] = $param['title'];

        if(empty($param['content'])){
            $Result['errCode'] = 'L10070';
            $Result['errMsg'] = '抱歉,请输入文章正文';
            return $Result;
        }
        $this->data['param']['content'] = $param['content'];

        if(!empty($param['cover'])){
            $this->data['param']['cover'] = $param['cover'];
        }

        $Result['data'] = $this->data;
        return $Result;
    }

    /**
     * @desc 验证发布评论参数
     * @param int       uid        用户id
     * @param int       did        动态id
     * @param string    content    评论内容
     * @date 2019.04.23
     * @author lizhipeng
     * @return array
     */
    public function checkCommentParam($param){
        $Result = [
            'errCode' => '200',
            'errMsg'  => '验证成功',
            'data'    => [],
        ];
        if(empty($param['uid'])){
            $Result['errCode'] = 'L10073';
            $Result['errMsg'] = '抱歉,系统异常，请联系管理员';
            return $Result;
        }
        $this->data['param']['uid'] = $param['uid'];

        if(empty($param['did'])){
            $Result['errCode'] = 'L10074';
            $Result['errMsg'] = '抱歉,系统异常,请联系管理员';
            return $Result;
        }
        $this->data['param']['did'] = $param['did'];

        if(empty($param['content'])){
            $Result['errCode'] = 'L10075';
            $Result['errMsg'] = '抱歉,请输入评论内容';
            return $Result;
        }
        $this->data['param']['content'] = trim($param['content']);

        if(empty($param['action'])){
            $Result['errCode'] = 'L10076';
            $Result['errMsg'] = '抱歉,系统异常';
            return $Result;
        }
        $this->data['param']['action'] = trim($param['action']);

        $Result['data'] = $this->data;
        return $Result;
    }

    /**
     * @desc 验证点赞参数
     * @param int       uid        用户id
     * @param int       did        动态id
     * @date 2019.04.25
     * @author lizhipeng
     * @return array
     */
    public function checkCommentLikeParam($param){
        $Result = [
            'errCode' => '200',
            'errMsg'  => '验证成功',
            'data'    => [],
        ];

        if(empty($param['uid'])){
            $Result['errCode'] = 'L10083';
            $Result['errMsg'] = '抱歉,系统异常，请联系管理员';
            return $Result;
        }
        $this->data['param']['uid'] = $param['uid'];

        if(empty($param['did'])){
            $Result['errCode'] = 'L10084';
            $Result['errMsg'] = '抱歉,系统异常，请联系管理员';
            return $Result;
        }
        $this->data['param']['did'] = $param['did'];

        if(empty($param['action'])){
            $Result['errCode'] = 'L10076';
            $Result['errMsg'] = '抱歉,系统异常';
            return $Result;
        }
        $this->data['param']['action'] = trim($param['action']);

        $Result['data'] = $this->data;
        return $Result;
    }
}
