<?php

/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2016/7/8
 * Time: 17:05
 */
namespace Model;

use Components\Base\BaseModel;
use Components\Helpers\FormatHelp;

class NodeModel extends BaseModel
{
    protected $insertFields = array('name', 'title', 'status', 'pid', 'sort', 'level');
    protected $updateFields = array('id', 'name', 'title', 'status', 'pid', 'sort', 'level');

    protected $_auto = array(
        array('created_at', 'time', 1, 'function'),
        array('updated_at','time', 2, 'function')
    );

    /**
     * @var array 用户添加和修改的验证
     */
    // todo...修改时验证名称除自己外是唯一的。
    // todo...节点的父级不能是自己或子类。
    protected $_validate = array(
        array('name', 'require', '节点名称必须填写'), //默认情况下用正则进行验证
        //array('name', '', '节点名称已经存在', 0, 'unique', 1),
        array('name', '/^[a-zA-Z]{2,50}$/', '名称长度不符合规范或含有非法字符', 2, 'regex', 3),
        //array('name', 'notEqSelf', '节点名称已经存在！',1, 'callback', 2, array('id', 'name')),

        array('title', 'require', '描述必须填写'),
        array('title', '/^[\x{4E00}-\x{9FA5}a-zA-Z]+$/u', '描述只能是中文或英文单词', 2, 'regex', 3),
        array('title', '2,50', '描述的长度不合法', 2, 'length', 3),

        array('status', 'require', '用户状态必须填写'),

        array('pid', 'require', '所属分类必须选择'),
        array('pid', 'checkPidVal', '所属分类数据出错', 1, 'callback'),
        array('pid', 'notSelfAndSub', '选择的所属分类不能是它自身或它的子级', 1, 'callback', 2),
    );

    protected function notSelfAndSub($val)
    {
        $arr = explode('-', $val);
        $pid = $arr[0] + 0;
        $id = I('post.id');
        $data = $this->field('id, pid, level')->where('status <> 0')->select();
        FormatHelp::$treeLists = array();
        $arr = FormatHelp::getSubById($data, $id, true);
        $bool = in_array($pid, $arr);

        if ($bool){
            return false;
        }

        return true;
    }

    protected function checkPidVal($val)
    {
        $arr = explode('-', $val);
        $arr[0] += 0;

        if (!is_int($arr[0])) {
            return false;
        }

        if (in_array($arr[0], array(1,2,3))) {
            return false;
        }

        return true;
    }

    public function getSelectLists()
    {
        $data = $this->field('id, title, pid, level')->where('status = 1 and level <= 3')->order('sort desc')->select();

        if (!empty($data)) {
            $data = FormatHelp::treeSort($data);
        }

        return $data;
    }


    public function getAllByPage()
    {
        $res = $this->field('id, name, pid, title, remark, level, status')->select();

        if (!empty($res)) {
            $res = FormatHelp::treeSort($res);
        }

        return $res;
    }

    public function getAll($fields = array())
    {
        if (empty($fields)) {
            return $this->where('status <> 0')->select();
        }

        $fields = implode(',', $fields);

        return $this->field($fields)->where('status <> 0')->select();
    }

    public function edit($data)
    {
        $arr = explode('-', $data['pid']);
        $data['pid'] = $arr[0];
        $data['level'] = $arr[1] + 1;

        return $this->save($data);
    }
}