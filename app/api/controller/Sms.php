<?php
declare(strict_types=1);

namespace app\api\controller;

use app\BaseController;
use app\common\business\Sms as SmsBus;

class Sms extends BaseController
{
    public function code(): object
    {
        $phoneNumber = input('param.phone_number', '', 'trim');
        $data = [
            'phone_number' => $phoneNumber
        ];
        try {
            validate(\app\api\validate\User::class)->scene('send_code')->check($data);
        } catch (\think\exception\ValidateException $e) {
            return show(config('status.error'), $e->getError());
        }
        if (SmsBus::sendCode($phoneNumber, 6, 'ali')) {
            return show(config('status.success'), '验证码发送成功');
        }
        return show(config('status.error'), '验证码发送失败');
    }
}