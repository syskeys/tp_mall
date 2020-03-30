<?php

namespace app\common\business;

use think\facade\Cache;
use app\common\lib\Key;
use app\common\lib\Arr;

class Cart extends BusBase
{
    public function insertRedis($userId, $id, $num)
    {
        $goodsSku = (new GoodsSku())->getNormalSkuAndGoods($id);
        if (!$goodsSku) {
            return FALSE;
        }
        $data = [
            'title' => $goodsSku['goods']['title'],
            'image' => $goodsSku['goods']['recommend_image'],
            'num' => $num,
            'goods_id' => $goodsSku['goods']['id'],
            'create_time' => time()
        ];
        try {
            $get = Cache::hGet(Key::userCart($userId), $id);
            if ($get) {
                $get = json_decode($get, true);
                $data['num'] = $data['num'] + $get['num'];
            }
            $res = Cache::hSet(Key::userCart($userId), $id, json_encode($data));
        } catch (\Exception $e) {
            return FALSE;
        }
        return $res;
    }

    public function lists($userId, $ids)
    {
        try {
            if ($ids) {
                $ids = explode(',', $ids);
                $res = Cache::hMget(Key::userCart($userId), $ids);
                if (in_array(false, array_values($res))) {
                    return [];
                }
            } else {
                $res = Cache::hGetAll(Key::userCart($userId));
            }
        } catch (\Exception $e) {
            $res = [];
        }
        if (!$res) {
            return [];
        }
        $result = [];
        $skusIds = array_keys($res);
        $skus = (new GoodsSku())->getNormalInIds($skusIds);
        $skuIdPrice = array_column($skus, 'price', 'id');
        $stocks = array_column($skus, 'stock', 'id');
        $skuIdSpecsValueIds = array_column($skus, 'specs_value_ids', 'id');
        $specsValues = (new SpecsValue())->dealSpecsValue($skuIdSpecsValueIds);
        foreach ($res as $k => $v) {
            $price = $skuIdPrice[$k] ?? 0;
            $v = json_decode($v, true);
            if ($ids && isset($stocks[$k]) && $stocks[$k] < $v['num']) {
                throw new \think\Exception($v['title'] . '的商品库存不足');
            }
            $v['id'] = $k;
            $v['image'] = preg_match("/http:\/\//", $v['image'] ? $v['image'] : request()->domain() . $v['image']);
            $v['price'] = $price;
            $v['total_price'] = $price * $v['num'];
            $v['sku'] = $specsValues[$k] ?? '暂无规则';
            $result[] = $v;
        }
        if (!empty($result)) {
            $result = Arr::ArrsSortByKey($result, 'create_time');
        }
        return $result;
    }

    public function deleteRedis($userId, $id)
    {
        try {
            $res = Cache::hDel(Key::userCart($userId), $id);
        } catch (\Exception $e) {
            return FALSE;
        }
        return $res;
    }

    public function updateRedis($userId, $id, $num)
    {
        try {
            $get = Cache::hGet(Key::userCart($userId), $id);
        } catch (\Exception $e) {
            return FALSE;
        }
        if ($get) {
            $get = json_decode($get, true);
            $get['num'] = $num;
        } else {
            throw new \think\Exception('不存在该购物车的商品');
        }
        try {
            $res = Cache::hSet(Key::userCart($userId), $id, json_encode($get));
        } catch (\Exception $e) {
            return FALSE;
        }
        return $res;
    }

    public function getCount($userId)
    {
        try {
            $count = Cache::hLen(Key::userCart($userId));
        } catch (\Exception $e) {
            return 0;
        }
        return intval($count);
    }
}