<?php
/**
 * Created by PhpStorm.
 * User: wangl
 * Date: 2016/7/26
 * Time: 10:41
 */
namespace Channel\Controller;

use Components\Base\ChannelController;
use Components\Helpers\ArrayHelper;
use Model\TaskModel;
use Model\TaskSubmitPicture;
use Model\TaskSubmitPictureModel;
//use Org\Util\Date;
//use Think\Image;
use Think\Model;
//use Think\Page;




class TaskController extends ChannelController
{
    //用户信息seeion
    private $parther;
    //task表的实例
    private $tableTask;
    //partner表的实例
    private $tablePartner;
    //task_log表的实力
    private $tableTaskLog;
    //post表单数据
    private $postData;
    //post表单验证规则
    protected $task;
    //上传结果验证实例
    protected $taskSubmit;
    //任务数据
    private $taskRest;


    public function __construct()
    {
        parent::__construct();
        $this->parther=$_SESSION['partner'];
        $this->tableTask=M('task');
        $this->tablePartner=M('partner');
        $this->tableTaskLog=M('task_log');
        $this->task = new TaskModel($this->parther['id']);
        $this->taskSubmit=new TaskSubmitPictureModel();
    }


    //显示渠道商进行中的任务
    public function index()
    {
        $formData=I('get.');
        if($formData['title']!=0&&!empty($formData['title'])) {
            $data['title']=array('like','%'.$formData['title'].'%');
        }
        if($formData['create_time']!=0&&!empty($formData['create_time'])) {
            $data['create_time']=array('like',$formData['create_time'].'%');
        }
        if($formData['status']!=0&&!empty($formData['status'])) {
            $data['status']=array('eq',$formData['status']);
        }
        $data['partner_id']=array('eq',$this->parther['id']);
        $this->task->filterType=1;
        $this->task->filterArr=$data;
        $this->setPage($this->task,1);
        $this->display();

    }


    //渠道商发布任务
    public function publishTask()
    {
        //查询的余额
        $this->assign('balance',$this->selectBalance());

        //提交表单
        if (IS_POST) {
            $this->postData=I('post.');
            //验证表单
            if ($res = $this->task->validate($this->task->_form_validate)->create()) {
                $this->creatTask($this->postData);
            } else {
                $this->assign('info',$this->postData);
                $this->assign('error', $this->task->getError());
                $this->display();
            }

        } else {
            $this->display();
        }


    }

    //渠道商修改任务
    public function editTask()
    {
        $this->actTask('edit');
    }


    //渠道商增加任务人数
    public function addTask()
    {
        $this->actTask('add');
    }

    //任务竞价加急
    public function expediteTask()
    {
        $this->actTask('expedite');
    }

    //任务暂停
    public function pasueTask()
    {
        if (IS_POST) {
            $this->postData=I('post.');
            if ($this->checkTask($this->postData['id'])) {
                $rest=$this->tableTask->where('id=%d',$this->postData['id'])->getField('status');
                //暂停
                if ($this->postData['act']=='pasue') {
                    switch ($rest)
                    {
                        case '3':
                            if ($this->setTaskStatus($this->postData['id'],4)) {
                                echo 200;
                            } else {
                                echo 500;
                            }
                            break;
                        default:
                            echo 500;
                            break;
                    }
                }
                //开始
                if ($this->postData['act']=='start') {
                    switch ($rest)
                    {
                        case '4':
                            $balance=$this->tablePartner->where('id=%d',$this->parther['id'])->getField('balance');
                            $taskPrice=$this->tableTask->where('id=%d',$this->postData['id'])->getField('price');
                            if ($balance<=0||$balance<$taskPrice) {
                                //余额不足
                                echo 202;
                                exit();
                            }
                            if ($this->setTaskStatus($this->postData['id'],3)) {
                                echo 300;
                            } else {
                                echo 500;
                            }
                            break;
                        default:
                            echo 500;
                            break;
                    }
                }

            } else {
                echo 404;
            }
        } else {
            echo 404;
        }
    }

    //任务流水
    public function  logTask()
    {
        $taskId=I('get.id');
        if ($this->checkTask($taskId)) {
            $logRest=$this->tableTaskLog->where('task_id=%d and type=1',$taskId)->order('create_time desc')->select();
            //dd($logRest);
            $this->assign("log",$logRest);
            $this->display();
        } else {
            $this->error('权限错误','index',3);
        }

    }

    //任务截图
    public function pictureTask()
    {
        $taskId=I('get.id');
        if ($taskId==null) {
            $this->error('权限错误');
        }
        if ($this->checkTask($taskId)) {
            $this->taskSubmit->taskId=$taskId;
            $this->setPage($this->taskSubmit,1);
            $this->display();
        } else {
            $this->error('权限错误');
        }

    }

    //任务审核
    public function reviewTask()
    {

    }

    /**
     * 修改改任务的一系列操作
     */
    private function actTask($Type)
    {
        switch ($Type)
        {
            case 'add':
                $rule=&$this->task->_add_task;
                break;
            case 'edit':
                $rule=&$this->task->_edit_task;
                break;
            case 'expedite':
                $rule=&$this->task->_expedite;
                break;
            default:
                exit();
                break;
        }

        if(IS_POST) {
            $this->postData=I("post.");
            if ($res= $this->task->validate($rule)->create()) {
                if ($this->checkTask($this->postData['id'])) {
                    if ($Type==='add') {
                        $this->updataTaskQuantity($this->postData,$this->taskRest);
                    } elseif ($Type==='edit') {
                        $this->updataTask($this->postData);
                    } elseif ($Type==='expedite') {
                        $this->updataTaskPrice($this->postData,$this->taskRest);
                    }
                    else {
                        exit();
                    }

                } else {
                    $this->error('权限错误！','index',3);
                    exit();
                }
            } else {
                $this->assign('error',$this->task->getError());
                $this->assign('info',$this->postData);
                $this->display();
                exit();
            }
        }

        $taskId=I('get.id');
        if ($taskId==null) {
            $this->error('权限错误！','index',3);
        }

        if ($this->checkTask($taskId)) {
            //查询的余额
            $this->assign('balance',$this->selectBalance());
            $this->assign('taskRest',$this->taskRest);
            //dd($this->taskRest);
            $this->display();
        } else {
            $this->error('权限错误!');
        }


    }


    //往task插入数据
    private function creatTask($data)
    {
        $listData['create_time']=date('Y-m-d H:i:s',time());
        $listData['update_time']=date('Y-m-d H:i:s',time());
        $listData['partner_id']=$this->parther['id'];
        $listData['title']=$data['title'];
        $listData['type']=1;
        $listData['price']=$data['price'];
        $listData['quantity']=$data['quantity'];
        $listData['rate']=$data['rate'];
        $listData['priority']=0;
        $listData['audit_type']=1;
        $listData['task_config']='{"url":"'.$data['vote_url'].'","remark":"'.$data['vote_remark'].'"}';
        $listData['start_time']=$data['start_time'];
        $listData['end_time']=$data['end_time'];
        $listData['left_quantity']=$data['quantity'];
        $listData['status']=1;
        $listData['remark']=$data['remark'];
        $listData['left_quantity']=$data['quantity'];

        $rest=$this->tableTask->data($listData)->add();

        if($rest){
            $this->success('添加任务成功','publishTask',3);
        } else {
            $this->error('添加任务失败','publishTask',3);

        }

    }

    //修改task数据
    private function updataTask($data)
    {
        $listData['update_time']=date('Y-m-d H:i:s',time());
        $listData['title']=$data['title'];
        $listData['start_time']=$data['start_time'];
        $listData['end_time']=$data['end_time'];
        $listData['rate']=$data['rate'];
        $listData['remark']=$data['remark'];
        $listData['status']=1;

        $rest=$this->tableTask->where('id=%d',$this->postData['id'])->save($listData);

        if($rest) {
            $this->success('修改任务成功','index',3);
            exit();
        } else {
            $this->error('修改任务失败','index',3);
            exit();
        }


    }
    
    //更新人数
    private function updataTaskQuantity($postData,$taskRest)
    {
        $countQ=$postData['add_quantity']+$taskRest[0]['quantity'];
        $countLq=$postData['add_quantity']+$taskRest[0]['left_quantity'];

        $data=array('quantity'=>$countQ,'left_quantity'=>$countLq);

        $rest=$this->tableTask->where("id=%d",$postData['id'])->setField($data);

        if ($rest) {
            $logData=array(
                'id'=>$postData['id'],
                'add_quantity'=>$postData['add_quantity'],
                'after_quantity'=>$countQ
            );
            $this->addTaskLog(1,$logData);
            $this->success('增加人数成功','index',3);
            exit();
        } else {
            $this->error('增加人数失败','index',3);
            exit();
        }

    }

    //更新单价
    private function updataTaskPrice($postData,$taskRest)
    {
        $countPrice=$postData['growPrice']+$taskRest[0]['price'];
        $data=array(
            'price'=>$countPrice,
            'status'=>1
        );

        $rest=$this->tableTask->where("id=%d",$postData['id'])->setField($data);

        if ($rest) {
            $logData=array(
                'id'=>$postData['id'],
                'add_price'=>$postData['growPrice'],
                'after_price'=>$countPrice
            );
            $this->addTaskLog(2,$logData);
            $this->success('增加单价成功','index',3);
            exit();
        } else {
            $this->error('增加单价失败','index',3);
            exit();
        }

    }

    //检查修改权限
    private function checkTask($taskId)
    {
        $this->taskRest=$this->tableTask->where('id=%d',$taskId)->select();
        //检查是否有任务，修改任务者和任务发布者是否相同
        if($this->taskRest!=NULL) {
            if($this->taskRest[0]['partner_id']==$this->parther['id']) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    //查询余额
    private function  selectBalance()
    {
        return $this->tablePartner->where("id=%d",$this->parther['id'])->getField('balance');
    }

    //设置任务状态
    private function setTaskStatus($id,$data)
    {
        return $this->tableTask->where("id=%d",$id)->setField('status',$data);
    }


    //记录日志
    private function addTaskLog($type,$postData)
    {
        $data['create_time']=date('Y-m-d H:i:s',time());
        $data['type']=$type;
        $data['task_id']=$postData['id'];
        switch ($type)
        {
            case 1:
                $data['add_quantity']=$postData['add_quantity'];
                $data['after_quantity']=$postData['after_quantity'];
                break;
            case 2:
                $data['add_price']=$postData['add_price'];
                $data['after_price']=$postData['after_price'];
                break;
        }
        return $this->tableTaskLog->add($data);
    }

}