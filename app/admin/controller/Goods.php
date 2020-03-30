<?php

namespace app\admin\controller;

use app\common\business\Goods as GoodsBus;

class Goods extends AdminBase
{
    public function index()
    {
        $data = [];
        $title = input('param.title', '', 'trim');
        $time = input('param.time', '', 'trim');
        if (!empty($title)) {
            $data['title'] = $title;
        }
        if (!empty($time)) {
            $data['create_time'] = explode('-', $time);
        }
        $goods = (new GoodsBus())->getLists($data, 5);
        return view('', [
            'goods' => $goods
        ]);
    }

    public function add()
    {
        return view();
    }

    public function save()
    {
        if (!$this->request->isPost()) {
            return show(config('status.error'), '参数不合法');
        }
        $data = input('param.');
        $check = $this->request->checkToken('__token__');
        if (!$check) {
            return show(config('status.error'), '非法请求');
        }
        $data['category_path_id'] = $data['category_id'];
        $result = explode(',', $data['category_path_id']);
        $data['category_id'] = end($result);
        $res = (new GoodsBus())->insertData($data);
        if (!$res) {
            return show(config('status.error'), '商品新增失败');
        }
    }
}