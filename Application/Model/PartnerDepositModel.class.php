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

class PartnerDepositModel extends BaseModel
{
    //任务id
    private $partherId;

    //是否筛选
    public $filterType=0;
    //筛选条件
    public $filterArr=array();

    //where条件
    private $sql;
    public function __construct($partherId)
    {
        parent::__construct();
        $this->partherId=$partherId;
    }

    public function getAllByPage($startNo,$pageSize)
    {
        if($this->filterType==0) {
            return $this->query(" SELECT  `result`.`id`,`result`.`create_time`,`result`.`type`,`credit`,
                              `debit`,`title` 
                              FROM pt_partner_deposit AS result
                              LEFT JOIN pt_task  ON result.relation_id = pt_task.id
                              WHERE result.partner_id =$this->partherId
                              ORDER BY result.create_time DESC 
                              LIMIT $startNo , $pageSize");
        }
        if($this->filterType==1){
            $this->spliceSql();
            return $this->query(
                " SELECT  `result`.`id`,`result`.`create_time`,`result`.`type`,`credit`,
                              `debit`,`title` 
                              FROM pt_partner_deposit AS result
                              LEFT JOIN pt_task  ON result.relation_id = pt_task.id
                              WHERE $this->sql
                              ORDER BY result.create_time DESC 
                              LIMIT $startNo , $pageSize"
            );
        }
    }

    public function filterCount()
    {
        $this->spliceSql();
        $rest= $this->query(" SELECT COUNT(*) FROM pt_partner_deposit AS result WHERE $this->sql ");
        return $rest[0]['count(*)'];
    }

    private function spliceSql()
    {
        $this->sql="result.partner_id=$this->partherId";
        if (array_key_exists("type",$this->filterArr)) {
            $this->sql.=" AND result.type=".$this->filterArr['type'];
        }
        if (array_key_exists("create_time",$this->filterArr)) {
            $this->sql.=" AND result.create_time like '".$this->filterArr['create_time']."%' ";
        }
    }

}