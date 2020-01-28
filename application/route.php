<?php
use think\Route;

Route::get('login','index/login/login',[],[]);
Route::post('toLogin','index/login/toLogin',[],[]);
Route::get('logout','index/login/logout',[],[]);
Route::post('toChangeRole','index/login/toChangeRole',[],[]);

/*Route::group('wxwork',function(){
	Route::group('fms',function(){
		Route::any('index','wxwork/fms.index/index',[],[]);
	});
});
*/
