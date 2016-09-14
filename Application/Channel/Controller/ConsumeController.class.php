<?php
/**
 * Created by PhpStorm.
 * User: wangl
 * Date: 2016/8/8
 * Time: 16:47
 */
namespace Channel\Controller;

use Components\Base\ChannelController;
use Model\PartnerDeposit;
use Model\PartnerDepositModel;
use Think\Model;

class ConsumeController extends ChannelController
{
    //渠道商信息seeion
    private $parther;
    //渠道商表实例
    private $tablePartnerDeposit;

    public function __construct()
    {
        parent::__construct();
        $this->parther=$_SESSION['partner'];
        $this->tablePartnerDeposit=new PartnerDepositModel($this->parther['id']);

    }

    /**
     * 首页显示
     */
    public function index()
    {
        $formData=I("get.");
        
        if (!empty($formData['create_time'])&&$formData['create_time']!=0) {
            $data['create_time']=$formData['create_time'];
        }
        if (!empty($formData['type'])&&$formData['type']!=0) {
            $data['type']=$formData['type'];
        }
        $this->tablePartnerDeposit->filterType=1;
        $this->tablePartnerDeposit->filterArr=$data;
        $this->setPage($this->tablePartnerDeposit,1);
        $this->display();
    }


}