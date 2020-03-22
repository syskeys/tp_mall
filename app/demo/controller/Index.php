<?php

namespace app\demo\controller;

use app\BaseController;
use app\common\business\Demo;

class Index extends BaseController
{
    public function abc()
    {
        return 'demo模块下的abc方法';
    }

    public function index()
    {
        $categoryId = $this->request->param('category_id', 0, 'intval');
        if (empty($categoryId)) {
            return show(config('status.error'), '参数错误');
        }
        $demo = new Demo();
        $results = $demo->getDemoDataByCategoryId($categoryId);
        return show(config('status.success'), '请求成功', $results);
    }

    public function aa()
    {
        throw new \think\exception\HttpException(404, '找不到');
    }

    public function aaa()
    {
        dump(2);
    }
}