<?php
/**
 * @name 生蚝科技RBAC框架(TP)-C-角色管理
 * @package System
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-11-02
 * @version 2019-11-02
 */

namespace app\system\controller;

use app\common\controller\Safe;
use think\Db;

class Role
{	
	public function index()
	{
		checkTabloadToken(inputGet('tabloadToken'),inputGet('tabId',1));
		return view('/role',['tabId'=>inputGet('tabId')]);
	}
	
	
	public function getList()
	{
		//Safe::checkLogin(1);
		
		$list=model('Menu')->select();
		
		returnAjaxData(200,'success',['list'=>$list]);
	}
	
	public function operate()
	{
		$type=inputPost('type',0,1);
		$userId=inputPost('userId',0,1);
		$userData=inputPost('userData',0,1);
		
		$query=Db::name('user')->where('id',$userId)
			->update($userData);
			
			if($query==1) returnAjaxData(200,'success');
			else returnAjaxData(500,'Database error',[],'配置已被删除或为无效的值！');
	}
}
