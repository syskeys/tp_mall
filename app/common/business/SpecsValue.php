<?php

namespace app\common\business;

use app\common\model\mysql\SpecsValue as SpecsValueModel;

class SpecsValue extends BusBase
{
    public $model = null;

    public function __construct()
    {
        $this->model = new SpecsValueModel();
    }

    /**
     * @param $specsId
     * @return array
     */
    public function getBySpecsId($specsId)
    {
        try {
            $result = $this->model->getNormalBySpecsId($specsId, 'id,name');
        } catch (\Exception $e) {
            return [];
        }
        $result = $result->toArray();
        return $result;
    }

    public function dealSpecsValue($skuIdSpecsValueIds)
    {
        $ids = array_values($skuIdSpecsValueIds);
        $ids = implode(',', $ids);
        $ids = array_unique(explode(',', $ids));
        $result = $this->getNormalInIds($ids);
        if (!$result) {
            return [];
        }
        $res = [];
        foreach ($skuIdSpecsValueIds as $skuId => $specs) {
            $specs = explode(',', $specs);
            $skuStr = [];
            foreach ($specs as $spec) {
                $skuStr[] = $result[$spec]['specs_name'] . ':' . $result[$spec]['name'];
            }
            $res[$skuId] = implode('  ', $skuStr);
        }
        return $res;
    }

    public function getNormalInIds($ids)
    {
        if (!$ids) {
            return [];
        }
        try {
            $result = $this->model->getNormalInIds($ids);
        } catch (\Exception $e) {
            return [];
        }
        $result = $result->toArray();
        if (!$result) {
            return [];
        }
        $specsNames = config('specs');
        $specsNamesArrs = array_column($specsNames, 'name', 'id');
        $res = [];
        foreach ($result as $resultValue) {
            $res[$resultValue['id']] = [
                'name' => $resultValue['name'],
                'specs_name' => $specsNamesArrs[$resultValue['specs_id']] ?? ''
            ];
        }
        return $res;
    }
}