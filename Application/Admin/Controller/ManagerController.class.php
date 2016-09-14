<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2016/7/6
 * Time: 17:34
 */

namespace Admin\Controller;


use Components\Base\AdminController;
use Model\ManagerModel;
use Model\RoleModel;

class ManagerController extends AdminController
{
    protected $manager;
    protected $role;

    public function __construct()
    {
        parent::__construct();
        $this->manager = new ManagerModel();
        $this->role = new RoleModel();
    }

    public function index()
    {
        $data = $this->setPage($this->manager);
        $this->display();
    }

    public function create()
    {
        if (IS_POST) {
            $this->assignData = I('post.');

            if ($res = $this->manager->create($this->assignData)) {
                $bool = $this->manager->insert($res);

                if ($bool) {
                    return $this->success('添加管理员成功！', U('Manager/index'));
                }

                return $this->error('添加管理员失败！');
            }

            $this->assign('error', $this->manager->getError());
        }

        $this->assign('data', $this->assignData);
        $this->display();
    }

    public function update($id)
    {
        $id = $id + 0;

        if ($id <= 0) {
            return $this->error('不存在的用户', U('Manager/index'));
        }

        if (IS_POST) {
            $this->assignData = I('post.');

            if ($res = $this->manager->create($this->assignData)) {
                $bool = $this->manager->edit($res);

                if (false !== $bool) {
                    return $this->success('修改用户信息成功！', U('Manager/index'));
                }

                return $this->error('修改用户信息失败！');
            }

            $this->assign('error', $this->manager->getError());
        }

        if (empty($this->assignData)) {
            $this->assignData = $this->manager->getRow($id);

            if (empty($this->assignData)) {
                return $this->error('不存在的用户', U('Manager/index'));
            }
        }

        $this->assign('data', $this->assignData);
        $this->display();
    }

    public function delete($id)
    {
        $id = $id + 0;

        if ($id <= 0) {
            return $this->error('不存在的用户', U('Manager/index'));
        }

        $bool = $this->manager->remove($id);

        if ($bool) {
            return $this->success('删除用户成功', U('Manager/index'));
        }

        return $this->error("该用户无法删除！", U('Manager/index'));
    }

    public function toConfigure($id)
    {
        $tmpArr = array();
        $roleManager = M('role_manager');
        $userId = $id + 0;
        $userData = $this->manager->getRow($id);

        if ($userId <= 0 || empty($userData)) {
            return $this->error('不存在的用户', U('Manager/index'));
        }

        if (IS_POST) {
            $selectArr = I('post.role');
            $userId = I('post.id') + 0 ;

            if (empty($selectArr)) {
                $effectNum = $roleManager->where("user_id=%d", array($userId))->delete();
                return $this->success('分配角色成功！', U('Manager/index'));
            }

            if (!empty($selectArr)) {
                foreach ($selectArr as $key => $item) {
                    $tmpArr[$key]['role_id'] = $item;
                    $tmpArr[$key]['user_id'] = $userId;
                }

                $res = $roleManager->where("user_id=%d", array($userId))->find();

                if (!empty($res)) {
                    $effectNum = $roleManager->where("user_id=%d", array($userId))->delete();

                    if (empty($effectNum)) {
                        return $this->error('分配角色失败！');
                    }
                }

                $bool = $roleManager->addAll($tmpArr);

                if ($bool) {
                    return $this->success('分配角色成功！', U('Manager/index'));
                }

                return $this->error('分配角色失败！');
            }

        }

        // 先判断一下，用户是否已经选择了角色，把哪些角色的id取出来，然后checked
        $userRole = $roleManager->where("user_id=%d", array($userId))->field('role_id')->select();

        if (!empty($userRole)) {
            foreach ($userRole as $item) {
                $tmpArr[] = $item['role_id'];
            }
        }

        $roleData = $this->role->getAll();
        $this->assign('userRole', $tmpArr);
        $this->assign('user', $userData);
        $this->assign('role', $roleData);
        $this->display();
    }

}