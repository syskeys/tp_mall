<?php

namespace app\common\lib;

class Time
{
    public static function userLoginExpireTime($type = 2)
    {
        $type = !in_array($type, [1, 2]) ? 2 : $type;
        if ($type == 1) {
            $day = 7;
        } else {
            $day = 30;
        }
        return $day * 24 * 3600;
    }
}