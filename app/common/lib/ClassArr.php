<?php

namespace app\common\lib;

class ClassArr
{
    public static function smsClassStat()
    {
        return [
            'ali' => "app\common\lib\sms\AliSms",
            'baidu' => "app\common\lib\sms\BaiduSms",
            'jd' => "app\common\lib\sms\JdSms"
        ];
    }

    public static function initClass($type, $classes, $params = [], $needInstance = false)
    {
        if (!array_key_exists($type, $classes)) {
            return false;
        }
        $className = $classes[$type];
        return $needInstance == true ? (new \ReflectionClass($className))->newInstanceArgs($params) : $className;
    }
}