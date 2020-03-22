<?php

namespace app\common\business;

use app\common\model\mysql\Demo as DemoModel;

class Demo
{
    /**
     * 通过getDemoDataByCategoryId获取数据
     * @param $categoryId
     * @param int $limit
     * @return array
     */
    public function getDemoDataByCategoryId($categoryId, $limit = 10)
    {
        $model = new DemoModel();
        $results = $model->getDemoDataByCategoryId($categoryId, $limit);
        if (empty($results)) {
            return [];
        }
        $categories = config('category');
        foreach ($results as $key => $result) {
            $results[$key]['categoryName'] = $categories[$result['category_id']] ?? '其他';
        }
        return $results;
    }
}