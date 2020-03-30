<?php

namespace app\common\business;

use app\common\lib\Show;
use app\common\lib\Snowflake;
use app\common\model\mysql\Order as OrderModel;
use app\common\model\mysql\OrderGoods as OrderGoodsModel;

class Order extends BusBase
{
    public $model = null;

    public function __construct()
    {
        $this->model = new OrderModel();
    }

    public function save($data)
    {
        // 获取订单号
        $workId = mt_rand(1, 1023);
        $orderId = Snowflake::getInstance()->setWorkId($workId)->id();
        //获取购物车数据
        $cartObj = new Cart();
        $result = $cartObj->lists($data['user_id'], $data['ids']);
        if (!$result) {
            return false;
        }
        $newResult = array_map(function ($v) use ($orderId) {
            $v['sku_id'] = $v['id'];
            unset($v['id']);
            $v['order_id'] = $orderId;
            return $v;
        }, $result);
        $price = array_sum(array_column($result, 'total_price'));
        $orderData = [
            'user_id' => $data['user_id'],
            'order_id' => $orderId,
            'total_price' => $price,
            'address_id' => $data['address_id']
        ];
        $this->model->startTrans();
        try {
            //新增order
            $id = $this->add($orderData);
            if (!$id) {
                return 0;
            }
            //新增order_goods
            $orderGoodsResult = (new OrderGoodsModel())->saveAll($newResult);
            if (!$orderGoodsResult) {
                return 0;
            }
            //goods_sku更新
            $skuRes = (new GoodsSku())->updateStock($data);
            //goods更新
            //删除购物车
            $this->model->commit();
            return true;
        } catch (\Exception $e) {
            $this->model->rollback();
            return false;
        }
    }

    public function detail($data)
    {
        $condition = [
            'user_id' => $data['user_id'],
            'order_id' => $data['order_id']
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
        $orders = !empty($orders) ? $orders[0] : [];
        if (empty($orders)) {
            return [];
        }
        $orders['id'] = $orders['order_id'];
        $orders['consignee_info'] = '浙江省 杭州市 西湖区 xxx';
        // $orders['mall_title'] = '111';
        // $orders['price'] = $orders['total_price'];
        $orderGoods = (new OrderGoods())->getByOrderId($data['order_id']);
        $orders['malls'] = $orderGoods;
        return $orders;
    }
}