<?php
/**
 * Created by PhpStorm.
 * User: wangl
 * Date: 2016/9/12
 * Time: 10:41
 */
namespace Model;

use Components\Base\BaseModel;

class TaskAppealModel extends BaseModel
{
    public function insertNewData($info)
    {
        $data['create_time']=date("Y-m-d H:i:s", time());
        $data['status']=1;
        $data['task_id']=$info['task_id'];
        $data['member_id']=$info['member_id'];
        $data['submit_result_id']=$info['id'];
        
        if ($result=$this->add($data)){
            return $result;
        } else {
            return false;
        }
        
    }
}