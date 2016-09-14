<?php
/**
 * Created by PhpStorm.
 * User: wangl
 * Date: 2016/8/11
 * Time: 14:39
 */
namespace Wchat\Controller;

use Components\Base\WchatController;
use Components\Helpers\Sendsms;
use Components\Helpers\SendsmsHelper;
use Model\MemberModel;
use Think\Verify;
use Think\Model;

class HomeController extends WchatController
{

    private $SMS;
    private $postInfo;
    private $member;

    public function __construct()
    {
        parent::__construct();

        $this->SMS=new SendsmsHelper();
        $this->member=new MemberModel();
    }

    public function login()
    {
        layout('Layouts/login');
        if (IS_POST) {
            $this->postInfo=I("post.");
            if ($this->member->validate($this->member->_login_validate)->create()) {
                if ($data=$this->member->checkLogin($this->postInfo)) {
                    $this->setMemberlLogined($data);
                    return $this->redirect('Wchat/Member/index');
                } else {
                    $err = array('username'=>'用户名和密码是不匹配滴！');
                    $this->assign('error', $err);
                }
            } else {
                $this->assign('error',$this->member->getError());
            }
        }
        $this->display();
    }

    public function ajaxLogin()
    {
        if (IS_POST) {
            $this->postInfo=I("post.");
            if ($this->member->validate($this->member->_login_ajax_validate)->create()) {
                if ($data=$this->member->checkLogin($this->postInfo)) {
                    echo 200;
                } else {
                    echo json_encode(array(
                        "password"=>"账号和密码是不匹配的"
                    ));
                }
            } else {
                $error=$this->member->getError();
                echo json_encode($error);
            }
        } else {
            echo 505;
        }
    }

    public function regedit()
    {
        layout('Layouts/regedit');
        //提交表单
        if (IS_POST) {
            $this->postInfo=I("post.");
            //dd($this->postInfo);
            //dd($_SESSION['mobile_code']);
            if ($res=$this->member->validate($this->member->_form_validate)->create()) {
                if ($rest=$this->member->regedit($this->postInfo)) {
                    $_SESSION['mobile_code']="";
                    $this->setMemberlLogined($rest);
                    return $this->redirect('Wchat/Member/index');
                } else {
                    $this->error('注册失败','index',3);
                }
                exit();
            } else {
                $this->assign('error', $this->member->getError());
            }
            $this->display();
            exit();
        }
        //推荐码
        $referee=I('get.referee');
        if (!empty($referee)) {
            $this->assign('referee',$referee);
        }
        $this->display();
    }

    /**
     * ajax手机注册
     */
    public function ajaxRegedit()
    {
        //是否post提交
        if (IS_POST) {
            $this->postInfo=I("post.");
            if ($res=$this->member->validate($this->member->_form_validate)->create()) {
                echo 200;
            } else{
                $error=$this->member->getError();
                echo json_encode($error);
            }
        } else {
            //拒绝ajax请求不是post
            echo 505;
        }

    }

    /**
     * 先正则判断手机格式是否合法，然后去检查数据库里是否已经存在，如果都通过，则发送短信验证码。
     */
    public function sendIdentify()
    {
        $mobile=$_GET['mobile'];
        if (empty($mobile)) {
            echo 503;//手机号必须填写
            exit();
        }
        $code=$_GET['code'];
        $verfiy=new Verify();
        //验证验证码是否正确
        if ($verfiy->check($code)) {
            //验证手机格式是否合法
            if (preg_match('/^1[34578]\d{9}$/',$mobile)) {
                //验证此手机是否绑定过
                $rest=$this->member->where("mobile='%s'",$mobile)->getField();
                if ($rest==null) {
                    //手机验证码是否发送过
                    if (empty($_SESSION['mobile_code'])) {
                        $this->creatAndSendSMS($mobile);
                    } else {
                        $this->repeatSendCode($mobile);
                    }
                } else {
                    echo 502;//手机已经被绑定
                }
            } else {
                echo 501;//手机格式错误！
            }
        } else {
            echo 500;//验证码错误
        }


    }

    /**
     * 验证码显示
     */
    public function verfiyImg()
    {
        $config = array(
            'imageW' => 120,
            'imageH' => 40,
            'fontSize' => 15,
            'length' => 4,
            'fontttf' => '5.ttf'
        );

        $verfiy = new Verify($config);
        $verfiy->entry();
    }

    /**
     * 创建验证验证码并发送
     */
    private function creatAndSendSMS($mobile)
    {
        //没有
        //验证码
        $code=rand(1000,9999);
        $tempArry=array(
            'code'=>$code,
            'creat_time'=>time(),
            'send_time'=>0,
            'phone'=>$mobile
        );
        //创建session
        $_SESSION['mobile_code']=$tempArry;
        //短信内容
        $mobileContent="【碎片时间】您的验证码是:$code,有效时间10分钟。";
        //发送短信
        echo $info=$this->SMS->sendSMS($mobileContent,$mobile);
        if ($info==100) {
            $_SESSION['send_time']=time();
        }
    }

    /**
     * 重复发送验证码
     */
    private function repeatSendCode($mobile)
    {
        //有
        $rest=$_SESSION['mobile_code'];
        $rest['phone']=$mobile;
        if (time()>$rest['send_time']+120) {
            //刷新验证码
            $outTime=$rest['creat_time']+600;
            if (time()>$outTime) {
                $rest['code']=rand(1000,9999);
                $rest['creat_time']=time();
            }
            //短信内容
            $code=$rest['code'];
            $mobileContent="【碎片时间】您的验证码是:$code,有效时间10分钟。";
            //发送短信
            echo $info=$this->SMS->sendSMS($mobileContent,$mobile);
            //成功写入发送时间
            if ($info==100) {
                $rest['send_time']=time();
                //重写session
                $_SESSION['mobile_code']=$rest;
            }
        } else {
            echo 505;//短信已发送请120秒后再试
        }
    }
    

}

