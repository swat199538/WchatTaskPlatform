<?php
/**
 * Created by PhpStorm.
 * User: wangl
 * Date: 2016/9/5
 * Time: 10:05
 */
namespace Model;

use Components\Base\BaseModel;

class TaskHandleModel extends BaseModel
{
    public $memberId;

    /**
     * @param $index 第几条开始
     * @param $count=6 多少条数
     * @return bool|mixed
     */
    public function queryTaskHandData($index,$count=6)
    {
        $result=$this->query(
            " SELECT `task_id`,`pt_task_handle`.`status`,`accept_time`,`submit_result_id`,`title`,`price` 
              FROM pt_task_handle 
              LEFT JOIN pt_task 
              ON pt_task.id = pt_task_handle.task_id
              WHERE member_id=$this->memberId
              ORDER BY `pt_task_handle`.`create_time` DESC 
              LIMIT $index,$count");
        if ($result) {
            //dd($result);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * @param $id
     * @return bool|mixed
     */
    public function queryTaskSubmitResult($id)
    {
        $result=$this->query(
            " SELECT `id`,`member_id`,`status`,`audit_time`,`remark`
              FROM pt_task_submit_result 
              WHERE id= $id;
             "
        );

        if ($result) {
            //dd($result);
            return $result;
        } else {
            return false;
        }

    }

    /**
     * @param $id 任务
     */
    public function taskAppeal($id)
    {
        
    }

    

}