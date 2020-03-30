<?php

namespace app\admin\controller;

use think\facade\View;
use app\common\business\Category as CategoryBus;
use app\common\lib\Status as StatusLib;

class Category extends AdminBase
{
    public function index()
    {
        $pid = input('param.pid', 0, 'intval');
        $data = [
            'pid' => $pid
        ];
        try {
            $categories = (new CategoryBus())->getLists($data, 5);
        } catch (\Exception $e) {
            $categories = \app\common\lib\Arr::getPaginateDefaultData(5);
        }
        // return json($categories);exit;
        return View::fetch('', [
            'categories' => $categories,
            'pid' => $pid
        ]);
    }

    public function add()
    {
        try {
            $categories = (new CategoryBus())->getNormalCategories();
        } catch (\Exception $e) {
            $categories = [];
        }

        return View::fetch('', [
            'categories' => json_encode($categories)
        ]);
    }

    public function save()
    {
        $pid = input('param.pid', 0, 'intval');
        $name = input('param.name', '', 'trim');
        $data = [
            'pid' => $pid,
            'name' => $name
        ];
        $validate = new \app\admin\validate\Category();
        if (!$validate->check($data)) {
            return show(config('status.error'), $validate->getError());
        }
        try {
            $result = (new CategoryBus())->add($data);
        } catch (\Exception $e) {
            return show(config('status.error'), $e->getMessage());
        }
        if ($result) {
            return show(config('status.success'), 'ok');
        }
        return show(config('status.error'), '新增分类失败');
    }

    /**
     * 排序
     * @return \think\response\Json
     */
    public function listorder()
    {
        $id = input('param.id', 0, 'intval');
        $listorder = input('param.listorder', 'intval');
        if (!$id) {
            return show(config('status.error'), '参数错误');
        }
        try {
            $res = (new CategoryBus())->listorder($id, $listorder);
        } catch (\Exception $e) {
            return show(config('status.error'), $e->getMessage());
        }
        if ($res) {
            return show(config('status.success'), '排序成功');
        } else {
            return show(config('status.error'), '排序失败');
        }
    }

    public function status()
    {
        $status = input('param.status', 0, 'intval');
        $id = input('param.id', 0, 'intval');
        if (!$id || !in_array($status, StatusLib::getTableStatus())) {
            return show(config('status.error'), '参数错误');
        }
        try {
            $res = (new CategoryBus())->status($id, $status);
        } catch (\Exception $e) {
            return show(config('status.error'), $e->getMessage());
        }
        if ($res) {
            return show(config('status.success'), '状态修改成功');
        } else {
            return show(config('status.error'), '状态修改失败');
        }
    }

    public function dialog()
    {
        $categories = (new CategoryBus())->getNormalByPid();
        return view('', [
            'categories' => json_encode($categories)
        ]);
    }

    public function getByPid()
    {
        $pid = input('param.pid', 0, 'intval');
        $categories = (new CategoryBus())->getNormalByPid($pid);
        return show(config('status.success'), 'ok', $categories);
    }
}