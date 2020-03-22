<?php

namespace app\admin\middleware;

class Auth
{
    public function handle($request, \Closure $next)
    {
        if (empty(session(config('admin.session_admin'))) && !preg_match('/login/', $request->pathinfo())) {
            return redirect(url('login/index'));
        }
        // if (empty(session(config('admin.session_admin'))) && $request->controller() != 'Login') {
        //     return redirect(url('login/index'));
        // }
        return $next($request);
    }

    public function end(\think\Response $response)
    {

    }
}