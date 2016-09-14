<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 16/1/15
 * Time: 下午10:10
 */
namespace Components\Helpers;

class FormatHelp {
    const CATEGORY_ID = 'id';
    const CATEGORY_PID = 'pid';
    public static $treeLists = array();

    /**
     * 无限极分类
     * @param array $lists 所有分类列表
     * @param int $pid 分类的父id
     * @param int $level 分类的层级
     * @return array
     */
    public static function treeSort(array $lists, $pid=0, $level=1) {

        foreach ($lists as $item) {

            if ($item[self::CATEGORY_PID] == $pid) {
                $item['level'] = $level;
                self::$treeLists[] = $item;
                self::treeSort($lists, $item[self::CATEGORY_ID], $level+1);
            }
        }

        return self::$treeLists;
    }

    /**
     * 获取一个分类的所有子孙类别id，并且可以包括它自己
     * @param array $lists 所有分类数组
     * @param $id int 指定分类的id
     * @return array
     */
    public static function getSubById(array $lists, $id, $self = false) {
        $arr = array();
        $subLists = self::treeSort($lists, $id);
        self::$treeLists = array();

        foreach ($subLists as $item) {
            $arr[] = $item[self::CATEGORY_ID];
        }

        if (false !== $self) {
            $arr[] = $id;
        }

        return $arr;
    }


    /**
     * 根据前台显示要求，格式化数组。方便在前台导航中使用。
     * 左侧栏分类导航树
     * @param $arr
     * @param int $pid
     * @return array
     *
     * 原始数组：
     * array(
            array('cid'=>1, 'parent_id'=>0, 'cname'=>'服饰'),
            array('cid'=>2, 'parent_id'=>0, 'cname'=>'手机'),
            array('cid'=>3, 'parent_id'=>1, 'cname'=>'男装'),
            array('cid'=>4, 'parent_id'=>2, 'cname'=>'智能手机'),
            array('cid'=>5, 'parent_id'=>1, 'cname'=>'女装'),
            array('cid'=>6, 'parent_id'=>3, 'cname'=>'保安服'),
            array('cid'=>7, 'parent_id'=>4, 'cname'=>'苹果手机'),
            array('cid'=>8, 'parent_id'=>5, 'cname'=>'连衣裙'),
            array('cid'=>9, 'parent_id'=>3, 'cname'=>'男士衬衣')
        );
     * 格式化后：
     * Array (
            [0] => Array (
                [cid] => 1
                [parent_id] => 0
                [cname] => 服饰
                [child] => Array (
                        [0] => Array (
                            [cid] => 3
                            [parent_id] => 1
                            [cname] => 男装
                            [child] => Array (
                                    [0] => Array (
                                        [cid] => 6
                                        [parent_id] => 3
                                        [cname] => 男保安服
                                        [child] => Array ()

                                    )

                                    [1] => Array(
                                        [cid] => 9
                                        [parent_id] => 3
                                        [cname] => 男士衬衣
                                        [child] => Array ()

                                    )

            )......

        前台结构:
         <li>
     *      <h3>服饰(顶级导航)</h3>
     *      <div>
     *          <dt>男装(一级导航)</dt>
     *          <dd>男保安服(二级导航)</dd>
     *          <dd>男士衬衣(二级导航)</dd>
     *      </div>
         </li>
     */
    public static function frontNavSort(array $arr, $pid=0) {
        $lists  = array();

        foreach ($arr as $item) {
            if ($item[self::CATEGORY_PID] == $pid) {
                $child = self::frontNavSort($arr, $item[self::CATEGORY_ID]);
                $item['child'] = $child;
                $lists[] = $item;
            }
        }

        return $lists;
    }

} 