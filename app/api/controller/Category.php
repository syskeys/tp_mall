<?php

namespace app\api\controller;

use app\common\business\Category as CategoryBus;
use app\common\lib\Arr as ArrLib;
use app\common\lib\Show;

class Category extends ApiBase
{
    public function index()
    {
        try {
            $categoryBus = new CategoryBus();
            $categories = $categoryBus->getFrontCategories();
        } catch (\Exception $e) {
            return show(config('status.success'), '内部异常');
        }
        if (!$categories) {
            return show(config('status.success'), '数据为空');
        }
        $result = ArrLib::getTree($categories);
        $result = ArrLib::sliceTreeArr($result);
        return show(config('status.success'), 'ok', $result);
    }

    /**
     * @return \think\response\Json
     */
    public function search()
    {
        $result = [
            'name' => '我是一级分类',
            'focus_ids' => [1, 11],
            'list' => [
                [
                    ['id' => 1, 'name' => '二级分类1'],
                    ['id' => 2, 'name' => '二级分类2'],
                    ['id' => 3, 'name' => '二级分类3'],
                    ['id' => 4, 'name' => '二级分类4'],
                    ['id' => 5, 'name' => '二级分类5']
                ],
                [
                    ['id' => 11, 'name' => '二级分类1'],
                    ['id' => 12, 'name' => '二级分类2'],
                    ['id' => 13, 'name' => '二级分类3'],
                    ['id' => 14, 'name' => '二级分类4'],
                    ['id' => 15, 'name' => '二级分类5']
                ]
            ]
        ];
        return Show::success($result);
    }

    /**
     * 获取子分类
     * @return \think\response\Json
     */
    public function sub()
    {
        $result = [
            ['id' => 21, 'name' => '点二到三分类1'],
            ['id' => 22, 'name' => '点二到三分类2'],
            ['id' => 23, 'name' => '点二到三分类3'],
            ['id' => 24, 'name' => '点二到三分类4'],
            ['id' => 25, 'name' => '点二到三分类5']
        ];
        return Show::success($result);
    }
}