<?php
namespace Components\Helpers;
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2016/7/6
 * Time: 12:37
 */
class SendsmsHelper
{
    private $smsapi="http://api.smsbao.com/";//api地址
    private $user='king19800105';
    private $password;
    /*private $sattusStr=array(
        "0" => "短信发送成功",
        "-1" => "参数不全",
        "-2" => "服务器空间不支持,请确认支持curl或者fsocket，联系您的空间商解决或者更换空间！",
        "30" => "密码错误",
        "40" => "账号不存在",
        "41" => "余额不足",
        "42" => "帐户已过期",
        "43" => "IP地址限制",
        "50" => "内容含有敏感词"
    );*/
    public function __construct()
    {
        $this->password=md5('19800105q');
    }

    public function sendSMS($content,$phone)
    {
        
        $sendurl = $this->smsapi."sms?u=".$this->user."&p=".$this->password."&m=".$phone."&c=".urlencode($content);
        $result =file_get_contents($sendurl) ;
        if ($result==0) {
            return 100;
        } else {
            return 504;
        }
    }



}