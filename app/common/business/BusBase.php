<?php

namespace app\common\business;

class BusBase
{
    /**
     * 新增
     * @param $data
     * @return bool|mixed
     */
    public function add($data)
    {
        $data['status'] = config('status.mysql.table_normal');
        try {
            $this->model->save($data);
        } catch (\Exception $e) {
            return 0;
        }
        return $this->model->id;
    }
}