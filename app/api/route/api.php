<?php

use think\facade\Route;

Route::rule('smscode', 'sms/code', 'POST');
Route::resource('user', 'User');