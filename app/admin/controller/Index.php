<?php

namespace app\admin\controller;

use think\facade\View;

class Index extends AdminBase
{
    public function initialize()
    {
        // if (empty($this->isLogin())) {
        //     return $this->redirect(url('login/index'));
        // }
    }

    public function index()
    {
        return View::fetch();
    }

    public function welcome()
    {
        return View::fetch();
    }
}