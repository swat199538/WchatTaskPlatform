<?php
/**
 * Created by PhpStorm.
 * User: wangl
 * Date: 2016/9/12
 * Time: 11:10
 */
namespace Model;

use Components\Base\BaseModel;

class TaskAppealPictureModel extends BaseModel
{
    public function insertNewData($info,$taskid)
    {
        $create_time=date("Y-m-d H:i:s", time());
        foreach ($info as $value) {
            $dataList[]=array(
                'create_time'=>$create_time,
                'task_appeal_id'=>$taskid,
                'image_url'=>$value['image_url']
            );
        }
        
        if ($this->addAll($dataList)) {
            return true;
        } else {
            return false;
        }
    }
}