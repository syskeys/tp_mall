<?php

namespace app\api\controller\mall;

use app\api\controller\ApiBase;
use app\common\lib\Show;
use app\common\business\Goods as GoodsBus;

class Detail extends ApiBase
{
    public function index()
    {
        $id = input('param.id', 0, 'intval');
        if (!$id) {
            return Show::error();
        }
        $result = (new GoodsBus())->getGoodsDetailBySkuId($id);
        if (!$result) {
            return Show::error();
        }
        return Show::success($result);
    }
}