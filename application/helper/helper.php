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
}
