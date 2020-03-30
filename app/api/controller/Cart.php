<?php

namespace app\api\controller;

use app\common\lib\Show;
use app\common\business\Cart as CartBus;

class Cart extends AuthBase
{
    public function add()
    {
        if (!$this->request->isPost()) {
            return Show::error();
        }
        $id = input('param.id', 0, 'intval');
        $num = input('param.num', 0, 'intval');
        if (!$id || !$num) {
            return Show::error('参数不合法');
        }
        $res = (new CartBus())->insertRedis($this->userId, $id, $num);
        if ($res === FALSE) {
            return Show::error();
        }
        return Show::success();
    }

    public function lists()
    {
        $ids = input('param.ids', '', 'trim');
        $res = (new CartBus())->lists($this->userId, $ids);
        if ($res === FALSE) {
            return Show::error();
        }
        return Show::success($res);
    }

    public function delete()
    {
        if (!$this->request->isPost()) {
            return Show::error();
        }
        $id = input('param.id', 0, 'intval');
        if (!$id) {
            return Show::error('参数不合法');
        }
        $res = (new CartBus())->deleteRedis($this->userId, $id);
        if ($res === FALSE) {
            return Show::error();
        }
        return Show::success();
    }

    public function update()
    {
        if (!$this->request->isPost()) {
            return Show::error();
        }
        $id = input('param.id', 0, 'intval');
        $num = input('param.num', 0, 'intval');
        if (!$id || !$num) {
            return Show::error('参数不合法');
        }
        try {
            $res = (new CartBus())->updateRedis($this->userId, $id, $num);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }
        if ($res === FALSE) {
            return Show::error();
        }
        return Show::success($res);
    }
}