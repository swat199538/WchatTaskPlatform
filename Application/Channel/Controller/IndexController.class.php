<?php
namespace Channel\Controller;

use Components\Base\ChannelController;
use Model\PartnerModel;
use Think\Verify;

class IndexController extends ChannelController
{
    protected $manager;

    public function __construct()
    {
        parent::__construct();
        $this->manager = new PartnerModel();
    }

    /**
     * 工作台显示
     */
    public function index()
    {
        
        $id=session('partner');
        $info=$this->getCannelInfo($id['id']);
        //dd($info);
        $this->assign('info', $info);
        $this->display();
    }


    /**
     * 渠道商登录
     */
    public function login()
    {
        layout('Layouts/base');
        
        if(IS_POST){
            //获取所有表单信息
            $this->assignData=I('post.');

            //验证表单
            if ($res = $this->manager->validate($this->manager->_login_validate)->create()) {
                
                if ($data = $this->manager->loginCheck($res)) {
                    // 写入登入信息，并调转到其他首页
                    $this->setChannelLogined($data);
                    return $this->success('登入成功！', U('Index/index'));
                } else {
                    $err = array('username'=>'用户名和密码是不匹配滴！');
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
     * 用户登出
     */
    public function logout()
    {
        session(null);

        return $this->redirect('login');
    }


}