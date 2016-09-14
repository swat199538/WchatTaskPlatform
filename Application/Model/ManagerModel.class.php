<?php

/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2016/7/8
 * Time: 17:05
 */
namespace Model;

use Components\Base\BaseModel;
use Think\Verify;

class ManagerModel extends BaseModel
{
    protected $insertFields = array('username', 'password', 'password_confirmation', 'email', 'remark', 'status');
    protected $updateFields = array('id', 'email', 'remark', 'status', 'password', 'new_password', 'new_password_confirmation', 'last_logged_at', 'last_logged_ip');

    protected $_auto = array(
        array('created_at', 'time', 1, 'function'),
        array('password','md5', 3, 'function'),
        array('updated_at','time', 2, 'function')
    );

    /**
     * @var array 用户添加和修改的验证
     */
    protected $_validate = array(
        array('username', 'require', '用户名必须填写'),
        array('username', '', '用户名已存在', 0, 'unique', 1),
        array('username', '/^[A-Za-z0-9]+$/', '长度不符合规范或含有非法字符', 0, 'regex'),
        array('username', '5,25', '用户名长度应在5~25位之间', 0, 'length'),

        array('password', 'require', '密码必填'),
        array('password', '/^[A-Za-z0-9]+$/', '密码只能是数字和字母的组合', 0, 'regex', 1),
        array('password', '6,60', '密码不得少于6位或大于60位', 2, 'length', 1),

        array('password_confirmation', 'require', '确认密码必填'),
        array('password_confirmation', 'password', '两次输入不一致', 2, 'confirm'),

        array('email', 'require', '邮箱必须填写'),
        array('email', 'email', '邮箱格式不正确'),
        array('email', '6,50', '邮箱长度不正确', 3, 'length'),

        array('remark', '1,255', '备注信息输入长度过长', 2, 'length'),

        array('status', 'require', '用户状态必须存在'),
        array('status', array(0, 1), '登入状态不合法', 2, 'in')

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
     * @var array 用户修改密码验证
     */
    public $_reset_password_validate = array(
        array('password', 'require', '原始密码必填！'),
        array('new_password', 'require', '新密码必填！'),
        array('new_password', '/^[A-Za-z0-9]+$/', '密码只能是数字和字母的组合', 0, 'regex'),
        array('new_password', '6,60', '密码不得少于6位或大于60位', 2, 'length'),
        array('new_password_confirmation', 'new_password', '两次输入不一致', 0, 'confirm'),
    );

    protected function checkCode($code)
    {
        $code = I('post.captcha');
        $captcha = new Verify();
        
        return $captcha->check($code);
    }

    public function getAllByPage($startNo, $pageSize)
    {
        return $this->field('id, username, email, remark, last_logged_at, last_logged_ip, status')->limit("$startNo, $pageSize")->order('created_at desc')->select();
    }


    public function remove($id)
    {
        $res = $this->find($id);

        if (empty($res) || !empty($res['is_super'])) {
            return false;
        }
        
        return $this->delete($id);
    }

    public function loginCheck($data)
    {
        $username = $data['username'];
        $userData = $this->field('id, username, password, is_super')->where("username='%s' and status=1", array($username))->find();

        if (empty($userData) || empty($data)) {
            return false;
        }

        if (0 !== strcmp($data['password'], $userData['password'])) {
            return false;
        }

        return $userData;
    }

    public function resetPassword($data)
    {
        $old = $data['password'];
        //$data['new_password'] = md5($data['new_password']);
        $userPwd = $this->field('password')->where("username='%s'", array(session('user.username')))->find();
        $userPwd = $userPwd['password'];

        if (0 !== strcmp($userPwd, $old)) {
            return false;
        }

        $data['password'] = md5($data['new_password']);

        return $this->save($data);
    }

}