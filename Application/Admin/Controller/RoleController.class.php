<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2016/7/6
 * Time: 17:34
 */

namespace Admin\Controller;


use Components\Base\AdminController;
use Components\Helpers\FormatHelp;
use Model\NodeModel;
use Model\RoleModel;

class RoleController extends AdminController
{
    protected $role = null;
    protected $node = null;

    public function __construct()
    {
        parent::__construct();
        $this->role = new RoleModel();
        $this->node = new NodeModel();
    }

    public function index()
    {
        $data = $this->setPage($this->role);
        $this->display();
    }

    public function create()
    {
        if (IS_POST) {
            $this->assignData = I('post.');

            if ($res = $this->role->create($this->assignData)) {
                $bool = $this->role->insert($res);

                if ($bool) {
                    return $this->success('添加角色成功！', U('Role/index'));
                }

                return $this->error('添加角色失败！');
            }

            $this->assign('error', $this->role->getError());
        }

        $this->assign('data', $this->assignData);
        $this->display();
    }

    public function update($id)
    {
        $id = $id + 0;

        if ($id <= 0) {
            return $this->error('不存在的角色', U('Role/index'));
        }

        if (IS_POST) {
            $this->assignData = I('post.');

            if ($res = $this->role->create($this->assignData)) {
                $bool = $this->role->edit($res);

                if (false !== $bool) {
                    return $this->success('修改角色信息成功！', U('Role/index'));
                }

                return $this->error('修改用户信息失败！');
            }

            $this->assign('error', $this->role->getError());
        }

        if (empty($this->assignData)) {
            $this->assignData = $this->role->getRow($id);

            if (empty($this->assignData)) {
                return $this->error('不存在的用户', U('Manager/index'));
            }
        }

        $this->assign('data', $this->assignData);
        $this->display();
    }

    public function toConfigure($id)
    {
        // 保存角色的id
        $roleId = $id + 0;

        if ($roleId <= 0) {
            return $this->error('不存在的角色', U('Role/index'));
        }

        $tmpArr = array();
        $access = M('access');

        if (IS_POST) {
            $level = null;
            $nodeId = null;
            $roleId = I('post.id') + 0 ;
            $accessLists = I('post.access');

            if (empty($accessLists)) {
                $effectNum = $access->where("role_id=%d", array($roleId))->delete();
                return $this->success('分配权限成功！', U('Role/index'));
            }

            //先把用户的role_id对应的数据全部都删掉，然后重新insert。
            if (!empty($accessLists) && $roleId > 0) {
                foreach ($accessLists as $key => $item) {
                    $arrVal = explode('-', $item);
                    $tmpArr[$key]['role_id'] = $roleId;
                    $tmpArr[$key]['node_id'] = $arrVal[0];
                    $tmpArr[$key]['level'] = $arrVal[1];
                }

                $res = $access->where("role_id=%d", array($roleId))->find();

                if (!empty($res)) {
                    $effectNum = $access->where("role_id=%d", array($roleId))->delete();

                    if (empty($effectNum)) {
                        return $this->error('分配权限失败！');
                    }
                }

                $bool = $access->addAll($tmpArr);

                if ($bool) {
                    return $this->success('分配权限成功！', U('Role/index'));
                }

                return $this->error('分配权限失败！');
            }

        }

        // 先判断一下，用户是否已经选择了角色，把哪些角色的id取出来，然后checked
        $roleData = $access->where("role_id=%d", array($roleId))->field('node_id,level')->select();
        // 格式化
        if (!empty($roleData)) {
            foreach ($roleData as $item) {
                $tmpArr[] = $item['node_id'] . '-' . $item['level'];
            }
        }

        // 获取节点的列表，并格式化好展示。
        $lists = $this->node->getAll();

        if (!empty($lists)){
            $lists = FormatHelp::frontNavSort($lists);
        } else {
            return $this->error('请先设置节点');
        }
        
        // 注入到模板中，按层级显示。
        $this->assign('roleData', $tmpArr);
        $this->assign('lists', $lists);
        $this->assign('id', $roleId);
        $this->display();
    }

    public function delete($id)
    {
        $id += 0;

        if ($id <= 0) {
            return $this->error('不存在的角色');
        }

        $bool = M('role_manager')->where("role_id=%d", array($id))->find();

        if ($bool) {
            return $this->error('该角色有用户使用，请先删除用户下的角色！');
        }

        $bool = $this->delete($id);

        if ($bool) {
            return $this->success('删除角色成功！', U('Role/index'));
        }

        return $this->error('删除角色失败！');
    }
}
