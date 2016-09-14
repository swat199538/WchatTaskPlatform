<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2016/7/26
 * Time: 17:42
 */

namespace Components\Helpers;


class StringHelper
{
    /**
     * 截取字符串的前n位或后n位。
     * 参数是正整数，表示截取前n位，负整数表示截取后n位
     *
     * @param $str
     * @param $num
     * @return mixed
     */
    public static function subPos($str, $num)
    {
        $strLen = strlen($str);
        $absNum = abs($num);

        if (0 == $strLen || $absNum >= $strLen || 0 == $absNum) {
            return $str;
        }

        if ($num > 0) {
            $str =  substr($str, 0, $num); // 截前面的字符
        } else {
            $str =  substr($str, $num); // 截后面的字符
        }

        return $str;
    }

    /**
     * 根据指定字符截取
     * type = first 返回按字符分割后的第一个元素。
     * type = last 返回按字符分割后的最后一个元素。
     * type = null 返回分割后的所有元素。
     *
     * @param string $str 处理的字符串
     * @param string $tag 分隔符
     * @param null $type
     * @return array|null|string
     */
    public static function subPosByTag($str, $tag, $type = null)
    {
        $arr = explode($tag, $str);
        $cnt = count($arr);
        $val = null;

        if (0 == strcmp($type, 'last')) {
            $val[] = $arr[$cnt - 1];
            unset($arr[$cnt - 1]);
            $val[] = implode($tag, $arr);
        } else if (0 == strcmp($type, 'first')) {
            $val = $arr[0];
        } else {
            $val = $arr;
        }

        return $val;
    }
}