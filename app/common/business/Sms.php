<?php
declare(strict_types=1);

namespace app\common\business;

use app\common\lib\sms\AliSms;
use app\common\lib\Num;
use app\common\lib\ClassArr;

class Sms
{
    public static function sendCode(string $phoneNumber, int $len, string $type = 'ali'): bool
    {
        // 生成6位验证码
        $code = Num::getCode($len);
        // $sms = AliSms::sendCode($phoneNumber, $code);
        // 使用工厂模式
        // $type = ucfirst($type);
        // $class = "app\common\lib\sms\\" . $type . "Sms";
        // $sms = $class::sendCode($phoneNumber, $code);
        $classStats = ClassArr::smsClassStat();
        $classObj = ClassArr::initClass($type, $classStats);
        $sms = $classObj::sendCode($phoneNumber, $code);
        $sms = true; //模拟短信发送成功
        if ($sms) {
            // 短信验证码记录到Redis里面
            cache(config('redis.code_pre') . $phoneNumber, $code, config('redis.code_expire'));
        }
        return $sms;
    }
}