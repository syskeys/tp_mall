<?php

namespace app\common\business;

use app\common\model\mysql\Goods as GoodsModel;
use app\common\business\GoodsSku as GoodsSkuBus;

class Goods extends BusBase
{
    public $model = null;

    public function __construct()
    {
        $this->model = new GoodsModel();
    }

    public function insertData($data)
    {
        // 开启事务
        $this->model->startTrans();
        try {
            $goodsId = $this->add($data);
            if (!$goodsId) {
                return $goodsId;
            }
            if ($data['goods_specs_type'] == 1) {
                $goodsSkuData = [
                    'goods_id' => $goodsId
                ];
                // todo
                return true;
            } elseif ($data['goods_specs_type'] == 2) {
                $goodsSkuBus = new GoodsSkuBus();
                $data['goods_id'] = $goodsId;
                $res = $goodsSkuBus->saveAll($data);
                if (!empty($res)) {
                    $stock = array_sum(array_column($res, 'stock'));
                    $goodsUpdateData = [
                        'price' => $res[0]['price'],
                        'cost_price' => $res[0]['cost_price'],
                        'stock' => $stock,
                        'sku' => $res[0]['id']
                    ];
                    $goodsRes = $this->model->updateById($goodsId, $goodsUpdateData);
                    if (!$goodsRes) {
                        throw new \think\Exception('insertData:goods主表更新失败');
                    }
                } else {
                    throw new \think\Exception('sku表新增失败');
                }
            }
            // 事务提交
            $this->model->commit();
            return true;
        } catch (\think\Exception $e) {
            // 事务回滚
            $this->model->rollback();
            return false;
        }
    }

    /**
     * @param $data
     * @param int $num
     * @return array
     */
    public function getLists($data, $num = 5)
    {
        $likeKeys = [];
        if (!empty($data)) {
            $likeKeys = array_keys($data);
        }
        try {
            $list = $this->model->getLists($likeKeys, $data, $num);
            $result = $list->toArray();
        } catch (\Exception $e) {
            $result = \app\common\lib\Arr::getPaginateDefaultData(5);
        }
        return $result;
    }

    public function getRotationChart()
    {
        $data = [
            'is_index_recommend' => 1
        ];
        $field = 'sku_id as id, title, big_image as image';
        try {
            $result = $this->model->getNormalGoodsByCondition($data, $field, 5);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        $result = $result->toArray();
        return $result;
    }

    public function categoryGoodsRecommend($categoryIds)
    {
        if (!$categoryIds) {
            return [];
        }
        $result = [];
        foreach ($categoryIds as $k => $categoryId) {
            $result[$k]['categorys'] = [];
        }
        foreach ($categoryIds as $key => $categoryId) {
            $result[$key]['goods'] = $this->getNormalGoodsFindInSetCategoryId($categoryId);
        }
        return $result;
    }

    public function getNormalGoodsFindInSetCategoryId($categoryId)
    {
        $field = 'sku_id as id, title, price, recommend_image as image';
        try {
            $result = $this->model->getNormalGoodsFindInSetCategoryId($categoryId, $field);
        } catch (\Exception $e) {
            return [];
        }
        $result = $result->toArray();
        return $result;
    }

    public function getNormalLists($data, $num = 5, $order)
    {
        try {
            $field = 'sku_id as id, title, recommend_image as image, price';
            $list = $this->model->getNormalLists($data, $num, $field, $order);
            $res = $list->toArray();
            $result = [
                'total_page_num' => isset($res['last_page']) ? $res['last_page'] : 0,
                'count' => isset($res['total']) ? $res['total'] : 0,
                'page' => isset($res['current_page']) ? $res['current_page'] : 0,
                'page_size' => $num,
                'list' => isset($res['data']) ? $res['data'] : []
            ];
        } catch (\Exception $e) {
            $result = [];
        }
        return $result;
    }

    public function getGoodsDetailBySkuId($skuId)
    {
        $skuBusObj = new GoodsSkuBus();
        $goodsSku = $skuBusObj->getNormalSkuAndGoods($skuId);
        if (!$goodsSku) {
            return [];
        }
        if (empty($goodsSku['goods'])) {
            return [];
        }
        $goods = $goodsSku['goods'];
        $skus = $skuBusObj->getSkusByGoodsId();
        if (!$skus) {
            return [];
        }
        $gids = array_column($skus, 'id', 'specs_value_ids');
        $sku = [];
        $result = [
            'title' => $goods['title'],
            'price' => $goodsSku['price'],
            'cost_price' => $goodsSku['cost_price'],
            'sales_count' => 0,
            'stock' => $goodsSku['stock'],
            'gids' => $gids,
            'image' => $goods['carousel_image'],
            'sku' => $sku,
            'detail' => [
                'd1' => [
                    '商品编码' => $goodsSku['id'],
                    '上架时间' => $goods['create_time']
                ],
                'd2' => preg_replace('/(<img.+?src=")(.*?)/', '$1' . request()->domain() . '$2', $goods['description'])
            ]
        ];
        return $result;
    }
}