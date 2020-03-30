<?php

namespace app\common\model\mysql;

use think\Model;

class BaseModel extends Model
{
    protected $autoWriteTimestamp = true;

    public function updateById($id, $data)
    {
        $data['update_time'] = time();
        return $this->where(['id' => $id])->save($data);
    }

    public function getNormalInIds($ids)
    {
        return $this->whereIn('id', $ids)
            ->where('status', '=', config('status.mysql.table_normal'))
            ->select();
    }

    public function getByCondition($condition = [], $order = ['id' => 'desc'])
    {
        if (!$condition || !is_array($condition)) {
            return false;
        }
        $result = $this->where($condition)
            ->order($order)
            ->select();
        return $result;
    }
}