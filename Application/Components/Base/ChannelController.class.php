<?php
/**
 * Created by PhpStorm.
 * User: wangl
 * Date: 2016/7/21
 * Time: 17:05
 */
namespace Components\Base;

use Think\Model;

class ChannelController extends BaseController
{


    //用户操作提示信息
    protected $msgInfo=array(
        "edit"=>"修改成功",
        "creat"=>"创建成功",
        "pause"=>"暂停成功",
        "add"=>"新增人数成功",
        "urgent"=>"加急成功"
    );

    public function __construct()
    {
        parent::__construct();

        $noAuthArr = explode(',', C('NOT_AUTH'));
        //dd($noAuthArr);
        $routeName = CONTROLLER_NAME . '/' . ACTION_NAME;

        if(!in_array($routeName,$noAuthArr)){
            $res=$this->checkLogin();
            if (!$res) {
                return $this->redirect('Channel/Index/login');
            }

        }


        
    }

    public function getCannelInfo($cannelId)
    {
        $info=M('partner');

        $rest=$info->where('id=%d',$cannelId)->getfield('username,balance,task_publish');
        
        return $rest;

    }
    
    //检查是否登录
    public function checkLogin()
    {
        $partner = session('partner');

        if (is_null($partner)) {
            return false;
        }

        return $partner;

    }
    
}