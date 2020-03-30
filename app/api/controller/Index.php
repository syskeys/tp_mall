<?php

namespace app\api\controller;

use app\common\business\Goods as GoodsBus;
use app\common\lib\Show;

class Index extends ApiBase
{
    /**
     * 首页轮播图
     * @return \think\response\Json
     */
    public function getRotationChart()
    {
        $result = (new GoodsBus())->getRotationChart();
        return Show::success($result);
    }

    public function categoryGoodsRecommend()
    {
        $categoryIds = [
            71,
            51
        ];
        $result = (new GoodsBus())->categoryGoodsRecommend($categoryIds);
        return Show::success($result);
    }
}