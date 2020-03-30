<?php

namespace app\common\model\mysql;

class Goods extends BaseModel
{
    /**
     * @param $query
     * @param $value
     */
    public function searchTitleAttr($query, $value)
    {
        $query->where('title', 'like', '%' . $value . '%');
    }

    public function searchCreateTimeAttr($query, $value)
    {
        $query->whereBetweenTime('create_time', $value[0], $value[1]);
    }

    /**
     * 获取后端列表数据
     * @param $data
     * @param int $num
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getLists($likeKeys, $data, $num = 10)
    {
        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        if (!empty($likeKeys)) {
            // 搜索
            $res = $this->withSearch($likeKeys, $data);
        } else {
            $res = $this;
        }
        $list = $res->whereIn('status', [0, 1])
            ->order($order)
            ->paginate($num);
        return $list;
    }

    public function getNormalGoodsByCondition($where, $field = '*', $limit = 5)
    {
        $order = [
            // 'listorder' => 'desc',
            'id' => 'desc'
        ];
        $where['status'] = config('status.success');
        return $this->where($where)
            ->field($field)
            ->order($order)
            ->limit($limit)
            ->select();
    }

    public function getImageAttr($value)
    {
        if (strpos($value, 'http') < 0) {
            return request()->domain() . $value;
        }
        return $value;
    }

    public function getCarouselImageAttr($value)
    {
        if (!empty($value)) {
            $value = explode(',', $value);
            $value = array_map(function ($v) {
                return request()->domain() . $v;
            }, $value);
        }
        return $value;
    }

    public function getNormalGoodsFindInSetCategoryId($categoryId, $field = '*', $limit = 10)
    {
        $order = [
            'id' => 'desc'
        ];
        return $this->whereFindInSet('category_path_id', $categoryId)
            ->where('status', '=', config('status.success'))
            ->field($field)
            ->order($order)
            ->limit($limit)
            ->select();
    }

    public function getNormalLists($data, $num = 10, $field = '*', $order)
    {
        $res = $this;
        if (isset($data['category_path_id'])) {
            $res = $this->whereFindInSet('category_path_id', $data['category_path_id']);
        }
        return $res->where('status', '=', config('status.success'))
            ->field($field)
            ->order($order)
            ->paginate($num);
    }
}