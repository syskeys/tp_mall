<?php

namespace app\common\lib;

class Key
{
    /**
     * 记录用户购物车的Redis key
     * @param $userId
     * @return mixed
     */
    public static function userCart($userId)
    {
        return config('redis.cart_pre' . $userId);
    }
}