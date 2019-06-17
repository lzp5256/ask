<?php
namespace app\helper;

use app\base\controller\Base;
use app\dynamic\model\Dynamic;
use app\dynamic\model\DynamicComment as DynamicCommentModel;
use app\dynamic\model\DynamicLike;
use app\other\model\OtherFlag;
use app\region\model\Region;
use app\user\model\User;
use app\activity\model\Activity;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class helper extends Base {
    /** 注:公用方法首字母大写 */

    protected $data_type  = 1; //1-字符串(默认) 2-数组
    const EFFECTIVE_STATE = '1'; //有效状态值
    const INVALID_STATE   = '2'; //无效状态值

    /**
     * 公用方法 | 获取评论列表
     *
     * @return array
     */
    public function GetCommentList(){
        $where['status'] = '1';
        $where['did'] = $this->data['did'];  // 默认为字符串
        $where['action'] = $this->data['action'];
        // 如果传入值为数组，则更换条件
        if(is_array($this->data['did'])){
            $this->data_type = 2;
            $where['did'] = ['IN',$this->data['did']];
        }

        $model = new DynamicCommentModel();
        $data  = selectDataToArray($model->where($where)->select());
        if(empty($data)){
            return [];
        }
        $list = [];
        foreach ($data as $k => $v){
            $list[$v['did']]['list'][] = $v;
        }
        return $list;
    }

    /**
     * 公用方法 | 检查用户是否有效
     *
     * return array
     */
    public function GetUserStatusById(){
        $uid = $this->data['uid'];
        $model = new User();
        $findUserInfo = $model->findUser(['id'=>(int)$uid,'status'=>self::EFFECTIVE_STATE]);
        if(empty($findUserInfo)){
            return [];
        }
        return findDataToArray($findUserInfo);
    }

    /**
     * 公用方法 | 检查用户是否在规定动态内点赞状态
     *
     * return array
     */
    public function GetUserLikeState(){
//        $uid = $this->data['uid'];
//        $did = $this->data['did'];
        $where['did'] = $this->data['did'];
        $where['action'] = $this->data['action'];
        if(is_array($this->data['did'])){
            $this->data_type = 2;
            $where['did'] = ['IN',$this->data['did']];
        }
        if($this->data_type == '1'){
            $where['uid'] = $this->data['uid'];
        }
        $model = new DynamicLike();
        $data = $model->where($where)->select();
        if(empty($data)){
            return [];
        }
        $arr = [];
        foreach ($data as $k => $v){
            $arr[$v['uid']] = 1;
        }
        return $arr;
    }



    /**
     * 公用方法 | 获取动态详情
     *
     * return array
     */
    public function GetDynamicById(){
        $did = $this->data['did'];
        $model = new Dynamic();
        $findArticle = $model->findArticle(['id'=>(int)$did,'status'=>self::EFFECTIVE_STATE]);
        if(empty($findArticle)){
            return [];
        }
        return findDataToArray($findArticle);
    }

    /**
     * 公用方法 | 获取活动信息
     *
     * return array
     */
    public function GetActivityInfoById(){
        $activity_id = $this->data['activity_id'];
        $model = new Activity();
        $getOneActivityInfo = $model->getOneActivityInfo(['id'=>(int)$activity_id,'status'=>self::EFFECTIVE_STATE]);
        if(empty($getOneActivityInfo)){
            return [];
        }
        return findDataToArray($getOneActivityInfo);
    }

    /**
     * 公用方法 | 获取所有标签信息
     *
     * return array
     */
    public function GetFlagList(){
        $arr = [];
        $model = new OtherFlag();
        $data = $model->getOtherFlagList(['flagState'=>'1']);
        if(empty($data)){
            return [];
        }
        $data = selectDataToArray($data);
        foreach ($data  as $k => $v){
            $arr[explode(':',$v['flagName'])[3]] = explode(':',$v['flagValue']);
        }
        return $arr;
    }

    /**
     * 公用方法 | 发送邮件
     */
    public function SendEmail($title,$content){
        $mail = new PHPMailer();
        try {
            // 服务器设置
            $mail->SMTPDebug = 0; // 开启Debug
            $mail->isSMTP(); // 使用SMTP
            $mail->Host = config('email.host'); // 服务器地址
            $mail->SMTPAuth = true; // 开启SMTP验证
            $mail->Username = config('email.uname'); // SMTP 用户名（你要使用的邮件发送账号）
            $mail->Password = config('email.pwd'); // SMTP 密码
            $mail->SMTPSecure = 'ssl'; // 开启TLS 可选
            $mail->Port = 465; // 端口
            // 设置发送的邮件的编码
            $mail->CharSet = 'UTF-8';

            // 收件人
            $mail->setFrom('reminder@yipinchongke.com'); // 来自
            $mail->addAddress('support@yipinchongke.com'); // 可以只传邮箱地址

            // 内容
            $mail->isHTML(true); // 设置邮件格式为HTML
            $mail->Subject = (string)$title; //邮件主题
            $mail->Body = (string)$content; //邮件内容
            $res = $mail->send();
            return $res;
        } catch (Exception $e) {
            return '邮件发送失败,Mailer Error:'.$mail->ErrorInfo;
        }
    }

    /**
     * 公用方法 | 获取地区名称
     *
     */
    public function GetCityByCode($code,$level){
        $model = new Region();
        $info  = $model->findRegion(['status'=>1,'rg_id'=>$code,'delete_flag'=>0,'rg_level'=>$level]);
        if(empty($info)){
            return [];
        }
        $res = $info->toArray();
        var_dump($res);die;
//        return
    }
}
