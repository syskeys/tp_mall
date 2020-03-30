<?php

namespace app\common\business;

use app\common\model\mysql\Category as CategoryModel;

class Category
{
    public $model = null;

    public function __construct()
    {
        $this->model = new CategoryModel();
    }

    public function add($data)
    {
        $data['status'] = config('status.mysql.table_normal');
        try {
            $this->model->save($data);
        } catch (\Exception $e) {
            throw new \think\Exception('服务内部异常');
        }
        return $this->model->id;
    }

    public function getNormalCategories()
    {
        $field = 'id, name, pid';
        $categories = $this->model->getNormalCategories($field);
        if (!$categories) {
            $categories = [];
        }
        $categories = $categories->toArray();
        return $categories;
    }

    public function getFrontCategories()
    {
        $field = 'id as category_id, name, pid';
        $categories = $this->model->getNormalCategories($field);
        if (!$categories) {
            $categories = [];
        }
        $categories = $categories->toArray();
        return $categories;
    }

    public function getLists($data, $num)
    {
        $list = $this->model->getLists($data, $num);
        if (!$list) {
            return [];
        }
        $result = $list->toArray();
        $result['render'] = $list->render();

        $pids = array_column($result['data'], 'id');
        if ($pids) {
            $idCountResult = $this->model->getChildCountInPids(['pid' => $pids]);
            $idCountResult = $idCountResult->toArray();
            $idCounts = [];

            foreach ($idCountResult as $countResult) {
                $idCounts[$countResult['pid']] = $countResult['count'];
            }
        }
        if ($result['data']) {
            foreach ($result['data'] as $k => $value) {
                $result['data'][$k]['childCount'] = $idCounts[$value['id']] ?? 0;
            }
        }

        return $result;
    }

    /**
     * 根据id获取某条记录
     * @param $id
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getById($id)
    {
        $result = $this->model->find($id);
        if (empty($result)) {
            return [];
        }
        $result = $result->toArray();
        return $result;
    }

    public function listorder($id, $listorder)
    {
        $res = $this->getById($id);
        if (!$res) {
            throw new \think\Exception('不存在该记录');
        }
        $data = [
            'listorder' => $listorder
        ];
        try {
            $res = $this->model->updateById($id, $data);
        } catch (\Exception $e) {
            return false;
        }
        return $res;
    }

    public function status($id, $status)
    {
        $res = $this->getById($id);
        if (!$res) {
            throw new \think\Exception('不存在该记录');
        }
        if ($res['status'] == $status) {
            throw new \think\Exception('状态修改前后一致');
        }
        $data = [
            'status' => $status
        ];
        try {
            $res = $this->model->updateById($id, $data);
        } catch (\Exception $e) {
            return false;
        }
        return $res;
    }

    public function getNormalByPid($pid = 0, $field = 'id,name,pid')
    {
        try {
            $res = $this->model->getNormalByPid($pid, $field);
        } catch (\Exception $e) {
            return [];
        }
        $res = $res->toArray();
        return $res;
    }
}