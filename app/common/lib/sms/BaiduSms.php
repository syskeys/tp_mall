<?php
declare(strict_types=1);

namespace app\common\lib\sms;

class BaiduSms implements SmsBase
{
    public static function sendCode(string $phoneNumber, int $code): bool
    {
        return true;
    }
}