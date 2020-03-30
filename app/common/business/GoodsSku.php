<?php

namespace app\common\business;

use app\common\model\mysql\GoodsSku as GoodsSkuModel;

class GoodsSku extends BusBase
{
    public $model = null;

    public function __construct()
    {
        $this->model = new GoodsSkuModel();
    }

    public function saveAll($data)
    {
        if (!$data['skus']) {
            return false;
        }
        foreach ($data['skus'] as $value) {
            $insertData[] = [
                'goods_id' => $data['goods_id'],
                'specs_value_ids' => $value['propvalnames'],
                'price' => $value['propvalnames']['skuSellPrice'],
                'cost_price' => $value['propvalnames']['skuMarketPrice'],
                'stock' => $value['propvalnames']['skuStock']
            ];
        }
        try {
            $result = $this->model->saveAll($insertData);
            return $result->toArray();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getNormalSkuAndGoods($id)
    {
        try {
            $result = $this->model->with('goods')->find($id);
        } catch (\Exception $e) {
            return [];
        }
        if (!$result) {
            return [];
        }
        $result = $result->toArray();
        if ($result['status'] != config('status.mysql.table_normal')) {
            return [];
        }
        return $result;
    }

    public function getSkusByGoodsId($goodsIs = 0)
    {
        if (!$goodsIs) {
            return [];
        }
        try {
            $skus = $this->model->getNormalByGoodsId($goodsIs);
        } catch (\Exception $e) {
            return [];
        }
        return $skus->toArray();
    }

    public function getNormalInIds($ids)
    {
        try {
            $result = $this->model->getNormalInIds($ids);
        } catch (\Exception $e) {
            return [];
        }
        return $result->toArray();
    }

    public function updateStock($data)
    {
        foreach ($data as $value) {
            $this->model->incStock($value['id'], $value['num']);
        }
        return true;
    }
}