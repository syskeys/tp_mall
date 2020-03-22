<?php

namespace app\controller;

use app\BaseController;

class Demo extends BaseController
{
    public function __call($name, $arguments)
    {
        return show(config('status.action_not_found'), "找不到{$name}方法", null, 404);
    }

    public function show()
    {
        $result = [
            'status' => 200,
            'result' => 1,
            'message' => 'success'
        ];
        return json($result);
    }

    public function request()
    {
        dump($this->request->param());
    }
}