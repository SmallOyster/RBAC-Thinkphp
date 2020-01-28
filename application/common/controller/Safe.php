<?php
/**
 * @name 生蚝科技RBAC框架(TP)-C-安全
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-11-21
 * @version 2020-01-11
 */

namespace app\common\controller;

use think\Session;
use think\Request;

class Safe
{
	public function checkPermission($isAjax=0)
	{
	}
	
	
	public function checkLogin($isAjax=0)
	{
		//return;// debug
		
		if(Session::get('isLogin')!=true){
			if($isAjax==1 ||  Request::instance()->isAjax()){
				returnAjaxData(403001,'User not login',[],'您尚未登录！');
			}else{
				gotourl('login');
				return false;
			}
		}
	}
	
	
	public function checkSensOprToken($token='')
	{
		$sessToken=Session::get('sensOprToken');
		if($sessToken!=$token){
			returnAjaxData(403002,'Invaild sensitive operation token',[],'接口令牌无效<br>请刷新页面重试');
		}else{
			return true;
		}
	}
}
