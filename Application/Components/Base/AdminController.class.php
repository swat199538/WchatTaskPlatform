<?php
namespace Components\Base;
use Components\Extend\RBAC;
use Components\Helpers\FormatHelp;
use Components\Helpers\StringHelper;

/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2016/7/6
 * Time: 14:53
 */
class AdminController extends BaseController
{
    protected $userData = null;

    protected $msg = array(
        'create' => '添加操作执行成功！',
        'update' => '修改操作执行成功！'
    );

    public function __construct()
    {
        parent::__construct();
        $noAuthArr = explode(',', C('NOT_AUTH'));
        $currentRoute = MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME;

        if (!in_array($currentRoute, $noAuthArr)) {
            $res = $this->checkLogin();

            if (!$res) {
                return $this->redirect('Index/login');
            }

            $isSuper = session('user.is_super');
            $this->userData = $res;

            // 判断是否是超级管理员，1是就直接通过所有，0不是就继续判断。
            if (empty($res['is_super'])) {
                $data = array();
                $userId = $res['id'];
                $rbac = new RBAC();
                $tmpData = session('user_auth_info');

                if (!empty($tmpData)) {
                    $data = session('user_auth_info');
                    $authArr = $data['auth'];
                    $bool = in_array($currentRoute, $authArr);
                } else {
                    $bool = $rbac->getUserAuth($userId);
                    $data['nav'] = $rbac->getNav();
                }

                if (!$bool) {
                    return $this->error('您没有访问权限，请联系管理员！', U(C('RBAC_DEFAULT')));
                }

            }

            $this->assign('isSuper', $isSuper);
            $this->assign('navLists', isset($data['nav']) ? $data['nav'] : array());

        }
    }

    protected function checkLogin()
    {
        $user = session('user');

        if (is_null($user)) {
            return false;
        }

        return $user;
    }

}

