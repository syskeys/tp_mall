<?php

namespace app\common\lib;

class Arr
{
    /**
     * 分树类，支持无限级分类
     * @param $data
     * @return array
     */
    public static function getTree($data)
    {
        $items = [];
        foreach ($data as $v) {
            $items[$v['category_id']] = $v;
        }
        $tree = [];
        foreach ($items as $id => $item) {
            if (isset($items[$item['pid']])) {
                $items[$item['pid']]['list'][] = &$items[$id];
            } else {
                $tree[] = &$items[$id];
            }
        }
        return $tree;
    }

    public static function sliceTreeArr($data, $firstCount = 5, $secondCount = 3, $thirdCount = 5)
    {
        $data = array_slice($data, 0, $firstCount);
        foreach ($data as $k => $v) {
            if (!empty($v['list'])) {
                $data[$k]['list'] = array_slice($v['list'], 0, $secondCount);
                foreach ($data[$k]['list'] as $key => $value) {
                    if (!empty($value['list'])) {
                        $data[$k]['list'][$key]['list'] = array_slice($value['list'], 0, $thirdCount);
                    }
                }
            }
        }
        return $data;
    }

    /**
     * 分页默认返回值
     * @param $num
     * @return array
     */
    public static function getPaginateDefaultData($num)
    {
        return [
            'total' => 0,
            'per_page' => $num,
            'current_page' => 1,
            'last_page' => 0,
            'data' => []
        ];
    }

    public static function ArrsSortByKey($result, $key, $sort = SORT_DESC)
    {
        if (!is_array($result) || !$key) {
            return [];
        }
        array_multisort(array_column($result, $key), $sort, $result);
        return $result;
    }
}