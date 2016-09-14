<?php
namespace Wchat\Controller;

use Com\Wechat;
use Components\Base\WchatController;
use Org\Util\Date;
use Think\Model;

class IndexController extends WchatController
{

    private $chatInfo;
    private $wechat;


    public function __construct()
    {
        parent::__construct();
        $this->chatInfo=$this->selectToken();
        if ($this->chatInfo!=false){
            $this->wechat=new Wechat($this->chatInfo['token']);
        } else {
            exit();
        }
        $this->tableMember=M('member');
    }


    public function index()
    {

        $data=$this->wechat->request();

        if ($data['MsgType']==Wechat::MSG_TYPE_TEXT) {
            $this->doTextMsg($data['Content'],$data);
        }

        if ($data['MsgType']==Wechat::MSG_TYPE_EVENT) {
            $this->doEventMsg($data);
        }
        
        if ($data['MsgType']==wechat::MSG_TYPE_IMAGE) {
            $this->doImgMsg($data);
        }

    }
    
    //处理用户消息
    private function doTextMsg($data,$info)
    {
        switch ($data){
            case "你好":
                $content="大家好才是真的好";
                $this->wechat->response($content,Wechat::MSG_TYPE_TEXT);
                break;
            case "放弃":
                $content=$this->proactiveAbandonTask($info);
                $this->wechat->response($content,Wechat::MSG_TYPE_TEXT);
                break;
            case "完成":
                $content=$this->finshTaskSubmit($info);
                $this->wechat->response($content,Wechat::MSG_TYPE_TEXT);
            break;
            default:
                $content="囧囧囧";
                $this->wechat->response($content,Wechat::MSG_TYPE_TEXT);
                break;
        }
    }

   //处理事件消息
    private function doEventMsg($data)
    {
        //订阅事件
        if ($data['Event']==Wechat::MSG_EVENT_SUBSCRIBE) {
            if ($this->bindWechat($data['EventKey'],$data['FromUserName'])) {
                $content="绑定成功！";
            } else{
                $content="绑定失败！";
            }
            $this->wechat->response($content,Wechat::MSG_TYPE_TEXT);
        }

        //点击事件
        if($data['Event']==Wechat::MSG_EVENT_CLICK) {
            switch ($data['EventKey']){
                //我要接事件
                case "ACCEPT_TASK":
                    $content=$this->assignTask($data['FromUserName']);
                    $this->wechat->response($content,Wechat::MSG_TYPE_TEXT);
                    break;
                //我要收事件
                case "POST_TASK":
                    $content=$this->acceptTask($data['FromUserName']);
                    $this->wechat->response($content,Wechat::MSG_TYPE_TEXT);
                    break;
            }
        }

        //已经关注的扫描带参数的二维码事件
        if ($data['Event']==Wechat::MSG_EVENT_SCAN){
            if ($this->bindWechat($data['EventKey'],$data['FromUserName'],1)) {
                $content="绑定成功！";
            } else{
                $content="绑定失败！";
            }
            $this->wechat->response($content,Wechat::MSG_TYPE_TEXT);
        }
    }

    //处理图片消息
    private function doImgMsg($data)
    {
        $content=$this->acceptPic($data);
        $this->wechat->response($content,Wechat::MSG_TYPE_TEXT);
    }

    //绑定微信
    private function bindWechat($id,$from,$type=0)
    {
        if ($type==0) {
            $user=explode('qrscene_',$id);
            $id=$user[1];
        }
        $data['wx_open_id']=$from;
        $data['wx_is_bind']=1;
        return $this->tableMember->where("id=%d ",$id)->save($data);
    }

    
}