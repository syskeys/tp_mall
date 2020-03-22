<?php
/**
 * 需要登录的时候继承
 */

namespace app\api\controller;

class AuthBase extends ApiBase
{
    public $accessToken = '';

    public $userId = 0;

    public $username = '';

    public function initialize()
    {
        parent::initialize();
        $this->accessToken = $this->request->header('access-token');
        if (!$this->accessToken || !$this->isLogin()) {
            return $this->show(config('status.not_login'), '没有登录');
        }
    }

    /**
     * 判断用户是否登录
     * @return bool
     */
    public function isLogin()
    {
        $userInfo = cache(config('redis.token_pre') . $this->accessToken);
        if (!$userInfo) {
            return false;
        }
        if (!empty($userInfo['id']) && !empty($userInfo['username'])) {
            $this->userId = $userInfo['id'];
            $this->username = $userInfo['username'];
            return true;
        }
        return false;
    }
}