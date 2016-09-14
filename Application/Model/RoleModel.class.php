<?php

/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2016/7/8
 * Time: 17:05
 */
namespace Model;

use Components\Base\BaseModel;
use Think\Verify;

class RoleModel extends BaseModel
{
    protected $insertFields = array('name', 'remark', 'status');
    protected $updateFields = array('id', 'name', 'remark', 'status');

    protected $_auto = array(
        array('created_at', 'time', 1, 'function'),
        array('updated_at','time', 2, 'function')
    );

    /**
     * @var array 用户添加和修改的验证
     */
    protected $_validate = array(
        array('name', 'require', '角色名称必须填写'), //默认情况下用正则进行验证
        array('name', '', '角色名称已经存在', 0, 'unique', 1),
        array('name', 'notEqSelf', '角色名称已经存在！',1, 'callback', 2, array('id', 'name')),
        array('name', '/^[a-zA-Z]{2,15}$/', '长度不符合规范或含有非法字符', 2, 'regex', 3),

        array('remark', 'require', '描述必须填写'),
        array('remark', '/^[\x{4E00}-\x{9FA5}a-zA-Z]+$/u', '描述只能是中文或英文单词', 2, 'regex', 3),
        array('remark', '2,15', '描述的长度不合法', 2, 'length', 3),

        array('status', 'require', '用户状态必须存在'),
        array('status', array(0, 1), '登入状态不合法', 2, 'in')
    );


    public function getAllByPage($startNo, $pageSize)
    {
        return $this->field('id, name, remark, status')->limit("$startNo, $pageSize")->select();
    }

    public function getAll()
    {
        return $this->where('status>0')->field('id,name,status,remark')->select();
    }
    
}