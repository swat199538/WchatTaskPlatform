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

class MemberController extends WchatController
{
    
    
    public function __construct()
    {
        parent::__construct();
        $noAuthArr = explode(',', C('NOT_AUTH'));
        $routeName = CONTROLLER_NAME . '/' . ACTION_NAME;
        if(!in_array($routeName,$noAuthArr)){
            $res=$this->checkLogin();
            if (!$res) {
                $this->redirect('Wchat/Home/login');
            }
        }
        $this->assign('data',$_SESSION['member']);
        $this->tableMemberWithdrawals->memberId=$_SESSION['member'];
        $this->tableTaskHandleModle->memberId=$_SESSION['member'];
    }

    /**
     * 个人中心
     */
    public function index()
    {
        $userInfo=$this->tableMember->where('id=%d ',$_SESSION['member'])->field('id,nickname,head_image_url,finsh_task_count,invite_member_count,balance')->find();
        $this->assign('userInfo',$userInfo);
        $this->assign('data',$_SESSION['member']);
        $this->display();
    }

    /**
     * 分配微信公众号
     */
    public function acceptTask()
    {
        $urserid=I('get.id');
        //echo $urserid;
        if ($urserid!=$_SESSION['member']) {
            $this->error('权限错误',U('Member/index'),3);
            exit();
        }
        if (!$url=$this->assignWechat($urserid)) {
            $this->error('分配出错',U('Member/index'),3);
            exit();
        }
        $this->assign("url",$url);
        $this->display();
    }

    /**
     * 编辑提现方式
     */
    public function editWithdrawals()
    {
        $this->display();
    }

    /**
     * ajax请求绑定状态
     */
    public function ajaxLoadPayStatus()
    {
        $type=I("post.");
        //$reust=$this->tableMemberWithdrawalsMethod->where()
    }

    /**
     * 查看任务记录
     */
    public function lookTaskLog()
    {
        $result=$this->tableTaskHandleModle->queryTaskHandData(0);
        //dd($result);
        if ($result){
            //dd($result);
            $this->assign('withdrawaData',$result);
        } else {
            $this->assign('withdrawaData',null);
        }

        $this->display();
    }

    /**
     * 查看邀请记录
     */
    public function lookInviteLog()
    {

    }

    /**
     * 查看提现记录
     */
    public function lookWithdrawalsLog()
    {
        $result=$this->tableMemberWithdrawals->queryLimitWithdrawalsData(0);
        if ($result){
            //dd($result);
            $this->assign('withdrawaData',$result);
        } else {
            $this->assign('withdrawaData',null);
        }
        
        $this->display();
    }

    /**
     * ajax拉取withdrawals数据
     */
    public function ajaxLoadWithdrawalsData()
    {
        $index=I('post.index');
        $result=$this->tableMemberWithdrawals->queryLimitWithdrawalsData($index);
        if ($result){
            echo json_encode(array(
                'index'=>$index+6,
                'data'=>$result
            ));
        } else{
            echo json_encode(array(
                'index'=>$index+6,
                'data'=>null
            ));
        }

    }

    /**
     * ajax拉取withdrawals单条记录详情
     */
    public function ajaxLoadWdDetail()
    {
        $id=I('post.id');
        $result=$this->tableMemberWithdrawals->queryWithdrawalsById($id);
        if (!$result){
            echo 404;
            exit();
        }
        //过滤他人获取
        if ($result['member_id']!=$_SESSION['member']) {
            echo 404;
            exit();
        }
        echo json_encode($result);
    }

    /**
     * ajax拉取taskHandle
     */
    public function ajaxLoadTaskHandle()
    {
        $index=I('post.index');
        $result=$this->tableTaskHandleModle->queryTaskHandData($index);
        if ($result){
            echo json_encode(array(
                'index'=>$index+6,
                'data'=>$result
            ));
        } else{
            echo json_encode(array(
                'index'=>$index+6,
                'data'=>null
            ));
        }
    }

    /**
     * ajax拉取任务审核状态
     */
    public function ajaxLoadTaskAuditStatus()
    {
        $id=I("post.id");
        $result=$this->tableTaskHandleModle->queryTaskSubmitResult($id);
        if ($result){
            if ($result[0]['member_id']!=$_SESSION['member']) {
                exit();
            }
            echo json_encode($result[0]);
        } else {
            echo 404;
        }

    }

    /**
     * ajax申诉任务
     */
    public function ajaxAppealTask()
    {
        $id=I('post.id');
        $result=$this->tableSubmitResult->where('id=%d ',$id)->field('id,audit_time,status,member_id,task_id')->find();
        if (!$result){
            echo "出错稍候再试！";
            exit();
        }
        //是否是本人
        if ($result['member_id']!==$_SESSION['member']){
            echo "出错请稍候再试！";
            exit();
        }
        //三天内可以申诉
        $audit_time=strtotime($result['audit_time'])+259200;
        if ($audit_time<time()){
            echo "超过申诉时间！";
            exit();
        }
        //状态是否正确
        if($result['status']!=2){
            echo "任务没有被驳回！";
            exit();
        }
        //开启事务
        $this->tableTaskAppeal->startTrans();
        
        //往task_appeal插入数据
        $result2=$this->tableTaskAppeal->insertNewData($result);

        //插入失败暂停，回滚
        if (!$result2) {
            echo '申诉申请失败请重试!';
            $this->tableTaskAppeal->rollback();
            exit();
        }
        
        //先将图片从临时文件夹拷贝永久文件夹
        $img=$this->tableSubmitPicture->where('task_submit_id=%d ',$id)->select();
        foreach ($img as $value){
            //文件所在路径
            $suorce='./Public/storage/taskImge/'.$value['image_url'];
            //文件要被拷贝路径
            $dir='./Public/storage/taskAppelImage/'.$value['image_url'];
            if (!copy($suorce,$dir)){
                echo "图片已经丢失无法申诉！";
                exit();
            }
        }

        //往task_appeal_picture表插入数据
        if (!$this->tableTaskAppealPicture->insertNewData($img,$result2)){
            echo "审核申诉提交失败，请重试";
            $this->tableTaskAppeal->rollback();
            exit();
        }

        //更新task_submit_result的状态
        $datas=array(
            'status'=>5
        );
        if ($this->tableSubmitResult->where('id=%d ',$id)->setField($datas)){
            echo 200;
            $this->tableTaskAppeal->commit();
        } else {
            echo "审核申诉提交失败，请重试";
            $this->tableTaskAppeal->rollback();
        }

    }

    /**
     * ajax拉取二维邀请码
     */
    public function loadQRcode()
    {
        $result=$this->tableMember->where('id=%d ',$_SESSION['member'])->field('invite_code')->find();
        if ($result['invite_code']==NULL) {
            //生成邀请码写入数据库，并创建二维码发给前端
            $inviteCode=creatInviteCode($_SESSION['member']);
            //写入数据库
            if ($this->tableMember->where('id=%d ',$_SESSION['member'])->setField('invite_code', $inviteCode)) {
                $url=$this->createQR($inviteCode);
                echo $url;
            } else {
                echo 404;
            }
        } else {
            //找已经存在二维码发给前端,没有创建二维码发给前端
            $fileName="./Public/storage/memberQr/".$result['invite_code'].".png";
            if (file_exists($fileName)) {
                $url=ASSETS_URL.'/storage/memberQr/'.$result['invite_code'].'.png';
                echo $url;
            } else {
                $url=$this->createQR($result['invite_code']);
                echo $url;
            }
        }
    }

    /**创建邀请二维码
     * @param $inviteCode
     * @return string
     */
    private function createQR($inviteCode)
    {
        $value=REGEDIT_URL.'/referee/'.$inviteCode;
        $errorCorrectionLevel = 'L';//容错级别
        $matrixPointSize = 6;//生成图片大小
        //引入第三方类库
        include_once VENDOR_PATH."QR/phpqrcode.php";
        $qr=new \QRcode();
        $qr::png($value, "./Public/storage/memberQr/$inviteCode.png", $errorCorrectionLevel, $matrixPointSize, 2);
        $url=ASSETS_URL."/storage/memberQr/$inviteCode.png";
        return $url;
    }

}

