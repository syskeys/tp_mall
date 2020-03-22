<?php

namespace app\admin\controller;

use think\Exception;
use think\facade\View;
use app\common\model\mysql\AdminUser;

class Login extends AdminBase
{
    public function initialize()
    {
        if ($this->isLogin()) {
            return $this->redirect(url('index/index'));
        }
    }

    public function index()
    {
        return View::fetch();
    }

    public function md5()
    {
        return md5('admin_mall');
    }

    public function check()
    {
        if (!$this->request->isPost()) {
            return show(config('status.error'), '请求方式错误');
        }
        $username = $this->request->param('username', '', 'trim');
        $password = $this->request->param('password', '', 'trim');
        $captcha = $this->request->param('captcha', '', 'trim');
        $data = [
            'username' => $username,
            'password' => $password,
            'captcha' => $captcha
        ];
        $validate = new \app\admin\validate\AdminUser();
        if (!$validate->check($data)) {
            return show(config('status.error'), $validate->getError());
        }
        try {
            $result = \app\admin\business\AdminUser::login($data);
        } catch (Exception $e) {
            return show(config('status.error'), $e->getMessage());
        }
        if ($result) {
            return show(config('status.success'), '登录成功');
        } else {
            return show(config('status.error'), '登录失败');
        }
    }
}