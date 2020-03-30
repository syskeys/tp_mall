<?php

namespace app\common\model\mysql;

class Category extends BaseModel
{
    /**
     * 获取正常的分类信息
     * @param string $field
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNormalCategories($field = '*')
    {
        $where = [
            'status' => config('status.mysql.table_normal')
        ];
        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        return $this->where($where)
            ->field($field)
            ->order($order)
            ->select();
    }

    public function getLists($where, $num = 10)
    {
        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        $result = $this->where('status', '<>', config('status.mysql.table_delete'))
            ->where($where)
            ->order($order)
            ->paginate($num);
        return $result;
    }

    public function getChildCountInPids($condition)
    {
        $where[] = ['pid', 'in', $condition['pid']];
        $where[] = ['status', '<>', config('status.mysql.table_delete')];
        $res = $this->where($where)
            ->field(['pid', 'count(*) as count'])
            ->group('pid')
            ->select();
        return $res;
    }

    public function getNormalByPid($pid = 0, $field)
    {
        $where = [
            'pid' => $pid,
            'status' => config('status.mysql.table_normal')
        ];
        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        $res = $this->where($where)
            ->field($field)
            ->order($order)
            ->select();
        return $res;
    }
}