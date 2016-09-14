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

class NodeController extends AdminController
{
    protected $node = null;

    public function __construct()
    {
        parent::__construct();
        $this->node = new NodeModel();
    }

    public function index()
    {
        $data = $this->node->getAllByPage();
        $this->assign('data', $data);
        $this->display();
    }

    public function create()
    {
        if (IS_POST) {
            $this->assignData = I('post.');

            if ($res = $this->node->create($this->assignData)) {
                $tmpArr = explode('-', $res['pid']);
                $res['pid'] = $tmpArr[0];
                $res['level'] = $tmpArr[1] + 1;
                $bool = $this->node->insert($res);

                if ($bool) {
                    return $this->success('添加节点成功！', U('Node/index'));
                }

                return $this->error('添加节点失败！');
            }
            
            $this->assign('error', $this->node->getError());
        }
        $selectLists = $this->node->getSelectLists();
        $this->assign('data', $this->assignData);
        $this->assign('selectLists', $selectLists);
        $this->display();
    }

    public function update($id)
    {
        $id = $id + 0;

        if ($id <= 0) {
            return $this->error('不存在的节点');
        }

        if (IS_POST) {
            $this->assignData = I('post.');

            if ($res = $this->node->create($this->assignData)) {
                $bool = $this->node->edit($res);

                if (false !== $bool) {
                    return $this->success('修改节点信息成功！', U('Node/index'));
                }

                return $this->error('修改节点信息失败！');
            }

            $this->assign('error', $this->node->getError());
        }

        if (empty($this->assignData)) {
            $this->assignData = $this->node->getRow($id);
        } else {
            $pid = explode('-', $this->assignData['pid']);
            $this->assignData['pid'] = $pid[0];
        }

        $selectLists = $this->node->getSelectLists();
        $this->assign('selectLists', $selectLists);
        $this->assign('data', $this->assignData);
        $this->display();

    }

    public function delete($id)
    {
        $id += 0;

        if ($id <= 0) {
            return $this->error('不存在的节点');
        }

        $row = $this->node->getAll();

        // 删除时要验证， 被删除的节点没有子级节点，有子节点，必须先把子节点删光。
        if (!empty($row)) {
            $data = FormatHelp::getSubById($row, $id);

            if (!empty($data)) {
                return $this->error('该节点有子节点，不能直接删除，需要先把子节点删除！');
            }
        }

        // 删除时还要判断，角色里面有没有使用，没有使用才能删除。否则先删除角色里面的节点。
        $isHas = M('access')->where("node_id=%d", array($id))->find();

        if (!empty($isHas)) {
            return $this->error('该节点在权限中已经使用，请先删除权限中的节点信息！');
        }

        $bool = $this->node->remove($id);

        if (!empty($bool)) {
            return $this->success('删除节点成功！', U('Node/index'));
        }

        return $this->error('删除节点成功！');
    }
}
