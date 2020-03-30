<?php

namespace app\common\model\mysql;

class SpecsValue extends BaseModel
{
    /**
     * @param $specsId
     * @param string $field
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNormalBySpecsId($specsId, $field = '*')
    {
        $where = [
            'specs_id' => $specsId,
            'status' => config('status.mysql.table_normal')
        ];
        $res = $this->where($where)
            ->field($field)
            ->select();
        return $res;
    }
}