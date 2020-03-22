<?php

namespace app\common\lib;

class Str
{
    /**
     * 生成token
     * @param $string
     * @return string
     */
    public static function getLoginToken($string)
    {
        //生成一个不会重复的字符串
        $str = md5(uniqid(md5(microtime(true)), true));
        // 返回加密值
        return sha1($str . $string);
    }
}