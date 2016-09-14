<?php
/**
 * Created by PhpStorm.
 * User: wangl
 * Date: 2016/8/10
 * Time: 15:20
 */
namespace Components\Base;

use Model\MemberWithdrawalsModel;
use Model\TaskAppealModel;
use Model\TaskAppealPictureModel;
use Model\TaskHandleModel;
use Think\Model;

class WchatController extends BaseController
{
    //WechatAccount表的实例
    protected $tableWechatAccount;

    //member表的实例
    protected $tableMember;

    //task_handle表的实例
    protected $tableTaskHandle;

    //task表的实例
    protected $tableTask;

    //partner表的实例
    protected $tablePartner;

    //partner_deposit表的实例
    protected $tablePartnerDeposit;

    //task_submit_result表的实例
    protected $tableSubmitResult;

    //task_submit_picture表的实例
    protected $tableSubmitPicture;

    //member_withdrawals表的实例
    protected $tableMemberWithdrawals;

    //$tableTaskHandleModle实例
    protected $tableTaskHandleModle;

    //task_appeal表的实例
    protected $tableTaskAppeal;

    //task_appeal_picture表的实例
    protected $tableTaskAppealPicture;

    //member_withdrawals_method表的实例
    protected $tableMemberWithdrawalsMethod;
    
    public function __construct()
    {
        parent::__construct();
        $this->tableWechatAccount=M('wechat_account');
        $this->tableMember=M('member');
        $this->tableTaskHandle=M('task_handle');
        $this->tableTask=M('task');
        $this->tablePartner=M('partner');
        $this->tablePartnerDeposit=M('partner_deposit');
        $this->tableSubmitResult=M('task_submit_result');
        $this->tableSubmitPicture=M('task_submit_picture');
        $this->tableMemberWithdrawalsMethod=M('member_withdrawals_method');
        $this->tableMemberWithdrawals=new MemberWithdrawalsModel();
        $this->tableTaskHandleModle=new TaskHandleModel();
        $this->tableTaskAppeal=new TaskAppealModel();
        $this->tableTaskAppealPicture=new TaskAppealPictureModel();
    }

    /**
     * 判断是那个公众号的消息，并启用他的token信息
     */
    protected function selectToken()
    {

        $data=$this->tableWechatAccount->field('id,app_id,appsecret,token')->select();

        $signature=I('get.signature');
        $timestamp=I('get.timestamp');
        $nonce=I('get.nonce');
        
        foreach ($data as $value){
            $token=$value['token'];
            $array=array($timestamp,$nonce,$token);
            sort($array,SORT_STRING);
            $tmp=implode($array);
            $tmp=sha1($tmp);
            if ($tmp==$signature)
            {
                return $value;
            }
        }
        return false;
    }

    /**重新请求公众号的access_token，存入数据库并返回请求的access_token
     * @param $appid
     * @param $appsecret
     * @return false
     */
    protected function requestToken($appid,$appsecret)
    {
        $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
        $http=curl_init($url);
        curl_setopt($http,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($http, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($http, CURLOPT_SSL_VERIFYHOST, FALSE);
        $data=curl_exec($http);
        curl_close($http);
        $data=json_decode($data,true);
        if (array_key_exists('errcode')) {
            return false;
        } else {
            //$this->tableWechatAccount->access_token=$data['access_token'];
            //$this->tableWechatAccount->token_time=date("Y-m-d H:i:s",time()+$data['expires_in']);
            $updata['access_token']=$data['access_token'];
            $updata['token_time']=date("Y-m-d H:i:s",time()+$data['expires_in']);
            $updata['update_time']=date("Y-m-d H:i:s",time());
            $map['app_id']=$appid;
            $rest=$this->tableWechatAccount->where($map)->setField($updata);
            if ($rest){
                return $data['access_token'];
            } else {
                return false;
            }
        }

    }

    /**查询access_token,返回一个数组
     * @param $appid 公众号的appid
     * @return bool|mixed 
     */
    protected function queryToken($appid)
    {
        $map['app_id']=$appid;
        $data=$this->tableWechatAccount->where($map)->find();
        if (time()<strtotime($data['token_time'])) {
            return $data['access_token'];
        } else {
            return $this->requestToken($appid,$data['appsecret']);
        }
    }

    /**根据微信原始id找app_id
     * @param $wxid 微信号
     * @return mixed
     */
    private function queryAppid($wxid)
    {
        $map['wx_primeval_id']=$wxid;
        $data=$this->tableWechatAccount->where($map)->find();
        return $data['app_id'];
    }

    protected function assignWechat($userid)
    {
        //从数据库选择可用的公众号-----现在规则写的很简单以后可以详细写
        $weChatData=$this->tableWechatAccount->where('is_enable=1')->order('priority desc, create_time desc')->find();
        //查询token是否存在是否过期存在就重新请求
        if (empty($weChatData['access_token'])) {
            if (!$weChatData['access_token']=$this->requestToken($weChatData['app_id'],$weChatData['appsecret'])) {
                return false;
            }
        } else {
            $tokenTime=strtotime($weChatData['token_time']);
            if (time()>=$tokenTime) {
                if (!$weChatData['access_token']=$this->requestToken($weChatData['app_id'],$weChatData['appsecret'])) {
                    return false;
                }
            }
        }
        //创建临时带参数二维码
        $tokenId=$weChatData['access_token'];
        $json= '
            {
            "action_name": "QR_LIMIT_SCENE",
            "action_info": {
            "scene": {
            "scene_id": '.$userid.'
                }
                }
            }
        ';
        $url="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$tokenId";
        $jsonInfo=$this->https_post($url,$json);
        $jsonInfo=json_decode($jsonInfo,true);
        if (array_key_exists('ticket',$jsonInfo)) {
            //'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$jsonInfo['ticket'];
            return 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$jsonInfo['ticket'];
        } else {
            return false;
        }
    }


    /**检查登录
     * @return bool|mixed
     */
    protected function checkLogin()
    {
        $partner = session('member');

        if (is_null($partner)) {
            return false;
        }

        return $partner;
    }

    /**HTTP post 请求
     * @param $url
     * @param $data
     * @return mixed
     */
    private function https_post($url,$data)
    {
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)) {
            curl_setopt($ch,CURLOPT_POST,1);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        }
        $output=curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    /**为用户分配任务
     *$map过滤条件，不用这种形式服务器一直出错，估计跟php版本有关系吧。
     */
    protected function assignTask($wechatId)
    {
        //通过微信openid找到用户，如果没有找到表示为绑定。
        $map['wx_open_id']=$wechatId;
        $userId=$this->tableMember->where($map)->field('id')->find();
        if ($userId==null) {
            return "您还有没有绑定过微信！请去http://shuadan.cocsms.com/index.php/Wchat/Home/login.html绑定";
        }
        $map=null;
        //通过用户id查询当前是否有进行中的任务
        $map['member_id']=$userId['id'];
        $map['_string']='status=0 or status=3';
        $taking=$this->tableTaskHandle->where($map)->field('task_id')->find();
        $map=null;
        //如果有就提示用户当前正在进行的任务
        if ($taking!=null) {
            $map['id']=$taking['task_id'];
            $task=$this->tableTask->where($map)->field('title,task_config,type,price')->find();
            $map=null;
            switch ($task['type']) {
                case 1:
                    $taskContent=json_decode($task['task_config'],true);
                    $url=$taskContent['url'];
                    $remark=$taskContent['remark'];
                    return '当前正在进行任务:'.
                           $task['title']."\n单价:".
                           $task['price']."元\n链接:".
                           $url."\n".
                           $remark;
                    break;
                default:
                    return false;
                    break;
            }
        }
        //去任务表去找当前符合分配条件的前50个任务
        $map['status']=array('EQ',3);
        $map['left_quantity']=array('GT',0);
        $map['start_time']=array('ELT',date("Y-m-d H:i:s",time()));
        $map['end_time']=array('EGT',date("Y-m-d H:i:s",time()));
        $taskRest=$this->tableTask->where($map)->
        order('priority desc,create_time asc,price desc,doing_count asc,complete_count asc')->
        select();
        //循环对查出的数据进行判断是否适合被分配出去
        foreach ($taskRest as $key=>$value) {
            //根据速度算出任务下一个时间需要的秒数
            //秒数
            $rateTime=0;
            if ($value['rate']!=0) {
                $rateTime=60/$value['rate'];
            }
            //判断任务是否超过限制速度
            if ($value['last_time']!=null) {
                //合法时间
                $legalTime=strtotime($value['last_time'])+$rateTime;
                if (time()>$legalTime) {
                    //分配任务
                    if ($this->addTaskAssign($value,$userId['id'])) {
                        $taskContent=json_decode($value['task_config'],true);
                        $url=$taskContent['url'];
                        $remark=$taskContent['remark'];
                        return $value['title']."\n单价:".
                               $value['price']."元\n链接:".
                               $url."\n".
                               $remark;
                    }
                }
            } else {
                //分配任务
                if ($this->addTaskAssign($value,$userId['id'])) {
                    $taskContent=json_decode($value['task_config'],true);
                    $url=$taskContent['url'];
                    $remark=$taskContent['remark'];
                    return $value['title']."\n单价:".
                    $value['price']."元\n链接:".
                    $url."\n".
                    $remark;
                }
            }
        }
        return "暂时没有任务，请稍候再试。";
    }

    /**
     * @param $taskData
     * @param $memberId
     * @return bool
     */
    private function addTaskAssign($taskData,$memberId)
    {
        //检查发布这个任务的人的余额是否足够。
        $map['id']=$taskData['partner_id'];
        $balance=$this->tablePartner->where($map)->getField('balance');
        unset($map);
        //如果账号余额少于任务单价，则暂停这个任务
        if ($balance<$taskData['price']) {
            $map['id']=$taskData['id'];
            $this->tableTask->where($map)->setField('status','4');
            return false;
        }
        $this->tableTask->startTrans();
        //最后领取时间
        $data['last_time']=date("Y-m-d H:i:s",time());
        //已经领取的数量
        $data['taking_quantity']=$taskData['taking_quantity']+1;
        //进行中的数量
        $data['doing_count']=$taskData['doing_count']+1;
        //可接的数量
        $data['left_quantity']=$taskData['quantity']-$data['taking_quantity']+$taskData['abandon_count']+$taskData['audit_refuse_count'];
        //更新条件
        $map['id']=$taskData['id'];
        if ($this->tableTask->where($map)->setField($data)) {
            $handle['create_time']= date("Y-m-d H:i:s",time());
            $handle['update_time']= date("Y-m-d H:i:s",time());
            $handle['accept_time']= date("Y-m-d H:i:s",time());
            $handle['task_id']=$taskData['id'];
            $handle['member_id']=$memberId;
            if ($this->tableTaskHandle->data($handle)->add()) {
                //$this->tableTask->commit();
                //扣除用户的资金
                $partnerData['balance']=$balance-$taskData['price'];
                $partnerData['update_time']=date("Y-m-d H:i:s",time());
                $map['id']=$taskData['partner_id'];
                if ($this->tablePartner->where($map)->setField($partnerData)) {
                    unset($map);
                    //往资金表里写日志
                    $depositData['create_time']=date("Y-m-d H:i:s",time());
                    $depositData['update_time']=date("Y-m-d H:i:s",time());
                    $depositData['partner_id']=$taskData['partner_id'];
                    $depositData['type']=3;
                    $depositData['debit']=$taskData['price'];
                    $depositData['balance']=$partnerData['balance'];
                    $depositData['relation_id']=$taskData['id'];
                    $depositData['operator']="系统";
                    if ($this->tablePartnerDeposit->data($depositData)->add()) {
                        $this->tableTask->commit();
                        return true;
                    } else {
                        $this->tableTask->rollback();
                        return false;
                    }
                } else {
                    $this->tableTask->rollback();
                    return false;
                }
            } else {
                $this->tableTask->rollback();
                return false;
            }
        } else {
            $this->tableTask->rollback();
            return false;
        }
    }

    protected function acceptTask($wechatId)
    {
        //通过微信openid找到用户，如果没有找到表示为绑定。
        $map['wx_open_id']=$wechatId;
        $userId=$this->tableMember->where($map)->field('id')->find();
        if ($userId==null) {
            return "您还有没有绑定过微信！请去http://shuadan.cocsms.com/index.php/Wchat/Home/login.html绑定";
        }
        $map=null;
        //通过用户id查询当前是否有进行中的任务
        $map['member_id']=$userId['id'];
        $map['_string']='status=0 or status=3';
        $taking=$this->tableTaskHandle->where($map)->field('id,task_id,status,accept_time')->find();
        $map=null;
        //如果没有就提示用户当前没有进行的任务，或者任务已经过期
        if ($taking==null) {
            return "您当前还没有进行中的任务，或者任务已经超时";
        }
        if ($taking['status']==0) {
            //检查任务是否超时,这个超时时间可以写成常量，或者写入数据库，方便配置，以后再说。
            if (strtotime($taking['accept_time'])+600<time()) {
                if ($this->abandonTask($taking['id'])) {
                    return "您的任务已经超时！被放弃！";
                } else {
                    return "出错了！";
                }
            }
            //将任务标记为提交中
            $map['id']=$taking['id'];
            $this->tableTaskHandle->where($map)->setField('status',3);
            //结果表准备插入的数据
            $resultData=array(
                'create_time'=>date("Y-m-d H:i:s",time()),
                'update_time'=>date("Y-m-d H:i:s",time()),
                'task_id'=>$taking['task_id'],
                'member_id'=>$userId['id'],
                'status'=>4,
                'operator'=>'系统'
            );
            $resultId=$this->tableSubmitResult->data($resultData)->add();
            if ($resultId) {
                $handleData['submit_result_id']=$resultId;
                if ($this->tableTaskHandle->where($map)->setField($handleData)) {
                    if ($taskInfo=$this->getTaskInfo($taking['task_id'])) {
                        return '您当前正在进行任务【'.$taskInfo['title']."】\n\n".
                        "如果未完成任务或无法完成请回复【放弃】\n\n 如果已经完成该任务，请提交任务截图:\n\n".
                         "**提交的截图务必使完成任务的证据截图，否则您的账户将会被冻结";
                    }
                }
            }
        }
        if ($taking['status']==3) {
            if ($taskInfo=$this->getTaskInfo($taking['task_id'])) {
                return '您当前正在进行任务【'.$taskInfo['title']."】\n\n".
                "如果未完成任务或无法完成请回复【放弃】\n\n 如果已经完成该任务，请提交任务截图:\n\n".
                "**提交的截图务必使完成任务的证据截图，否则您的账户将会被冻结";
            }
        }
    }

    /**
     * @param $id 任务处理进行表的id
     * @return bool
     */
    private function abandonTask($id)
    {
        $map['id']=$id;
        $this->tableTaskHandle->startTrans();
        $rest=$this->tableTaskHandle->where($map)->field('submit_result_id,task_id')->find();
        if ($rest['submit_result_id']!=null) {
            $map2['id']=$rest['submit_result_id'];
            //状态5表示任务放弃
            if (!$this->tableSubmitResult->where($map2)->setField('status',5)) {
                $this->tableTaskHandle->rollback();
                return false;
            }
         }
        //状态2表示任务放弃
        if (! $this->tableTaskHandle->where($map)->setField('status',2)) {
           $this->tableTaskHandle->rollback();
           return false;
        }
        unset($map);
        $map['id']=$rest['task_id'];
        $taskID=$rest['task_id'];
        $rest=$this->tableTask->where($map)->field('quantity,taking_quantity,abandon_count,audit_refuse_count,doing_count,partner_id,price')->find();
        $taskData=array(
            'doing_count'=>$rest['doing_count']-1,
            'abandon_count'=>$rest['abandon_count']+1,
        );
        $leftQuantity=$rest['quantity']-$rest['taking_quantity']+$taskData['abandon_count']+$rest['audit_refuse_count'];
        $taskData['left_quantity']=$leftQuantity;
        if ($this->tableTask->where($map)->setField($taskData)) {
            //给发布任务的人返回余额。
            unset($map);
            $map['id']=$rest['partner_id'];
            if ($this->tablePartner->where($map)->setInc('balance',$rest['price'])) {
                $balance=$this->tablePartner->where($map)->getField('balance');
                $depositData=array(
                    'create_time'=>date("Y-m-d H:i:s",time()),
                    'update_time'=>date("Y-m-d H:i:s",time()),
                    'partner_id'=>$rest['partner_id'],
                    'type'=>5,
                    'credit'=>$rest['price'],
                    'debit'=>0,
                    'balance'=>$balance+$rest['price'],
                    'relation_id'=>$taskID,
                    'operator'=>'系统'
                );
                if ($this->tablePartnerDeposit->data($depositData)->add()) {
                    $this->tableTaskHandle->commit();
                    return true;
                } else {
                    $this->tableTaskHandle->rollback();
                    return false;
                }
            } else {
                $this->tableTaskHandle->rollback();
                return false;
            }
        } else {
            $this->tableTaskHandle->rollback();
            return false;
        }
    }

    /**获取任务信息
     * @param $taskId 任务的id
     * @return array
     */
    private function getTaskInfo($taskId)
    {
        $map[id]=$taskId;
        if ($res=$this->tableTask->where($map)->find()) {
            return $res;
        } else {
            return false;
        }
    }


    protected function acceptPic($data)
    {
        $userId=$this->findIdByWx($data['FromUserName']);
        if (!$userId) {
            return "您当前还为绑定账号请去http://shuadan.cocsms.com/index.php/Wchat/Home/login.html绑定";
        }
        //查询用户是否有正在提交中的任务
        $map['status']=3;
        $map['member_id']=$userId['id'];
        $rest=$this->tableTaskHandle->where($map)->field('submit_result_id')->find();
        unset($map);
        if (!$rest) {
            return "你给我扔了一张图片，本宝宝稳稳的接住了，但是本宝宝并不知道这个图片要干嘛";
        }
        //如果就把这张发过来的图片保存下来，然后存到数据库
        $appId=$this->queryAppid($data['ToUserName']);
        $source=$this->downSourceByMediaId($data['MediaId'],$appId);
        $name=time().$data['FromUserName'].$userId.'.jpg';
        if (!$this->saveWeiFile($name,$source['body'])) {
            return "出错了再发一遍";
        }
        $pictureData['create_time']=date("Y-m-d H:i:s",time());
        $pictureData['update_time']=date("Y-m-d H:i:s",time());
        $pictureData['task_submit_id']=$rest['submit_result_id'];
        $pictureData['image_url']=$name;
        if ($this->tableSubmitPicture->data($pictureData)->add()) {
            return "如果有多张截图请继续发送，如果已经发送完毕，请回复【完成】";
        } else {
            return "出错了再发一遍";
        }
    }

    /**通过微信openid找id号
     * @param $wxOpenId wx_open_id
     * @return bool|array
     */
    private function findIdByWx($wxOpenId)
    {
        $map['wx_open_id']=$wxOpenId;
        $userId=$this->tableMember->where($map)->field('id')->find();
        if ($userId==null) {
            return false;
        } else {
            return $userId;
        }
    }

    /**下载微信的资源
     * @param $MediaId
     * @return array
     */
    private function downSourceByMediaId($MediaId,$appid)
    {
            $accessToken=$this->queryToken($appid);
            $url="https://api.weixin.qq.com/cgi-bin/media/get?access_token=$accessToken&media_id=$MediaId";
            $ch=curl_init($url);
            curl_setopt($ch,CURLOPT_HEADER,0);
            curl_setopt($ch,CURLOPT_NOBODY,0);
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
            curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            $package=curl_exec($ch);
            $httpinfo=curl_getinfo($ch);
            curl_close($ch);
            $imageAll=array_merge(array('header'=>$httpinfo),array('body'=>$package));
            return $imageAll;
    }

    /**
     * @param $fileName
     * @param $filecontent
     * @return bool
     */
    private function saveWeiFile($fileName,$filecontent)
    {

        $local_file=fopen("./Public/storage/taskImge/$fileName",'wr');
        if(false!==$local_file) {
            if(false!==fwrite($local_file,$filecontent)) {
                fclose($local_file);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    /**
     * @param $data 微信发过的值
     * @return string
     */
    protected function finshTaskSubmit($data)
    {
        $userId=$this->findIdByWx($data['FromUserName']);
        if (!$userId) {
            return "您当前还为绑定账号请去http://shuadan.cocsms.com/index.php/Wchat/Home/login.html绑定";
        }
        $map['member_id']=$userId['id'];
        $map['status']=3;

        //检查是否有在提交中的任务
        $rest=$this->tableTaskHandle->where($map)->field('id,submit_result_id,task_id')->find();
        if (!$rest) {
            return "你当前没有正在提交中的任务,要提交请先点击【我要接】,无法完成请回复【放弃】";
        }

        //检查是否提交过任务截图
        $map2['task_submit_id']=$rest['submit_result_id'];
        $rest2=$this->tableSubmitPicture->where($map2)->select();
        if ($rest2==NULL) {
            return "您好没有上传过图片！无法完成请回复【放弃】";
        }

        //开启事务
        $this->tableTaskHandle->startTrans();
        $handleData['status']=1;
        $handleData['update_time']=date("Y-m-d H:i:s",time());
        //修改task_handle的任务状态
        if ($this->tableTaskHandle->where($map)->setField($handleData)) {
            unset($map);
            $map['id']=$rest['submit_result_id'];
            //修改task_submit_result的状态
            $resultData=array(
                'update_time'=>date("Y-m-d H:i:s",time()),
                'status'=>1,
                'operator'=>'系统'
            );
            if ($this->tableSubmitResult->where($map)->setField($resultData)) {
                unset($map);
                $map['id']=$rest['task_id'];
                $task=$this->tableTask->where($map)->field('doing_count,audit_wait_count')->find();
                $taskData=array(
                    'update_time'=>date("Y-m-d H:i:s",time()),
                    'doing_count'=>$task['doing_count']-1,
                    'audit_wait_count'=>$task['audit_wait_count']+1,
                );
                //return $rest['task_id'];
                if ($this->tableTask->where($map)->setField($taskData)) {
                    $this->tableTaskHandle->commit();
                    return "任务提交已完成，正审核中...";
                } else {
                    $this->tableTaskHandle->rollback();
                    return "处理失败！";
                }
            } else {
                $this->tableTaskHandle->rollback();
                return "处理失败！";
            }
        } else {
            $this->tableTaskHandle->rollback();
            return "处理失败！";
        }

    }

    /**主动放弃正在进行中的任务
     * 
     */
    protected function proactiveAbandonTask($info)
    {
        $userId=$this->findIdByWx($info['FromUserName']);
        if (!$userId) {
            return "您当前还为绑定账号请去http://shuadan.cocsms.com/index.php/Wchat/Home/login.html绑定";
        }
        $map['member_id']=$userId['id'];
        $map['_string']='status=0 or status=3';
        //检查是否有在提交中的任务
        $rest=$this->tableTaskHandle->where($map)->field('id')->find();
        if (!$rest) {
            return "你当前没有正在提交中的任务，让我怎么给你取消呀！囧囧囧";
        }
        if ($this->abandonTask($rest['id'])) {
            return "任务已放弃！";
        } else {
            return "哎呀，这个任务好像很喜欢你放弃失败了呢，在试一下吧！";
        }
    }

}