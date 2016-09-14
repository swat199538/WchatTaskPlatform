<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2016/7/6
 * Time: 15:30
 */

namespace Components\Base;


use Think\Model;

class BaseModel extends Model
{
    protected $patchValidate = true;

    /**
     * 添加一个验证规则，唯一但是不包含自身
     */
    protected function notEqSelf($val, $id, $field)
    {
        $currentId = I('post.' . $id);
        $isHas = $this->where("id<>%d and {$field}='%s'", array($currentId, $val))->find();

        if (empty($isHas)) {
            return true;
        }

        return false;
    }

    public function insert($data)
    {
        return $this->add($data);
    }

    public function edit($data)
    {
        return  $this->save($data);
    }

    public function remove($id)
    {
        $res = $this->find($id);

        if (empty($res)) {
            return false;
        }

        return $this->delete($id);
    }

    public function getRow($id, $fields = array())
    {
        if (empty($fields)) {
            return $this->find($id);
        }

        $fields = implode(',', $fields);

        return $this->field($fields)->find($id);
    }

    public function getAll($fields = array())
    {
        if (empty($fields)) {
            return $this->field('*')->select();
        }

        $fields = implode(',', $fields);

        return $this->field($fields)->select();
    }
    
    public function filterCount()
    {
        
    }
    
}