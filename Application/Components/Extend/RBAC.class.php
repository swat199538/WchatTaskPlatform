<?php
namespace Components\Extend;
use Components\Helpers\ArrayHelper;
use Components\Helpers\FormatHelp;
use Components\Helpers\StringHelper;

/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2016/7/27
 * Time: 10:51
 */
class RBAC
{
    protected $action;
    protected $module;
    protected $controller;
    protected $currentRoute;
    protected $nav = array();

    public function __construct()
    {
        $this->action = ACTION_NAME;
        $this->controller = CONTROLLER_NAME;
        $this->module = MODULE_NAME;
        $this->currentRoute = $this->module . '/' . $this->controller . '/' . $this->action;
    }

    public function getUserAuth($userId)
    {
        $retArr = array();
        $navLists = array();
        // 根据用户，取出用户拥有的角色id。
        $roleManager = M('role_manager')->field('role_id')->where("user_id=%d", array($userId))->select();

        // 对于没有任何权限的用户，只能访问自己的首页
        if (empty($roleManager)) {
            if (0 === strcmp($this->currentRoute, C('RBAC_DEFAULT'))) {
                return true;
            }

            return false;
        }

        $res = $this->getNodeLists($roleManager);

        if (empty($res)) {
            return false;
        }

        $retArr = $this->formatAuthData($res);
        $retArr['nav'] = $this->nav = $this->formatNav($retArr['nav']);

        if (!in_array($this->currentRoute, $retArr['auth'])) {
            return false;
        }

        session('user_auth_info', $retArr);

        return true;
    }

    public function getNav()
    {
        return $this->nav;
    }

    /**
     * 获取允许的列表,并格式化
     *
     * @param array $roleManager 用户的角色数据
     * @return array|bool
     */
    protected function getNodeLists($roleManager)
    {
        $roleIdLists = ArrayHelper::twoToOne($roleManager, 'role_id');
        $roleIdLists = implode(',', $roleIdLists);
        $sql = "SELECT n.level, n.id, n.name, n.title, n.pid FROM (SELECT DISTINCT node_id FROM __ACCESS__ WHERE role_id IN ($roleIdLists)) AS a JOIN __NODE__ AS n ON a.node_id = n.id";
        $res = M()->query($sql);

        return $res;
    }

    /**
     * 格式化权限，以及权限对于的导航列表
     *
     * @param $res
     * @return array
     */
    protected function formatAuthData($res)
    {
        $resArr = array();
        $res = FormatHelp::treeSort($res);
        $level1 = '';
        $path = '';
        //dd(FormatHelp::frontNavSort($res));
        foreach ($res as $key => $item) {
            $item['level'] += 0;

            switch ($item['level']) {
                case 1:
                    $level1 = $item['name'];
                    break;
                case 2:
                    continue;
                    break;
                case 3:
                    $path = $this->setLevelThree($level1, $item['name']);
                    $item['url'] = $path;
                    $res[$key]['url'] = $path;
                    break;
                case 4:
                    $path = $this->setLevelFour($path, $item['name']);
                    break;
                default :
                    break;
            }

            if (!empty($path)) {
                $resArr[] = $path;
            }

        }

        $resArr = array_unique($resArr);

        return array('auth' => $resArr, 'nav' => $res);

    }

    /**
     * 拼接第三层级的路由
     *
     * @param $path
     * @param $name
     * @return array|null|string
     */
    protected function setLevelThree($path, $name)
    {
        $defaultAction = C('DEFAULT_ACTION');

        if (false !== strpos($path, '/')) {
            $path = StringHelper::subPosByTag($path, '/', 'first');
        }

        $path .= '/' . $name . '/' . $defaultAction;

        return $path;
    }

    /**
     * 拼接第四层级的路由
     *
     * @param $path
     * @param $name
     * @return array|null|string
     */
    protected function setLevelFour($path, $name)
    {
        if (false !== strpos($path, '/')) {
            $path = StringHelper::subPosByTag($path, '/', 'last');
        }

        $path = $path[1] . '/' . $name;

        return $path;
    }

    protected function formatNav($res)
    {
        $default = C('DEFAULT_CONTROLLER');
        $defaultModule = $default . 'Module';

        if (!empty($res)) {
            foreach ($res as $key => $item) {
                if (0 === strcmp($item['name'], $default) || 0 === strcmp($item['name'], $defaultModule)) {
                    unset($res[$key]);
                }

                if (4 == $item['level']) {
                    unset($res[$key]);
                }
            }

            $res = FormatHelp::frontNavSort($res);
        }

        return $res;
    }


}