<?php
/**
 * Created by PhpStorm.
 * User: wangl
 * Date: 2016/7/28
 * Time: 14:37
 */
namespace Model;

use Components\Base\BaseModel;
use Think\Verify;

class MemberModel extends BaseModel
{
    //protected $insertFields = array('title', 'price', 'start_time', 'end_time', 'quantity', 'vote_url', 'vote_remark', 'rate');
    //protected $updateFields = array('title', 'price', 'start_time', 'end_time', 'rate', );

    //是否开启过滤模式0否，1是
    public $filterType=0;

    //过滤条件
    public $filterArr=array();

    //验证注册表单合法合法
    public $_form_validate=array(
        array('phone', 'require', '手机号码必须填写'),
        array('phone', '/^1[34578]\d{9}$/', '手机格式错', 0, 'regex', 1),
        array('password', 'require', '密码必须填写'),
        array('password2', 'require', '重复密码必须填写'),
        array('password2', 'checkRatePassWord', '必须与密码相同', 0, 'function'),
        array('phoneCode', 'require', '手机验证码必须填写'),
        array('phoneCode', 'checkPhoneCode', '验证码错误', 0, 'function'),
   );

    /**
     * @var array 用户登入验证
     */
    public $_login_validate = array(
        array('username', 'require', '用户名必填！'),
        array('password', 'require', '密码必填！'),
        array('captcha', 'require', '验证码必填！'),
        array('captcha', 'checkCode', '验证码填写不正确！',1, 'callback'),
    );

    /**
     * @var array 用户ajax登录验证
     */
    public $_login_ajax_validate = array(
        array('username', 'require', '用户名必填！'),
        array('password', 'require', '密码必填！'),
        array('captcha', 'require', '验证码必填！'),
        array('captcha', 'ajaxCheckCode', '验证码填写不正确！',1, 'callback'),
    );

    /**
     * @param $data
     * @return bool|mixed
     */
    public function regedit($data)
    {
        $info['mobile']=$data['phone'];
        $info['password']=md5($data['password']);
        $info['register_ip']=get_client_ip();
        $info['create_time']=date("Y-m-d H:i:s",time());;
        $info['update_time']=date("Y-m-d H:i:s",time());;
        $info['login_ip']=$info['register_ip'];
        $info['login_date']=date("Y-m-d H:i:s",time());
        $info['mobile_is_bind']=1;
        $info['register_channel']="手机号快速注册";
        if (!empty($data['referee'])) {
            //有邀请码的
            return $this->haveRefereeRegedit($info,$data);

        }
        //没有邀请码的
        $rest=$this->data($info)->add();
        return $rest;
    }

    /**有邀请码的注册
     * @param $info
     * @param $data
     * @return bool
     */
    private function haveRefereeRegedit($info,$data)
    {
        $rest=$this->where(" invite_code=%d ",$data['referee'])->getField('id');
        if ($rest) {
            //邀请码查到对应用户
            $this->startTrans();
            $info['parent_id']=$rest;
            //添加新用户
            if ($rest2=$this->data($info)->add()) {
                //邀请者邀请数量+1
                if ($this->where(" invite_code=%d ",$data['referee'])->setInc('invite_member_count',1)) {
                    $this->commit();
                    return $rest2;
                } else {
                    $this->rollback();
                    return false;
                }
            } else {
                $this->rollback();
                return false;
            }
        } else {
            //邀请码查不到对应的用户
            $rest=$this->data($info)->add();
            return $rest;
        }
    }
    /**
     * @param $data
     * @return bool|mixed
     */
    public function checkLogin($data)
    {
        $userphone = $data['username'];
        $userData = $this->field('id, password')->where("mobile='%s'", array($userphone))->find();
        if (empty($userData) || empty($data)) {
            return false;
        }
        if (0 !== strcmp(md5($data['password']), $userData['password'])) {
            return false;
       }
        return $userData['id'];
    }


    protected function checkCode($code)
    {
        $code = I('post.captcha');
        $captcha = new Verify();

        return $captcha->check($code);
    }

    protected function ajaxCheckCode($code)
    {
        $code = I('post.captcha');
        $captcha = new Verify();
        return $captcha->ajaxCheck($code);
    }

}