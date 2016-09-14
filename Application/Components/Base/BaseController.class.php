<?php

/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2016/7/6
 * Time: 14:53
 */
namespace Components\Base;

use Model\ManagerModel;
use Think\Controller;
use Think\Page;

class BaseController extends Controller
{
    protected $assignData = array();
    protected $manager;

    public function __construct()
    {
        parent::__construct();
        $commonData = array();
        $commonData['cname'] = CONTROLLER_NAME;
        $commonData['aname'] = ACTION_NAME;
        $this->assign('currentData', $commonData);
        $this->manager = new ManagerModel();
    }

    public function _empty()
    {
        echo '404 not find !'; exit;
    }

    protected $pageStyle = array();

    protected function setPage($model,$type=0,$pageNum, $rollPage = 5, $lastSuffix = true)
    {
        if ($type==0) {
            $allCnt = $model->count();

            if (empty($allCnt)) {
                return false;
            }
        } else {
            //这个过滤条数计算的方法请自己在类里实现
            $allCnt=$model->filterCount();
            //dd($allCnt);
            if (empty($allCnt)) {
                $pageStr="没有查询到相关数据！";
                $this->assign('pageStr', $pageStr);
                return true;
            }
            
        }

        $pageNum = C('ADMIN_PAGE_NUM');
        $pageModel = new Page($allCnt, $pageNum);
        $pageModel->lastSuffix = $lastSuffix;
        $pageModel->rollPage = $rollPage;
        if (!$this->pageStyle['setting']) {
            $this->setPageStyle();
        }

        $pageModel->setConfig('prev', $this->pageStyle['prev']);
        $pageModel->setConfig('next', $this->pageStyle['next']);
        $pageModel->setConfig('first', $this->pageStyle['first']);
        $pageModel->setConfig('last', $this->pageStyle['last']);
        $pageModel->setConfig('theme', $this->pageStyle['theme']);
        $startNo = $pageModel->firstRow;
        $pageSize = $pageModel->listRows;

        
        $data = $model->getAllByPage($startNo, $pageSize);
        //dd($data);
        $pageStr = $pageModel->show();
        $this->assign('no', $startNo);
        $this->assign('data', $data);
        $this->assign('pageStr', $pageStr);

        return true;
    }

    protected function setPageStyle($setting = false, $prev = '【上一页】', $next = '【下一页】', $first = '【首页】', $last = '【末页】', $theme = '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% 共%TOTAL_ROW% 条记录,当前是 %NOW_PAGE% / %TOTAL_PAGE% ')
    {
        $this->pageStyle['prev'] = $prev;
        $this->pageStyle['next'] = $next;
        $this->pageStyle['first'] = $first;
        $this->pageStyle['last'] = $last;
        $this->pageStyle['theme'] = $theme;
        $this->pageStyle['setting'] = $setting;
    }

    protected function setLoggedInfo($data)
    {
        if (!empty($data['password'])) {
            unset($data['password']);
        }

        session('user', $data);
        $id = $data['id'];
        $data = array();
        $data['last_logged_at'] = time();
        $data['last_logged_ip'] = get_client_ip();
        $data['id'] = $id;

        return $this->manager->edit($data);
    }

    /**设置渠道商session
     * @param $data
     * @return bool
     */
    protected function setChannelLogined($data)
    {
        if(!empty($data['password'])){
            unset($data['password']);
        }

        session('partner',$data);

        return true;
    }
    
    /**设置投手的session
     * @param $data
     * @return bool
     */
    protected function setMemberlLogined($data)
    {
        session('member',$data);
        return true;
    }

}