<?php

namespace app\common\model\mysql;

use think\Model;

class Demo extends Model
{
    public function getDemoDataByCategoryId($categoryId, $limit = 10)
    {
        if (empty($categoryId)) {
            return [];
        }
        return $this->name('demo')
            ->where('category_id', $categoryId)
            ->limit($limit)
            ->select()
            ->toArray();
    }
}