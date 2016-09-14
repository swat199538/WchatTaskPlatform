<?php
/**
 * Created by PhpStorm.
 * User: wangl
 * Date: 2016/8/5
 * Time: 15:46
 */

namespace Model;

use Components\Base\BaseModel;
use Components\Helpers\ArrayHelper;
use Helpers;

class TaskSubmitPictureModel extends BaseModel
{
    //任务id
    public $taskId;

    public function __construct($taskId)
    {
        parent::__construct();
        $this->taskId=$taskId;
    }

    public function getAllByPage($startNo,$pageSize)
    {
        //$this->where("task_id=%d",$this->taskId)->limit($startNo,$pageSize)->order('create_time desc');
        $rest = $this->query(" SELECT  `result`.`id`,`result`.`create_time`,`task_id`,`member_id`,`status`,`task_submit_id`,`image_url`
                              FROM pt_task_submit_result AS result
                              LEFT JOIN pt_task_submit_picture AS picture ON result.id = picture.task_submit_id
                              WHERE task_id =$this->taskId AND result.status!=4
                              LIMIT $startNo , $pageSize");
        return ArrayHelper::filterRepeatTwoArry($rest,'task_submit_id','image_url');
    }

    public function filterCount()
    {
        $taskRest=M('task_submit_result');
        return $taskRest->where('task_id=%d',$this->taskId)->count();
    }

}