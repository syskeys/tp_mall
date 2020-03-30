<?php

namespace app\common\lib;

class Show
{
    /**
     * success
     * @param array $data
     * @param string $message
     * @return \think\response\Json
     */
    public static function success($data = [], $message = 'OK')
    {
        $result = [
            'status' => config('status.success'),
            'message' => $message,
            'result' => $data
        ];
        return json($result);
    }

    /**
     * error
     * @param array $data
     * @param string $message
     * @param int $status
     * @return \think\response\Json
     */
    public static function error($data = [], $status = 0, $message = 'error')
    {
        $result = [
            'status' => $status,
            'message' => $message,
            'result' => $data
        ];
        return json($result);
    }
}