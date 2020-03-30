<?php

namespace app\common\business;

use app\common\model\mysql\OrderGoods as OrderGoodsModel;

class OrderGoods extends BusBase
{
    public $model = null;

    public function __construct()
    {
        $this->model = new OrderGoodsModel();
    }

    public function getByOrderId($orderId)
    {
        $condition = [
            'order_id' => $orderId
        ];
        try {
            $orders = $this->model->getByCondition($condition);
        } catch (\Exception $e) {
            $orders = [];
        }
        if (!$orders) {
            return [];
        }
        $orders = $orders->toArray();
        return $orders;
    }
}