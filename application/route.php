<?php
use think\Route;

Route::get('login','index/login/login',[],[]);
Route::post('toLogin','index/login/toLogin',[],[]);
Route::get('logout','index/login/logout',[],[]);
Route::post('toChangeRole','index/login/toChangeRole',[],[]);
