<?php
namespace Components\Helpers;
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2016/7/6
 * Time: 12:37
 */
class ArrayHelper
{
    /**
     * 根据指定的索引，把二维数组转换成一维数组
     *
     * @param array $arr 处理的数组
     * @param string $tag 索引名
     * @return array|bool
     */
    public static function twoToOne($arr, $tag)
    {
        if (is_array($arr) && !empty($arr)) {
            $tmpArr = array();

            foreach ($arr as $key => $item) {
                $tmpArr[] = $item[$tag];
            }

            return $tmpArr;
        }

        return false;

    }

    /**
     * 把一个二维数组里面，重复的字段值过滤掉并在指定的索引上追加成数组
     * @param $arr 要处理的数组
     * @param $index 用来判断的索引
     * @return array 要追加值的索引
     */
    static function filterRepeatTwoArry($arr,$Decide,$index)
    {
        $count=count($arr);
        for ($i=0;$i<$count;$i++) {
            if (array_key_exists($i,$arr)) {
                $is_start=$i+1;
                for ($j=$i+1;$j<$count;$j++) {
                    if ($arr[$i][$Decide]==$arr[$j][$Decide]) {
                        if ($is_start==$j) {
                            $arr[$i][$index]=array('0'=>$arr[$i][$index],'1'=>$arr[$j][$index]);
                            unset($arr[$j]);
                        } else {
                            $numbers=count($arr[$i][$index]);
                            $arr[$i][$index][$numbers]=$arr[$j][$index];
                            unset($arr[$j]);
                        }
                    }
                }
            }
        }
        return $arr;
    }


}