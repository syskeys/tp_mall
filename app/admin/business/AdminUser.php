<?php

namespace app\admin\business;

use app\common\model\mysql\AdminUser as AdminUserModel;
use think\Exception;

class AdminUser
{
    public static function login($data)
    {
        try {
            $adminUserObj = new AdminUserModel();
            $adminUser = self::getAdminUserByUsername($data['username']);
            if (empty($adminUser)) {
                throw new Exception('该用户不存在');
            }
            if ($adminUser['password'] != md5($data['password'] . '_mall')) {
                throw new Exception('密码错误');
                // return show(config('status.error'), '密码错误');
            }

            //更新登录信息
            $updateData = [
                'last_login_time' => time(),
                'last_login_ip' => request()->ip(),
                'update_time' => time()
            ];
            $res = $adminUserObj->updateById($adminUser['id'], $updateData);
            if (empty($res)) {
                throw new Exception('登录失败');
                // return show(config('status.error'), '登录失败');
            }
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
            // return show(config('status.error'), '内部异常，登录失败');
        }
        //登录信息存储到session中
        session(config('admin.session_admin'), $adminUser);
        return true;
    }

    /**
     * 通过用户名获取用户信息
     * @param $username
     * @return array|bool|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getAdminUserByUsername($username)
    {
        $adminUserObj = new AdminUserModel();
        $adminUser = $adminUserObj->getAdminUserByUsername($username);
        if (empty($adminUser) || $adminUser->status != config('status.mysql.table_normal')) {
            return false;
        }
        $adminUser = $adminUser->toArray();
        return $adminUser;
    }
}