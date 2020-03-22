<?php

namespace app\demo\middleware;

class Check
{
    public function handle($request, \Closure $next)
    {
        dump(1);
        return $next($request);
    }

    public function end(\think\Response $response)
    {
        
    }
}