<?php

namespace app\admin\validate;

use think\Validate;

class Category extends Validate
{
    protected $rule = [
        'pid' => 'require',
        'name' => 'require'
    ];

    protected $message = [
        'pid' => '父类id不能为空',
        'name' => '分类名称不能为空'
    ];
}