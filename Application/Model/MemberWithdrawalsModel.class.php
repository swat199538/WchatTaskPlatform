<?php
/**
 * Created by PhpStorm.
 * User: wangl
 * Date: 2016/9/5
 * Time: 10:05
 */
namespace Model;

use Components\Base\BaseModel;

class MemberWithdrawalsModel extends BaseModel
{
    public $memberId;

    /**
     * @param $index 第几条开始
     * @param $count=6 多少条数
     * @return bool|mixed
     */
    public function queryLimitWithdrawalsData($index,$count=6)
    {
        $result=$this->where('member_id=%d ',$this->memberId)
        ->order('create_time desc')
        ->limit($index,$count)
        ->field('id,create_time,withdrawals_type,amount,status,audit_status')
        ->select();
        if ($result){
            return $result;
        } else{
            return false;
        }
    }

    /**
     * @param $id
     * @return bool|mixed
     */
    public function queryWithdrawalsById($id)
    {
        $result=$this->where('id=%d ',$id)->find();
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

}