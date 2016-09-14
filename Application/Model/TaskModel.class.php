<?php
/**
 * Created by PhpStorm.
 * User: wangl
 * Date: 2016/7/28
 * Time: 14:37
 */
namespace Model;

use Components\Base\BaseModel;

class TaskModel extends BaseModel
{
    private $id;
    //protected $insertFields = array('title', 'price', 'start_time', 'end_time', 'quantity', 'vote_url', 'vote_remark', 'rate');
    //protected $updateFields = array('title', 'price', 'start_time', 'end_time', 'rate', );

    //是否开启过滤模式0否，1是
    public $filterType=0;

    //过滤条件
    public $filterArr=array();

    //验证添加任务合法
    public $_form_validate=array(
        array('title', 'require', '任务名必须填写'),
        array('title', '2,25', '任务名长度应在2~25位之间', 0, 'length'),

        array('price', 'require', '任务单价必须填写'),
        array('price', '/^(([0-9]+.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*.[0-9]+)|([0-9]*[1-9][0-9]*))$/', '任务只能是正浮点数', 0, 'regex', 1),
        array('price', '1,60', '任务单价不得少于6位或大于60位', 2, 'length', 1),

        array('start_time', 'require', '开始时间必须填写'),
        array('start_time', '/[- :]/', '格式错误!(知道您很厉害的不要攻击我嘛！)', 0, 'regex', 1),

        //array('end_time', 'checkEndTime', '结束时间不能等于小于', 0, 'function'),
        array('end_time', 'require','结束时间必须填写'),
        array('end_time', '/[- :]/','格式错误!(知道您很厉害的不要攻击我嘛！)', 0, 'regex', 1),

        array('quantity', 'require','人数必须填写'),
        array('quantity', '/^[0-9]*[1-9][0-9]*$/', '人数必须是大于0的整数', 0, 'regex', 1),

        array('vote_url', 'require', '去投票的网址必须填写'),
        array('vote_url', 'url', 'url格式不正确'),

        array('vote_remark', 'require', '投票内容必须填'),
        array('vote_remark', '5,200', '投票内容不得少于5或大于200', 2, 'length', 1),

        array('rate', 'checkRate', '限制数必须非负的整数', 0, 'function'),

   );

    //验证修改任务合法
    public $_edit_task =array(
        array('title', 'require', '任务名必须填写'),
        array('title', '2,25', '任务名长度应在2~25位之间', 0, 'length'),

        array('price', 'require', '任务单价必须填写'),
        array('price', '/^(([0-9]+.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*.[0-9]+)|([0-9]*[1-9][0-9]*))$/', '任务只能是正浮点数', 0, 'regex', 1),
        array('price', '1,60', '任务单价不得少于6位或大于60位', 2, 'length', 1),

        array('start_time', 'require', '开始时间必须填写'),
        array('start_time', '/[- :]/', '格式错误!(知道您很厉害的不要攻击我嘛！)', 0, 'regex', 1),

        //array('end_time', 'checkEndTime', '结束时间不能等于小于', 0, 'function'),
        array('end_time', 'require','结束时间必须填写'),
        array('end_time', '/[- :]/','格式错误!(知道您很厉害的不要攻击我嘛！)', 0, 'regex', 1),

        array('rate', 'checkRate', '限制数必须非负的整数', 0, 'function'),

    );

    //验证增加人数合法性
    public $_add_task=array(
        array('add_quantity', 'require', '新增人数必须填写'),
        array('add_quantity', 'checkQuantity', '请输入非负整数', 0, 'function'),
        array('add_quantity', 'checkPopulation', '人数不能小于1', 0, 'function'),
    );

    //验证加急合法性
    public $_expedite=array(
        array('growPrice', 'require', '新增价格必须填写'),
        array('growPrice', 'checkGrowPrice', '必须大于零的数字', 0, 'function')
    );

    //验证筛选参数合法性
    public $_filter=array(
        //array('title', '2,25', '任务名长度应在2~25位之间', 0, 'length'),
        //array('create_time', '/[-]/', '格式错误!(知道您很厉害的不要攻击我嘛！)', 0, 'regex', 1),
        array('status', 'checkStatus', '状态错误', 0, 'function')
    );

    public function __construct($id)
    {
        parent::__construct();
        $this->id=$id;
    }


    public function getAllByPage($startNo,$pageSize)
    {
        if ($this->filterType==0) {
           return $this->where("partner_id=%d",$this->id)->limit("$startNo, $pageSize")->order('create_time desc')->select();
        }
        if ($this->filterType==1) {
            $this->spliceSql();
           return $this->where($this->filterArr)->limit("$startNo, $pageSize")->order('create_time desc')->select();
        }
    }

    public function filterCount()
    {
        $this->spliceSql();
        return $this->where($this->filterArr)->count();
    }

    private function spliceSql()
    {
        $this->filterArr['partner_id']=$this->id;
    }

}