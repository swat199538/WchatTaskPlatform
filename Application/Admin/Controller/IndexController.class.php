<?php
namespace Admin\Controller;

use Components\Base\AdminController;
use Model\ManagerModel;
use Think\Verify;

class IndexController extends AdminController
{
    protected $manager;

    public function __construct()
    {
        parent::__construct();
        $this->manager = new ManagerModel();
    }

    /**
     * 后台首页
     */
    public function index()
    {
        $this->display();
    }

    /**
     * 用户登入
     * @return mixed
     */
    public function login()
    {
        layout('Layouts/base');

        if (IS_POST) {
            $this->assignData = I('post.');
            // 验证通过
            if ($res = $this->manager->validate($this->manager->_login_validate)->create()) {
                if ($data = $this->manager->loginCheck($res)) {
                    // 写入登入信息，并调转到其他首页
                    $this->setLoggedInfo($data);

                    return $this->success('登入成功！', U('Index/index'));
                } else {
                    $err = array('username'=>'用户名和密码不匹配！');
                    $this->assign('error', $err);
                }

            } else {
                $this->assign('error', $this->manager->getError());
            }

        }

        $this->assign('data', $this->assignData);
        $this->display();
    }

    /**
     * 用户登出
     */
    public function logout()
    {
        session(null);

        return $this->redirect('login');
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
     * 用户密码重置
     */
    public function resetPassword()
    {
        if (IS_POST) {
            if ($res = $this->manager->validate($this->manager->_reset_password_validate)->create()) {
                $res['new_password'] = I('post.new_password');
                $bool = $this->manager->resetPassword($res);

                if ($bool) {
                    return $this->success('密码成功！', U('Index/index'));
                }

                $err = array('password'=>'原始密码输入不正确！');
                $this->assign('error', $err);
            } else {
                $this->assign('error', $this->manager->getError());
            }
        }

        $this->display();
    }
}