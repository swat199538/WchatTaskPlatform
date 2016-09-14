<?php
/**
 * Created by PhpStorm.
 * User: wangl
 * Date: 2016/8/11
 * Time: 14:39
 */
namespace Wchat\Controller;

use Components\Base\WchatController;

class TestController extends WchatController
{
    public function index()
    {
        /*$appid="wx3bb18b674d365a35";
        $appsecret="3ab7ad2728ef4a3b26b8e299a9eb6e99";
        $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";*/
        $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx3bb18b674d365a35&secret=3ab7ad2728ef4a3b26b8e299a9eb6e99";

        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $data=curl_exec($ch);
        $error=curl_error($ch);
        curl_close($ch);

        var_dump($data);

    }

    public function demo()
    {
        $token="kLyaaUke2sSsx52ukd_iisLJSiX4lniNdDrA_UmdDHWaPswAJQQyRLTFlui_rekTdlLZu1gCKqt45WJeLsELscghO_0z2j_UDbjbT6ZKBPmnQQOrf2TXkNty8z0snp1LBUMcAGAKDV";
        $json= '
            {
            "action_name": "QR_LIMIT_SCENE",
            "action_info": {
            "scene": {
            "scene_id": 1000
                }
                }
            }
        ';

        $url="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$token";

        $jsonInfo=$this->https_post($url,$json);

        $jsonInfo=json_decode($jsonInfo,true);
        var_dump($jsonInfo);
        //qFaGhHHM1nxJHVe4KnlAxNF9s2fkSUTlXbF1UlhV_pzJPxSJ82MH6Ct1QHKgu9zw1ekWwyGDjn5EBxcKTxDEFSzDwxP3mRsPQQHgCpOo0MeavvY-eE-bvb9kwOmT9FjGACUjACAKME

    }

    public function testToken()
    {
        $this->queryToken('wx3bb18b674d365a');

    }


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

    public function test()
    {
        $str='strtt_1';
        $tt=explode('st',$str);
        dd($tt);
    }

    public function test1()
    {
        $id='o-Bx0wj-uq6qB2-d1zeAZU6VZRq4';
        $tableMember=M('member');
        //通过微信openid找到用户，如果没有找到标示为绑定。
        $map['wx_open_id']=$id;
        $member=$tableMember->where($map)->field('id')->find();
        var_dump($member);
    }

    public function test2()
    {
        $tableTask=M('task');
        //去任务表去找当前符合分配条件的前50个任务
        $map['status']=array('EQ',3);
        $map['left_quantity']=array('NEQ',0);
        $map['start_time']=array('ELT',date("Y-m-d H:i:s",time()));
        $map['end_time']=array('EGT',date("Y-m-d H:i:s",time()));
        $taskRest=$tableTask->where($map)->
        order('actual_priority desc,priority desc,create_time asc,price desc,doing_count asc,complete_count asc')->
        select();
        echo $a=strtotime($taskRest[0]['create_time']);
    }

    public function test3()
    {
        $tablePartner=M('partner');
        $rest=$tablePartner->where("id=100000")->getField('balance');
        var_dump($rest);
    }

    public function test4()
    {
        $tableTaskHandle=M('task_handle');
        $map['member_id']=1;
        $map['_string']='status=0 or status=3';
        $taking=$tableTaskHandle->where($map)->field('task_id,status')->find();
        dd($taking);
        //echo $tableTaskHandle->getLastSql();
    }

    public function test5()
    {
        $tableTaskHandle=M('task_handle');
        $tableSubmitResult=M('task_submit_result');
        $tableTask=M('task');
        $map['id']=1;
        $tableTaskHandle->startTrans();
        $rest=$tableTaskHandle->where($map)->field('submit_result_id,task_id')->find();
        if ($rest['submit_result_id']!=null) {
            $map2['id']=$rest['submit_result_id'];
            //状态5表示任务放弃
            if (!$tableSubmitResult->where($map2)->setField('status',5)) {
                $tableTaskHandle->rollback();
                echo 204;
            }
        }
        //状态2表示任务放弃
        if (!$tableTaskHandle->where($map)->setField('status',2)) {
            $tableTaskHandle->rollback();
            echo 304;
        }
        unset($map);
        $map['id']=$rest['task_id'];
        $rest=$tableTask->where($map)->field('quantity,taking_quantity,abandon_count,audit_refuse_count,doing_count')->find();
        $taskData=array(
            'doing_count'=>$rest['doing_count']-1,
            'abandon_count'=>$rest['abandon_count']+1,
        );

        $taskData['doing_count']=$rest['doing_count']-1;
        $taskData['abandon_count']=$rest['abandon_count']+1;
        $leftQuantity=$rest['quantity']-$rest['taking_quantity']+$taskData['abandon_count']+$rest['audit_refuse_count'];
        $taskData['left_quantity']=$leftQuantity;
        if ($tableTask->where($map)->setField($taskData)) {
            $tableTaskHandle->commit();
            echo 404;
        } else {
            $tableTaskHandle->rollback();
            echo  505;
        }
    }

    public function test6()
    {
        $tableSubmitResult=M('task_submit_result');
        $resultData=array(
            'create_time'=>date("Y-m-d H:i:s",time()),
            'update_time'=>date("Y-m-d H:i:s",time()),
            'task_id'=>12,
            'member_id'=>9,
            'status'=>4,
            'operator'=>'系统'
        );
        $resultId=$tableSubmitResult->data($resultData)->add();
        dd($resultId);
    }

}