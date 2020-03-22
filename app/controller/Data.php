<?php

namespace app\controller;

use app\BaseController;
use think\facade\Db;
use app\model\Demo;

class Data extends BaseController
{
    public function index()
    {
        $result = Db::table('mall_demo')
            ->order('id','desc')
            ->find();
        dump($result);
    }

    public function model1()
    {
        $result = Demo::find(1);
        dump($result->toArray());
    }
}