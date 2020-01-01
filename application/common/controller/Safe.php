<?php
/**
 * @name 生蚝科技RBAC框架(TP)-C-安全
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-11-21
 * @version 2019-12-29
 */

namespace app\common\controller;

use think\Session;
use think\Request;

class Safe
{
	public function checkPermission($isAjax=0)
	{
	}
	
	
	public function checkLogin($isAjax=1)
	{
		//return;// debug
		
		if(Session::get('isLogin')!=true){
			if($isAjax==1) returnAjaxData(403001,'User not login',[],'您尚未登录！');
			else gotourl('login');
		}
	}
}
