<?php

namespace app\common\model\mysql;

use think\Model;

class User extends Model
{
    /**
     * 自动生成写入时间
     * @var bool
     */
    protected $autoWriteTimestamp = true;

    public function getUserByPhoneNumber($phoneNumber)
    {
        if (empty($phoneNumber)) {
            return false;
        }
        $where = [
            'phone_number' => $phoneNumber
        ];
        return $this->where($where)->find();
    }

    /**
     * 通过id获取用户数据
     * @param $id
     * @return array|bool|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getUserById($id)
    {
        if (!$id) {
            return false;
        }
        return $this->find($id);
    }

    /**
     * 通过用户名获取用户数据
     * @param $username
     * @return array|bool|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getUserByUsername($username)
    {
        if (!empty($username)) {
            return false;
        }
        $where = [
            'username' => $username
        ];
        return $this->where($where)->find();
    }

    /**
     * 通过主键id更新数据
     * @param $id
     * @param $data
     * @return bool
     */
    public function updateById($id, $data)
    {
        $id = intval($id);
        if (empty($id) || empty($data) || !is_array($data)) {
            return false;
        }
        $where = [
            'id' => $id
        ];
        return $this->where($where)->save($data);
    }
}